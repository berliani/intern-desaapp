<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends Model implements HasName
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'name',
        'subdomain',
        'logo',
        'alamat',
        'telepon',
        'email',
        'website',
        'status',
    ];

    /**
     * Mendapatkan nama yang akan ditampilkan untuk tenant di Filament.
     */
    public function getFilamentName(): string
    {
        return $this->name;
    }

    /**
     * Relasi ke semua user yang dimiliki oleh company ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi ke profil desa yang dimiliki oleh company ini.
     */
    public function profilDesa(): HasOne
    {
        // Pastikan foreign key 'company_id' di tabel 'profil_desa' sudah benar.
        return $this->hasOne(ProfilDesa::class, 'company_id');
    }

    /**
     * Mendapatkan semua penduduk yang dimiliki oleh company ini melalui profil desa.
     * Relasi ini PENTING untuk multi-tenancy Filament.
     */
    public function penduduks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Penduduk::class,    // Model tujuan akhir (Penduduk)
            ProfilDesa::class,  // Model perantara (ProfilDesa)
            'company_id',       // Foreign key di tabel ProfilDesa (yang menghubungkan ke Company)
            'desa_id',          // Foreign key di tabel Penduduk (yang menghubungkan ke ProfilDesa)
            'id',               // Local key di tabel Company
            'id'                // Local key di tabel ProfilDesa
        );
    }

    /**
     * Relasi ke semua pengaduan melalui penduduk.
     */
    public function pengaduans(): HasManyThrough
    {
        return $this->hasManyThrough(Pengaduan::class, Penduduk::class);
    }

    /**
     * Relasi ke semua layanan desa melalui profil desa.
     */
    public function layananDesas(): HasManyThrough
    {
        return $this->hasManyThrough(LayananDesa::class, ProfilDesa::class);
    }

    /**
     * Relasi ke semua data keuangan desa melalui profil desa.
     */
    public function keuanganDesas(): HasManyThrough
    {
        return $this->hasManyThrough(KeuanganDesa::class, ProfilDesa::class);
    }

    /**
     * Relasi ke semua data bansos melalui profil desa.
     */
    public function bansos(): HasManyThrough
    {
        return $this->hasManyThrough(Bansos::class, ProfilDesa::class);
    }
}

