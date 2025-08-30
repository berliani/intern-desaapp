<?php

namespace App\Filament\Resources\BansosResource\Widgets;

use App\Models\Bansos;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Filament\Facades\Filament; // Import Filament Facade

class BansosStats extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    // Properti untuk filter
    public ?string $periode = 'semua';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    public function mount(): void
    {
        $this->setPeriodeFilter('semua');
    }

    // Listener untuk event dari Dashboard
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

    // Helper untuk mengatur periode filter
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
            default:
                // Untuk kustom, tanggal akan ditetapkan secara manual
                break;
        }
    }

    protected function getPeriodeDisplayText(): string
    {
        if ($this->periode === 'kustom' && $this->dariTanggal && $this->sampaiTanggal) {
            return Carbon::parse($this->dariTanggal)->format('d/m/Y') . ' - ' . Carbon::parse($this->sampaiTanggal)->format('d/m/Y');
        }

        return match($this->periode) {
            'hari_ini' => 'Hari Ini',
            'minggu_ini' => 'Minggu Ini',
            'bulan_ini' => 'Bulan Ini',
            'tahun_ini' => 'Tahun Ini',
            'bulan_lalu' => 'Bulan Lalu',
            'tahun_lalu' => 'Tahun Lalu',
            'semua', 'semua_waktu' => 'Semua Waktu',
            default => 'Periode'
        };
    }

    protected function getStats(): array
    {
        // Dapatkan ID tenant (desa) yang sedang login
        $tenantId = Filament::getTenant()->id;

        // Query dasar dengan filter tenant
        $query = Bansos::query()->where('company_id', $tenantId);

        // Terapkan filter tanggal jika ada dan bukan 'semua'
        if ($this->dariTanggal && $this->sampaiTanggal && !in_array($this->periode, ['semua', 'semua_waktu'])) {
            $query->whereBetween('tanggal_pengajuan', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay()
            ]);
        }

        // Hitung total per status
        $totalPerStatus = (clone $query)->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Total pengajuan bantuan
        $totalPengajuan = array_sum($totalPerStatus);

        // Total bantuan sudah diterima
        $totalDiterima = $totalPerStatus['Sudah Diterima'] ?? 0;

        // Total bantuan ditolak
        $totalDitolak = $totalPerStatus['Ditolak'] ?? 0;

        // Total bantuan menunggu proses
        $totalMenunggu = ($totalPerStatus['Diajukan'] ?? 0) + ($totalPerStatus['Diverifikasi'] ?? 0) + ($totalPerStatus['Dalam Verifikasi'] ?? 0);

        // Hitung berdasarkan prioritas
        $prioritas = (clone $query)->select('prioritas', DB::raw('count(*) as total'))
            ->groupBy('prioritas')
            ->pluck('total', 'prioritas')
            ->toArray();

        // Hitung bantuan yang ditandai urgent
        $totalUrgent = (clone $query)->where('is_urgent', true)->count();

        // Persentase persetujuan (disetujui & diterima / total pengajuan)
        $persentasePersetujuan = $totalPengajuan > 0
            ? round((($totalPerStatus['Disetujui'] ?? 0) + $totalDiterima) / $totalPengajuan * 100)
            : 0;

        $periodeDisplay = $this->getPeriodeDisplayText();

        return [
            Stat::make('Total Pengajuan', number_format($totalPengajuan, 0, ',', '.'))
                ->description("Periode: {$periodeDisplay}")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Menunggu Proses', number_format($totalMenunggu, 0, ',', '.'))
                ->description('Pengajuan sedang diproses')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Sudah Diterima', number_format($totalDiterima, 0, ',', '.'))
                ->description('Bantuan telah disalurkan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Ditolak', number_format($totalDitolak, 0, ',', '.'))
                ->description('Pengajuan ditolak')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Prioritas Tinggi', number_format($prioritas['Tinggi'] ?? 0, 0, ',', '.'))
                ->description('Termasuk ' . number_format($totalUrgent, 0, ',', '.') . ' kasus urgent')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Persentase Persetujuan', $persentasePersetujuan . '%')
                ->description('Tingkat persetujuan bantuan')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
}
