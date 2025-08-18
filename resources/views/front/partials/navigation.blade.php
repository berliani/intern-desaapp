<nav x-data="{ mobileMenuOpen: false, scrolled: false,
      toggleMobileMenu() { this.mobileMenuOpen = !this.mobileMenuOpen } }"
     @scroll.window="scrolled = window.pageYOffset > 20"
     class="sticky top-0 z-50 bg-white shadow-sm py-2 transition-all duration-200"
     :class="{ 'py-1 shadow-md': scrolled }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <!-- Logo dengan efek hover -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center transition-transform hover:-translate-y-0.5 duration-200">
                    @if($profilDesa && $profilDesa->logo)
                        <img
                            src="{{ Storage::url($profilDesa->logo) }}"
                            alt="{{ $profilDesa->nama_desa }}"
                            class="h-10 w-auto object-contain"
                        >
                    @else
                        <div class="flex items-center">
                            <span class="text-emerald-600 font-bold text-2xl mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-emerald-600 font-bold text-xl">{{ $profilDesa->nama_desa ?? 'Desa Digital' }}</div>
                                <div class="text-gray-500 text-xs font-medium hidden sm:block">Website Resmi Desa</div>
                            </div>
                        </div>
                    @endif
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex lg:items-center">
                <div class="flex items-center space-x-6 mr-8">
                    <a href="{{ route('home') }}" class="flex items-center space-x-1.5 text-sm font-medium group">
                        <span class="text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </span>
                        <span class="py-2 px-1 {{ request()->routeIs('home') ? 'border-b-2 border-emerald-500 text-emerald-600 font-semibold' : 'border-b-2 border-transparent text-gray-600 group-hover:text-emerald-500 group-hover:border-emerald-300' }} transition duration-200">
                            Beranda
                        </span>
                    </a>

                    <a href="{{ route('profil') }}" class="flex items-center space-x-1.5 text-sm font-medium group">
                        <span class="text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                        <span class="py-2 px-1 {{ request()->routeIs('profil') ? 'border-b-2 border-emerald-500 text-emerald-600 font-semibold' : 'border-b-2 border-transparent text-gray-600 group-hover:text-emerald-500 group-hover:border-emerald-300' }} transition duration-200">
                            Profil
                        </span>
                    </a>

                    <a href="{{ route('berita') }}" class="flex items-center space-x-1.5 text-sm font-medium group">
                        <span class="text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </span>
                        <span class="py-2 px-1 {{ request()->routeIs('berita') ? 'border-b-2 border-emerald-500 text-emerald-600 font-semibold' : 'border-b-2 border-transparent text-gray-600 group-hover:text-emerald-500 group-hover:border-emerald-300' }} transition duration-200">
                            Berita
                        </span>
                    </a>

                    <a href="{{ route('umkm') }}" class="flex items-center space-x-1.5 text-sm font-medium group">
                        <span class="text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </span>
                        <span class="py-2 px-1 {{ request()->routeIs('umkm') ? 'border-b-2 border-emerald-500 text-emerald-600 font-semibold' : 'border-b-2 border-transparent text-gray-600 group-hover:text-emerald-500 group-hover:border-emerald-300' }} transition duration-200">
                            UMKM
                        </span>
                    </a>

                    <a href="{{ route('layanan') }}" class="flex items-center space-x-1.5 text-sm font-medium group">
                        <span class="text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        <span class="py-2 px-1 {{ request()->routeIs('layanan') ? 'border-b-2 border-emerald-500 text-emerald-600 font-semibold' : 'border-b-2 border-transparent text-gray-600 group-hover:text-emerald-500 group-hover:border-emerald-300' }} transition duration-200">
                            Layanan
                        </span>
                    </a>

                    <a href="{{ route('statistik') }}" class="flex items-center space-x-1.5 text-sm font-medium group">
                        <span class="text-emerald-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </span>
                        <span class="py-2 px-1 {{ request()->routeIs('statistik') ? 'border-b-2 border-emerald-500 text-emerald-600 font-semibold' : 'border-b-2 border-transparent text-gray-600 group-hover:text-emerald-500 group-hover:border-emerald-300' }} transition duration-200">
                            Statistik
                        </span>
                    </a>
                </div>

                @auth
                    <!-- User Dropdown Desktop -->
                    <div class="relative border-l border-gray-200 pl-6" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            @click.away="open = false"
                            class="flex items-center text-sm font-medium text-gray-700 hover:text-emerald-600 focus:outline-none transition duration-150 ease-in-out bg-gray-100 hover:bg-gray-200 rounded-full pl-3 pr-2 py-1.5"
                        >
                            <span class="mr-1">{{ Auth::user()->name }}</span>
                            <img
                                class="h-8 w-8 rounded-full object-cover border-2 border-white shadow-sm"
                                src="{{ Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=10B981&background=D1FAE5' }}"
                                alt="{{ Auth::user()->name }}"
                            />
                        </button>

                        <!-- Dropdown Menu yang Diperbarui -->
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 rounded-xl shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50 border border-gray-100"
                            x-cloak
                        >
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm text-gray-500">Selamat datang,</p>
                                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            </div>

                            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg> Dashboard
                            </a>
                            <a href="{{ route('warga.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg> Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-emerald-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Tombol Autentikasi yang Diperbarui -->
                    <div class="flex items-center border-l border-gray-200 pl-6 ml-6 space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium px-5 py-2 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 border border-emerald-200 rounded-lg transition-colors duration-200">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-medium px-5 py-2 text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm hover:shadow transition-all duration-200">
                            Daftar
                        </a>
                         <a href="{{ route('register-desa') }}" class="text-sm font-medium px-5 py-2 text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm hover:shadow transition-all duration-200">
                            Daftarkan Desa
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="lg:hidden">
                <button
                    @click="toggleMobileMenu"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500 transition duration-150"
                >
                    <span class="sr-only">Buka menu</span>
                    <svg class="h-6 w-6" x-bind:class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" x-bind:class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div class="lg:hidden fixed inset-x-0 z-40" x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
        <div class="pt-2 pb-3 space-y-1 bg-white border-t border-gray-200 mt-2 shadow-lg">
            <!-- Menu item dengan ikon -->
            <a href="{{ route('home') }}" class="flex items-center pl-3 pr-4 py-3 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }} transition-colors rounded-lg mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-base font-medium">Beranda</span>
            </a>

            <a href="{{ route('profil') }}" class="flex items-center pl-3 pr-4 py-3 {{ request()->routeIs('profil') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }} transition-colors rounded-lg mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-base font-medium">Profil Desa</span>
            </a>

            <a href="{{ route('berita') }}" class="flex items-center pl-3 pr-4 py-3 {{ request()->routeIs('berita') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }} transition-colors rounded-lg mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <span class="text-base font-medium">Berita</span>
            </a>

            <a href="{{ route('umkm') }}" class="flex items-center pl-3 pr-4 py-3 {{ request()->routeIs('umkm') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }} transition-colors rounded-lg mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="text-base font-medium">UMKM</span>
            </a>

            <a href="{{ route('layanan') }}" class="flex items-center pl-3 pr-4 py-3 {{ request()->routeIs('layanan') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }} transition-colors rounded-lg mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-base font-medium">Layanan</span>
            </a>

            <a href="{{ route('statistik') }}" class="flex items-center pl-3 pr-4 py-3 {{ request()->routeIs('statistik') ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }} transition-colors rounded-lg mx-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-base font-medium">Statistik</span>
            </a>

            <!-- Mobile Authentication Links dengan Style yang Diperbarui -->
            <div class="pt-4 pb-3 border-t border-gray-200 mt-2">
                @auth
                    <!-- User Profile Mobile yang Diperbarui -->
                    <div class="flex items-center mx-3 p-3 bg-gray-50 rounded-lg">
                        <div class="flex-shrink-0">
                            <img
                                class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-sm"
                                src="{{ Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=10B981&background=D1FAE5' }}"
                                alt="{{ Auth::user()->name }}"
                            />
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500 truncate">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1 px-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center py-3 px-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-emerald-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="text-base font-medium">Dashboard</span>
                        </a>

                        <a href="{{ route('warga.profile') }}" class="flex items-center py-3 px-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-emerald-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-base font-medium">Profil</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center py-3 px-3 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-emerald-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="text-base font-medium">Keluar</span>
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Tombol Login/Register yang Diperbarui -->
                    <div class="grid grid-cols-1 gap-3 px-3 mt-3">
                        <a href="{{ route('login') }}" class="flex justify-center items-center px-4 py-3 border-2 border-emerald-200 text-base font-medium rounded-lg text-emerald-600 bg-white hover:bg-emerald-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg> Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
