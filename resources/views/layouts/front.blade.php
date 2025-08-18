<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <!-- Preconnect -->
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Tags -->
        <title>{{ $profilDesa->nama_desa ?? config('app.name') }} - @yield('title', 'Website Resmi')</title>
        <meta name="description" content="@yield('meta_description', 'Website Resmi Desa ' . ($profilDesa->nama_desa ?? 'Digital') . ' - Informasi dan layanan desa dalam genggaman')">
        <meta name="keywords" content="@yield('meta_keywords', 'desa digital, pemerintahan desa, ' . ($profilDesa->nama_desa ?? 'desa') . ', layanan desa, berita desa, UMKM desa')">
        <meta name="author" content="{{ $profilDesa->nama_desa ?? config('app.name') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $profilDesa->nama_desa ?? config('app.name') }} - @yield('title', 'Website Resmi')">
        <meta property="og:description" content="@yield('meta_description', 'Website Resmi Desa ' . ($profilDesa->nama_desa ?? 'Digital') . ' - Informasi dan layanan desa dalam genggaman')">
        <meta property="og:image" content="{{ $profilDesa->logo ? Storage::url($profilDesa->logo) : asset('images/default-logo.png') }}">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $profilDesa->nama_desa ?? config('app.name') }} - @yield('title', 'Website Resmi')">
        <meta name="twitter:description" content="@yield('meta_description', 'Website Resmi Desa ' . ($profilDesa->nama_desa ?? 'Digital') . ' - Informasi dan layanan desa dalam genggaman')">
        <meta name="twitter:image" content="{{ $profilDesa->logo ? Storage::url($profilDesa->logo) : asset('images/default-logo.png') }}">

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">

        <meta name="theme-color" content="#047857">

        <!-- Fonts - Upgraded with modern combinations -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/css/front.css', 'resources/js/app.js', 'resources/js/front.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

              <!-- Include Swiper.js from CDN -->
              <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />


        @livewireStyles

        <!-- Page Specific Styles -->
        @stack('styles')
    </head>

    <body class="font-sans antialiased bg-gray-50">
        <!-- Subtle background pattern using SVG instead of image -->
        <div class="fixed inset-0 -z-10 bg-dots-pattern opacity-10"></div>

        <div class="min-h-screen flex flex-col">
            <!-- Enhanced Header/Navigation -->
            <header class="fixed top-0 z-50 w-full header-nav">
                @include('front.partials.navigation')
            </header>

            <!-- Hero Section with improved styling -->
            @hasSection('hero')
                <div class="hero-section">
                    @yield('hero')
                </div>
            @endif

            <!-- Modernized Breadcrumbs -->
            @hasSection('breadcrumbs')
                <div class="bg-white border-b border-gray-100 pt-16 pb-1">
                    <div class="w-full">
                        @yield('breadcrumbs')
                    </div>
                </div>
            @endif

            <!-- Main Content Area with improved spacing -->
            <main class="flex-grow bg-white">
                @yield('content')
            </main>

            <!-- Enhanced Footer -->
            <footer class="footer">
                @include('front.partials.footer')
            </footer>
        </div>

        <!-- Scripts -->
        @livewireScripts

        <!-- Additional Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

        @stack('scripts')
    </body>
</html>
