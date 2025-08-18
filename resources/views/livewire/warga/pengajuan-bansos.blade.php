<div>
    <x-slot name="header">
        <div class="flex justify-between items-center py-1">
            <h2 class="text-lg sm:text-xl font-medium text-gray-800 leading-tight">
                {{ __('Pengajuan Bantuan Sosial') }}
            </h2>
            <a href="{{ route('warga.bansos') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-sm hover:shadow-md hover:from-emerald-600 hover:to-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-300">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Lihat Data Bantuan
            </a>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($penduduk)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Form Pengajuan Bantuan
                        </h3>

                        <form wire:submit.prevent="ajukanBansos" class="space-y-6">
                            <!-- Jenis Bantuan Select -->
                            <div>
                                <label for="jenisBansos" class="block text-sm font-medium text-gray-700">
                                    Jenis Bantuan <span class="text-red-500">*</span>
                                </label>
                                <select id="jenisBansos"
                                    wire:model="selectedJenis"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 rounded-md">
                                    <option value="">-- Pilih Jenis Bantuan --</option>
                                    @foreach($jenisBansos as $jenis)
                                        <option value="{{ $jenis->id }}">
                                            {{ $jenis->nama_bansos }} - {{ $jenis->kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('selectedJenis')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alasan Pengajuan -->
                            <div>
                                <label for="alasan_pengajuan" class="block text-sm font-medium text-gray-700">
                                    Alasan Pengajuan <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1">
                                    <textarea id="alasan_pengajuan"
                                        wire:model="alasan_pengajuan"
                                        rows="4"
                                        class="shadow-sm focus:ring-emerald-500 focus:border-emerald-500 block w-full border-gray-300 rounded-md"
                                        placeholder="Jelaskan alasan Anda membutuhkan bantuan ini..."></textarea>
                                </div>
                                @error('alasan_pengajuan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500">
                                    Minimal 10 karakter. Jelaskan kondisi dan alasan Anda membutuhkan bantuan ini.
                                </p>
                            </div>

                            <!-- Keterangan Tambahan -->
                            <div>
                                <label for="keterangan" class="block text-sm font-medium text-gray-700">
                                    Keterangan Tambahan
                                </label>
                                <div class="mt-1">
                                    <textarea id="keterangan"
                                        wire:model="keterangan"
                                        rows="3"
                                        class="shadow-sm focus:ring-emerald-500 focus:border-emerald-500 block w-full border-gray-300 rounded-md"
                                        placeholder="Informasi tambahan yang perlu disampaikan..."></textarea>
                                </div>
                                @error('keterangan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bantuan Mendesak Toggle -->
                            <div class="flex items-center">
                                <button type="button"
                                    wire:click="$toggle('is_urgent')"
                                    @class([
                                        'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2',
                                        'bg-emerald-600' => $is_urgent,
                                        'bg-gray-200' => !$is_urgent,
                                    ])>
                                    <span @class([
                                        'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                        'translate-x-5' => $is_urgent,
                                        'translate-x-0' => !$is_urgent,
                                    ])></span>
                                </button>
                                <span class="ml-3 text-sm">
                                    <span class="font-medium text-gray-900">Bantuan Mendesak</span>
                                    <span class="text-gray-500">(Tandai jika bantuan ini sangat mendesak)</span>
                                </span>
                            </div>

                            <!-- File Upload Section -->
                            <div class="space-y-6">
                                <!-- Foto Rumah -->
                                <div class="p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-6">Foto Rumah</h3>
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
                                                <span class="text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB</span>
                                            </div>
                                            <input type="file" wire:model="foto_rumah" id="foto_rumah" class="hidden" accept="image/*">
                                        </label>

                                        <!-- Progress Bar -->
                                        <div x-show="isUploading" class="mt-4">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300" x-bind:style="`width: ${progress}%`"></div>
                                            </div>
                                            <p class="text-xs text-center text-gray-500 mt-2">Mengupload... <span x-text="progress"></span>%</p>
                                        </div>

                                        <!-- Preview Section -->
                                        @if ($foto_rumah && !$errors->has('foto_rumah'))
                                            <div class="mt-4">
                                                <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                                    @if(is_string($foto_rumah))
                                                        <img src="{{ Storage::url($foto_rumah) }}" class="h-24 w-24 object-cover rounded-lg">
                                                    @else
                                                        <img src="{{ $foto_rumah->temporaryUrl() }}" class="h-24 w-24 object-cover rounded-lg">
                                                    @endif
                                                    <div class="ml-4">
                                                        <p class="text-sm font-medium text-gray-900">Preview foto</p>
                                                        <button type="button" wire:click="$set('foto_rumah', null)"
                                                                class="mt-1 text-xs text-red-600 hover:text-red-800">
                                                            Hapus gambar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @error('foto_rumah')
                                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Dokumen Pendukung -->
                                <div class="p-6">
                                    <h3 class="text-lg font-medium text-gray-900 mb-6">Dokumen Pendukung</h3>
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
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-sm text-gray-600">
                                                    <span class="font-medium text-emerald-600">Klik untuk upload</span>
                                                    atau seret dan lepas
                                                </span>
                                                <span class="text-xs text-gray-500">Format: PDF, JPG, PNG. Maksimal 2MB</span>
                                            </div>
                                            <input type="file" wire:model="dokumen_pendukung" id="dokumen_pendukung" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                        </label>

                                        <!-- Progress Bar -->
                                        <div x-show="isUploading" class="mt-4">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-300" x-bind:style="`width: ${progress}%`"></div>
                                            </div>
                                            <p class="text-xs text-center text-gray-500 mt-2">Mengupload... <span x-text="progress"></span>%</p>
                                        </div>

                                        <!-- Preview Section -->
                                        @if ($dokumen_pendukung && !$errors->has('dokumen_pendukung'))
                                            <div class="mt-4">
                                                <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                                                    <div class="flex items-center justify-center h-24 w-24 bg-gray-100 rounded-lg">
                                                        <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="ml-4">
                                                        <p class="text-sm font-medium text-gray-900">Dokumen telah diunggah</p>
                                                        <button type="button" wire:click="$set('dokumen_pendukung', null)"
                                                                class="mt-1 text-xs text-red-600 hover:text-red-800">
                                                            Hapus dokumen
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @error('dokumen_pendukung')
                                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-sm hover:shadow-md hover:from-emerald-600 hover:to-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-300 disabled:opacity-70 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled"
                                    wire:target="ajukanBansos">
                                    <svg wire:loading wire:target="ajukanBansos" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span wire:loading.remove wire:target="ajukanBansos">Kirim Pengajuan</span>
                                    <span wire:loading wire:target="ajukanBansos">Mengirim...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <!-- Warning for Unverified Users -->
                <div class="rounded-md bg-yellow-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Verifikasi Data Diperlukan</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Untuk mengajukan bantuan sosial, akun Anda harus terhubung dengan data penduduk. Silakan hubungi admin desa untuk verifikasi data Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- SweetAlert Scripts -->
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
