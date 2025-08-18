<div>
    <x-slot name="header">
        <div class="flex items-center justify-between py-1">
            <h2 class="text-lg sm:text-xl font-medium text-gray-800 truncate">Detail Bantuan</h2>

            <a href="{{ route('warga.bansos') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-emerald-600 hover:text-emerald-800 border border-emerald-200 hover:bg-emerald-50 transition-all duration-300">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                Kembali
                </a>
        </div>
    </x-slot>

    <!-- Blok utama dengan Alpine.js yang mencakup seluruh komponen -->
    <div x-data="{
        showModal: false,
        showAllHistory: false,
        closeModal() { this.showModal = false; }
    }" @closeModal.window="closeModal">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kolom 1 & 2: Informasi Utama -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Informasi Program Bantuan -->
                    <div class="bg-white overflow-hidden rounded-lg shadow-sm border border-gray-100">
                <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 bg-emerald-50 p-2 rounded-full mr-3">
                                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        </div>
                                <h2 class="text-lg font-medium text-gray-900">{{ $bansos->jenisBansos->nama_bansos }}</h2>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $bansos->jenisBansos->kategori }}
                                </span>
                        </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="flex items-center">
                                    <div class="bg-gray-50 rounded-lg p-2 mr-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Instansi Pemberi</div>
                                        <div class="font-medium">{{ $bansos->jenisBansos->instansi_pemberi }}</div>
                            </div>
                        </div>

                                <div class="flex items-center">
                                    <div class="bg-gray-50 rounded-lg p-2 mr-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                                        </svg>
                        </div>
                        <div>
                                        <div class="text-xs text-gray-500">Bentuk Bantuan</div>
                                        <div class="font-medium">{{ $bansos->jenisBansos->bentuk_bantuan }}</div>
                        </div>
                        </div>

                                <div class="flex items-center">
                                    <div class="bg-gray-50 rounded-lg p-2 mr-3">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                        </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Periode Bantuan</div>
                                        <div class="font-medium">{{ $bansos->jenisBansos->periode }}</div>
                </div>
            </div>

                                <div class="flex items-center">
                                    <div class="bg-gray-50 rounded-lg p-2 mr-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                    </div>
                                <div>
                                        <div class="text-xs text-gray-500">Nilai Bantuan</div>
                                        <div class="font-medium">{{ $bansos->jenisBansos->getNilaiBantuanFormatted() }}</div>
                                </div>
                            </div>
                        </div>

                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $bansos->jenisBansos->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $bansos->jenisBansos->is_active ? 'Program Aktif' : 'Program Tidak Aktif' }}
                                    </span>

                                    @if($bansos->is_urgent)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            Bantuan Mendesak
                                </span>
                                    @endif
                        </div>

                                @if($bansos->alasan_pengajuan)
                                    <div class="mt-4 p-3 bg-gray-50 rounded-md">
                                        <div class="text-xs text-gray-500 mb-1">Alasan Pengajuan</div>
                                        <div class="text-sm">{{ $bansos->alasan_pengajuan }}</div>
                                    </div>
                                @endif

                                @if($bansos->keterangan)
                                    <div class="mt-3 p-3 bg-gray-50 rounded-md">
                                        <div class="text-xs text-gray-500 mb-1">Keterangan Tambahan</div>
                                        <div class="text-sm">{{ $bansos->keterangan }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Progress Bantuan -->
                    <div class="bg-white overflow-hidden rounded-lg shadow-sm border border-gray-100">
                <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tracking Progress Bantuan
                    </h3>

                    <!-- Status Terkini dengan Tombol Aksi -->
                            <div class="mb-8">
                                @if($bansos->status === 'Disetujui')
                                    <div class="p-4 bg-green-50 rounded-lg mb-6 border border-green-100">
                                        <div class="flex">
                                            <svg class="h-6 w-6 text-green-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <div class="ml-3">
                                                <div class="flex justify-between">
                                                    <h3 class="text-sm font-medium text-green-800">Bantuan Telah Disetujui</h3>
                                                </div>
                                                <div class="mt-2 text-sm text-green-700">
                                                    <p>Anda dapat mengambil bantuan sebelum {{ $bansos->tenggat_pengambilan?->format('d F Y') ?: 'batas waktu yang ditentukan' }}</p>

                                                    @if($bansos->lokasi_pengambilan)
                                                    <div class="mt-2 p-2 bg-white bg-opacity-50 rounded-md flex items-start">
                                                        <svg class="flex-shrink-0 w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <div class="ml-2">
                                                            <span class="text-sm font-medium text-green-800">Lokasi Pengambilan:</span>
                                                            <p class="text-sm text-green-700">{{ $bansos->lokasi_pengambilan }}</p>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="mt-4">
                                                    <button @click="showModal = true"
                                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Konfirmasi Penerimaan
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Status Summary -->
                                <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-700">Status Terkini</h4>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($bansos->status == 'Diajukan') bg-yellow-100 text-yellow-800
                                        @elseif($bansos->status == 'Dalam Verifikasi') bg-blue-100 text-blue-800
                                        @elseif($bansos->status == 'Diverifikasi') bg-indigo-100 text-indigo-800
                                        @elseif($bansos->status == 'Disetujui') bg-green-100 text-green-800
                                        @elseif($bansos->status == 'Ditolak') bg-red-100 text-red-800
                                        @elseif($bansos->status == 'Sudah Diterima') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                            {{ $bansos->status }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Timeline Progress dengan Show More -->
                                <div class="relative">
                                    <div class="border-l-2 border-gray-200 ml-3">
                                        @php
                                            $totalRiwayat = $bansos->riwayatStatus->sortByDesc('waktu_perubahan')->count();
                                            $initialDisplay = 3; // Jumlah riwayat yang ditampilkan awalnya
                                        @endphp

                                        @foreach($bansos->riwayatStatus->sortByDesc('waktu_perubahan') as $index => $riwayat)
                                            <div x-show="showAllHistory || {{ $index }} < {{ $initialDisplay }}"
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 transform translate-y-4"
                                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                                 class="relative mb-8 ml-6">
                                                <div class="absolute -left-[2.4rem] mt-1.5">
                                                    <span class="flex items-center justify-center w-7 h-7 rounded-full
                                                        @if($riwayat->status_baru == 'Diajukan') bg-yellow-100
                                                        @elseif($riwayat->status_baru == 'Dalam Verifikasi') bg-blue-100
                                                        @elseif($riwayat->status_baru == 'Diverifikasi') bg-indigo-100
                                                        @elseif($riwayat->status_baru == 'Disetujui') bg-green-100
                                                        @elseif($riwayat->status_baru == 'Ditolak') bg-red-100
                                                        @elseif($riwayat->status_baru == 'Sudah Diterima') bg-purple-100
                                                        @else bg-gray-100
                                                        @endif border
                                                        @if($riwayat->status_baru == 'Diajukan') border-yellow-200
                                                        @elseif($riwayat->status_baru == 'Dalam Verifikasi') border-blue-200
                                                        @elseif($riwayat->status_baru == 'Diverifikasi') border-indigo-200
                                                        @elseif($riwayat->status_baru == 'Disetujui') border-green-200
                                                        @elseif($riwayat->status_baru == 'Ditolak') border-red-200
                                                        @elseif($riwayat->status_baru == 'Sudah Diterima') border-purple-200
                                                        @else border-gray-200
                                                        @endif">
                                                        <svg class="w-4 h-4
                                                            @if($riwayat->status_baru == 'Diajukan') text-yellow-600
                                                            @elseif($riwayat->status_baru == 'Dalam Verifikasi') text-blue-600
                                                            @elseif($riwayat->status_baru == 'Diverifikasi') text-indigo-600
                                                            @elseif($riwayat->status_baru == 'Disetujui') text-green-600
                                                            @elseif($riwayat->status_baru == 'Ditolak') text-red-600
                                                            @elseif($riwayat->status_baru == 'Sudah Diterima') text-purple-600
                                                            @else text-gray-600
                                                            @endif"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            @if($riwayat->status_baru == 'Disetujui' || $riwayat->status_baru == 'Sudah Diterima')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                            @elseif($riwayat->status_baru == 'Ditolak')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            @endif
                                        </svg>
                                                    </span>
                                                </div>

                                                <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm hover:shadow transition-shadow">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="inline-flex items-center text-sm font-medium
                                                            @if($riwayat->status_baru == 'Diajukan') text-yellow-700
                                                            @elseif($riwayat->status_baru == 'Dalam Verifikasi') text-blue-700
                                                            @elseif($riwayat->status_baru == 'Diverifikasi') text-indigo-700
                                                            @elseif($riwayat->status_baru == 'Disetujui') text-green-700
                                                            @elseif($riwayat->status_baru == 'Ditolak') text-red-700
                                                            @elseif($riwayat->status_baru == 'Sudah Diterima') text-purple-700
                                                            @else text-gray-700
                                                            @endif">
                                                            {{ $riwayat->status_baru }}
                                                        </span>
                                                        <time class="text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-full">
                                                            {{ $riwayat->waktu_perubahan->format('d M Y H:i') }}
                                                        </time>
                                                    </div>

                                                    @if($riwayat->keterangan)
                                                        <p class="text-sm text-gray-600">{{ $riwayat->keterangan }}</p>
                                                    @endif

                                                    @if($riwayat->status_baru === 'Disetujui' && $bansos->lokasi_pengambilan)
                                                        <div class="mt-3 p-3 bg-blue-50 rounded-md text-blue-700 text-sm">
                                                            <div class="flex items-start">
                                                                <svg class="flex-shrink-0 w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                </svg>
                                                                <div class="ml-2">
                                                                    <span class="font-medium">Lokasi Pengambilan:</span>
                                                                    <p>{{ $bansos->lokasi_pengambilan }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        <!-- Tombol Lihat Lebih Banyak -->
                                        @if($totalRiwayat > $initialDisplay)
                                            <div class="ml-6 mb-4">
                                                <button @click="showAllHistory = !showAllHistory"
                                                        class="flex items-center justify-center w-full py-2 px-4 bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 rounded-md border border-gray-200 transition-colors">
                                                    <template x-if="!showAllHistory">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                            </svg>
                                                            <span>Lihat Semua Riwayat ({{ $totalRiwayat }})</span>
                                                        </div>
                                                    </template>
                                                    <template x-if="showAllHistory">
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                            </svg>
                                                            <span>Sembunyikan</span>
                                                        </div>
                                                    </template>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom 3: Informasi Penerima dan Sidebar -->
                <div class="space-y-6">
                    <!-- Informasi Penerima Bantuan -->
                    <div class="bg-white overflow-hidden rounded-lg shadow-sm border border-gray-100">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Informasi Penerima
                            </h3>

                            <div class="space-y-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xl font-bold">
                                        {{ substr($bansos->penduduk->nama, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-lg font-medium text-gray-900">{{ $bansos->penduduk->nama }}</div>
                                        <div class="text-sm text-gray-500">NIK: {{ $bansos->penduduk->nik }}</div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 pt-4 grid grid-cols-1 gap-3">
                                    <div>
                                        <div class="text-xs text-gray-500">Tanggal Lahir</div>
                                        <div class="mt-1 text-sm">{{ $bansos->penduduk->tanggal_lahir?->format('d F Y') }}</div>
                                    </div>

                                    <div>
                                        <div class="text-xs text-gray-500">Desa</div>
                                        <div class="mt-1 text-sm">{{ $bansos->desa->nama_desa }}</div>
                                    </div>

                                    <div>
                                        <div class="text-xs text-gray-500">Alamat</div>
                                        <div class="mt-1 text-sm">{{ $bansos->penduduk->alamat }}</div>
                                    </div>

                                    <!-- Nomor HP -->
                                    <div>
                                        <div class="text-xs text-gray-500">Nomor HP</div>
                                        <div class="mt-1 text-sm flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ $bansos->penduduk->no_hp ?? $bansos->penduduk->telepon ?? '(Belum diisi)' }}
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <div class="text-xs text-gray-500">Email</div>
                                        <div class="mt-1 text-sm flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            {{ $bansos->penduduk->email ?? '(Belum diisi)' }}
                                        </div>
                                    </div>

                                    <div>
                                        <div class="text-xs text-gray-500">Diajukan Oleh</div>
                                        <div class="mt-1 flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $bansos->sumber_pengajuan == 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $bansos->sumber_pengajuan == 'admin' ? 'Admin/Petugas' : 'Warga' }}
                                            </span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tanggal Penting -->
                    <div class="bg-white overflow-hidden rounded-lg shadow-sm border border-gray-100">
                        <div class="p-6">
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Tanggal Penting</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">Tanggal Pengajuan</div>
                                    <div class="text-sm font-medium">{{ $bansos->tanggal_pengajuan->format('d M Y') }}</div>
                                </div>

                                @if($bansos->tanggal_penerimaan)
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">Tanggal Penerimaan</div>
                                    <div class="text-sm font-medium">{{ $bansos->tanggal_penerimaan->format('d M Y') }}</div>
                                </div>
                                @endif

                                @if($bansos->tenggat_pengambilan && in_array($bansos->status, ['Disetujui']))
                                <div class="flex justify-between items-center">
                                    <div class="text-xs text-gray-500">Batas Pengambilan</div>
                                    <div class="text-sm font-medium text-red-600">{{ $bansos->tenggat_pengambilan->format('d M Y') }}</div>
                                </div>
                                @endif

                                <!-- Menambahkan lokasi pengambilan -->
                                @if($bansos->lokasi_pengambilan && in_array($bansos->status, ['Disetujui', 'Diverifikasi']))
                                <div class="pt-2 mt-1 border-t border-gray-100">
                                    <div class="text-xs text-gray-500 mb-1">Lokasi Pengambilan</div>
                                    <div class="flex items-start">
                                        <svg class="flex-shrink-0 w-4 h-4 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <div class="ml-2 text-sm text-blue-700 break-words">{{ $bansos->lokasi_pengambilan }}</div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                            </div>

            <!-- Modal Konfirmasi Penerimaan -->
                            <div x-show="showModal"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-50 overflow-y-auto"
                                 style="display: none;">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <!-- Background overlay -->
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"></div>

                    <!-- Modal content -->
                    <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                        <!-- Modal content -->
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900">
                                                    Konfirmasi Penerimaan Bantuan
                                                </h3>

                                                <div class="mt-4">
                                                    <p class="text-sm text-gray-500">
                                                        Silakan upload bukti penerimaan bantuan (foto saat menerima bantuan)
                                                    </p>

                                                    <div class="mt-4">
                                                        <div class="flex items-center justify-center w-full">
                                                            <div class="w-full">
                                                                <label class="flex flex-col w-full h-48 border-2 border-dashed border-emerald-300 hover:bg-gray-50 hover:border-emerald-400 rounded-lg transition-all duration-200 ease-in-out cursor-pointer group">
                                                                    <div class="relative flex flex-col items-center justify-center pt-7 h-full">
                                                                        @if($buktiPenerimaan)
                                                                            @if($buktiPenerimaan->temporaryUrl())
                                                                                <div class="relative w-full h-full flex items-center justify-center">
                                                                                    <img src="{{ $buktiPenerimaan->temporaryUrl() }}" class="h-40 object-contain rounded-lg" alt="Preview">
                                                                                    <button type="button" wire:click="$set('buktiPenerimaan', null)"
                                                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                                        </svg>
                                                                                    </button>
                                                                                </div>
                                                                            @endif
                                                                        @else
                                                                            <div class="flex flex-col items-center">
                                                                                <svg class="w-12 h-12 text-emerald-400 group-hover:text-emerald-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                                </svg>
                                                                                <p class="mt-4 text-sm text-gray-500 group-hover:text-gray-600 transition-colors duration-200">
                                                                                    <span class="font-semibold text-emerald-500 group-hover:text-emerald-600">Klik untuk upload</span> atau seret dan lepas
                                                                                </p>
                                                                                <p class="mt-1 text-xs text-gray-500">PNG, JPG atau GIF (Maks. 2MB)</p>
                                                                            </div>
                                                                            <div wire:loading wire:target="buktiPenerimaan" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 rounded-lg">
                                                                                <div class="flex items-center space-x-2">
                                                                                    <svg class="animate-spin h-6 w-6 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                                                    </svg>
                                                                                    <span class="text-sm text-emerald-500">Mengupload...</span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <input type="file" wire:model="buktiPenerimaan" class="hidden" accept="image/*"/>
                                                                </label>
                                                                @error('buktiPenerimaan')
                                                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                            <button wire:click="konfirmasiPenerimaan"
                                                    wire:loading.attr="disabled"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-600 text-base font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 transition-colors">
                                                <span wire:loading.remove wire:target="konfirmasiPenerimaan">Konfirmasi</span>
                                                <span wire:loading wire:target="konfirmasiPenerimaan">Memproses...</span>
                                            </button>
                                            <button @click="showModal = false" type="button"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                                                Batal
                                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert Scripts -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('closeModal', () => {
                window.dispatchEvent(new Event('closeModal'));
            });

            @this.on('showAlert', (data) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                if (data[0].icon === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: data[0].title,
                        text: data[0].text,
                        showConfirmButton: true,
                        confirmButtonColor: '#10B981',
                        customClass: {
                            confirmButton: 'px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500'
                        }
                    });
                } else {
                    Toast.fire({
                        icon: data[0].icon,
                        title: data[0].text,
                        customClass: {
                            popup: 'bg-white rounded-lg shadow-xl border border-gray-100',
                            title: 'text-sm font-medium text-gray-900'
                        }
                    });
                }
            });

            // Tambahkan listener untuk closeModal event dari Livewire
            @this.on('closeModal', () => {
                console.log('Menutup modal bukti penerimaan');
                // Dispatch event untuk Alpine.js
                window.dispatchEvent(new CustomEvent('closeModal'));

                // Sembunyikan modal secara langsung (solusi cadangan)
                const modals = document.querySelectorAll('[x-data]');
                modals.forEach(modal => {
                    if (modal.__x) {
                        if (modal.__x.$data.showModal !== undefined) {
                            modal.__x.$data.showModal = false;
                        }
                    }
                });
            });

            // Listener untuk konfirmasi penerimaan sukses
            @this.on('konfirmasiSuccess', () => {
                console.log('Konfirmasi penerimaan berhasil');

                // Tutup modal
                window.dispatchEvent(new CustomEvent('closeModal'));

                // Segarkan halaman setelah delay
                setTimeout(() => {
                    location.reload();
                }, 2000); // Tunggu 2 detik setelah alert muncul
            });
        });
    </script>
</div>
