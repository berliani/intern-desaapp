<?php

namespace App\Models;

use App\Models\Bansos;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\KeuanganDesa;
use App\Models\LayananDesa;

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
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the profile for the company.
     */
    public function profilDesa(): HasOne
    public function profilDesa(): HasOne
    {
        // Pastikan foreign key 'company_id' di tabel 'profil_desa' sudah benar.
        return $this->hasOne(ProfilDesa::class, 'company_id');
    }

    /**
     * Get all of the penduduks for the Company.
     */
    public function penduduks(): HasMany
    {
        return $this->hasMany(Penduduk::class, 'company_id');
    }


    public function pengaduans(): HasManyThrough
    {

        return $this->hasManyThrough(Pengaduan::class, Penduduk::class);
    }
    public function layananDesas(): HasManyThrough
{

    return $this->hasManyThrough(LayananDesa::class, ProfilDesa::class);
}
 public function keauanganDesas(): HasManyThrough
{

    return $this->hasManyThrough(KeuanganDesa::class, ProfilDesa::class);
}
  public function keuanganDesas(): HasMany
    {
        return $this->hasMany(KeuanganDesa::class);
    }
 public function bansos(): HasManyThrough
{

    return $this->hasManyThrough(Bansos::class, ProfilDesa::class);
}
}
