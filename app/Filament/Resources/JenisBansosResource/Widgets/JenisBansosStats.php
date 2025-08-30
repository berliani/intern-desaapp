<?php

namespace App\Filament\Resources\JenisBansosResource\Widgets;

use App\Models\JenisBansos;
use App\Models\Bansos;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Filament\Facades\Filament; 

class JenisBansosStats extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    // Properti untuk filter
    public ?string $periode = 'semua';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    // Listener untuk filter dari Dashboard
    #[On('filter-changed')]
    public function onDashboardFilterChanged(string $dari_tanggal = '', string $sampai_tanggal = '', string $periode = 'semua'): void
    {
        $this->periode = $periode;

        if ($periode === 'kustom' && $dari_tanggal && $sampai_tanggal) {
            $this->dariTanggal = $dari_tanggal;
            $this->sampaiTanggal = $sampai_tanggal;
        } else {
            $this->setPeriodeFilter($periode);
        }
    }

    public function setPeriodeFilter(string $periode): void
    {
        $this->periode = $periode;

        switch ($periode) {
            case 'hari_ini':
                $this->dariTanggal = now()->toDateString();
                $this->sampaiTanggal = now()->toDateString();
                break;
            case 'minggu_ini':
                $this->dariTanggal = now()->startOfWeek()->toDateString();
                $this->sampaiTanggal = now()->endOfWeek()->toDateString();
                break;
            case 'bulan_ini':
                $this->dariTanggal = now()->startOfMonth()->toDateString();
                $this->sampaiTanggal = now()->endOfMonth()->toDateString();
                break;
            case 'tahun_ini':
                $this->dariTanggal = now()->startOfYear()->toDateString();
                $this->sampaiTanggal = now()->endOfYear()->toDateString();
                break;
            case 'bulan_lalu':
                $this->dariTanggal = now()->subMonth()->startOfMonth()->toDateString();
                $this->sampaiTanggal = now()->subMonth()->endOfMonth()->toDateString();
                break;
            case 'tahun_lalu':
                $this->dariTanggal = now()->subYear()->startOfYear()->toDateString();
                $this->sampaiTanggal = now()->subYear()->endOfYear()->toDateString();
                break;
            case 'semua':
            case 'semua_waktu':
                $this->dariTanggal = null;
                $this->sampaiTanggal = null;
                break;
        }
    }

    protected function getStats(): array
    {
        // Dapatkan ID tenant (desa) yang sedang login
        $tenantId = Filament::getTenant()->id;

        // Query GLOBAL untuk Jenis Bantuan (sesuai permintaan)
        $jenisBansosQuery = JenisBansos::query();

        // Query SPESIFIK TENANT untuk Pengajuan Bantuan (Bansos)
        $bansosQuery = Bansos::query()->where('company_id', $tenantId);


        if ($this->dariTanggal && $this->sampaiTanggal) {
            $startDate = Carbon::parse($this->dariTanggal)->startOfDay();
            $endDate = Carbon::parse($this->sampaiTanggal)->endOfDay();

            // Filter query global berdasarkan tanggal pembuatan
            $jenisBansosQuery->whereBetween('created_at', [$startDate, $endDate]);
            // Filter query tenant berdasarkan tanggal pengajuan
            $bansosQuery->whereBetween('tanggal_pengajuan', [$startDate, $endDate]);
        }

        // Hitung Total Jenis Bantuan (GLOBAL)
        $totalJenisBansos = (clone $jenisBansosQuery)->count();
        $totalJenisBansosAktif = (clone $jenisBansosQuery)->where('is_active', true)->count();
        $persentaseAktif = $totalJenisBansos > 0 ? round(($totalJenisBansosAktif / $totalJenisBansos) * 100) : 0;

        // Hitung Total Pengajuan Bantuan (SPESIFIK TENANT)
        $totalPenerima = (clone $bansosQuery)->count();
        $totalDiterima = (clone $bansosQuery)->where('status', 'Sudah Diterima')->count();
        $persentaseDiterima = $totalPenerima > 0 ? round(($totalDiterima / $totalPenerima) * 100) : 0;

        // Kategori Bantuan Terbanyak (GLOBAL)
        $kategoriQuery = DB::table('jenis_bansos')
            ->select('kategori', DB::raw('count(*) as total'));
        if ($this->dariTanggal && $this->sampaiTanggal) {
            $kategoriQuery->whereBetween('created_at', [Carbon::parse($this->dariTanggal)->startOfDay(), Carbon::parse($this->sampaiTanggal)->endOfDay()]);
        }
        $kategoriTerbanyak = $kategoriQuery->groupBy('kategori')->orderByDesc('total')->first();
        $namaKategoriTerbanyak = $kategoriTerbanyak->kategori ?? 'Tidak ada data';
        $jumlahKategoriTerbanyak = $kategoriTerbanyak->total ?? 0;

        $periodeDisplay = match ($this->periode) {
            'hari_ini' => 'Hari Ini',
            'minggu_ini' => 'Minggu Ini',
            'bulan_ini' => 'Bulan Ini',
            'tahun_ini' => 'Tahun Ini',
            'bulan_lalu' => 'Bulan Lalu',
            'tahun_lalu' => 'Tahun Lalu',
            default => 'Semua Waktu'
        };

        return [
            Stat::make('Total Jenis Bantuan', number_format($totalJenisBansos))
                ->description($totalJenisBansosAktif . ' aktif (' . $persentaseAktif . '%) - ' . $periodeDisplay)
                ->descriptionIcon('heroicon-m-gift')
                ->color('primary'),

            Stat::make('Total Pengajuan di Desa Ini', number_format($totalPenerima))
                ->description(number_format($totalDiterima) . ' sudah diterima (' . $persentaseDiterima . '%) - ' . $periodeDisplay)
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Kategori Terbanyak (Global)', $namaKategoriTerbanyak)
                ->description($jumlahKategoriTerbanyak . ' jenis bantuan - ' . $periodeDisplay)
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('warning'),
        ];
    }
}
