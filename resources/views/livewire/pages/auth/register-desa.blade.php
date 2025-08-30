<div>
    {{-- Bagian Judul --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold bg-gradient-to-r from-emerald-700 to-emerald-500 bg-clip-text text-transparent">
            Daftarkan Akun Admin Desa
        </h2>
        <p class="text-gray-600 mt-2">
            Langkah pertama untuk membuat website desa Anda.
        </p>
    </div>

    {{-- Notifikasi --}}
    <div>
        @if (session('status'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Form Pendaftaran --}}
    <form wire:submit="register" class="space-y-4">
        @csrf

        {{-- Bagian 1: Verifikasi Akun --}}
        <div class="p-4 border rounded-lg bg-gray-50 mt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">1. Verifikasi Akun</h3>

            @if (!$otpVerified)
                {{-- Pilih Metode --}}
                <div>
                    <x-input-label value="Pilih Metode Verifikasi" />
                    <div class="flex items-center gap-6 mt-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" wire:model.live="verificationMethod" value="email"
                                name="verification_method"
                                class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300">
                            <span class="text-gray-700">Email</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" wire:model.live="verificationMethod" value="whatsapp"
                                name="verification_method"
                                class="focus:ring-emerald-500 h-4 w-4 text-emerald-600 border-gray-300">
                            <span class="text-gray-700">WhatsApp</span>
                        </label>
                    </div>
                </div>

                {{-- Input Email / WhatsApp --}}
                <div class="mt-4">
                    @if ($verificationMethod === 'email')
                        <div>
                            <x-input-label for="email">
                                Alamat Email <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    @endif

                    @if($verificationMethod === 'whatsapp')
                        <div>
                            <x-input-label for="telepon">
                                Nomor WhatsApp <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input wire:model="telepon" id="telepon" class="block mt-1 w-full" type="text"
                                placeholder="Contoh: 081234567890" />
                            <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                        </div>
                    @endif
                </div>
                    @if ($verificationMethod === 'whatsapp')
                        <div>
                            <x-input-label for="telepon">
                                Nomor WhatsApp <span class="text-red-500">*</span>
                            </x-input-label>
                            <x-text-input wire:model="telepon" id="telepon" class="block mt-1 w-full" type="text"
                                placeholder="Contoh: 081234567890" />
                            <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                        </div>
                    @endif
                </div>

                {{-- Captcha --}}
                <label for="captcha" class="block text-sm font-medium text-gray-700 mt-4">Verifikasi Captcha</label>
                <div class="flex items-center space-x-4 mt-1">
                    <div class="flex items-center justify-around w-48 h-16 px-2 bg-gray-200 border rounded-md overflow-hidden"
                        style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23d4d4d8\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">

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
                <x-text-input wire:model.lazy="captcha" id="captcha" type="text" class="block mt-2 w-full"
                    placeholder="Masukkan captcha di atas" required x-ref="captcha"
                    @keydown.enter.prevent="$wire.sendVerificationCode()" />
                <x-input-error :messages="$errors->get('captcha')" class="mt-2" />

                {{-- Tombol Kirim OTP --}}
                @if (!$otpSent)
                    <button type="button" wire:click="sendVerificationCode" wire:loading.attr="disabled"
                        class="w-full mt-4 px-4 py-2 font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 disabled:opacity-50">
                        <span wire:loading.remove wire:target="sendVerificationCode">Kirim Kode Verifikasi</span>
                        <span wire:loading wire:target="sendVerificationCode">Mengirim...</span>
                    </button>
                @else
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <x-input-label for="otp" value="Masukkan Kode OTP" />
                        <div class="flex items-center space-x-2">
                            <x-text-input wire:model="otp" id="otp" class="block mt-1 w-full" type="text"
                                required />
                            <button type="button" wire:click="verifyOtp" wire:loading.attr="disabled"
                                class="px-4 py-2 font-medium text-white bg-emerald-600 rounded-md hover:bg-emerald-700 disabled:opacity-50">
                                <span wire:loading.remove wire:target="verifyOtp">Verifikasi</span>
                                <span wire:loading wire:target="verifyOtp">Memeriksa...</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('otp')" class="mt-2" />
                    </div>
                @endif
            @else
                <p class="font-medium">✅ Akun Anda sudah terverifikasi.</p>
            @endif
        </div>

        {{-- Bagian 2: Informasi Admin --}}
        <fieldset
            class="p-4 border rounded-lg bg-gray-50 space-y-4 @if (!$otpVerified) opacity-50 pointer-events-none @endif">
            <legend class="text-lg font-semibold text-gray-800 px-2">2. Informasi Admin & Password</legend>

            <div>
                <x-input-label for="name">
                    Nama Lengkap <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="username">
                    Username Admin <span class="text-red-500">*</span>
                </x-input-label>
                <x-text-input wire:model="username" id="username" class="block mt-1 w-full" type="text"
                    required />
                <p class="mt-1 text-xs text-gray-500">Tanpa spasi. Gunakan tanda hubung (-) atau (_).</p>
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div x-data="{ show: false }">
                <x-input-label for="password">
                    Password <span class="text-red-500">*</span>
                </x-input-label>
                <div class="relative mt-1">
                    <x-text-input wire:model="password" id="password" class="block w-full pr-10"
                        x-bind:type="show ? 'text' : 'password'" required />
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                            </path>
                        </svg>
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500">Min. 8 karakter, kombinasi huruf besar & kecil, angka, simbol.
                </p>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div x-data="{ show: false }">
                <x-input-label for="password_confirmation">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </x-input-label>
                <div class="relative mt-1">
                    <x-text-input wire:model="password_confirmation" id="password_confirmation"
                        class="block w-full pr-10" x-bind:type="show ? 'text' : 'password'" required />
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        <svg x-show="show" class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </fieldset>

        {{-- Tombol Submit --}}
        <div class="flex items-center justify-end pt-4">
            <button type="submit"
                class="inline-flex items-center px-6 py-2.5 bg-emerald-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed"
                {{ !$otpVerified ? 'disabled' : '' }}>
                <span>Daftarkan Akun Saya</span>
            </button>
        </div>
    </form>
</div>
