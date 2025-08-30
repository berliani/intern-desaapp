<div>
    <x-slot name="header">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-gradient-to-r from-emerald-600 to-emerald-400 p-1.5 rounded-lg shadow-sm mr-3">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verifikasi Data Kependudukan') }}
        </h2>
        </div>
    </x-slot>

    <div class="pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Decorative background elements -->
            <div class="absolute left-0 right-0 -z-10 opacity-40 overflow-hidden">
                <div class="absolute top-20 -left-10 w-32 h-32 bg-emerald-100 rounded-full"></div>
                <div class="absolute top-40 right-10 w-24 h-24 bg-emerald-100 rounded-full"></div>
            </div>

            @if (isset($verifikasiPending) && $verifikasiPending)
                <div class="bg-white shadow-lg rounded-xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="p-2 bg-emerald-100 rounded-lg text-emerald-700">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Data Verifikasi yang Telah Dikirim</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">NIK</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->nik }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Nomor KK</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->kk }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Nama Lengkap</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->nama }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Email</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->email }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Nomor HP</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->no_hp ?? '-' }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Tempat Lahir</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->tempat_lahir }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Tanggal Lahir</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->tanggal_lahir->format('d-m-Y') }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Jenis Kelamin</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Golongan Darah</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->golongan_darah ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2 border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Alamat</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->alamat }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">RT/RW</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->rt_rw }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Agama</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->agama }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Status Perkawinan</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->status_perkawinan }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Pekerjaan</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->pekerjaan ?? '-' }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Pendidikan Terakhir</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->pendidikan ?? '-' }}</p>
                        </div>
                        <div class="border-l-2 border-emerald-200 pl-3">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Status dalam Keluarga</span>
                            <p class="mt-1 font-medium">{{ $verifikasiPending->kepala_keluarga ? 'Kepala Keluarga' : 'Anggota Keluarga' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Status Verifikasi:</span>
                            <div class="mt-2 flex items-center">
                                <span class="flex items-center px-3 py-1.5 bg-amber-100 text-amber-800 rounded-full">
                                    <svg class="w-4 h-4 mr-1 animate-pulse" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-semibold">Menunggu Verifikasi</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mt-6 flex items-start">
                        <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-amber-800">
                            <p class="font-medium">Data Anda sedang diproses</p>
                            <p class="mt-1">Data akan diverifikasi oleh admin dalam waktu 1-3 hari kerja. Silakan periksa kembali secara berkala.</p>
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
                        @csrf <!-- CSRF protection -->

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NIK -->
                        <div>
                                <x-input-label for="nik" value="NIK" class="text-gray-700 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z" clip-rule="evenodd"></path>
                                    </svg>
                                    NIK
                                </x-input-label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" />
                                        </svg>
                                    </div>
                                    <x-text-input wire:model.live="nik" id="nik" type="text" class="pl-10 py-2.5 block w-full" maxlength="16" placeholder="16 digit" required />
                                </div>
                            <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                        </div>

                        <!-- Nomor KK -->
                        <div>
                                <x-input-label for="kk" value="Nomor KK" class="text-gray-700 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                    </svg>
                                    Nomor KK
                                </x-input-label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <x-text-input wire:model.live="kk" id="kk" type="text" class="pl-10 py-2.5 block w-full" maxlength="16" placeholder="16 digit" required />
                                </div>
                            <x-input-error :messages="$errors->get('kk')" class="mt-2" />
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                                <x-input-label for="nama" value="Nama Lengkap" class="text-gray-700 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                    Nama Lengkap
                                </x-input-label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <x-text-input wire:model.live="nama" id="nama" type="text" class="pl-10 py-2.5 block w-full" placeholder="Nama sesuai KTP" required />
                                </div>
                            <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                                <x-input-label for="email" value="Email" class="text-gray-700 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    Email
                                </x-input-label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                    <x-text-input wire:model.live="email" id="email" type="email" class="pl-10 py-2.5 block w-full" placeholder="nama@email.com" required />
                                </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- No. HP -->
                        <div>
                                <x-input-label for="no_hp" value="Nomor HP" class="text-gray-700 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.948.684l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                    </svg>
                                    Nomor HP
                                </x-input-label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <x-text-input wire:model.live="no_hp" id="no_hp" type="text" class="pl-10 py-2.5 block w-full" placeholder="08xxxxxxxxxx" />
                                </div>
                            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                                <x-input-label for="tempat_lahir" value="Tempat Lahir" class="text-gray-700 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tempat Lahir
                                </x-input-label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <x-text-input wire:model.live="tempat_lahir" id="tempat_lahir" type="text" class="pl-10 py-2.5 block w-full" placeholder="Kota kelahiran" required />
                                </div>
                            <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                                <x-input-label for="tanggal_lahir" value="Tanggal Lahir" class="text-gray-700 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                    </svg>
                                    Tanggal Lahir
                                </x-input-label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <x-text-input wire:model.live="tanggal_lahir" id="tanggal_lahir" type="date" class="pl-10 py-2.5 block w-full" required />
                                </div>
                            <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                        </div>

                         
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-8 pt-4 border-t border-gray-100">
                            <button type="button" wire:click="$refresh" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Reset
                            </button>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Kirim Data
                            </button>
                        </div>
                    </form>
                    </div>
            @endif
        </div>
    </div>
</div>