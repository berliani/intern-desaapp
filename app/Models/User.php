<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Support\Collection;
use Filament\Panel;
use App\IMS\EnkripsiIMS;

class User extends Authenticatable implements HasTenants
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Atribut yang dapat diisi secara massal.
     * PERBAIKAN: Menambahkan 'nik', 'email', dan 'telepon' ke fillable.
     * Ini PENTING agar mutator (fungsi setNikAttribute, dll.) dapat berjalan
     * saat membuat user baru melalui User::create().
     */
    protected $fillable = [
        'name',
        'username',
        'company_id',
        'penduduk_id',
        'password',
        'profile_photo_path',

        // Atribut virtual untuk memicu mutator enkripsi
        'nik',
        'email',
        'telepon',

        // Kolom-kolom di database
        'email_encrypted',
        'email_search_hash',
        'telepon_encrypted',
        'telepon_search_hash',
        'nik_encrypted',
        'nik_search_hash',
        'nik_prefix_hash',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data atribut yang akan di-cast secara otomatis.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi one-to-one ke data Penduduk.
     */
    public function penduduk(): HasOne
    {
        return $this->hasOne(Penduduk::class, 'user_id');
    }

    /**
     * Relasi one-to-one ke data VerifikasiPenduduk.
     */
    public function verifikasiPenduduk(): HasOne
    {
        return $this->hasOne(VerifikasiPenduduk::class, 'user_id');
    }

    /**
     * Relasi ke Company (desa).
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // --- FUNGSI UNTUK FILAMENT MULTI-TENANCY ---

    public function getTenants(Panel $panel): Collection
    {
        return collect([$this->company]);
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->company_id === $tenant->id;
    }

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

    // --- MUTATOR & ACCESSOR (SETTER & GETTER) ---

    // NIK
    public function setNikAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['nik_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['nik_search_hash'] = self::hashForSearch($value);
            if (strlen($value) >= 8) {
                $prefix = substr($value, 0, 8);
                $this->attributes['nik_prefix_hash'] = self::hashForSearch($prefix);
            }
        } else {
            $this->attributes['nik_encrypted'] = null;
            $this->attributes['nik_search_hash'] = null;
            $this->attributes['nik_prefix_hash'] = null;
        }
    }

    public function getNikAttribute()
    {
        $encrypted = $this->attributes['nik_encrypted'] ?? null;
        if (!$encrypted) {
            return null;
        }
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return null; // Lebih aman, return null jika dekripsi gagal
        }
    }

    // Email
    public function setEmailAttribute($value)
    {
        if (!empty($value)) {
            $lowerValue = strtolower($value);
            $this->attributes['email_encrypted'] = self::getEncryptor()->encrypt($lowerValue);
            $this->attributes['email_search_hash'] = self::hashForSearch($lowerValue);
        } else {
            $this->attributes['email_encrypted'] = null;
            $this->attributes['email_search_hash'] = null;
        }
    }

    public function getEmailAttribute()
    {
        $encrypted = $this->attributes['email_encrypted'] ?? null;
        if (!$encrypted) {
            return null;
        }
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Telepon
    public function setTeleponAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['telepon_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['telepon_search_hash'] = self::hashForSearch($value);
        } else {
            $this->attributes['telepon_encrypted'] = null;
            $this->attributes['telepon_search_hash'] = null;
        }
    }

    public function getTeleponAttribute()
    {
        $encrypted = $this->attributes['telepon_encrypted'] ?? null;
        if (!$encrypted) {
            return null;
        }
        try {
            return self::getEncryptor()->decrypt($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Helper statis untuk membuat search hash, agar bisa dipanggil dari luar.
     */
    public static function hashForSearch(string $value): string
    {
        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
        if (!$pepperKey) {
            throw new \Exception("Pepper key IMS_PEPPER_KEY tidak valid.");
        }
        return hash_hmac('sha256', $value, $pepperKey);
    }

    /**
     * Menjalankan aksi saat model event terjadi.
     * Di sini digunakan untuk menghapus data terkait saat user dihapus.
     */
    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            // Hapus data penduduk terkait jika ada
            if ($user->penduduk) {
                $user->penduduk->delete();
            }

            // Hapus data verifikasi penduduk terkait jika ada
            if ($user->verifikasiPenduduk) {
                $user->verifikasiPenduduk->delete();
            }
        });
    }
}

