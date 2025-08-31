<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\IMS\EnkripsiIMS;
use Carbon\Carbon;


class AparatDesa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aparat_desa';

    protected $fillable = [
        'struktur_pemerintahan_id',
        'nama',
        'jabatan',
        'foto',
        'pendidikan',
        'tanggal_lahir',
        'alamat',
        'kontak',
        'periode_jabatan',
        'urutan'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function strukturPemerintahan(): BelongsTo
    {
        return $this->belongsTo(StrukturPemerintahan::class, 'struktur_pemerintahan_id');
    }

    protected static function booted()
    {
        // Memastikan urutan diisi jika tidak ada
        static::creating(function ($aparat) {
            if (!$aparat->urutan) {
                // Tetapkan urutan default berdasarkan jabatan
                $aparat->urutan = static::getDefaultUrutan($aparat->jabatan);
            }
        });

        // Add this to ensure struktur_pemerintahan_id is set
        static::saving(function ($aparat) {
            if (!$aparat->struktur_pemerintahan_id && $aparat->struktur_pemerintahan) {
                $aparat->struktur_pemerintahan_id = $aparat->struktur_pemerintahan->id;
            }
        });
    }

    /**
     * Mendapatkan urutan default berdasarkan jabatan
     */
    private static function getDefaultUrutan(string $jabatan): int
    {
        return match(strtolower(trim($jabatan))) {
            'kepala desa' => 1,
            'sekretaris desa' => 2,
            'bendahara desa', 'kepala urusan keuangan' => 3,
            'kepala urusan umum' => 4,
            'kepala seksi pemerintahan', 'kepala urusan pemerintahan' => 5,
            'kepala seksi kesejahteraan' => 6,
            'kepala seksi pelayanan' => 7,
            default => preg_match('/kepala dusun/i', $jabatan) ? 10 : 15,
        };
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

    // Kontak (encrypt + search_hash)
    public function setKontakAttribute($value) {
        if (!empty($value)) {
            $this->attributes['kontak_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['kontak_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }
    public function getKontakAttribute($value) {
        $encrypted = $this->attributes['kontak_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : $value;
    }

    // Tanggal lahir (hanya encrypt, tanpa search_hash)
    public function setTanggalLahirAttribute($value) {
        if (!empty($value)) {
            $date = Carbon::parse($value)->format('Y-m-d');
            $this->attributes['tanggal_lahir_encrypted'] = self::getEncryptor()->encrypt($date);
        }
    }
    public function getTanggalLahirAttribute($value) {
        $encrypted = $this->attributes['tanggal_lahir_encrypted'] ?? null;
        $decrypted = $encrypted ? self::getEncryptor()->decrypt($encrypted) : $value;
        return $decrypted ? Carbon::parse($decrypted) : null;
    }
    // --- AKHIR BLOK ENKRIPSI ---

}