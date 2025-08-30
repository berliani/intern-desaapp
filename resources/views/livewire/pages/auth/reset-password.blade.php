<?php

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public ?string $token = null;
    public string $email = '';
    public string $otp = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $isOtpFlow = false;
    public ?int $userId = null;

    public function mount(?string $token = null): void
    {
        if ($token && $token !== 'otp-flow') {
            $this->isOtpFlow = false;
            $this->token = $token;
            $this->email = request()->string('email');
        } 
        elseif (session()->has('password_reset_user_id')) {
            $this->isOtpFlow = true;
            $this->userId = session('password_reset_user_id');
            $user = User::find($this->userId);
            
            if (!$user || now()->isAfter(session('password_reset_otp_expires_at'))) {
                session()->flash('error', 'Sesi reset password tidak valid atau telah kadaluwarsa.');
                $this->redirectRoute('password.request', navigate: true);
                return;
            }
            $this->email = $user->email ?? $user->telepon ?? 'Akun Anda';
        } 
        else {
            $this->redirectRoute('password.request', navigate: true);
        }
    }

    public function resetPassword(): void
    {
        if ($this->isOtpFlow) {
            $this->resetPasswordWithOtp();
        } else {
            $this->resetPasswordWithToken();
        }
    }

    private function resetPasswordWithToken(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $tokenData = DB::table('password_reset_tokens')->where('email', $this->email)->first();

        if (!$tokenData || !Hash::check($this->token, $tokenData->token)) {
            $this->addError('email', __('passwords.token'));
            return;
        }

        $emailSearchHash = hash('sha256', strtolower($this->email));
        $user = User::where('email_search_hash', $emailSearchHash)->first();

        if (!$user) {
            $this->addError('email', __('passwords.user'));
            return;
        }

        $user->forceFill(['password' => Hash::make($this->password), 'remember_token' => Str::random(60)])->save();
        event(new PasswordReset($user));
        DB::table('password_reset_tokens')->where('email', $this->email)->delete();

        Session::flash('status', __('passwords.reset'));
        $this->redirectRoute('login', navigate: true);
    }

    private function resetPasswordWithOtp(): void
    {
        $this->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if (session('password_reset_otp') != $this->otp) {
            $this->addError('otp', 'Kode OTP yang Anda masukkan tidak valid.');
            return;
        }
        
        if (now()->isAfter(session('password_reset_otp_expires_at'))) {
            $this->addError('otp', 'Kode OTP telah kadaluwarsa. Silakan minta kode baru.');
            return;
        }

        $user = User::find($this->userId);

        if (!$user) {
             $this->addError('otp', 'Gagal menemukan pengguna terkait. Sesi mungkin tidak valid.');
             return;
        }
        
        $user->forceFill(['password' => Hash::make($this->password), 'remember_token' => Str::random(60)])->save();
        event(new PasswordReset($user));

        session()->forget(['password_reset_otp', 'password_reset_user_id', 'password_reset_otp_expires_at']);
        session()->flash('status', __('passwords.reset'));
        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <div class="mb-6 relative">
        <div class="absolute -top-10 -left-10 w-20 h-20 bg-emerald-100 rounded-full opacity-50"></div>
        <div class="absolute -bottom-4 right-0 w-12 h-12 bg-emerald-100 rounded-full opacity-30"></div>
        <div class="relative">
            <div class="flex items-center gap-2 mb-3">
                <div class="flex items-center bg-gradient-to-r from-emerald-600 to-emerald-400 text-white text-xs font-medium py-1 px-3 rounded-full shadow-sm">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                    <span>Reset Password</span>
                </div>
            </div>
            <h2 class="text-2xl font-bold bg-gradient-to-r from-emerald-700 to-emerald-500 bg-clip-text text-transparent">
                Atur Ulang Password Anda
            </h2>
            <p class="text-gray-600 mt-2">
                @if($isOtpFlow)
                    Masukkan kode OTP yang telah kami kirim dan buat password baru.
                @else
                    Buat password baru untuk akun Anda.
                @endif
            </p>
        </div>
    </div>

    <form wire:submit="resetPassword" class="space-y-4">
        <div>
            <x-input-label for="email">Akun Terkait <span class="text-red-500">*</span></x-input-label>
            <x-text-input wire:model="email" id="email" class="block w-full mt-1 bg-gray-100 cursor-not-allowed" type="text" name="email" required readonly />
        </div>

        @if($isOtpFlow)
        <div>
            <x-input-label for="otp">Kode OTP <span class="text-red-500">*</span></x-input-label>
            <x-text-input wire:model="otp" id="otp" class="block w-full mt-1" type="text" name="otp" required autofocus placeholder="Masukkan 6 digit kode OTP" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>
        @endif

        <div>
            <x-input-label for="password">Password Baru <span class="text-red-500">*</span></x-input-label>
            <div class="relative" x-data="{ showPassword: false }">
                <x-text-input wire:model="password" id="password" class="block w-full mt-1" name="password" required autocomplete="new-password" placeholder="Min. 8 karakter, angka, simbol" x-bind:type="showPassword ? 'text' : 'password'" />
                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" @click="showPassword = !showPassword">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="showPassword" class="h-5 w-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation">Konfirmasi Password Baru <span class="text-red-500">*</span></x-input-label>
            <div class="relative" x-data="{ showConfirmPassword: false }">
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block w-full mt-1" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru" x-bind:type="showConfirmPassword ? 'text' : 'password'" />
                 <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" @click="showConfirmPassword = !showConfirmPassword">
                    <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="showConfirmPassword" class="h-5 w-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end pt-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</div>
