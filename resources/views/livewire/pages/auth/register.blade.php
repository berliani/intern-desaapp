<?php

use App\Models\User;
use App\Models\Penduduk;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Pastikan ini di-import
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $nik = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mengisi nama secara otomatis ketika NIK ditemukan.
     * Logika ini sudah disiapkan untuk bekerja setelah tabel 'penduduk' dienkripsi.
     */
    public function updatedNik()
    {
        if (strlen($this->nik) === 16) {
            $penduduk = null;
            // Cek apakah tabel penduduk sudah memiliki kolom hash
            if (DB::getSchemaBuilder()->hasColumn('penduduk', 'nik_search_hash')) {
                $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                $searchHash = hash_hmac('sha256', $this->nik, $pepperKey);
                $penduduk = Penduduk::where('nik_search_hash', $searchHash)->first();
            } else {
                // Fallback ke cara lama jika tabel penduduk belum dimigrasi
                $penduduk = Penduduk::where('nik', $this->nik)->first();
            }

            if ($penduduk) {
                $this->name = $penduduk->nama;
            }
        }
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                // PERBAIKAN: Validasi keunikan email menggunakan hash
                function ($attribute, $value, $fail) {
                    $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                    $searchHash = hash_hmac('sha256', $value, $pepperKey);
                    if (DB::table('users')->where('email_search_hash', $searchHash)->exists()) {
                        $fail('Email yang Anda masukkan sudah terdaftar.');
                    }
                }
            ],
            'nik' => [
                'required', 'string', 'size:16',
                // PERBAIKAN: Validasi keunikan NIK menggunakan hash
                function ($attribute, $value, $fail) {
                    $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                    $searchHash = hash_hmac('sha256', $value, $pepperKey);
                    if (DB::table('users')->where('nik_search_hash', $searchHash)->exists()) {
                        $fail('NIK yang Anda masukkan sudah terdaftar.');
                    }
                }
            ],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        
        $penduduk = null;
        if (DB::getSchemaBuilder()->hasColumn('penduduk', 'nik_search_hash')) {
            $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
            $nikSearchHash = hash_hmac('sha256', $this->nik, $pepperKey);
            $penduduk = Penduduk::where('nik_search_hash', $nikSearchHash)->first();
        } else {
            $penduduk = Penduduk::where('nik', $this->nik)->first();
        }
        
        $validated['penduduk_id'] = $penduduk?->id;
        $validated['nik'] = $this->nik;

        $user = User::create($validated);

        if ($penduduk) {
            $user->assignRole('warga');
            session()->flash('message', 'Pendaftaran berhasil! Data NIK ditemukan, akun Anda sudah terverifikasi.');
            $redirectTo = '/warga/dashboard';
        } else {
            $user->assignRole('unverified');
            session()->flash('warning', 'Pendaftaran berhasil! Silakan lengkapi data verifikasi Anda melalui menu Verifikasi Data.');
            $redirectTo = '/verifikasi-data';
        }

        event(new Registered($user));

        Auth::login($user);

        $this->redirect($redirectTo, navigate: true);
    }
}; ?>

<div>
    <!-- Modern Header -->
    <div class="mb-6 relative">
        <div class="absolute -top-10 -left-10 w-20 h-20 bg-emerald-100 rounded-full opacity-50"></div>
        <div class="absolute -bottom-4 right-0 w-12 h-12 bg-emerald-100 rounded-full opacity-30"></div>

        <div class="relative">
            <!-- Modern Badge with Home Icon - Matching login style -->
            <div class="flex items-center gap-2 mb-3">
                <div class="flex items-center bg-gradient-to-r from-emerald-600 to-emerald-400 text-white text-xs font-medium py-1 px-3 rounded-full shadow-sm">
                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    <span>Portal Desa Digital</span>
                </div>

                <!-- Responsive additions for larger screens -->
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

    <form wire:submit="register" class="space-y-4">
        @csrf <!-- Explicit CSRF Token for added security -->

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-gray-700 font-medium mb-1 flex items-center">
                <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                Nama Lengkap
            </x-input-label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <x-text-input wire:model.live="name" id="name" class="block w-full pl-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500" type="text" name="name" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium mb-1 flex items-center">
                <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                </svg>
                Email
            </x-input-label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <x-text-input wire:model="email" id="email" class="block w-full pl-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500" type="email" name="email" required autocomplete="username" placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- NIK -->
        <div>
            <x-input-label for="nik" :value="__('NIK')" class="text-gray-700 font-medium mb-1 flex items-center">
                <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
                NIK
            </x-input-label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                </div>
                <x-text-input wire:model.live="nik" id="nik" class="block w-full pl-10 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-emerald-500 focus:border-emerald-500" type="text" name="nik" required maxlength="16" placeholder="Masukkan 16 digit NIK" />
            </div>
            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
            @if(strlen($nik) === 16)
                <p class="text-xs text-emerald-600 mt-1 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Format NIK sudah benar (16 digit)
                </p>
            @endif
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium mb-1 flex items-center">
                <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
                Password
            </x-input-label>
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
                            type="password"
                            name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    x-bind:type="showPassword ? 'text' : 'password'"
                />
                <!-- Eye Icon Toggle Button -->
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                    @click="showPassword = !showPassword"
                >
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-gray-700 font-medium mb-1 flex items-center">
                <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                Konfirmasi Password
            </x-input-label>
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
                            type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                    x-bind:type="showConfirmPassword ? 'text' : 'password'"
                />
                <!-- Eye Icon Toggle Button -->
                <button
                    type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                    @click="showConfirmPassword = !showConfirmPassword"
                >
                    <svg x-show="!showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg x-show="showConfirmPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Honeypot trap for bots (hidden field) -->
        <div class="hidden">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="flex items-center justify-between pt-2">
            <a class="text-sm font-medium text-emerald-600 hover:text-emerald-700 flex items-center" href="{{ route('login') }}" wire:navigate>
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                {{ __('Sudah punya akun?') }}
            </a>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg>
                {{ __('Daftar') }}
            </button>
        </div>
    </form>
</div>
