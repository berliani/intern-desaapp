@extends('layouts.front')

@section('title', $umkm->nama_usaha . ' - UMKM ' . ($profilDesa->nama_desa ?? 'Desa'))

@section('meta')
<meta name="description" content="{{ \Illuminate\Support\Str::limit($umkm->deskripsi, 160) }}">
<meta property="og:title" content="{{ $umkm->nama_usaha }} - UMKM {{ $profilDesa->nama_desa ?? 'Desa' }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit($umkm->deskripsi, 160) }}">
@if($umkm->foto_usaha)
<meta property="og:image" content="{{ Storage::url($umkm->foto_usaha) }}">
@endif
<meta property="og:type" content="business.business">
@endsection

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
                    <a href="{{ route('umkm') }}" class="flex items-center text-gray-500 hover:text-emerald-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        UMKM
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-emerald-600 font-medium">{{ $umkm->nama_usaha }}</span>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
<!-- Main Content Area -->
<div class="bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Two-column layout: Image + Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Left Column: Hero Image -->
            <div class="lg:col-span-2">
                <div class="w-full h-full relative rounded-xl overflow-hidden">
                    @if($umkm->foto_usaha)
                        <div class="w-full h-full aspect-[16/9] overflow-hidden">
                            <img src="{{ Storage::url($umkm->foto_usaha) }}"
                                 alt="{{ $umkm->nama_usaha }}"
                                 class="w-full h-full object-cover object-center">
                        </div>
                    @else
                        @php
                            $kategoriIcons = [
                                'Kuliner' => 'fas fa-utensils',
                                'Kerajinan' => 'fas fa-hands',
                                'Fashion' => 'fas fa-tshirt',
                                'Pertanian' => 'fas fa-leaf',
                                'Jasa' => 'fas fa-concierge-bell',
                                'Lainnya' => 'fas fa-store',
                            ];
                        @endphp
                        <div class="w-full h-full aspect-[16/9] bg-gradient-to-r from-emerald-500/20 to-emerald-600/20 flex items-center justify-center">
                            <span class="text-6xl text-gray-300 bg-white h-32 w-32 flex items-center justify-center rounded-full border-4 border-emerald-100">
                                <i class="{{ $kategoriIcons[$umkm->kategori] ?? 'fas fa-store' }}"></i>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm h-full flex flex-col">
                    <!-- Category and Verification -->
                    <div class="p-5 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-t-xl border border-emerald-100">
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

                        <div class="flex justify-between items-center mb-4">
                            <!-- Category Badge -->
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                                    bg-{{ $kategoriColors[$umkm->kategori] ?? 'emerald' }}-100
                                    text-{{ $kategoriColors[$umkm->kategori] ?? 'emerald' }}-800
                                    border border-{{ $kategoriColors[$umkm->kategori] ?? 'emerald' }}-200">
                                <i class="{{ $kategoriIcons[$umkm->kategori] ?? 'fas fa-store' }} mr-1.5"></i>
                                {{ $umkm->kategori }}
                            </span>

                            <!-- Verification Badge -->
                            @if($umkm->is_verified)
                            <div class="flex items-center text-xs font-medium px-2.5 py-1.5 rounded-md border border-blue-100 bg-blue-50 text-blue-700">
                                <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Terverifikasi
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-5 flex-grow border-x border-b border-gray-100 rounded-b-xl">
                        <!-- Owner Info -->
                        @if($umkm->penduduk)
                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if($umkm->penduduk->user && $umkm->penduduk->user->profile_photo_path)
                                        <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-white shadow-sm">
                                            <img src="{{ Storage::url($umkm->penduduk->user->profile_photo_path) }}"
                                                 alt="{{ $umkm->penduduk->nama }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <span class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center text-emerald-500 shadow-sm border border-gray-200">
                                            <i class="fas fa-user-alt text-xl"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <p class="text-base font-bold text-gray-900">
                                        {{ $umkm->penduduk->nama }}
                                    </p>
                                    <p class="text-sm text-gray-500 flex items-center">
                                        <i class="fas fa-user-shield text-emerald-500 mr-1.5 text-xs"></i>
                                        Pemilik UMKM
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- WhatsApp Contact -->
                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <a href="https://wa.me/{{ $umkm->kontak_whatsapp }}"
                               target="_blank"
                               class="w-full flex items-center justify-center gap-2 px-4 py-3.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                                <i class="fab fa-whatsapp text-xl"></i>
                                <span class="font-medium">Hubungi via WhatsApp</span>
                            </a>
                        </div>

                        <!-- Share Section - Modern Design -->
                        <div class="relative z-50">
                            <h3 class="text-sm uppercase tracking-wide text-gray-500 mb-3 flex items-center">
                                <i class="fas fa-share-alt text-emerald-500 mr-2"></i>
                                Bagikan UMKM
                            </h3>

                            <div class="flex flex-wrap gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('umkm.show', $umkm->id)) }}"
                                   target="_blank" rel="noopener"
                                   class="w-10 h-10 flex items-center justify-center bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors hover:shadow-md hover:scale-110 transition-transform duration-300">
                                    <i class="fab fa-facebook-f"></i>
                                </a>

                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('umkm.show', $umkm->id)) }}&text={{ urlencode($umkm->nama_usaha . ' - UMKM ' . ($profilDesa->nama_desa ?? 'Desa')) }}"
                                   target="_blank" rel="noopener"
                                   class="w-10 h-10 flex items-center justify-center bg-sky-500 text-white rounded-full hover:bg-sky-600 transition-colors hover:shadow-md hover:scale-110 transition-transform duration-300">
                                    <i class="fab fa-twitter"></i>
                                </a>

                                <a href="https://wa.me/?text={{ urlencode($umkm->nama_usaha . ' - UMKM ' . ($profilDesa->nama_desa ?? 'Desa') . ' ' . route('umkm.show', $umkm->id)) }}"
                                   target="_blank" rel="noopener"
                                   class="w-10 h-10 flex items-center justify-center bg-green-500 text-white rounded-full hover:bg-green-600 transition-colors hover:shadow-md hover:scale-110 transition-transform duration-300">
                                    <i class="fab fa-whatsapp"></i>
                                </a>

                                <a href="https://t.me/share/url?url={{ urlencode(route('umkm.show', $umkm->id)) }}&text={{ urlencode($umkm->nama_usaha . ' - UMKM ' . ($profilDesa->nama_desa ?? 'Desa')) }}"
                                   target="_blank" rel="noopener"
                                   class="w-10 h-10 flex items-center justify-center bg-blue-400 text-white rounded-full hover:bg-blue-500 transition-colors hover:shadow-md hover:scale-110 transition-transform duration-300">
                                    <i class="fab fa-telegram"></i>
                                </a>

                                <button onclick="copyToClipboard('{{ route('umkm.show', $umkm->id) }}')"
                                       class="w-10 h-10 flex items-center justify-center bg-gray-500 text-white rounded-full hover:bg-gray-600 transition-colors hover:shadow-md hover:scale-110 transition-transform duration-300">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Details Section - Full Width -->
        <div class="bg-gray-50 rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <!-- Business Name and About Section -->
            <div class="mb-3">
                <h1 class="text-2xl font-bold text-gray-900 ">{{ $umkm->nama_usaha }}</h1>

                @if($umkm->deskripsi)
                <div class="prose max-w-none text-gray-600 ">
                    <p class="leading-relaxed">{{ $umkm->deskripsi }}</p>
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg p-5 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <span class="flex-shrink-0 w-12 h-12 rounded-full bg-gray-50 text-emerald-500 flex items-center justify-center mr-4 border border-gray-200">
                            <i class="fas fa-tag text-xl"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-2">Produk & Layanan</h3>
                            <p class="text-gray-700">{{ $umkm->produk }}</p>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="bg-white rounded-lg p-5 border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex">
                        <span class="flex-shrink-0 w-12 h-12 rounded-full bg-gray-50 text-blue-500 flex items-center justify-center mr-4 border border-gray-200">
                            <i class="fas fa-map-marker-alt text-xl"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-2">Lokasi Usaha</h3>
                            <p class="text-gray-700">{{ $umkm->lokasi ?: 'Lokasi belum diisi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- UMKM Terkait -->
@if(count($umkmLainnya) > 0)
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Modern Header - Matched with Home Style -->
        <div class="mb-6">
            <!-- Title and Button in One Row -->
            <div class="flex items-center justify-between mb-3">
                <!-- Left: Title Badge -->
                <div class="flex items-center">
                    <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                    <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        UMKM TERKAIT
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
                Produk dan layanan serupa yang mungkin anda minati
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($umkmLainnya as $item)
                <div class="group bg-white rounded-2xl shadow-sm overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-lg relative border border-gray-100" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
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
                    <div class="p-6 flex flex-col flex-grow relative bg-white">
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
                            @if($item->is_verified)
                            <div class="flex items-center text-xs font-medium px-2.5 py-1.5 rounded-md border border-blue-100 bg-blue-50 text-blue-700">
                                <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Terverifikasi
                            </div>
                            @endif
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
                                {{ $item->produk }}
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
    </div>
</section>
@endif
@endsection