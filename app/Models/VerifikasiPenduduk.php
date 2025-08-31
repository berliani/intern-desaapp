<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\IMS\EnkripsiIMS;
use Carbon\Carbon;

class VerifikasiPenduduk extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel database yang digunakan oleh model.
     */
    protected $table = 'verifikasi_penduduk';

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     */
    protected $fillable = [
        'user_id',
        'penduduk_id',
        'desa_id',
        'company_id',
        'kepala_keluarga_id',
        'nama',
        'rt',
        'rw',
        'tempat_lahir',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'kepala_keluarga',
        'pekerjaan',
        'pendidikan',
        'status',
        'catatan',
        'golongan_darah',

        // Atribut virtual untuk memicu mutator enkripsi
        'nik',
        'kk',
        'tanggal_lahir',
        'email',
        'no_hp',
        'alamat',

        // Kolom di database untuk data terenkripsi dan hash
        'nik_encrypted', 'nik_search_hash', 'nik_prefix_hash',
        'kk_encrypted', 'kk_search_hash',
        'tanggal_lahir_encrypted', 'tanggal_lahir_search_hash',
        'email_encrypted', 'email_search_hash',
        'no_hp_encrypted', 'no_hp_search_hash',
        'alamat_encrypted', 'alamat_search_hash',
    ];

    /**
     * Tipe data atribut yang akan di-cast secara otomatis.
     */
    protected $casts = [
        'kepala_keluarga' => 'boolean',
    ];

    // --- RELASI ELOQUENT ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(ProfilDesa::class, 'desa_id');
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function kepalaKeluarga(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'kepala_keluarga_id');
    }

    // --- ACCESSOR TAMBAHAN ---

    public function getJenisKelaminLabelAttribute()
    {
        return match ($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => 'Tidak Diketahui'
        };
    }

    // --- BLOK KODE ENKRIPSI ---
    private static ?EnkripsiIMS $encryptorInstance = null;
    private static ?string $pepperKey = null;

    private static function getEncryptor(): EnkripsiIMS
    {
        if (self::$encryptorInstance === null) {
            $key = hex2bin(env('IMS_ENCRYPTION_KEY'));
            if (!$key) {
                throw new \Exception("Kunci enkripsi IMS_ENCRYPTION_KEY tidak valid.");
            }
            self::$encryptorInstance = new EnkripsiIMS($key);
        }
        return self::$encryptorInstance;
    }

    private static function getPepperKey(): string
    {
        if (self::$pepperKey === null) {
            $key = hex2bin(env('IMS_PEPPER_KEY'));
            if (!$key) {
                throw new \Exception("Pepper key IMS_PEPPER_KEY tidak valid.");
            }
            self::$pepperKey = $key;
        }
        return self::$pepperKey;
    }

    /**
     * Helper statis untuk membuat search hash, agar konsisten dengan Model lain.
     */
    public static function hashForSearch(string $value): string
    {
        return hash_hmac('sha256', $value, self::getPepperKey());
    }

    // --- MUTATOR & ACCESSOR UNTUK DATA SENSITIF ---

    // NIK
    public function setNikAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['nik_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['nik_search_hash'] = self::hashForSearch($value);
            if (strlen($value) >= 8) {
                $this->attributes['nik_prefix_hash'] = self::hashForSearch(substr($value, 0, 8));
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
            return null;
        }
    }

    // KK
    public function setKkAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['kk_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['kk_search_hash'] = self::hashForSearch($value);
        }
    }
    public function getKkAttribute()
    {
        $encrypted = $this->attributes['kk_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Tanggal lahir
    public function setTanggalLahirAttribute($value)
    {
        if (!empty($value)) {
            $date = Carbon::parse($value)->format('Y-m-d');
            $this->attributes['tanggal_lahir_encrypted'] = self::getEncryptor()->encrypt($date);
            $this->attributes['tanggal_lahir_search_hash'] = self::hashForSearch($date);
        }
    }
    public function getTanggalLahirAttribute()
    {
        $encrypted = $this->attributes['tanggal_lahir_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    // No HP
    public function setNoHpAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['no_hp_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['no_hp_search_hash'] = self::hashForSearch($value);
        }
    }
    public function getNoHpAttribute()
    {
        $encrypted = $this->attributes['no_hp_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Email
    public function setEmailAttribute($value)
    {
        if (!empty($value)) {
            $lowerValue = strtolower($value);
            $this->attributes['email_encrypted'] = self::getEncryptor()->encrypt($lowerValue);
            $this->attributes['email_search_hash'] = self::hashForSearch($lowerValue);
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
            $this->attributes['alamat_search_hash'] = self::hashForSearch($value);
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
}
