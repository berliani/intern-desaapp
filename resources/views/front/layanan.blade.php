@extends('layouts.front')

@section('title', 'Layanan ' . ($profilDesa->nama_desa ?? 'Desa'))

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Layanan Desa
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
                 onclick="document.getElementById('layanan-list').scrollIntoView({behavior: 'smooth'})">
                <!-- Moving Light Effect on Hover -->
                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-all duration-1000 ease-in-out"></span>

                <!-- Badge Content -->
                <div class="relative z-10 flex items-center">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2.5 animate-pulse"></div>
                    <span class="text-white font-semibold text-sm tracking-wider">PELAYANAN DESA</span>
                </div>
            </div>

            <!-- Main Title with Interactive Element -->
            <h1 class="mt-4 text-3xl md:text-4xl lg:text-5xl font-bold text-white group" data-aos="fade-up">
                <span class="relative inline-block">
                   Layanan {{ $profilDesa->nama_desa ?? 'Desa' }}
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-emerald-300 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></span>
                </span>
            </h1>

            <!-- Description with Fade In Effect -->
            <p class="mt-4 max-w-2xl mx-auto text-emerald-50 text-lg opacity-90" data-aos="fade-up" data-aos-delay="100">
                Informasi lengkap tentang layanan administrasi dan fasilitas yang tersedia untuk warga desa
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

