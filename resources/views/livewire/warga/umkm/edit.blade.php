<div class="min-h-screen">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-lg sm:text-xl font-medium text-gray-800 flex items-center">
                <svg class="w-6 h-6 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                {{ __('Edit UMKM') }}
            </h2>
            <a href="{{ route('warga.umkm') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-emerald-600 hover:text-emerald-800 border border-emerald-200 hover:bg-emerald-50 transition-all duration-300">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- UMKM Form Card -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
                <form wire:submit.prevent="updateUmkm" class="divide-y divide-gray-100">
                    <!-- Basic Information Section -->
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Dasar</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                            <div>
                                <label for="nama_usaha" class="block text-sm font-medium text-gray-700 mb-1">Nama Usaha <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <input type="text" id="nama_usaha" wire:model="nama_usaha"
                                           class="pl-10 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                           placeholder="Masukkan nama usaha Anda">
                                </div>
                                @error('nama_usaha') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori Usaha</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <select id="kategori" wire:model="kategori"
                                            class="pl-10 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Kuliner">Kuliner</option>
                                        <option value="Kerajinan">Kerajinan</option>
                                        <option value="Fashion">Fashion</option>
                                        <option value="Pertanian">Pertanian</option>
                                        <option value="Jasa">Jasa</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                @error('kategori') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="produk" class="block text-sm font-medium text-gray-700 mb-1">Produk/Layanan <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="produk" wire:model="produk"
                                           class="pl-10 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                           placeholder="Jenis produk atau layanan">
                                </div>
                                @error('produk') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="kontak_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">Kontak WhatsApp <span class="text-red-500">*</span></label>
                                <div class="flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-300 bg-gray-50 text-gray-500">+</span>
                                    <input type="text" id="kontak_whatsapp" wire:model="kontak_whatsapp"
                                           class="w-full rounded-none rounded-r-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                           placeholder="628xxxxxxxxxx">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Format: 628xxxxxxxxxx (tanpa tanda + atau -)</p>
                                @error('kontak_whatsapp') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location and Description Section -->
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Lokasi & Deskripsi</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi Usaha</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="lokasi" wire:model="lokasi"
                                           class="pl-10 w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                           placeholder="Alamat lengkap tempat usaha">
                                </div>
                                @error('lokasi') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Usaha</label>
                                <textarea id="deskripsi" wire:model="deskripsi" rows="4"
                                          class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                          placeholder="Deskripsi singkat tentang usaha Anda"></textarea>
                                @error('deskripsi') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Photo Upload Section -->
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Foto Produk</h3>
                        <div x-data="{
                                isUploading: false,
                                progress: 0
                            }"
                            x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                            x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">

                            <label class="flex justify-center w-full h-32 px-4 transition bg-white border-2 border-gray-300 border-dashed rounded-xl appearance-none cursor-pointer hover:border-emerald-500 focus:outline-none">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-sm text-gray-600">
                                        <span class="font-medium text-emerald-600">Klik untuk upload</span>
                                        atau seret dan lepas
                                    </span>
                                    <span class="text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 1MB</span>
                                </div>
                                <input type="file" wire:model="foto_produk" id="foto_produk" class="hidden">
                            </label>

                            <!-- Progress Bar -->
                            <div x-show="isUploading" class="mt-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300" x-bind:style="`width: ${progress}%`"></div>
                                </div>
                                <p class="text-xs text-center text-gray-500 mt-2">Mengupload... <span x-text="progress"></span>%</p>
                            </div>

                            <!-- Preview Section -->
                            <div class="mt-4">
                                @if ($foto_produk && !$errors->has('foto_produk'))
                                    <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                        <img src="{{ $foto_produk->temporaryUrl() }}" class="h-24 w-24 object-cover rounded-lg">
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Preview foto baru</p>
                                            <button type="button" wire:click="$set('foto_produk', null)"
                                                    class="mt-1 text-xs text-red-600 hover:text-red-800">
                                                Hapus gambar
                                            </button>
                                        </div>
                                    </div>
                                @elseif ($currentFotoUrl)
                                    <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                        <img src="{{ $currentFotoUrl }}" class="h-24 w-24 object-cover rounded-lg">
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">Foto saat ini</p>
                                            <p class="text-xs text-gray-500 mt-1">Upload foto baru untuk mengganti</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @error('foto_produk')
                                <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button Section -->
                    <div class="px-6 py-4 bg-gray-50">
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Update UMKM
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script SweetAlert -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('showAlert', (data) => {
                Swal.fire({
                    icon: data[0].icon,
                    title: data[0].title,
                    text: data[0].text,
                    showConfirmButton: data[0].icon === 'error',
                    timer: data[0].icon === 'success' ? 3000 : undefined,
                    timerProgressBar: data[0].icon === 'success',
                    toast: data[0].icon === 'success',
                    position: data[0].icon === 'success' ? 'top-end' : 'center',
                    customClass: {
                        popup: 'rounded-lg shadow-lg border border-gray-200',
                        title: 'text-gray-800',
                        content: 'text-gray-700'
                    }
                });
            });
        });
    </script>
</div>