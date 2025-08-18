<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Portal Digital Desa - Layanan terpadu untuk warga desa">
        <meta name="theme-color" content="#047857">

        <title>{{ config('app.name', 'Desa Digital') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- ADD THESE LINES - Livewire Styles -->
        @livewireStyles
    </head>
    <body class="font-sans text-gray-900 antialiased">
        @php
            $profilDesa = \App\Models\ProfilDesa::first();
        @endphp

        <!-- Home Button - Fixed in Corner -->
        <a href="{{ route('front.home') }}" class="fixed top-4 right-4 z-50 flex items-center justify-center w-10 h-10 bg-white rounded-full shadow-md hover:shadow-lg transition-all duration-200 border border-emerald-100 group">
            <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            <span class="absolute right-full mr-2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">Beranda</span>
        </a>

        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Left Side - Decorative/Branding Side -->
            <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-emerald-700 to-emerald-900 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-pattern opacity-10"></div>
                <div class="w-full h-full flex flex-col justify-between relative z-10 p-8 lg:p-12">
                    <!-- Top Area -->
                    <div class="flex items-center">
                        @if($profilDesa && $profilDesa->logo)
                            <img src="{{ Storage::disk('public')->url($profilDesa->logo) }}"
                                 alt="{{ $profilDesa->nama_desa ?? config('app.name') }}"
                                 class="w-16 h-16 object-contain rounded-full bg-white p-1">
                        @else
                            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center text-emerald-800 text-xl font-bold">
                                {{ substr(config('app.name', 'DD'), 0, 2) }}
                            </div>
                        @endif
                        <div class="ml-4">
                            <h1 class="text-xl font-bold">{{ $profilDesa->nama_desa ?? config('app.name') }}</h1>
                            <p class="text-sm text-emerald-50">
                                @php
                                    // Clean up location names by removing prefixes
                                    $kecamatan = $profilDesa->kecamatan ?? 'Kecamatan';
                                    $kabupaten = $profilDesa->kabupaten ?? 'Kabupaten';
                                    $provinsi = $profilDesa->provinsi ?? 'Provinsi';

                                    // Remove "Kecamatan" prefix if it exists
                                    $kecamatan = preg_replace('/^kecamatan\s+/i', '', $kecamatan);

                                    // Remove "Kabupaten" prefix if it exists
                                    $kabupaten = preg_replace('/^kabupaten\s+/i', '', $kabupaten);

                                    // Remove "Provinsi" prefix if it exists
                                    $provinsi = preg_replace('/^provinsi\s+/i', '', $provinsi);
                                @endphp
                                {{ $kecamatan }}, {{ $kabupaten }}, {{ $provinsi }}
                            </p>
                        </div>
                    </div>

                    <!-- Middle Area with Image -->
                    <div class="flex-grow flex flex-col justify-center py-4">
                        <h2 class="text-3xl font-bold mb-2">Layanan Desa dalam Genggaman</h2>
                        <p class="text-emerald-50 mb-4">Akses informasi dan layanan desa kapan saja dan di mana saja</p>

                        <!-- Featured Image - Desktop Only -->
                        <div class="w-full mx-auto rounded-xl overflow-hidden shadow-lg">
                            @if($profilDesa && $profilDesa->thumbnail)
                                <img src="{{ Storage::disk('public')->url($profilDesa->thumbnail) }}"
                                     alt="Desa Digital"
                                     class="w-full h-auto object-cover">
                            @else
                                <img src="{{ asset('img/desa-thumbnail.jpg') }}"
                                     alt="Desa Digital"
                                     class="w-full h-auto object-cover"
                                     onerror="this.src='https://images.unsplash.com/photo-1565675006584-8cbd549b3a75?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';this.onerror=null;">
                            @endif
                        </div>
                    </div>

                    <!-- Bottom Area -->
                    <div class="text-sm text-emerald-50">
                        &copy; {{ date('Y') }} {{ $profilDesa->nama_desa ?? config('app.name') }}
                    </div>
                </div>
            </div>

            <!-- Right Side - Form Area -->
            <div class="w-full md:w-1/2 bg-white flex items-center justify-center p-4 sm:p-6 md:p-8">
                <div class="w-full max-w-md min-h-screen md:min-h-0 flex flex-col justify-between py-6">
                    <!-- Mobile Header (visible only on mobile) -->
                    <div class="md:hidden">
                        <!-- Top Logo and Text -->
                        <div class="flex items-start justify-start sm:items-center sm:justify-center mb-6">
                            @if($profilDesa && $profilDesa->logo)
                                <img src="{{ Storage::disk('public')->url($profilDesa->logo) }}"
                                     alt="{{ $profilDesa->nama_desa ?? config('app.name') }}"
                                     class="w-16 h-16 object-contain rounded-full bg-white p-1 shadow-sm">
                            @else
                                <div class="w-16 h-16 rounded-full bg-emerald-600 flex items-center justify-center text-white text-xl font-bold shadow-sm">
                                    {{ substr(config('app.name', 'DD'), 0, 2) }}
                                </div>
                            @endif
                            <div class="ml-4">
                                <h1 class="text-xl font-bold text-gray-900">{{ $profilDesa->nama_desa ?? config('app.name') }}</h1>
                                <p class="text-sm text-emerald-600">
                                    {{ preg_replace('/^kecamatan\s+/i', '', $profilDesa->kecamatan ?? 'Kecamatan') }},
                                    {{ preg_replace('/^kabupaten\s+/i', '', $profilDesa->kabupaten ?? 'Kabupaten') }},
                                    {{ preg_replace('/^provinsi\s+/i', '', $profilDesa->provinsi ?? 'Provinsi') }}
                                </p>
                            </div>
                        </div>

                        <!-- Removed Mobile Featured Image -->
                    </div>

                    <!-- Main Content -->
                    <div>
                {{ $slot }}
                    </div>

                    <!-- Mobile Footer -->
                    <div class="text-center text-sm text-gray-600 mt-auto pt-8 md:hidden">
                        &copy; {{ date('Y') }} {{ $profilDesa->nama_desa ?? config('app.name') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- ADD THESE LINES AT THE END OF BODY -->
        @livewireScripts

        <!-- ADD Alpine.js Initialization -->
        <script>
            document.addEventListener('alpine:init', () => {
                // Keep the sidebar store (this part is working)
                Alpine.store('sidebar', {
                    isOpen: true,
                    collapsedGroups: [],
                    groupIsCollapsed(group) {
                        return this.collapsedGroups.includes(group);
                    },
                    toggleCollapsedGroup(group) {
                        if (this.groupIsCollapsed(group)) {
                            this.collapsedGroups = this.collapsedGroups.filter((g) => g !== group);
                            return;
                        }
                        this.collapsedGroups.push(group);
                    },
                    open() {
                        this.isOpen = true;
                    },
                    close() {
                        this.isOpen = false;
                    },
                    toggle() {
                        this.isOpen = !this.isOpen;
                    }
                });
            });
        </script>
    </body>
</html>
