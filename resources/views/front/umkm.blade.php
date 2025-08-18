@extends('layouts.front')

@section('title', 'UMKM ' . ($profilDesa->nama_desa ?? 'Desa'))

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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        UMKM
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
                 onclick="document.getElementById('products').scrollIntoView({behavior: 'smooth'})">
                <!-- Moving Light Effect on Hover -->
                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-all duration-1000 ease-in-out"></span>

                <!-- Badge Content -->
                <div class="relative z-10 flex items-center">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2.5 animate-pulse"></div>
                    <span class="text-white font-semibold text-sm tracking-wider">PRODUK LOKAL</span>
                </div>
            </div>

            <!-- Main Title with Interactive Element -->
            <h1 class="mt-4 text-3xl md:text-4xl lg:text-5xl font-bold text-white group" data-aos="fade-up">
                <span class="relative inline-block">
               UMKM {{ $profilDesa->nama_desa ?? 'Desa' }}
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-emerald-300 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></span>
                </span>
            </h1>

            <!-- Description with Fade In Effect -->
            <p class="mt-4 max-w-2xl mx-auto text-emerald-50 text-lg opacity-90" data-aos="fade-up" data-aos-delay="100">
                Produk dan jasa unggulan dari pelaku usaha mikro, kecil, dan menengah di {{ $profilDesa->nama_desa ?? 'desa kami' }}
            </p>

            <!-- Action Button -->
            <div class="mt-8" data-aos="fade-up" data-aos-delay="200">
                <a href="#products" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-emerald-700 font-medium rounded-full hover:bg-emerald-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-shopping-bag"></i>
                    Lihat Produk
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

<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    .animate-shimmer {
        animation: shimmer 2s infinite;
    }
</style>

