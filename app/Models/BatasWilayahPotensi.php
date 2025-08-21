<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatasWilayahPotensi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'batas_wilayah_potensi';

    protected $fillable = [
        'profil_desa_id',
        'created_by',
        // Informasi Wilayah
        'luas_wilayah',
        'batas_utara',
        'batas_timur',
        'batas_selatan',
        'batas_barat',
        'keterangan_batas',
        // Potensi Desa dalam satu kolom (lebih fleksibel)
        'potensi_desa',
        'keterangan_potensi',
    ];

    protected $casts = [
        'luas_wilayah' => 'float',
        'potensi_desa' => 'array',
    ];

    public function profilDesa(): BelongsTo
    {
        return $this->belongsTo(ProfilDesa::class, 'profil_desa_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
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

    /**
     * Mendapatkan potensi desa berdasarkan kategori
     */
    public function getPotensiByKategori(string $kategori): array
    {
        if (!$this->potensi_desa || !is_array($this->potensi_desa)) {
            return [];
        }

        return array_filter($this->potensi_desa, function($item) use ($kategori) {
            return isset($item['kategori']) && $item['kategori'] === $kategori;
        });
    }

    /**
     * Menghitung jumlah potensi berdasarkan kategori
     */
    public function countPotensiByKategori(string $kategori): int
    {
        if (!$this->potensi_desa || !is_array($this->potensi_desa)) {
            return 0;
        }

        return count(array_filter($this->potensi_desa, function($item) use ($kategori) {
            return isset($item['kategori']) && $item['kategori'] === $kategori;
        }));
    }

    /**
     * Mendapatkan semua kategori potensi yang ada
     */
    public function getAllKategoriPotensi(): array
    {
        if (!$this->potensi_desa || !is_array($this->potensi_desa)) {
            return [];
        }

        $kategori = [];
        foreach ($this->potensi_desa as $potensi) {
            if (isset($potensi['kategori']) && !in_array($potensi['kategori'], $kategori)) {
                $kategori[] = $potensi['kategori'];
            }
        }

        return $kategori;
    }
}
