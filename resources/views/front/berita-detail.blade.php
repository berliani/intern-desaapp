@extends('layouts.front')

@section('title', $berita->judul . ' - Berita ' . ($profilDesa->nama_desa ?? 'Desa'))

@section('meta')
<meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($berita->isi), 160) }}">
<meta property="og:title" content="{{ $berita->judul }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($berita->isi), 160) }}">
@if($berita->gambar)
<meta property="og:image" content="{{ Storage::url($berita->gambar) }}">
@endif
<meta property="og:type" content="article">
<meta property="article:published_time" content="{{ $berita->created_at->toIso8601String() }}">
<meta property="article:section" content="{{ $berita->kategori }}">
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
                    <a href="{{ route('berita') }}" class="flex items-center text-gray-500 hover:text-emerald-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        Berita
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-emerald-600 font-medium line-clamp-1 max-w-[280px] sm:max-w-md">
                        {{ $berita->judul }}
                    </span>
                </li>
            </ol>
        </nav>
    </div>
</div>
@endsection

@section('content')
<!-- Hero Section with Clean Image (No Text Overlay) -->
<div class="w-full relative">
    @if($berita->gambar)
    <div class="w-full h-[300px] md:h-[400px] lg:h-[450px] overflow-hidden">
        <img src="{{ Storage::url($berita->gambar) }}"
             alt="{{ $berita->judul }}"
             class="w-full h-full object-cover">
    </div>
    @else
    <!-- Fallback when no image - Simple colored bar -->
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-[150px] md:h-[200px]"></div>
    @endif
</div>

