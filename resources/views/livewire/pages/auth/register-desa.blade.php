<?php

use App\Models\User;
use App\Models\Company;
use App\Models\ProfilDesa;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Support\Str;

new #[Layout('layouts.guest')] class extends Component
{
    // Properti untuk data desa
    public string $nama_desa = '';
    public string $subdomain = '';

    // Properti untuk data admin desa
    public string $admin_name = '';
    public string $admin_email = '';
    public string $admin_password = '';
    public string $admin_password_confirmation = '';

    /**
     * Secara otomatis membuat slug untuk subdomain saat nama desa diketik.
     */
    public function updatedNamaDesa($value)
    {
        $this->subdomain = Str::slug($value);
    }

    /**
     * Menangani permintaan pendaftaran desa baru.
     */
    public function registerDesa(): void
    {
        // Validasi input form
        $validated = $this->validate([
            'nama_desa' => ['required', 'string', 'max:100', 'unique:'.Company::class.',name'],
            'subdomain' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:'.Company::class.',subdomain'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'admin_password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);


        DB::transaction(function () use ($validated) {
            // 1. Buat entri baru di tabel 'companies'
            $company = Company::create([
                'name' => $validated['nama_desa'],
                'subdomain' => $validated['subdomain'],
            ]);

            // 2. Buat user admin untuk desa tersebut
            $adminUser = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'company_id' => $company->id, // Tautkan user dengan company
            ]);


            // 3. Berikan role 'admin' kepada user baru
            $adminUser->assignRole('admin');

            // 4. Login user admin yang baru dibuat
            Auth::login($adminUser);

            // 5. Kirim event bahwa user telah terdaftar
            event(new Registered($adminUser));
        });

        // 6. Redirect ke dashboard admin desa untuk melengkapi profil
        $this->redirect('/desa/dashboard', navigate: true);
    }
}; ?>

<div>
    <!-- Header Modern -->
    <div class="mb-6">
         <h2 class="text-2xl font-bold bg-gradient-to-r from-emerald-700 to-emerald-500 bg-clip-text text-transparent">
            Daftarkan Desa Anda
        </h2>
        <p class="text-gray-600 mt-2">
            Buat website untuk desa Anda dalam beberapa langkah mudah.
        </p>
    </div>

    <form wire:submit="registerDesa" class="space-y-4">
        @csrf

        {{-- Bagian Informasi Desa --}}
        <div class="p-4 border rounded-lg bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Informasi Desa</h3>
            <!-- Nama Desa -->
            <div>
                <x-input-label for="nama_desa" value="Nama Desa" />
                <x-text-input wire:model.live="nama_desa" id="nama_desa" class="block mt-1 w-full" type="text" name="nama_desa" required autofocus placeholder="Contoh: Desa Makmur Jaya" />
                <x-input-error :messages="$errors->get('nama_desa')" class="mt-2" />
            </div>

            <!-- Subdomain -->
            <div class="mt-4">
                <x-input-label for="subdomain" value="Alamat Website (Subdomain)" />
                <div class="flex items-center">
                    <x-text-input wire:model="subdomain" id="subdomain" class="block w-full rounded-r-none" type="text" name="subdomain" required placeholder="contoh: makmur-jaya" />
                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md h-11">
                        .desa.local
                    </span>
                </div>
                 <x-input-error :messages="$errors->get('subdomain')" class="mt-2" />
                 <p class="text-xs text-gray-500 mt-1">Hanya boleh berisi huruf, angka, dan tanda hubung (-).</p>
            </div>
        </div>


        {{-- Bagian Informasi Admin Desa --}}
        <div class="p-4 border rounded-lg bg-gray-50 mt-6">
             <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Informasi Admin Pengelola</h3>
            <!-- Nama Admin -->
            <div class="mt-4">
                <x-input-label for="admin_name" value="Nama Lengkap Admin" />
                <x-text-input wire:model="admin_name" id="admin_name" class="block mt-1 w-full" type="text" name="admin_name" required autocomplete="name" placeholder="Nama pengelola website desa" />
                <x-input-error :messages="$errors->get('admin_name')" class="mt-2" />
            </div>

            <!-- Email Admin -->
            <div class="mt-4">
                <x-input-label for="admin_email" value="Email Admin" />
                <x-text-input wire:model="admin_email" id="admin_email" class="block mt-1 w-full" type="email" name="admin_email" required autocomplete="username" placeholder="Email untuk login admin" />
                <x-input-error :messages="$errors->get('admin_email')" class="mt-2" />
            </div>

            <!-- Password Admin -->
            <div class="mt-4">
                <x-input-label for="admin_password" value="Password" />
                <x-text-input wire:model="admin_password" id="admin_password" class="block mt-1 w-full" type="password" name="admin_password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                <x-input-error :messages="$errors->get('admin_password')" class="mt-2" />
            </div>

            <!-- Konfirmasi Password Admin -->
            <div class="mt-4">
                <x-input-label for="admin_password_confirmation" value="Konfirmasi Password" />
                <x-text-input wire:model="admin_password_confirmation" id="admin_password_confirmation" class="block mt-1 w-full" type="password" name="admin_password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
                <x-input-error :messages="$errors->get('admin_password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-between pt-4">
             <a class="text-sm font-medium text-emerald-600 hover:text-emerald-700" href="{{ route('login') }}" wire:navigate>
                {{ __('Sudah punya akun?') }}
            </a>

            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg wire:loading wire:target="registerDesa" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Daftarkan Desa Saya</span>
            </button>
        </div>
    </form>
</div>
