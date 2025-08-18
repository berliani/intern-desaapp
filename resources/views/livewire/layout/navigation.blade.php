<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white shadow-sm relative z-10">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center">
                        @php
                            $profilDesa = \App\Models\ProfilDesa::first();
                        @endphp

                        @if($profilDesa && $profilDesa->logo)
                            <img src="{{ Storage::disk('public')->url($profilDesa->logo) }}"
                                 alt="{{ $profilDesa->nama_desa ?? 'Logo Desa' }}"
                                 class="block h-9 w-auto rounded-full transition duration-150 ease-in-out transform hover:scale-105">
                        @else
                           <p>Logo</p>
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:ml-10 md:flex md:space-x-8">
                    <!-- Home/Front End Link -->
                    <x-nav-link :href="route('front.home')" class="text-sm font-medium px-1 py-2 text-gray-700 hover:text-emerald-600 border-b-2 border-transparent hover:border-emerald-500 transition duration-150 ease-in-out flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        {{ __('Beranda') }}
                    </x-nav-link>

                    @if(auth()->user()->hasRole(['warga', 'unverified']))
                        <x-nav-link :href="route('warga.dashboard')" :active="request()->routeIs('warga.dashboard')" wire:navigate class="text-sm font-medium px-1 py-2 text-gray-700 hover:text-emerald-600 border-b-2 {{ request()->routeIs('warga.dashboard') ? 'border-emerald-600 text-emerald-600 font-semibold' : 'border-transparent hover:border-emerald-500' }} transition duration-150 ease-in-out flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                            </svg>
                            {{ __('Dashboard') }}
                        </x-nav-link>

                        @if(auth()->user()->hasRole('unverified'))
                            <x-nav-link :href="route('verifikasi-data')" :active="request()->routeIs('verifikasi-data')" wire:navigate class="text-sm font-medium px-1 py-2 text-gray-700 hover:text-emerald-600 border-b-2 {{ request()->routeIs('verifikasi-data') ? 'border-emerald-600 text-emerald-600 font-semibold' : 'border-transparent hover:border-emerald-500' }} transition duration-150 ease-in-out">
                                {{ __('Verifikasi Data') }}
                            </x-nav-link>
                        @endif

                        @if(auth()->user()->hasRole('warga'))
                            <x-nav-link :href="route('warga.pengaduan')" :active="request()->routeIs('warga.pengaduan')" wire:navigate class="text-sm font-medium px-1 py-2 text-gray-700 hover:text-emerald-600 border-b-2 {{ request()->routeIs('warga.pengaduan') ? 'border-emerald-600 text-emerald-600 font-semibold' : 'border-transparent hover:border-emerald-500' }} transition duration-150 ease-in-out flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                {{ __('Pengaduan') }}
                            </x-nav-link>

                            <!-- Dropdown Menu untuk Bansos -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="flex items-center text-sm font-medium px-1 py-2 text-gray-700 hover:text-emerald-600 border-b-2 {{ request()->routeIs('warga.bansos*') ? 'border-emerald-600 text-emerald-600 font-semibold' : 'border-transparent hover:border-emerald-500' }} transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    {{ __('Bantuan Sosial') }}
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                    <div class="py-1">
                                        <x-dropdown-link :href="route('warga.bansos')" wire:navigate
                                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">
                                            <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                            {{ __('Daftar Bantuan') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('warga.pengajuan-bansos')" wire:navigate
                                            class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">
                                            <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            {{ __('Ajukan Bantuan') }}
                                        </x-dropdown-link>
                                    </div>
                                </div>
                            </div>

                            <x-nav-link :href="route('warga.umkm')" :active="request()->routeIs('warga.umkm')" wire:navigate class="text-sm font-medium px-1 py-2 text-gray-700 hover:text-emerald-600 border-b-2 {{ request()->routeIs('warga.umkm') ? 'border-emerald-600 text-emerald-600 font-semibold' : 'border-transparent hover:border-emerald-500' }} transition duration-150 ease-in-out flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                {{ __('UMKM') }}
                            </x-nav-link>
                        @endif
                    @endif
                </div>
            </div>

            <div class="flex items-center">
                <!-- Verifikasi Data Notification -->
                @if(auth()->user()->hasRole('unverified') && !auth()->user()->penduduk)
                    <div class="hidden md:flex items-center mr-4">
                        <a href="{{ route('verifikasi-data') }}" class="flex items-center px-3 py-1.5 text-sm bg-amber-50 text-amber-700 border border-amber-200 rounded-full hover:bg-amber-100 transition-colors duration-150">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Lengkapi Data
                        </a>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="hidden md:flex md:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center p-2 border border-transparent rounded-full text-gray-500 bg-gray-50 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                                @if(auth()->user()->profile_photo_path)
                                    <img src="{{ Storage::disk('public')->url(auth()->user()->profile_photo_path) }}?v={{ time() }}"
                                         alt="{{ auth()->user()->name }}"
                                         class="h-8 w-8 rounded-full object-cover">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-semibold">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="ml-2 text-sm font-medium truncate max-w-[120px]" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                                <svg class="ml-1 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            @if(auth()->user()->hasRole('warga'))
                                <x-dropdown-link :href="route('warga.profile')" wire:navigate class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">
                                    <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ __('Profile') }}
                                </x-dropdown-link>
                            @endif

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-left">
                                <x-dropdown-link class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50">
                                    <svg class="mr-2 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center md:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': !open}"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="hidden md:hidden">
        <div class="bg-white pt-2 pb-3 space-y-1 shadow-lg border-t">
            <!-- Home/Front End Link for Mobile -->
            <x-responsive-nav-link :href="route('front.home')" class="block pl-3 pr-4 py-2 text-base font-medium hover:bg-gray-50 transition duration-150 ease-in-out flex items-center">
                <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ __('Beranda') }}
            </x-responsive-nav-link>

            @if(auth()->user()->hasRole(['warga', 'unverified']))
                <x-responsive-nav-link :href="route('warga.dashboard')" :active="request()->routeIs('warga.dashboard')" wire:navigate class="block pl-3 pr-4 py-2 text-base font-medium hover:bg-gray-50 transition duration-150 ease-in-out {{ request()->routeIs('warga.dashboard') ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500' : '' }} flex items-center">
                    <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>

                @if(auth()->user()->hasRole('unverified'))
                    <x-responsive-nav-link :href="route('verifikasi-data')" :active="request()->routeIs('verifikasi-data')" wire:navigate class="block pl-3 pr-4 py-2 text-base font-medium hover:bg-gray-50 transition duration-150 ease-in-out {{ request()->routeIs('verifikasi-data') ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500' : '' }}">
                        <div class="flex items-center">
                            {{ __('Verifikasi Data') }}
                            @if(!auth()->user()->penduduk)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Wajib</span>
                            @endif
                        </div>
                    </x-responsive-nav-link>
                @endif

                @if(auth()->user()->hasRole('warga'))
                    <x-responsive-nav-link :href="route('warga.pengaduan')" :active="request()->routeIs('warga.pengaduan')" wire:navigate class="block pl-3 pr-4 py-2 text-base font-medium hover:bg-gray-50 transition duration-150 ease-in-out {{ request()->routeIs('warga.pengaduan') ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500' : '' }} flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                        {{ __('Pengaduan') }}
                    </x-responsive-nav-link>

                    <!-- Bantuan Sosial Menu untuk Mobile -->
                    <div x-data="{ bansosOpen: false }" class="relative">
                        <button @click="bansosOpen = !bansosOpen" class="w-full flex items-center pl-3 pr-4 py-2 text-base font-medium hover:bg-gray-50 transition duration-150 ease-in-out {{ request()->routeIs('warga.bansos*') ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500' : '' }}">
                            <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="flex-1 text-left">{{ __('Bantuan Sosial') }}</span>
                            <svg class="h-5 w-5 transform" :class="{ 'rotate-180': bansosOpen }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="bansosOpen" class="bg-gray-50">
                            <x-responsive-nav-link :href="route('warga.bansos')" :active="request()->routeIs('warga.bansos')" wire:navigate class="block pl-6 pr-4 py-2 text-base font-medium hover:bg-gray-100">
                                {{ __('Daftar Bantuan') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('warga.pengajuan-bansos')" :active="request()->routeIs('warga.pengajuan-bansos')" wire:navigate class="block pl-6 pr-4 py-2 text-base font-medium hover:bg-gray-100">
                                {{ __('Ajukan Bantuan') }}
                            </x-responsive-nav-link>
                        </div>
                    </div>

                    <x-responsive-nav-link :href="route('warga.umkm')" :active="request()->routeIs('warga.umkm')" wire:navigate class="block pl-3 pr-4 py-2 text-base font-medium hover:bg-gray-50 transition duration-150 ease-in-out {{ request()->routeIs('warga.umkm') ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500' : '' }} flex items-center">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        {{ __('UMKM') }}
                    </x-responsive-nav-link>
                @endif
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center px-4 py-2">
                <div class="flex-shrink-0">
                    @if(auth()->user()->profile_photo_path)
                        <img src="{{ Storage::disk('public')->url(auth()->user()->profile_photo_path) }}?v={{ time() }}"
                             alt="{{ auth()->user()->name }}"
                             class="h-10 w-10 rounded-full object-cover">
                    @else
                        <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1 border-t border-gray-200 pt-2">
                @if(auth()->user()->hasRole('warga'))
                    <x-responsive-nav-link :href="route('warga.profile')" :active="request()->routeIs('warga.profile')" wire:navigate class="flex pl-4 pr-4 py-2 text-base font-medium hover:bg-white {{ request()->routeIs('warga.profile') ? 'bg-emerald-50 text-emerald-700 border-l-4 border-emerald-500' : '' }}">
                        <svg class="mr-3 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-left">
                    <x-responsive-nav-link class="flex pl-4 pr-4 py-2 text-base font-medium hover:bg-white">
                        <svg class="mr-3 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
