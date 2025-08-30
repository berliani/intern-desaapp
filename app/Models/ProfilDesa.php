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
        'created_by',
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'thumbnails',
        'logo',
        'alamat',
        'website',
        'visi',
        'misi',
        'sejarah',
        'company_id',
        'created_by',
        // luas_wilayah dihapus

        // Atribut virtual untuk form (telepon & email akan dienkripsi oleh mutator)
        'telepon',
        'email',
        'alamat',

        // Kolom-kolom baru yang dienkripsi dan di-hash
        'email_encrypted',
        'email_search_hash',
        'telepon_encrypted',
        'telepon_search_hash',
        'alamat_encrypted',
        'alamat_search_hash',
    ];

    // agar accessor selalu dijalankan saat data model diubah menjadi array.
    protected $appends = ['telepon', 'email', 'alamat'];

    // Cast thumbnails sebagai array
    protected $casts = [
        'thumbnails' => 'array',
        'sejarah' => 'string',
    ];

            // --- BLOK KODE ENKRIPSI ---
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

    // Telepon Accessor & Mutator
    public function setTeleponAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['telepon_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['telepon_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }

    public function getTeleponAttribute()
    {
        $encrypted = $this->attributes['telepon_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return 'Gagal Dekripsi';
        }
    }

    // Email Accessor & Mutator
    public function setEmailAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['email_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['email_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }

    public function getEmailAttribute()
    {
        $encrypted = $this->attributes['email_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return 'Gagal Dekripsi';
        }
    }

    // Alamat Accessor & Mutator 
    public function setAlamatAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['alamat_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['alamat_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }

    public function getAlamatAttribute()
    {
        $encrypted = $this->attributes['alamat_encrypted'] ?? $this->attributes['alamat'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            // Fallback to plain text if decryption fails (for old data)
            return $this->attributes['alamat_encrypted'] ? 'Gagal Dekripsi' : $this->attributes['alamat'];
        }
    }
    // --- AKHIR ENKRIPSI ---

    // Relasi-relasi
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

        // Konversi luas ke km²
        $luasKm2 = $batasWilayah->luas_wilayah / 1000000;

        // Hitung kepadatan
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
}
