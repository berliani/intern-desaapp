<div class="min-h-screen">
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <h2 class="text-lg sm:text-xl font-medium text-gray-800 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ __('Detail UMKM') }}
                </h2>
            </div>
            <a href="{{ route('warga.umkm') }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-emerald-600 hover:text-emerald-800 border border-emerald-200 hover:bg-emerald-50 transition-all duration-300">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="rounded-xl bg-green-50 p-4 text-sm text-green-800 shadow-sm border border-green-100 animate__animated animate__fadeIn" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            @if($detailUmkm)
                <!-- Main UMKM Detail Card -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
                    <!-- Header Section with Status and Actions -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $detailUmkm->is_verified ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-yellow-100 text-yellow-800 border border-yellow-200' }}">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $detailUmkm->is_verified ? 'Terverifikasi' : 'Menunggu Verifikasi' }}
                                </span>

                                @if($detailUmkm->kategori)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium shadow-sm
                                        {{ $detailUmkm->kategori == 'Kuliner' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                                        {{ $detailUmkm->kategori == 'Kerajinan' ? 'bg-blue-100 text-blue-800 border border-blue-200' : '' }}
                                        {{ $detailUmkm->kategori == 'Fashion' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                                        {{ $detailUmkm->kategori == 'Pertanian' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : '' }}
                                        {{ $detailUmkm->kategori == 'Jasa' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}
                                        {{ $detailUmkm->kategori == 'Lainnya' || !$detailUmkm->kategori ? 'bg-gray-100 text-gray-800 border border-gray-200' : '' }}">
                                        {{ $detailUmkm->kategori }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex gap-2">
                                <button wire:click="editForm({{ $detailUmkm->id }})"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-emerald-600 hover:text-emerald-800 border border-emerald-200 hover:bg-emerald-50 transition-all duration-300">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>
                                <button
                                    onclick="confirmDelete({{ $detailUmkm->id }}, '{{ $detailUmkm->nama_usaha }}')"
                                    type="button"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-red-600 hover:text-red-800 border border-red-200 hover:bg-red-50 transition-all duration-300">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-6">
                        <!-- Image Column -->
                        <div class="relative h-80 md:h-[380px] bg-gray-100 overflow-hidden">
                            @if($detailUmkm->foto_usaha)
                                <img src="{{ Storage::url($detailUmkm->foto_usaha) }}"
                                     alt="{{ $detailUmkm->nama_usaha }}"
                                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                    <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Detail Column -->
                        <div class="p-6">
                            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $detailUmkm->nama_usaha }}</h1>

                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-gray-500">Produk/Layanan</h3>
                                        <p class="mt-1 text-base text-gray-900">{{ $detailUmkm->produk }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-gray-500">Kontak WhatsApp</h3>
                                        <p class="mt-1 text-base text-gray-900">{{ $detailUmkm->kontak_whatsapp }}</p>
                                    </div>
                                </div>

                                @if($detailUmkm->lokasi)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <svg class="h-6 w-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-gray-500">Lokasi Usaha</h3>
                                        <p class="mt-1 text-base text-gray-900">{{ $detailUmkm->lokasi }}</p>
                                    </div>
                                </div>
                                @endif

                                <div class="pt-6">
                                    <a href="https://wa.me/{{ $detailUmkm->kontak_whatsapp }}" target="_blank"
                                        class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-green-500 hover:bg-green-600 rounded-lg transition-all duration-300 shadow-sm hover:shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        Hubungi via WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($detailUmkm->deskripsi)
                    <div class="p-6 border-t border-gray-100">
                        <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            Deskripsi Usaha
                        </h3>
                        <div class="prose max-w-none text-gray-700">
                            {{ $detailUmkm->deskripsi }}
                        </div>
                    </div>
                    @endif

                    <div class="p-4 bg-gray-50 border-t border-gray-100">
                        <p class="text-sm text-gray-500 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Terdaftar pada {{ $detailUmkm->created_at->format('d M Y') }} · Diperbarui {{ $detailUmkm->updated_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl p-8 shadow-sm border border-red-100">
                    <div class="text-center">
                        <svg class="mx-auto h-16 w-16 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Data UMKM tidak ditemukan</h3>
                        <p class="mt-2 text-sm text-gray-500">UMKM yang Anda cari tidak tersedia atau telah dihapus.</p>
                        <div class="mt-6">
                            <a href="{{ route('warga.umkm') }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 shadow-sm hover:shadow-md transition-all duration-300">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Kembali ke Daftar UMKM
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Sweet Alert untuk konfirmasi delete -->
<script>
function confirmDelete(id, nama) {
    Swal.fire({
        title: '<span class="text-gray-800">Hapus UMKM?</span>',
        html: `<p class="text-gray-600">Anda yakin ingin menghapus UMKM <b class="text-gray-800">${nama}</b>? <br>Tindakan ini tidak dapat dibatalkan.</p>`,
        icon: 'warning',
        iconColor: '#f87171',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        buttonsStyling: true,
        customClass: {
            popup: 'rounded-xl shadow-xl border border-gray-200',
            confirmButton: 'rounded-lg text-sm font-medium py-2 px-4',
            cancelButton: 'rounded-lg text-sm font-medium py-2 px-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Panggil method Livewire
            @this.deleteUmkm(id);
        }
    });
}

document.addEventListener('livewire:initialized', () => {
    @this.on('showAlert', (data) => {
        Swal.fire({
            icon: data.icon || 'success',
            title: data.title || 'Berhasil!',
            text: data.text || 'Operasi berhasil dilakukan.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            customClass: {
                popup: 'rounded-lg shadow-lg border border-gray-200',
                title: 'text-gray-800',
                content: 'text-gray-700'
            }
        }).then(() => {
            if (data.icon === 'success' && data.text.includes('berhasil dihapus')) {
                // Redirect ke halaman list UMKM setelah penghapusan berhasil
                window.location.href = '{{ route("warga.umkm") }}';
            }
        });
    });
});
</script>