@extends('layouts.front')

@section('title', 'Beranda')

@section('content')
    <!-- Modern Hero Section with Improved Mobile Responsiveness -->
    <section class="relative overflow-hidden min-h-screen" x-data="modernCarousel()">
        <!-- Background Layer - Converting to img elements for lazy loading -->
        <div class="absolute inset-0 w-full h-full">
            @if(isset($profilDesa->thumbnails) && is_array($profilDesa->thumbnails) && count($profilDesa->thumbnails) > 0)
                @foreach($profilDesa->thumbnails as $index => $thumbnail)
                    <div x-show="currentSlide === {{ $index }}"
                         x-transition:enter="transition ease-out"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                         :style="'transition-duration: ' + transitionDuration + 'ms'"
                         class="absolute inset-0 w-full h-full overflow-hidden">
                        <img src="{{ Storage::url($thumbnail) }}"
                             alt="Slide {{ $index + 1 }}"
                             loading="lazy"
                             class="absolute inset-0 w-full h-full object-cover object-center">
                        <!-- Improved overlay for better text visibility -->
                        <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-black/10"></div>
                </div>
                @endforeach
            @else
                <!-- Fallback Background -->
                <div class="absolute inset-0 w-full h-full bg-gray-900"></div>
            @endif
        </div>

        <!-- Mobile Slide Indicators: Top Right Corner -->
        @if(isset($profilDesa->thumbnails) && is_array($profilDesa->thumbnails) && count($profilDesa->thumbnails) > 1)
        <div class="absolute top-4 right-4 z-30 md:hidden">
            <div class="flex items-center gap-1.5">
                @foreach($profilDesa->thumbnails as $index => $thumbnail)
                    <button @click="currentSlide = {{ $index }}"
                            class="h-1.5 transition-all duration-300 focus:outline-none rounded-full overflow-hidden"
                            :class="{'w-6 bg-white': currentSlide === {{ $index }}, 'w-3 bg-white/30 hover:bg-white/50': currentSlide !== {{ $index }}}">
                    </button>
                @endforeach
                </div>
        </div>
        @endif

        <!-- DESKTOP LAYOUT (Hidden on mobile) -->
        <div class="absolute inset-0 hidden md:grid md:grid-cols-12 z-10">
            <!-- Main Content Area (8 cols on desktop) -->
            <div class="md:col-span-8 flex items-center">
                <div class="w-full max-w-4xl px-8 py-16">
                    <!-- Modern Badge -->
                    <div class="relative inline-flex items-center px-5 py-2.5 rounded-lg bg-gradient-to-r from-emerald-500/20 to-emerald-600/20 backdrop-blur-md border border-white/20 shadow-lg group overflow-hidden" data-aos="fade-down" data-aos-duration="800">
                        <!-- Animated Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-400/30 to-emerald-600/30 rounded-lg blur opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>



                        <!-- Badge Content -->
                        <span class="relative z-10 text-white font-semibold text-sm uppercase tracking-wider flex items-center">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full mr-2.5 animate-pulse"></span>
                            DESA DIGITAL
                        </span>
                    </div>

                    <!-- Clear, Bold Typography -->
                    <h1 class="mt-3 text-6xl md:text-7xl font-bold text-white leading-none" data-aos="fade-up" data-aos-duration="800">
                        @php
                            $namaDesa = $profilDesa->nama_desa ?? 'Digital';
                            if (Str::startsWith(strtolower($namaDesa), 'desa ')) {
                                echo $namaDesa;
                            } else {
                                echo 'Desa ' . $namaDesa;
                            }
                        @endphp
                </h1>

                    <!-- Clear Subtitle with Better Contrast -->
                    <p class="mt-5 text-white text-xl font-light max-w-2xl leading-relaxed" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
                    @if($profilDesa->kecamatan && $profilDesa->kabupaten)
                        @php
                            // Formatting code untuk lokasi
                            $kecamatan = $profilDesa->kecamatan;
                            if (!Str::startsWith(strtolower($kecamatan), 'kecamatan ')) {
                                $kecamatan = 'Kecamatan ' . $kecamatan;
                            }

                            $kabupaten = $profilDesa->kabupaten;
                            if (!Str::startsWith(strtolower($kabupaten), 'kabupaten ')) {
                                $kabupaten = 'Kabupaten ' . $kabupaten;
                            }

                            $lokasiLengkap = "{$kecamatan}, {$kabupaten}";

                            if (!empty($profilDesa->provinsi)) {
                                $provinsi = $profilDesa->provinsi;
                                if (!Str::startsWith(strtolower($provinsi), 'provinsi ')) {
                                    $provinsi = 'Provinsi ' . $provinsi;
                                }
                                $lokasiLengkap .= ", {$provinsi}";
                            }

                            echo $lokasiLengkap;
                        @endphp
                    @else
                        Portal Digital Desa - Layanan Terpadu untuk Warga Desa
                    @endif
                    </p>

                <!-- Modern Action Buttons -->
                    <div class="mt-10 flex flex-wrap gap-4" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ route('profil') }}" class="inline-flex items-center justify-center px-6 py-2 border border-white/30 text-base font-medium rounded-md text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 transition">
                        <i class="fas fa-info-circle mr-2"></i> Profil Desa
                    </a>
                        <a href="{{ route('layanan') }}" class="inline-flex items-center justify-center px-6 py-2 text-base font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 transition">
                        <i class="fas fa-file-alt mr-2"></i> Layanan Desa
                    </a>
                </div>
            </div>
        </div>

            <!-- Feature Cards Area (4 cols on desktop) -->
            <div class="md:col-span-4 bg-black/50 backdrop-blur-md">
                <div class="h-full flex flex-col justify-center p-8">
                    <!-- Modern Section Heading -->
                    <div class="mb-8">
                        <h2 class="text-white text-xl font-bold mb-1">FITUR UNGGULAN</h2>
                        <div class="h-1 w-16 bg-emerald-500 rounded-full"></div>
                    </div>

                    <!-- Modern Feature Cards with Clear Text -->
                    <div class="space-y-4">
                        <!-- Layanan Card -->
                        <a href="{{ route('layanan') }}" class="group block p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-all duration-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-emerald-600 flex items-center justify-center">
                                    <i class="fas fa-file-alt text-white text-lg"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-white text-lg font-medium mb-0.5">Layanan Desa</h3>
                                    <p class="text-white/70 text-sm">Layanan administrasi terpadu</p>
                                </div>
                                <div class="ml-2">
                                    <i class="fas fa-chevron-right text-white/40 group-hover:text-white transition-all"></i>
                                </div>
                            </div>
                        </a>

                        <!-- UMKM Card -->
                        <a href="{{ route('umkm') }}" class="group block p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-all duration-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-emerald-600 flex items-center justify-center">
                                    <i class="fas fa-store text-white text-lg"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-white text-lg font-medium mb-0.5">UMKM Desa</h3>
                                    <p class="text-white/70 text-sm">Produk lokal berkualitas</p>
                                </div>
                                <div class="ml-2">
                                    <i class="fas fa-chevron-right text-white/40 group-hover:text-white transition-all"></i>
                                </div>
                            </div>
                        </a>

                        <!-- Berita Card -->
                        <a href="{{ route('berita') }}" class="group block p-4 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg transition-all duration-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-emerald-600 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-white text-lg"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-white text-lg font-medium mb-0.5">Berita Desa</h3>
                                    <p class="text-white/70 text-sm">Informasi terkini</p>
                                </div>
                                <div class="ml-2">
                                    <i class="fas fa-chevron-right text-white/40 group-hover:text-white transition-all"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- MOBILE LAYOUT (Hidden on desktop) -->
        <div class="absolute inset-0 flex flex-col md:hidden z-10">
            <!-- Top Row: Text Content -->
            <div class="flex-1 flex items-center">
                <div class="w-full px-5 py-6">
                    <!-- Mobile Badge -->
                    <div class="relative inline-flex items-center px-3 py-1.5 rounded-lg bg-gradient-to-r from-emerald-500/20 to-emerald-600/20 backdrop-blur-md border border-white/20 shadow-md group overflow-hidden" data-aos="fade-down" data-aos-duration="800">
                        <!-- Animated Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-400/30 to-emerald-600/30 rounded-lg blur opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                        <span class="relative z-10 text-white font-semibold text-xs uppercase tracking-wider flex items-center">
                            <span class="w-1 h-1 bg-emerald-400 rounded-full mr-2 animate-pulse"></span>
                            DESA DIGITAL
                        </span>
                    </div>

                    <!-- Mobile Typography -->
                    <h1 class="mt-3 text-3xl font-bold text-white leading-tight" data-aos="fade-up" data-aos-duration="800">
                        @php
                            $namaDesa = $profilDesa->nama_desa ?? 'Digital';
                            if (Str::startsWith(strtolower($namaDesa), 'desa ')) {
                                echo $namaDesa;
                            } else {
                                echo 'Desa ' . $namaDesa;
                            }
                        @endphp
                    </h1>

                    <!-- Mobile Subtitle -->
                    <p class="mt-3 text-white text-base font-light leading-relaxed" data-aos="fade-up" data-aos-delay="100" data-aos-duration="800">
                        @if($profilDesa->kecamatan && $profilDesa->kabupaten)
                            @php
                                // Simplified location format for mobile
                                $lokasiSingkat = "{$profilDesa->kecamatan}, {$profilDesa->kabupaten}";
                                echo $lokasiSingkat;
                            @endphp
                        @else
                            Portal Digital Desa - Layanan Terpadu
                        @endif
                    </p>

                    <!-- Mobile Action Buttons -->
                    <div class="mt-6 flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ route('profil') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-white/30 text-sm font-medium rounded-md text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 transition">
                            <i class="fas fa-info-circle mr-2"></i> Profil Desa
                        </a>
                        <a href="{{ route('layanan') }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 transition">
                            <i class="fas fa-file-alt mr-2"></i> Layanan Desa
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Row: Feature Cards for Mobile (1 per row) -->
            <div class="bg-black/50 backdrop-blur-md py-4 px-4 border-t border-white/10">
                <h2 class="text-white text-sm font-bold uppercase mb-3">FITUR UNGGULAN</h2>

                <div class="space-y-2.5">
                    <!-- Layanan Card -->
                    <a href="{{ route('layanan') }}" class="group block p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-white text-sm font-medium">Layanan Desa</h3>
                                <p class="text-white/70 text-xs">Layanan administrasi terpadu</p>
                            </div>
                            <div class="ml-2">
                                <i class="fas fa-chevron-right text-white/40 group-hover:text-white text-xs transition-all"></i>
                            </div>
                        </div>
                    </a>

                    <!-- UMKM Card -->
                    <a href="{{ route('umkm') }}" class="group block p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center">
                                <i class="fas fa-store text-white"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-white text-sm font-medium">UMKM Desa</h3>
                                <p class="text-white/70 text-xs">Produk lokal berkualitas</p>
                            </div>
                            <div class="ml-2">
                                <i class="fas fa-chevron-right text-white/40 group-hover:text-white text-xs transition-all"></i>
                            </div>
                        </div>
                    </a>

                    <!-- Berita Card -->
                    <a href="{{ route('berita') }}" class="group block p-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-600 flex items-center justify-center">
                                <i class="fas fa-newspaper text-white"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-white text-sm font-medium">Berita Desa</h3>
                                <p class="text-white/70 text-xs">Informasi terkini</p>
                            </div>
                            <div class="ml-2">
                                <i class="fas fa-chevron-right text-white/40 group-hover:text-white text-xs transition-all"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Desktop Slide Indicator (hidden on mobile) -->
        @if(isset($profilDesa->thumbnails) && is_array($profilDesa->thumbnails) && count($profilDesa->thumbnails) > 1)
        <div class="absolute bottom-8 left-8 right-8 z-20 hidden md:flex">
            <div class="flex items-center gap-2">
            @foreach($profilDesa->thumbnails as $index => $thumbnail)
                <button @click="currentSlide = {{ $index }}"
                            class="h-1.5 transition-all duration-300 focus:outline-none rounded-full overflow-hidden"
                            :class="{'w-8 bg-white': currentSlide === {{ $index }}, 'w-4 bg-white/30 hover:bg-white/50': currentSlide !== {{ $index }}}">
                </button>
            @endforeach
        </div>
        </div>
        @endif
    </section>

    <!-- Modern Statistics Section with Contemporary Design -->
    <section class="py-12 bg-white relative z-10 overflow-hidden">


        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex justify-center mb-3" data-aos="fade-up">
                <div class="inline-flex items-center bg-emerald-50 px-4 py-2 rounded-full">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></div>
                    <h2 class="text-emerald-800 text-sm font-semibold tracking-wide uppercase">Data Statistik</h2>
                        </div>
                    </div>

            <p class="text-gray-600 max-w-3xl mx-auto text-center text-base mb-10">
                Informasi statistik terkini tentang jumlah penduduk, UMKM, layanan, dan berita desa
            </p>

            <!-- Modern Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Penduduk Stat -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-500 group relative" data-aos="fade-up" data-aos-delay="50">
                    <!-- Top Accent Bar -->
                    <div class="h-1.5 w-full bg-gradient-to-r from-emerald-400 to-emerald-600"></div>

                    <div class="p-6">
                        <!-- Floating Icon -->
                        <div class="absolute top-6 right-6 w-16 h-16 flex items-center justify-center text-emerald-100 opacity-50 group-hover:opacity-100 group-hover:scale-125 transition-all duration-700">
                            <i class="fas fa-users text-5xl"></i>
                        </div>

                        <!-- Label -->
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium">
                            <i class="fas fa-users mr-1.5 text-emerald-500"></i>
                            <span>Penduduk</span>
                    </div>

                        <!-- Stat Value with Animation -->
                        <div class="mt-2 mb-1">
                            <div class="text-4xl md:text-5xl font-bold text-gray-800 tracking-tight tabular-nums counter-item flex items-baseline"
                                 x-data="{ shown: false, value: 0, target: {{ $statistik['penduduk'] ?? 0 }} }"
                                 x-init="$el._x_isShown = false">
                                <span x-text="value.toLocaleString()" class="group-hover:text-emerald-700 transition-colors duration-300">0</span>
                                <span class="text-sm font-medium text-emerald-600 ml-2 tracking-wide">jiwa</span>
                    </div>
                    </div>

                        <!-- Label -->
                        <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-700 transition-colors duration-300">Total Penduduk Desa</p>

                        <!-- Decorative Element -->
                        <div class="w-12 h-1 bg-emerald-200 rounded-full mt-4 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                    </div>

                <!-- UMKM Stat -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-500 group relative" data-aos="fade-up" data-aos-delay="100">
                    <!-- Top Accent Bar -->
                    <div class="h-1.5 w-full bg-gradient-to-r from-blue-400 to-blue-600"></div>

                    <div class="p-6">
                        <!-- Floating Icon -->
                        <div class="absolute top-6 right-6 w-16 h-16 flex items-center justify-center text-blue-100 opacity-50 group-hover:opacity-100 group-hover:scale-125 transition-all duration-700">
                            <i class="fas fa-store text-5xl"></i>
                </div>

                        <!-- Label -->
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                            <i class="fas fa-store mr-1.5 text-blue-500"></i>
                            <span>UMKM</span>
                        </div>

                        <!-- Stat Value with Animation -->
                        <div class="mt-2 mb-1">
                            <div class="text-4xl md:text-5xl font-bold text-gray-800 tracking-tight tabular-nums counter-item flex items-baseline"
                                 x-data="{ shown: false, value: 0, target: {{ $statistik['umkm'] ?? 0 }} }"
                                 x-init="$el._x_isShown = false">
                                <span x-text="value.toLocaleString()" class="group-hover:text-blue-700 transition-colors duration-300">0</span>
                                <span class="text-sm font-medium text-blue-600 ml-2 tracking-wide">unit</span>
                            </div>
                        </div>

                        <!-- Label -->
                        <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-700 transition-colors duration-300">Total UMKM Desa</p>

                        <!-- Decorative Element -->
                        <div class="w-12 h-1 bg-blue-200 rounded-full mt-4 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                </div>

                <!-- Berita Stat -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-500 group relative" data-aos="fade-up" data-aos-delay="150">
                    <!-- Top Accent Bar -->
                    <div class="h-1.5 w-full bg-gradient-to-r from-amber-400 to-amber-600"></div>

                    <div class="p-6">
                        <!-- Floating Icon -->
                        <div class="absolute top-6 right-6 w-16 h-16 flex items-center justify-center text-amber-100 opacity-50 group-hover:opacity-100 group-hover:scale-125 transition-all duration-700">
                            <i class="fas fa-newspaper text-5xl"></i>
                        </div>

                        <!-- Label -->
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-medium">
                            <i class="fas fa-newspaper mr-1.5 text-amber-500"></i>
                            <span>Berita</span>
                        </div>

                        <!-- Stat Value with Animation -->
                        <div class="mt-2 mb-1">
                            <div class="text-4xl md:text-5xl font-bold text-gray-800 tracking-tight tabular-nums counter-item flex items-baseline"
                                 x-data="{ shown: false, value: 0, target: {{ $statistik['berita'] ?? 0 }} }"
                                 x-init="$el._x_isShown = false">
                                <span x-text="value.toLocaleString()" class="group-hover:text-amber-700 transition-colors duration-300">0</span>
                                <span class="text-sm font-medium text-amber-600 ml-2 tracking-wide">artikel</span>
                            </div>
                        </div>

                        <!-- Label -->
                        <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-700 transition-colors duration-300">Total Berita Desa</p>

                        <!-- Decorative Element -->
                        <div class="w-12 h-1 bg-amber-200 rounded-full mt-4 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                </div>

                <!-- Layanan Stat -->
                <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-500 group relative" data-aos="fade-up" data-aos-delay="200">
                    <!-- Top Accent Bar -->
                    <div class="h-1.5 w-full bg-gradient-to-r from-purple-400 to-purple-600"></div>

                    <div class="p-6">
                        <!-- Floating Icon -->
                        <div class="absolute top-6 right-6 w-16 h-16 flex items-center justify-center text-purple-100 opacity-50 group-hover:opacity-100 group-hover:scale-125 transition-all duration-700">
                            <i class="fas fa-file-alt text-5xl"></i>
                        </div>

                        <!-- Label -->
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-medium">
                            <i class="fas fa-file-alt mr-1.5 text-purple-500"></i>
                            <span>Layanan</span>
                        </div>

                        <!-- Stat Value with Animation -->
                        <div class="mt-2 mb-1">
                            <div class="text-4xl md:text-5xl font-bold text-gray-800 tracking-tight tabular-nums counter-item flex items-baseline"
                                 x-data="{ shown: false, value: 0, target: {{ $statistik['layanan'] ?? 0 }} }"
                                 x-init="$el._x_isShown = false">
                                <span x-text="value.toLocaleString()" class="group-hover:text-purple-700 transition-colors duration-300">0</span>
                                <span class="text-sm font-medium text-purple-600 ml-2 tracking-wide">jenis</span>
                            </div>
                        </div>

                        <!-- Label -->
                        <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-700 transition-colors duration-300">Total Layanan Desa</p>

                        <!-- Decorative Element -->
                        <div class="w-12 h-1 bg-purple-200 rounded-full mt-4 group-hover:w-20 transition-all duration-500"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern React/Vue Style Sambutan & Program Section with Interactive 3D Elements -->
    @if(($strukturPemerintahan && $strukturPemerintahan->sambutan_kepala_desa) ||
        ($strukturPemerintahan && ($strukturPemerintahan->prioritas_program || $strukturPemerintahan->program_kerja)))
    <section class="py-12 bg-gray-50 relative overflow-hidden">
        <!-- 3D Interactive Background Elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-emerald-100/40 to-emerald-50/10 rounded-full blur-3xl -z-10 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-blue-100/40 to-blue-50/10 rounded-full blur-3xl -z-10 animate-pulse" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Modern React-style Tab Interface -->
            <div x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'sambutan' }"
                 x-init="$watch('activeTab', value => { window.location.hash = value })">

                <!-- 3D Interactive Tab Navigation -->
                <div class="flex justify-center mb-6">
                    <div class="inline-flex bg-white/90 backdrop-blur-sm rounded-full p-1 shadow-md">
                        @if($strukturPemerintahan && $strukturPemerintahan->sambutan_kepala_desa)
                        <button @click="activeTab = 'sambutan'"
                                :class="{ 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-md': activeTab === 'sambutan',
                                         'bg-transparent text-gray-600 hover:text-gray-800': activeTab !== 'sambutan' }"
                                class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 9H16M8 13H14M8 17H12M10 2V6M14 2V6M3 8C3 6.89543 3.89543 6 5 6H19C20.1046 6 21 6.89543 21 8V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Sambutan
                        </button>
                        @endif

                        @if($strukturPemerintahan && ($strukturPemerintahan->prioritas_program || $strukturPemerintahan->program_kerja))
                        <button @click="activeTab = 'program'"
                                :class="{ 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md': activeTab === 'program',
                                         'bg-transparent text-gray-600 hover:text-gray-800': activeTab !== 'program' }"
                                class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            Program Desa
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Content Container -->
                <div class="mt-8 relative">
                    <!-- Sambutan Tab Panel -->
                    @if($strukturPemerintahan && $strukturPemerintahan->sambutan_kepala_desa)
                    <div x-show="activeTab === 'sambutan'"
                         x-transition:enter="transition-all duration-500 ease-out"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition-all duration-300 ease-in"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4"
                         class="mx-auto max-w-5xl">

                        <div class="bg-white rounded-xl shadow-md overflow-hidden">
                            <div class="grid md:grid-cols-12 gap-6 p-6">
                                <!-- Kepala Desa Profile -->
                    @if($strukturPemerintahan->foto_kepala_desa)
                                <div class="md:col-span-4 flex flex-col items-center" data-aos="fade-right">
                                    <div class="relative mb-3">
                                        <!-- Profile Photo -->
                                        <div class="w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden ring-4 ring-white shadow-md relative z-10">
                                            <img class="object-cover w-full h-full"
                                 src="{{ Storage::url($strukturPemerintahan->foto_kepala_desa) }}"
                                                 alt="Kepala Desa {{ $strukturPemerintahan->nama_kepala_desa }}"
                                                 loading="lazy">
                        </div>

                                        <!-- Verification Badge -->
                                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center shadow-md z-20">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <h3 class="text-lg font-bold text-gray-900">{{ $strukturPemerintahan->nama_kepala_desa }}</h3>
                                        <p class="text-sm font-medium text-emerald-700">Kepala Desa</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $strukturPemerintahan->periode_jabatan }}</p>
                        </div>

                                    <!-- Decorative Elements -->
                                    <div class="mt-4 hidden md:flex justify-center">
                                        <div class="flex space-x-1">
                                            @for($i = 0; $i < 5; $i++)
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-bounce" style="animation-delay: {{ $i * 200 }}ms"></div>
                                            @endfor
                    </div>
                </div>
                                </div>
                                @endif

                                <!-- Sambutan Content -->
                                <div class="md:col-span-8" data-aos="fade-left">
                                    <div class="prose prose-emerald max-w-none text-gray-600 leading-relaxed p-4 bg-white">
                            {!! $strukturPemerintahan->sambutan_kepala_desa !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

                    <!-- Program Tab Panel -->
                    @if($strukturPemerintahan && ($strukturPemerintahan->prioritas_program || $strukturPemerintahan->program_kerja))
                    <div x-show="activeTab === 'program'"
                         x-transition:enter="transition-all duration-500 ease-out"
                         x-transition:enter-start="opacity-0 transform translate-y-4"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition-all duration-300 ease-in"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform -translate-y-4"
                         class="mx-auto max-w-5xl">


                        <!-- Content with Interactive Elements -->
                        <div class="flex flex-col md:flex-row gap-8 mb-5 md:mb-3 items-center" data-aos="fade-up">
                            <!-- 3D Decorative Element -->
                            <div class="w-20 h-20 md:w-24 md:h-24 relative perspective-1000 hidden md:block">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-emerald-400 rounded-xl shadow-lg animate-rotate-y"></div>
                                <div class="absolute inset-2 bg-white rounded-lg flex items-center justify-center animate-rotate-y-reverse">
                                    <svg class="w-10 h-10 text-blue-500" viewBox="0 0 24 24" fill="none">
                                        <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15M9 5C9 6.10457 9.89543 7 11 7H13C14.1046 7 15 6.10457 15 5M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5M12 12H15M12 16H15M9 12H9.01M9 16H9.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Descriptive Text -->
                            <p class="text-gray-600 flex-1 text-center md:text-left bg-white backdrop-blur-sm p-4 rounded-lg shadow-sm">
                                Program yang menjadi fokus pembangunan dan pengembangan desa untuk mewujudkan kesejahteraan masyarakat
                </p>
            </div>

                        <!-- Program Tabs - Modern and Interactive -->
                        <div x-data="{ activeSubTab: 'prioritas' }" data-aos="fade-up">
                            <!-- Modern Tab Navigation - Single Row on Mobile -->
                            <div class="flex justify-center mb-5">
                                <div class="inline-flex bg-white/90 backdrop-blur-sm rounded-full p-1 shadow-md">
                                <button @click="activeSubTab = 'prioritas'"
                                        :class="{ 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md': activeSubTab === 'prioritas',
                                                    'bg-transparent text-gray-600 hover:text-gray-800': activeSubTab !== 'prioritas' }"
                                            class="px-4 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" :class="{'text-white': activeSubTab === 'prioritas'}" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span>Program Prioritas</span>
                                    </button>

                                    <button @click="activeSubTab = 'programKerja'"
                                            :class="{ 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-md': activeSubTab === 'programKerja',
                                                    'bg-transparent text-gray-600 hover:text-gray-800': activeSubTab !== 'programKerja' }"
                                            class="px-4 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" :class="{'text-white': activeSubTab === 'programKerja'}" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Program Kerja</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Program Content with Modern Transitions -->
                            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                                <!-- Program Prioritas Content -->
                                <div x-show="activeSubTab === 'prioritas'"
                                     x-transition:enter="transition-all ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform translate-y-4"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     x-transition:leave="transition-all ease-in duration-200"
                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                     x-transition:leave-end="opacity-0 transform -translate-y-4"
                                     class="p-6">

                                    @if($strukturPemerintahan->prioritas_program)
                                        <div class="prose prose-blue max-w-none">
                {!! $strukturPemerintahan->prioritas_program !!}
            </div>
                                    @else
                                        <div class="py-10 text-center">
                                            <!-- Empty State -->
                                            <div class="w-20 h-20 mx-auto mb-4 relative">
                                                <div class="absolute inset-0 rounded-full bg-blue-100 animate-pulse"></div>
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <svg class="w-10 h-10 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
        </div>
                                        </div>
                                        <h3 class="text-xl font-medium text-gray-800 mb-2">Belum Ada Program Prioritas</h3>
                                        <p class="text-gray-500 max-w-md mx-auto">Program prioritas pembangunan desa belum tersedia atau sedang dalam proses pembaruan.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Program Kerja Content -->
                            <div x-show="activeSubTab === 'programKerja'"
                                 x-transition:enter="transition-all ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform translate-y-4"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition-all ease-in duration-200"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-4"
                                 class="p-6">

                                @if($strukturPemerintahan->program_kerja)
                                    <div class="prose prose-emerald max-w-none">
                                        {!! $strukturPemerintahan->program_kerja !!}
                                    </div>
                                @else
                                    <div class="py-10 text-center">
                                        <!-- Empty State -->
                                        <div class="w-20 h-20 mx-auto mb-4 relative">
                                            <div class="absolute inset-0 rounded-full bg-emerald-100 animate-pulse"></div>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <svg class="w-10 h-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <h3 class="text-xl font-medium text-gray-800 mb-2">Belum Ada Program Kerja</h3>
                                        <p class="text-gray-500 max-w-md mx-auto">Program kerja desa belum tersedia atau sedang dalam proses pembaruan.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 3D Animation Styles -->
    <style>
        .perspective-1000 { perspective: 1000px; }

        @keyframes rotate-y {
            0%, 100% { transform: rotateY(0deg); }
            50% { transform: rotateY(180deg); }
        }

        @keyframes rotate-y-reverse {
            0%, 100% { transform: rotateY(0deg); }
            50% { transform: rotateY(-180deg); }
        }

        .animate-rotate-y {
            animation: rotate-y 10s infinite ease-in-out;
        }

        .animate-rotate-y-reverse {
            animation: rotate-y-reverse 10s infinite ease-in-out;
        }
    </style>
    </section>
    @endif

    <!-- Berita Terbaru - Modern Slider Layout with Enhanced Cards -->
    @if($beritaTerbaru->count() > 0)
    <section class="py-12 bg-white relative overflow-hidden">
        <!-- Abstract Background Elements -->
        <div class="absolute top-0 left-1/3 w-1/2 h-full bg-gradient-to-b from-white via-emerald-50/30 to-transparent -z-10"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-blue-50/40 -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Modern Header -->
            <div class="mb-0">
                <!-- Title and Button in One Row -->
                <div class="flex items-center justify-between mb-3">
                 <!-- For Berita Section Header -->
