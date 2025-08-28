<?php

namespace App\Livewire\Pages\Auth;

use App\IMS\EnkripsiIMS;
use Livewire\Component;
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

        $this->otpSent = true;
        session()->flash('status', 'Kode verifikasi telah dikirim!');
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
            // 2. Siapkan encryptor
            $key = hex2bin(env('IMS_ENCRYPTION_KEY'));
            if (!$key) { throw new \Exception("Kunci enkripsi IMS tidak valid."); }
            $encryptor = new EnkripsiIMS($key);
            $company = Company::create([
                'name' => 'Desa ' . $validated['name'],
                'subdomain' => 'desa-' . Str::slug($validated['name']) . '-' . Str::lower(Str::random(4)),
            ]);

            $userData = [
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'company_id' => $company->id,
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

            $adminUser = User::create($userData);

            $adminUser->assignRole('admin');
            event(new Registered($adminUser));
            Auth::login($adminUser);
        });

        return $this->redirect(route('desa.profil.create'));
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
        $client = new TwilioClient($sid, $token);

        try {
            $client->messages->create('whatsapp:+' . $normalizedPhone, [
                "from" => $from,
                "body" => "Kode verifikasi pendaftaran desa Anda adalah: {$otpCode}"
            ]);
        } catch (\Twilio\Exceptions\TwilioException $e) {
            session()->flash('error', 'Gagal mengirim OTP WhatsApp: ' . $e->getMessage());
            $this->otpSent = false;
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

    public function render()
    {
        return view('livewire.pages.auth.register-desa');
    }
}
