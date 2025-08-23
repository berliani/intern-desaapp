<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasTenants; 
use Illuminate\Support\Collection;
use Filament\Panel; 

class User extends Authenticatable implements HasTenants 
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    
    protected $fillable = [
        'name',
        'username',
        'email',
        'telepon', 
        'password',
        'company_id',
        'penduduk_id',
        'nik',
        'profile_photo_path',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',

            //  'email' => 'encrypted',
            // 'telepon' => 'encrypted',
        ];
    }

    /**
     * Get the penduduk associated with the user.
     */
    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'penduduk_id');
    }

    /**
     * Get the company associated with the user.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // Method yang dibutuhkan oleh HasTenants (Sudah Benar)
    public function getTenants(Panel $panel): Collection
    {
        return collect([$this->company]);
    }

    // Method ini untuk verifikasi akses (Sudah Benar)
    public function canAccessTenant(Model $tenant): bool
    {
        return $this->company_id === $tenant->id;
    }
}
