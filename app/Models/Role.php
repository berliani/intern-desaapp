<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends SpatieRole
{
    use HasFactory;

    /**
     * Sebuah role dimiliki oleh satu company (tenant).
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

