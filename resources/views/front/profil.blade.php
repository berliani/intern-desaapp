@extends('layouts.front')

@section('title', 'Profil Desa')

@section('breadcrumbs')
<div class=" border-gray-100">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Profil Desa
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
                 onclick="document.getElementById('profile-overview').scrollIntoView({behavior: 'smooth'})">
                <!-- Moving Light Effect on Hover -->
                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-all duration-1000 ease-in-out"></span>

                <!-- Badge Content -->
                <div class="relative z-10 flex items-center">
                    <div class="w-2 h-2 bg-emerald-400 rounded-full mr-2.5 animate-pulse"></div>
                    <span class="text-white font-semibold text-sm tracking-wider">DESA DIGITAL</span>
                </div>
            </div>

            <!-- Main Title with Interactive Element -->
            <h1 class="mt-4 text-3xl md:text-4xl lg:text-5xl font-bold text-white group" data-aos="fade-up">
                <span class="relative inline-block">
                Profil {{ $profilDesa->nama_desa ?? 'Desa' }}
                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-emerald-300 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></span>
                </span>
            </h1>

            <!-- Description with Fade In Effect -->
            <p class="mt-4 max-w-2xl mx-auto text-emerald-50 text-lg opacity-90" data-aos="fade-up" data-aos-delay="100">
                Mengenal sejarah, visi misi, dan struktur pemerintahan {{ $profilDesa->nama_desa ?? 'desa kami' }}
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

