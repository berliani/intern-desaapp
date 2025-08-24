<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\IMS\EnkripsiIMS;

class Penduduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penduduk';

    protected $fillable = [
        'nik',
        'kk',
        'nama',
        'alamat',
        'rt_rw',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'pekerjaan',
        'pendidikan',
        'id_desa',
        'kepala_keluarga',
        'kepala_keluarga_id',
        'user_id',
        'no_hp',
        'email',
        'golongan_darah',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'kepala_keluarga' => 'boolean',
        'jenis_kelamin' => 'string',
    ];

    // Helper method untuk jenis kelamin
    public function getJenisKelaminLabelAttribute()
    {
        return match($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => 'Tidak Diketahui'
        };
    }

    // Relasi-relasi tetap sama seperti sebelumnya
    public function desa(): BelongsTo
    {
        return $this->belongsTo(ProfilDesa::class, 'id_desa');
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

    public function kartuKeluarga()
    {
        return $this->belongsTo(KartuKeluarga::class, 'kk', 'nomor_kk');
    }

    public function isKepalaKeluarga(): bool
    {
        return $this->kepala_keluarga;
    }

    // --- MULAI BLOK KODE ENKRIPSI YANG HILANG ---
    private static ?EnkripsiIMS $encryptorInstance = null;
    private static function getEncryptor(): EnkripsiIMS {
        if (self::$encryptorInstance === null) {
            $key = hex2bin(env('IMS_ENCRYPTION_KEY'));
            if (!$key) { throw new \Exception("Kunci enkripsi tidak valid."); }
            self::$encryptorInstance = new EnkripsiIMS($key);
        }
        return self::$encryptorInstance;
    }
    private static function getPepperKey(): string {
        $key = hex2bin(env('IMS_PEPPER_KEY'));
        if (!$key) { throw new \Exception("Pepper key tidak valid."); }
        return $key;
    }

    // NIK
    public function setNikAttribute($value) {
        if (!empty($value)) {
            $this->attributes['nik_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['nik_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
            if (strlen($value) >= 8) {
                $prefix = substr($value, 0, 8);
                $this->attributes['nik_prefix_hash'] = hash_hmac('sha256', $prefix, self::getPepperKey());
            }
        }
    }
    public function getNikAttribute($value) {
        $encrypted = $this->attributes['nik_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : $value;
    }

    // KK
    public function setKkAttribute($value) {
        if (!empty($value)) {
            $this->attributes['kk_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['kk_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }
    // get KK
    public function getKkAttribute($value) {
        $encrypted = $this->attributes['kk_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : $value;
    }

    // No. HP
    public function setNoHpAttribute($value) {
        if (!empty($value)) {
            $this->attributes['no_hp_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['no_hp_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }
    public function getNoHpAttribute($value) {
        $encrypted = $this->attributes['no_hp_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : $value;
    }

    // Email
    public function setEmailAttribute($value) {
        if (!empty($value)) {
            $this->attributes['email_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['email_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }
    public function getEmailAttribute($value) {
        $encrypted = $this->attributes['email_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : $value;
    }
    // --- AKHIR BLOK KODE ENKRIPSI ---
}