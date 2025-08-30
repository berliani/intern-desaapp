<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class KeuanganDesa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'keuangan_desa';

    protected $fillable = [
        'desa_id',
        'created_by',
        'jenis',
        'deskripsi',
        'jumlah',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Konstanta untuk jenis transaksi
    const JENIS_PEMASUKAN = 'Pemasukan';
    const JENIS_PENGELUARAN = 'Pengeluaran';

    public function desa(): BelongsTo
    {
        return $this->belongsTo(ProfilDesa::class, 'desa_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope untuk memfilter pemasukan
    public function scopePemasukan(Builder $query): Builder
    {
        return $query->where('jenis', 'pemasukan');
    }

    // Scope untuk memfilter pengeluaran
    public function scopePengeluaran(Builder $query): Builder
    {
        return $query->where('jenis', 'pengeluaran');
    }

    // Scope untuk memfilter berdasarkan periode
    public function scopePeriode(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    // Scope untuk memfilter bulan ini
    public function scopeBulanIni(Builder $query): Builder
    {
        return $query->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year);
    }

    // Scope untuk memfilter tahun ini
    public function scopeTahunIni(Builder $query): Builder
    {
        return $query->whereYear('tanggal', now()->year);
    }

    /**
     * Menyimpan nilai jumlah dalam format yang benar
     */
    public function setJumlahAttribute($value)
    {
        // Bersihkan nilai dari karakter non-angka (titik, koma, spasi, dll)
        $this->attributes['jumlah'] = preg_replace('/[^0-9]/', '', $value);
    }

    // Accessor untuk format Rupiah (untuk tampilan di aplikasi)
    public function getJumlahRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    // Mendapatkan jenis dengan warna
    public function getJenisColorAttribute(): string
    {
        return $this->jenis === self::JENIS_PEMASUKAN ? 'success' : 'danger';
    }

    // Mendapatkan ikon berdasarkan jenis
    public function getJenisIconAttribute(): string
    {
        return $this->jenis === self::JENIS_PEMASUKAN ? 'heroicon-o-arrow-up-circle' : 'heroicon-o-arrow-down-circle';
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        parent::booted();

        // Clear cache when a new record is created
        static::created(function ($keuangan) {
            static::clearKeuanganCache();
        });

        // Clear cache when a record is updated
        static::updated(function ($keuangan) {
            static::clearKeuanganCache();
        });

        // Clear cache when a record is deleted
        static::deleted(function ($keuangan) {
            static::clearKeuanganCache();
        });
    }

    /**
     * Clear all keuangan-related caches
     */
    public static function clearKeuanganCache()
    {
        // Only clear keuangan-related caches, not everything
        $cacheKeys = \Cache::get('keuangan_cache_keys', []);
        foreach ($cacheKeys as $key) {
            \Cache::forget($key);
        }
        \Cache::forget('keuangan_cache_keys');
    }
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