<div x-data="tabNavigation">

    <!-- Tabs Section - Modern Mobile Slider -->
    <section class="py-4 bg-white sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative">

                <!-- Tab Pills Container -->
                <div id="tabPills" class="flex items-center gap-3 overflow-x-auto pb-2 pt-1 scrollbar-hide scroll-smooth px-2">
                    <button
                        @click="activeTab = 'about'; $nextTick(() => scrollToActiveTab())"
                        id="tab-about"
                        :class="{
                            'bg-emerald-500 text-white ring-2 ring-emerald-500/20 shadow-lg shadow-emerald-500/20': activeTab === 'about',
                            'bg-gray-100/80 text-gray-600 hover:bg-gray-200 hover:text-gray-700 hover:shadow-md': activeTab !== 'about'
                        }"
                        class="shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full whitespace-nowrap transition-all duration-300"
                    >
                        <i class="fas fa-info-circle text-[12px]"></i>
                        <span class="hidden sm:inline">Informasi</span>Umum
                    </button>
                    <button
                        @click="activeTab = 'vision'; $nextTick(() => scrollToActiveTab())"
                        id="tab-vision"
                        :class="{
                            'bg-emerald-500 text-white ring-2 ring-emerald-500/20 shadow-lg shadow-emerald-500/20': activeTab === 'vision',
                            'bg-gray-100/80 text-gray-600 hover:bg-gray-200 hover:text-gray-700 hover:shadow-md': activeTab !== 'vision'
                        }"
                        class="shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full whitespace-nowrap transition-all duration-300"
                    >
                        <i class="fas fa-bullseye text-[12px]"></i>
                        Visi & Misi
                    </button>
                    <button
                        @click="activeTab = 'history'; $nextTick(() => scrollToActiveTab())"
                        id="tab-history"
                        :class="{
                            'bg-emerald-500 text-white ring-2 ring-emerald-500/20 shadow-lg shadow-emerald-500/20': activeTab === 'history',
                            'bg-gray-100/80 text-gray-600 hover:bg-gray-200 hover:text-gray-700 hover:shadow-md': activeTab !== 'history'
                        }"
                        class="shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full whitespace-nowrap transition-all duration-300"
                    >
                        <i class="fas fa-history text-[12px]"></i>
                        Sejarah
                    </button>
                    <button
                        @click="activeTab = 'structure'; $nextTick(() => scrollToActiveTab())"
                        id="tab-structure"
                        :class="{
                            'bg-emerald-500 text-white ring-2 ring-emerald-500/20 shadow-lg shadow-emerald-500/20': activeTab === 'structure',
                            'bg-gray-100/80 text-gray-600 hover:bg-gray-200 hover:text-gray-700 hover:shadow-md': activeTab !== 'structure'
                        }"
                        class="shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full whitespace-nowrap transition-all duration-300"
                    >
                        <i class="fas fa-sitemap text-[12px]"></i>
                        <span class="hidden sm:inline">Struktur Pemerintahan</span><span class="sm:hidden">Struktur</span>
                    </button>
                    <button
                        @click="activeTab = 'geography'; $nextTick(() => scrollToActiveTab())"
                        id="tab-geography"
                        :class="{
                            'bg-emerald-500 text-white ring-2 ring-emerald-500/20 shadow-lg shadow-emerald-500/20': activeTab === 'geography',
                            'bg-gray-100/80 text-gray-600 hover:bg-gray-200 hover:text-gray-700 hover:shadow-md': activeTab !== 'geography'
                        }"
                        class="shrink-0 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-full whitespace-nowrap transition-all duration-300"
                    >
                        <i class="fas fa-map-marked-alt text-[12px]"></i>
                        <span class="hidden sm:inline">Geografis Desa</span><span class="sm:hidden">Geografis</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Tab Contents -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Informasi Umum Tab - Clean Modern Design with Enhanced Icons -->
            <div x-show="activeTab === 'about'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-white">
                    <!-- Modern Header - Matched with UMKM Style -->
                    <div class="mb-5">
                        <!-- Title and Button in One Row -->
                        <div class="flex items-center justify-between mb-3">
                            <!-- Left: Title Badge with Icon -->
                            <div class="flex items-center">
                                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                                    <i class="fas fa-home-alt text-emerald-600 mr-1.5"></i>
                                    TENTANG DESA
                                </span>
                            </div>


                        </div>


                    </div>

                    <!-- Main Content with Clean Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-6">
                        <!-- Image Column with Slider Gallery & Header -->
                        <div class="lg:col-span-5 xl:col-span-4">
                            <!-- Village Identity at Top -->
                            <div class="bg-gray-50 shadow-sm rounded-lg p-5 mb-4 border border-gray-100 text-center">
                                <img src="{{ $profilDesa && $profilDesa->logo ? Storage::url($profilDesa->logo) : asset('images/default-logo.png') }}"
                                    alt="Logo Desa" class="h-20 w-20 object-contain mx-auto mb-3">
                                <h3 class="font-semibold text-xl text-gray-900 mb-1">
                                    {{ $profilDesa->nama_desa ?? 'Desa Digital' }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ $profilDesa->kecamatan ?? '' }}{{ !empty($profilDesa->kecamatan) ? ', ' : '' }}{{ $profilDesa->kabupaten ?? '' }}
                                </p>
                            </div>

                            <!-- Image Slider for Thumbnails -->
                            <div
                                x-data="imageSlider"
                                data-total-images="{{ $profilDesa && $profilDesa->thumbnails && is_array($profilDesa->thumbnails) ? count($profilDesa->thumbnails) : 1 }}"
                                class="relative mb-4 rounded-lg overflow-hidden shadow-sm">
                                <!-- Main Slider Container -->
                                <div class="relative aspect-w-16 aspect-h-9 bg-gray-100">
                                    <!-- Images -->
                                    @if($profilDesa && $profilDesa->thumbnails && is_array($profilDesa->thumbnails) && count($profilDesa->thumbnails) > 0)
                                        @foreach($profilDesa->thumbnails as $index => $thumbnail)
                                            <div x-show="currentIndex === {{ $index }}"
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 transform scale-95"
                                                 x-transition:enter-end="opacity-100 transform scale-100"
                                                 class="w-full h-full">
                                                <img src="{{ Storage::url($thumbnail) }}"
                                                     alt="{{ $profilDesa->nama_desa }} - Image {{ $index + 1 }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                @else
                                        <div class="w-full h-full">
                                            <img src="{{ asset('images/default-village.jpg') }}"
                                                 alt="Default Village Image"
                                                 class="w-full h-full object-cover">
                                        </div>
                                @endif
                        </div>

                                <!-- Controls - only show if multiple images exist -->
                                @if($profilDesa && $profilDesa->thumbnails && is_array($profilDesa->thumbnails) && count($profilDesa->thumbnails) > 1)
                                    <!-- Previous/Next Buttons -->
                                    <button @click.stop="prev()"
                                            class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-full bg-black/40 backdrop-blur-sm text-white hover:bg-black/60 transition-colors z-20">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <button @click.stop="next()"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-full bg-black/40 backdrop-blur-sm text-white hover:bg-black/60 transition-colors z-20">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>

                                    <!-- Indicator Dots -->
                                    <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-2 z-20">
                                        @foreach($profilDesa->thumbnails as $index => $thumbnail)
                                            <button @click.stop="currentIndex = {{ $index }}"
                                                    :class="{'bg-white': currentIndex === {{ $index }}, 'bg-white/50 hover:bg-white/70': currentIndex !== {{ $index }} }"
                                                    class="w-2.5 h-2.5 rounded-full transition-all"
                                                    aria-label="Show image {{ $index + 1 }}">
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Swipe Area for Touch Devices - Don't let it interfere with buttons -->
                                    <div class="absolute inset-0 z-10"
                                         x-on:touchstart="touchStart($event)"
                                         x-on:touchend="touchEnd($event)">
                                    </div>
                                @endif
                                </div>
                            </div>

                        <!-- Information Column -->
                        <div class="lg:col-span-7 xl:col-span-8 space-y-6">
                            <!-- General Information Card -->
                            <div class="bg-gray-50 shadow-sm rounded-lg overflow-hidden border border-gray-100">
                                <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                                    <i class="fas fa-file-alt text-emerald-500 mr-2"></i>
                                    <h3 class="font-medium text-gray-900">Informasi Desa</h3>
                        </div>
                                <div class="p-6">
                                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 flex items-center">
                                                <i class="fas fa-signature text-emerald-400 mr-1.5 text-xs"></i>
                                                Nama Desa
                                            </dt>
                                            <dd class="mt-1 text-gray-900">{{ $profilDesa->nama_desa ?? 'Belum diisi' }}</dd>
                    </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 flex items-center">
                                                <i class="fas fa-map text-emerald-400 mr-1.5 text-xs"></i>
                                                Kecamatan
                                            </dt>
                                            <dd class="mt-1 text-gray-900">{{ $profilDesa->kecamatan ?? 'Belum diisi' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 flex items-center">
                                                <i class="fas fa-city text-emerald-400 mr-1.5 text-xs"></i>
                                                Kabupaten
                                            </dt>
                                            <dd class="mt-1 text-gray-900">{{ $profilDesa->kabupaten ?? 'Belum diisi' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 flex items-center">
                                                <i class="fas fa-map-marked text-emerald-400 mr-1.5 text-xs"></i>
                                                Provinsi
                                            </dt>
                                            <dd class="mt-1 text-gray-900">{{ $profilDesa->provinsi ?? 'Belum diisi' }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 flex items-center">
                                                <i class="fas fa-mail-bulk text-emerald-400 mr-1.5 text-xs"></i>
                                                Kode Pos
                                            </dt>
                                            <dd class="mt-1 text-gray-900">{{ $profilDesa->kode_pos ?? 'Belum diisi' }}</dd>
                                        </div>
                                    </dl>
                </div>
            </div>

                            <!-- Contact Information Card -->
                            <div class="bg-gray-50 shadow-sm rounded-lg overflow-hidden border border-gray-100">
                                <div class="px-6 py-4 border-b border-gray-100 flex items-center">
                                    <i class="fas fa-address-card text-emerald-500 mr-2"></i>
                                    <h3 class="font-medium text-gray-900">Kontak & Alamat</h3>
                                </div>
                    <div class="p-6">
                                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 flex items-center">
                                                <i class="fas fa-phone-alt text-emerald-400 mr-1.5 text-xs"></i>
                                                Telepon
                                            </dt>
                                            <dd class="mt-1 text-gray-900">
                                                @if($profilDesa && $profilDesa->telepon)
                                                    <a href="tel:{{ $profilDesa->telepon }}" class="text-emerald-600 hover:underline flex items-center group">
                                                        <span>{{ $profilDesa->telepon }}</span>
                                                        <i class="fas fa-headset ml-1.5 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                                    </a>
                                                @else
                                                    <span>Belum diisi</span>
                                                @endif
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 flex items-center">
                                                <i class="fas fa-envelope text-emerald-400 mr-1.5 text-xs"></i>
                                                Email
                                            </dt>
                                            <dd class="mt-1 text-gray-900">
                                                @if($profilDesa && $profilDesa->email)
                                                    <a href="mailto:{{ $profilDesa->email }}" class="text-emerald-600 hover:underline flex items-center group">
                                                        <span>{{ $profilDesa->email }}</span>
                                                        <i class="fas fa-paper-plane ml-1.5 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                                    </a>
                                                @else
                                                    <span>Belum diisi</span>
                                                @endif
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500 flex items-center">
                                                <i class="fas fa-globe text-emerald-400 mr-1.5 text-xs"></i>
                                                Website
                                            </dt>
                                            <dd class="mt-1 text-gray-900">
                                                @if($profilDesa && $profilDesa->website)
                                                    <a href="{{ $profilDesa->website }}" target="_blank" class="text-emerald-600 hover:underline flex items-center group">
                                                        <span>{{ $profilDesa->website }}</span>
                                                        <i class="fas fa-external-link-alt ml-1.5 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                                    </a>
                                                @else
                                                    <span>Belum diisi</span>
                                                @endif
                                            </dd>
                                        </div>
                                        <div class="md:col-span-2">
                                            <dt class="text-sm font-medium text-gray-500 flex items-center">
                                                <i class="fas fa-map-marker-alt text-emerald-400 mr-1.5 text-xs"></i>
                                                Alamat Kantor Desa
                                            </dt>
                                            <dd class="mt-1 text-gray-900">{{ $profilDesa->alamat ?? 'Belum diisi' }}</dd>
                                    </div>
                                    </dl>
                            </div>
                        </div>


                            </div>
                        </div>
                    </div>
            </div>

            <!-- Visi & Misi Tab -->
            <div x-show="activeTab === 'vision'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-white">
                    <!-- Vision Section -->
                    <div class="mb-8">
                        <!-- Title Header (Matched with UMKM/Tentang Desa Style) -->
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center">
                                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                                    <i class="fas fa-eye text-emerald-600 mr-1.5"></i>
                                    VISI DESA
                                </span>
                </div>
            </div>

                        <!-- Vision Content with bg-gray-50 -->
                        <div class="bg-gray-50 shadow-sm rounded-lg overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="prose prose-emerald max-w-none text-gray-600">
                                {!! $profilDesa->visi ?? '<p class="italic text-gray-400">Visi desa belum diisi</p>' !!}
                        </div>
                    </div>
                </div>
            </div>

                    <!-- Mission Section -->
                    <div class="mb-8">
                        <!-- Title Header (Matched with UMKM/Tentang Desa Style) -->
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center">
                                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                                    <i class="fas fa-bullseye text-emerald-600 mr-1.5"></i>
                                    MISI DESA
                                </span>
                            </div>
                        </div>

                        <!-- Mission Content with bg-gray-50 -->
                        <div class="bg-gray-50 shadow-sm rounded-lg overflow-hidden border border-gray-100">
                            <div class="p-6">
                            <div class="prose prose-emerald max-w-none text-gray-600">
                                {!! $profilDesa->misi ?? '<p class="italic text-gray-400">Misi desa belum diisi</p>' !!}
                                        </div>
                                    </div>
                                </div>
                                        </div>
                                    </div>
            </div>

            <!-- Sejarah Desa Tab -->
            <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-white">
                    <!-- Title Header (Matched with UMKM/Tentang Desa Style) -->
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center">
                            <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                            <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                                <i class="fas fa-history text-emerald-600 mr-1.5"></i>
                                SEJARAH DESA
                            </span>
                                        </div>
                                    </div>

                    <!-- History Content with bg-gray-50 -->
                    <div class="bg-gray-50 shadow-sm rounded-lg overflow-hidden border border-gray-100" data-aos="fade-up">
                    <div class="p-6">
                        <div class="prose prose-emerald max-w-none text-gray-600">
                            {!! $profilDesa->sejarah ?? '<p class="italic text-gray-400">Sejarah desa belum diisi</p>' !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

            <!-- Struktur Pemerintahan Tab -->
            <div x-show="activeTab === 'structure'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-white">
                    <!-- Title Header (Using UMKM/Tentang Desa Style) -->
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center">
                            <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                            <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                                <i class="fas fa-sitemap text-emerald-600 mr-1.5"></i>
                                BAGAN STRUKTUR APARAT DESA
                            </span>
                        </div>
                    </div>

                        <!-- Bagan Struktur -->
                    <div class="mb-8">
                            @if($strukturPemerintahan && $strukturPemerintahan->bagan_struktur)
                        <div class="bg-gray-50 p-3 rounded-lg shadow-sm border border-gray-100">
                                <img src="{{ Storage::url($strukturPemerintahan->bagan_struktur) }}"
                                     alt="Bagan Struktur Organisasi Desa"
                                     class="w-full h-auto object-contain rounded-lg">
                            </div>
                            @else
                            <div class="bg-gray-50 border border-gray-200 p-8 rounded-lg text-center text-gray-400 italic">
                            <i class="fas fa-sitemap text-gray-300 text-5xl mb-3"></i>
                            <p>Bagan struktur organisasi belum tersedia</p>
                            </div>
                            @endif
                        </div>

                    <!-- Aparat Desa Section without search functionality -->
                        @if($strukturPemerintahan && $strukturPemerintahan->aparatDesa->count() > 0)
                        <div class="mt-12" x-data="{ jabatanAktif: 'semua' }">
                        <!-- Header without search input -->
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center">
                                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                                    <i class="fas fa-users text-emerald-600 mr-1.5"></i>
                                    DAFTAR APARAT DESA
                                </span>
                            </div>
                        </div>

                        <!-- Filter Kategori Jabatan - keep this part -->
                        <div class="relative mb-6" x-data="{
                            isDragging: false,
                            startX: 0,
                            scrollLeft: 0,
                            handleMouseDown(e) {
                                const slider = $refs.slider;
                                this.isDragging = true;
                                this.startX = e.pageX - slider.offsetLeft;
                                this.scrollLeft = slider.scrollLeft;
                                slider.classList.add('cursor-grabbing');
                            },
                            handleMouseMove(e) {
                                if(!this.isDragging) return;
                                e.preventDefault();
                                const slider = $refs.slider;
                                const x = e.pageX - slider.offsetLeft;
                                const walk = (x - this.startX) * 2;
                                slider.scrollLeft = this.scrollLeft - walk;
                            },
                            handleMouseUp() {
                                this.isDragging = false;
                                $refs.slider.classList.remove('cursor-grabbing');
                            }
                        }">
                            <div
                                x-ref="slider"
                                class="overflow-x-auto py-2 scrollbar-hide cursor-grab active:cursor-grabbing"
                                style="-webkit-overflow-scrolling: touch;"
                                @mousedown="handleMouseDown"
                                @mousemove="handleMouseMove"
                                @mouseup="handleMouseUp"
                                @mouseleave="handleMouseUp"
                            >
                                <div class="inline-flex gap-2 min-w-full px-1 py-1">
                                <button @click="jabatanAktif = 'semua'"
                                        :class="{ 'bg-emerald-600 text-white shadow-md': jabatanAktif === 'semua', 'bg-white text-gray-700 hover:bg-gray-100': jabatanAktif !== 'semua' }"
                                        class="shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200">
                                        <i class="fas fa-users mr-1.5"></i>
                                    Semua
                                </button>

                                @php
                                    $jabatanUnik = $strukturPemerintahan->aparatDesa->pluck('jabatan')->unique();
                                @endphp

                                @foreach($jabatanUnik as $jabatan)
                                <button @click="jabatanAktif = '{{ $jabatan }}'"
                                        :class="{ 'bg-emerald-600 text-white shadow-md': jabatanAktif === '{{ $jabatan }}', 'bg-white text-gray-700 hover:bg-gray-100': jabatanAktif !== '{{ $jabatan }}' }"
                                        class="shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200">
                                    {{ $jabatan }}
                                </button>
                                @endforeach
                                </div>
                            </div>
                            </div>

                        <!-- Aparat Grid without search filter -->
                        <div class="relative">
                            <div
                                x-ref="aparatContainer"
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5"
                                x-data="{ hoverAparat: null }"
                            >
                                @foreach($strukturPemerintahan->aparatDesa->sortBy('urutan') as $index => $aparat)
                                <div
                                    x-show="jabatanAktif === 'semua' || jabatanAktif === '{{ $aparat->jabatan }}'"
                                    @mouseenter="hoverAparat = {{ $index }}"
                                    @mouseleave="hoverAparat = null"
                                    class="bg-gray-50 rounded-xl overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-md group hover:-translate-y-1 aparat-card"
                                >
                                    <div class="relative aspect-w-1 aspect-h-1 bg-gray-200">
                                        @if($aparat->foto)
                                        <img src="{{ Storage::url($aparat->foto) }}"
                                             alt="{{ $aparat->nama }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div
                                            :class="{'opacity-70': hoverAparat === {{ $index }}, 'opacity-0': hoverAparat !== {{ $index }}}"
                                            class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent transition-opacity duration-300">
                                        </div>
                                        @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-user text-gray-300 text-6xl"></i>
                                        </div>
                                        @endif

                                        <div
                                            :class="{'bottom-0 opacity-100': hoverAparat === {{ $index }}, 'bottom-[-20px] opacity-0': hoverAparat !== {{ $index }}}"
                                            class="absolute left-0 right-0 p-3 transition-all duration-300">
                                            <span class="inline-block bg-emerald-500 text-white text-xs font-medium px-2.5 py-1 rounded-full">
                                                {{ $aparat->jabatan }}
                                            </span>
                                    </div>
                                    </div>

                                    <div class="p-4">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-1 group-hover:text-emerald-600 transition-colors">{{ $aparat->nama }}</h4>
                                        <p class="text-emerald-600 font-medium mb-3 text-sm">{{ $aparat->jabatan }}</p>

                                        <div class="space-y-2 text-sm text-gray-600">
                                            @if($aparat->pendidikan)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <i class="fas fa-graduation-cap text-emerald-400 mr-2"></i>
                                                </div>
                                                <div>{{ $aparat->pendidikan }}</div>
                                            </div>
                                            @endif

                                            @if($aparat->periode_jabatan)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <i class="fas fa-calendar text-emerald-400 mr-2"></i>
                                                </div>
                                                <div>{{ $aparat->periode_jabatan }}</div>
                                            </div>
                                            @endif

                                            @if($aparat->kontak)
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mt-0.5">
                                                    <i class="fas fa-phone text-emerald-400 mr-2"></i>
                                                </div>
                                                <div>
                                                    <a href="tel:{{ $aparat->kontak }}" class="hover:text-emerald-600 transition-colors">
                                                        {{ $aparat->kontak }}
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            </div>
                        </div>
                        @else
                    <!-- "No data" message when no aparat data exists -->
                    <div class="bg-gray-50 p-8 rounded-lg text-center mt-8 border border-gray-100">
                        <i class="fas fa-users text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">Data aparat desa belum tersedia</p>
                        </div>
                        @endif
                </div>
            </div>

            <!-- Geografis Desa Tab - Enhanced Modern Design -->
            <div x-show="activeTab === 'geography'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="bg-white overflow-hidden">
                    <!-- Modern Header with Subtitle -->
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                                <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                                    <i class="fas fa-map-marked-alt text-emerald-600 mr-1.5"></i>
                                    GEOGRAFIS DESA
                                </span>
                            </div>
                        </div>
                        <p class="text-gray-600 ml-3">Informasi mengenai lokasi dan kondisi geografis {{ $profilDesa->nama_desa ?? 'desa' }}</p>
                    </div>

                        @if($profilDesa->batasWilayahPotensi)
                        <!-- Map and Info Section - Side by Side on Desktop -->
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
                            <!-- Interactive Map (Larger on Desktop) -->
                            <div class="lg:col-span-7 order-2 lg:order-1">
                                <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm border border-gray-100">
                                    <div class="aspect-w-16 aspect-h-9 md:aspect-h-7">
                                        @php
                                            $searchQuery = urlencode(($profilDesa->nama_desa ?? '') . ' ' .
                                                    ($profilDesa->kecamatan ?? '') . ' ' .
                                                    ($profilDesa->kabupaten ?? '') . ' ' .
                                                    ($profilDesa->provinsi ?? ''));

                                            if (empty(trim(str_replace(' ', '', $searchQuery)))) {
                                                $searchQuery = 'Indonesia';
                                            }
                                        @endphp
                                        <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.4157608731784!2d106.8532!3d-6.6003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5d2f9f02917%3A0xfa0651b318f198a2!2s{{ $searchQuery }}!5e0!3m2!1sen!2sid!4v1690447353417!5m2!1sen!2sid"
                                            width="100%"
                                            height="100%"
                                            style="border:0;"
                                            allowfullscreen=""
                                            loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade">
                                        </iframe>
                                    </div>
                                </div>
                            </div>

                            <!-- Information Cards with Tabs - Using Only Available Data Fields -->
                            <div class="lg:col-span-5 order-1 lg:order-2">
                                <div class="bg-gray-50 rounded-lg shadow-sm border border-gray-100 overflow-hidden" x-data="{ activeInfoTab: 'wilayah' }">
                                    <!-- Tab Navigation -->
                                    <div class="flex border-b border-gray-200">
                                        <button
                                            @click="activeInfoTab = 'wilayah'"
                                            :class="{'bg-white text-emerald-600 border-b-2 border-emerald-500': activeInfoTab === 'wilayah', 'text-gray-500 hover:text-gray-700': activeInfoTab !== 'wilayah'}"
                                            class="flex-1 py-3 px-4 text-sm font-medium text-center transition-colors duration-200 focus:outline-none"
                                        >
                                            <i class="fas fa-ruler-combined mr-1.5"></i>
                                            Informasi Wilayah
                                        </button>
                                        <button
                                            @click="activeInfoTab = 'batas'"
                                            :class="{'bg-white text-emerald-600 border-b-2 border-emerald-500': activeInfoTab === 'batas', 'text-gray-500 hover:text-gray-700': activeInfoTab !== 'batas'}"
                                            class="flex-1 py-3 px-4 text-sm font-medium text-center transition-colors duration-200 focus:outline-none"
                                        >
                                            <i class="fas fa-compass mr-1.5"></i>
                                            Batas Wilayah
                                        </button>
                                    </div>

                                    <!-- Area Information Tab - Only Using Available Data -->
                                    <div x-show="activeInfoTab === 'wilayah'" class="p-5">
                                        <div class="flex items-center justify-center">
                                            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-500 mb-4">
                                                <i class="fas fa-ruler-combined text-xl"></i>
                                            </div>
                                        </div>

                                        <h3 class="text-center text-lg font-medium text-gray-800 mb-4">Luas Wilayah</h3>

                                        <div class="text-center">
                                            <div class="text-2xl font-bold text-emerald-600 mb-1">
                                                {{ number_format($profilDesa->batasWilayahPotensi->luas_wilayah ?? 0) }} m²
                                            </div>
                                            <div class="text-gray-500">
                                                ({{ number_format(($profilDesa->batasWilayahPotensi->luas_wilayah ?? 0) / 10000, 2) }} hektar)
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Boundaries Tab - Layout Alternatif -->
                                    <div x-show="activeInfoTab === 'batas'" class="p-5">
                                        <!-- Lingkaran Kompas Style Modern -->
                                        <div class="relative w-full max-w-[300px] mx-auto mb-6" x-data="{ focus: null }">
                                            <!-- Background lingkaran pusat -->
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <div class="w-24 h-24 bg-emerald-50 rounded-full border border-emerald-100 flex items-center justify-center z-10">
                                                    <div class="text-center">
                                                        <i class="fas fa-home-alt text-emerald-600 text-xl mb-1"></i>
                                                        <p class="text-xs font-medium text-emerald-800 line-clamp-1">{{ $profilDesa->nama_desa ?? 'Desa' }}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Garis dan label arah mata angin -->
                                            <svg class="w-full h-[300px]" viewBox="0 0 200 200">
                                                <g transform="translate(100, 100)">
                                                    <!-- Garis kompas -->
                                                    <line x1="0" y1="-80" x2="0" y2="-40" stroke="#10b981" stroke-width="2" />
                                                    <line x1="0" y1="40" x2="0" y2="80" stroke="#10b981" stroke-width="2" />
                                                    <line x1="-80" y1="0" x2="-40" y2="0" stroke="#10b981" stroke-width="2" />
                                                    <line x1="40" y1="0" x2="80" y2="0" stroke="#10b981" stroke-width="2" />

                                                    <!-- Lingkaran di ujung tiap arah -->
                                                    <circle cx="0" cy="-80" r="12" fill="white" stroke="#d1d5db" />
                                                    <circle cx="0" cy="80" r="12" fill="white" stroke="#d1d5db" />
                                                    <circle cx="-80" cy="0" r="12" fill="white" stroke="#d1d5db" />
                                                    <circle cx="80" cy="0" r="12" fill="white" stroke="#d1d5db" />

                                                    <!-- Label arah mata angin -->
                                                    <text x="0" y="-80" text-anchor="middle" dominant-baseline="middle" class="text-xs font-bold" fill="#047857">U</text>
                                                    <text x="0" y="80" text-anchor="middle" dominant-baseline="middle" class="text-xs font-bold" fill="#047857">S</text>
                                                    <text x="-80" y="0" text-anchor="middle" dominant-baseline="middle" class="text-xs font-bold" fill="#047857">B</text>
                                                    <text x="80" y="0" text-anchor="middle" dominant-baseline="middle" class="text-xs font-bold" fill="#047857">T</text>
                                                </g>
                                            </svg>
                                        </div>

                                        <!-- Tabel Batas Wilayah -->
                                        <div class="max-w-md mx-auto mt-6">
                                            <table class="w-full border-collapse">
                                                <thead class="bg-emerald-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-emerald-800 border border-gray-200 rounded-tl-lg">Arah</th>
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-emerald-800 border border-gray-200 rounded-tr-lg">Berbatasan Dengan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 border border-gray-200 font-medium text-xs flex items-center">
                                                            <i class="fas fa-arrow-up text-emerald-500 mr-2"></i>Utara
                                                        </td>
                                                        <td class="px-4 py-3 border border-gray-200 text-sm">
                                                            {{ $profilDesa->batasWilayahPotensi->batas_utara ?? 'Belum diisi' }}
                                                        </td>
                                                    </tr>
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 border border-gray-200 font-medium text-xs flex items-center">
                                                            <i class="fas fa-arrow-right text-emerald-500 mr-2"></i>Timur
                                                        </td>
                                                        <td class="px-4 py-3 border border-gray-200 text-sm">
                                                            {{ $profilDesa->batasWilayahPotensi->batas_timur ?? 'Belum diisi' }}
                                                        </td>
                                                    </tr>
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 border border-gray-200 font-medium text-xs flex items-center">
                                                            <i class="fas fa-arrow-down text-emerald-500 mr-2"></i>Selatan
                                                        </td>
                                                        <td class="px-4 py-3 border border-gray-200 text-sm">
                                                            {{ $profilDesa->batasWilayahPotensi->batas_selatan ?? 'Belum diisi' }}
                                                        </td>
                                                    </tr>
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-3 border border-gray-200 font-medium text-xs flex items-center rounded-bl-lg">
                                                            <i class="fas fa-arrow-left text-emerald-500 mr-2"></i>Barat
                                                        </td>
                                                        <td class="px-4 py-3 border border-gray-200 text-sm rounded-br-lg">
                                                            {{ $profilDesa->batasWilayahPotensi->batas_barat ?? 'Belum diisi' }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            @if($profilDesa->batasWilayahPotensi->keterangan_batas)
                                            <div class="mt-4 bg-gray-50 p-3 rounded-md text-sm text-gray-600 border border-gray-100">
                                                <p class="font-medium text-gray-700 mb-1">Keterangan Tambahan:</p>
                                                <p>{{ $profilDesa->batasWilayahPotensi->keterangan_batas }}</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Potensi Desa Section - Enhanced Card Design with Fixed Draggable Categories -->
                        <div class="mt-12 mb-10" x-data="{ activeKategori: 'semua' }">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                                    <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                                        <i class="fas fa-seedling text-emerald-600 mr-1.5"></i>
                                        POTENSI DESA
                                    </span>
                                </div>
                            </div>

                            @if($profilDesa->batasWilayahPotensi->potensi_desa && is_array($profilDesa->batasWilayahPotensi->potensi_desa) && count($profilDesa->batasWilayahPotensi->potensi_desa) > 0)
                                <!-- Draggable Filter Categories - Exact Same Code as Aparat Desa -->
                                <div class="relative mb-6" x-data="{
                                    isDragging: false,
                                    startX: 0,
                                    scrollLeft: 0,
                                    handleMouseDown(e) {
                                        const slider = $refs.potensiSlider;
                                        this.isDragging = true;
                                        this.startX = e.pageX - slider.offsetLeft;
                                        this.scrollLeft = slider.scrollLeft;
                                        slider.classList.add('cursor-grabbing');
                                    },
                                    handleMouseMove(e) {
                                        if(!this.isDragging) return;
                                        e.preventDefault();
                                        const slider = $refs.potensiSlider;
                                        const x = e.pageX - slider.offsetLeft;
                                        const walk = (x - this.startX) * 2;
                                        slider.scrollLeft = this.scrollLeft - walk;
                                    },
                                    handleMouseUp() {
                                        this.isDragging = false;
                                        $refs.potensiSlider.classList.remove('cursor-grabbing');
                                    }
                                }">


                                    <div
                                        x-ref="potensiSlider"
                                        class="overflow-x-auto py-2 scrollbar-hide cursor-grab active:cursor-grabbing"
                                        style="-webkit-overflow-scrolling: touch;"
                                        @mousedown="handleMouseDown"
                                        @mousemove="handleMouseMove"
                                        @mouseup="handleMouseUp"
                                        @mouseleave="handleMouseUp"
                                    >
                                        <div class="inline-flex gap-2 min-w-full px-1 py-1">
                                    <button @click="activeKategori = 'semua'"
                                                :class="{ 'bg-emerald-600 text-white shadow-md': activeKategori === 'semua', 'bg-white text-gray-700 hover:bg-gray-100': activeKategori !== 'semua' }"
                                                class="shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center">
                                                <i class="fas fa-th-large mr-1.5 text-xs"></i>
                                        Semua Potensi
                                    </button>

                                    @php
                                        $kategoriList = [
                                            'sda' => 'Sumber Daya Alam',
                                            'pertanian' => 'Pertanian',
                                            'peternakan' => 'Peternakan',
                                            'pariwisata' => 'Pariwisata',
                                            'industri' => 'Industri/UMKM',
                                            'budaya' => 'Budaya/Kesenian',
                                            'lingkungan' => 'Lingkungan',
                                            'pendidikan' => 'Pendidikan',
                                            'kesehatan' => 'Kesehatan',
                                            'lainnya' => 'Lainnya'
                                        ];

                                                $iconMap = [
                                                    'sda' => 'fa-water',
                                                    'pertanian' => 'fa-seedling',
                                                    'peternakan' => 'fa-cow',
                                                    'pariwisata' => 'fa-umbrella-beach',
                                                    'industri' => 'fa-industry',
                                                    'budaya' => 'fa-masks-theater',
                                                    'lingkungan' => 'fa-leaf',
                                                    'pendidikan' => 'fa-school',
                                                    'kesehatan' => 'fa-hospital',
                                                    'lainnya' => 'fa-shapes'
                                        ];

                                        $kategoriYangAda = [];
                                        foreach ($profilDesa->batasWilayahPotensi->potensi_desa as $potensi) {
                                            if (isset($potensi['kategori']) && !in_array($potensi['kategori'], $kategoriYangAda)) {
                                                $kategoriYangAda[] = $potensi['kategori'];
                                            }
                                        }
                                    @endphp

                                    @foreach($kategoriYangAda as $kodeKategori)
                                        <button @click="activeKategori = '{{ $kodeKategori }}'"
                                                    :class="{ 'bg-emerald-600 text-white shadow-md': activeKategori === '{{ $kodeKategori }}', 'bg-white text-gray-700 hover:bg-gray-100': activeKategori !== '{{ $kodeKategori }}' }"
                                                    class="shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-colors border border-gray-200 flex items-center">
                                                    <i class="fas {{ $iconMap[$kodeKategori] ?? 'fa-shapes' }} mr-1.5 text-xs"></i>
                                            {{ $kategoriList[$kodeKategori] ?? ucfirst($kodeKategori) }}
                                        </button>
                                    @endforeach
                                        </div>
                                </div>



                                </div>

                                <!-- Completely Redesigned Potensi Cards - Clean Modern Style -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                    @foreach($profilDesa->batasWilayahPotensi->potensi_desa as $potensi)
                                        <div
                                            x-show="activeKategori === 'semua' || activeKategori === '{{ $potensi['kategori'] ?? 'lainnya' }}'"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 transform scale-95"
                                            x-transition:enter-end="opacity-100 transform scale-100"
                                            class="bg-gray-50 rounded-lg overflow-hidden shadow-sm border border-gray-100 group hover:shadow-md transition-all duration-300"
                                        >
                                            @php
                                                $iconClass = $iconMap[$potensi['kategori'] ?? 'lainnya'] ?? 'fa-shapes';
                                                $categoryName = $kategoriList[$potensi['kategori'] ?? 'lainnya'] ?? 'Lainnya';
                                                $accentColor = match($potensi['kategori'] ?? 'lainnya') {
                                                    'sda' => 'border-blue-500 text-blue-600',
                                                    'pertanian' => 'border-green-500 text-green-600',
                                                    'peternakan' => 'border-amber-500 text-amber-600',
                                                    'pariwisata' => 'border-purple-500 text-purple-600',
                                                    'industri' => 'border-slate-500 text-slate-600',
                                                    'budaya' => 'border-rose-500 text-rose-600',
                                                    'lingkungan' => 'border-emerald-500 text-emerald-600',
                                                    'pendidikan' => 'border-sky-500 text-sky-600',
                                                    'kesehatan' => 'border-red-500 text-red-600',
                                                    default => 'border-gray-500 text-gray-600'
                                                    };
                                                @endphp

                                            <!-- Simple Clean Header with Left Border Accent -->
                                            <div class="p-4 border-l-4 {{ $accentColor }} bg-white flex items-center">
                                                <i class="fas {{ $iconClass }} mr-3 text-lg"></i>
                                                <h4 class="text-lg font-medium text-gray-800 line-clamp-1">{{ $potensi['nama'] ?? 'Potensi Desa' }}</h4>
                                                </div>

                                            <!-- Content Area -->
                                            <div class="p-4">
                                                <!-- Category Badge -->
                                                <div class="mb-3">
                                                    <span class="text-xs font-medium bg-white px-2.5 py-1 rounded-full border border-gray-200 {{ $accentColor }}">
                                                        {{ $categoryName }}
                                                    </span>
                                            </div>

                                                <!-- Description -->
                                                @if(isset($potensi['deskripsi']) && !empty($potensi['deskripsi']))
                                                    <p class="text-gray-600 text-sm mb-3 line-clamp-3">{{ $potensi['deskripsi'] }}</p>
                                            @endif

                                                <!-- Location with Icon - Bottom of Card -->
                                            @if(isset($potensi['lokasi']) && !empty($potensi['lokasi']))
                                                    <div class="flex items-start text-xs text-gray-500 pt-2 mt-2 border-t border-gray-100">
                                                        <i class="fas fa-map-marker-alt mt-0.5 mr-2"></i>
                                                        <span class="flex-1">{{ $potensi['lokasi'] }}</span>
                                                    </div>
                                            @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Empty State - Simple, Clean Design -->
                                <div
                                    x-show="!Array.from(document.querySelectorAll('[x-show*=\'activeKategori\']')).some(el => window.getComputedStyle(el).display !== 'none')"
                                    x-cloak
                                    class="bg-gray-50 p-6 rounded-lg text-center border border-gray-100 mt-4"
                                >
                                    <div class="inline-flex items-center justify-center p-3 bg-gray-100 rounded-full mb-3">
                                        <i class="fas fa-seedling text-gray-300"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm">Tidak ada potensi desa dalam kategori ini</p>
                                </div>
                            @else
                                <div class="bg-gray-50 p-8 rounded-lg text-center border border-gray-100">
                                    <i class="fas fa-seedling text-gray-300 text-4xl mb-3"></i>
                                    <p class="text-gray-500">Data potensi desa belum tersedia</p>
                                </div>
                            @endif
                        </div>
                        @else
                        <div class="bg-gray-50 p-8 rounded-lg text-center border border-gray-100">
                            <i class="fas fa-map-marked-alt text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500">Data geografis desa belum tersedia</p>
                        </div>
                        @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection