@extends('layouts.front')

@section('title', 'Statistik Desa ' . ($profilDesa->nama_desa ?? ''))


@section('breadcrumbs')
<div class="border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="py-2" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('home') }}" class="flex items-center text-gray-500 hover:text-emerald-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Beranda
            </a>
        </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex items-center text-emerald-600 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Statistik Desa
            </div>
        </li>
    </ol>
</nav>
    </div>
</div>
@endsection

@section('content')
<!-- Interactive CTA-Style Header Section -->
<section class="pt-12 pb-16 bg-gradient-to-br from-emerald-600 to-emerald-900 relative overflow-hidden">
    <!-- Subtle Background Element -->
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full translate-x-1/3 translate-y-1/3"></div>

    <!-- Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <!-- Interactive Badge with Hover Effect -->
            <div class="relative inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-emerald-600/30 to-emerald-700/30 backdrop-blur-lg border border-white/30 shadow-xl group overflow-hidden cursor-pointer"
                 data-aos="fade-down"
                 onclick="document.getElementById('section-kependudukan').scrollIntoView({behavior: 'smooth'})">
                <!-- Moving Light Effect on Hover -->
                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-all duration-1000 ease-in-out"></span>

                <!-- Badge Content -->
                <div class="relative z-10 flex items-center">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2.5 animate-pulse"></div>
                    <span class="text-white font-semibold text-sm tracking-wider">DATA VISUALISASI</span>
                </div>
            </div>

            <!-- Main Title with Interactive Element -->
            <h1 class="mt-4 text-3xl md:text-4xl lg:text-5xl font-bold text-white group" data-aos="fade-up">
                <span class="relative inline-block">
                Statistik {{ $profilDesa->nama_desa ?? 'Desa' }}
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-emerald-300 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></span>
                </span>
            </h1>

            <!-- Description with Fade In Effect -->
            <p class="mt-4 max-w-2xl mx-auto text-emerald-50 text-lg opacity-90" data-aos="fade-up" data-aos-delay="100">
                Data dan perkembangan desa dalam bentuk visualisasi yang informatif
            </p>
        </div>
    </div>

    <!-- Bottom Wave -->
    <div class="absolute bottom-0 left-0 right-0 w-full overflow-hidden" style="height: 60px">
        <svg class="absolute bottom-0 w-full h-full" viewBox="0 0 1440 120" preserveAspectRatio="none" fill="white">
            <path d="M0,96L60,85.3C120,75,240,53,360,48C480,43,600,53,720,69.3C840,85,960,107,1080,101.3C1200,96,1320,64,1380,48L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"></path>
        </svg>
    </div>
</section>

<!-- Tab Navigation with Modern Styling & Draggable -->
<section class="pb-3 bg-white" id="statistik-tabs" x-data="{
    isDragging: false,
    startX: 0,
    scrollLeft: 0,
    moveDistance: 0,

    handleMouseDown(e) {
        const slider = $refs.tabSlider;
        this.isDragging = true;
        this.startX = e.pageX - slider.offsetLeft;
        this.scrollLeft = slider.scrollLeft;
        this.moveDistance = 0;
        slider.classList.add('cursor-grabbing');
    },

    handleMouseMove(e) {
        if(!this.isDragging) return;
        e.preventDefault();
        const slider = $refs.tabSlider;
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - this.startX) * 2;
        this.moveDistance += Math.abs(walk);
        slider.scrollLeft = this.scrollLeft - walk;
    },

    handleMouseUp(e) {
        this.isDragging = false;
        $refs.tabSlider.classList.remove('cursor-grabbing');
    },

    initTabs() {
        // Existing tab functionality preserved
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Only trigger tab change if we didn't drag significantly
                if (this.moveDistance < 10) {
                    const target = this.getAttribute('data-target');

                    // Deactivate all tabs
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-emerald-600', 'text-white');
                        btn.classList.add('bg-gray-50', 'text-gray-700', 'hover:bg-gray-100', 'hover:text-gray-800');
                    });

                    // Activate selected tab
                    this.classList.add('active', 'bg-emerald-600', 'text-white');
                    this.classList.remove('bg-gray-50', 'text-gray-700', 'hover:bg-gray-100', 'hover:text-gray-800');

                    // Hide all tab contents
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Show the selected tab content
                    document.getElementById(`section-${target}`).classList.remove('hidden');

                    // Resize charts if present
                    window.dispatchEvent(new Event('resize'));

                    // Load data based on active tab
                    if (target === 'inventaris' && typeof loadInventarisData === 'function') {
                        loadInventarisData(window.activeInventarisPeriode || 'semua_waktu');
                    } else if (target === 'keuangan' && typeof loadFinancialData === 'function') {
                        loadFinancialData(window.activePeriode || 'semua_waktu');
                    } else if (target === 'bansos' && typeof loadBansosData === 'function') {
                        loadBansosData(window.activeBansosPeriode || 'semua_waktu', window.bansosFilterType || 'status');
                    }
                }
            });
        });
    }
}" x-init="initTabs()">
    <div class="max-w-7xl pt-4 mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Modern Header -->
        <div class="mb-0">
            <!-- Title Row -->
            <div class="flex items-center mb-3">
                <div class="flex items-center">
                    <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                    <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                        <i class="fas fa-chart-bar text-emerald-600 mr-1.5"></i>
                        KATEGORI DATA
                    </span>
                </div>
            </div>

            <!-- Description on Full Row -->
            <p class="text-gray-600 text-sm md:text-base w-full mb-3">
                Pilih kategori data untuk melihat statistik desa dalam berbagai aspek
            </p>
        </div>

        <div class="relative">
            <div
                x-ref="tabSlider"
                class="overflow-x-auto py-2 scrollbar-hide cursor-grab active:cursor-grabbing"
                style="-webkit-overflow-scrolling: touch; -ms-overflow-style: none; scrollbar-width: none;"
                @mousedown="handleMouseDown"
                @mousemove="handleMouseMove"
                @mouseup="handleMouseUp"
                @mouseleave="handleMouseUp"
            >
                <style>
                    .scrollbar-hide::-webkit-scrollbar {
                        display: none;
                    }
                </style>
                <div class="inline-flex gap-2 min-w-full px-1 py-1" data-aos="fade-up">
                    <button type="button" id="tab-kependudukan"
                            class="tab-button active bg-emerald-600 text-white shrink-0 px-4 py-2.5 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center"
                            data-target="kependudukan">
                        <i class="fas fa-users mr-1.5 text-xs"></i> Kependudukan
            </button>

                    <button type="button" id="tab-keuangan"
                            class="tab-button bg-gray-50 text-gray-700 hover:bg-gray-100 hover:text-gray-800 shrink-0 px-4 py-2.5 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center"
                            data-target="keuangan">
                        <i class="fas fa-money-bill-wave mr-1.5 text-xs"></i> Keuangan
            </button>

                    <button type="button" id="tab-bansos"
                            class="tab-button bg-gray-50 text-gray-700 hover:bg-gray-100 hover:text-gray-800 shrink-0 px-4 py-2.5 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center"
                            data-target="bansos">
                        <i class="fas fa-hands-helping mr-1.5 text-xs"></i> Bantuan Sosial
            </button>

                    <button type="button" id="tab-inventaris"
                            class="tab-button bg-gray-50 text-gray-700 hover:bg-gray-100 hover:text-gray-800 shrink-0 px-4 py-2.5 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center"
                            data-target="inventaris">
                        <i class="fas fa-boxes mr-1.5 text-xs"></i> Inventaris Desa
            </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Sections -->
