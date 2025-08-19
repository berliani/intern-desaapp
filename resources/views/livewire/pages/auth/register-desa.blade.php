<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold bg-gradient-to-r from-emerald-700 to-emerald-500 bg-clip-text text-transparent">
            Daftarkan Desa Anda
        </h2>
        <p class="text-gray-600 mt-2">
            Buat website untuk desa Anda dalam beberapa langkah mudah.
        </p>
    </div>

    {{-- Tampilkan notifikasi sukses --}}
    @if (session()->has('email_verified_success'))
        <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="font-medium">Sukses!</span> {{ session('email_verified_success') }}
        </div>
    @endif
    @if (session()->has('otp_info'))
        <div class="mb-4 p-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
             {{ session('otp_info') }}
        </div>
    @endif


    <form wire:submit="registerDesa" class="space-y-4">
        @csrf

        {{-- Bagian 1: Informasi Desa --}}
        <div class="p-4 border rounded-lg bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">1. Informasi Desa</h3>
            <div>
                <x-input-label for="nama_desa" value="Nama Desa" />
                <x-text-input wire:model.live="nama_desa" id="nama_desa" class="block mt-1 w-full" type="text" name="nama_desa" required autofocus placeholder="Contoh: Desa Makmur Jaya" :disabled="$emailVerified" />
                <x-input-error :messages="$errors->get('nama_desa')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="subdomain" value="Alamat Website (Subdomain)" />
                <div class="flex items-center">
                    <x-text-input wire:model="subdomain" id="subdomain" class="block w-full rounded-r-none" type="text" name="subdomain" required placeholder="contoh: makmur-jaya" :disabled="$emailVerified" />
                    <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-l-0 border-gray-300 rounded-r-md h-11">
                        .desa.local
                    </span>
                </div>
                <x-input-error :messages="$errors->get('subdomain')" class="mt-2" />
                <p class="text-xs text-gray-500 mt-1">Hanya boleh berisi huruf, angka, dan tanda hubung (-).</p>
            </div>
        </div>


        {{-- Bagian 2: Informasi Admin Desa & Verifikasi --}}
        <div class="p-4 border rounded-lg bg-gray-50 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">2. Informasi & Verifikasi Admin</h3>
            
            <div class="mt-4">
                <x-input-label for="admin_email" value="Email Admin" />
                <x-text-input wire:model.lazy="admin_email" id="admin_email" class="block mt-1 w-full" type="email" name="admin_email" required autocomplete="username" placeholder="Email untuk login admin" :disabled="$emailVerified" />
                <x-input-error :messages="$errors->get('admin_email')" class="mt-2" />
            </div>

            {{-- --- BLOK VERIFIKASI: Muncul sebelum email terverifikasi --- --}}
            @if (!$emailVerified)
                <div class="mt-4 p-3 bg-gray-100 rounded-lg">
                    <label for="captcha" class="block text-sm font-medium text-gray-700">Verifikasi Captcha</label>
                    <div class="flex items-center space-x-4 mt-1">
                        <span class="px-4 py-2 text-lg font-bold tracking-widest text-gray-700 bg-gray-200 border rounded-md select-none">
                            {{ $generatedCaptcha }}
                        </span>
                        <button type="button" wire:click="generateCaptcha" title="Refresh Captcha" class="p-2 text-gray-600 bg-white border rounded-md hover:bg-gray-50">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.664 0l3.181-3.183m-4.991-2.695v-2.257a2.25 2.25 0 00-2.25-2.25H10.5a2.25 2.25 0 00-2.25 2.25v2.257m1.5-10.128l1.272 1.272M21 21l-1.272-1.272" /></svg>
                        </button>
                    </div>
                    <x-text-input wire:model.lazy="captcha" id="captcha" type="text" class="block mt-2 w-full" placeholder="Masukkan captcha di atas" required />
                    <x-input-error :messages="$errors->get('captcha')" class="mt-2" />

                    <button type="button" wire:click="sendVerificationCode" wire:loading.attr="disabled" class="w-full mt-3 px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 disabled:opacity-50">
                        <span wire:loading.remove wire:target="sendVerificationCode">Kirim Kode Verifikasi</span>
                        <span wire:loading wire:target="sendVerificationCode">Mengirim...</span>
                    </button>
                </div>

                @if ($emailVerificationSent)
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <x-input-label for="otp" value="Kode OTP" />
                    <div class="flex items-center space-x-2">
                        <x-text-input wire:model.lazy="otp" id="otp" class="block mt-1 w-full" type="text" name="otp" required placeholder="Masukkan 6 digit kode OTP" />
                        <button type="button" wire:click="verifyOtp" wire:loading.attr="disabled" class="px-4 py-2 font-medium text-white bg-emerald-600 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200 disabled:opacity-50">
                            <span wire:loading.remove wire:target="verifyOtp">Verifikasi</span>
                             <span wire:loading wire:target="verifyOtp">Memeriksa...</span>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                </div>
                @endif
            @endif
            {{-- --- AKHIR BLOK VERIFIKASI --- --}}


            {{-- --- BLOK ADMIN DETAIL: Muncul setelah email terverifikasi --- --}}
            <div class="mt-4 space-y-4 @if(!$emailVerified) opacity-50 pointer-events-none @endif">
                <div>
                    <x-input-label for="admin_name" value="Nama Lengkap Admin" />
                    <x-text-input wire:model="admin_name" id="admin_name" class="block mt-1 w-full" type="text" name="admin_name" required autocomplete="name" placeholder="Nama pengelola website desa" />
                    <x-input-error :messages="$errors->get('admin_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="admin_password" value="Password" />
                    <x-text-input wire:model="admin_password" id="admin_password" class="block mt-1 w-full" type="password" name="admin_password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                    <x-input-error :messages="$errors->get('admin_password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="admin_password_confirmation" value="Konfirmasi Password" />
                    <x-text-input wire:model="admin_password_confirmation" id="admin_password_confirmation" class="block mt-1 w-full" type="password" name="admin_password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
                    <x-input-error :messages="$errors->get('admin_password_confirmation')" class="mt-2" />
                </div>
            </div>
            {{-- --- AKHIR BLOK ADMIN DETAIL --- --}}

        </div>

        <div class="flex items-center justify-between pt-4">
            <a class="text-sm font-medium text-emerald-600 hover:text-emerald-700" href="{{ route('login') }}" wire:navigate>
                {{ __('Sudah punya akun?') }}
            </a>

            <button type="submit"
                    class="inline-flex items-center px-6 py-2.5 bg-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ !$emailVerified ? 'disabled' : '' }}>
                <svg wire:loading wire:target="registerDesa" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Daftarkan Desa Saya</span>
            </button>
        </div>
    </form>
</div>