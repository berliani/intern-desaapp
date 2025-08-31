<?php

namespace App\Livewire\Pages\Auth;

use App\Models\User;
use App\Models\Company;
use App\Mail\SendOtpMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;
use Twilio\Rest\Client as TwilioClient;

#[Layout('layouts.guest')]
class RegisterDesa extends Component
{
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $telepon = '';
    public string $verificationMethod = 'email';
    public string $generatedCaptcha = '';
    public string $captcha = '';
    public string $otp = '';
    public bool $otpSent = false;
    public bool $otpVerified = false;

    public function mount(): void
    {
        $this->generateCaptcha();
    }

    public function generateCaptcha(): void
    {
        $this->generatedCaptcha = Str::random(6);
        session(['captcha' => $this->generatedCaptcha]);
        $this->captcha = '';
    }

    public function sendVerificationCode(): void
    {
        $this->validate(['captcha' => ['required', 'string']]);

        if (strtolower($this->captcha) !== strtolower(session('captcha'))) {
            $this->addError('captcha', 'Captcha yang Anda masukkan tidak sesuai.');
            $this->generateCaptcha();
            return;
        }

        if ($this->verificationMethod === 'email') {
            $validated = $this->validate(['email' => ['required', 'email', 'max:255']]);

            // Cek keunikan secara manual menggunakan hash dari Model User
            $emailSearchHash = User::hashForSearch(strtolower($validated['email']));
            if (User::where('email_search_hash', $emailSearchHash)->exists()) {
                $this->addError('email', 'Alamat email ini sudah terdaftar.');
                return;
            }

            $this->sendOtpByEmail($validated['email']);
        } else {
            // <<< PERBAIKAN: Menghapus blok validasi telepon yang duplikat
            $validated = $this->validate(['telepon' => ['required', 'string', 'regex:/^(\+62|62|0)8[0-9]{9,15}$/']]);
            $normalizedPhone = $this->normalizePhoneNumber($validated['telepon']);

            // Cek keunikan secara manual menggunakan hash dari Model User
            $teleponSearchHash = User::hashForSearch($normalizedPhone);
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

    public function register()
    {
        if (!$this->otpVerified) {
            $this->addError('otp', 'Harap selesaikan verifikasi terlebih dahulu.');
            return;
        }

        // <<< PERBAIKAN: Menghapus duplikasi aturan validasi email dan telepon
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email' => ['required_if:verificationMethod,email', 'nullable', 'email', 'max:255'],
            'telepon' => ['required_if:verificationMethod,whatsapp', 'nullable', 'string'],
            'password' => [
                'required',
                'string',
                'confirmed',
                Rules\Password::min(8)->mixedCase()->numbers()->symbols()
            ],
        ], [
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'username.unique' => 'Username ini sudah digunakan.',
        ]);

        DB::transaction(function () use ($validated) {
            $company = Company::create([
                'name' => 'Desa ' . $validated['name'],
                'subdomain' => 'desa-' . Str::slug($validated['name']) . '-' . Str::lower(Str::random(4)),
            ]);
            
            // <<< PERBAIKAN: Menyerahkan enkripsi ke Model User secara otomatis
            // Model User akan menangani enkripsi dan hashing saat `User::create()` dipanggil.
            $userData = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => $validated['password'], // Model akan hash ini secara otomatis
                'company_id' => $company->id,
                'email' => $validated['email'] ?? null,
                'telepon' => $this->normalizePhoneNumber($validated['telepon'] ?? null),
            ];

            $adminUser = User::create($userData);

            $adminUser->assignRole('admin');
            event(new Registered($adminUser));
            Auth::login($adminUser);
        }); // <<< PERBAIKAN UTAMA: Menambahkan `);` yang hilang untuk menutup DB::transaction

        // Redirect harus di luar transaction closure
        return $this->redirect(route('filament.admin.pages.dashboard'), navigate: true);
    }

    private function sendOtpByEmail(string $email)
    {
        $otpCode = random_int(100000, 999999);
        session(['otp' => $otpCode]);
        Mail::to($email)->send(new SendOtpMail($otpCode));
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
            $client->messages->create('whatsapp:+' . $normalizedPhone, [
                "from" => $from,
                "body" => "Kode verifikasi pendaftaran desa Anda adalah: {$otpCode}"
            ]);
        } catch (\Twilio\Exceptions\TwilioException $e) {
            $this->addError('telepon', 'Gagal mengirim OTP. Pastikan nomor benar dan terdaftar WhatsApp.');
        }
    }

    private function normalizePhoneNumber(?string $phoneNumber): ?string
    {
        if (empty($phoneNumber)) return null;
        $number = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }
        if (!str_starts_with($number, '62')) {
            return '62' . $number;
        }
        return $number;
    }

    public function render()
    {
        return view('livewire.pages.auth.register-desa');
    }
}