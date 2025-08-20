<?php

namespace App\Livewire\Pages\Auth;

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
    // Properti Informasi Desa
    public string $nama_desa = '';
    public string $subdomain = '';

    // Properti Informasi Admin
    public string $admin_name = '';
    public string $admin_email = '';
    public string $admin_password = '';
    public string $admin_password_confirmation = '';
    public string $telepon = '';

    // Properti untuk Verifikasi
    public string $verificationMethod = 'email'; // 'email' atau 'whatsapp'
    public string $generatedCaptcha = '';
    public string $captcha = '';
    public string $otp = '';

    // State untuk mengontrol alur UI
    public bool $otpSent = false;
    public bool $otpVerified = false;

    public function mount(): void
    {
        $this->generateCaptcha();
    }

    public function updatedNamaDesa($value): void
    {
        $this->subdomain = Str::slug($value);
        // Mengirim event ke browser untuk memindahkan fokus
        $this->dispatch('subdomain-filled');
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
            $validated = $this->validate(['admin_email' => ['required', 'email', 'unique:users,email']]);
            $this->sendOtpByEmail($validated['admin_email']);
        } else { // whatsapp
            $validated = $this->validate(['telepon' => ['required', 'string', 'unique:users,telepon', 'regex:/^(\+62|62|0)8[0-9]{9,15}$/']]);
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

    public function register(): void
    {
        if (!$this->otpVerified) {
            $this->addError('otp', 'Harap selesaikan verifikasi terlebih dahulu.');
            return;
        }

        $validated = $this->validate([
            'nama_desa' => ['required', 'string', 'max:100', 'unique:companies,name'],
            'subdomain' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:companies,subdomain'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required_if:verificationMethod,email', 'nullable', 'email', 'max:255', 'unique:users,email'],
            'telepon' => ['required_if:verificationMethod,whatsapp', 'nullable', 'string', 'unique:users,telepon'],
            'admin_password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($validated) {
            $company = Company::create([
                'name' => $validated['nama_desa'],
                'subdomain' => $validated['subdomain'],
            ]);

            $adminUser = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'] ?? "user-" . Str::random(5) . "@{$validated['subdomain']}.desa",
                'telepon' => $this->normalizePhoneNumber($validated['telepon'] ?? null),
                'password' => Hash::make($validated['admin_password']),
                'company_id' => $company->id,
            ]);

            $adminUser->assignRole('admin');
            event(new Registered($adminUser));
            Auth::login($adminUser);
        });

        $this->redirect(route('desa.profil.create'));
    }

    // --- Helper Methods ---
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
