<?php

namespace App\Models;

use App\IMS\EnkripsiIMS;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifikasiPenduduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'verifikasi_penduduk';

    protected $fillable = [
        'company_id',
        'user_id',
        'penduduk_id',
        'desa_id',
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
        'nik',
        'kk',
        'alamat',
        'tanggal_lahir',
        'email',
        'no_hp',
    ];

    protected $casts = [
        'kepala_keluarga' => 'boolean',
        'tanggal_lahir' => 'date',
    ];

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

    public static function hashForSearch(string $value): string
    {
        return hash_hmac('sha256', $value, self::getPepperKey());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function penduduk(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(ProfilDesa::class, 'desa_id');
    }

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
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }

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
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }

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
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }

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
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }

    public function setEmailAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['email_encrypted'] = self::getEncryptor()->encrypt(strtolower($value));
            $this->attributes['email_search_hash'] = self::hashForSearch(strtolower($value));
        }
    }
    public function getEmailAttribute()
    {
        $encrypted = $this->attributes['email_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }

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
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }
}