<!-- Kategori Filter with Smooth Drag -->
<section class="pb-3 bg-white shadow-sm" id="layanan-list" x-data="{
    activeCategory: '{{ request('kategori') ?? 'semua' }}',
    isDragging: false,
    startX: 0,
    scrollLeft: 0,
    moveDistance: 0,

    handleMouseDown(e) {
        const slider = $refs.categorySlider;
        this.isDragging = true;
        this.startX = e.pageX - slider.offsetLeft;
        this.scrollLeft = slider.scrollLeft;
        this.moveDistance = 0;
        slider.classList.add('cursor-grabbing');
    },

    handleMouseMove(e) {
        if(!this.isDragging) return;
        e.preventDefault();
        const slider = $refs.categorySlider;
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - this.startX) * 2;
        this.moveDistance += Math.abs(walk);
        slider.scrollLeft = this.scrollLeft - walk;
    },

    handleMouseUp(e) {
        if(this.isDragging) {
            if(this.moveDistance > 10 && e.target.tagName === 'A') {
                e.preventDefault(); // Prevent click only if we dragged significantly
            }
            this.isDragging = false;
            $refs.categorySlider.classList.remove('cursor-grabbing');
        }
    },

    scrollToActiveCategory() {
        setTimeout(() => {
            const activeEl = this.$el.querySelector('.active-category');
            if (activeEl) {
                const container = $refs.categorySlider;
                const containerWidth = container.offsetWidth;
                const buttonWidth = activeEl.offsetWidth;
                const buttonLeft = activeEl.offsetLeft;

                container.scrollTo({
                    left: buttonLeft - (containerWidth / 2) + (buttonWidth / 2),
                    behavior: 'smooth'
                });
            }
        }, 50);
    }
}" x-init="scrollToActiveCategory()">
    <div class="max-w-7xl pt-4 mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Modern Header -->
        <div class="mb-0">
            <!-- Title Row -->
            <div class="flex items-center mb-3">
                <div class="flex items-center">
                    <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                    <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        LAYANAN DESA
                    </span>
                </div>
            </div>

            <!-- Description on Full Row -->
            <p class="text-gray-600 text-sm md:text-base w-full mb-3">
                Pilih kategori untuk melihat layanan desa sesuai dengan kebutuhan Anda
            </p>
        </div>

        <div class="relative">
            <div
                x-ref="categorySlider"
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
                <div class="inline-flex gap-2 min-w-full px-1 py-1">
                    <a href="{{ route('layanan') }}"
                       @click="if(moveDistance > 10) $event.preventDefault(); else { activeCategory = 'semua'; scrollToActiveCategory(); }"
                       :class="{ 'bg-emerald-600 text-white shadow-md active-category': activeCategory === 'semua', 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:text-gray-800': activeCategory !== 'semua' }"
                       class="shrink-0 px-4 py-2.5 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center">
                        <i class="fas fa-th-large mr-1.5 text-xs"></i>
                        Semua Kategori
                    </a>

                    @php
                        $kategoriList = [
                            'Surat' => 'Surat',
                            'Kesehatan' => 'Kesehatan',
                            'Pendidikan' => 'Pendidikan',
                            'Sosial' => 'Sosial',
                            'Infrastruktur' => 'Infrastruktur'
                        ];

                        $kategoriColors = [
                            'Surat' => 'blue',
                            'Kesehatan' => 'green',
                            'Pendidikan' => 'yellow',
                            'Sosial' => 'purple',
                            'Infrastruktur' => 'red',
                        ];

                        $kategoriIcons = [
                            'Surat' => 'fas fa-file-alt',
                            'Kesehatan' => 'fas fa-heartbeat',
                            'Pendidikan' => 'fas fa-graduation-cap',
                            'Sosial' => 'fas fa-users',
                            'Infrastruktur' => 'fas fa-road',
                        ];
                    @endphp

                    @foreach($kategoriList as $key => $value)
                        <a href="{{ route('layanan', ['kategori' => $key]) }}"
                           @click="if(moveDistance > 10) $event.preventDefault(); else { activeCategory = '{{ $key }}'; scrollToActiveCategory(); }"
                           :class="{ 'bg-emerald-600 text-white shadow-md active-category': activeCategory === '{{ $key }}', 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:text-gray-800': activeCategory !== '{{ $key }}' }"
                           class="shrink-0 px-4 py-2.5 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center">
                            <i class="{{ $kategoriIcons[$key] ?? 'fas fa-info-circle' }} mr-1.5 text-xs"></i>
                            {{ $value }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Layanan Desa -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(count($layanans) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($layanans as $layanan)
                    <div class="group bg-gray-50 rounded-2xl shadow-sm overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-lg relative border border-gray-100" data-aos="fade-up" data-aos-delay="{{ $loop->index % 3 * 50 }}">
                        @php
                            $kategoriColor = $kategoriColors[$layanan->kategori] ?? 'emerald';
                        @endphp

                        <div class="p-6 flex flex-col flex-grow">
                            <!-- Category and Price Row -->
                            <div class="flex justify-between items-start mb-4 relative z-10">
                                <!-- Left: Category Badge - Enhanced Design -->
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium
                                    text-{{ $kategoriColor }}-700
                                    bg-{{ $kategoriColor }}-50
                                    border border-{{ $kategoriColor }}-100 shadow-sm">
                                    <i class="{{ $kategoriIcons[$layanan->kategori] ?? 'fas fa-info-circle' }} text-{{ $kategoriColor }}-500 mr-1.5"></i>
                                    {{ $layanan->kategori }}
                                </span>

                                <!-- Right: Price Badge -->
                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-md border border-gray-100 bg-white text-gray-700 text-xs font-medium">
                                    <i class="fas fa-money-bill-wave text-gray-500 mr-1.5"></i>
                                    {{ $layanan->getBiayaFormatted() }}
                                </span>
                            </div>

                            <!-- Enhanced Title with Modern Typography -->
                            <a href="{{ route('layanan.show', $layanan->id) }}" class="block group relative z-10">
                                <!-- Modern Title Design with Gradient Accent -->
                                <div class="relative h-0.5 w-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full mb-3 transition-all duration-300 group-hover:w-24"></div>
                                <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                    {{ $layanan->nama_layanan }}
                                </h3>
                            </a>

                            <!-- Modern Description with Premium Typography -->
                            <div class="mt-1 relative z-10">
                                <p class="text-gray-600 text-sm leading-relaxed mb-5 pl-3 border-l-2 border-emerald-200 line-clamp-3 after:content-['...']">
                                    {!! strip_tags($layanan->deskripsi) !!}
                                </p>
                            </div>

                            <!-- Info Icons Section -->
                            <div class="space-y-2 mt-1 mb-4">
                                @if($layanan->lokasi_layanan)
                                    <div class="flex items-start">
                                        <span class="text-emerald-500 mr-2 mt-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </span>
                                        <p class="text-gray-600 text-sm line-clamp-1">
                                            {{ $layanan->lokasi_layanan }}
                                        </p>
                                    </div>
                                @endif

                                @if($layanan->jadwal_pelayanan)
                                    <div class="flex items-start">
                                        <span class="text-emerald-500 mr-2 mt-0.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </span>
                                        <p class="text-gray-600 text-sm line-clamp-1">
                                            {{ $layanan->jadwal_pelayanan }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Enhanced Action Buttons with Modern Design - No border-t -->
                            <div class="mt-auto flex justify-between items-center relative z-10">
                                <!-- Detail Button - Consistent styling with UMKM buttons -->
                                <a href="{{ route('layanan.show', $layanan->id) }}" class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium text-sm rounded-lg px-4 py-2 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                    <i class="fas fa-info-circle mr-1.5 text-white"></i>
                                    <span>Detail Layanan</span>
                                </a>

                                <!-- Contact Link - More modern pill design -->
                                @if($layanan->kontak_layanan)
                                <a href="tel:{{ $layanan->kontak_layanan }}" class="inline-flex items-center px-3.5 py-1.5 bg-white text-emerald-600 rounded-full shadow-sm text-sm font-medium border border-emerald-100 hover:bg-emerald-50 transition-all duration-300 hover:shadow-md">
                                    <i class="fas fa-phone-alt mr-1.5"></i>
                                    Hubungi
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $layanans->links('vendor.pagination.card') }}
            </div>

        @else
            <!-- Empty State with Modern Design -->
            <div class="text-center py-16 bg-gray-50 rounded-xl shadow-sm border border-gray-100">
                <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-white text-gray-400 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-xl font-medium text-gray-700">Belum ada layanan</h3>
                <p class="text-gray-500 mt-2">Belum ada layanan desa yang terdaftar pada kategori ini</p>
                <div class="mt-6">
                    <a href="{{ route('layanan') }}" class="inline-flex items-center px-4 py-2 bg-white text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Lihat semua layanan
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection