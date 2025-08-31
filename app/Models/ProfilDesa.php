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

    /**
     * Nama tabel database yang digunakan oleh model.
     */
    protected $table = 'profil_desa';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     * Duplikat telah dihapus untuk kebersihan kode.
     */
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
        'website',
        'visi',
        'misi',
        'sejarah',
        // Atribut virtual untuk form (akan dienkripsi oleh mutator)
        'telepon',
        'email',
        'alamat',
        // Kolom di database untuk data terenkripsi dan hash
        'email_encrypted',
        'email_search_hash',
        'telepon_encrypted',
        'telepon_search_hash',
        'alamat_encrypted',
        'alamat_search_hash',
    ];

    /**
     * Menambahkan accessor ke representasi array/JSON model.
     * Ini memastikan 'telepon', 'email', 'alamat' yang terdekripsi selalu ada saat data diakses.
     */
    protected $appends = ['telepon', 'email', 'alamat'];

    /**
     * Tipe data atribut yang akan di-cast secara otomatis.
     */
    protected $casts = [
        'thumbnails' => 'array',
    ];

    // --- BLOK KODE ENKRIPSI ---
    // Implementasi yang benar dipertahankan, duplikat dihapus.
    private static ?EnkripsiIMS $encryptorInstance = null;
    private static ?string $pepperKey = null;

    private static function getEncryptor(): EnkripsiIMS
    {
        if (self::$encryptorInstance === null) {
            $encryptionKey = hex2bin(env('IMS_ENCRYPTION_KEY'));
            if (!$encryptionKey) {
                throw new \Exception("Kunci enkripsi IMS_ENCRYPTION_KEY tidak valid.");
            }
            self::$encryptorInstance = new EnkripsiIMS($encryptionKey);
        }
        return self::$encryptorInstance;
    }

    private static function getPepperKey(): string
    {
        if (self::$pepperKey === null) {
            $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
            if (!$pepperKey) {
                throw new \Exception("Pepper key IMS_PEPPER_KEY tidak valid.");
            }
            self::$pepperKey = $pepperKey;
        }
        return self::$pepperKey;
    }

    // --- MUTATOR & ACCESSOR UNTUK DATA SENSITIF ---

    // Telepon
    public function setTeleponAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['telepon_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['telepon_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        } else {
            $this->attributes['telepon_encrypted'] = null;
            $this->attributes['telepon_search_hash'] = null;
        }
    }

    public function getTeleponAttribute()
    {
        $encrypted = $this->attributes['telepon_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return null; // Return null jika dekripsi gagal agar aman
        }
    }

    // Email
    public function setEmailAttribute($value)
    {
        if (!empty($value)) {
            $lowerValue = strtolower($value);
            $this->attributes['email_encrypted'] = self::getEncryptor()->encrypt($lowerValue);
            $this->attributes['email_search_hash'] = hash_hmac('sha256', $lowerValue, self::getPepperKey());
        } else {
            $this->attributes['email_encrypted'] = null;
            $this->attributes['email_search_hash'] = null;
        }
    }

    public function getEmailAttribute()
    {
        $encrypted = $this->attributes['email_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Alamat
    public function setAlamatAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['alamat_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['alamat_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        } else {
            $this->attributes['alamat_encrypted'] = null;
            $this->attributes['alamat_search_hash'] = null;
        }
    }

    public function getAlamatAttribute()
    {
        $encrypted = $this->attributes['alamat_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    // --- RELASI ELOQUENT ---

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'desa_id');
    }

    public function layanan(): HasMany
    {
        return $this->hasMany(LayananDesa::class, 'desa_id');
    }

    public function berita(): HasMany
    {
        return $this->hasMany(Berita::class, 'desa_id');
    }

    public function keuangan(): HasMany
    {
        return $this->hasMany(KeuanganDesa::class, 'desa_id');
    }

    public function inventaris(): HasMany
    {
        return $this->hasMany(Inventaris::class, 'desa_id');
    }

    public function pengaduan(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'desa_id');
    }

    public function umkm(): HasMany
    {
        return $this->hasMany(Umkm::class, 'desa_id');
    }

    public function batasWilayahPotensi(): HasOne
    {
        return $this->hasOne(BatasWilayahPotensi::class, 'profil_desa_id');
    }

    public function strukturPemerintahan(): HasOne
    {
        return $this->hasOne(StrukturPemerintahan::class, 'profil_desa_id');
    }

    // --- HELPER METHODS ---

    /**
     * Mengembalikan nama lengkap wilayah administratif desa.
     */
    public function getNamaLengkap(): string
    {
        $namaDesa = $this->nama_desa ?? '-';
        $kecamatan = $this->kecamatan ?? '-';
        $kabupaten = $this->kabupaten ?? '-';

        return "Desa {$namaDesa}, Kecamatan {$kecamatan}, Kabupaten {$kabupaten}";
    }

    /**
     * Menghitung kepadatan penduduk (jiwa/km²).
     */
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

        // Konversi luas dari m² ke km²
        $luasKm2 = $batasWilayah->luas_wilayah / 1000000;

        return round($jumlahPenduduk / $luasKm2, 2);
    }

    /**
     * PERBAIKAN: Mengambil luas wilayah dari relasi BatasWilayahPotensi
     * dan memformatnya ke dalam m² dan hektar.
     */
    public function getLuasWilayahFormatted(): string
    {
        // Mengambil data luas wilayah dari relasi
        $luasWilayahM2 = $this->batasWilayahPotensi->luas_wilayah ?? null;

        if (!$luasWilayahM2) {
            return '-';
        }

        $luasM2Formatted = number_format($luasWilayahM2, 0, ',', '.');
        $luasHaFormatted = number_format($luasWilayahM2 / 10000, 2, ',', '.');

        return "{$luasM2Formatted} m² ({$luasHaFormatted} ha)";
    }
}

