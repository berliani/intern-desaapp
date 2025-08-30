<?php

use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Twilio\Rest\Client as TwilioClient;

new #[Layout('layouts.guest')] class extends Component
{
    public string $verificationMethod = 'email';
    public string $email = '';
    public string $telepon = '';

    public function sendVerification(): void
    {
        if ($this->verificationMethod === 'email') {
            $this->sendOtpByEmail();
        } else {
            $this->sendOtpByWhatsApp();
        }
    }

    private function sendOtpByEmail(): void
    {
        $validated = $this->validate(['email' => ['required', 'email']]);
        $emailSearchHash = hash('sha256', strtolower($validated['email']));
        $user = User::where('email_search_hash', $emailSearchHash)->first();

        if (!$user) {
            $this->addError('email', __('passwords.user'));
            return;
        }

        $otpCode = random_int(100000, 999999);
        Mail::to($user->email)->send(new SendOtpMail($otpCode, $user->company, 'reset'));
        
        $this->storeSessionAndRedirect($user, $otpCode);
    }

    private function sendOtpByWhatsApp(): void
    {
        $validated = $this->validate(['telepon' => ['required', 'string', 'regex:/^(\+62|62|0)8[0-9]{9,15}$/']]);
        
        $normalizedPhone = $this->normalizePhoneNumber($validated['telepon']);
        $teleponSearchHash = hash('sha256', $normalizedPhone);
        $user = User::where('telepon_search_hash', $teleponSearchHash)->first();

        if (!$user) {
            $this->addError('telepon', 'Nomor WhatsApp tidak terdaftar.');
            return;
        }

        $otpCode = random_int(100000, 999999);
        
        try {
            $sid    = env('TWILIO_SID');
            $token  = env('TWILIO_AUTH_TOKEN');
            $from   = env('TWILIO_WHATSAPP_FROM');
            $client = new TwilioClient($sid, $token);

            $client->messages->create('whatsapp:+' . $normalizedPhone, [
                "from" => $from,
                "body" => "Kode reset password Anda adalah: {$otpCode}. Jangan berikan kode ini kepada siapapun."
            ]);

            $this->storeSessionAndRedirect($user, $otpCode);

        } catch (\Twilio\Exceptions\TwilioException $e) {
            $this->addError('telepon', 'Gagal mengirim kode OTP. Silakan coba lagi.');
        }
    }
    
    private function storeSessionAndRedirect(User $user, int $otpCode): void
    {
        session([
            'password_reset_otp' => $otpCode,
            'password_reset_user_id' => $user->id,
            'password_reset_otp_expires_at' => now()->addMinutes(10)
        ]);
        
        $this->redirect(route('password.reset', ['token' => 'otp-flow']), navigate: true);
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
}; ?>

<div>
    <!-- Modern Header -->
    <div class="mb-6 relative">
        <div class="absolute -top-10 -left-10 w-20 h-20 bg-emerald-100 rounded-full opacity-50"></div>
        <div class="absolute -bottom-4 right-0 w-12 h-12 bg-emerald-100 rounded-full opacity-30"></div>

        <div class="relative">
            <div class="flex items-center gap-2 mb-3">
                <div class="flex items-center bg-gradient-to-r from-emerald-600 to-emerald-400 text-white text-xs font-medium py-1 px-3 rounded-full shadow-sm">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    <span>Portal Desa Digital</span>
                </div>
                <div class="hidden sm:block bg-gray-100 h-px flex-grow mx-2"></div>
                <div class="hidden sm:flex gap-1 text-xs text-gray-500">
                    <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                    <span>Lupa Password</span>
                </div>
            </div>

            <h2 class="text-2xl font-bold bg-gradient-to-r from-emerald-700 to-emerald-500 bg-clip-text text-transparent">
                Lupa Password?
            </h2>
            <p class="text-gray-600 mt-2 flex items-center">
                <svg class="w-4 h-4 mr-1 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                Pilih metode untuk mendapatkan kode verifikasi (OTP)
            </p>
        </div>
    </div>

    <x-auth-session-status class="mb-4 p-4 text-sm rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200" :status="session('status')" />

    <form wire:submit="sendVerification" class="space-y-4">
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

        @if ($verificationMethod === 'email')
            <div>
                <x-input-label for="email">Email <span class="text-red-500">*</span></x-input-label>
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus placeholder="nama@email.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        @else
            <div>
                <x-input-label for="telepon">Nomor WhatsApp <span class="text-red-500">*</span></x-input-label>
                <x-text-input wire:model="telepon" id="telepon" class="block mt-1 w-full" type="tel" name="telepon" required autofocus placeholder="08xxxxxxxxxx" />
                <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
            </div>
        @endif

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm font-medium text-emerald-600 hover:text-emerald-700 flex items-center" href="{{ route('login') }}" wire:navigate>
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                {{ __('Kembali ke Login') }}
            </a>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700">
                {{ __('Kirim Kode OTP') }}
            </button>
        </div>
    </form>
</div>