<div class="flex items-center">
    <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
    <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
        <i class="fas fa-newspaper text-emerald-600 mr-1.5"></i>
        INFORMASI TERKINI
    </span>
</div>

                    <!-- Right: Simplified Button -->
                    <a href="{{ route('berita') }}" class="flex-shrink-0 inline-flex items-center text-sm font-medium text-emerald-600 border border-emerald-200 rounded-lg px-3 py-1.5 hover:bg-emerald-50 transition-colors">
                        <span>Lihat Semua</span>
                        <svg class="ml-1.5 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                </a>
            </div>

                <!-- Description on Full Row -->
                <p class="text-gray-600 text-sm md:text-base w-full">
                    Berita dan informasi terbaru seputar kegiatan dan perkembangan desa
                </p>
            </div>

            <!-- Swiper Slider Container -->
            <div class="relative mt-6">
                <!-- Main Swiper -->
                <div class="swiper-container berita-slider">
                    <div class="swiper-wrapper">
                        @foreach($beritaTerbaru as $berita)
                        <div class="swiper-slide p-3">
                            <!-- Modern Card Design -->
                            <div class="group bg-white rounded-2xl shadow-sm overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-lg relative border border-gray-100">
                                <!-- Enhanced Image Container with Overlay Gradient -->
                                <div class="relative aspect-w-16 aspect-h-9 overflow-hidden">
                    @if($berita->gambar)
                                    <img class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-105"
                             src="{{ Storage::url($berita->gambar) }}"
                                         alt="{{ $berita->judul }}"
                                         loading="lazy">

                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-gray-300 text-5xl"></i>
                    </div>
                    @endif
                                </div>

                                <!-- Modern Content Area -->
                                <div class="p-6 flex flex-col flex-grow relative bg-gray-50">
                                    <!-- Date and Category on Same Line - Modern Style -->
                                    <div class="flex items-center justify-between mb-4 relative z-10">
                                        <!-- Modern Date Badge - Updated Design -->
                                        <div class="flex items-center">
                                            <div class="flex flex-col items-center mr-3 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg shadow-sm overflow-hidden border border-emerald-100 transition-all duration-300 group-hover:shadow-md">
                                                <div class="bg-emerald-500 text-white text-xs font-bold w-full text-center py-0.5 px-2">
                                                    {{ $berita->created_at->locale('id')->isoFormat('MMM') }}
                                                </div>
                                                <div class="text-emerald-700 text-base font-bold py-0.5 px-3">
                                                    {{ $berita->created_at->isoFormat('D') }}
                                                </div>
                                            </div>

                                            <div class="flex flex-col text-xs">
                                                <span class="text-gray-500 font-medium">
                                                    {{ $berita->created_at->isoFormat('YYYY') }}
                                                </span>
                                                <span class="text-gray-400">
                                                    {{ $berita->created_at->locale('id')->isoFormat('dddd') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Enhanced Category Badge -->
                                        @if(isset($berita->kategori))
                                            @php
                                                $styles = match($berita->kategori) {
                                                    'Umum' => 'bg-indigo-500 border-indigo-600',
                                                    'Pengumuman' => 'bg-amber-500 border-amber-600',
                                                    'Kegiatan' => 'bg-emerald-500 border-emerald-600',
                                                    'Infrastruktur' => 'bg-rose-500 border-rose-600',
                                                    'Kesehatan' => 'bg-sky-500 border-sky-600',
                                                    'Pendidikan' => 'bg-purple-500 border-purple-600',
                                                    default => 'bg-gray-500 border-gray-600',
                                                };
                                                $icon = match($berita->kategori) {
                                                    'Umum' => 'fa-globe',
                                                    'Pengumuman' => 'fa-bullhorn',
                                                    'Kegiatan' => 'fa-calendar-check',
                                                    'Infrastruktur' => 'fa-road',
                                                    'Kesehatan' => 'fa-heartbeat',
                                                    'Pendidikan' => 'fa-graduation-cap',
                                                    default => 'fa-tag',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-medium text-white {{ $styles }} shadow-md border-l-4">
                                                <i class="fas {{ $icon }} mr-1"></i>
                                                {{ $berita->kategori }}
                            </span>
                                        @endif
                        </div>

                                    <!-- Enhanced Title with Premium Typography and Truncation -->
                                    <a href="{{ route('berita.show', $berita->id) }}" class="block group relative z-10">
                                        <!-- Modern Title Design with Gradient Accent -->
                                        <div class="relative h-0.5 w-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full mb-3 transition-all duration-300 group-hover:w-24"></div>
                                        <h3 class="text-xl font-bold text-gray-900 leading-tight mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                            {{ $berita->judul }}
                                        </h3>
                                    </a>

                                    <!-- Modern Description with Premium Typography and 2-line Truncation (UMKM Style) -->
                                    <div class="mt-1 relative z-10">
                                        <p class="text-gray-600 text-sm leading-relaxed mb-5 pl-3 border-l-2 border-emerald-200 line-clamp-2 after:content-['...']">
                                            {{ strip_tags($berita->konten ?? $berita->isi) }}
                                        </p>
                                    </div>

                                    <!-- Author and Read More Section -->
                                    <div class="mt-auto pt-4 border-t border-dashed border-gray-200 flex justify-between items-center relative z-10">
                                        <!-- Author Info -->
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-user text-emerald-500 mr-2"></i>
                                            {{ $berita->creator->name ?? 'Admin' }}
                                        </div>

                                        <!-- Modern Read More Link -->
                                        <a href="{{ route('berita.show', $berita->id) }}" class="inline-flex items-center bg-emerald-50 hover:bg-emerald-100 text-emerald-600 font-medium text-sm rounded-full px-4 py-1.5 transition-colors duration-300 group/link">
                                            <span>Baca selengkapnya</span>
                                            <svg class="ml-1.5 w-4 h-4 transform transition-transform duration-300 group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                            </a>
                        </div>
                    </div>
                            </div>
                        </div>
                @endforeach
            </div>
        </div>

                <!-- Modern Pagination Indicators -->
                <div class="flex justify-center mt-10">
                    <div class="swiper-pagination berita-pagination"></div>
                </div>
            </div>
        </div>


    </section>
    @endif

    <!-- UMKM Unggulan - Modern Slider Layout Matched with Berita Style -->
    @if($umkmUnggulan->count() > 0)
    <section class="py-12 bg-gray-50 relative overflow-hidden">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Modern Header - Matched with Berita Style -->
            <div class="mb-0">
                <!-- Title and Button in One Row -->
                <div class="flex items-center justify-between mb-3">
                    <!-- Left: Title Badge -->
                   <!-- For UMKM Section Header -->
<div class="flex items-center">
    <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
    <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        UMKM DESA
    </span>
</div>

                    <!-- Right: Simplified Button -->
                    <a href="{{ route('umkm') }}" class="flex-shrink-0 inline-flex items-center text-sm font-medium text-emerald-600 border border-emerald-200 rounded-lg px-3 py-1.5 hover:bg-emerald-50 transition-colors">
                        <span>Lihat Semua</span>
                        <svg class="ml-1.5 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                </a>
            </div>

                <!-- Description on Full Row -->
                <p class="text-gray-600 text-sm md:text-base w-full">
                    Temukan produk dan jasa unggulan dari UMKM lokal desa
                </p>
            </div>

            <!-- Swiper Slider Container -->
            <div class="relative mt-6">
                <!-- Main Swiper -->
                <div class="swiper-container umkm-slider">
                    <div class="swiper-wrapper">
                        @php
                            $kategoriColors = [
                                'Kuliner' => 'green',
                                'Kerajinan' => 'blue',
                                'Fashion' => 'yellow',
                                'Pertanian' => 'indigo',
                                'Jasa' => 'red',
                                'Lainnya' => 'gray',
                            ];

                            $kategoriIcons = [
                                'Kuliner' => 'fas fa-utensils',
                                'Kerajinan' => 'fas fa-hands',
                                'Fashion' => 'fas fa-tshirt',
                                'Pertanian' => 'fas fa-leaf',
                                'Jasa' => 'fas fa-concierge-bell',
                                'Lainnya' => 'fas fa-store',
                            ];
                        @endphp
                        @foreach($umkmUnggulan as $umkm)
                        <div class="swiper-slide p-3">
                            <!-- Modern Card Design -->
                            <div class="group bg-white rounded-2xl shadow-sm overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-lg relative border border-gray-100">
                                <!-- Enhanced Image Container with Overlay Gradient - Changed to 16:9 ratio -->
                                <div class="relative aspect-w-16 aspect-h-9 overflow-hidden">
                                    @if($umkm->foto_usaha)
                                        <img class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-105"
                                             src="{{ Storage::url($umkm->foto_usaha) }}"
                                             alt="{{ $umkm->nama_usaha }}"
                                             loading="lazy">

                                        <!-- Gradient Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                            <i class="fas fa-store text-gray-300 text-5xl"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Modern Content Area - Updated with White Background and Modern Design -->
                                <div class="p-6 flex flex-col flex-grow relative bg-white">
                                    <!-- Modern Category and Info Row -->
                                    <div class="flex items-center justify-between mb-4 relative z-10">
                                        <!-- Left: Category Badge - Enhanced Design -->
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium
                                            text-{{ $kategoriColors[$umkm->kategori] ?? 'emerald' }}-700
                                            bg-{{ $kategoriColors[$umkm->kategori] ?? 'emerald' }}-50
                                            border border-{{ $kategoriColors[$umkm->kategori] ?? 'emerald' }}-100 shadow-sm">
                                            <i class="{{ $kategoriIcons[$umkm->kategori] ?? 'fas fa-store' }} text-{{ $kategoriColors[$umkm->kategori] ?? 'emerald' }}-500 mr-1.5"></i>
                                            {{ $umkm->kategori }}
                                        </span>

                                        <!-- Right: UMKM Verification Badge -->
                                        <div class="flex items-center text-xs font-medium px-2.5 py-1.5 rounded-md border border-blue-100 bg-blue-50 text-blue-700">
                                            <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                            Terverifikasi
                                        </div>
                                    </div>

                                    <!-- Enhanced Title with Modern Typography -->
                                    <a href="{{ route('umkm.show', $umkm->id) }}" class="block group relative z-10">
                                        <!-- Modern Title Design with Gradient Accent -->
                                        <div class="relative h-0.5 w-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full mb-3 transition-all duration-300 group-hover:w-24"></div>
                                        <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                            {{ $umkm->nama_usaha }}
                                        </h3>
                                    </a>

                                    <!-- Modern Description with Premium Typography and 2-line Truncation -->
                                    <div class="mt-1 relative z-10">
                                        <p class="text-gray-600 text-sm leading-relaxed mb-5 pl-3 border-l-2 border-emerald-200 line-clamp-2 after:content-['...']">
                                            {{ $umkm->deskripsi }}
                                        </p>
                                    </div>

                                    <!-- Enhanced Action Buttons with Modern Design - Swapped Positions -->
                                    <div class="mt-auto pt-4 border-t border-dashed border-gray-200 flex justify-between items-center relative z-10">
                                        <!-- WhatsApp Button - Now on Left Side -->
                                        <a href="{{ $umkm->getWhatsappUrl() }}" target="_blank" class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium text-sm rounded-lg px-4 py-2 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                            <i class="fab fa-whatsapp mr-1.5 text-white"></i>
                                            <span>WhatsApp</span>
                                        </a>

                                        <!-- Detailed Link - Now on Right Side -->
                                        <a href="{{ route('umkm.show', $umkm->id) }}" class="inline-flex items-center text-emerald-600 font-medium text-sm transition-colors duration-300 group/detail hover:text-emerald-700">
                                            <span class="relative">
                                                <span class="absolute bottom-0 h-0.5 w-full scale-x-0 origin-left bg-emerald-600 transition-transform duration-300 group-hover/detail:scale-x-100"></span>
                                                Lihat detail
                                            </span>
                                            <svg class="ml-1.5 w-4 h-4 transform transition-transform duration-300 group-hover/detail:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Modern Pagination Indicators -->
                <div class="flex justify-center mt-10">
                    <div class="swiper-pagination umkm-pagination"></div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Modern CTA Section (Simplified) -->
    <section class="pt-12 pb-20 bg-gradient-to-br from-emerald-600 to-emerald-900 relative overflow-hidden">
        <!-- Subtle Background Element (Single) -->
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full translate-x-1/3 translate-y-1/3"></div>

        <!-- Content Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <!-- Badge -->
                <div class="relative inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-emerald-600/30 to-emerald-700/30 backdrop-blur-lg border border-white/30 shadow-xl group overflow-hidden" data-aos="fade-down">
                    <!-- Moving Light Effect -->
                    <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-all duration-1000 ease-in-out"></span>

                    <!-- Badge Content -->
                    <div class="relative z-10 flex items-center">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2.5 animate-pulse"></div>
                        <span class="text-white font-semibold text-sm tracking-wider">DESA DIGITAL</span>
                    </div>
                </div>

                <!-- Title -->
                <h2 class="mt-3 text-2xl md:text-3xl font-bold text-white">
                    Ingin tahu lebih banyak tentang desa kami?
                </h2>

                <!-- Description - Fixed to prevent double "Desa" -->
                <p class="mt-3 text-sm md:text-base text-emerald-50 max-w-3xl mx-auto">
                    @php
                        $namaDesa = $profilDesa->nama_desa ?? 'Digital';
                        if (Str::startsWith(strtolower($namaDesa), 'desa ')) {
                            echo 'Temukan informasi lengkap tentang ' . $namaDesa . ' dan layanan yang tersedia';
                        } else {
                            echo 'Temukan informasi lengkap tentang Desa ' . $namaDesa . ' dan layanan yang tersedia';
                        }
                    @endphp
                </p>

                <!-- Buttons -->
                <div class="mt-8 flex flex-col px-12 sm:flex-row gap-4 justify-center">
                    <a href="{{ route('layanan') }}" class="inline-flex items-center justify-center px-3 py-3 text-base font-medium rounded-lg bg-white text-emerald-700 hover:bg-gray-100 shadow-lg hover:shadow-xl transition-all duration-300">
                        <i class="fas fa-file-alt mr-2"></i> Lihat Layanan Desa
                    </a>

                    <a href="{{ route('profil') }}" class="inline-flex items-center justify-center px-3 py-3 text-base font-medium rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 shadow-lg hover:shadow-xl transition-all duration-300">
                        <i class="fas fa-info-circle mr-2"></i> Tentang Desa Kami
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Wave -->
        <div class="absolute bottom-0 left-0 right-0 w-full overflow-hidden" style="height: 60px">
            <svg class="absolute bottom-0 w-full h-full" viewBox="0 0 1440 120" preserveAspectRatio="none" fill="white">
                <path d="M0,96L60,85.3C120,75,240,53,360,48C480,43,600,53,720,69.3C840,85,960,107,1080,101.3C1200,96,1320,64,1380,48L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"></path>
        </svg>
        </div>
    </section>

    <!-- Modern Maps & Contact Section with Enhanced Layout -->
    <section class="py-12 bg-white relative overflow-hidden">
        <!-- Background Decorative Elements (Improved) -->
        <div class="absolute top-0 right-0 w-1/2 h-1/2 bg-gradient-to-br from-emerald-50 to-emerald-100/20 rounded-full opacity-60 blur-3xl -z-10 transform translate-x-1/4 -translate-y-1/4"></div>
        <div class="absolute bottom-0 left-0 w-1/2 h-1/2 bg-gradient-to-tr from-blue-50 to-blue-100/20 rounded-full opacity-60 blur-3xl -z-10 transform -translate-x-1/4 translate-y-1/4"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Simplified Modern Header - Matches Statistics Section with added paragraph text -->
            <div class="text-center mb-8" data-aos="fade-up">
                <div class="inline-flex items-center bg-emerald-50 px-4 py-2 rounded-full">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></div>
                    <h2 class="text-emerald-800 text-sm font-semibold tracking-wide uppercase">Lokasi & Kontak</h2>
                </div>
                <!-- Added paragraph text -->
                <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                    @php
                        $namaDesa = $profilDesa->nama_desa ?? '';
                        if (Str::startsWith(strtolower($namaDesa), 'desa ')) {
                            echo 'Temukan lokasi dan informasi kontak ' . $namaDesa;
                        } else {
                            echo 'Temukan lokasi dan informasi kontak Desa ' . $namaDesa;
                        }
                    @endphp
                </p>
            </div>

            <!-- Modern Layout Structure -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left: Map Section (7 columns on desktop) -->
                <div class="lg:col-span-7 order-2 lg:order-1">
                    <!-- Map Container with Fixed Height -->
                    <div class="bg-white rounded-2xl overflow-hidden shadow-xl border border-gray-100" data-aos="fade-right">
                        <!-- Alamat Card - Improved one-line format -->
                        <div class="bg-gray-50 p-5 border-b border-gray-100 relative z-10">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-12 h-12 rounded-lg bg-emerald-500 flex items-center justify-center shadow-md">
                                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Alamat Kantor Desa</h3>
                                    <p class="text-gray-700 mt-1 text-sm leading-relaxed">
                                        @php
                                            $alamatParts = [];

                                            // Add base address
                                            if ($profilDesa->alamat ?? false) {
                                                $alamatParts[] = $profilDesa->alamat;
                                            }

                                            // Add province and postal code
                                            if ($profilDesa->provinsi ?? false) {
                                                $provinsiKodePos = $profilDesa->provinsi;
                                                if ($profilDesa->kode_pos ?? false) {
                                                    $provinsiKodePos .= ' ' . $profilDesa->kode_pos;
                                                }
                                                $alamatParts[] = $provinsiKodePos;
                                            }

                                            echo implode(', ', $alamatParts) ?: 'Alamat kantor desa belum diisi';
                                        @endphp
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Map with Loading State and Fixed Height -->
                        <div class="relative">
                            @php
                                // Handle nama desa to prevent duplication
                                $namaDesa = $profilDesa->nama_desa ?? '';
                                if (Str::startsWith(strtolower($namaDesa), 'desa ')) {
                                    $cleanNamaDesa = $namaDesa;
                                } else {
                                    $cleanNamaDesa = 'Desa ' . $namaDesa;
                                }

                                // Improved search query for better map results
                                $searchQuery = urlencode(
                                    $cleanNamaDesa . ' ' .
                                    ($profilDesa->kecamatan ?? '') . ' ' .
                                    ($profilDesa->kabupaten ?? '') . ' ' .
                                    ($profilDesa->provinsi ?? '')
                                );

                                // Default to Indonesia if no specific location
                                if (empty(trim(str_replace(' ', '', $searchQuery))) || $searchQuery === 'Desa+') {
                                    $searchQuery = 'Indonesia';
                                }
                            @endphp

                            <!-- Loading Indicator -->
                            <div class="absolute inset-0 bg-gray-100 flex items-center justify-center z-10 map-loader">
                                <div class="flex flex-col items-center">
                                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-emerald-500 mb-2"></div>
                                    <p class="text-sm text-gray-500">Memuat peta...</p>
                                </div>
                            </div>

                            <!-- Fixed height map container -->
                            <div class="h-[450px]">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.4157608731784!2d106.8532!3d-6.6003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5d2f9f02917%3A0xfa0651b318f198a2!2s{{ $searchQuery }}!5e0!3m2!1sen!2sid!4v1690447353417!5m2!1sen!2sid"
                                    width="100%"
                                    height="100%"
                                    style="border:0;"
                                    allowfullscreen=""
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    onload="this.parentNode.querySelector('.map-loader') && (this.parentNode.querySelector('.map-loader').style.display = 'none')">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Contact Cards (5 columns on desktop) -->
                <div class="lg:col-span-5 order-1 lg:order-2 flex flex-col gap-5" data-aos="fade-left">
                    <!-- Phone Contact Card - Updated background -->
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-lg transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-blue-200 group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-5">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-phone text-white text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">Telepon</h4>
                                <p class="text-gray-600 mt-3 group-hover:text-gray-700 transition-colors">
                                    @if($profilDesa->telepon)
                                        <a href="tel:{{ $profilDesa->telepon }}" class="hover:text-blue-600 transition-colors flex items-center">
                                            <span>{{ $profilDesa->telepon }}</span>
                                            <span class="ml-2 text-blue-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <i class="fas fa-arrow-right text-xs"></i>
                                            </span>
                                        </a>
                                    @else
                                        Telepon belum diisi
                                    @endif
                                </p>
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-500">
                                        Hubungi kami untuk informasi lebih lanjut tentang desa dan pelayanan yang tersedia.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Contact Card - Updated background -->
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-lg transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-purple-200 group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-5">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-envelope text-white text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-purple-700 transition-colors">Email</h4>
                                <p class="text-gray-600 mt-3 group-hover:text-gray-700 transition-colors">
                                    @if($profilDesa->email)
                                        <a href="mailto:{{ $profilDesa->email }}" class="hover:text-purple-600 transition-colors flex items-center">
                                            <span class="break-all">{{ $profilDesa->email }}</span>
                                            <span class="ml-2 text-purple-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <i class="fas fa-arrow-right text-xs"></i>
                                            </span>
                                        </a>
                                    @else
                                        Email belum diisi
                                    @endif
                                </p>
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-500">
                                        Kirim email untuk pertanyaan, saran, atau keperluan administrasi desa.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Website Contact Card - Updated background -->
                    <div class="bg-gray-50 p-6 rounded-2xl shadow-lg transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-amber-200 group">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-5">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-globe text-white text-xl"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-amber-700 transition-colors">Website</h4>
                                <p class="text-gray-600 mt-3 group-hover:text-gray-700 transition-colors">
                                    @if($profilDesa->website)
                                        <a href="{{ $profilDesa->website }}" target="_blank" class="hover:text-amber-600 transition-colors flex items-center">
                                            <span class="break-all">{{ $profilDesa->website }}</span>
                                            <span class="ml-2 text-amber-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <i class="fas fa-external-link-alt text-xs"></i>
                                            </span>
                                        </a>
                                    @else
                                        Website belum diisi
                                    @endif
                                </p>
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-500">
                                        Kunjungi website kami untuk informasi lebih lengkap dan layanan online desa.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('styles')
<style>

/* Change pagination bullets to emerald theme */
.swiper-pagination-bullet {
    width: 10px;
    height: 10px;
    background: rgba(16, 185, 129, 0.2); /* emerald-500 with opacity */
    opacity: 1;
}

.swiper-pagination-bullet-active {
    background: rgb(16, 185, 129); /* emerald-500 */
    transform: scale(1.2);
    transition: transform 0.3s;
}

/* Dynamic bullets styling */
.swiper-pagination-bullet-active-main {
    background: rgb(16, 185, 129); /* emerald-500 */
    transform: scale(1.4);
}

.swiper-pagination-bullet-active-prev,
.swiper-pagination-bullet-active-next {
    background: rgba(16, 185, 129, 0.6); /* emerald-500 with medium opacity */
    transform: scale(1.1);
}

.swiper-pagination-bullet-active-prev-prev,
.swiper-pagination-bullet-active-next-next {
    background: rgba(16, 185, 129, 0.3); /* emerald-500 with low opacity */
}
</style>
@endpush

@push('scripts')
  <script>
    function modernCarousel() {
    return {
        currentSlide: 0,
        totalSlides: {{ isset($profilDesa->thumbnails) && is_array($profilDesa->thumbnails) && count($profilDesa->thumbnails) > 0
            ? count($profilDesa->thumbnails)
            : 1 }},
        autoplaySpeed: 7000, // Time between slides (ms) - 7 seconds
        transitionDuration: 800, // Duration of transition animation (ms) - 0.8 seconds
        autoplayTimeout: null,

        init() {
            if (this.totalSlides > 1) {
                this.startAutoplay();

                // Stop autoplay when user interacts with the carousel
                document.querySelector('section').addEventListener('mouseenter', () => {
                    this.stopAutoplay();
                });

                document.querySelector('section').addEventListener('touchstart', () => {
                    this.stopAutoplay();
                }, {passive: true});

                // Resume autoplay when user leaves
                document.querySelector('section').addEventListener('mouseleave', () => {
                    this.startAutoplay();
                });

                document.querySelector('section').addEventListener('touchend', () => {
                    this.startAutoplay();
                }, {passive: true});
            }
        },

        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        },

        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        },

        startAutoplay() {
            this.stopAutoplay();
            this.autoplayTimeout = setTimeout(() => {
                this.nextSlide();
                this.startAutoplay();
            }, this.autoplaySpeed);
        },

        stopAutoplay() {
            clearTimeout(this.autoplayTimeout);
        }
    };
}

// Observer for lazy loading additional content
document.addEventListener('DOMContentLoaded', () => {
    // Create an intersection observer for any additional content
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // If the element has data-src, replace src with it
                const img = entry.target.querySelector('img[data-src]');
                if (img) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                }
                observer.unobserve(entry.target);
            }
        });
    }, {
        rootMargin: '0px 0px 200px 0px' // Load when within 200px of viewport
    });

    // Observe sections with images
    document.querySelectorAll('.lazy-section').forEach(section => {
        observer.observe(section);
    });
});
  </script>
@endpush