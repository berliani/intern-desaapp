<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ProfilDesa;
use App\Models\Berita;
use App\Models\Umkm;
use App\Models\StrukturPemerintahan;
use App\Models\Company;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Dapatkan host dari URL dan ekstrak subdomainnya
        $host = $request->getHost();
        $subdomain = explode('.', $host)[0];

        // 2. Cari Company berdasarkan subdomain
        $company = Company::where('subdomain', $subdomain)->first();

        // 3. FALLBACK: Jika tidak ada company yang cocok (misal di domain utama), ambil company TERBARU sebagai default.
        if (!$company) {
            $company = Company::latest()->first(); // Diubah dari first() menjadi latest()->first()
        }

        // Jika tidak ada company sama sekali di database, inisialisasi semua data sebagai kosong.
        if (!$company) {
            $profilDesa = null;
            $beritaTerbaru = collect();
            $umkmUnggulan = collect();
            $strukturPemerintahan = null;
            $statistik = ['penduduk' => 0, 'umkm' => 0, 'berita' => 0, 'layanan' => 0];
        } else {
            // 4. Ambil semua data terkait berdasarkan company_id yang sudah ditemukan
            $profilDesa = ProfilDesa::where('company_id', $company->id)->first();

            // Mengambil data lain langsung menggunakan company_id
            $beritaTerbaru = Berita::where('company_id', $company->id)->latest()->take(12)->get();
            $umkmUnggulan = Umkm::where('company_id', $company->id)->where('is_verified', true)->latest()->take(12)->get();

            // Asumsi Struktur Pemerintahan masih terhubung melalui profil_desa_id
            $strukturPemerintahan = $profilDesa ? StrukturPemerintahan::where('profil_desa_id', $profilDesa->id)->first() : null;

            // Mengambil data statistik langsung menggunakan company_id
            $statistik = [
                'penduduk' => \App\Models\Penduduk::where('company_id', $company->id)->count(),
                'umkm' => $umkmUnggulan->count(),
                'berita' => $beritaTerbaru->count(),
                'layanan' => \App\Models\LayananDesa::where('company_id', $company->id)->count()
            ];
        }

        // 5. Kirim data yang sudah difilter ke view
        return view('front.home', compact(
            'profilDesa',
            'strukturPemerintahan',
            'beritaTerbaru',
            'umkmUnggulan',
            'statistik'
        ));
    }
}
