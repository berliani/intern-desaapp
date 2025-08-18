<div class="min-h-screen">
    <x-slot name="header">
        <div class="flex justify-between items-center py-1">
            <h2 class="text-lg sm:text-xl font-medium text-gray-800">
            {{ __('Pengaduan Warga') }}
        </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
            @if (session()->has('message'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 shadow-sm border border-green-100" role="alert">
                    <p>{{ session('message') }}</p>
                </div>
            @endif

            @if (session()->has('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 shadow-sm border border-red-100" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if($penduduk)
            <!-- Form Pengaduan dengan UI yang diperbarui -->
            <div class="bg-white overflow-hidden rounded-lg shadow-sm mb-6">
                    <div class="p-6">
                    <h3 class="text-lg font-medium mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        {{ $isEditing ? 'Edit Pengaduan' : 'Buat Pengaduan Baru' }}
                    </h3>

                        <form wire:submit.prevent="{{ $isEditing ? 'updatePengaduan' : 'createPengaduan' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengaduan <span class="text-red-500">*</span></label>
                                <input id="judul" type="text" wire:model="judul"
                                    placeholder="Masukkan judul pengaduan (min. 5 karakter)"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors @error('judul') border-red-300 @enderror">
                                @error('judul') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                                <select id="kategori" wire:model="kategori"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors @error('kategori') border-red-300 @enderror">
                                    <option value="">Pilih Kategori Pengaduan</option>
                                    <option value="Keamanan">Keamanan</option>
                                    <option value="Infrastruktur">Infrastruktur</option>
                                    <option value="Sosial">Sosial</option>
                                    <option value="Lingkungan">Lingkungan</option>
                                    <option value="Pelayanan Publik">Pelayanan Publik</option>
                                    <option value="Kesehatan">Kesehatan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                @error('kategori') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            </div>

                        <div class="mb-6">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Pengaduan <span class="text-red-500">*</span></label>
                                <textarea id="deskripsi" wire:model="deskripsi" rows="4"
                                    placeholder="Jelaskan detail pengaduan Anda secara jelas (min. 10 karakter)"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-colors @error('deskripsi') border-red-300 @enderror"></textarea>
                            @error('deskripsi') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                        <div class="mb-6">
                                <label for="foto-input" class="block text-sm font-medium text-gray-700 mb-1">Foto Bukti (Opsional)</label>

                            <div class="mt-2 relative border-2 border-gray-300 border-dashed rounded-lg @error('foto') border-red-300 @enderror"
                                     x-data="{
                                        isHovered: false,
                                        initFileUpload() {
                                            const dropzone = this.$el;
                                            const fileInput = document.getElementById('foto-input');

                                            // Handle click on the entire dropzone
                                            dropzone.addEventListener('click', (e) => {
                                                if (!@js($foto || ($isEditing && $oldFoto)) ||
                                                    e.target.closest('[data-action=replace]')) {
                                                    fileInput.click();
                                                    e.preventDefault();
                                                }
                                            });

                                            // Prevent propagation for remove button
                                            const removeBtn = dropzone.querySelector('[data-action=remove]');
                                            if (removeBtn) {
                                                removeBtn.addEventListener('click', (e) => {
                                                    e.stopPropagation();
                                                });
                                            }

                                            // Drag and drop handling
                                            ['dragenter', 'dragover'].forEach(eventName => {
                                                dropzone.addEventListener(eventName, () => {
                                                    this.isHovered = true;
                                                });
                                            });

                                            ['dragleave', 'drop'].forEach(eventName => {
                                                dropzone.addEventListener(eventName, () => {
                                                    this.isHovered = false;
                                                });
                                            });
                                        }
                                     }"
                                     x-init="initFileUpload()"
                                 x-bind:class="{ 'bg-emerald-50 border-emerald-300 shadow-sm': isHovered }"
                                 class="cursor-pointer px-6 pt-5 pb-6 flex justify-center transition-all duration-200">

                                    <div class="space-y-2 text-center w-full py-3 cursor-pointer">
                                        @if($foto)
                                            <div class="relative mx-auto">
                                            <img src="{{ $foto->temporaryUrl() }}" class="mx-auto h-48 w-auto object-cover rounded-lg shadow-sm" alt="Preview">
                                            <button type="button" data-action="remove" wire:click="$set('foto', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @elseif($isEditing && $oldFoto)
                                            <div class="relative mx-auto">
                                            <img src="{{ Storage::url($oldFoto) }}" class="mx-auto h-48 w-auto object-cover rounded-lg shadow-sm" alt="Current Photo">
                                            <button type="button" data-action="remove" wire:click="$set('oldFoto', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @else
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex justify-center text-sm text-gray-600">
                                            <span class="bg-white rounded-md font-medium text-emerald-600 px-2">Unggah foto</span>
                                                <p class="pl-1">atau seret dan lepas</p>
                                            </div>
                                        @endif

                                        <div wire:loading wire:target="foto" class="text-sm text-emerald-600">
                                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-emerald-600 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Mengunggah...
                                        </div>

                                        <p class="text-xs text-gray-500">JPG, PNG, atau GIF (maks. 1MB)</p>
                                    </div>

                                    <!-- Replace button when we have an image -->
                                    @if($foto || ($isEditing && $oldFoto))
                                        <div class="absolute bottom-2 right-2">
                                        <button type="button" data-action="replace" class="inline-flex items-center px-2 py-1 bg-white text-gray-700 text-xs rounded-md border border-gray-300 shadow-sm hover:bg-gray-50 focus:outline-none">
                                                <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                                Ganti
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <!-- The actual file input that's hidden -->
                                <input id="foto-input" type="file" wire:model="foto" accept="image/*" class="hidden">

                            @error('foto') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-between">
                                @if($isEditing)
                                <button type="button" wire:click="cancelEdit" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                                            wire:loading.attr="disabled" wire:target="updatePengaduan">
                                        Batal
                                    </button>
                                <button type="submit" class="inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-70 disabled:cursor-not-allowed transition-colors"
                                            wire:loading.attr="disabled" wire:target="updatePengaduan">
                                        <svg wire:loading wire:target="updatePengaduan" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span wire:loading.remove wire:target="updatePengaduan">Update Pengaduan</span>
                                        <span wire:loading wire:target="updatePengaduan">Memproses...</span>
                                    </button>
                                @else
                                    <div class="text-sm text-gray-500">
                                        <span class="text-red-500">*</span> Wajib diisi
                                    </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-sm hover:shadow-md hover:from-emerald-600 hover:to-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed"
                                            wire:loading.attr="disabled" wire:target="createPengaduan">
                                        <svg wire:loading wire:target="createPengaduan" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span wire:loading.remove wire:target="createPengaduan">Kirim Pengaduan</span>
                                        <span wire:loading wire:target="createPengaduan">Mengirim...</span>
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

            <!-- Daftar Pengaduan dengan UI yang ditingkatkan -->
            <div class="bg-white overflow-hidden rounded-lg shadow-sm">
                    <div class="p-6">
                    <div class="pb-4 border-b border-gray-200 mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Riwayat Pengaduan
                        </h3>

                        <button wire:click="$refresh" x-data="{ spinning: false }"
                            @click="spinning = true; setTimeout(() => spinning = false, 1000)"
                            class="flex items-center justify-center w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-800 transition-all duration-300 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" :class="{ 'animate-spin': spinning }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </button>
                    </div>

                        @if($pengaduanList->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="mt-4 text-xl font-medium text-gray-900">Belum Ada Pengaduan</h3>
                            <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">Anda belum memiliki riwayat pengaduan. Silakan buat pengaduan baru melalui form di atas.</p>
                        </div>
                    @else
                        <!-- Mobile view - Cards with improved layout -->
                        <div class="md:hidden space-y-4">
                            @foreach($pengaduanList as $item)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
                                    <!-- Header section with title and date -->
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <div class="flex justify-between items-center mb-1">
                                            <h4 class="text-base font-medium text-gray-900 truncate pr-2">
                                                {{ $item->judul ?? 'Pengaduan #'.$item->id }}
                                            </h4>
                                            <span class="text-xs text-gray-500 whitespace-nowrap">
                                                {{ $item->created_at->format('d M Y') }}
                                            </span>
                                        </div>

                                        <p class="text-xs text-gray-500 line-clamp-2">
                                            {{ $item->getRingkasanDeskripsi(50) }}
                                        </p>
                                    </div>

                                    <!-- Footer section with category, status and actions -->
                                    <div class="px-4 py-3 bg-gray-50 flex flex-col space-y-3">
                                        <!-- Category and status badges -->
                                        <div class="flex flex-wrap gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-fit">
                                                {{ $item->kategori }}
                                            </span>

                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit
                                                @if($item->status == 'Belum Ditangani') bg-yellow-100 text-yellow-800
                                                @elseif($item->status == 'Sedang Diproses') bg-blue-100 text-blue-800
                                                @elseif($item->status == 'Selesai') bg-green-100 text-green-800
                                                @elseif($item->status == 'Ditolak') bg-red-100 text-red-800
                                                @endif">
                                                {{ $item->status }}
                                            </span>
                                        </div>

                                        <!-- Action buttons with text labels -->
                                        <div class="flex justify-end">
                                            @if($item->status == 'Belum Ditangani')
                                                <button wire:click="editPengaduan({{ $item->id }})" class="inline-flex items-center px-3 py-1.5 rounded-md bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors mr-2">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                    <span>Edit</span>
                                                </button>
                                                <button
                                                    onclick="confirmDelete({{ $item->id }}, '{{ $item->judul ?? 'Pengaduan #'.$item->id }}')"
                                                    type="button"
                                                    class="inline-flex items-center px-3 py-1.5 rounded-md bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                            @else
                                                <button wire:click="viewPengaduan({{ $item->id }})" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-gradient-to-r from-emerald-500 to-emerald-600 shadow-sm hover:shadow-md transition-all duration-300">
                                                    <span>Lihat Detail</span>
                                                    <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop view - Table with modern styling -->
                        <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-100">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($pengaduanList as $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <div class="font-medium">{{ $item->judul ?? 'Pengaduan #'.$item->id }}</div>
                                                    <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">{{ $item->getRingkasanDeskripsi(50) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full bg-blue-100 text-blue-800 w-fit">
                                                        {{ $item->kategori }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full w-fit
                                                        @if($item->status == 'Belum Ditangani') bg-yellow-100 text-yellow-800
                                                        @elseif($item->status == 'Sedang Diproses') bg-blue-100 text-blue-800
                                                        @elseif($item->status == 'Selesai') bg-green-100 text-green-800
                                                        @elseif($item->status == 'Ditolak') bg-red-100 text-red-800
                                                        @endif">
                                                        {{ $item->status }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->created_at->format('d M Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    @if($item->status == 'Belum Ditangani')
                                                        <button wire:click="editPengaduan({{ $item->id }})" class="text-emerald-600 hover:text-emerald-900 mr-3">Edit</button>
                                                        <button
                                                            onclick="confirmDelete({{ $item->id }}, '{{ $item->judul ?? 'Pengaduan #'.$item->id }}')"
                                                            type="button"
                                                            class="text-red-600 hover:text-red-900">Hapus</button>
                                                    @else
                                                    <button wire:click="viewPengaduan({{ $item->id }})" class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium text-white bg-gradient-to-r from-emerald-500 to-emerald-600 shadow-sm hover:shadow-md transition-all duration-300">
                                                        <span>Detail</span>
                                                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                        </svg>
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @else
            <div class="bg-white rounded-lg shadow-sm border border-yellow-100 overflow-hidden">
                <div class="bg-yellow-50 px-4 py-3 border-b border-yellow-100">
                    <h3 class="text-sm font-medium text-yellow-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Verifikasi Data Diperlukan
                    </h3>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600">
                        Untuk mengajukan pengaduan, akun Anda harus terhubung dengan data penduduk. Silakan hubungi admin desa untuk verifikasi data Anda.
                    </p>
                    <div class="mt-4">
                        <a href="#" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-sm hover:from-yellow-500 hover:to-yellow-600 hover:shadow transition-all duration-300">
                            Pelajari Lebih Lanjut
                            <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        </div>
                    </div>
                </div>
            @endif
    </div>

    <!-- Modal Detail Pengaduan dengan UI yang diperbarui -->
    @if($showDetail)
        <div class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50 p-4 overflow-y-auto" wire:click="closeDetail">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl mx-auto my-8 transform transition-all ease-out duration-300"
                 onclick="event.stopPropagation();"
                 x-on:click.away="$wire.closeDetail()"
                 x-data="{}"
                 x-init="setTimeout(() => $el.classList.add('scale-100', 'opacity-100'), 50)"
                 class="scale-95 opacity-0">

                <div class="flex justify-between items-center p-4 border-b">
                    <h2 class="text-xl font-medium text-gray-900">Detail Pengaduan</h2>
                    <button wire:click="closeDetail" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @if($selectedPengaduan)
                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $selectedPengaduan->judul ?? 'Pengaduan #'.$selectedPengaduan->id }}</h3>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                    <span class="px-2.5 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800 w-fit">
                                        {{ $selectedPengaduan->kategori }}
                                    </span>
                                    <span class="px-2.5 py-0.5 text-xs rounded-full w-fit
                                        @if($selectedPengaduan->status == 'Belum Ditangani') bg-yellow-100 text-yellow-800
                                        @elseif($selectedPengaduan->status == 'Sedang Diproses') bg-blue-100 text-blue-800
                                        @elseif($selectedPengaduan->status == 'Selesai') bg-green-100 text-green-800
                                        @elseif($selectedPengaduan->status == 'Ditolak') bg-red-100 text-red-800
                                        @endif">
                                        {{ $selectedPengaduan->status }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $selectedPengaduan->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                <p class="text-gray-800 whitespace-pre-line">{{ $selectedPengaduan->deskripsi }}</p>
                            </div>
                            @if($selectedPengaduan->foto)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Bukti Foto:
                                    </h4>
                                    <div class="bg-gray-100 p-2 rounded-lg flex justify-center shadow-sm">
                                    <img src="{{ Storage::url($selectedPengaduan->foto) }}" alt="Bukti foto" class="rounded-lg max-h-80 object-contain hover:scale-105 transition-transform cursor-zoom-in">
                                    </div>
                                </div>
                            @endif
                            @if($selectedPengaduan->tanggapan)
                                <div class="border-t pt-4">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                    Tanggapan:
                                    </h4>
                                    <div class="bg-green-50 p-4 rounded-lg shadow-sm border border-green-100">
                                        <p class="text-gray-800 whitespace-pre-line">{{ $selectedPengaduan->tanggapan }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                        Ditanggapi pada: {{ $selectedPengaduan->tanggal_tanggapan ? $selectedPengaduan->tanggal_tanggapan->format('d M Y, H:i') : '-' }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                                    <button wire:click="closeDetail" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Tutup
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
    <!-- SweetAlert Scripts -->
    <script>
        function confirmDelete(id, judul) {
            Swal.fire({
                title: '<span class="text-gray-800">Hapus Pengaduan?</span>',
                html: `<p class="text-gray-600">Anda yakin ingin menghapus pengaduan <b class="text-gray-800">${judul}</b>? <br>Tindakan ini tidak dapat dibatalkan.</p>`,
                icon: 'warning',
                iconColor: '#f87171',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                buttonsStyling: true,
                showClass: {
                    popup: 'animate__animated animate__fadeIn animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut animate__faster'
                },
                customClass: {
                    popup: 'rounded-xl shadow-xl border border-gray-200',
                    confirmButton: 'rounded-lg text-sm font-medium py-2 px-4',
                    cancelButton: 'rounded-lg text-sm font-medium py-2 px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Panggil method Livewire
                    @this.confirmDeletePengaduan(id);
                }
            });
        }

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
                }).then(() => {
                    if (data[0].icon === 'success' && data[0].text.includes('berhasil dihapus')) {
                        // Refresh komponen jika berhasil menghapus
                        Livewire.dispatch('refresh');
                    }
                });
            });
        });
    </script>
</div>
