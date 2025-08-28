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
            <div class="absolute left-0 right-0 -z-10 opacity-40 overflow-hidden">
                <div class="absolute top-20 -left-10 w-32 h-32 bg-emerald-100 rounded-full"></div>
                <div class="absolute top-40 right-10 w-24 h-24 bg-emerald-100 rounded-full"></div>
            </div>

            @if ($verifikasiPending)
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <div class="p-2 bg-amber-100 rounded-lg text-amber-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Data Verifikasi Anda Sedang Diproses</h3>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 flex items-start">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-amber-800">
                        <p class="font-medium">Data Anda sedang dalam peninjauan oleh admin.</p>
                        <p class="mt-1">Proses ini biasanya memakan waktu 1-3 hari kerja. Anda akan mendapatkan notifikasi jika status verifikasi berubah. Mohon untuk memeriksa kembali secara berkala.</p>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <div class="p-2 bg-emerald-100 rounded-lg text-emerald-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Formulir Verifikasi Data</h3>
                </div>

                <form wire:submit.prevent="submit" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="nik">NIK</x-input-label>
                            <x-text-input wire:model="nik" id="nik" type="text" class="block w-full bg-gray-100 cursor-not-allowed" readonly />
                            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="nama">Nama Lengkap</x-input-label>
                            <x-text-input wire:model="nama" id="nama" type="text" class="block w-full bg-gray-100 cursor-not-allowed" readonly />
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="kk">Nomor KK <span class="text-red-500">*</span></x-input-label>
                            <x-text-input wire:model.live="kk" id="kk" type="text" class="block w-full" maxlength="16" placeholder="16 digit nomor Kartu Keluarga" required />
                            <x-input-error :messages="$errors->get('kk')" class="mt-2" />
                        </div>

                        <div>
                            {{-- Label dinamis: Tanda bintang (*) hanya muncul jika email wajib diisi --}}
                            <x-input-label for="email">Email @if($hasRegisteredWithEmail) <span class="text-red-500">*</span> @endif</x-input-label>

                            <x-text-input
                                wire:model="email"
                                id="email"
                                type="email"
                                {{-- Class dan atribut readonly menjadi dinamis --}}
                                class="block w-full {{ $hasRegisteredWithEmail ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                :readonly="$hasRegisteredWithEmail"
                                placeholder="{{ !$hasRegisteredWithEmail ? 'Masukkan alamat email (opsional)' : '' }}" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tempat_lahir">Tempat Lahir <span class="text-red-500">*</span></x-input-label>
                            <x-text-input wire:model="tempat_lahir" id="tempat_lahir" type="text" class="block w-full" placeholder="Kota kelahiran" required />
                            <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tanggal_lahir">Tanggal Lahir <span class="text-red-500">*</span></x-input-label>
                            <x-text-input wire:model="tanggal_lahir" id="tanggal_lahir" type="date" class="block w-full" required />
                            <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="alamat">Alamat Lengkap <span class="text-red-500">*</span></x-input-label>
                            <x-text-input wire:model="alamat" id="alamat" type="text" class="block w-full" placeholder="Contoh: Jl. Merdeka No. 10" required />
                            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="rt_rw">RT/RW <span class="text-red-500">*</span></x-input-label>
                            <x-text-input wire:model="rt_rw" id="rt_rw" type="text" class="block w-full" placeholder="Contoh: 001/002" required />
                            <x-input-error :messages="$errors->get('rt_rw')" class="mt-2" />
                        </div>

                        <div>
                            {{-- Label dinamis: Tanda bintang (*) hanya muncul jika nomor HP wajib diisi --}}
                            <x-input-label for="no_hp">Nomor HP @if($hasRegisteredWithPhone) <span class="text-red-500">*</span> @endif</x-input-label>

                            <x-text-input
                                wire:model="no_hp"
                                id="no_hp"
                                type="text"
                                {{-- Class dan atribut readonly menjadi dinamis --}}
                                class="block w-full {{ $hasRegisteredWithPhone ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                :readonly="$hasRegisteredWithPhone"
                                placeholder="{{ !$hasRegisteredWithPhone ? 'Masukkan nomor HP (opsional)' : '08xxxxxxxxxx' }}" />
                            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="jenis_kelamin">Jenis Kelamin <span class="text-red-500">*</span></x-input-label>
                            <select wire:model="jenis_kelamin" id="jenis_kelamin" class="block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="agama">Agama <span class="text-red-500">*</span></x-input-label>
                            <select wire:model="agama" id="agama" class="block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                <option value="">Pilih Agama</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                            <x-input-error :messages="$errors->get('agama')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="status_perkawinan">Status Perkawinan <span class="text-red-500">*</span></x-input-label>
                            <select wire:model="status_perkawinan" id="status_perkawinan" class="block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                <option value="">Pilih Status</option>
                                <option value="Belum Kawin">Belum Kawin</option>
                                <option value="Kawin">Kawin</option>
                                <option value="Cerai Hidup">Cerai Hidup</option>
                                <option value="Cerai Mati">Cerai Mati</option>
                            </select>
                            <x-input-error :messages="$errors->get('status_perkawinan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="pendidikan">Pendidikan Terakhir <span class="text-red-500">*</span></x-input-label>
                            <select wire:model="pendidikan" id="pendidikan" class="block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                <option value="Tidak Sekolah">Tidak Sekolah</option>
                                <option value="Belum Sekolah">Belum Sekolah</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="D1">D1</option>
                                <option value="D2">D2</option>
                                <option value="D3">D3</option>
                                <option value="D4">D4/S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                            <x-input-error :messages="$errors->get('pendidikan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="pekerjaan">Pekerjaan <span class="text-red-500">*</span></x-input-label>
                            <x-text-input wire:model="pekerjaan" id="pekerjaan" type="text" class="block w-full" required />
                            <x-input-error :messages="$errors->get('pekerjaan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="golongan_darah">Golongan Darah <span class="text-red-500">*</span></x-input-label>
                            <select wire:model="golongan_darah" id="golongan_darah" class="block w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                                <option value="">Pilih Gol. Darah</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                                <option value="Tidak Tahu">Tidak Tahu</option>
                            </select>
                            <x-input-error :messages="$errors->get('golongan_darah')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="kepala_keluarga">Status dalam Keluarga <span class="text-red-500">*</span></x-input-label>
                            <div class="mt-2 space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" wire:model="kepala_keluarga" name="kepala_keluarga" value="1" class="form-radio text-emerald-600" required>
                                    <span class="ml-2">Kepala Keluarga</span>
                                </label>
                                <label class="inline-flex items-center ml-6">
                                    <input type="radio" wire:model="kepala_keluarga" name="kepala_keluarga" value="0" class="form-radio text-emerald-600" required>
                                    <span class="ml-2">Anggota Keluarga</span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('kepala_keluarga')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="$refresh" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                            Reset
                        </button>
                        <button type="submit" wire:loading.attr="disabled" wire:target="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 disabled:opacity-50">
                            <svg wire:loading wire:target="submit" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove wire:target="submit">Kirim Data Verifikasi</span>
                            <span wire:loading wire:target="submit">Memproses...</span>
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>