<!-- Products Section Start Here -->
<section id="products" class=" bg-white">
    <!-- Kategori Filter with Smooth Drag -->
    <section class="pb-3 bg-white " id="products" x-data="{
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            PRODUK LOKAL
                        </span>
                    </div>
                </div>

                <!-- Description on Full Row -->
                <p class="text-gray-600 text-sm md:text-base w-full mb-3">
                    Pilih kategori untuk melihat produk dan jasa UMKM sesuai dengan kebutuhan Anda
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
                        <a href="{{ route('umkm') }}"
                           @click="if(moveDistance > 10) $event.preventDefault(); else { activeCategory = 'semua'; scrollToActiveCategory(); }"
                           :class="{ 'bg-emerald-600 text-white shadow-md active-category': activeCategory === 'semua', 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:text-gray-800': activeCategory !== 'semua' }"
                           class="shrink-0 px-4 py-2.5 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center">
                            <i class="fas fa-th-large mr-1.5 text-xs"></i>
                            Semua Kategori
                        </a>

                        @php
                            $kategoriList = [
                                'Kuliner' => 'Kuliner',
                                'Kerajinan' => 'Kerajinan',
                                'Fashion' => 'Fashion',
                                'Pertanian' => 'Pertanian',
                                'Jasa' => 'Jasa',
                                'Lainnya' => 'Lainnya',
                            ];

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

                        @foreach($kategoriList as $key => $value)
                            <a href="{{ route('umkm', ['kategori' => $key]) }}"
                               @click="if(moveDistance > 10) $event.preventDefault(); else { activeCategory = '{{ $key }}'; scrollToActiveCategory(); }"
                               :class="{ 'bg-emerald-600 text-white shadow-md active-category': activeCategory === '{{ $key }}', 'bg-gray-50 text-gray-700 hover:bg-gray-100 hover:text-gray-800': activeCategory !== '{{ $key }}' }"
                               class="shrink-0 px-4 py-2.5 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center">
                                <i class="{{ $kategoriIcons[$key] ?? 'fas fa-store' }} mr-1.5 text-xs"></i>
                                {{ $value }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- UMKM Grid -->
    <section class="py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(count($umkm) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($umkm as $item)
                        <div class="group bg-gray-50 rounded-2xl shadow-sm overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-lg relative border border-gray-100" data-aos="fade-up" data-aos-delay="{{ $loop->index % 4 * 50 }}">
                            <!-- Enhanced Image Container with Overlay Gradient -->
                            <div class="relative aspect-w-16 aspect-h-9 overflow-hidden">
                                @if($item->foto_usaha)
                                    <img src="{{ Storage::url($item->foto_usaha) }}"
                                         alt="{{ $item->nama_usaha }}"
                                         class="object-cover w-full h-full transition-transform duration-700 group-hover:scale-105">

                                    <!-- Gradient Overlay -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <i class="fas fa-store text-gray-300 text-5xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Modern Content Area -->
                            <div class="p-6 flex flex-col flex-grow relative bg-gray-50">
                                <!-- Modern Category and Info Row -->
                                <div class="flex items-center justify-between mb-4 relative z-10">
                                    <!-- Left: Category Badge - Enhanced Design -->
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium
                                        text-{{ $kategoriColors[$item->kategori] ?? 'emerald' }}-700
                                        bg-{{ $kategoriColors[$item->kategori] ?? 'emerald' }}-50
                                        border border-{{ $kategoriColors[$item->kategori] ?? 'emerald' }}-100 shadow-sm">
                                        <i class="{{ $kategoriIcons[$item->kategori] ?? 'fas fa-store' }} text-{{ $kategoriColors[$item->kategori] ?? 'emerald' }}-500 mr-1.5"></i>
                                        {{ $item->kategori }}
                                    </span>

                                    <!-- Right: UMKM Badge -->
                                    <div class="flex items-center text-xs font-medium px-2.5 py-1.5 rounded-md border border-blue-100 bg-blue-50 text-blue-700">
                                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Terverifikasi
                                    </div>
                                </div>

                                <!-- Enhanced Title with Modern Typography -->
                                <a href="{{ route('umkm.show', $item->id) }}" class="block group relative z-10">
                                    <!-- Modern Title Design with Gradient Accent -->
                                    <div class="relative h-0.5 w-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full mb-3 transition-all duration-300 group-hover:w-24"></div>
                                    <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                        {{ $item->nama_usaha }}
                                    </h3>
                                </a>

                                <!-- Modern Description with Premium Typography -->
                                <div class="mt-1 relative z-10">
                                    <p class="text-gray-600 text-sm leading-relaxed mb-5 pl-3 border-l-2 border-emerald-200 line-clamp-2 after:content-['...']">
                                        {{ $item->deskripsi }}
                                    </p>
                                </div>

                                <!-- Location Information -->
                                <div class="flex items-start mt-1 mb-4">
                                    <span class="text-emerald-500 mr-2 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </span>
                                    <p class="text-gray-600 text-sm line-clamp-1">
                                        {{ $item->lokasi ?: 'Lokasi belum diisi' }}
                                    </p>
                                </div>

                                <!-- Enhanced Action Buttons with Modern Design -->
                                <div class="mt-auto pt-4 border-t border-dashed border-gray-200 flex justify-between items-center relative z-10">
                                    <!-- WhatsApp Button -->
                                    <a href="https://wa.me/{{ $item->kontak_whatsapp }}" target="_blank"
                                       class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium text-sm rounded-lg px-4 py-2 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                        <i class="fab fa-whatsapp mr-1.5 text-white"></i>
                                        <span>WhatsApp</span>
                                    </a>

                                    <!-- Detailed Link -->
                                    <a href="{{ route('umkm.show', $item->id) }}" class="inline-flex items-center text-emerald-600 font-medium text-sm transition-colors duration-300 group/detail hover:text-emerald-700">
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
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $umkm->links('vendor.pagination.card') }}
                </div>

            @else
                <div class="text-center py-16 bg-gray-50 rounded-xl shadow-sm">
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gray-100 text-gray-400 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-medium text-gray-700">Belum ada UMKM</h3>
                    <p class="text-gray-500 mt-2">Belum ada UMKM yang terdaftar pada kategori ini</p>
                </div>
            @endif
        </div>
    </section>
</section>
@endsection