<section class="pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Kependudukan Section -->
        <div id="section-kependudukan" class="tab-content">
            <!-- Modern Header for Kependudukan Section -->
            <div class="flex items-center mb-6" data-aos="fade-up">
                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                    <i class="fas fa-users text-emerald-600 mr-1.5"></i>
                    KEPENDUDUKAN DESA
                </span>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
                <!-- Total Penduduk Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-emerald-100 mr-4">
                        <i class="fas fa-users text-xl text-emerald-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total Penduduk</h3>
                        <div class="text-2xl font-bold text-emerald-500 mt-1">{{ number_format($totalPenduduk, 0, ',', '.') }}</div>
                        <p class="text-xs text-gray-500 mt-1">Jumlah warga terdaftar di desa</p>
                    </div>
                </div>

                <!-- Rasio Gender Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-indigo-100 mr-4">
                        <i class="fas fa-venus-mars text-xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Rasio Gender</h3>
                        <div class="text-2xl font-bold mt-1">
                            <span class="text-indigo-600">{{ $persenLakiLaki }}%</span>
                            <span class="text-gray-400 mx-1">:</span>
                            <span class="text-pink-500">{{ $persenPerempuan }}%</span>
                    </div>
                        <p class="text-xs text-gray-500 mt-1">Perbandingan laki-laki dan perempuan</p>
                </div>
                </div>

                <!-- Total Kepala Keluarga Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-orange-100 mr-4">
                        <i class="fas fa-home text-xl text-orange-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Kepala Keluarga</h3>
                        <div class="text-2xl font-bold text-orange-500 mt-1">{{ number_format($totalKepalaKeluarga, 0, ',', '.') }}</div>
                        <p class="text-xs text-gray-500 mt-1">Total KK terdaftar di desa</p>
                    </div>
                </div>
            </div>

            <!-- Modern Header for Charts Section -->
            <div class="flex items-center mb-4" data-aos="fade-up">
                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                    <i class="fas fa-chart-pie text-emerald-600 mr-1.5"></i>
                    GRAFIK DEMOGRAFI
                </span>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-4 sm:p-5" data-aos="fade-up">
                    <h3 class="text-gray-700 font-semibold mb-4 flex items-center">
                        <i class="fas fa-chart-pie text-indigo-500 mr-2"></i>
                        Distribusi Jenis Kelamin
                    </h3>
                    <div id="gender-chart" class="w-full h-[300px] xs:h-[320px] sm:h-[350px]"></div>
                </div>

                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-4 sm:p-5" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-gray-700 font-semibold mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-blue-500 mr-2"></i>
                        Kelompok Umur
                    </h3>
                    <div id="age-chart" class="w-full h-[300px] xs:h-[320px] sm:h-[350px]"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-4 sm:p-5" data-aos="fade-up">
                    <h3 class="text-gray-700 font-semibold mb-4 flex items-center">
                        <i class="fas fa-graduation-cap text-green-500 mr-2"></i>
                        Tingkat Pendidikan
                    </h3>
                    <div id="education-chart" class="w-full h-[300px] xs:h-[320px] sm:h-[350px]"></div>
                </div>

                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-4 sm:p-5" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-gray-700 font-semibold mb-4 flex items-center">
                        <i class="fas fa-briefcase text-amber-500 mr-2"></i>
                        Pekerjaan
                    </h3>
                    <div id="occupation-chart" class="w-full h-[300px] xs:h-[320px] sm:h-[350px]"></div>
                </div>
            </div>
        </div>

        <!-- Keuangan Section dengan Filter Dropdown Modern -->
        <div id="section-keuangan" class="tab-content hidden">
            <!-- Header Modern dengan Title -->
            <div class="flex items-center mb-1" data-aos="fade-up">
                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                    <i class="fas fa-chart-line text-emerald-600 mr-1.5"></i>
                    KEUANGAN DESA
                </span>
            </div>

            <!-- Description and Filter Row -->
            <div class="flex justify-between items-center mb-3 gap-3">
                <p class="text-gray-600 text-sm md:text-base truncate max-w-[60%] sm:max-w-[70%]">
                    Statistik keuangan desa<span class="hidden sm:inline"> dalam berbagai periode</span>
                </p>

                <!-- Modern Dropdown Filter -->
                <div class="relative shrink-0" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-white border border-gray-200 px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-lg shadow-sm flex items-center gap-1.5 sm:gap-2 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-filter text-emerald-500"></i>
                        <span id="selected-periode" class="text-sm md:text-base whitespace-nowrap">Semua Waktu</span>
                        <i class="fas fa-chevron-down text-gray-400 ml-1 sm:ml-2"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" x-ref="dropdown" class="absolute right-0 mt-2 bg-white rounded-lg shadow-lg z-50 border border-gray-100 overflow-hidden min-w-[200px] w-full md:w-auto">
                        <div id="periode-filters" class="max-h-[70vh] overflow-y-auto">
                            <button type="button" class="periode-btn active w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="semua_waktu" @click="open = false">
                                <i class="fas fa-calendar text-emerald-500 w-4"></i> Semua Waktu
                            </button>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="tahun_ini" @click="open = false">
                                <i class="fas fa-calendar-alt text-emerald-500 w-4"></i> Tahun Ini
                            </button>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="bulan_ini" @click="open = false">
                                <i class="fas fa-calendar-day text-emerald-500 w-4"></i> Bulan Ini
                            </button>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="minggu_ini" @click="open = false">
                                <i class="fas fa-calendar-week text-emerald-500 w-4"></i> Minggu Ini
                            </button>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="tahun_lalu" @click="open = false">
                                <i class="fas fa-history text-emerald-500 w-4"></i> Tahun Lalu
                            </button>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="bulan_lalu" @click="open = false">
                                <i class="fas fa-history text-emerald-500 w-4"></i> Bulan Lalu
                            </button>
                            <div class="border-t border-gray-100"></div>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="kustom" id="btn-kustom" @click="open = false">
                                <i class="fas fa-calendar-plus text-emerald-500 w-4"></i> Kustom
                    </button>
                        </div>
                    </div>
                </div>
                </div>

            <!-- Custom Date Range - Modern UI -->
            <div id="date-range-picker" class="hidden mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3">
                    <div class="sm:col-span-1 lg:col-span-3">
                        <label for="dari-tanggal" class="block text-sm md:text-base font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" id="dari-tanggal" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm md:text-base">
                    </div>
                    <div class="sm:col-span-1 lg:col-span-3">
                        <label for="sampai-tanggal" class="block text-sm md:text-base font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" id="sampai-tanggal" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm md:text-base">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-1 flex items-end">
                        <button type="button" id="terapkan-filter" class="w-full px-3 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition flex items-center justify-center text-sm md:text-base">
                            <i class="fas fa-check mr-1.5"></i> Terapkan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Period Label -->
            <p class="text-gray-700 mb-6 text-sm md:text-base" id="periode-info">Menampilkan data keuangan untuk periode <span class="font-semibold">Semua Waktu</span></p>

            <!-- Loading Indicator -->
            <div id="keuangan-loading" class="hidden">
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-emerald-500"></div>
                </div>
            </div>

            <!-- Stats Overview Cards - Simplified Modern Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8" id="keuangan-stats-container">
                <!-- Total Pemasukan Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5" data-aos="fade-up">
                    <h3 class="text-gray-500 text-sm md:text-base font-medium mb-2">Total Pemasukan</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center mr-3" style="background-color: rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-arrow-up text-emerald-500"></i>
                    </div>
                        <div>
                            <div class="text-2xl font-bold text-emerald-500" id="total-pemasukan">Rp 0</div>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Seluruh pemasukan desa</p>
                        </div>
                    </div>
                </div>

                <!-- Total Pengeluaran Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5" data-aos="fade-up">
                    <h3 class="text-gray-500 text-sm md:text-base font-medium mb-2">Total Pengeluaran</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center mr-3" style="background-color: rgba(239, 68, 68, 0.1);">
                            <i class="fas fa-arrow-down text-red-500"></i>
                    </div>
                        <div>
                            <div class="text-2xl font-bold text-red-500" id="total-pengeluaran">Rp 0</div>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Seluruh pengeluaran desa</p>
                        </div>
                    </div>
                </div>

                <!-- Saldo Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5" data-aos="fade-up">
                    <h3 class="text-gray-500 text-sm md:text-base font-medium mb-2">Saldo Desa</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center mr-3" style="background-color: rgba(59, 130, 246, 0.1);">
                            <i class="fas fa-wallet text-blue-500"></i>
                    </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-500" id="saldo-desa">Rp 0</div>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5" id="periode-text">Saldo keuangan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Keuangan - Simplified -->
            <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 mb-6" data-aos="fade-up">
                <div class="p-4">
                    <div class="flex items-center mb-4">

                        <h3 class="text-sm md:text-base font-medium text-gray-800">
                            <i class="fas fa-info-circle text-emerald-600 mr-2"></i> Informasi Keuangan
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Pemasukan Terbaru -->
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-green-100">
                            <h4 class="font-medium text-emerald-600 mb-2 flex items-center text-sm md:text-base">
                                <i class="fas fa-arrow-up text-emerald-600 mr-2"></i> Transaksi Pemasukan Terbesar
                            </h4>
                            <div id="top-pemasukan" class="text-emerald-500 text-sm md:text-base">
                            <p class="text-gray-500 italic">Memuat data...</p>
                        </div>
                </div>

                    <!-- Pengeluaran Terbaru -->
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-red-100">
                            <h4 class="font-medium text-red-600 mb-2 flex items-center text-sm md:text-base">
                                <i class="fas fa-arrow-down text-red-600 mr-2"></i> Transaksi Pengeluaran Terbesar
                            </h4>
                            <div id="top-pengeluaran" class="text-red-500 text-sm md:text-base">
                            <p class="text-gray-500 italic">Memuat data...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Status - Simplified -->
            <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100" data-aos="fade-up">
                <div class="p-4">
                    <div class="flex items-center mb-4">

                        <h3 class="text-sm md:text-base font-medium text-gray-800">
                            <i class="fas fa-chart-line text-emerald-600 mr-2"></i> Ringkasan Status Keuangan
                        </h3>
                    </div>

                <div class="flex flex-col gap-3" id="status-keuangan">
                        <!-- Saldo status - akan berubah warna berdasarkan kondisi -->
                        <div id="saldo-status-container" class="p-3 bg-white rounded-lg border border-red-100 flex items-start">
                            <i id="saldo-icon" class="fas fa-info-circle text-red-500 mt-0.5 mr-2.5"></i>
                            <span id="saldo-status" class="block text-red-800 font-medium text-sm md:text-base">Menghitung status keuangan...</span>
                    </div>

                        <!-- Trend status - akan berubah warna berdasarkan kondisi -->
                        <div id="trend-status-container" class="p-3 bg-white rounded-lg border border-red-100 flex items-start">
                            <i id="trend-icon" class="fas fa-chart-line text-red-500 mt-0.5 mr-2.5"></i>
                            <span id="trend-status" class="block text-red-800 text-sm md:text-base">Menganalisis tren keuangan...</span>
                    </div>
                </div>
            </div>
            </div>

            <!-- Script untuk warna status berdasarkan kondisi deficit/surplus -->

        </div>

        <!-- Bansos Section -->
        <div id="section-bansos" class="tab-content hidden">
            <!-- Header Modern dengan Title -->
            <div class="flex items-center mb-1" data-aos="fade-up">
                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                    <i class="fas fa-hands-helping text-emerald-600 mr-1.5"></i>
                    BANTUAN SOSIAL
                </span>
            </div>

            <!-- Description and Filter Row -->
            <div class="flex justify-between items-center mb-3 gap-3">
                <p class="text-gray-600 text-sm md:text-base truncate max-w-[60%] sm:max-w-[70%]">
                    Statistik bantuan sosial<span class="hidden sm:inline"> dalam berbagai periode</span>
                </p>

                <!-- Modern Dropdown Filter -->
                <div class="relative shrink-0" x-data="{ open: false }">
                    <button @click="open = !open" class="bg-white border border-gray-200 px-2.5 sm:px-4 py-1.5 sm:py-2 rounded-lg shadow-sm flex items-center gap-1.5 sm:gap-2 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-filter text-emerald-500"></i>
                        <span id="bansos-selected-periode" class="text-sm md:text-base whitespace-nowrap">Semua Waktu</span>
                        <i class="fas fa-chevron-down text-gray-400 ml-1 sm:ml-2"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 bg-white rounded-lg shadow-lg z-50 border border-gray-100 overflow-hidden min-w-[200px] w-full md:w-auto">
                        <div id="bansos-periode-filters" class="max-h-[70vh] overflow-y-auto">
                            <button type="button" class="periode-btn active w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="semua_waktu" @click="open = false">
                                <i class="fas fa-calendar text-emerald-500 w-4"></i> Semua Waktu
                            </button>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="tahun_ini" @click="open = false">
                                <i class="fas fa-calendar-alt text-emerald-500 w-4"></i> Tahun Ini
                            </button>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="bulan_ini" @click="open = false">
                                <i class="fas fa-calendar-day text-emerald-500 w-4"></i> Bulan Ini
                            </button>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="minggu_ini" @click="open = false">
                                <i class="fas fa-calendar-week text-emerald-500 w-4"></i> Minggu Ini
                            </button>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="tahun_lalu" @click="open = false">
                                <i class="fas fa-history text-emerald-500 w-4"></i> Tahun Lalu
                            </button>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="bulan_lalu" @click="open = false">
                                <i class="fas fa-history text-emerald-500 w-4"></i> Bulan Lalu
                            </button>
                            <div class="border-t border-gray-100"></div>
                            <button type="button" class="periode-btn w-full text-left px-4 py-2.5 text-sm md:text-base hover:bg-emerald-50 flex items-center gap-2.5" data-periode="kustom" id="bansos-btn-kustom" @click="open = false">
                                <i class="fas fa-calendar-plus text-emerald-500 w-4"></i> Kustom
                    </button>
                        </div>
                    </div>
                </div>
                </div>

            <!-- Custom Date Range - Modern UI -->
            <div id="bansos-date-range-picker" class="hidden mb-6 bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-3">
                    <div class="sm:col-span-1 lg:col-span-3">
                        <label for="bansos-dari-tanggal" class="block text-sm md:text-base font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" id="bansos-dari-tanggal" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm md:text-base">
                    </div>
                    <div class="sm:col-span-1 lg:col-span-3">
                        <label for="bansos-sampai-tanggal" class="block text-sm md:text-base font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" id="bansos-sampai-tanggal" class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500 text-sm md:text-base">
                    </div>
                    <div class="sm:col-span-2 lg:col-span-1 flex items-end">
                        <button type="button" id="bansos-terapkan-filter" class="w-full px-3 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 transition flex items-center justify-center text-sm md:text-base">
                            <i class="fas fa-check mr-1.5"></i> Terapkan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Indicator for active period -->
            <p class="text-gray-700 mb-6 text-sm md:text-base">Menampilkan data bantuan sosial untuk periode <span class="font-semibold" id="bansos-periode-display">Semua Waktu</span></p>

            <!-- Loading Indicator -->
            <div id="bansos-loading" class="hidden">
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-emerald-500"></div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:mb-4" id="bansos-stats-container">
                <!-- Total Pengajuan Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5" data-aos="fade-up">
                    <h3 class="text-gray-500 text-sm md:text-base font-medium mb-2">Total Pengajuan</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center mr-3" style="background-color: rgba(59, 130, 246, 0.1);">
                            <i class="fas fa-file-alt text-blue-500"></i>
                    </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-500" id="total-pengajuan">0</div>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Seluruh pengajuan bantuan</p>
                        </div>
                    </div>
                </div>

                <!-- Dalam Proses Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-gray-500 text-sm md:text-base font-medium mb-2">Dalam Proses</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center mr-3" style="background-color: rgba(245, 158, 11, 0.1);">
                            <i class="fas fa-clock text-amber-500"></i>
                    </div>
                        <div>
                            <div class="text-2xl font-bold text-amber-500" id="total-proses">0</div>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Pengajuan sedang diproses</p>
                        </div>
                    </div>
                </div>

                <!-- Bantuan Diterima Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-gray-500 text-sm md:text-base font-medium mb-2">Sudah Diterima</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center mr-3" style="background-color: rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                    </div>
                        <div>
                            <div class="text-2xl font-bold text-emerald-500" id="total-diterima">0</div>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Bantuan telah disalurkan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <!-- Ditolak Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5" data-aos="fade-up">
                    <h3 class="text-gray-500 text-sm md:text-base font-medium mb-2">Ditolak</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center mr-3" style="background-color: rgba(239, 68, 68, 0.1);">
                            <i class="fas fa-times-circle text-red-500"></i>
                    </div>
                        <div>
                            <div class="text-2xl font-bold text-red-500" id="total-ditolak">0</div>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Pengajuan ditolak</p>
                        </div>
                    </div>
                </div>

                <!-- Prioritas Tinggi Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="text-gray-500 text-sm md:text-base font-medium mb-2">Prioritas Tinggi</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center mr-3" style="background-color: rgba(249, 115, 22, 0.1);">
                            <i class="fas fa-exclamation-triangle text-orange-500"></i>
                    </div>
                        <div>
                            <div class="text-2xl font-bold text-orange-500" id="total-prioritas-tinggi">0</div>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Termasuk kasus urgent</p>
                        </div>
                    </div>
                </div>

                <!-- Persentase Persetujuan Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5" data-aos="fade-up" data-aos-delay="200">
                    <h3 class="text-gray-500 text-sm md:text-base font-medium mb-2">Persentase Persetujuan</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center mr-3" style="background-color: rgba(79, 70, 229, 0.1);">
                            <i class="fas fa-chart-pie text-indigo-500"></i>
                    </div>
                        <div>
                            <div class="text-2xl font-bold text-indigo-500" id="persentase-persetujuan">0%</div>
                            <p class="text-gray-500 text-xs md:text-sm mt-0.5">Tingkat persetujuan bantuan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart with Filter -->
            <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-4 sm:p-5 mb-6" data-aos="fade-up">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                    <h3 class="text-lg md:text-xl font-semibold mb-2 md:mb-0"><i class="fas fa-chart-pie mr-2 text-emerald-600"></i> Grafik Bantuan Sosial</h3>
                    <div class="self-end md:self-auto">
                        <select id="bansos-chart-filter" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 p-2.5">
                            <option value="status">Berdasarkan Status</option>
                            <option value="prioritas">Berdasarkan Prioritas</option>
                            <option value="sumber">Berdasarkan Sumber Pengajuan</option>
                        </select>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-lg bg-white shadow-inner p-3">
                    <div id="bansos-chart" class="w-full h-[300px] xs:h-[320px] sm:h-[350px] md:h-[400px]"></div>
                </div>
            </div>

            <!-- Script untuk menangani dropdown bansos -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Custom handling for bansos periode buttons if Alpine.js isn't closing the dropdown
                document.querySelectorAll('#bansos-periode-filters .periode-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        // Semua dropdowns yang mungkin terbuka (jika ada multiple)
                        document.querySelectorAll('[x-data]').forEach(dropdown => {
                            if (dropdown.__x) {
                                dropdown.__x.$data.open = false;
                            }
                        });

                        // Update selected text
                        const periodText = this.innerText.trim();
                        document.getElementById('bansos-selected-periode').textContent = periodText;

                        // Update the period display indicator
                        document.getElementById('bansos-periode-display').textContent = periodText;

                        // Handle custom date range visibility
                        if (this.getAttribute('data-periode') === 'kustom') {
                            document.getElementById('bansos-date-range-picker').classList.remove('hidden');
                        } else {
                            document.getElementById('bansos-date-range-picker').classList.add('hidden');
                        }
                    });
                });

                // Also update period display when applying custom date filter
                document.getElementById('bansos-terapkan-filter').addEventListener('click', function() {
                    const dariTanggal = document.getElementById('bansos-dari-tanggal').value;
                    const sampaiTanggal = document.getElementById('bansos-sampai-tanggal').value;

                    if (dariTanggal && sampaiTanggal) {
                        const customRangeText = `${dariTanggal} s/d ${sampaiTanggal}`;
                        document.getElementById('bansos-periode-display').textContent = customRangeText;
                    }
                });
            });
            </script>
        </div>

        <!-- Inventaris Section -->
        <div id="section-inventaris" class="tab-content hidden">
            <!-- Modern Header for Inventaris Section -->
            <div class="flex items-center mb-6" data-aos="fade-up">
                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                    <i class="fas fa-boxes text-emerald-600 mr-1.5"></i>
                    INVENTARIS DESA
                </span>
            </div>

            <!-- Loading Indicator -->
            <div id="inventaris-loading" class="hidden">
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-emerald-500"></div>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8" id="inventaris-stats-container">
                <!-- Total Inventaris Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-blue-100 mr-4">
                        <i class="fas fa-boxes text-xl text-blue-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total Jenis Barang</h3>
                        <div class="text-2xl font-bold text-blue-500 mt-1" id="total-inventaris">0</div>
                        <p class="text-xs text-gray-500 mt-1">Jumlah kategori barang</p>
                    </div>
                </div>

                <!-- Total Unit Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-emerald-100 mr-4">
                        <i class="fas fa-cube text-xl text-emerald-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total Unit</h3>
                        <div class="text-2xl font-bold text-emerald-500 mt-1" id="total-unit">0</div>
                        <p class="text-xs text-gray-500 mt-1">Jumlah unit barang keseluruhan</p>
                    </div>
                </div>

                <!-- Total Nilai Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-orange-100 mr-4">
                        <i class="fas fa-money-bill-wave text-xl text-orange-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Total Nilai</h3>
                        <div class="text-2xl font-bold text-orange-500 mt-1" id="total-nilai">Rp 0</div>
                        <p class="text-xs text-gray-500 mt-1">Nilai aset inventaris desa</p>
                    </div>
                </div>
            </div>

            <!-- Modern Header for Kondisi Section -->
            <div class="flex items-center mb-4" data-aos="fade-up">
                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                    <i class="fas fa-tasks text-emerald-600 mr-1.5"></i>
                    BERDASARKAN KONDISI
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
                <!-- Kondisi Baik Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-emerald-100 mr-4">
                        <i class="fas fa-check-circle text-xl text-emerald-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Kondisi Baik</h3>
                        <div class="text-2xl font-bold text-emerald-500 mt-1" id="kondisi-baik">0</div>
                        <p class="text-xs text-gray-500 mt-1">Unit barang dalam kondisi baik</p>
                    </div>
                </div>

                <!-- Kondisi Rusak Ringan Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-amber-100 mr-4">
                        <i class="fas fa-exclamation-triangle text-xl text-amber-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Rusak Ringan</h3>
                        <div class="text-2xl font-bold text-amber-500 mt-1" id="kondisi-rusak-ringan">0</div>
                        <p class="text-xs text-gray-500 mt-1">Unit barang rusak ringan</p>
                    </div>
                </div>

                <!-- Kondisi Rusak Berat Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-red-100 mr-4">
                        <i class="fas fa-times-circle text-xl text-red-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Rusak Berat</h3>
                        <div class="text-2xl font-bold text-red-500 mt-1" id="kondisi-rusak-berat">0</div>
                        <p class="text-xs text-gray-500 mt-1">Unit barang rusak berat</p>
                    </div>
                </div>
            </div>

            <!-- Modern Header for Status Section -->
            <div class="flex items-center mb-4" data-aos="fade-up">
                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                    <i class="fas fa-tag text-emerald-600 mr-1.5"></i>
                    BERDASARKAN STATUS
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
                <!-- Status Tersedia Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-blue-100 mr-4">
                        <i class="fas fa-check text-xl text-blue-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Tersedia</h3>
                        <div class="text-2xl font-bold text-blue-500 mt-1" id="status-tersedia">0</div>
                        <p class="text-xs text-gray-500 mt-1">Unit barang tersedia untuk digunakan</p>
                    </div>
                </div>

                <!-- Status Dipinjam Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-purple-100 mr-4">
                        <i class="fas fa-hand-holding text-xl text-purple-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Dipinjam</h3>
                        <div class="text-2xl font-bold text-purple-500 mt-1" id="status-dipinjam">0</div>
                        <p class="text-xs text-gray-500 mt-1">Unit barang yang sedang dipinjam</p>
                    </div>
                </div>

                <!-- Status Dalam Perbaikan Card -->
                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 p-5 flex items-start" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-yellow-100 mr-4">
                        <i class="fas fa-tools text-xl text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Dalam Perbaikan</h3>
                        <div class="text-2xl font-bold text-yellow-600 mt-1" id="status-dalam-perbaikan">0</div>
                        <p class="text-xs text-gray-500 mt-1">Unit barang sedang diperbaiki</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<!-- ApexCharts JS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
     <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Fungsi untuk mengubah warna tampilan status berdasarkan nilai saldo
                function updateStatusColors(isDeficit) {
                    const saldoIcon = document.getElementById('saldo-icon');
                    const trendIcon = document.getElementById('trend-icon');
                    const saldoStatus = document.getElementById('saldo-status');
                    const trendStatus = document.getElementById('trend-status');
                    const saldoContainer = document.getElementById('saldo-status-container');
                    const trendContainer = document.getElementById('trend-status-container');

                    if (isDeficit) {
                        // Defisit - warna merah
                        saldoIcon.className = 'fas fa-info-circle text-red-500 mt-0.5 mr-2.5';
                        trendIcon.className = 'fas fa-chart-line text-red-500 mt-0.5 mr-2.5';
                        saldoStatus.className = 'block text-red-500 font-medium text-sm md:text-base';
                        trendStatus.className = 'block text-red-500 text-sm md:text-base';
                        saldoContainer.className = 'p-3 bg-white rounded-lg border border-red-100 flex items-start';
                        trendContainer.className = 'p-3 bg-white rounded-lg border border-red-100 flex items-start';
                    } else {
                        // Surplus - warna emerald
                        saldoIcon.className = 'fas fa-info-circle text-emerald-500 mt-0.5 mr-2.5';
                        trendIcon.className = 'fas fa-chart-line text-emerald-500 mt-0.5 mr-2.5';
                        saldoStatus.className = 'block text-emerald-500 font-medium text-sm md:text-base';
                        trendStatus.className = 'block text-emerald-500 text-sm md:text-base';
                        saldoContainer.className = 'p-3 bg-white rounded-lg border border-emerald-100 flex items-start';
                        trendContainer.className = 'p-3 bg-white rounded-lg border border-emerald-100 flex items-start';
                    }
                }

                // Pantau perubahan pada nilai saldo desa
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'characterData' || mutation.type === 'childList') {
                            const saldoElement = document.getElementById('saldo-desa');
                            if (saldoElement) {
                                const saldoText = saldoElement.textContent;
                                // Periksa apakah saldo negatif (defisit)
                                const isDeficit = saldoText.includes('-');
                                updateStatusColors(isDeficit);
                            }
                        }
                    });
                });

                // Mulai observasi
                const saldoElement = document.getElementById('saldo-desa');
                if (saldoElement) {
                    observer.observe(saldoElement, { childList: true, characterData: true, subtree: true });
                    // Periksa nilai awal
                    const isDeficit = saldoElement.textContent.includes('-');
                    updateStatusColors(isDeficit);
                }

                // Custom handling for periode buttons if Alpine.js isn't closing the dropdown
                document.querySelectorAll('.periode-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        // Semua dropdowns yang mungkin terbuka (jika ada multiple)
                        document.querySelectorAll('[x-data]').forEach(dropdown => {
                            if (dropdown.__x) {
                                dropdown.__x.$data.open = false;
                            }
                        });

                        // Update selected text
                        const periodText = this.innerText.trim();
                        document.getElementById('selected-periode').textContent = periodText;

                        // Handle custom date range visibility
                        if (this.getAttribute('data-periode') === 'kustom') {
                            document.getElementById('date-range-picker').classList.remove('hidden');
                        } else {
                            document.getElementById('date-range-picker').classList.add('hidden');
                        }
                    });
                });
            });

