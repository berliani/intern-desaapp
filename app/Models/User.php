<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasTenants;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Support\Collection;
use Filament\Panel;
use App\IMS\EnkripsiIMS;
use Filament\Panel;

class User extends Authenticatable implements HasTenants
class User extends Authenticatable implements HasTenants
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = [
        'name',
        'username',
        'company_id',
        'penduduk_id',
        'password',
        'password',
        'profile_photo_path',

        // Kolom-kolom baru yang dienkripsi dan di-hash
        'email_encrypted',
        'email_search_hash',
        'telepon_encrypted',
        'telepon_search_hash',
        'nik_encrypted',
        'nik_search_hash',
        'nik_prefix_hash',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function penduduk(): HasOne
    {
        return $this->hasOne(Penduduk::class, 'user_id');
    }

    public function verifikasiPenduduk(): HasOne
    {
        return $this->hasOne(VerifikasiPenduduk::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function getTenants(Panel $panel): Collection
    {
        return collect([$this->company]);
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->company_id === $tenant->id;
    }

    private static ?EnkripsiIMS $encryptorInstance = null;
    private static ?string $pepperKey = null;

    private static function getEncryptor(): EnkripsiIMS
    {
        if (self::$encryptorInstance === null) {
            $encryptionKey = hex2bin(env('IMS_ENCRYPTION_KEY'));
            if (!$encryptionKey) { throw new \Exception("Kunci enkripsi IMS_ENCRYPTION_KEY tidak valid."); }
            self::$encryptorInstance = new EnkripsiIMS($encryptionKey);
        }
        return self::$encryptorInstance;
    }

    private static function getPepperKey(): string
    {
        if (self::$pepperKey === null) {
            $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
            if (!$pepperKey) { throw new \Exception("Pepper key IMS_PEPPER_KEY tidak valid."); }
            self::$pepperKey = $pepperKey;
        }
        return self::$pepperKey;
    }

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
    public function getNikAttribute($value)
    {
        $encrypted = $this->attributes['nik_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : $value;
    }

    public function setEmailAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['email_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['email_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }
    public function getEmailAttribute($value)
    {
        $encrypted = $this->attributes['email_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : $value;
    }

    public function setTeleponAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['telepon_encrypted'] = self::getEncryptor()->encrypt($value);
            $this->attributes['telepon_search_hash'] = hash_hmac('sha256', $value, self::getPepperKey());
        }
    }
    public function getTeleponAttribute($value)
    {
        $encrypted = $this->attributes['telepon_encrypted'] ?? null;
        return $encrypted ? self::getEncryptor()->decrypt($encrypted) : $value;
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            if ($user->penduduk) {
                $user->penduduk->delete();
            }

            if ($user->verifikasiPenduduk) {
                $user->verifikasiPenduduk->delete();
            }
        });
    }
}
