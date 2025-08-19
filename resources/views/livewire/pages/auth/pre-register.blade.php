<div>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center text-gray-800">Pendaftaran Akun Desa</h2>
            <p class="text-center text-gray-600">Masukkan email Anda untuk memulai proses pendaftaran.</p>

            <form wire:submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <input wire:model="email" id="email" type="email" class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="contoh@email.com" required>
                    @error('email') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="captcha" class="block text-sm font-medium text-gray-700">Verifikasi Captcha</label>
                    <div class="flex items-center space-x-4 mt-1">
                        <span class="px-4 py-2 text-lg font-bold tracking-widest text-gray-700 bg-gray-200 border rounded-md select-none">
                            {{ $generatedCaptcha }}
                        </span>
                        <button type="button" wire:click="generateCaptcha" title="Refresh Captcha" class="p-2 text-gray-600 bg-gray-100 border rounded-md hover:bg-gray-200">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.664 0l3.181-3.183m-4.991-2.695v-2.257a2.25 2.25 0 00-2.25-2.25H10.5a2.25 2.25 0 00-2.25 2.25v2.257m1.5-10.128l1.272 1.272M21 21l-1.272-1.272" />
                            </svg>
                        </button>
                    </div>
                    <input wire:model="captcha" id="captcha" type="text" class="w-full px-3 py-2 mt-2 border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500" placeholder="Masukkan captcha di atas" required>
                    @error('captcha') <span class="text-sm text-red-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full px-4 py-2 font-medium text-white bg-emerald-600 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-200">
                    Kirim Kode Verifikasi
                </button>
            </form>
        </div>
    </div>
</div>