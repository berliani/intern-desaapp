@extends('layouts.front')

@section('title', $layanan->nama_layanan . ' - Layanan ' . ($profilDesa->nama_desa ?? 'Desa'))

@section('meta')
<meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($layanan->deskripsi), 160) }}">
<meta property="og:title" content="{{ $layanan->nama_layanan }} - Layanan {{ $profilDesa->nama_desa ?? 'Desa' }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($layanan->deskripsi), 160) }}">
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
                    <a href="{{ route('layanan') }}" class="flex items-center text-gray-500 hover:text-emerald-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Layanan
                    </a>
        </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-emerald-600 font-medium line-clamp-1 max-w-[280px] sm:max-w-md">
                        {{ $layanan->nama_layanan }}
                    </span>
        </li>
    </ol>
</nav>
    </div>
</div>
@endsection

@section('content')
<!-- Main Content -->
<div class="bg-white py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
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

                $kategoriColor = $kategoriColors[$layanan->kategori] ?? 'emerald';
            @endphp

        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
                <h1 class="text-2xl font-bold text-gray-900">{{ $layanan->nama_layanan }}</h1>

                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1.5 text-sm font-semibold rounded-full
                                    bg-{{ $kategoriColor }}-100
                                    text-{{ $kategoriColor }}-800
                                    border border-{{ $kategoriColor }}-200">
                            <i class="{{ $kategoriIcons[$layanan->kategori] ?? 'fas fa-info-circle' }} mr-1"></i>
                            {{ $layanan->kategori }}
                        </span>

                        <span class="px-3 py-1.5 text-sm font-semibold rounded-full
                                    {{ $layanan->biaya == 0 ? 'bg-green-100 text-green-800 border-green-200' : 'bg-gray-100 text-gray-800 border-gray-200' }}
                                    border">
                            <i class="fas fa-money-bill-wave mr-1"></i>
                            {{ $layanan->getBiayaFormatted() }}
                        </span>
                    </div>
                </div>

            <!-- Meta Info with Share Button Row -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-5">
                <div class="flex flex-wrap gap-4">
                    @if($layanan->updated_at)
                    <div class="flex items-center gap-2 text-gray-500">
                        <i class="far fa-calendar text-emerald-600"></i>
                        <span class="text-sm">Diperbarui: {{ $layanan->updated_at->locale('id')->isoFormat('DD MMMM YYYY') }}</span>
                    </div>
                    @endif
                </div>

                <!-- Share Button -->
                <div x-data="{ shareOpen: false }" class="relative">
                    <button @click="shareOpen = !shareOpen"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-50 hover:bg-emerald-50 text-gray-500 hover:text-emerald-500 transition-colors">
                        <i class="fas fa-share-alt text-[14px]"></i>
                        <span class="text-sm font-medium">Bagikan</span>
                    </button>

                    <!-- Share Menu -->
                    <div x-show="shareOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.away="shareOpen = false"
                         class="absolute z-50 right-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 w-48 p-1.5">

                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('layanan.show', $layanan->id)) }}"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-3 p-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors group">
                            <span class="w-8 h-8 flex items-center justify-center bg-blue-500/10 text-blue-500 rounded-full group-hover:scale-110 transition-transform">
                                <i class="fab fa-facebook-f text-[16px]"></i>
                            </span>
                            <span class="font-medium">Facebook</span>
                        </a>

                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('layanan.show', $layanan->id)) }}&text={{ urlencode($layanan->nama_layanan) }}"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-3 p-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors group">
                            <span class="w-8 h-8 flex items-center justify-center bg-sky-500/10 text-sky-500 rounded-full group-hover:scale-110 transition-transform">
                                <i class="fab fa-twitter text-[16px]"></i>
                            </span>
                            <span class="font-medium">Twitter</span>
                        </a>

                        <a href="https://wa.me/?text={{ urlencode($layanan->nama_layanan . ' - ' . route('layanan.show', $layanan->id)) }}"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-3 p-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors group">
                            <span class="w-8 h-8 flex items-center justify-center bg-green-500/10 text-green-500 rounded-full group-hover:scale-110 transition-transform">
                                <i class="fab fa-whatsapp text-[16px]"></i>
                            </span>
                            <span class="font-medium">WhatsApp</span>
                        </a>

                        <button onclick="copyToClipboard('{{ route('layanan.show', $layanan->id) }}')"
                                class="flex items-center gap-3 p-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors w-full group">
                            <span class="w-8 h-8 flex items-center justify-center bg-gray-500/10 text-gray-500 rounded-full group-hover:scale-110 transition-transform">
                                <i class="far fa-copy text-[16px]"></i>
                            </span>
                            <span class="font-medium">Salin Link</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi Layanan -->
        <div class="bg-gray-50 p-6 rounded-lg mb-4">
            <div class="prose prose-emerald max-w-none">
                {!! $layanan->deskripsi !!}
            </div>
                </div>

                <!-- Informasi Layanan -->
        <div class="bg-gray-50 p-6 rounded-lg mb-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @if($layanan->lokasi_layanan)
                    <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Lokasi Layanan</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $layanan->lokasi_layanan }}</p>
                        </div>
                    </div>
                    @endif

                    @if($layanan->jadwal_pelayanan)
                    <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Jadwal Pelayanan</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $layanan->jadwal_pelayanan }}</p>
                        </div>
                    </div>
                    @endif

                    @if($layanan->kontak_layanan)
                    <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Kontak Layanan</h3>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $layanan->kontak_layanan) }}"
                           class="mt-1 text-gray-600 hover:text-emerald-600 transition-colors inline-flex items-center text-sm group">
                            <span>{{ $layanan->kontak_layanan }}</span>
                            <span class="ml-2 bg-emerald-50 text-emerald-500 px-2 py-0.5 rounded text-xs font-medium flex items-center group-hover:bg-emerald-100 transition-colors">
                                <i class="fab fa-whatsapp mr-1"></i>
                                Chat
                            </span>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Persyaratan & Prosedur Tab Section -->
        @if(!empty($layanan->persyaratan) || !empty($layanan->prosedur))
        <div x-data="{ activeTab: 'persyaratan' }" class="mb-4">
            <!-- Tab Navigation -->
            <div class="flex justify-center mb-6">
                <div class="inline-flex bg-white backdrop-blur-sm rounded-full p-1 shadow-md">
                    @if(!empty($layanan->persyaratan))
                    <button @click="activeTab = 'persyaratan'"
                            :class="{ 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-md': activeTab === 'persyaratan',
                                     'bg-transparent text-gray-600 hover:text-gray-800': activeTab !== 'persyaratan' }"
                            class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center">
                        <svg class="w-4 h-4 mr-1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Persyaratan
                    </button>
                    @endif

                    @if(!empty($layanan->prosedur))
                    <button @click="activeTab = 'prosedur'"
                            :class="{ 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md': activeTab === 'prosedur',
                                     'bg-transparent text-gray-600 hover:text-gray-800': activeTab !== 'prosedur' }"
                            class="px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center">
                        <svg class="w-4 h-4 mr-1.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 9H16M8 13H14M8 17H12M10 2V6M14 2V6M3 8C3 6.89543 3.89543 6 5 6H19C20.1046 6 21 6.89543 21 8V20C21 21.1046 20.1046 22 19 22H5C3.89543 22 3 21.1046 3 20V8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Prosedur
                    </button>
                    @endif
                </div>
                </div>

            <!-- Content Container -->
            <div class="mt-4 relative">
                <!-- Persyaratan Tab Panel -->
                @if(!empty($layanan->persyaratan))
                <div x-show="activeTab === 'persyaratan'"
                     x-transition:enter="transition-all duration-500 ease-out"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition-all duration-300 ease-in"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-4"
                     class="bg-gray-50 rounded-lg p-6 shadow-sm">

                    <ul class="space-y-4 text-gray-600">
                        @foreach($layanan->persyaratan as $syarat)
                            <li class="flex items-start">
                                <span class="flex-shrink-0 h-5 w-5 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mr-3 mt-0.5">
                                    <i class="fas fa-check text-xs"></i>
                                </span>
                                <div>
                                    <span class="font-medium text-gray-900">{{ $syarat['dokumen'] }}</span>
                                    @if(!empty($syarat['keterangan']))
                                        <p class="text-sm text-gray-500 mt-1">{{ $syarat['keterangan'] }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Prosedur Tab Panel -->
                @if(!empty($layanan->prosedur))
                <div x-show="activeTab === 'prosedur'"
                     x-transition:enter="transition-all duration-500 ease-out"
                     x-transition:enter-start="opacity-0 transform translate-y-4"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition-all duration-300 ease-in"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-4"
                     class="bg-gray-50 rounded-lg p-6 shadow-sm">

                    <ol class="relative space-y-6 text-gray-600 ml-6 border-l-2 border-blue-200">
                        @foreach($layanan->prosedur as $index => $step)
                            <li class="pl-8 relative">
                                <!-- Circle with number -->
                                <div class="absolute -left-4 h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                                    {{ $index + 1 }}
                                </div>

                                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-100">
                                    <span class="font-medium text-gray-900">{{ $step['langkah'] }}</span>
                                    @if(!empty($step['keterangan']))
                                        <p class="text-sm text-gray-500 mt-2">{{ $step['keterangan'] }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Layanan Terkait -->
@if(count($layananLainnya) > 0)
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Modern Header -->
        <div class="mb-8">
            <!-- Title Row -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-6 w-1 bg-emerald-500 rounded-full mr-2"></div>
                    <span class="bg-emerald-50 px-2.5 py-1 rounded-full text-emerald-800 text-sm font-semibold flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        LAYANAN TERKAIT
                    </span>
                </div>
                <a href="{{ route('layanan') }}" class="flex-shrink-0 inline-flex items-center text-sm font-medium text-emerald-600 border border-emerald-200 rounded-lg px-3 py-1.5 hover:bg-emerald-50 transition-colors">
                    <span>Lihat Semua</span>
                    <svg class="ml-1.5 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Description on Full Row -->
            <p class="text-gray-600 text-sm md:text-base w-full mt-3">
                Layanan desa lainnya yang mungkin Anda butuhkan
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($layananLainnya as $item)
                <div class="group bg-white rounded-2xl shadow-sm overflow-hidden h-full flex flex-col transition-all duration-300 hover:shadow-lg relative border border-gray-100" data-aos="fade-up" data-aos-delay="{{ $loop->index % 3 * 50 }}">
                    @php
                        $itemKategoriColor = $kategoriColors[$item->kategori] ?? 'emerald';
                    @endphp

                    <div class="p-6 flex flex-col flex-grow">
                        <!-- Category and Price Row -->
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <!-- Left: Category Badge - Enhanced Design -->
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium
                                text-{{ $itemKategoriColor }}-700
                                bg-{{ $itemKategoriColor }}-50
                                border border-{{ $itemKategoriColor }}-100 shadow-sm">
                                <i class="{{ $kategoriIcons[$item->kategori] ?? 'fas fa-info-circle' }} text-{{ $itemKategoriColor }}-500 mr-1.5"></i>
                                {{ $item->kategori }}
                            </span>

                            <!-- Right: Price Badge -->
                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-md border border-gray-100 bg-gray-50 text-gray-700 text-xs font-medium">
                                <i class="fas fa-money-bill-wave text-gray-500 mr-1.5"></i>
                                {{ $item->getBiayaFormatted() }}
                            </span>
                        </div>

                        <!-- Enhanced Title with Modern Typography -->
                        <a href="{{ route('layanan.show', $item->id) }}" class="block group relative z-10">
                            <!-- Modern Title Design with Gradient Accent -->
                            <div class="relative h-0.5 w-12 bg-gradient-to-r from-emerald-400 to-emerald-600 rounded-full mb-3 transition-all duration-300 group-hover:w-24"></div>
                            <h3 class="text-xl font-bold text-gray-900 leading-tight mb-2 line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                {{ $item->nama_layanan }}
                            </h3>
                        </a>

                        <!-- Modern Description with Premium Typography -->
                        <div class="mt-1 relative z-10">
                            <p class="text-gray-600 text-sm leading-relaxed mb-5 pl-3 border-l-2 border-emerald-200 line-clamp-3 after:content-['...']">
                                {!! strip_tags($item->deskripsi) !!}
                            </p>
                        </div>

                        <!-- Info Icons Section -->
                        <div class="space-y-2 mt-1 mb-4">
                            @if($item->lokasi_layanan)
                                <div class="flex items-start">
                                    <span class="text-emerald-500 mr-2 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </span>
                                    <p class="text-gray-600 text-sm line-clamp-1">
                                        {{ $item->lokasi_layanan }}
                                    </p>
                                </div>
                            @endif

                            @if($item->jadwal_pelayanan)
                                <div class="flex items-start">
                                    <span class="text-emerald-500 mr-2 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </span>
                                    <p class="text-gray-600 text-sm line-clamp-1">
                                        {{ $item->jadwal_pelayanan }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Enhanced Action Buttons with Modern Design -->
                        <div class="mt-auto flex justify-between items-center relative z-10">
                            <!-- Detail Button - Consistent styling with UMKM buttons -->
                            <a href="{{ route('layanan.show', $item->id) }}" class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-medium text-sm rounded-lg px-4 py-2 transition-all duration-300 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                <i class="fas fa-info-circle mr-1.5 text-white"></i>
                                <span>Detail Layanan</span>
                            </a>

                            <!-- Contact Link - Same as layanan card -->
                            @if($item->kontak_layanan)
                            <a href="tel:{{ $item->kontak_layanan }}" class="inline-flex items-center px-3.5 py-1.5 bg-white text-emerald-600 rounded-full shadow-sm text-sm font-medium border border-emerald-100 hover:bg-emerald-50 transition-all duration-300 hover:shadow-md">
                                <i class="fas fa-phone-alt mr-1.5"></i>
                                Hubungi
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('styles')
<style>
    /* Typography enhancements for content */
    .prose {
        @apply text-gray-700;
    }

    .prose h2 {
        @apply text-xl font-bold text-gray-800 mt-10 mb-4;
    }

    .prose h3 {
        @apply text-lg font-semibold text-gray-800 mt-8 mb-4;
    }

    .prose p {
        @apply text-gray-700 leading-relaxed mb-6;
    }

    .prose a {
        @apply text-emerald-600 hover:text-emerald-700 transition-colors;
    }

    .prose ul, .prose ol {
        @apply pl-8 my-4;
    }

    .prose ul li, .prose ol li {
        @apply mb-2;
    }

    .prose blockquote {
        @apply border-l-4 border-emerald-400 bg-emerald-50/50 pl-4 py-2 pr-2 my-4 italic text-gray-700;
    }

    .prose ul {
        list-style-type: disc !important;
    }

    .prose ol {
        list-style-type: decimal !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text)
            .then(() => {
                // Show success toast
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 animate-fade-in z-50';
                toast.innerHTML = `
                    <i class="fas fa-check text-emerald-400"></i>
                    <span>Link berhasil disalin!</span>
                `;
                document.body.appendChild(toast);

                // Remove toast after 2 seconds
                setTimeout(() => {
                    toast.classList.add('animate-fade-out');
                    setTimeout(() => toast.remove(), 300);
                }, 2000);
            })
            .catch(console.error);
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(10px); }
    }

    .animate-fade-in {
        animation: fadeIn 0.2s ease-out forwards;
    }

    .animate-fade-out {
        animation: fadeOut 0.2s ease-out forwards;
    }
</style>
@endpush