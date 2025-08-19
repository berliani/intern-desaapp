<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Middleware\RoleMiddleware;
use Livewire\Volt\Volt;

// Livewire Components
use App\Livewire\Warga\Dashboard;
use App\Livewire\Warga\Pengaduan;
use App\Livewire\Warga\Bansos;
use App\Livewire\Warga\Umkm;
use App\Livewire\Warga\Profile;
use App\Livewire\Warga\VerifikasiData;
use App\Livewire\Warga\PengajuanBansos;
use App\Livewire\Warga\BansosDetail;

use App\Livewire\Pages\Auth\RegisterDesa;
// use App\Livewire\Pages\Auth\PreRegister;
// use App\Livewire\Pages\Auth\VerifyOtp;

// Export Controllers
use App\Http\Controllers\LayananExportController;
use App\Http\Controllers\KeuanganExportController;
use App\Http\Controllers\InventarisExportController;
use App\Http\Controllers\PendudukExportController;
use App\Http\Controllers\KartuKeluargaExportController;
use App\Http\Controllers\PengaduanExportController;
use App\Http\Controllers\VerifikasiPendudukExportController;
use App\Http\Controllers\UmkmExportController;
use App\Http\Controllers\JenisBansosExportController;
use App\Http\Controllers\BansosExportController;

// Front Controllers
use App\Http\Controllers\FrontController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// Front-end access route for dashboard users
Route::get('/front/home', function () {
    return redirect('/');
})->name('front.home');
// Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
require __DIR__.'/auth.php';

// Dashboard Redirect After Login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        // Admin langsung ke Filament panel
        if (auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            return redirect('/admin');
        }
        // Warga dan unverified ke dashboard warga
        return redirect('/warga/dashboard');
    })->name('dashboard');
    Volt::route('desa/dashboard', 'desa.dashboard')->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| Register-Desa Routes
|--------------------------------------------------------------------------
*/
Route::get('/register-desa', RegisterDesa::class)->name('register-desa');
// Route::get('/register-email', PreRegister::class)->name('pre-register');
// Route::get('/verify-otp', VerifyOtp::class)->name('verify-otp');
// Route::post('/verify-otp', [VerifyOtp::class, 'verify'])->name('verify-otp.post');

/*
|--------------------------------------------------------------------------
| Warga Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Verifikasi Data (Unverified Users)
    Route::get('/verifikasi-data', VerifikasiData::class)
        ->middleware(RoleMiddleware::class.':unverified')
        ->name('verifikasi-data');

    // Warga Area
    Route::prefix('warga')->group(function () {
        // Dashboard (Accessible by both warga and unverified)
        Route::get('/dashboard', Dashboard::class)
            ->middleware(RoleMiddleware::class.':warga|unverified')
            ->name('warga.dashboard');

        // Verified Warga Only Features
        Route::middleware(RoleMiddleware::class.':warga')->group(function () {
            Route::get('/pengaduan', Pengaduan::class)->name('warga.pengaduan');
            Route::get('/bansos', Bansos::class)->name('warga.bansos');
            Route::get('/bansos/pengajuan', PengajuanBansos::class)->name('warga.pengajuan-bansos');
            Route::get('/bansos/{id}', BansosDetail::class)->name('warga.bansos.detail');

            // UMKM Routes - these should load the same component but with different parameters
            Route::get('/umkm', Umkm::class)->name('warga.umkm');
            Route::get('/umkm/create', Umkm::class)->name('warga.umkm.create');
            Route::get('/umkm/{id}/edit', Umkm::class)->name('warga.umkm.edit');
            Route::get('/umkm/{id}', Umkm::class)->name('warga.umkm.detail');

            Route::get('/profile', Profile::class)->name('warga.profile');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Authentication & Logout Routes
|--------------------------------------------------------------------------
*/

// General Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    // Clear intended URL
    request()->session()->forget('url.intended');
    return redirect('/');
})->name('logout');