<!-- Main Content with Sidebar Layout -->
<div class="bg-white py-8 md:py-12 -mt-0">
    <div class="max-w-7xl pt-4 mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-2">
                <!-- Article Title and Category Section -->
                <div class="mb-8">
                    <!-- Category Badge with Matching Style -->
                    @php
                        $styles = match($berita->kategori ?? '') {
                            'Umum' => 'bg-indigo-500 border-indigo-600',
                            'Pengumuman' => 'bg-amber-500 border-amber-600',
                            'Kegiatan' => 'bg-emerald-500 border-emerald-600',
                            'Infrastruktur' => 'bg-rose-500 border-rose-600',
                            'Kesehatan' => 'bg-sky-500 border-sky-600',
                            'Pendidikan' => 'bg-purple-500 border-purple-600',
                            default => 'bg-emerald-500 border-emerald-600',
                        };
                        $icon = match($berita->kategori ?? '') {
                            'Umum' => 'fa-globe',
                            'Pengumuman' => 'fa-bullhorn',
                            'Kegiatan' => 'fa-calendar-check',
                            'Infrastruktur' => 'fa-road',
                            'Kesehatan' => 'fa-heartbeat',
                            'Pendidikan' => 'fa-graduation-cap',
                            default => 'fa-tag',
                        };
                    @endphp

                    <a href="{{ route('berita', ['kategori' => $berita->kategori]) }}"
                       class="inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-medium text-white {{ $styles }} shadow-md border-l-4 mb-4">
                        <i class="fas {{ $icon }} mr-1"></i>
                        <span>{{ $berita->kategori ?? 'Berita' }}</span>
                    </a>

                    <!-- Title with Reduced Size -->
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 leading-tight mb-6">
                        {{ $berita->judul }}
                    </h1>

                    <!-- Date & Share Button Row - Properly Aligned -->
                    <div class="flex items-center justify-between border-b border-gray-100 pb-5">
                        <!-- Date -->
                        <div class="flex items-center gap-2 text-gray-500">
                            <i class="far fa-calendar text-emerald-600"></i>
                            <span class="text-sm">{{ $berita->created_at->locale('id')->isoFormat('DD MMMM YYYY') }}</span>
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

                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('berita.show', $berita->id)) }}"
                                   target="_blank" rel="noopener"
                                   class="flex items-center gap-3 p-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors group">
                                    <span class="w-8 h-8 flex items-center justify-center bg-blue-500/10 text-blue-500 rounded-full group-hover:scale-110 transition-transform">
                                        <i class="fab fa-facebook-f text-[16px]"></i>
                                    </span>
                                    <span class="font-medium">Facebook</span>
                                </a>

                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('berita.show', $berita->id)) }}&text={{ urlencode($berita->judul) }}"
                                   target="_blank" rel="noopener"
                                   class="flex items-center gap-3 p-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors group">
                                    <span class="w-8 h-8 flex items-center justify-center bg-sky-500/10 text-sky-500 rounded-full group-hover:scale-110 transition-transform">
                                        <i class="fab fa-twitter text-[16px]"></i>
                                    </span>
                                    <span class="font-medium">Twitter</span>
                                </a>

                                <a href="https://wa.me/?text={{ urlencode($berita->judul . ' - ' . route('berita.show', $berita->id)) }}"
                                   target="_blank" rel="noopener"
                                   class="flex items-center gap-3 p-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors group">
                                    <span class="w-8 h-8 flex items-center justify-center bg-green-500/10 text-green-500 rounded-full group-hover:scale-110 transition-transform">
                                        <i class="fab fa-whatsapp text-[16px]"></i>
                                    </span>
                                    <span class="font-medium">WhatsApp</span>
                                </a>

                                <a href="https://t.me/share/url?url={{ urlencode(route('berita.show', $berita->id)) }}&text={{ urlencode($berita->judul) }}"
                                   target="_blank" rel="noopener"
                                   class="flex items-center gap-3 p-2 text-sm text-gray-600 hover:bg-gray-50 rounded-lg transition-colors group">
                                    <span class="w-8 h-8 flex items-center justify-center bg-blue-400/10 text-blue-400 rounded-full group-hover:scale-110 transition-transform">
                                        <i class="fab fa-telegram text-[16px]"></i>
                                    </span>
                                    <span class="font-medium">Telegram</span>
                                </a>

                                <button onclick="copyToClipboard('{{ route('berita.show', $berita->id) }}')"
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

                <!-- Article Content -->
                <div class="prose prose-emerald lg:prose-lg max-w-none mx-auto mb-10">
                    {!! $berita->isi !!}
                </div>

                <!-- Author Section (Moved to below content) -->
                <div class="border-t border-gray-100 pt-6 mt-10">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-emerald-500 flex items-center justify-center text-white ring-4 ring-emerald-50 shadow-md">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                            <div class="absolute -bottom-1 -right-1 h-4 w-4 md:h-5 md:w-5 bg-green-500 border-[3px] border-white rounded-full shadow-sm"></div>
                        </div>

                        <div class="min-w-0">
                            <div class="flex items-center flex-wrap gap-2">
                                <h3 class="text-base md:text-lg font-bold text-gray-900 break-words">{{ $berita->creator->name ?? 'Admin' }}</h3>
                                <span class="inline-flex items-center gap-1 px-2 md:px-2.5 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-semibold bg-emerald-50 text-emerald-600">
                                    <i class="fas fa-check-circle text-[8px] md:text-[10px]"></i>
                                    Admin Desa
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">
                                Penulis di {{ $profilDesa->nama_desa ?? 'Desa Digital' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="lg:col-span-1">
                <!-- Berita Terkait -->
                @if(count($beritaTerkait) > 0)
                <div class="bg-white rounded-xl shadow-sm p-5 mb-6 border border-gray-100 sticky top-4">
                    <h3 class="font-semibold text-gray-900 mb-6 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-newspaper text-emerald-500"></i>
                        Berita Terkait
                    </h3>
                    <div class="space-y-5">
                        @foreach($beritaTerkait as $item)
                            <a href="{{ route('berita.show', $item->id) }}" class="flex gap-3 group">
                                <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden">
                                    @if($item->gambar)
                                        <img src="{{ Storage::url($item->gambar) }}"
                                             alt="{{ $item->judul }}"
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-newspaper text-gray-300 text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-medium line-clamp-2 group-hover:text-emerald-600 transition-colors">
                                        {{ $item->judul }}
                                    </h4>
                                    <div class="flex items-center mt-1 text-xs text-gray-500">
                                        <i class="far fa-calendar text-[9px] mr-1"></i>
                                        {{ $item->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Kategori -->
                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                    <h3 class="font-semibold text-gray-900 mb-6 flex items-center gap-2 border-b border-gray-100 pb-3">
                        <i class="fas fa-folder text-emerald-500"></i>
                        Kategori
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $kategoriList = [
                                'Umum' => 'bg-indigo-50 text-indigo-600',
                                'Pengumuman' => 'bg-amber-50 text-amber-600',
                                'Kegiatan' => 'bg-emerald-50 text-emerald-600',
                                'Infrastruktur' => 'bg-rose-50 text-rose-600',
                                'Kesehatan' => 'bg-sky-50 text-sky-600',
                                'Pendidikan' => 'bg-purple-50 text-purple-600',
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
                               class="px-3 py-1.5 text-xs rounded-md transition-all duration-300 hover:-translate-y-0.5 inline-flex items-center gap-1.5 {{ $value }}">
                                <i class="fas {{ $icons[$key] ?? 'fa-tag' }} text-[10px]"></i>
                                {{ $key }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')

@endpush