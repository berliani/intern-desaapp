<div class="min-h-screen">
    <x-slot name="header">
        <div class="flex justify-between items-center py-1">
            <h2 class="text-lg sm:text-xl font-medium text-gray-800">
                {{ __('UMKM Saya') }}
            </h2>
            @if($penduduk)
                <a href="{{ route('warga.umkm.create') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-sm hover:shadow-md hover:from-emerald-600 hover:to-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-300">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah UMKM
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 shadow-sm border border-green-100 animate__animated animate__fadeIn" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>{{ session('message') }}</p>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 shadow-sm border border-red-100 animate__animated animate__fadeIn" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if (session()->has('success-delete'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '{{ session('success-delete') }}',
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
                        });
                    });
                </script>
            @endif

            @if($penduduk)
                <!-- UMKM List Card -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
                    <div class="p-5 border-b border-gray-100">
                        <div class="flex flex-wrap justify-between items-center gap-3">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Daftar UMKM Saya
                            </h3>
                            @if(!$umkmList->isEmpty())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                {{ $umkmList->count() }} bisnis
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        @if($umkmList->isEmpty())
                            <div class="text-center py-12 max-w-md mx-auto">
                                <svg class="mx-auto h-24 w-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <h3 class="mt-5 text-xl font-medium text-gray-900">Belum Ada UMKM</h3>
                                <p class="mt-3 text-sm text-gray-500">Anda belum memiliki UMKM terdaftar. Daftarkan bisnis Anda sekarang untuk mempromosikannya di desa Anda.</p>
                                <div class="mt-6">
                                    <a href="{{ route('warga.umkm.create') }}"
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-sm hover:shadow-md hover:from-emerald-600 hover:to-emerald-700 focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-300">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Tambah UMKM Baru
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($umkmList as $item)
                                    <div class="group bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md hover:border-emerald-200 transition-all duration-300" data-umkm-id="{{ $item->id }}">
                                        <!-- Image Section -->
                                        <div class="relative h-52 overflow-hidden bg-white">
                                            @if($item->foto_usaha)
                                                <img src="{{ Storage::url($item->foto_usaha) }}" alt="{{ $item->nama_usaha }}"
                                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-50 group-hover:bg-gray-100 transition-colors duration-500">
                                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif

                                            <!-- Verification Badge -->
                                            <div class="absolute top-3 right-3">
                                                @if($item->is_verified)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 shadow-sm border border-green-200">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Terverifikasi
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 shadow-sm border border-yellow-200">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Menunggu Verifikasi
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- WhatsApp Button -->
                                            @if($item->kontak_whatsapp)
                                            <div class="absolute bottom-3 right-3">
                                                <a href="https://wa.me/{{ $item->kontak_whatsapp }}" target="_blank"
                                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-green-500 text-white shadow-sm hover:bg-green-600 transition duration-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                                    </svg>
                                                    Hubungi
                                                </a>
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Content Section -->
                                        <div class="p-5">
                                            <div class="flex justify-between items-start mb-3">
                                                <h4 class="text-lg font-semibold text-gray-900 group-hover:text-emerald-700 transition-colors duration-300">{{ $item->nama_usaha }}</h4>

                                                @if($item->kategori)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium shadow-sm
                                                        {{ $item->kategori == 'Kuliner' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                                                        {{ $item->kategori == 'Kerajinan' ? 'bg-blue-100 text-blue-800 border border-blue-200' : '' }}
                                                        {{ $item->kategori == 'Fashion' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}
                                                        {{ $item->kategori == 'Pertanian' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : '' }}
                                                        {{ $item->kategori == 'Jasa' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}
                                                        {{ $item->kategori == 'Lainnya' || !$item->kategori ? 'bg-gray-100 text-gray-800 border border-gray-200' : '' }}
                                                    ">
                                                        {{ $item->kategori }}
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-gray-700 text-sm mb-3 line-clamp-2">{{ $item->produk }}</p>

                                            <div class="space-y-2 mb-3">
                                                @if($item->lokasi)
                                                    <p class="text-gray-600 text-sm flex items-start">
                                                        <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="line-clamp-1">{{ $item->lokasi }}</span>
                                                    </p>
                                                @endif

                                                @if($item->deskripsi)
                                                    <p class="text-gray-600 text-sm line-clamp-2">{{ $item->deskripsi }}</p>
                                                @endif
                                            </div>

                                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                                <a href="{{ route('warga.umkm.detail', $item->id) }}"
                                                   class="inline-flex items-center text-sm font-medium text-emerald-600 hover:text-emerald-800 px-3 py-1.5 border border-emerald-200 rounded-lg transition-colors hover:bg-emerald-50">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    Detail
                                                </a>

                                                <div class="flex space-x-2">
                                                    <a href="{{ route('warga.umkm.edit', $item->id) }}"
                                                       class="text-emerald-600 hover:text-emerald-900 p-2 hover:bg-emerald-50 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                    <button
                                                        onclick="confirmDelete({{ $item->id }}, '{{ $item->nama_usaha }}')"
                                                        type="button"
                                                        class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-lg transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- No Penduduk Data State -->
                <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-yellow-100">
                    <div class="p-5 border-b border-yellow-100">
                        <h3 class="text-sm font-medium text-yellow-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            Verifikasi Data Diperlukan
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-0.5">
                                <svg class="w-8 h-8 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-yellow-800 mb-2">Data Belum Terverifikasi</h4>
                                <p class="text-sm text-gray-600 mb-4">
                                    Untuk mendaftarkan UMKM, akun Anda harus terhubung dengan data penduduk. Silakan hubungi admin desa untuk verifikasi data Anda.
                                </p>
                                <a href="{{ route('warga.profile') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300">
                                    Lengkapi Profil
                                    <svg class="ml-1.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Sweet Alert for Interactive Notifications -->
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
                    // Simpan ID untuk referensi
                    const deletedId = id;

                    // Panggil method Livewire
                    @this.deleteUmkm(deletedId).then(() => {
                        // Hapus elemen dari DOM secara manual
                        const element = document.querySelector(`[data-umkm-id="${deletedId}"]`);
                        if (element) {
                            element.remove();
                        }

                        // Atau refresh komponen Livewire
                        Livewire.dispatch('refresh');
                    });
                }
            });
        }

        // Tambahkan event listener untuk menangkap event deleteUmkm
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('deleteUmkm', (id) => {
                // Panggil method Livewire
                @this.deleteUmkm(id);
            });
        });

        document.addEventListener('livewire:initialized', () => {
            // Listener untuk event refresh
            Livewire.on('refresh', () => {
                // Memaksa Livewire untuk me-render ulang komponen
                Livewire.dispatch('$refresh');
            });

            // Existing listener untuk showAlert
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
                });
            });
        });
    </script>
</div>