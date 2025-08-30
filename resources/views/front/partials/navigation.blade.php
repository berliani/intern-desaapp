@php

    $host = request()->getHost();
    $mainDomain = config('app.domain', 'desa.local');
    $isSubdomain = $host !== $mainDomain && Str::endsWith($host, '.' . $mainDomain);
@endphp

<nav x-data="{
    mobileMenuOpen: false,
    scrolled: false,
    toggleMobileMenu() { this.mobileMenuOpen = !this.mobileMenuOpen }
}" @scroll.window="scrolled = window.pageYOffset > 20"
    class="sticky top-0 z-50 bg-white shadow-sm py-2 transition-all duration-200"
    :class="{ 'py-1 shadow-md': scrolled }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center transition-transform hover:-translate-y-0.5 duration-200">
                    {{-- LOGIC CHECK FOR SUBDOMAIN --}}
                    @if(request()->attributes->has('company'))
                    @php
                    $company = request()->attributes->get('company');
                    $profilDesa = $company->profilDesa;
                    @endphp
                    @if($profilDesa && $profilDesa->logo)
                    <img src="{{ Storage::url($profilDesa->logo) }}" alt="{{ $profilDesa->nama_desa }}" class="h-10 w-auto object-contain">
                    @else
                    <div class="flex items-center">
                        <span class="text-emerald-600 font-bold text-2xl mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <div>
                            <div class="text-emerald-600 font-bold text-xl">{{ $company->name }}</div>
                            <div class="text-gray-500 text-xs font-medium hidden sm:block">Website Resmi Desa</div>
                        </div>
                    </div>
                    @endif
                    @else
                    {{-- Tampilan Logo Global --}}
                    <div class="flex items-center">
                        <span class="text-emerald-600 font-bold text-2xl mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </span>
                        <div>
                            <div class="text-emerald-600 font-bold text-xl">Desa Digital</div>
                            <div class="text-gray-500 text-xs font-medium hidden sm:block">Platform Website Desa</div>
                        </div>
                    </div>
                    @endif
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden lg:flex lg:items-center">
                <div class="flex items-center space-x-6 mr-8">
                </div>


                <div class="flex items-center border-l border-gray-200 pl-6 ml-6 space-x-4">
                    @if ($isSubdomain)

                        @auth
                            <!-- User Dropdown Desktop -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false"
                                    class="flex items-center text-sm font-medium text-gray-700 hover:text-emerald-600 focus:outline-none transition duration-150 ease-in-out bg-gray-100 hover:bg-gray-200 rounded-full pl-3 pr-2 py-1.5">
                                    <span class="mr-1">{{ Auth::user()->name }}</span>
                                    <img class="h-8 w-8 rounded-full object-cover border-2 border-white shadow-sm"
                                        src="{{ Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=10B981&background=D1FAE5' }}"
                                        alt="{{ Auth::user()->name }}">
                                </button>
                                <div x-show="open" x-transition
                                    class="absolute right-0 mt-2 w-56 rounded-xl shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50 border border-gray-100"
                                    x-cloak>
                                    <a href="{{ route('dashboard') }}"
                                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-emerald-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-emerald-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg> Dashboard
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-emerald-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-emerald-500"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium px-5 py-2 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 border border-emerald-200 rounded-lg transition-colors duration-200">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}"
                                class="text-sm font-medium px-5 py-2 text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm hover:shadow transition-all duration-200">
                                Daftar Warga
                            </a>
                        @endauth
                    @else
                        {{-- TAMPILAN UNTUK SITUS UTAMA (GLOBAL) --}}
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium px-5 py-2 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 border border-emerald-200 rounded-lg transition-colors duration-200">
                            Masuk
                        </a>
                        {{-- <a href="{{ route('register') }}"
                            class="text-sm font-medium px-5 py-2 text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm hover:shadow transition-all duration-200">
                            Daftar Warga --}}
                        </a>
                        <a href="{{ route('register-desa') }}"
                            class="text-sm font-medium px-5 py-2 text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm hover:shadow transition-all duration-200">
                            Daftarkan Desa
                        </a>
                    @endif
                </div>
                @endif
            </div>

            <!-- Mobile menu button -->
            <div class="lg:hidden">
                <button @click="toggleMobileMenu" class="inline-flex items-center justify-center p-2 rounded-lg text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500 transition duration-150">
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
    <div class="lg:hidden fixed inset-x-0 z-40" x-show="mobileMenuOpen" x-transition x-cloak>
        <div class="pt-2 pb-3 space-y-1 bg-white border-t border-gray-200 mt-2 shadow-lg">

            <div class="pt-4 pb-3 border-t border-gray-200 mt-2">
                @if(request()->attributes->has('company'))
                {{-- TAMPILAN TOMBOL MOBILE UNTUK SUBDOMAIN --}}
                @auth
                {{-- Tampilan mobile saat login --}}
                @else
                <div class="grid grid-cols-1 gap-3 px-3 mt-3">
                    <a href="{{ route('login') }}" class="flex justify-center items-center px-4 py-3 border-2 border-emerald-200 text-base font-medium rounded-lg text-emerald-600 bg-white hover:bg-emerald-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg> Masuk
                    </a>
                    {{-- FIX: Tombol Daftar Warga ditambahkan di sini --}}
                    <a href="{{ route('register') }}" class="flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg> Daftar Warga
                    </a>
                </div>
                @endauth
                @else
                {{-- TAMPILAN TOMBOL MOBILE UNTUK SITUS UTAMA (GLOBAL) --}}
                <div class="grid grid-cols-1 gap-3 px-3 mt-3">
                    <a href="{{ route('login') }}" class="flex justify-center items-center px-4 py-3 border-2 border-emerald-200 text-base font-medium rounded-lg text-emerald-600 bg-white hover:bg-emerald-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg> Masuk
                    </a>
                    <!-- <a href="{{ route('register') }}" class="flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg> Daftar Warga
                        </a> -->
                    <a href="{{ route('register-desa') }}" class="flex justify-center items-center px-4 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg> Daftarkan Desa
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</nav>
