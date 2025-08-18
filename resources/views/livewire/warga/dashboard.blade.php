<div class="">
    @php $suppressGlobalMessages = true; @endphp

    <x-slot name="header">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-emerald-800 leading-tight">
                {{ __('Dashboard Warga') }}
            </h2>
            @if($penduduk)
                <div class="text-sm bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full flex items-center mt-2 md:mt-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Terverifikasi</span>
                </div>
            @endif
        </div>
    </x-slot>

    <div>
        @section('flash-messages')
            @if (session()->has('message'))
                <div class="bg-green-50 p-4 border border-green-200 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif
        @endsection

        <div class="py-0 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <!-- Subtle background pattern -->
            <div class="absolute top-0 right-0 -z-10 opacity-5">
                <svg width="400" height="400" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <path fill="none" stroke="currentColor" stroke-width="0.5" d="M10,10 L90,10 L90,90 L10,90 Z" />
                    <circle cx="50" cy="50" r="40" stroke="currentColor" stroke-width="0.5" fill="none" />
                    <path fill="none" stroke="currentColor" stroke-width="0.5" d="M10,50 L90,50" />
                    <path fill="none" stroke="currentColor" stroke-width="0.5" d="M50,10 L50,90" />
                </svg>
            </div>

            <!-- Banner selamat datang modern & clean -->
            <div class="bg-white shadow-sm border border-gray-100 text-gray-800 rounded-xl p-4 mb-6 animate-fade-in-down">
                <div class="flex items-center">
                    <div class="flex-shrink-0 block">
                        <div class="p-2.5 bg-emerald-50 rounded-lg">
                            <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        @php
                            $profilDesa = \App\Models\ProfilDesa::first();
                            $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                            $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $tanggal = $hari[now()->dayOfWeek] . ', ' . now()->day . ' ' . $bulan[now()->month - 1] . ' ' . now()->year;
                        @endphp
                        <h2 class="text-lg sm:text-xl font-bold text-gray-800">Selamat Datang, {{ $penduduk ? $penduduk->nama : auth()->user()->name }}!</h2>
                        <p class="text-gray-500 text-sm sm:text-base">
                            {{ $tanggal }} - Website Resmi {{ $profilDesa ? $profilDesa->nama_desa : 'Desa Digital' }}
                        </p>
                    </div>
                </div>
                <div class="mt-3 border-t border-gray-100 pt-3">
                    <div class="flex items-center text-xs text-emerald-600">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Gunakan layanan desa digital untuk kemudahan akses informasi dan layanan publik</span>
                    </div>
                </div>
            </div>

            @if($verifikasiPending)
                <div class="bg-white rounded-xl overflow-hidden shadow-sm border-l-4 border-l-indigo-500 border border-gray-100 transition-all duration-300 hover:shadow-md mb-6 p-6">
                    <div class="flex items-start">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-indigo-100 flex-shrink-0">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-indigo-900 mb-2">Verifikasi Data Sedang Diproses</h3>
                            <p class="text-gray-600">
                                Pengajuan verifikasi data Anda sedang dalam proses review oleh admin desa. Kami akan memberi tahu Anda segera setelah verifikasi selesai.
                            </p>
                            <div class="mt-4 h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                                <div class="bg-indigo-500 h-full rounded-full w-1/2 animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($penduduk)
                <!-- Profile Summary - Modern Card -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md mb-6">
                    <div class="p-4 sm:p-6">
                        <!-- Layout mobile yang hanya tampil di mobile -->
                        <div class="flex md:hidden flex-col space-y-4">
                            <!-- Baris 1: Foto, Nama dan NIK -->
                            <div class="flex items-center">
                                <!-- Foto Profil -->
                                <div class="flex-shrink-0 mr-3">
                                    @if($user->profile_photo_path)
                                        <img src="{{ Storage::disk('public')->url($user->profile_photo_path) }}?v={{ time() }}"
                                             alt="{{ $penduduk->nama }}"
                                             class="h-16 w-16 rounded-full object-cover shadow-md border-2 border-white">
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 flex items-center justify-center text-white text-lg font-bold shadow-md">
                                            {{ substr($penduduk->nama, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Nama dan NIK -->
                                <div class="flex-grow">
                                    <h3 class="text-base font-bold text-gray-800 line-clamp-1">{{ $penduduk->nama }}</h3>
                                    <div class="inline-flex items-center bg-gray-50 text-gray-700 rounded-lg py-1 px-2 mt-1 w-fit">
                                        <div class="rounded-full p-1 bg-blue-100 mr-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                        </div>
                                        <span class="text-xs">{{ $penduduk->nik }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Baris 2: Alamat -->
                            <div class="inline-flex items-center bg-gray-50 text-gray-700 rounded-lg py-1.5 px-2.5 w-fit max-w-full">
                                <div class="rounded-full p-1 bg-emerald-100 mr-1.5 flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <span class="text-xs truncate">{{ $penduduk->alamat }}, RT/RW {{ $penduduk->rt_rw }}</span>
                            </div>
                        </div>

                        <!-- Layout desktop yang hanya tampil di desktop -->
                        <div class="hidden md:flex md:flex-row md:items-center">
                            <div class="flex-shrink-0 mr-5">
                                @if($user->profile_photo_path)
                                    <img src="{{ Storage::disk('public')->url($user->profile_photo_path) }}?v={{ time() }}"
                                         alt="{{ $penduduk->nama }}"
                                         class="h-24 w-24 rounded-full object-cover shadow-md border-2 border-white">
                                @else
                                    <div class="h-24 w-24 rounded-full bg-gradient-to-r from-emerald-400 to-emerald-500 flex items-center justify-center text-white text-xl font-bold shadow-md">
                                        {{ substr($penduduk->nama, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h3 class="text-xl font-bold text-gray-800">{{ $penduduk->nama }}</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <!-- NIK -->
                                    <div class="flex items-center text-gray-700 bg-gray-50 rounded-lg p-2 transition-all duration-300 hover:bg-gray-100 w-fit">
                                        <div class="rounded-full p-1.5 bg-blue-100 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                            </svg>
                                        </div>
                                        <span class="text-sm">NIK: <span class="font-medium">{{ $penduduk->nik }}</span></span>
                                    </div>

                                    <!-- Status Kepala Keluarga -->
                                    <div class="flex items-center text-gray-700 bg-gray-50 rounded-lg p-2 transition-all duration-300 hover:bg-gray-100 w-fit">
                                        <div class="rounded-full p-1.5 bg-amber-100 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm">Status: <span class="font-medium">{{ $penduduk->kepala_keluarga ? 'Kepala Keluarga' : 'Anggota Keluarga' }}</span></span>
                                    </div>

                                    <!-- Alamat -->
                                    <div class="flex items-center text-gray-700 bg-gray-50 rounded-lg p-2 transition-all duration-300 hover:bg-gray-100 w-fit">
                                        <div class="rounded-full p-1.5 bg-emerald-100 mr-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <span class="text-sm truncate">{{ $penduduk->alamat }}, RT/RW {{ $penduduk->rt_rw }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats - Clean Design with Hover Effects -->
                <div class="grid grid-cols-2 gap-3 md:gap-4 lg:grid-cols-4 lg:gap-6 mb-6">
                    <!-- Bansos Stat -->
                    <div class="bg-white rounded-xl p-3 sm:p-5 shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md hover:border-blue-200 group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 rounded-lg p-2 sm:p-3 bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <h5 class="text-xs sm:text-sm font-medium text-gray-500 mb-1 group-hover:text-blue-600 transition-all duration-300">Bantuan Sosial</h5>
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $bansos->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pengaduan Stat -->
                    <div class="bg-white rounded-xl p-3 sm:p-5 shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md hover:border-emerald-200 group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 rounded-lg p-2 sm:p-3 bg-emerald-100 text-emerald-600 group-hover:bg-emerald-200 transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <h5 class="text-xs sm:text-sm font-medium text-gray-500 mb-1 group-hover:text-emerald-600 transition-all duration-300">Pengaduan</h5>
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $pengaduan->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- UMKM Stat -->
                    <div class="bg-white rounded-xl p-3 sm:p-5 shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md hover:border-amber-200 group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 rounded-lg p-2 sm:p-3 bg-amber-100 text-amber-600 group-hover:bg-amber-200 transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <h5 class="text-xs sm:text-sm font-medium text-gray-500 mb-1 group-hover:text-amber-600 transition-all duration-300">UMKM</h5>
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ $umkm->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Layanan Stat -->
                    <div class="bg-white rounded-xl p-3 sm:p-5 shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md hover:border-teal-200 group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 rounded-lg p-2 sm:p-3 bg-teal-100 text-teal-600 group-hover:bg-teal-200 transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <h5 class="text-xs sm:text-sm font-medium text-gray-500 mb-1 group-hover:text-teal-600 transition-all duration-300">Informasi Layanan</h5>
                                <p class="text-xl sm:text-2xl font-bold text-gray-800">{{ isset($layanan) ? $layanan->count() : 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Section - Service Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Add your service cards here -->
                </div>
            @else
                <!-- No Verification Submitted - Modern Card with CTA -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm border-l-4 border-l-blue-500 border border-gray-100 transition-all duration-300 hover:shadow-md mb-6 p-6">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="flex-shrink-0 mb-4 md:mb-0">
                            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="md:ml-6 flex-grow">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Verifikasi Data Diperlukan</h3>
                            <p class="text-gray-600 mb-4">
                                Untuk mengakses layanan desa secara penuh, Anda perlu menyelesaikan verifikasi data kependudukan. Verifikasi ini hanya perlu dilakukan sekali.
                            </p>
                            <a href="{{ route('verifikasi-data') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Lengkapi Data Verifikasi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Feature Highlights - Modern Cards with Hover Effects -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Layanan Administrasi Card -->
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md group">
                        <div class="p-6">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-blue-100 text-blue-600 mb-4 transition-all duration-300 group-hover:bg-blue-600 group-hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2 text-gray-800 group-hover:text-blue-600 transition-all duration-300">Layanan Administrasi</h3>
                            <p class="text-gray-600 mb-4">Akses berbagai layanan administrasi desa dengan mudah dan cepat secara online tanpa perlu antre.</p>
                            <div class="pt-4 border-t border-gray-100">
                                <a href="#" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center group-hover:translate-x-1 transition-transform duration-300">
                                    Pelajari Lebih Lanjut
                                    <svg class="ml-1 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Pengaduan Warga Card -->
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md group">
                        <div class="p-6">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-emerald-100 text-emerald-600 mb-4 transition-all duration-300 group-hover:bg-emerald-600 group-hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2 text-gray-800 group-hover:text-emerald-600 transition-all duration-300">Pengaduan Warga</h3>
                            <p class="text-gray-600 mb-4">Sampaikan keluhan dan pengaduan dengan mudah dan pantau status penyelesaiannya secara transparan.</p>
                            <div class="pt-4 border-t border-gray-100">
                                <a href="#" class="text-emerald-600 hover:text-emerald-800 font-medium inline-flex items-center group-hover:translate-x-1 transition-transform duration-300">
                                    Pelajari Lebih Lanjut
                                    <svg class="ml-1 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- UMKM Desa Card -->
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md group">
                        <div class="p-6">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-amber-100 text-amber-600 mb-4 transition-all duration-300 group-hover:bg-amber-600 group-hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold mb-2 text-gray-800 group-hover:text-amber-600 transition-all duration-300">UMKM Desa</h3>
                            <p class="text-gray-600 mb-4">Promosikan produk UMKM Anda dan perluas jangkauan bisnis ke seluruh masyarakat desa dan sekitarnya.</p>
                            <div class="pt-4 border-t border-gray-100">
                                <a href="#" class="text-amber-600 hover:text-amber-800 font-medium inline-flex items-center group-hover:translate-x-1 transition-transform duration-300">
                                    Pelajari Lebih Lanjut
                                    <svg class="ml-1 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out;
        }
    </style>
</div>