document.addEventListener('DOMContentLoaded', function() {
    // Tab Navigation
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');

            // Deactivate all tabs
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.add('hidden'));

            // Activate selected tab
            this.classList.add('active');
            document.getElementById(`section-${target}`).classList.remove('hidden');

            // Perform specific actions based on the selected tab
            if (target === 'kependudukan') {
                window.dispatchEvent(new Event('resize'));
            } else if (target === 'inventaris') {
                // This is the critical fix - load inventory data when switching to the tab
                loadInventarisData(window.activeInventarisPeriode || 'semua_waktu');
            } else if (target === 'keuangan' && typeof loadFinancialData === 'function') {
                loadFinancialData(window.activePeriode || 'semua_waktu');
            } else if (target === 'bansos' && typeof loadBansosData === 'function') {
                loadBansosData(window.activeBansosPeriode || 'semua_waktu', window.bansosFilterType || 'status');
            }
        });
    });

    // 1. Gender Chart (Donut)
    if (document.getElementById('gender-chart')) {
        const genderChartOptions = {
            series: [{{ $totalLakiLaki }}, {{ $totalPerempuan }}],
            chart: {
                type: 'donut',
                height: 360,
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#4F46E5', '#EC4899'],
            labels: ['Laki-laki', 'Perempuan'],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '14px',
                markers: {
                    width: 12,
                    height: 12,
                    radius: 6
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return Math.round(val) + '%';
                },
                style: {
                    fontWeight: 'bold'
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '22px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 600,
                                color: '#111827',
                                offsetY: -15
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 400,
                                color: '#6B7280',
                                offsetY: 5
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                color: '#111827',
                                fontSize: '16px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 600
                            }
                        }
                    }
                }
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val + ' orang';
                    }
                }
            },
            stroke: {
                width: 2
            },
            responsive: [
                {
                    breakpoint: 640,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            ]
        };

        const genderChart = new ApexCharts(document.getElementById('gender-chart'), genderChartOptions);
        genderChart.render();
    }

    // 2. Age Group Chart (Bar) - Distributed Columns tanpa Data Label
    if (document.getElementById('age-chart')) {
        // Array warna yang beragam untuk setiap batang
        const ageColors = ['#008FFB', '#00E396', '#FEB019', '#FF4560', '#775DD0', '#38C77A', '#F86624', '#2E93fA'];

        const ageChartOptions = {
            series: [{
                name: 'Jumlah Penduduk',
                data: @json($kelompokUmurData)
            }],
            chart: {
                height: 350,
                type: 'bar',
                events: {
                    click: function(chart, w, e) {
                        // console.log(chart, w, e)
                    }
                },
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600,
                    animateGradually: {
                        enabled: true,
                        delay: 100
                    }
                },
                fontFamily: 'Inter, sans-serif',
            },
            colors: ageColors,
            plotOptions: {
                bar: {
                    columnWidth: '55%',
                    distributed: true,
                    borderRadius: 8
                }
            },
            dataLabels: {
                enabled: false // Data label dinonaktifkan sesuai permintaan
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: @json($kelompokUmurLabels),
                labels: {
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Inter, sans-serif',
                        fontWeight: 500,
                        colors: ageColors
                    },
                    rotate: -45,
                    rotateAlways: false
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah Penduduk',
                    style: {
                        fontSize: '14px',
                        fontWeight: 500
                    }
                },
                labels: {
                    formatter: function(val) {
                        return Math.round(val);
                    }
                }
            },
            grid: {
                show: true,
                borderColor: '#F0F4F8',
                strokeDashArray: 5,
                position: 'back',
                xaxis: {
                    lines: {
                        show: false
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                padding: {
                    top: 0,
                    right: 10,
                    bottom: 0,
                    left: 10
                }
            },
            tooltip: {
                theme: 'light',
                style: {
                    fontSize: '14px',
                    fontFamily: 'Inter, sans-serif'
                },
                y: {
                    title: {
                        formatter: function() {
                            return 'Jumlah:';
                        }
                    },
                    formatter: function(val) {
                        return val + ' orang';
                    }
                },
                marker: {
                    show: false
                }
            },
            responsive: [
                {
                    breakpoint: 768,
                    options: {
                        plotOptions: {
                            bar: {
                                columnWidth: '75%',
                                borderRadius: 6
                            }
                        },
                        xaxis: {
                            labels: {
                                rotate: -45,
                                rotateAlways: true,
                                style: {
                                    fontSize: '11px'
                                }
                            }
                        }
                    }
                },
                {
                    breakpoint: 640,
                    options: {
                        chart: {
                            height: 320
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '80%',
                                borderRadius: 4
                            }
                        },
                        xaxis: {
                            labels: {
                                rotate: -45,
                                rotateAlways: true,
                                style: {
                                    fontSize: '9px'
                                }
                            }
                        }
                    }
                }
            ]
        };

        const ageChart = new ApexCharts(document.getElementById('age-chart'), ageChartOptions);
        ageChart.render();
    }

    // 3. Education Chart (Donut)
    if (document.getElementById('education-chart')) {
        const educationChartOptions = {
            series: @json($pendidikanData),
            chart: {
                type: 'donut',
                height: 350,
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#60A5FA', '#34D399', '#F59E0B', '#6366F1', '#8B5CF6', '#EC4899'],
            labels: @json($pendidikanLabels),
            legend: {
                position: 'bottom',
                fontSize: '14px',
                offsetY: 5,
                markers: {
                    width: 12,
                    height: 12,
                    radius: 6
                },
                itemMargin: {
                    horizontal: 5,
                    vertical: 2
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return Math.round(val) + '%';
                },
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold'
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        background: 'transparent',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '20px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 600,
                                color: '#111827',
                                offsetY: -10
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 400,
                                color: '#6B7280',
                                offsetY: 2
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                color: '#111827',
                                fontSize: '16px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 600
                            }
                        }
                    }
                }
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val + ' orang';
                    }
                }

            },
            stroke: {
                width: 2
            },
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 640,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            ]
        };

        const educationChart = new ApexCharts(document.getElementById('education-chart'), educationChartOptions);
        educationChart.render();
    }

    // 4. Occupation Chart (Donut)
    if (document.getElementById('occupation-chart')) {
        const occupationChartOptions = {
            series: @json($pekerjaanData),
            chart: {
                type: 'donut',
                height: 350,
                fontFamily: 'Inter, sans-serif',
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#F97316', '#8B5CF6', '#14B8A6', '#F43F5E', '#EAB308', '#3B82F6'],
            labels: @json($pekerjaanLabels),
            legend: {
                position: 'bottom',
                fontSize: '14px',
                offsetY: 5,
                markers: {
                    width: 12,
                    height: 12,
                    radius: 6
                },
                itemMargin: {
                    horizontal: 5,
                    vertical: 2
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return Math.round(val) + '%';
                },
                style: {
                    fontSize: '12px',
                    fontWeight: 'bold'
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        background: 'transparent',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '20px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 600,
                                color: '#111827',
                                offsetY: -10
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 400,
                                color: '#6B7280',
                                offsetY: 2
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                color: '#111827',
                                fontSize: '16px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: 600
                            }
                        }
                    }
                }
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val + ' orang';
                    }
                }
            },
            stroke: {
                width: 2
            },
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                },
                active: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            responsive: [
                {
                    breakpoint: 640,
                    options: {
                        chart: {
                            height: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            ]
        };

        const occupationChart = new ApexCharts(document.getElementById('occupation-chart'), occupationChartOptions);
        occupationChart.render();
    }

    // Inisialisasi dan fitur untuk tab Keuangan
    if (document.getElementById('section-keuangan')) {
        // Filter periode yang aktif
        let activePeriode = 'semua_waktu';  // Change from 'bulan_ini'
        let customDateRange = {
            dariTanggal: null,
            sampaiTanggal: null
        };

        // Load financial data based on period
        function loadFinancialData(periode, customDates = null) {
            // Show loading
            document.getElementById('keuangan-loading').classList.remove('hidden');
            document.getElementById('keuangan-stats-container').classList.add('pulse');

            // Prepare request data
            let requestData = {
                periode: periode
            };

            // Add custom dates if provided
            if (customDates && periode === 'kustom') {
                requestData.dari_tanggal = customDates.dariTanggal;
                requestData.sampai_tanggal = customDates.sampaiTanggal;
            }

            // Create a cache key for browser storage
            const cacheKey = `keuangan_data_${periode}${customDates ? '_' + customDates.dariTanggal + '_' + customDates.sampaiTanggal : ''}`;

            // Check if we have recent cached data in localStorage (less than 5 minutes old)
            const cachedData = localStorage.getItem(cacheKey);
            if (cachedData) {
                try {
                    const cached = JSON.parse(cachedData);
                    const cacheTime = new Date(cached.timestamp);
                    const now = new Date();
                    const cacheAge = (now - cacheTime) / 1000 / 60; // age in minutes

                    // Use cache if it's less than 5 minutes old
                    if (cacheAge < 5) {
                        // Hide loading
                        document.getElementById('keuangan-loading').classList.add('hidden');
                        document.getElementById('keuangan-stats-container').classList.remove('pulse');

                        // Update stats data
                        updateStats(cached.data);
                        return;
                    }
                } catch (e) {
                    // Continue with normal fetch if cache parsing fails
                }
            }

            // Make AJAX request
            fetch('/keuangan/data?' + new URLSearchParams(requestData), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                // Hide loading
                document.getElementById('keuangan-loading').classList.add('hidden');
                document.getElementById('keuangan-stats-container').classList.remove('pulse');

                // Cache the data in localStorage with timestamp
                localStorage.setItem(cacheKey, JSON.stringify({
                    data: data,
                    timestamp: new Date()
                }));

                // Update stats data
                updateStats(data);
            })
            .catch(error => {
                // Hide loading
                document.getElementById('keuangan-loading').classList.add('hidden');
                document.getElementById('keuangan-stats-container').classList.remove('pulse');

                // Show error message
                alert('Terjadi kesalahan saat memuat data keuangan. Silakan coba lagi.');
            });
        }

        // Update statistics with new data
        function updateStats(data) {
            // Update Stats Overview
            document.getElementById('total-pemasukan').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.overview.totalPemasukan);
            document.getElementById('total-pengeluaran').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.overview.totalPengeluaran);
            document.getElementById('saldo-desa').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(data.overview.saldoDesa);
            document.getElementById('periode-text').textContent = 'Saldo keuangan ' + data.overview.periodeLabel.toLowerCase();

            // Update period info
            document.getElementById('periode-info').innerHTML = 'Menampilkan data keuangan untuk periode <span class="font-semibold">' + data.overview.periodeLabel + '</span>.';

            // Update saldo status
            const saldoStatus = document.getElementById('saldo-status');
            if (data.overview.saldoDesa > 0) {
                saldoStatus.textContent = 'Kondisi keuangan desa dalam keadaan surplus sebesar ' + 'Rp ' + new Intl.NumberFormat('id-ID').format(data.overview.saldoDesa);
                saldoStatus.parentElement.classList.remove('bg-red-50', 'border-red-100');
                saldoStatus.parentElement.classList.add('bg-green-50', 'border-green-100');
                saldoStatus.classList.remove('text-red-800');
                saldoStatus.classList.add('text-green-800');
            } else if (data.overview.saldoDesa < 0) {
                saldoStatus.textContent = 'Kondisi keuangan desa dalam keadaan defisit sebesar ' + 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(data.overview.saldoDesa));
                saldoStatus.parentElement.classList.remove('bg-green-50', 'border-green-100');
                saldoStatus.parentElement.classList.add('bg-red-50', 'border-red-100');
                saldoStatus.classList.remove('text-green-800');
                saldoStatus.classList.add('text-red-800');
            } else {
                saldoStatus.textContent = 'Kondisi keuangan desa dalam keadaan seimbang';
                saldoStatus.parentElement.classList.remove('bg-red-50', 'border-red-100', 'bg-green-50', 'border-green-100');
                saldoStatus.parentElement.classList.add('bg-blue-50', 'border-blue-100');
                saldoStatus.classList.remove('text-red-800', 'text-green-800');
                saldoStatus.classList.add('text-blue-800');
            }

            // Update trend status (simple analysis based on data)
            const trendStatus = document.getElementById('trend-status');
            const ratio = data.overview.totalPengeluaran > 0 ?
                (data.overview.totalPemasukan / data.overview.totalPengeluaran) : 0;

            if (ratio >= 1.5) {
                trendStatus.textContent = 'Tren keuangan sangat positif. Pemasukan lebih besar 50% dari pengeluaran.';
            } else if (ratio > 1) {
                trendStatus.textContent = 'Tren keuangan positif. Pemasukan lebih besar dari pengeluaran.';
            } else if (ratio === 1) {
                trendStatus.textContent = 'Tren keuangan seimbang. Pemasukan sama dengan pengeluaran.';
            } else if (ratio >= 0.8) {
                trendStatus.textContent = 'Tren keuangan perlu perhatian. Pemasukan lebih kecil dari pengeluaran.';
            } else {
                trendStatus.textContent = 'Tren keuangan negatif. Pemasukan jauh lebih kecil dari pengeluaran.';
            }

            // Try to display top transactions if available in the API response
            if (data.topTransactions) {
                const topPemasukan = document.getElementById('top-pemasukan');
                const topPengeluaran = document.getElementById('top-pengeluaran');

                if (data.topTransactions.pemasukan && data.topTransactions.pemasukan.length > 0) {
                    const transaction = data.topTransactions.pemasukan[0];
                    topPemasukan.innerHTML = `
                        <div class="font-semibold text-lg">Rp ${new Intl.NumberFormat('id-ID').format(transaction.jumlah)}</div>
                        <div class="text-sm">${transaction.deskripsi || 'Tidak ada deskripsi'}</div>
                        <div class="text-xs text-gray-500">${transaction.tanggal || ''}</div>
                    `;
                } else {
                    topPemasukan.innerHTML = '<p class="text-gray-500 italic">Tidak ada data pemasukan</p>';
                }

                if (data.topTransactions.pengeluaran && data.topTransactions.pengeluaran.length > 0) {
                    const transaction = data.topTransactions.pengeluaran[0];
                    topPengeluaran.innerHTML = `
                        <div class="font-semibold text-lg">Rp ${new Intl.NumberFormat('id-ID').format(transaction.jumlah)}</div>
                        <div class="text-sm">${transaction.deskripsi || 'Tidak ada deskripsi'}</div>
                        <div class="text-xs text-gray-500">${transaction.tanggal || ''}</div>
                    `;
                } else {
                    topPengeluaran.innerHTML = '<p class="text-gray-500 italic">Tidak ada data pengeluaran</p>';
                }
            } else {
                // Fallback if topTransactions is not available
                document.getElementById('top-pemasukan').innerHTML = '<p class="text-gray-500 italic">Data tidak tersedia</p>';
                document.getElementById('top-pengeluaran').innerHTML = '<p class="text-gray-500 italic">Data tidak tersedia</p>';
            }
        }

        // Initialize filters
        function initFilters() {
            const periodeButtons = document.querySelectorAll('#periode-filters .periode-btn');
            const customButton = document.getElementById('btn-kustom');
            const dateRangePicker = document.getElementById('date-range-picker');
            const terapkanButton = document.getElementById('terapkan-filter');
            const dariTanggalInput = document.getElementById('dari-tanggal');
            const sampaiTanggalInput = document.getElementById('sampai-tanggal');

            // Set today as max date for date inputs
            const today = new Date().toISOString().split('T')[0];
            dariTanggalInput.setAttribute('max', today);
            sampaiTanggalInput.setAttribute('max', today);

            // Set default from and to dates (start and end of current month)
            const currentDate = new Date();
            const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).toISOString().split('T')[0];
            const lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).toISOString().split('T')[0];

            dariTanggalInput.value = firstDayOfMonth;
            sampaiTanggalInput.value = lastDayOfMonth;

            // Add click event to each period button
            periodeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const periode = this.getAttribute('data-periode');

                    // Skip if clicking the already active button
                    if (this.classList.contains('active') && periode !== 'kustom') return;

                    // Update active button
                    periodeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Show/hide date range picker
                    if (periode === 'kustom') {
                        dateRangePicker.classList.remove('hidden');
                    } else {
                        dateRangePicker.classList.add('hidden');

                        // Update active period and load data
                        activePeriode = periode;
                        loadFinancialData(activePeriode);
                    }
                });
            });

            // Apply custom date range
            terapkanButton.addEventListener('click', function() {
                const dariTanggal = dariTanggalInput.value;
                const sampaiTanggal = sampaiTanggalInput.value;

                if (!dariTanggal || !sampaiTanggal) {
                    alert('Silakan pilih rentang tanggal yang valid.');
                    return;
                }

                if (new Date(dariTanggal) > new Date(sampaiTanggal)) {
                    alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
                    return;
                }

                // Update custom date range
                customDateRange.dariTanggal = dariTanggal;
                customDateRange.sampaiTanggal = sampaiTanggal;

                // Load data with custom date range
                loadFinancialData('kustom', customDateRange);
            });
        }

        // Initialize when tab is shown
        document.getElementById('tab-keuangan').addEventListener('click', function() {
            // Check if we already initialized
            if (!document.getElementById('keuangan-initialized')) {
                // Add a hidden marker to track initialization
                const marker = document.createElement('div');
                marker.id = 'keuangan-initialized';
                marker.style.display = 'none';
                document.body.appendChild(marker);

                // Initialize
                initFilters();
                loadFinancialData(activePeriode);
            }
        });

        // Check for URL parameters to set the active tab
        if (window.location.search.includes('tab=keuangan')) {
            // Simulate clicking the keuangan tab
            document.getElementById('tab-keuangan').click();
        }
    }

    // Inisialisasi dan fitur untuk tab Bansos
    if (document.getElementById('section-bansos')) {
        // Filter periode yang aktif
        let activeBansosPeriode = 'semua_waktu';  // Change from 'bulan_ini'
        let bansosFilterType = 'status';
        let bansosChart = null;
        let bansosCustomDateRange = {
            dariTanggal: null,
            sampaiTanggal: null
        };

        // Load bansos data based on period and filter - set chartType to fixed 'donut'
        function loadBansosData(periode = 'semua_waktu', filter = 'status', customDates = null) {
            // Show loading state
            document.getElementById('bansos-loading').classList.remove('hidden');

            // Prepare request data
            const requestData = {
                periode: periode,
                filter: filter,
                chart_type: 'donut'
            };

            // Add custom dates if provided
            if (customDates && periode === 'kustom') {
                requestData.dariTanggal = customDates.dariTanggal;
                requestData.sampaiTanggal = customDates.sampaiTanggal;
            }

            // Create a browser cache key
            const cacheKey = `bansos_data_${periode}_${filter}_${customDates ? '_' + customDates.dariTanggal + '_' + customDates.sampaiTanggal : ''}`;

            // Check if we have valid cached data in browser storage (for 5 minutes)
            const cachedData = localStorage.getItem(cacheKey);
            if (cachedData) {
                try {
                    const cache = JSON.parse(cachedData);
                    const cacheTime = new Date(cache.timestamp);
                    const now = new Date();
                    const diffMinutes = (now - cacheTime) / 1000 / 60;

                    // Use cache if less than 5 minutes old
                    if (diffMinutes < 5) {
                        // Hide loading
                        document.getElementById('bansos-loading').classList.add('hidden');

                        // Update statistics using the cached data
                        updateBansosStats(cache.data);
                        return; // Exit early without server request
                    }
                } catch (e) {
                    // Invalid cache, continue with server request
                }
            }

            // Fetch data from the API
            fetch('/bansos/data?' + new URLSearchParams(requestData), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Cache the data in localStorage with timestamp
                    localStorage.setItem(cacheKey, JSON.stringify({
                        data: data,
                        timestamp: new Date()
                    }));

                    // Hide loading
                    document.getElementById('bansos-loading').classList.add('hidden');

                    // Update statistics
                    updateBansosStats(data);
                } else {
                    throw new Error(data.message || 'Unknown error occurred');
                }
            })
            .catch(error => {
                document.getElementById('bansos-loading').classList.add('hidden');

                // Show error alert
                alert('Terjadi kesalahan saat memuat data bantuan sosial. Silakan coba lagi.');
            });
        }

        // Extract the stats updating logic to a separate function
        function updateBansosStats(data) {
            // Update statistics
            document.getElementById('total-pengajuan').textContent = data.stats.total_pengajuan.toLocaleString('id-ID');
            document.getElementById('total-proses').textContent = data.stats.dalam_proses.toLocaleString('id-ID');
            document.getElementById('total-diterima').textContent = data.stats.sudah_diterima.toLocaleString('id-ID');
            document.getElementById('total-ditolak').textContent = data.stats.ditolak.toLocaleString('id-ID');

            // Update priority stats
            if (data.stats.prioritas) {
                document.getElementById('total-prioritas-tinggi').textContent =
                    data.stats.prioritas.tinggi.toLocaleString('id-ID');
            }

            // Calculate and update approval percentage
            const totalApproved = data.stats.sudah_diterima || 0;
            const totalApplications = data.stats.total_pengajuan || 1; // Avoid divide by zero
            const approvalPercentage = Math.round((totalApproved / totalApplications) * 100);
            document.getElementById('persentase-persetujuan').textContent = approvalPercentage + '%';

            // Update chart if data is available
            if (data.chart) {
                // Ensure we're getting valid labels and data
                const chartLabels = data.chart.labels || [];
                const chartData = data.chart.datasets[0].data || [];

                // Filter out any null or undefined values
                const validLabelIndices = chartLabels.map((label, index) =>
                    label !== null && label !== undefined ? index : -1
                ).filter(index => index !== -1);

                const filteredLabels = validLabelIndices.map(index => chartLabels[index]);
                const filteredData = validLabelIndices.map(index => chartData[index]);

                updateBansosChart({
                    labels: filteredLabels,
                    series: filteredData,
                    title: 'Distribusi Bantuan Sosial'
                }, 'donut', window.bansosFilterType || 'status'); // Pass the current filter type
            }
        }

        // Initialize and update Bansos chart - simplified for donut only
        function updateBansosChart(chartData, chartType = 'donut', filterType = 'status') {
            // Process the data to deduplicate labels
            if (chartData.labels && chartData.series) {
                // Create a map to combine duplicate labels
                const labelMap = new Map();

                chartData.labels.forEach((label, index) => {
                    if (labelMap.has(label)) {
                        // Add the value to existing entry
                        labelMap.set(label, labelMap.get(label) + chartData.series[index]);
                    } else {
                        // Create new entry
                        labelMap.set(label, chartData.series[index]);
                    }
                });

                // Reconstruct the data arrays
                const dedupedLabels = [...labelMap.keys()];
                const dedupedSeries = [...labelMap.values()];

                // Replace the original data
                chartData.labels = dedupedLabels;
                chartData.series = dedupedSeries;
            }

            // Calculate total explicitly
            const totalValue = chartData.series.reduce((a, b) => a + b, 0);

            // Chart colors based on data
            const getColors = (labels) => {
                const colorMap = {
                    // Status colors
                    'Diajukan': '#f59e0b',
                    'Dalam Verifikasi': '#60a5fa',
                    'Diverifikasi': '#3b82f6',
                    'Disetujui': '#10b981',
                    'Ditolak': '#ef4444',
                    'Sudah Diterima': '#84cc16',
                    'Dibatalkan': '#9ca3af',

                    // Priority colors
                    'Tinggi': '#ef4444',
                    'Sedang': '#f59e0b',
                    'Rendah': '#22c55e',
                    'Kasus Urgent': '#dc2626',

                    // Source colors
                    'Admin/Petugas Desa': '#3b82f6',
                    'Pengajuan Warga': '#10b981',
                };

                // Default colors for other data
                const defaultColors = [
                    '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6',
                    '#ec4899', '#6366f1', '#14b8a6', '#f97316', '#06b6d4'
                ];

                return labels.map((label, index) => {
                    return colorMap[label] || defaultColors[index % defaultColors.length];
                });
            };

            // Destroy existing chart if it exists
            if (bansosChart) {
                bansosChart.destroy();
            }

            // Get chart options optimized for donut chart
            const options = {
                series: chartData.series,
                    chart: {
                        type: 'donut',
                    height: 380,
                        toolbar: {
                            show: false
                        },
                    fontFamily: 'Inter, sans-serif',
                },
                labels: chartData.labels,
                colors: getColors(chartData.labels),
                    legend: {
                        position: 'bottom',
                    horizontalAlign: 'center',
                        fontSize: '14px',
                    offsetY: 8,
                        markers: {
                        width: 10,
                        height: 10,
                            radius: 6
                        },
                        itemMargin: {
                        horizontal: 10,
                        vertical: 5
                        }
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '60%',
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontSize: '20px',
                                        fontWeight: 600,
                                    offsetY: 0
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '16px',
                                    fontWeight: 500,
                                    offsetY: 8
                                    },
                                    total: {
                                        show: true,
                                    showAlways: true,
                                        fontSize: '16px',
                                    fontWeight: 600,
                                    label: 'Total',
                                    formatter: function() {
                                        return totalValue.toLocaleString('id-ID');
                                    }
                                    }
                                }
                            }
                        }
                    },
                dataLabels: {
                    enabled: window.innerWidth > 768,
                    formatter: function(val) {
                        return Math.round(val) + '%';
                    },
                    style: {
                        fontWeight: 'bold',
                        colors: ['#fff']
                    },
                    dropShadow: {
                        enabled: true
                    }
                },
                responsive: [{
                            breakpoint: 640,
                            options: {
                                chart: {
                            height: 350
                                },
                                legend: {
                            position: 'bottom',
                            offsetY: 0
                        }
                    }
                }],
                tooltip: {
                            enabled: true,
                    y: {
                        formatter: function(val, opts) {
                            const seriesIndex = opts.seriesIndex;
                            const originalValue = chartData.series[seriesIndex];
                            return originalValue.toLocaleString('id-ID');
                        }
                    }
                },
                        title: {
                    text: chartData.title || 'Grafik Bantuan Sosial',
                    align: 'center',
                            style: {
                        fontSize: '16px',
                        fontWeight: 600,
                        fontFamily: 'Inter, sans-serif',
                        color: '#374151'
                    }
                }
            };

            // REMOVED: Special handling for 'jenis' filter type

            // Create new chart
            bansosChart = new ApexCharts(document.getElementById('bansos-chart'), options);
            bansosChart.render();
        }

        // Initialize filters for bansos section
        function initBansosFilters() {
            const periodeButtons = document.querySelectorAll('#bansos-periode-filters .periode-btn');
            const dateRangePicker = document.getElementById('bansos-date-range-picker');
            const terapkanButton = document.getElementById('bansos-terapkan-filter');
            const dariTanggalInput = document.getElementById('bansos-dari-tanggal');
            const sampaiTanggalInput = document.getElementById('bansos-sampai-tanggal');
            const chartFilterSelect = document.getElementById('bansos-chart-filter');

            // Set today as max date for date inputs
            const today = new Date().toISOString().split('T')[0];
            dariTanggalInput.setAttribute('max', today);
            sampaiTanggalInput.setAttribute('max', today);

            // Set default from and to dates (start and end of current month)
            const currentDate = new Date();
            const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).toISOString().split('T')[0];
            const lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).toISOString().split('T')[0];

            dariTanggalInput.value = firstDayOfMonth;
            sampaiTanggalInput.value = lastDayOfMonth;

            // Add click event to each period button
            periodeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const periode = this.getAttribute('data-periode');

                    // Skip if clicking the already active button
                    if (this.classList.contains('active') && periode !== 'kustom') return;

                    // Update active button
                    periodeButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Show/hide date range picker
                    if (periode === 'kustom') {
                        dateRangePicker.classList.remove('hidden');
                    } else {
                        dateRangePicker.classList.add('hidden');

                        // Update active period and load data
                        activeBansosPeriode = periode;
                        loadBansosData(activeBansosPeriode, bansosFilterType, bansosCustomDateRange);
                    }
                });
            });

            // Apply custom date range
            terapkanButton.addEventListener('click', function() {
                const dariTanggal = dariTanggalInput.value;
                const sampaiTanggal = sampaiTanggalInput.value;

                if (!dariTanggal || !sampaiTanggal) {
                    alert('Silakan pilih rentang tanggal yang valid.');
                    return;
                }

                if (new Date(dariTanggal) > new Date(sampaiTanggal)) {
                    alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
                    return;
                }

                // Update custom date range
                bansosCustomDateRange.dariTanggal = dariTanggal;
                bansosCustomDateRange.sampaiTanggal = sampaiTanggal;

                // Load data with custom date range
                loadBansosData('kustom', bansosFilterType, bansosCustomDateRange);
            });

            // Chart filter change
            chartFilterSelect.addEventListener('change', function() {
                // Store the selected filter type globally or pass it appropriately
                window.bansosFilterType = this.value;
                loadBansosData(activeBansosPeriode, window.bansosFilterType, bansosCustomDateRange);
            });
        }

        // Initialize when tab is shown - load with fixed donut chart type
        document.getElementById('tab-bansos').addEventListener('click', function() {
            // Check if we already initialized
            if (!document.getElementById('bansos-initialized')) {
                // Add a hidden marker to track initialization
                const marker = document.createElement('div');
                marker.id = 'bansos-initialized';
                marker.style.display = 'none';
                document.body.appendChild(marker);

                // Initialize
                window.bansosFilterType = document.getElementById('bansos-chart-filter').value || 'status';
                initBansosFilters();
                loadBansosData(activeBansosPeriode, window.bansosFilterType);
            }
        });

        // Check for URL parameters to set the active tab
        if (window.location.search.includes('tab=bansos')) {
            // Simulate clicking the bansos tab
            document.getElementById('tab-bansos').click();
        }
    }

    // Inisialisasi dan fitur untuk tab Inventaris
    if (document.getElementById('section-inventaris')) {
        // Filter periode yang aktif
        let activeInventarisPeriode = 'semua_waktu';  // Change from 'bulan_ini'
        let inventarisFilterType = 'kategori';
        let inventarisChart = null;
        let inventarisCustomDateRange = {
            dariTanggal: null,
            sampaiTanggal: null
        };

        // Load inventaris data based on period and filter
        function loadInventarisData(periode = 'semua_waktu', customDates = null) {
            // Show loading state
            document.getElementById('inventaris-loading').classList.remove('hidden');
            document.querySelectorAll('#section-inventaris .stat-card').forEach(card => {
                card.classList.add('opacity-50');
            });

            // Create a browser cache key based on filter parameters
            const cacheKey = `inventaris_data_${periode}${customDates ? '_' + customDates.dariTanggal + '_' + customDates.sampaiTanggal : ''}`;

            // Check if we have valid cached data in browser storage (for 5 minutes)
            const cachedData = localStorage.getItem(cacheKey);
            if (cachedData) {
                try {
                    const cache = JSON.parse(cachedData);
                    const cacheTime = new Date(cache.timestamp);
                    const now = new Date();
                    const diffMinutes = (now - cacheTime) / 1000 / 60;

                    // Use cache if less than 5 minutes old
                    if (diffMinutes < 5) {
                        // Update UI with cached data
                        updateInventarisStats(cache.data);

                        // Hide loading state
                        document.getElementById('inventaris-loading').classList.add('hidden');
                        document.querySelectorAll('#section-inventaris .stat-card').forEach(card => {
                            card.classList.remove('opacity-50');
                        });
                        return; // Exit early without server request
                    }
                } catch (e) {
                    // Invalid cache, continue with server request
                    console.log('Cache error:', e);
                }
            }

            // Prepare request data
            const requestData = {
                periode: periode
            };

            // Add date range if custom period
            if (periode === 'kustom' && customDates) {
                requestData.dariTanggal = customDates.dariTanggal;
                requestData.sampaiTanggal = customDates.sampaiTanggal;
            }

            // Make the request with proper headers
            fetch('/inventaris/data?' + new URLSearchParams(requestData), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Cache the data in localStorage with timestamp
                    localStorage.setItem(cacheKey, JSON.stringify({
                        data: data,
                        timestamp: new Date()
                    }));

                    // Update UI with the data
                    updateInventarisStats(data);
                }

                // Hide loading state
                document.getElementById('inventaris-loading').classList.add('hidden');
                document.querySelectorAll('#section-inventaris .stat-card').forEach(card => {
                    card.classList.remove('opacity-50');
                });
            })
            .catch(error => {
                console.error('Error loading inventaris data:', error);

                // Hide loading state on error
                document.getElementById('inventaris-loading').classList.add('hidden');
                document.querySelectorAll('#section-inventaris .stat-card').forEach(card => {
                    card.classList.remove('opacity-50');
                });

                // Show user-friendly error message
                alert('Tidak dapat memuat data inventaris. Silakan coba lagi.');
            });
        }

        // Separate function to update the stats display (makes code more maintainable)
        function updateInventarisStats(data) {
            if (!data || !data.stats) return;

            // Update main statistics with thousand separators
            document.getElementById('total-inventaris').textContent = data.stats.total_inventaris.toLocaleString('id-ID');
            document.getElementById('total-unit').textContent = data.stats.total_unit.toLocaleString('id-ID') + ' unit';
            document.getElementById('total-nilai').textContent = data.stats.total_nilai;

            // Update kondisi statistics (unit counts)
            document.getElementById('kondisi-baik').textContent = data.stats.kondisi.baik.toLocaleString('id-ID') + ' unit';
            document.getElementById('kondisi-rusak-ringan').textContent = data.stats.kondisi.rusak_ringan.toLocaleString('id-ID') + ' unit';
            document.getElementById('kondisi-rusak-berat').textContent = data.stats.kondisi.rusak_berat.toLocaleString('id-ID') + ' unit';

            // Update status statistics (unit counts)
            document.getElementById('status-tersedia').textContent = data.stats.status.tersedia.toLocaleString('id-ID') + ' unit';
            document.getElementById('status-dipinjam').textContent = data.stats.status.dipinjam.toLocaleString('id-ID') + ' unit';
            document.getElementById('status-dalam-perbaikan').textContent = data.stats.status.dalam_perbaikan.toLocaleString('id-ID') + ' unit';
        }

        // Initialize filters for inventaris section
        function initInventarisFilters() {
            // Set default active period for inventaris section
            window.activeInventarisPeriode = 'semua_waktu';

            // Set up event listeners for period buttons
            document.querySelectorAll('#inventaris-periode-filters .periode-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const periode = this.getAttribute('data-periode');

                    // Skip if clicking the already active button
                    if (this.classList.contains('active') && periode !== 'kustom') return;

                    window.activeInventarisPeriode = periode;

                    // Update active state - THIS IS THE FIX
                    document.querySelectorAll('#inventaris-periode-filters .periode-btn').forEach(btn => {
                        btn.classList.remove('active', 'bg-emerald-600', 'text-white');
                        btn.classList.add('bg-gray-100', 'text-gray-700');
                    });

                    this.classList.add('active', 'bg-emerald-600', 'text-white');
                    this.classList.remove('bg-gray-100', 'text-gray-700');

                    // Hide the date range picker if not custom periode
                    if (periode !== 'kustom') {
                        document.getElementById('inventaris-date-range-picker').classList.add('hidden');
                        // Load data immediately when changing periode
                        loadInventarisData(periode);
            } else {
                        document.getElementById('inventaris-date-range-picker').classList.remove('hidden');
                    }
                });
            });

            // Make sure the semua_waktu button is properly marked as active initially
            const defaultButton = document.querySelector('#inventaris-periode-filters .periode-btn[data-periode="semua_waktu"]');
            if (defaultButton) {
                defaultButton.classList.add('active');
            }

            // Rest of the function remains unchanged
            const terapkanButton = document.getElementById('inventaris-terapkan-filter');
            if (terapkanButton) {
                terapkanButton.addEventListener('click', function() {
                    const dariTanggal = document.getElementById('inventaris-dari-tanggal').value;
                    const sampaiTanggal = document.getElementById('inventaris-sampai-tanggal').value;

                    if (dariTanggal && sampaiTanggal) {
                        loadInventarisData('kustom', { dariTanggal, sampaiTanggal });
                    } else {
                        alert('Silakan pilih rentang tanggal lengkap');
                    }
                });
            }
        }

        // Initialize when tab is shown - load all data
        document.getElementById('tab-inventaris').addEventListener('click', function() {
            // Check if we already initialized
            if (!document.getElementById('inventaris-initialized')) {
                // Add a hidden marker to track initialization
                const marker = document.createElement('div');
                marker.id = 'inventaris-initialized';
                marker.style.display = 'none';
                document.body.appendChild(marker);

                // Load all data (semua_waktu)
                loadInventarisData('semua_waktu');
            }
        });

        // Check for URL parameters to set the active tab
        if (window.location.search.includes('tab=inventaris')) {
            // Simulate clicking the inventaris tab
            document.getElementById('tab-inventaris').click();
        }
    }
});

