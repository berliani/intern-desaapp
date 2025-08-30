<?php

use App\Models\User;
use App\Models\Penduduk;
use App\Models\Company;
use App\Mail\SendOtpMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Str;
use Twilio\Rest\Client as TwilioClient;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $username = '';
    public string $nik = '';
    public string $email = '';
    public string $telepon = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $verificationMethod = 'email';
    public string $generatedCaptcha = '';
    public string $captcha = '';
    public string $otp = '';
    public bool $otpSent = false;
    public bool $otpVerified = false;
    public bool $nikFound = false;

    public function mount(): void
    {
        $companyFromRequest = request()->get('company');

        if ($companyFromRequest) {
            session(['registration_company_id' => $companyFromRequest->id]);
        }

        if (!session()->has('registration_company_id')) {
            abort(404, 'Halaman pendaftaran tidak valid. Pastikan Anda mengakses melalui subdomain yang benar.');
        }

        $this->generateCaptcha();
    }

    public function generateCaptcha(): void
    {
        $this->generatedCaptcha = Str::random(6);
        session(['captcha' => $this->generatedCaptcha]);
        $this->captcha = '';
    }

    public function updatedNik(string $value): void
    {
        $this->name = '';
        $this->nikFound = false;
        $this->resetErrorBag('nik');

        if (strlen($value) === 16) {
            $companyId = session('registration_company_id');
            $nikSearchHash = hash('sha256', $value);
            $penduduk = Penduduk::where('nik_search_hash', $nikSearchHash)
                ->where('company_id', $companyId)
                ->first();

            if ($penduduk) {
                if ($penduduk->user_id) {
                    $this->addError('nik', 'NIK ini sudah terdaftar dan terhubung dengan akun lain.');
                    return;
                }
                $this->name = $penduduk->nama;
                $this->nikFound = true;
            }
        }
    }

    public function sendVerificationCode(): void
    {
        $this->validate(['captcha' => ['required', 'string']]);

        if (strtolower($this->captcha) !== strtolower(session('captcha'))) {
            $this->addError('captcha', 'Captcha yang Anda masukkan tidak sesuai.');
            $this->generateCaptcha();
            return;
        }

        $this->validate(['nik' => ['required', 'string', 'size:16']]);
        $nikSearchHash = hash('sha256', $this->nik);
        if (User::where('nik_search_hash', $nikSearchHash)->exists()) {
            $this->addError('nik', 'NIK ini sudah digunakan untuk mendaftar akun.');
            return;
        }

        if ($this->verificationMethod === 'email') {
            $validated = $this->validate(['email' => ['required', 'string', 'lowercase', 'email', 'max:255']]);
            $emailSearchHash = hash('sha256', strtolower($validated['email']));
            if (User::where('email_search_hash', $emailSearchHash)->exists()) {
                $this->addError('email', 'Alamat email ini sudah terdaftar.');
                return;
            }
            $this->sendOtpByEmail($validated['email']);
        } else {
            $validated = $this->validate(['telepon' => ['required', 'string', 'regex:/^(\+62|62|0)8[0-9]{9,15}$/']]);
            $normalizedPhone = $this->normalizePhoneNumber($validated['telepon']);
            $teleponSearchHash = hash('sha256', $normalizedPhone);
            if (User::where('telepon_search_hash', $teleponSearchHash)->exists()) {
                $this->addError('telepon', 'Nomor telepon ini sudah terdaftar.');
                return;
            }
            $this->sendOtpByWhatsApp($validated['telepon']);
        }

        if (empty($this->getErrorBag()->get('telepon'))) {
            $this->otpSent = true;
            session()->flash('status', 'Kode verifikasi telah dikirim!');
        }
    }

    public function verifyOtp(): void
    {
        $this->validate(['otp' => ['required', 'numeric', 'digits:6']]);

        if (session('otp') == $this->otp) {
            $this->otpVerified = true;
            session()->forget(['otp', 'captcha']);
            session()->flash('status', 'Verifikasi berhasil! Silakan lengkapi pendaftaran.');
        } else {
            $this->addError('otp', 'Kode OTP tidak valid.');
        }
    }

    public function register(): void
    {
        if (!$this->otpVerified) {
            $this->addError('otp', 'Harap selesaikan verifikasi terlebih dahulu.');
            return;
        }

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'nik' => ['required', 'string', 'size:16'],
            'email' => ['required_if:verificationMethod,email', 'nullable', 'string', 'lowercase', 'email', 'max:255'],
            'telepon' => ['required_if:verificationMethod,whatsapp', 'nullable', 'string'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Rules\Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-) dan (_).',
        ]);

        $companyId = session('registration_company_id');

        if (!$companyId || !Company::find($companyId)) {
            session()->flash('error', 'Sesi pendaftaran tidak valid. Silakan muat ulang halaman dan coba lagi.');
            return;
        }

        $key = hex2bin(env('IMS_ENCRYPTION_KEY'));
        if (!$key) {
            throw new \Exception("Kunci enkripsi IMS tidak valid atau tidak ada di .env");
        }
        $encryptor = new \App\IMS\EnkripsiIMS($key);

        $nikSearchHash = hash('sha256', $validated['nik']);
        $penduduk = Penduduk::where('nik_search_hash', $nikSearchHash)
            ->where('company_id', $companyId)
            ->first();

        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'penduduk_id' => $penduduk?->id,
            'company_id' => $companyId,
            'nik_encrypted' => $encryptor->encrypt($validated['nik']),
            'nik_search_hash' => $nikSearchHash,
        ];

        if (!empty($validated['email'])) {
            $userData['email_encrypted'] = $encryptor->encrypt($validated['email']);
            $userData['email_search_hash'] = hash('sha256', strtolower($validated['email']));
        }

        if (!empty($validated['telepon'])) {
            $normalizedPhone = $this->normalizePhoneNumber($validated['telepon']);
            $userData['telepon_encrypted'] = $encryptor->encrypt($normalizedPhone);
            $userData['telepon_search_hash'] = hash('sha256', $normalizedPhone);
        }

        $user = User::create($userData);

        $redirectTo = '';
        if ($penduduk) {
            $user->assignRole('warga');
            $penduduk->update(['user_id' => $user->id]);
            session()->flash('status', 'Pendaftaran berhasil! Akun Anda sudah terverifikasi sebagai warga.');
            $redirectTo = '/dashboard';
        } else {
            $user->assignRole('unverified');
            session()->flash('status', 'Pendaftaran berhasil! NIK Anda belum terdaftar, silakan lakukan verifikasi data.');
            $redirectTo = '/verifikasi-data';
        }

        event(new Registered($user));
        Auth::login($user);

        session()->forget('registration_company_id');

        $this->redirect($redirectTo, navigate: true);
    }

    private function sendOtpByEmail(string $email)
    {
        $companyId = session('registration_company_id');
        $company = Company::find($companyId);

        $otpCode = random_int(100000, 999999);
        session(['otp' => $otpCode]);
        Mail::to($email)->send(new SendOtpMail($otpCode, $company));
    }

    private function sendOtpByWhatsApp(string $phoneNumber)
    {
        $otpCode = random_int(100000, 999999);
        session(['otp' => $otpCode]);
        $normalizedPhone = $this->normalizePhoneNumber($phoneNumber);

        $sid    = env('TWILIO_SID');
        $token  = env('TWILIO_AUTH_TOKEN');
        $from   = env('TWILIO_WHATSAPP_FROM');

        if (!$sid || !$token || !$from) {
            $this->addError('telepon', 'Konfigurasi layanan WhatsApp tidak lengkap. Harap hubungi admin.');
            return;
        }

        $client = new TwilioClient($sid, $token);

        try {
            $companyId = session('registration_company_id');
            $company = Company::find($companyId);
            $companyName = $company ? $company->name : 'Portal Desa';

            $client->messages->create('whatsapp:+' . $normalizedPhone, [
                "from" => $from,
                "body" => "Kode verifikasi pendaftaran Anda di {$companyName} adalah: {$otpCode}"
            ]);
        } catch (\Twilio\Exceptions\TwilioException $e) {
            $this->addError('telepon', 'Gagal mengirim OTP ke nomor WhatsApp Anda. Pastikan nomor sudah benar dan terdaftar di WhatsApp.');
        }
    }

    private function normalizePhoneNumber(?string $phoneNumber): ?string
    {
        if (empty($phoneNumber)) return null;
        $number = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (substr($number, 0, 1) === '0') {
            return '62' . substr($number, 1);
        }
        if (substr($number, 0, 2) !== '62') {
            return '62' . $number;
        }
        return $number;
    }
};?>

