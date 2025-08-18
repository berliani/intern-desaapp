<footer class="bg-gradient-to-b from-gray-50 to-gray-100 border-t border-gray-200">
    <!-- Back to top button -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <button id="back-to-top" class="absolute -top-5 right-8 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full p-3 shadow-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-emerald-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>

    <!-- Main footer content -->
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Kolom 1: Tentang Desa (larger size) -->
            <div class="md:col-span-1 lg:col-span-1">
                <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase relative inline-block after:absolute after:w-1/2 after:h-0.5 after:bg-emerald-500 after:bottom-0 after:left-0 pb-1">
                    Tentang Desa
                </h3>
                <div class="mt-5">
                    @if($profilDesa && $profilDesa->logo)
                        <img src="{{ Storage::url($profilDesa->logo) }}"
                             alt="{{ $profilDesa->nama_desa }}"
                             class="h-16 w-auto mb-4 object-contain">
                    @endif
                    <p class="text-lg text-gray-700 font-medium mb-2">
                        {{ $profilDesa->nama_desa ?? 'Desa Digital' }}
                    </p>

                    @if($profilDesa && $profilDesa->telepon)
                        <p class="mt-3 text-sm text-gray-500 flex items-center group">
                            <span class="flex items-center justify-center bg-emerald-100 text-emerald-500 h-8 w-8 rounded-full mr-3 group-hover:bg-emerald-500 group-hover:text-white transition duration-300">
                                <i class="fas fa-phone-alt"></i>
                            </span>
                            <span class="group-hover:text-emerald-600 transition duration-300">{{ $profilDesa->telepon }}</span>
                        </p>
                    @endif
                    @if($profilDesa && $profilDesa->email)
                        <p class="mt-2 text-sm text-gray-500 flex items-center group">
                            <span class="flex items-center justify-center bg-emerald-100 text-emerald-500 h-8 w-8 rounded-full mr-3 group-hover:bg-emerald-500 group-hover:text-white transition duration-300">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <span class="group-hover:text-emerald-600 transition duration-300">{{ $profilDesa->email }}</span>
                        </p>
                    @endif

                    @if($profilDesa && $profilDesa->deskripsi)
                        <p class="mt-4 text-sm text-gray-500 leading-relaxed">
                            {{ \Illuminate\Support\Str::limit($profilDesa->deskripsi, 150) }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Kolom 2: Link Cepat -->
            <div>
                <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase relative inline-block after:absolute after:w-1/2 after:h-0.5 after:bg-emerald-500 after:bottom-0 after:left-0 pb-1">
                    Akses Cepat
                </h3>
                <ul class="mt-5 space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('profil') }}" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                            Profil Desa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('berita') }}" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                            Berita
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('umkm') }}" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                            UMKM
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('statistik') }}" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                            Statistik Desa
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Kolom 3: Layanan (5 daftar layanan) -->
            <div>
                <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase relative inline-block after:absolute after:w-1/2 after:h-0.5 after:bg-emerald-500 after:bottom-0 after:left-0 pb-1">
                    Layanan Desa
                </h3>
                <ul class="mt-5 space-y-3">
                    @php
                        $layananDesa = \App\Models\LayananDesa::latest()->take(5)->get();
                    @endphp

                    @if($layananDesa->count() > 0)
                        @foreach($layananDesa as $layanan)
                            <li>
                                <a href="{{ route('layanan.show', $layanan->id) }}" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                                    <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                                    {{ $layanan->nama_layanan }}
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <a href="{{ route('layanan') }}" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                                <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                                Administrasi Desa
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                                <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                                Pengaduan Warga
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                                <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                                Cek Bantuan Sosial
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                                <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                                Pelayanan Publik
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-base text-gray-600 hover:text-emerald-600 flex items-center transition duration-300 group">
                                <span class="w-2 h-2 bg-emerald-400 rounded-full mr-2 group-hover:w-3 transition-all duration-300"></span>
                                Permohonan Dokumen
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Updated Copyright section -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between items-center">
                <p class="text-sm text-gray-500 mb-4 md:mb-0">
                    Hak Cipta &copy; 2007-{{ date('Y') }} PT Intermedia Solusindo Global
                </p>
             
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('back-to-top');

    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0');
            backToTopButton.classList.add('opacity-100');
        } else {
            backToTopButton.classList.remove('opacity-100');
            backToTopButton.classList.add('opacity-0');
        }
    });

    // Scroll to top when button is clicked
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>