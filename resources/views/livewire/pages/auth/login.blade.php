<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;

new #[Layout('layouts.guest')] class extends Component {
    public string $loginField = '';
    public string $password = '';
    public bool $remember = false;
    public string $captcha = '';
    public string $generatedCaptcha = '';

    public function mount(): void
    {
        $this->generateCaptcha();
    }

    public function generateCaptcha(): void
    {
        $randomString = Str::random(6);
        $this->generatedCaptcha = $randomString;
        Session::put('captcha', $randomString);
        $this->captcha = '';
    }

    public function login()
    {
        try {
            $this->validate(
                [
                    'loginField' => 'required|string',
                    'password' => 'required|string',
                    'captcha' => 'required|string|in:' . session('captcha'),
                ],
                [
                    'loginField.required' => 'Username atau Email wajib diisi.',
                    'password.required' => 'Password wajib diisi.',
                    'captcha.in' => 'Kode Captcha yang Anda masukkan salah.',
                    'captcha.required' => 'Kode Captcha wajib diisi.',
                ],
            );
        } catch (ValidationException $e) {
            if ($e->validator->errors()->has('captcha')) {
                $this->generateCaptcha();
            }
            throw $e;
        }

        $this->ensureIsNotRateLimited();

        $isEmail = filter_var($this->loginField, FILTER_VALIDATE_EMAIL);
        $user = null;

        if ($isEmail) {
            $emailSearchHash = hash('sha256', strtolower($this->loginField));
            $user = User::where('email_search_hash', $emailSearchHash)->first();
        } else {
            $user = User::where('username', $this->loginField)->first();
        }

        if (!$user || !Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());
            $this->addError('loginField', trans('auth.failed'));
            $this->generateCaptcha();
            return;
        }

        Auth::login($user, $this->remember);

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $loggedInUser = auth()->user();
        if ($loggedInUser->hasAnyRole(['super_admin', 'admin'])) {
            return redirect()->to('/admin');
        }
        return $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }
        event(new Lockout(request()));
        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'loginField' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->loginField) . '|' . request()->ip());
    }
}; ?>


<div>
    <!-- Modern Welcome Header -->
    <div class="mb-8 relative">
        <div class="absolute -top-10 -left-10 w-20 h-20 bg-emerald-100 rounded-full opacity-50"></div>
        <div class="absolute -bottom-4 right-0 w-12 h-12 bg-emerald-100 rounded-full opacity-30"></div>
        <div class="relative">
            <div class="flex items-center gap-2 mb-3">
                <div
                    class="flex items-center bg-gradient-to-r from-emerald-600 to-emerald-400 text-white text-xs font-medium py-1 px-3 rounded-full shadow-sm">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                        </path>
                    </svg>
                    <span>Portal Desa Digital</span>
                </div>
            </div>
            <h2
                class="text-3xl font-bold bg-gradient-to-r from-emerald-700 to-emerald-500 bg-clip-text text-transparent">
                Selamat Datang!</h2>
            <p class="text-gray-600 mt-2 flex items-center">
                <svg class="w-4 h-4 mr-1 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"
                        clip-rule="evenodd"></path>
                </svg>
                Masuk untuk mengakses layanan desa
            </p>
        </div>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="loginField" :value="'Username atau Email'" class="text-gray-700 font-medium mb-1" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <x-text-input wire:model="loginField" id="loginField"
                    class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                    type="text" name="loginField" required autofocus autocomplete="username"
                    placeholder="Masukkan username atau email Anda" />
            </div>
            <x-input-error :messages="$errors->get('loginField')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="'Password'" class="text-gray-700 font-medium mb-1" />
            <div class="relative" x-data="{ showPassword: false }">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input wire:model="password" id="password"
                    class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500"
                    type="password" name="password" required autocomplete="current-password"
                    placeholder="Masukkan Password Anda" x-bind:type="showPassword ? 'text' : 'password'" />
                <button type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                    @click="showPassword = !showPassword">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                    <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                        </path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <label for="captcha" class="block text-sm font-medium text-gray-700">Verifikasi Captcha</label>
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

        <div class="flex items-center justify-between">
            <label for="remember" class="flex items-center">
                <input wire:model="remember" id="remember" type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-emerald-600 hover:text-emerald-700"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
                {{ __('Masuk') }}
            </button>
        </div>

        @if (Route::has('register'))
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    {{ __('Belum punya akun?') }}
                    <a href="{{ route('register') }}" class="font-medium text-emerald-600 hover:text-emerald-700"
                        wire:navigate>
                        {{ __('Daftar') }}
                    </a>
                </p>
            </div>
        @endif
    </form>
</div>