<div>
    <div class="mb-6 relative">
        <div class="absolute -top-10 -left-10 w-20 h-20 bg-emerald-100 rounded-full opacity-50"></div>
        <div class="absolute -bottom-4 right-0 w-12 h-12 bg-emerald-100 rounded-full opacity-30"></div>
        <div class="relative">
            <div class="flex items-center gap-2 mb-3">
                <div class="flex items-center bg-gradient-to-r from-emerald-600 to-emerald-400 text-white text-xs font-medium py-1 px-3 rounded-full shadow-sm">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    <span>Portal Desa Digital</span>
                </div>
                <div class="hidden sm:block bg-gray-200 h-px flex-grow mx-2"></div>
                <div class="hidden sm:flex gap-1 text-xs text-gray-500">
                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Pendaftaran Akun</span>
                </div>
            </div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-emerald-700 to-emerald-500 bg-clip-text text-transparent">
                Daftar Akun Baru
            </h2>
            <p class="text-gray-600 mt-2 flex items-center">
                <svg class="w-4 h-4 mr-1 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg>
                Lengkapi data untuk membuat akun
            </p>
        </div>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="register" class="space-y-4">
        @csrf
        @if(!$otpVerified)
        <div class="space-y-4">
            <div>
                <x-input-label for="nik">Nomor Induk Kependudukan (NIK) <span class="text-red-500">*</span></x-input-label>
                <x-text-input wire:model.live.debounce.500ms="nik" id="nik" type="text" class="block mt-1 w-full" required maxlength="16" placeholder="Masukkan 16 digit NIK" :disabled="$otpSent" />
                @if($nikFound)
                <p class="text-xs text-green-600 mt-1">✓ NIK ditemukan, nama akan terisi otomatis.</p>
                @endif
                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
            </div>

            <div>
                <x-input-label>Metode Verifikasi <span class="text-red-500">*</span></x-input-label>
                <div class="flex gap-4 mt-1">
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="verificationMethod" value="email" name="verification_method" class="form-radio text-emerald-600">
                        <span class="ml-2 text-sm text-gray-700">Email</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" wire:model.live="verificationMethod" value="whatsapp" name="verification_method" class="form-radio text-emerald-600">
                        <span class="ml-2 text-sm text-gray-700">WhatsApp</span>
                    </label>
                </div>
            </div>

            @if($verificationMethod === 'email')
            <div>
                <x-input-label for="email">Alamat Email <span class="text-red-500">*</span></x-input-label>
                <x-text-input wire:model="email" id="email" type="email" class="block mt-1 w-full" required :disabled="$otpSent" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            @else
            <div>
                <x-input-label for="telepon">Nomor Telepon (WhatsApp) <span class="text-red-500">*</span></x-input-label>
                <x-text-input wire:model="telepon" id="telepon" type="tel" class="block mt-1 w-full" required :disabled="$otpSent" />
                <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
            </div>
            @endif

            @if(!$otpSent)
            <div>
                <label for="captcha" class="block text-sm font-medium text-gray-700 mt-4">Verifikasi Captcha <span class="text-red-500">*</span></label>
                <div class="flex items-center space-x-4 mt-1">
                    <div class="flex items-center justify-around w-48 h-16 px-2 bg-gray-200 border rounded-md overflow-hidden"
                        style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23d4d4d8\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">

                        @if (!empty($generatedCaptcha))
                        @foreach (str_split($generatedCaptcha) as $char)
                        @php
                        $hue = rand(0, 360);
                        $saturation = rand(70, 95);
                        $lightness = rand(25, 45);
                        $color = "hsl({$hue}, {$saturation}%, {$lightness}%)";
                        $rotation = rand(-25, 25);
                        $font_size = rand(22, 32);
                        $top_offset = rand(-5, 5);
                        $font_weight = rand(400, 800);
                        @endphp
                        <span class="select-none"
                            style="transform: rotate({{ $rotation }}deg); font-size: {{ $font_size }}px; font-weight: {{ $font_weight }}; position: relative; top: {{ $top_offset }}px; color: {{ $color }}; font-family: 'Courier New', Courier, monospace;">
                            {{ $char }}
                        </span>
                        @endforeach
                        @endif
                    </div>
                    <button type="button" wire:click="generateCaptcha" title="Refresh Captcha"
                        class="p-2 text-gray-600 bg-white border rounded-md hover:bg-gray-50">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.664 0l3.181-3.183m-4.991-2.695v-2.257a2.25 2.25 0 00-2.25-2.25H10.5a2.25 2.25 0 00-2.25 2.25v2.257m1.5-10.128l1.272 1.272M21 21l-1.272-1.272" />
                        </svg>
                    </button>
                </div>
                <x-text-input wire:model="captcha" id="captcha" type="text" class="block mt-2 w-full"
                    placeholder="Masukkan captcha di atas" required />
                <x-input-error :messages="$errors->get('captcha')" class="mt-2" />
            </div>
            <button type="button" wire:click="sendVerificationCode" class="w-full justify-center inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700">
                Kirim Kode Verifikasi (OTP)
            </button>
            @endif

            @if($otpSent)
            <div>
                <x-input-label for="otp">Kode OTP <span class="text-red-500">*</span></x-input-label>
                <x-text-input wire:model="otp" id="otp" type="text" class="block mt-1 w-full" required placeholder="Masukkan 6 digit kode OTP" />
                <x-input-error :messages="$errors->get('otp')" class="mt-2" />
            </div>
            <button type="button" wire:click="verifyOtp" class="w-full justify-center inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700">
                Verifikasi OTP
            </button>
            @endif
        </div>
        @endif

        @if($otpVerified)
        <div class="space-y-4">
            <div>
                <x-input-label for="name">Nama Lengkap (sesuai KTP) <span class="text-red-500">*</span></x-input-label>
                <x-text-input wire:model="name" id="name" type="text" class="block mt-1 w-full" required :disabled="$nikFound" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="username">Username <span class="text-red-500">*</span></x-input-label>
                <x-text-input wire:model="username" id="username" type="text" class="block mt-1 w-full" required placeholder="Buat username unik" />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password">Password <span class="text-red-500">*</span></x-input-label>
                <div class="relative" x-data="{ showPassword: false }">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <x-text-input
                        wire:model="password"
                        id="password"
                        class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        x-bind:type="showPassword ? 'text' : 'password'" />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" @click="showPassword = !showPassword">
                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="showPassword" class="h-5 w-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation">Konfirmasi Password <span class="text-red-500">*</span></x-input-label>
                <div class="relative" x-data="{ showConfirmPassword: false }">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <x-text-input
                        wire:model="password_confirmation"
                        id="password_confirmation"
                        class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password"
                        x-bind:type="showConfirmPassword ? 'text' : 'password'" />
                    <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" @click="showConfirmPassword = !showConfirmPassword">
                        <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="showConfirmPassword" class="h-5 w-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end pt-2">
                <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Daftar') }}
                </button>
            </div>
        </div>
        @endif

        <div class="text-center mt-6">
            <a class="text-sm font-medium text-emerald-600 hover:text-emerald-700" href="{{ route('login') }}" wire:navigate>
                {{ __('Sudah punya akun?') }}
            </a>
        </div>
    </form>
</div>
