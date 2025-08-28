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

    protected $table = 'verifikasi_penduduk';

    protected $fillable = [
        'company_id',
        'user_id',
        'penduduk_id',
        'id_desa',
        'nik_encrypted',
        'nik_search_hash',
        'nik_prefix_hash',
        'kk_encrypted',
        'kk_search_hash',
        'kepala_keluarga_id',
        'nama',
        'alamat',
        'rt_rw',
        'tempat_lahir',
        'tanggal_lahir_encrypted',
        'tanggal_lahir_search_hash',
        'jenis_kelamin',
        'agama',
        'status_perkawinan',
        'kepala_keluarga',
        'pekerjaan',
        'pendidikan',
        'status',
        'catatan',
        'email_encrypted',
        'email_search_hash',
        'no_hp_encrypted',
        'no_hp_search_hash',
        'golongan_darah',
    ];

    protected $casts = [
        'kepala_keluarga' => 'integer',
        'jenis_kelamin' => 'string',
    ];

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function desa(): BelongsTo { return $this->belongsTo(ProfilDesa::class, 'id_desa'); }
    public function penduduk(): BelongsTo { return $this->belongsTo(Penduduk::class); }
    public function kepalaKeluarga(): BelongsTo { return $this->belongsTo(Penduduk::class, 'kepala_keluarga_id'); }
    public function getJenisKelaminLabelAttribute() { return match($this->jenis_kelamin) { 'L' => 'Laki-laki', 'P' => 'Perempuan', default => 'Tidak Diketahui' }; }

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
        }
    }
    public function getNikAttribute() {
        $encrypted = $this->attributes['nik_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }

    // KK
    public function setKkAttribute($value) {
        if (!empty($value)) {
            $this->attributes['kk_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['kk_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }
    public function getKkAttribute() {
        $encrypted = $this->attributes['kk_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }

    // Tanggal lahir
    public function setTanggalLahirAttribute($value) {
        if (!empty($value)) {
            $date = Carbon::parse($value)->format('Y-m-d');
            $this->attributes['tanggal_lahir_encrypted'] = self::getEncryptor()->encrypt($date);
            $this->attributes['tanggal_lahir_search_hash'] = hash_hmac('sha256', $date, self::getPepperKey());
        }
    }
    public function getTanggalLahirAttribute() {
        $encrypted = $this->attributes['tanggal_lahir_encrypted'] ?? null;
        $decrypted = $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
        return $decrypted ? Carbon::parse($decrypted) : null;
    }

    // No HP
    public function setNoHpAttribute($value) {
        if (!empty($value)) {
            $this->attributes['no_hp_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['no_hp_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }
    public function getNoHpAttribute() {
        $encrypted = $this->attributes['no_hp_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }

    public function setEmailAttribute($value) {
        if (!empty($value)) {
            $this->attributes['email_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['email_search_hash'] = hash_hmac('sha256', strtolower($value), self::getPepperKey());
        }
    }
    public function getEmailAttribute() {
        $encrypted = $this->attributes['email_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : null;
    }
}
