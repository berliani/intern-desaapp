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

    // Properti untuk Verifikasi Email
    public string $generatedCaptcha = '';
    public string $captcha = '';
    public string $otp = '';

    // State untuk mengontrol alur UI
    public bool $emailVerificationSent = false;
    public bool $emailVerified = false;

    /**
     * Inisialisasi komponen dengan membuat captcha pertama kali.
     */
    public function mount(): void
    {
        $this->generateCaptcha();

        // Cek kalau sebelumnya sudah terverifikasi, ambil dari session
        if (session('email_verified')) {
            $this->emailVerified = true;
        }
    }

    /**
     * Membuat slug subdomain secara otomatis.
     */
    public function updatedNamaDesa($value): void
    {
        $this->subdomain = Str::slug($value);
    }

    /**
     * Membuat captcha baru.
     */
    public function generateCaptcha(): void
    {
        $this->generatedCaptcha = Str::random(6);
        session(['captcha' => $this->generatedCaptcha]);
    }

    /**
     * Langkah 1: Kirim Kode Verifikasi (OTP)
     */
    public function sendVerificationCode(): void
    {
        // Kalau sudah pernah kirim, jangan izinkan ubah email
        if ($this->emailVerificationSent) {
            $this->addError('admin_email', 'Email sudah dikirim OTP, tidak bisa diubah lagi.');
            return;
        }

        $this->validate([
            'admin_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email'],
            'captcha' => ['required', 'string'],
        ]);

        if (strtolower($this->captcha) !== strtolower(session('captcha'))) {
            $this->addError('captcha', 'Captcha yang Anda masukkan tidak sesuai.');
            $this->generateCaptcha();
            return;
        }

        $generatedOtp = random_int(100000, 999999);

        // Simpan OTP & email di session untuk verifikasi
        session([
            'otp' => $generatedOtp,
            'verification_email' => $this->admin_email,
        ]);

        // TODO: kirim email OTP ke user
        Mail::to($this->admin_email)->send(new SendOtpMail($generatedOtp));

        // Untuk demo: tampilkan OTP di flash message
        session()->flash('otp_info', 'Kode OTP Anda (demo): ' . $generatedOtp);

        $this->emailVerificationSent = true;

        $this->addError('otp', 'Kode verifikasi telah dikirim ke email Anda.');
    }

    /**
     * Langkah 2: Verifikasi OTP
     */
    public function verifyOtp(): void
    {
        $this->validate([
            'otp' => ['required', 'numeric'],
        ]);

        if (
            session('otp') == $this->otp &&
            session('verification_email') == $this->admin_email
        ) {
            $this->emailVerified = true;

            // Simpan status verifikasi di session supaya persist
            session(['email_verified' => true]);

            // Hapus data OTP & captcha
            session()->forget(['otp', 'verification_email', 'captcha']);

            session()->flash('email_verified_success', 'Email berhasil diverifikasi! Silakan lengkapi pendaftaran.');
        } else {
            $this->addError('otp', 'Kode OTP tidak valid.');
        }
    }

    /**
     * Langkah 3: Pendaftaran Desa
     */
    public function registerDesa(): void
    {
        if (!$this->emailVerified) {
            $this->addError('otp', 'Harap verifikasi email Anda terlebih dahulu.');
            return;
        }

        $validated = $this->validate([
            'nama_desa' => ['required', 'string', 'max:100', 'unique:' . Company::class . ',name'],
            'subdomain' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:' . Company::class . ',subdomain'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email'],
            'admin_password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($validated) {
            $company = Company::create([
                'name' => $validated['nama_desa'],
                'subdomain' => $validated['subdomain'],
            ]);

            $adminUser = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'company_id' => $company->id,
            ]);

            $adminUser->assignRole('admin');

            event(new Registered($adminUser));

            Auth::login($adminUser);
        });

        // Setelah berhasil, hapus status verifikasi biar bersih
        session()->forget('email_verified');

        $this->redirect('/desa/dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.pages.auth.register-desa');
    }
}
