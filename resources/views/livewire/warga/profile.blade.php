@php
    use Illuminate\Support\Facades\Storage;

    // Definisikan URL foto dengan lebih jelas
    if ($user->profile_photo_path) {
        $photoUrl = Storage::disk('public')->url($user->profile_photo_path) . '?v=' . time();
    } else {
        $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF&size=150';
    }
@endphp

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if ($pendingVerification)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Perubahan data Anda sedang dalam proses verifikasi oleh admin desa. Beberapa layanan mungkin terbatas hingga verifikasi selesai.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Profile Header with Photo & Photo Management -->
            <div class="bg-white shadow-sm rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-24 sm:h-32"></div>
                <div class="px-4 sm:px-6 pb-4 sm:pb-6">
                    <div class="flex flex-col sm:flex-row items-center">
                        <div class="-mt-12 sm:-mt-16 flex flex-col justify-center items-center">
                            <!-- User Photo with Edit Button -->
                            <div class="relative mx-auto">
                                <!-- Foto profile dengan ukuran lebih kecil di mobile -->
                                <div class="relative inline-block">
                                    <img src="{{ $photoUrl }}" alt="{{ $user->name }}"
                                        class="rounded-full object-cover mx-auto border-4 border-white shadow-lg"
                                        style="width: 100px; height: 100px; background-color: #f3f4f6; @media (min-width: 640px) { width: 150px; height: 150px; }"
                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF&size=150'">

                                    <!-- Tombol kamera yang terintegrasi dengan foto - ukuran lebih kecil di mobile -->
                                    <button type="button"
                                        @click="$dispatch('open-photo-modal')"
                                        class="absolute bottom-0 right-0 p-1.5 sm:p-2 bg-emerald-600 rounded-full text-white shadow-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 sm:mt-6 sm:ml-6 text-center sm:text-left flex-grow">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                            <p class="text-sm sm:text-base text-gray-600">{{ $user->email }}</p>
                            @if($penduduk)
                                <div class="mt-2 flex flex-wrap gap-1.5 sm:gap-2 justify-center sm:justify-start">
                                    <span class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium bg-emerald-100 text-emerald-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Warga Terverifikasi
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium bg-gray-100 text-gray-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                        NIK: {{ $penduduk->nik }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Photo Modal with Preview -->
            <div x-data="{
                photoModalOpen: false,
                previewUrl: null,
                uploadProgress: false,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.previewFile(file);
                    }
                },
                previewFile(file) {
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previewUrl = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },
                clearPreview() {
                    this.previewUrl = null;
                    const fileInput = document.getElementById('photo-upload');
                    if (fileInput) fileInput.value = '';
                    @this.set('photo', null);
                }
            }"
            @open-photo-modal.window="photoModalOpen = true; previewUrl = null"
            @photoUploaded.window="photoModalOpen = false; clearPreview(); uploadProgress = false">

                <!-- Modal dialog -->
                <div x-show="photoModalOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-90"
                     class="fixed inset-0 z-50 overflow-y-auto"
                     style="display: none;"
                     @keydown.escape.window="photoModalOpen = false; clearPreview()">

                    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                        <!-- Background overlay -->
                        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="photoModalOpen = false; clearPreview()">
                            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <!-- Modal panel - prevent click propagation to overlay -->
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full md:w-2/3 w-full"
                             @click.stop>
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="w-full">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                Unggah Foto Profil
                                            </h3>
                                            <!-- Close button added for better UX -->
                                            <button type="button" @click="photoModalOpen = false; clearPreview()" class="text-gray-400 hover:text-gray-500">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <form wire:submit.prevent="updatePhoto">
                                            <!-- Upload area -->
                                            <div class="mb-5 relative">
                                                <!-- Loading indicator - posisi diperbaiki dengan centering yang lebih baik -->
                                                <div wire:loading wire:target="photo, updatePhoto" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 z-10 rounded-lg">
                                                    <div class="flex flex-col items-center">
                                                        <div class="animate-spin rounded-full h-10 w-10 border-4 border-emerald-500 border-t-transparent"></div>
                                                        <p class="mt-2 text-sm font-medium text-emerald-600">Mengunggah...</p>
                                                    </div>
                                                </div>

                                                <!-- Preview image when selected -->
                                                <div x-show="previewUrl" class="mb-4">
                                                    <div class="relative mx-auto w-40 h-40">
                                                        <img :src="previewUrl" class="w-full h-full object-cover rounded-full shadow-sm" alt="Preview">
                                                        <button type="button" @click="clearPreview"
                                                            class="absolute -top-2 -right-2 bg-white text-red-500 rounded-full p-1 shadow-md hover:bg-red-500 hover:text-white transition-colors duration-200">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Upload area when no preview - disederhanakan -->
                                                <div x-show="!previewUrl">
                                                    <label class="relative flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-emerald-300 bg-gray-50 hover:bg-gray-100 rounded-lg cursor-pointer transition-colors">
                                                        <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                                            <svg class="w-8 h-8 mb-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                            </svg>
                                                            <!-- Teks disederhanakan -->
                                                            <p class="text-xs text-gray-500">PNG, JPG atau GIF (Maks. 1MB)</p>
                                                        </div>
                                                        <input id="photo-upload" type="file" wire:model="photo" @change="handleFileSelect($event)" accept="image/*" class="hidden"/>
                                                    </label>
                                                </div>

                                                <!-- Show validation errors -->
                                                @error('photo')
                                                    <div class="p-2 bg-red-50 border border-red-200 rounded-lg text-red-600 text-xs flex items-center mt-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 flex-shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                        </svg>
                                                        <span>{{ $message }}</span>
                                                    </div>
                                                @enderror
                                            </div>

                                            <!-- Action buttons -->
                                            <div class="flex justify-end space-x-3">
                                                <button type="button"
                                                        @click="photoModalOpen = false; clearPreview()"
                                                        wire:loading.remove wire:target="updatePhoto"
                                                        class="px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                        wire:loading.remove wire:target="updatePhoto"
                                                        :disabled="!previewUrl"
                                                        :class="{ 'opacity-50 cursor-not-allowed': !previewUrl }"
                                                        class="px-3 py-1.5 bg-emerald-600 rounded-lg text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                                    Unggah Foto
                                                </button>
                                                <button type="button"
                                                        wire:loading wire:target="updatePhoto"
                                                        disabled
                                                        class="px-3 py-1.5 bg-emerald-600 rounded-lg text-sm font-medium text-white opacity-75 cursor-not-allowed">
                                                    Mengunggah...
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs for easier navigation - perbaikan untuk mobile -->
            <div x-data="{ activeTab: 'personal' }" class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button @click="activeTab = 'personal'"
                                :class="{ 'border-emerald-500 text-emerald-600': activeTab === 'personal',
                                         'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'personal' }"
                                class="w-1/2 py-3 sm:py-4 px-1 text-center border-b-2 font-medium text-xs sm:text-sm transition-colors">
                            Data Pribadi
                        </button>
                        <button @click="activeTab = 'account'"
                                :class="{ 'border-emerald-500 text-emerald-600': activeTab === 'account',
                                         'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'account' }"
                                class="w-1/2 py-3 sm:py-4 px-1 text-center border-b-2 font-medium text-xs sm:text-sm transition-colors">
                            Informasi Akun
                        </button>
                    </nav>
                </div>

                <!-- Personal Data Tab -->
                <div x-show="activeTab === 'personal'" class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-4 sm:mb-6">Data Pribadi</h3>

                @if (!$penduduk)
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Akun Anda belum terhubung dengan data penduduk. Silakan hubungi admin desa untuk verifikasi data.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <form wire:submit.prevent="updatePendudukData" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIK (Disabled - Read Only) -->
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                                <input type="text" id="nik" value="{{ $penduduk->nik }}" disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                <p class="mt-1 text-xs text-gray-500">NIK tidak dapat diubah</p>
                            </div>

                            <!-- KK (Disabled - Read Only) -->
                            <div>
                                <label for="kk" class="block text-sm font-medium text-gray-700">No. KK</label>
                                <input type="text" id="kk" value="{{ $penduduk->kk }}" disabled class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm">
                                <p class="mt-1 text-xs text-gray-500">Nomor KK tidak dapat diubah</p>
                            </div>

                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="nama" wire:model="nama" placeholder="Masukkan nama lengkap sesuai KTP" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('nama') border-red-300 @enderror">
                                @error('nama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <p class="mt-1 text-xs text-gray-500">Contoh: Ahmad Budiman</p>
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir <span class="text-red-500">*</span></label>
                                <input type="text" id="tempat_lahir" wire:model="tempat_lahir" placeholder="Kota/Kabupaten tempat lahir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('tempat_lahir') border-red-300 @enderror">
                                @error('tempat_lahir') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <p class="mt-1 text-xs text-gray-500">Contoh: Jakarta</p>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_lahir" wire:model="tanggal_lahir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('tanggal_lahir') border-red-300 @enderror">
                                @error('tanggal_lahir') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <p class="mt-1 text-xs text-gray-500">Format: YYYY-MM-DD</p>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select id="jenis_kelamin" wire:model="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('jenis_kelamin') border-red-300 @enderror">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Golongan Darah -->
                            <div>
                                <label for="golongan_darah" class="block text-sm font-medium text-gray-700">Golongan Darah</label>
                                <select id="golongan_darah" wire:model="golongan_darah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="">Pilih Golongan Darah</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="Belum Diketahui">Belum Diketahui</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak diketahui</p>
                            </div>

                            <!-- Agama -->
                            <div>
                                <label for="agama" class="block text-sm font-medium text-gray-700">Agama <span class="text-red-500">*</span></label>
                                <select id="agama" wire:model="agama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('agama') border-red-300 @enderror">
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                @error('agama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Status Perkawinan -->
                            <div>
                                <label for="status_perkawinan" class="block text-sm font-medium text-gray-700">Status Perkawinan <span class="text-red-500">*</span></label>
                                <select id="status_perkawinan" wire:model="status_perkawinan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('status_perkawinan') border-red-300 @enderror">
                                    <option value="">Pilih Status Perkawinan</option>
                                    <option value="Belum Kawin">Belum Kawin</option>
                                    <option value="Kawin">Kawin</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                                @error('status_perkawinan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Pekerjaan -->
                            <div>
                                <label for="pekerjaan" class="block text-sm font-medium text-gray-700">Pekerjaan <span class="text-red-500">*</span></label>
                                <input type="text" id="pekerjaan" wire:model="pekerjaan" placeholder="Pekerjaan utama saat ini" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('pekerjaan') border-red-300 @enderror">
                                @error('pekerjaan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <p class="mt-1 text-xs text-gray-500">Contoh: Petani, Guru, Wiraswasta</p>
                            </div>

                            <!-- Pendidikan -->
                            <div>
                                <label for="pendidikan" class="block text-sm font-medium text-gray-700">Pendidikan <span class="text-red-500">*</span></label>
                                <select id="pendidikan" wire:model="pendidikan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('pendidikan') border-red-300 @enderror">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="Tidak Sekolah">Tidak Sekolah</option>
                                    <option value="SD/Sederajat">SD/Sederajat</option>
                                    <option value="SMP/Sederajat">SMP/Sederajat</option>
                                    <option value="SMA/Sederajat">SMA/Sederajat</option>
                                    <option value="D1/D2/D3">D1/D2/D3</option>
                                    <option value="D4/S1">D4/S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                                @error('pendidikan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- No HP -->
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700">Nomor HP <span class="text-red-500">*</span></label>
                                <input type="text" id="no_hp" wire:model="no_hp" placeholder="08xxxxxxxxxx" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('no_hp') border-red-300 @enderror">
                                @error('no_hp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <p class="mt-1 text-xs text-gray-500">Nomor HP aktif yang dapat dihubungi</p>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" wire:model="email" placeholder="contoh@gmail.com" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('email') border-red-300 @enderror">
                                @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <p class="mt-1 text-xs text-gray-500">Email aktif untuk notifikasi</p>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat <span class="text-red-500">*</span></label>
                            <textarea id="alamat" wire:model="alamat" rows="2" placeholder="Alamat lengkap tempat tinggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('alamat') border-red-300 @enderror"></textarea>
                            @error('alamat') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="mt-1 text-xs text-gray-500">Contoh: Jl. Merdeka No. 123, Dusun Cempaka</p>
                        </div>

                        <!-- RT/RW -->
                        <div>
                            <label for="rt_rw" class="block text-sm font-medium text-gray-700">RT/RW <span class="text-red-500">*</span></label>
                            <input type="text" id="rt_rw" wire:model="rt_rw" placeholder="001/002" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('rt_rw') border-red-300 @enderror">
                            @error('rt_rw') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="mt-1 text-xs text-gray-500">Format: 000/000</p>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                <span class="text-red-500">*</span> Wajib diisi dengan data yang benar
                            </span>
                            <button type="submit" {{ $pendingVerification ? 'disabled' : '' }} class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ $pendingVerification ? 'Menunggu Verifikasi' : 'Perbarui Data' }}
                            </button>
                        </div>
                    </form>
                @endif
            </div>

                <!-- Account Tab -->
                <div x-show="activeTab === 'account'" class="p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-4 sm:mb-6">Informasi Akun</h3>

                    <!-- Update Password Form -->
                    <section class="mb-8 p-6 bg-white rounded-lg border border-gray-100 shadow-sm">
                        <header>
                            <h4 class="text-md font-medium text-gray-700">Ubah Password</h4>
                            <p class="mt-1 text-sm text-gray-600">
                                Pastikan akun Anda menggunakan password yang aman.
                            </p>
                        </header>

                        <form wire:submit.prevent="updatePassword" class="mt-4 space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                                <input id="current_password" type="password" wire:model="current_password" placeholder="Masukkan password saat ini" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('current_password') border-red-300 @enderror">
                                @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                <input id="password" type="password" wire:model="password" placeholder="Minimal 8 karakter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 @error('password') border-red-300 @enderror">
                                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                <p class="mt-1 text-xs text-gray-500">Gunakan kombinasi huruf, angka, dan simbol</p>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input id="password_confirmation" type="password" wire:model="password_confirmation" placeholder="Ulangi password baru" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                                    Perbarui Password
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts and styles remain unchanged -->
    <script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('showAlert', (data) => {
            // Give a small delay to ensure modal is closed first
            setTimeout(() => {
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
            }, 300); // 300ms delay
        });

        // Tambahkan listener untuk event photoUploaded
        @this.on('photoUploaded', () => {
            console.log('Foto berhasil diupload, menutup modal dan memperbarui tampilan');

            // 1. Paksa menutup modal melalui Alpine.js
            window.dispatchEvent(new CustomEvent('photoUploaded'));

            // 2. Tunggu sebentar lalu reload gambar
            setTimeout(() => {
                // Ambil elemen gambar profil
                const profileImg = document.querySelector('.relative.inline-block img');
                if (profileImg) {
                    // Simpan URL asli (tanpa parameter)
                    const originalSrc = profileImg.src.split('?')[0];
                    // Tambahkan timestamp baru untuk bypass cache
                    profileImg.src = originalSrc + '?reload=' + new Date().getTime();

                    console.log('Memuat ulang gambar: ' + profileImg.src);
                }

                // 3. Perbarui juga variabel PHP photoUrl dengan memuat ulang halaman setelah delay
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }, 200);
        });
    });
    </script>

</div>
