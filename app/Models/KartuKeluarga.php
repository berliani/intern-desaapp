<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\IMS\EnkripsiIMS;


class KartuKeluarga extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kartu_keluarga';

    protected $fillable = [
        'desa_id',
        'nomor_kk',
        'alamat',
        'rt',
        'rw',
        'kepala_keluarga_id',

        // Kolom-kolom baru yang dienkripsi dan di-hash
        'alamat_encrypted',
        'alamat_search_hash',
        'nomor_kk_encrypted',
        'nomor_kk_search_hash',
    ];

    protected $appends = ['nomor_kk', 'alamat'];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(ProfilDesa::class, 'desa_id');
    }

    public function kepalaKeluarga(): BelongsTo
    {
        return $this->belongsTo(Penduduk::class, 'kepala_keluarga_id');
    }

    // 
    public function anggotaKeluarga(): HasMany
    {
        // --- DIPERBARUI: Menggunakan hash untuk relasi ---
        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
        $kkSearchHash = hash_hmac('sha256', $this->nomor_kk, $pepperKey);

        return $this->hasMany(Penduduk::class, 'kk_search_hash', 'kk_search_hash')
            ->where('kk_search_hash', $kkSearchHash);
    }

    // --- BLOK ENKRIPSI ---
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

    // Nomor KK terenkripsi + hash
    public function setNomorKkAttribute($value) {
        if (!empty($value)) {
            $this->attributes['nomor_kk_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['nomor_kk_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }

    public function getNomorKkAttribute() {
        $encrypted = $this->attributes['nomor_kk_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return 'Gagal Dekripsi';
        }
    }

    // Alamat Accessor & Mutator ---
    public function setAlamatAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['alamat_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['alamat_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }

    public function getAlamatAttribute()
    {
        $encrypted = $this->attributes['alamat_encrypted'] ?? null;
        if (!$encrypted) return null;
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return 'Gagal Dekripsi';
        }
    }
    // --- AKHIR BLOK ENKRIPSI ---

}