<div>
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-gradient-to-r from-emerald-600 to-emerald-400 p-1.5 rounded-lg shadow-sm mr-3">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Verifikasi Data Kependudukan') }}
            </h2>
        </div>
    </x-slot>

    <div class="pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session()->has('message'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($verifikasiPending)
                {{-- Tampilan setelah data dikirim --}}
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    {{-- ... (kode untuk menampilkan data yang sudah dikirim tidak berubah) ... --}}
                </div>
            @else
                {{-- Tampilan form jika data belum dikirim --}}
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        {{-- ... (header form) ... --}}
                    </div>

                    <form wire:submit="submit" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIK -->
                            <div>
                                <x-input-label for="nik" value="NIK" />
                                <x-text-input wire:model="nik" id="nik" type="text" class="block w-full"
                                    maxlength="16" required />
                                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                            </div>
                            <!-- Nomor KK -->
                            <div>
                                <x-input-label for="kk" value="Nomor KK" />
                                <x-text-input wire:model="kk" id="kk" type="text" class="block w-full"
                                    maxlength="16" required />
                                <x-input-error :messages="$errors->get('kk')" class="mt-2" />
                            </div>
                            <!-- Nama Lengkap -->
                            <div class="md:col-span-2">
                                <x-input-label for="nama" value="Nama Lengkap" />
                                <x-text-input wire:model="nama" id="nama" type="text" class="block w-full"
                                    required />
                                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                            </div>
                            <!-- Email -->
                            <div>
                                <x-input-label for="email" value="Email" />
                                <x-text-input wire:model="email" id="email" type="email" class="block w-full"
                                    required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <!-- No. HP -->
                            <div>
                                <x-input-label for="no_hp" value="Nomor HP" />
                                <x-text-input wire:model="no_hp" id="no_hp" type="text" class="block w-full" />
                                <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                            </div>
                            <!-- Tempat Lahir -->
                            <div>
                                <x-input-label for="tempat_lahir" value="Tempat Lahir" />
                                <x-text-input wire:model="tempat_lahir" id="tempat_lahir" type="text"
                                    class="block w-full" required />
                                <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                            </div>
                            <!-- Tanggal Lahir -->
                            <div>
                                <x-input-label for="tanggal_lahir" value="Tanggal Lahir" />
                                <x-text-input wire:model="tanggal_lahir" id="tanggal_lahir" type="date"
                                    class="block w-full" required />
                                <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                            </div>
                            <!-- Jenis Kelamin -->
                            <div>
                                <x-input-label for="jenis_kelamin" value="Jenis Kelamin" />
                                <select wire:model="jenis_kelamin" id="jenis_kelamin"
                                    class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Pilih...</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                            </div>
                            <!-- Golongan Darah -->
                            <div>
                                <x-input-label for="golongan_darah" value="Golongan Darah" />
                                <select wire:model="golongan_darah" id="golongan_darah"
                                    class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Pilih...</option>
                                    <option value="A">A</option>
                                    <option value="A-">A-</option>
                                    <option value="B">B</option>
                                    <option value="B-">B-</option>
                                    <option value="AB">AB</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O">O</option>
                                    <option value="O-">O-</option>
                                    <option value="Tidak Tahu">Tidak Tahu</option>
                                </select>
                                <x-input-error :messages="$errors->get('golongan_darah')" class="mt-2" />
                            </div>
                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <x-input-label for="alamat" value="Alamat" />
                                <textarea wire:model="alamat" id="alamat" class="block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                            </div>
                            <!-- RT/RW -->
                            <div>
                                <x-input-label for="rt_rw" value="RT/RW" />
                                <x-text-input wire:model="rt_rw" id="rt_rw" type="text"
                                    class="block w-full" />
                                <x-input-error :messages="$errors->get('rt_rw')" class="mt-2" />
                            </div>
                            <!-- Agama -->
                            <div>
                                <x-input-label for="agama" value="Agama" />
                                <select wire:model="agama" id="agama"
                                    class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Pilih...</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                                <x-input-error :messages="$errors->get('agama')" class="mt-2" />
                            </div>
                            <!-- Status Perkawinan -->
                            <div>
                                <x-input-label for="status_perkawinan" value="Status Perkawinan" />
                                <select wire:model="status_perkawinan" id="status_perkawinan"
                                    class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Pilih...</option>
                                    <option value="Belum Kawin">Belum Kawin</option>
                                    <option value="Kawin">Kawin</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                                <x-input-error :messages="$errors->get('status_perkawinan')" class="mt-2" />
                            </div>
                            <!-- Pekerjaan -->
                            <div>
                                <x-input-label for="pekerjaan" value="Pekerjaan" />
                                <x-text-input wire:model="pekerjaan" id="pekerjaan" type="text"
                                    class="block w-full" />
                                <x-input-error :messages="$errors->get('pekerjaan')" class="mt-2" />
                            </div>
                            <!-- Pendidikan -->
                            <div>
                                <x-input-label for="pendidikan" value="Pendidikan" />
                                <select wire:model="pendidikan" id="pendidikan"
                                    class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                                    <option value="">Pilih...</option>
                                    <option value="Tidak Sekolah">Tidak Sekolah</option>
                                    <option value="Belum Sekolah">Belum Sekolah</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="D1">D1</option>
                                    <option value="D2">D2</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                                <x-input-error :messages="$errors->get('pendidikan')" class="mt-2" />
                            </div>
                            <!-- Kepala Keluarga -->
                            <div class="md:col-span-2">
                                <label for="kepala_keluarga" class="flex items-center">
                                    <input wire:model="kepala_keluarga" id="kepala_keluarga" type="checkbox"
                                        class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                                    <span class="ml-2 text-sm text-gray-600">Saya adalah Kepala Keluarga</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700">
                                <div wire:loading wire:target="submit"
                                    class="animate-spin -ml-1 mr-3 h-5 w-5 text-white">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <span wire:loading.remove>Kirim Data</span>
                                <span wire:loading>Mengirim...</span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
