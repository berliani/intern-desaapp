<?php

namespace App\Models;

use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model implements HasName
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subdomain',
    ];

    /**
     * Get the display name for the tenant.
     */
    public function getFilamentName(): string
    {
        return $this->name;
    }

    /**
     * Get the users that belong to the company.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the profile for the company.
     */
    public function profilDesa(): HasOne
    {
        return $this->hasOne(ProfilDesa::class);
    }
}
