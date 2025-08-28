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
use Illuminate\Support\Collection;
use Filament\Panel;
use App\IMS\EnkripsiIMS;

class User extends Authenticatable implements HasTenants
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'username',
        'company_id',
        'penduduk_id',
        'password',
        'profile_photo_path',
        'email_encrypted',
        'email_search_hash',
        'telepon_encrypted',
        'telepon_search_hash',
        'nik_encrypted',
        'nik_search_hash',
        'nik_prefix_hash',
    ];

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
    
    private static function getEncryptor(): EnkripsiIMS {
        if (self::$encryptorInstance === null) {
            $key = hex2bin(env('IMS_ENCRYPTION_KEY'));
            if (!$key) { throw new \Exception("Kunci enkripsi tidak valid."); }
            self::$encryptorInstance = new EnkripsiIMS($key);
        }
        return self::$encryptorInstance;
    }

    public function getNikAttribute()
    {
        $encrypted = $this->attributes['nik_encrypted'] ?? null;
        if ($encrypted) {
            try {
                return self::getEncryptor()->decrypt($encrypted);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function getEmailAttribute()
    {
        $encrypted = $this->attributes['email_encrypted'] ?? null;
        if ($encrypted) {
            try {
                return self::getEncryptor()->decrypt($encrypted);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function getTeleponAttribute()
    {
        $encrypted = $this->attributes['telepon_encrypted'] ?? null;
        if ($encrypted) {
            try {
                return self::getEncryptor()->decrypt($encrypted);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
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
