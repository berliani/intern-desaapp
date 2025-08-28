<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\IMS\EnkripsiIMS;

class ProfilDesa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profil_desa';

    protected $fillable = [
        'company_id',
        'created_by',
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'thumbnails',
        'logo',
        'alamat',
        'telepon_encrypted',
        'telepon_search_hash',
        'email_encrypted',
        'email_search_hash',
        'website',
        'visi',
        'misi',
        'sejarah',
    ];

    protected $casts = [
        'thumbnails' => 'array',
    ];

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
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function getNamaLengkap(): string
    {
        $namaDesa = $this->nama_desa ?? '-';
        $kecamatan = $this->kecamatan ?? '-';
        $kabupaten = $this->kabupaten ?? '-';

        return "Desa {$namaDesa}, Kecamatan {$kecamatan}, Kabupaten {$kabupaten}";
    }

    public function getKepadatanPenduduk(int $jumlahPenduduk = 0): ?float
    {
        $batasWilayah = $this->batasWilayahPotensi;
        if (!$batasWilayah || !$batasWilayah->luas_wilayah || $batasWilayah->luas_wilayah <= 0) {
            return null;
        }

        if ($jumlahPenduduk <= 0) {
            $jumlahPenduduk = $this->penduduk()->count();
            if ($jumlahPenduduk <= 0) {
                return null;
            }
        }

        $luasKm2 = $batasWilayah->luas_wilayah / 1000000;

        return round($jumlahPenduduk / $luasKm2, 2);
    }


    public function getLuasWilayahFormatted(): string
    {
        if (!$this->luas_wilayah) {
            return '-';
        }


        $luasM2 = number_format($this->luas_wilayah, 0, ',', '.');


        $luasHa = number_format($this->luas_wilayah / 10000, 2, ',', '.');

        return "{$luasM2} m² ({$luasHa} ha)";
    }

    private static ?EnkripsiIMS $encryptorInstance = null;
    private static function getEncryptor(): EnkripsiIMS
    {
        if (self::$encryptorInstance === null) {
            $key = hex2bin(env('IMS_ENCRYPTION_KEY'));
            if (!$key) {
                throw new \Exception("Kunci enkripsi tidak valid.");
            }
            self::$encryptorInstance = new EnkripsiIMS($key);
        }
        return self::$encryptorInstance;
    }

    public function setTeleponAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['telepon_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['telepon_search_hash'] = hash('sha256', $value);
        }
    }
    public function getTeleponAttribute()
    {
        $encrypted = $this->attributes['telepon_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }

    public function setEmailAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['email_encrypted'] = self::getEncryptor()->encrypt(strtolower($value));
            $this->attributes['email_search_hash'] = hash('sha256', strtolower($value));
        }
    }
    public function getEmailAttribute()
    {
        $encrypted = $this->attributes['email_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }
}
