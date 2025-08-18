<div class="min-h-screen">
    <x-slot name="header">
        <div class="flex justify-between items-center py-1">
            <h2 class="text-lg sm:text-xl font-medium text-gray-800">
                {{ __('Bantuan Sosial') }}
            </h2>
            @if($penduduk)
                <a href="{{ route('warga.pengajuan-bansos') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-sm hover:shadow-md hover:from-emerald-600 hover:to-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-300">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Ajukan Bantuan

                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        @if (session()->has('message'))
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 shadow-sm border border-green-100" role="alert">
                <p>{{ session('message') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 shadow-sm border border-red-100" role="alert">
                <p>{{ session('error') }}</p>
                </div>
        @endif

        @if($penduduk)
            <div class="bg-white overflow-hidden rounded-lg shadow-sm mb-6">
                    <div class="p-6">
                    <div class="pb-4 border-b border-gray-200 mb-6 flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Riwayat Bantuan Sosial
                            </h3>

                        <button wire:click="refresh" x-data="{ spinning: false }"
                            @click="spinning = true; setTimeout(() => spinning = false, 1000)"
                            class="flex items-center justify-center w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-800 transition-all duration-300 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" :class="{ 'animate-spin': spinning }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>

                        @if($bansosList->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            <h3 class="mt-4 text-xl font-medium text-gray-900">Belum Ada Data Bantuan</h3>
                            <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">Anda belum memiliki riwayat bantuan sosial.</p>
                            <div class="mt-6">
                                <a href="{{ route('warga.pengajuan-bansos') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-sm hover:shadow-md hover:from-emerald-600 hover:to-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-300">
                                    Ajukan Bantuan Sekarang
                                </a>
                            </div>
                            </div>
                        @else
                        <!-- Mobile view - Cards with improved layout -->
                        <div class="md:hidden space-y-4">
                            @foreach($bansosList as $bansos)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-all duration-300">
                                    <!-- Header with name on first line, category and status on second line -->
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <!-- First row: Only nama_bansos -->
                                        <h4 class="text-base font-medium text-gray-900 mb-2">
                                            {{ $bansos->jenisBansos->nama_bansos }}
                                        </h4>

                                        <!-- Second row: Category and status badges together -->
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-fit">
                                                {{ $bansos->jenisBansos->kategori }}
                                            </span>

                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit
                                                @if($bansos->status == 'Diajukan') bg-yellow-100 text-yellow-800
                                                @elseif($bansos->status == 'Dalam Verifikasi') bg-blue-100 text-blue-800
                                                @elseif($bansos->status == 'Diverifikasi') bg-indigo-100 text-indigo-800
                                                @elseif($bansos->status == 'Disetujui') bg-green-100 text-green-800
                                                @elseif($bansos->status == 'Ditolak') bg-red-100 text-red-800
                                                @elseif($bansos->status == 'Sudah Diterima') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $bansos->status }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content section -->
                                    <div class="p-4">
                                        @if($bansos->lokasi_pengambilan && in_array($bansos->status, ['Disetujui', 'Diverifikasi']))
                                            <div class="flex items-start mb-3 bg-blue-50 p-2 rounded-md">
                                                <svg class="flex-shrink-0 w-4 h-4 text-blue-500 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <div class="text-sm text-blue-700">
                                                    <span class="font-medium">Lokasi Pengambilan:</span><br/>
                                                    {{ $bansos->lokasi_pengambilan }}
                                                </div>
                                            </div>
                                        @endif

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span>Pengajuan: {{ $bansos->tanggal_pengajuan->format('d M Y') }}</span>
                                            </div>

                                            @if($bansos->tanggal_penerimaan)
                                                <div class="flex items-center text-xs text-green-600">
                                                    <svg class="w-3.5 h-3.5 mr-1.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span>Diterima: {{ $bansos->tanggal_penerimaan->format('d M Y') }}</span>
                                                </div>
                                            @endif

                                            @if($bansos->tenggat_pengambilan && in_array($bansos->status, ['Disetujui', 'Diverifikasi']))
                                                <div class="flex items-center text-xs text-red-600">
                                                    <svg class="w-3.5 h-3.5 mr-1.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>Tenggat: {{ $bansos->tenggat_pengambilan->format('d M Y') }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        @if($bansos->keterangan)
                                            <div class="mt-3 pt-3 border-t border-gray-100">
                                                <div class="text-xs text-gray-500">
                                                    <p class="line-clamp-2" title="{{ $bansos->keterangan }}">
                                                        {{ $bansos->keterangan }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Footer section with action button -->
                                    <div class="px-4 py-3 bg-gray-50 border-t border-gray-100 flex justify-end">
                                        <a href="{{ route('warga.bansos.detail', $bansos->id) }}"
                                           class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-gradient-to-r from-emerald-500 to-emerald-600 shadow-sm hover:from-emerald-600 hover:to-emerald-700 hover:shadow transition-all duration-300">
                                            <span>Lihat Detail</span>
                                            <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop view - Table with modern styling -->
                        <div class="hidden md:block overflow-x-auto rounded-lg border border-gray-100">
                                <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Bantuan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi Pengambilan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($bansosList as $bansos)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                                <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $bansos->jenisBansos->nama_bansos }}
                                                            </div>
                                                    <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-fit">
                                                                {{ $bansos->jenisBansos->kategori }}
                                                    </span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit
                                                        @if($bansos->status == 'Diajukan') bg-yellow-100 text-yellow-800
                                                        @elseif($bansos->status == 'Dalam Verifikasi') bg-blue-100 text-blue-800
                                                        @elseif($bansos->status == 'Diverifikasi') bg-indigo-100 text-indigo-800
                                                        @elseif($bansos->status == 'Disetujui') bg-green-100 text-green-800
                                                        @elseif($bansos->status == 'Ditolak') bg-red-100 text-red-800
                                                        @elseif($bansos->status == 'Sudah Diterima') bg-purple-100 text-purple-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ $bansos->status }}
                                                    </span>
                                                </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <div>Pengajuan: {{ $bansos->tanggal_pengajuan->format('d M Y') }}</div>
                                                @if($bansos->tanggal_penerimaan)
                                                    <div class="mt-1 text-green-600">Diterima: {{ $bansos->tanggal_penerimaan->format('d M Y') }}</div>
                                                @endif
                                                @if($bansos->tenggat_pengambilan && in_array($bansos->status, ['Disetujui', 'Diverifikasi']))
                                                    <div class="mt-1 text-red-600">Tenggat: {{ $bansos->tenggat_pengambilan->format('d M Y') }}</div>
                                                @endif
                                                </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                @if($bansos->lokasi_pengambilan && in_array($bansos->status, ['Disetujui', 'Diverifikasi']))
                                                    <div class="flex items-center text-blue-700">
                                                        <svg class="flex-shrink-0 w-4 h-4 text-blue-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        {{ $bansos->lokasi_pengambilan }}
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                                </td>
                                            <td class="px-6 py-4 text-sm font-medium">
                                                    <a href="{{ route('warga.bansos.detail', $bansos->id) }}"
                                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-emerald-600 shadow-sm hover:from-emerald-600 hover:to-emerald-700 hover:shadow transition-all duration-300">
                                                    <span>Detail</span>
                                                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                    </svg>
                                                </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                {{ $bansosList->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            @else
            <div class="bg-white rounded-lg shadow-sm border border-yellow-100 overflow-hidden">
                <div class="bg-yellow-50 px-4 py-3 border-b border-yellow-100">
                    <h3 class="text-sm font-medium text-yellow-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        Verifikasi Data Diperlukan
                    </h3>
                </div>
                <div class="p-4">
                    <p class="text-sm text-gray-600">
                        Untuk mengajukan bantuan sosial, akun Anda harus terhubung dengan data penduduk. Silakan hubungi admin desa untuk verifikasi data Anda.
                    </p>
                    <div class="mt-4">
                        <a href="#" class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-sm hover:from-yellow-500 hover:to-yellow-600 hover:shadow transition-all duration-300">
                            Pelajari Lebih Lanjut
                            <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                        </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif
        </div>
</div>
