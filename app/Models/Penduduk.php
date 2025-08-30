<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\IMS\EnkripsiIMS;
use Carbon\Carbon;

class Penduduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penduduk';

    protected $fillable = [
        'nama',
        'alamat',
        'rt_rw',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten',
        'tempat_lahir',
        'jenis_kelamin',
        'agama',
        'rt',
        'rw',
        'status_perkawinan',
        'pekerjaan',
        'pendidikan',
        'desa_id',
        'kepala_keluarga',
        'kepala_keluarga_id',
        'user_id',
        'golongan_darah',
        'company_id',

        // Atribut virtual untuk diterima dari form
        'nik',
        'kk',
        'tanggal_lahir',
        'no_hp',
        'email',

        // Kolom-kolom baru yang dienkripsi dan di-hash
        'email_encrypted',
        'email_search_hash',
        'telepon_encrypted',
        'telepon_search_hash',
        'nik_encrypted',
        'nik_search_hash',
        'nik_prefix_hash',
        'kk_encrypted',
        'kk_search_hash',
        'tanggal_lahir_encrypted',
        'tanggal_lahir_search_hash',
        'no_hp_encrypted',
        'no_hp_search_hash',
    ];

    protected $casts = [
        'kepala_keluarga' => 'boolean',
        'jenis_kelamin' => 'string',
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

    // NIK Accessor & Mutator
    public function setNikAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['nik_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['nik_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
            if (strlen($value) >= 8) {
                $prefix = substr($value, 0, 8);
                $this->attributes['nik_prefix_hash'] = hash_hmac('sha256', $prefix, self::getPepperKey());
            }
        }
    }

    public function getNikAttribute()
    {
        $encrypted = $this->attributes['nik_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return 'Gagal Dekripsi';
        }
    }

    // KK Accessor & Mutator
    public function setKkAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['kk_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['kk_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }

    public function getKkAttribute()
    {
        $encrypted = $this->attributes['kk_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return 'Gagal Dekripsi';
        }
    }

    // Tanggal Lahir Accessor & Mutator
    public function setTanggalLahirAttribute($value)
    {
        if (!empty($value)) {
            $date = Carbon::parse($value)->format('Y-m-d');
            $this->attributes['tanggal_lahir_encrypted'] = self::getEncryptor()->encrypt($date);
            $this->attributes['tanggal_lahir_search_hash'] = hash_hmac('sha256', $date, self::getPepperKey());
        }
    }

    public function getTanggalLahirAttribute()
    {
        $encrypted = $this->attributes['tanggal_lahir_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return 'Gagal Dekripsi';
        }
    }

    // No HP Accessor & Mutator
    public function setNoHpAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['no_hp_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['no_hp_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }

    public function getNoHpAttribute()
    {
        $encrypted = $this->attributes['no_hp_encrypted'] ?? null;
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

    public function company(): HasOneThrough
    {
        return $this->hasOneThrough(
            Company::class,
            ProfilDesa::class,
            'id',
            'id',
            'desa_id',
            'company_id'
        );
    }

    // Helper method untuk jenis kelamin
    public function getJenisKelaminLabelAttribute()
    {
        return match ($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => 'Tidak Diketahui'
        };
    }

    // Relasi-relasi tetap sama seperti sebelumnya
    public function desa(): BelongsTo
    {
        return $this->belongsTo(ProfilDesa::class, 'desa_id');
    }

    public function kepalaKeluarga()
    {
        return $this->belongsTo(Penduduk::class, 'kepala_keluarga_id');
    }

    public function anggotaKeluarga()
    {
        return $this->hasMany(Penduduk::class, 'kepala_keluarga_id');
    }

    /**
     * Get the user associated with the penduduk.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function bansos(): HasMany
    {
        return $this->hasMany(Bansos::class);
    }

    public function pengaduan(): HasMany
    {
        return $this->hasMany(Pengaduan::class);
    }

    public function umkm(): HasMany
    {
        return $this->hasMany(Umkm::class);
    }

    /**
     * PERBAIKAN: Relasi ke KartuKeluarga sekarang menggunakan search_hash.
     */
    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluarga::class, 'kk_search_hash', 'nomor_kk_search_hash');
    }


    public function isKepalaKeluarga(): bool
    {
        return $this->kepala_keluarga;
    }
}
