<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasName;

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
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the profile for the company.
     */
    public function profilDesa()
    {
        return $this->hasOne(ProfilDesa::class);
    }
}