// Add this new route for Filament logout
Route::post('/admin-logout-redirect', function () {
    auth()->logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('admin.logout.redirect');

/*
|--------------------------------------------------------------------------
| Export Routes
|--------------------------------------------------------------------------
*/

// Layanan Export
Route::prefix('layanan')->name('layanan.')->middleware(['auth'])->group(function () {
    Route::get('/{layanan}/export', [LayananExportController::class, 'export'])
        ->name('export');
    Route::get('/export-all', [LayananExportController::class, 'exportAll'])
        ->name('export.all');
    Route::get('/export-selected', [LayananExportController::class, 'exportSelected'])
        ->name('export.selected');
});

// Keuangan Desa Export
Route::prefix('keuangan')->name('keuangan.')->middleware(['auth'])->group(function () {
    Route::get('/{keuangan}/export', [KeuanganExportController::class, 'export'])
        ->name('export');
    Route::get('/export-all', [KeuanganExportController::class, 'exportAll'])
        ->name('export.all');
    Route::get('/export-selected', [KeuanganExportController::class, 'exportSelected'])
        ->name('export.selected');
});

// Inventaris Export
Route::prefix('inventaris')->name('inventaris.')->middleware(['auth'])->group(function () {
    Route::get('/{inventaris}/export', [InventarisExportController::class, 'export'])
        ->name('export');
    Route::get('/export/all', [InventarisExportController::class, 'exportAll'])
        ->name('export.all');
    Route::get('/export/selected', [InventarisExportController::class, 'exportSelected'])
        ->name('export.selected');
});

// Penduduk Export
Route::prefix('export')->name('export.')->middleware(['auth'])->group(function () {
    Route::get('/penduduk/{penduduk}', [PendudukExportController::class, 'export'])
        ->name('penduduk');
    Route::get('/penduduk-all', [PendudukExportController::class, 'exportAll'])
        ->name('penduduk.all');
    Route::get('/penduduk-selected', [PendudukExportController::class, 'exportSelected'])
        ->name('penduduk.selected');
});

// Kartu Keluarga Export
Route::prefix('kartu-keluarga')->name('kartu-keluarga.')->middleware(['auth'])->group(function () {
    Route::get('/export/{kk}', [KartuKeluargaExportController::class, 'exportSingle'])
        ->name('export');
    Route::get('/export-all', [KartuKeluargaExportController::class, 'exportAll'])
        ->name('export.all');
    Route::get('/export-selected', [KartuKeluargaExportController::class, 'exportSelected'])
        ->name('export.selected');
});

// Pengaduan Export
Route::prefix('pengaduan')->name('pengaduan.')->middleware(['auth'])->group(function () {
    Route::get('/{pengaduan}/export', [PengaduanExportController::class, 'export'])
        ->name('export');
    Route::get('/export-all', [PengaduanExportController::class, 'exportAll'])
        ->name('export.all');
    Route::get('/export-selected', [PengaduanExportController::class, 'exportSelected'])
        ->name('export.selected');
});

// Verifikasi Penduduk Export
Route::prefix('verifikasi')->name('verifikasi.')->middleware(['auth'])->group(function () {
    Route::get('/export-all', [VerifikasiPendudukExportController::class, 'exportAll'])
        ->name('export.all');
    Route::get('/export-selected', [VerifikasiPendudukExportController::class, 'exportSelected'])
        ->name('export.selected');
});

// UMKM Export
Route::prefix('umkm')->name('umkm.')->middleware(['auth'])->group(function () {
    Route::get('/export-all', [UmkmExportController::class, 'exportAll'])
        ->name('export.all');
    Route::get('/export-selected', [UmkmExportController::class, 'exportSelected'])
        ->name('export.selected');
});

// Routes untuk ekspor Jenis Bansos
Route::get('export/jenis-bansos/{jenisBansos}', [JenisBansosExportController::class, 'export'])
    ->name('jenis-bansos.export');
Route::get('export/jenis-bansos', [JenisBansosExportController::class, 'exportAll'])
    ->name('jenis-bansos.export.all');
Route::get('export/jenis-bansos-selected', [JenisBansosExportController::class, 'exportSelected'])
    ->name('jenis-bansos.export.selected');

// Routes untuk ekspor Bansos (PERHATIKAN URUTAN)
Route::middleware(['auth'])->group(function() {
    // 1. PENTING: Rute dengan parameter statis harus didefinisikan sebelum rute dengan parameter dinamis
    Route::get('/export/bansos/selected', [BansosExportController::class, 'exportSelected'])
        ->name('export.bansos.selected');

    Route::get('/export/bansos/all', [BansosExportController::class, 'exportAll'])
        ->name('export.bansos.all');

    // 2. Rute dengan parameter dinamis harus ditempatkan terakhir
    Route::get('/export/bansos/{bansos}', [BansosExportController::class, 'export'])
        ->name('export.bansos.single');
});

/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
*/

// Group all routes with the SecurityHeaders middleware
Route::middleware([\App\Http\Middleware\SecurityHeaders::class])->group(function () {
    // Static content routes - Updated for new controllers
    Route::get('/', [\App\Http\Controllers\Front\HomeController::class, 'index'])->name('home');
    Route::get('/profil', [\App\Http\Controllers\Front\ProfilController::class, 'index'])->name('profil');
    Route::get('/berita', [\App\Http\Controllers\Front\BeritaController::class, 'index'])->name('berita');
    Route::get('/umkm', [\App\Http\Controllers\Front\UmkmController::class, 'index'])->name('umkm');
    Route::get('/layanan', [\App\Http\Controllers\Front\LayananController::class, 'index'])->name('layanan');
    Route::get('/statistik', [\App\Http\Controllers\Front\StatistikController::class, 'index'])->name('statistik');

    // Dynamic content routes with parameter validation
    Route::get('/umkm/{id}', [\App\Http\Controllers\Front\UmkmController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('umkm.show');

    Route::get('/layanan/{id}', [\App\Http\Controllers\Front\LayananController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('layanan.show');

    Route::get('/berita/{id}', [\App\Http\Controllers\Front\BeritaController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('berita.show');

    // API/data endpoints with built-in throttle middleware
    Route::middleware(['throttle:60,1'])->group(function () {
        Route::get('/keuangan/data', [\App\Http\Controllers\API\KeuanganController::class, 'getData'])
            ->name('keuangan.data');

        Route::get('/bansos/data', [\App\Http\Controllers\API\BansosController::class, 'getData'])
            ->name('bansos.data');

        Route::get('/inventaris/data', [\App\Http\Controllers\API\InventarisController::class, 'getData'])
            ->name('inventaris.data');
    });

    // Cache clearing (admin only)
    Route::post('/statistik/clear-cache', [\App\Http\Controllers\Front\StatistikController::class, 'clearDataCaches'])
        ->middleware(['auth', 'role:admin'])
        ->name('statistik.clear-cache');
});