// Script untuk Update Label Periode
document.addEventListener('DOMContentLoaded', function() {
    const periodeButtons = document.querySelectorAll('.periode-btn');
    const selectedPeriode = document.getElementById('selected-periode');

    periodeButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update dropdown button text
            selectedPeriode.textContent = this.textContent.trim();
        });
    });
});

// Simplified loading function without filters
function loadInventarisData(periode = 'semua_waktu') {
    // Show loading state
    document.getElementById('inventaris-loading').classList.remove('hidden');

    // Prepare request data - always use semua_waktu
    const requestData = {
        periode: 'semua_waktu'
    };

    // Fetch data from the API
    fetch('/inventaris/data?' + new URLSearchParams(requestData), {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Hide loading
            document.getElementById('inventaris-loading').classList.add('hidden');

            // Update statistics
            updateInventarisStats(data);
        } else {
            throw new Error(data.message || 'Unknown error occurred');
        }
    })
    .catch(error => {
        document.getElementById('inventaris-loading').classList.add('hidden');

        // Show error alert
        alert('Terjadi kesalahan saat memuat data inventaris. Silakan coba lagi.');
    });
}

// Update stats with fetched data
function updateInventarisStats(data) {
    // Update main statistics with thousand separators
    document.getElementById('total-inventaris').textContent = data.stats.total_inventaris.toLocaleString('id-ID');
    document.getElementById('total-unit').textContent = data.stats.total_unit.toLocaleString('id-ID') + ' unit';
    document.getElementById('total-nilai').textContent = data.stats.total_nilai;

    // Update kondisi statistics (unit counts)
    document.getElementById('kondisi-baik').textContent = data.stats.kondisi.baik.toLocaleString('id-ID') + ' unit';
    document.getElementById('kondisi-rusak-ringan').textContent = data.stats.kondisi.rusak_ringan.toLocaleString('id-ID') + ' unit';
    document.getElementById('kondisi-rusak-berat').textContent = data.stats.kondisi.rusak_berat.toLocaleString('id-ID') + ' unit';

    // Update status statistics (unit counts)
    document.getElementById('status-tersedia').textContent = data.stats.status.tersedia.toLocaleString('id-ID') + ' unit';
    document.getElementById('status-dipinjam').textContent = data.stats.status.dipinjam.toLocaleString('id-ID') + ' unit';
    document.getElementById('status-dalam-perbaikan').textContent = data.stats.status.dalam_perbaikan.toLocaleString('id-ID') + ' unit';
}
</script>
@endpush
