<?php

namespace App\Filament\Resources\KeuanganDesaResource\Widgets;

use App\Models\KeuanganDesa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Filament\Facades\Filament;

class KeuanganStats extends BaseWidget
{
    // Refresh data setiap 60 detik
    protected static ?string $pollingInterval = '60s';

    // Nonaktifkan cache widget
    protected int|string|array $columnSpan = 'full';

    // Properti untuk menyimpan filter
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;
    public ?string $periode = 'bulan_ini';

    public function mount(): void
    {
        // Set default ke bulan ini
        $this->setPeriodeFilter('bulan_ini');
    }

    public function setPeriodeFilter(string $periode): void
    {
        $this->periode = $periode;

        if ($periode === 'semua_waktu') {
            $this->dariTanggal = null;
            $this->sampaiTanggal = null;
            return;
        }

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
            default:
                // Untuk kustom, tanggal akan ditetapkan secara manual
                break;
        }
    }

    // Menerima event filter dari KeuanganDesa page
    #[On('keuangan-filter-changed')]
    public function onFilterChanged(string $dari = '', string $sampai = ''): void
    {
        if ($dari && $sampai) {
            $this->dariTanggal = $dari;
            $this->sampaiTanggal = $sampai;
            $this->periode = 'kustom';
        }
    }

    // Menerima event filter dari Dashboard
    #[On('filter-changed')]
    public function onDashboardFilterChanged(string $dari_tanggal = '', string $sampai_tanggal = '', string $periode = 'bulan_ini'): void
    {
        $this->periode = $periode;

        if ($periode === 'kustom' && $dari_tanggal && $sampai_tanggal) {
            $this->dariTanggal = $dari_tanggal;
            $this->sampaiTanggal = $sampai_tanggal;
        } else {
            // Gunakan setPeriodeFilter untuk mengatur tanggal berdasarkan periode
            $this->setPeriodeFilter($periode);
        }
    }

   protected function getStats(): array
    {
        $tenantId = Filament::getTenant()->id;
        $periodeText = $this->getPeriodeDisplayText();

        // Total keseluruhan (sudah difilter per tenant)
        $totalPemasukan = KeuanganDesa::where('company_id', $tenantId)
            ->whereIn('jenis', ['pemasukan', 'Pemasukan', 'PEMASUKAN'])
            ->sum('jumlah');

        $totalPengeluaran = KeuanganDesa::where('company_id', $tenantId)
            ->whereIn('jenis', ['pengeluaran', 'Pengeluaran', 'PENGELUARAN'])
            ->sum('jumlah');

        $saldo = $totalPemasukan - $totalPengeluaran;

        // Query untuk data filter (sudah difilter per tenant)
        $filteredQuery = KeuanganDesa::where('company_id', $tenantId);

        if ($this->periode !== 'semua_waktu' && $this->dariTanggal && $this->sampaiTanggal) {
            $filteredQuery->whereBetween('tanggal', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay()
            ]);
        }

        $pemasukanPeriode = $filteredQuery->clone()->whereIn('jenis', ['pemasukan', 'Pemasukan', 'PEMASUKAN'])->sum('jumlah');
        $pengeluaranPeriode = $filteredQuery->clone()->whereIn('jenis', ['pengeluaran', 'Pengeluaran', 'PENGELUARAN'])->sum('jumlah');
        $saldoPeriode = $pemasukanPeriode - $pengeluaranPeriode;

        // Hitung persentase perbandingan periode dengan total
        $pemasukanPersentase = $totalPemasukan > 0 ? round(($pemasukanPeriode / $totalPemasukan) * 100) : 0;
        $pengeluaranPersentase = $totalPengeluaran > 0 ? round(($pengeluaranPeriode / $totalPengeluaran) * 100) : 0;

        // Format nilai dengan singkatan
        $formattedTotalPemasukan = $this->formatLargeNumber($totalPemasukan);
        $formattedTotalPengeluaran = $this->formatLargeNumber($totalPengeluaran);
        $formattedSaldo = $this->formatLargeNumber($saldo);
        $formattedPemasukanPeriode = $this->formatLargeNumber($pemasukanPeriode);
        $formattedPengeluaranPeriode = $this->formatLargeNumber($pengeluaranPeriode);
        $formattedSaldoPeriode = $this->formatLargeNumber($saldoPeriode);

        return [
            Stat::make('Total Pemasukan (Keseluruhan)', $formattedTotalPemasukan)
                ->description($periodeText . ': ' . $formattedPemasukanPeriode . ' (' . $pemasukanPersentase . '%)')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Total Pengeluaran (Keseluruhan)', $formattedTotalPengeluaran)
                ->description($periodeText . ': ' . $formattedPengeluaranPeriode . ' (' . $pengeluaranPersentase . '%)')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),

            Stat::make('Saldo Keseluruhan', $formattedSaldo)
                ->description($periodeText . ': ' . $formattedSaldoPeriode)
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($saldo >= 0 ? 'success' : 'danger')
        ];
    }

    // Menghasilkan teks periode yang ditampilkan
    private function getPeriodeDisplayText(): string
    {
        if ($this->periode === 'semua_waktu') {
            return 'Semua Waktu';
        }

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
            default => 'Periode'
        };
    }

    // Fungsi untuk memformat angka besar menjadi singkatan
    protected function formatLargeNumber(float $number): string
    {
        // Simpan tanda negatif jika ada
        $isNegative = $number < 0;
        $absValue = abs($number);
        $prefix = $isNegative ? '- ' : '';

        if ($absValue >= 1000000000000) {
            return $prefix . 'Rp ' . number_format($absValue / 1000000000000, 2, ',', '.') . ' T';
        } elseif ($absValue >= 1000000000) {
            return $prefix . 'Rp ' . number_format($absValue / 1000000000, 2, ',', '.') . ' M';
        } elseif ($absValue >= 1000000) {
            return $prefix . 'Rp ' . number_format($absValue / 1000000, 2, ',', '.') . ' Jt';
        } else {
            return $prefix . 'Rp ' . number_format($absValue, 0, ',', '.');
        }
    }
}
