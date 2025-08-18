<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilDesa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profil_desa';

    protected $fillable = [
        'created_by',
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'thumbnails',
        'logo',
        'alamat',
        'telepon',
        'email',
        'website',
        'visi',
        'misi',
        'sejarah',
        'company_id',
        'created_by',
        // luas_wilayah dihapus
    ];

    // Cast thumbnails sebagai array
    protected $casts = [
        'thumbnails' => 'array',
    ];

    // Accessor untuk mendapatkan thumbnail pertama (jika diperlukan untuk kompatibilitas)
    public function getThumbnailAttribute()
    {
        if (isset($this->thumbnails) && is_array($this->thumbnails) && count($this->thumbnails) > 0) {
            return $this->thumbnails[0];
        }

        return null;
    }

    // Relasi-relasi
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'id_desa');
    }

    public function layanan(): HasMany
    {
        return $this->hasMany(LayananDesa::class, 'id_desa');
    }

    public function berita(): HasMany
    {
        return $this->hasMany(Berita::class, 'id_desa');
    }

    public function keuangan(): HasMany
    {
        return $this->hasMany(KeuanganDesa::class, 'id_desa');
    }

    public function inventaris(): HasMany
    {
        return $this->hasMany(Inventaris::class, 'id_desa');
    }

    public function pengaduan(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'id_desa');
    }

    public function umkm(): HasMany
    {
        return $this->hasMany(Umkm::class, 'id_desa');
    }

    public function batasWilayahPotensi(): HasOne
    {
        return $this->hasOne(BatasWilayahPotensi::class, 'profil_desa_id');
    }

    public function strukturPemerintahan(): HasOne
    {
        return $this->hasOne(StrukturPemerintahan::class, 'profil_desa_id');
    }

    /**
     * Mendapatkan nama lengkap desa dengan format "Desa X, Kecamatan Y, Kabupaten Z"
     */
    public function getNamaLengkap(): string
    {
        $namaDesa = $this->nama_desa ?? '-';
        $kecamatan = $this->kecamatan ?? '-';
        $kabupaten = $this->kabupaten ?? '-';

        return "Desa {$namaDesa}, Kecamatan {$kecamatan}, Kabupaten {$kabupaten}";
    }

    /**
     * Menghitung kepadatan penduduk (jika data luas wilayah tersedia dari relasi)
     */
    public function getKepadatanPenduduk(int $jumlahPenduduk = 0): ?float
    {
        $batasWilayah = $this->batasWilayahPotensi;
        if (!$batasWilayah || !$batasWilayah->luas_wilayah || $batasWilayah->luas_wilayah <= 0) {
            return null;
        }

        // Coba ambil jumlah penduduk dari relasi jika belum disediakan
        if ($jumlahPenduduk <= 0) {
            $jumlahPenduduk = $this->penduduk()->count();
            if ($jumlahPenduduk <= 0) {
                return null;
            }
        }

        // Konversi luas ke km²
        $luasKm2 = $batasWilayah->luas_wilayah / 1000000;

        // Hitung kepadatan
        return round($jumlahPenduduk / $luasKm2, 2);
    }

    /**
     * Mendapatkan luas wilayah dalam format yang mudah dibaca
     */
    public function getLuasWilayahFormatted(): string
    {
        if (!$this->luas_wilayah) {
            return '-';
        }

        // Format dalam m²
        $luasM2 = number_format($this->luas_wilayah, 0, ',', '.');

        // Konversi ke hektar
        $luasHa = number_format($this->luas_wilayah / 10000, 2, ',', '.');

        return "{$luasM2} m² ({$luasHa} ha)";
    }
}
