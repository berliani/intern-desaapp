@extends('layouts.front')

@section('title', 'Berita ' . ($profilDesa->nama_desa ?? 'Desa'))

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        Berita
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
                 onclick="document.getElementById('berita-list').scrollIntoView({behavior: 'smooth'})">
                <!-- Moving Light Effect on Hover -->
                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-all duration-1000 ease-in-out"></span>

                <!-- Badge Content -->
                <div class="relative z-10 flex items-center">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2.5 animate-pulse"></div>
                    <span class="text-white font-semibold text-sm tracking-wider">INFORMASI TERKINI</span>
                </div>
            </div>

            <!-- Main Title with Interactive Element -->
            <h1 class="mt-4 text-3xl md:text-4xl lg:text-5xl font-bold text-white group" data-aos="fade-up">
                <span class="relative inline-block">
                Berita {{ $profilDesa->nama_desa ?? 'Desa' }}
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-emerald-300 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></span>
                </span>
            </h1>

            <!-- Description with Fade In Effect -->
            <p class="mt-4 max-w-2xl mx-auto text-emerald-50 text-lg opacity-90" data-aos="fade-up" data-aos-delay="100">
                Informasi terbaru seputar kegiatan dan pengumuman di {{ $profilDesa->nama_desa ?? 'desa kami' }}
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
<section class="pb-3 bg-white shadow-sm" id="berita-list" x-data="{
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
}">
    <div class="max-w-7xl pt-4 mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Modern Header -->
        <div class="mb-0">
            <!-- Title Row -->
            <div class="flex items-center mb-3">
                <div class="flex items-center">
                    <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                    <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                        <i class="fas fa-newspaper text-emerald-600 mr-1.5"></i>
                        INFORMASI TERKINI
                    </span>
                </div>
            </div>

            <!-- Description on Full Row -->
            <p class="text-gray-600 text-sm md:text-base w-full mb-3">
                Pilih kategori untuk memfilter berita dan informasi sesuai topik yang Anda minati
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
                    <a href="{{ route('berita') }}"
                       @click="if(moveDistance > 10) $event.preventDefault(); else { activeCategory = 'semua'; scrollToActiveCategory(); }"
                       :class="{ 'bg-emerald-600 text-white shadow-md active-category': activeCategory === 'semua', 'bg-gray-100/80 text-gray-600 hover:bg-gray-200 hover:text-gray-700': activeCategory !== 'semua' }"
                       class="shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center">
                        <i class="fas fa-th-large mr-1.5 text-xs"></i>
                        Semua Kategori
                    </a>

                    @php
                        $kategoriList = [
                            'Umum' => 'Umum',
                            'Pengumuman' => 'Pengumuman',
                            'Kegiatan' => 'Kegiatan',
                            'Infrastruktur' => 'Infrastruktur',
                            'Kesehatan' => 'Kesehatan',
                            'Pendidikan' => 'Pendidikan',
                        ];

                        $icons = [
                            'Umum' => 'fa-globe',
                            'Pengumuman' => 'fa-bullhorn',
                            'Kegiatan' => 'fa-calendar-check',
                            'Infrastruktur' => 'fa-road',
                            'Kesehatan' => 'fa-heartbeat',
                            'Pendidikan' => 'fa-graduation-cap',
                        ];
                    @endphp

                    @foreach($kategoriList as $key => $value)
                        <a href="{{ route('berita', ['kategori' => $key]) }}"
                           @click="if(moveDistance > 10) $event.preventDefault(); else { activeCategory = '{{ $key }}'; scrollToActiveCategory(); }"
                           :class="{ 'bg-emerald-600 text-white shadow-md active-category': activeCategory === '{{ $key }}', 'bg-gray-100/80 text-gray-600 hover:bg-gray-200 hover:text-gray-700': activeCategory !== '{{ $key }}' }"
                           class="shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center">
                            <i class="fas {{ $icons[$key] ?? 'fa-tag' }} mr-1.5 text-xs"></i>
                            {{ $value }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Berita Terbaru with Modern Cards - White Background -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(count($berita) > 0)
            <!-- Grid Berita - Styled Like Home Page -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($berita as $index => $item)
                    <div class="group bg-white rounded-2xl shadow-sm overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-lg relative border border-gray-100"
                         data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <!-- Enhanced Image Container with Overlay Gradient -->
                        <div class="relative aspect-w-16 aspect-h-9 overflow-hidden">
                                @if($item->gambar)
                                <img class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-105"
                                     src="{{ Storage::url($item->gambar) }}"
                                         alt="{{ $item->judul }}"
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
                                <!-- Modern Calendar Date Style -->
                                <div class="flex items-center">
                                    <div class="flex flex-col items-center mr-3 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg shadow-sm overflow-hidden border border-emerald-100 transition-all duration-300 group-hover:shadow-md">
                                        <div class="bg-emerald-500 text-white text-xs font-bold w-full text-center py-0.5 px-2">
                                            {{ $item->created_at->locale('id')->isoFormat('MMM') }}
                                        </div>
                                        <div class="text-emerald-700 text-base font-bold py-0.5 px-3">
                                            {{ $item->created_at->isoFormat('D') }}
                                        </div>
                                    </div>

                                    <div class="flex flex-col text-xs">
                                        <span class="text-gray-500 font-medium">
                                            {{ $item->created_at->isoFormat('YYYY') }}
                                        </span>
                                        <span class="text-gray-400">
                                            {{ $item->created_at->locale('id')->isoFormat('dddd') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Enhanced Category Badge -->
                                @if(isset($item->kategori))
                                    @php
                                        $styles = match($item->kategori) {
                                            'Umum' => 'bg-indigo-500 border-indigo-600',
                                            'Pengumuman' => 'bg-amber-500 border-amber-600',
                                            'Kegiatan' => 'bg-emerald-500 border-emerald-600',
                                            'Infrastruktur' => 'bg-rose-500 border-rose-600',
                                            'Kesehatan' => 'bg-sky-500 border-sky-600',
                                            'Pendidikan' => 'bg-purple-500 border-purple-600',
                                            default => 'bg-gray-500 border-gray-600',
                                        };
                                        $icon = match($item->kategori) {
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
                                    {{ $item->kategori }}
                                </span>
                                @endif
                            </div>

                            <!-- Enhanced Title with Premium Typography and Truncation -->
                            <a href="{{ route('berita.show', $item->id) }}" class="block group relative z-10">
                                <!-- Modern Title Design with Gradient Accent -->
                                <div class="relative h-0.5 w-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full mb-3 transition-all duration-300 group-hover:w-24"></div>
                                <h3 class="text-xl font-bold text-gray-900 leading-tight mb-3 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                    {{ $item->judul }}
                                </h3>
                            </a>

                            <!-- Modern Description with Premium Typography and 2-line Truncation (UMKM Style) -->
                            <div class="mt-1 relative z-10">
                                <p class="text-gray-600 text-sm leading-relaxed mb-5 pl-3 border-l-2 border-emerald-200 line-clamp-2 after:content-['...']">
                                    {{ strip_tags($item->konten ?? $item->isi) }}
                                </p>
                            </div>

                            <!-- Author and Read More Section -->
                            <div class="mt-auto pt-4 border-t border-dashed border-gray-200 flex justify-between items-center relative z-10">
                                <!-- Author Info -->
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-user text-emerald-500 mr-2"></i>
                                    {{ $item->creator->name ?? 'Admin' }}
                                </div>

                                <!-- Modern Read More Link -->
                                <a href="{{ route('berita.show', $item->id) }}" class="inline-flex items-center bg-emerald-50 hover:bg-emerald-100 text-emerald-600 font-medium text-sm rounded-full px-4 py-1.5 transition-colors duration-300 group/link">
                                    <span>Baca selengkapnya</span>
                                    <svg class="ml-1.5 w-4 h-4 transform transition-transform duration-300 group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination - Kept Intact -->
            <div class="mt-12">
                {{ $berita->links('vendor.pagination.card') }}
            </div>

        @else
            <!-- Empty State - Modernized -->
            <div class="bg-gray-50 rounded-xl shadow-sm border border-gray-100 p-12 text-center mb-4">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-newspaper text-gray-300 text-3xl"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-700 mb-2">Belum ada berita</h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    Belum ada berita yang dipublikasikan pada kategori ini. Silahkan coba pilih kategori lainnya.
                </p>
                <div class="mt-6">
                    <a href="{{ route('berita') }}" class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Lihat semua berita
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection