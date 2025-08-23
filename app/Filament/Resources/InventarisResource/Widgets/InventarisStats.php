<?php

namespace App\Filament\Resources\InventarisResource\Widgets;

use App\Models\Inventaris;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Filament\Facades\Filament;
class InventarisStats extends BaseWidget
{
    // Refresh data setiap 60 detik
    protected static ?string $pollingInterval = '60s';

    // Nonaktifkan cache widget
    protected int|string|array $columnSpan = 'full';

    // Properti untuk menyimpan filter
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;
    public ?string $periode = 'semua';

    public function mount(): void
    {
        // Set default ke semua data
        $this->setPeriodeFilter('semua');
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
            case 'semua_waktu': // tambahkan case untuk format dashboard
                $this->dariTanggal = null;
                $this->sampaiTanggal = null;
                break;
            default:
                // Untuk kustom, tanggal akan ditetapkan secara manual
                break;
        }
    }

    // Menerima event filter inventaris khusus
    #[On('inventaris-filter-changed')]
    public function onInventarisFilterChanged(string $periode = 'semua', ?string $dari = null, ?string $sampai = null): void
    {
        if ($periode === 'kustom' && $dari && $sampai) {
            $this->dariTanggal = $dari;
            $this->sampaiTanggal = $sampai;
            $this->periode = 'kustom';
        } else {
            $this->setPeriodeFilter($periode);
        }
    }

    // Tambahkan listener untuk event filter-changed dari dashboard
    #[On('filter-changed')]
    public function onFilterChanged(string $periode = 'semua_waktu', ?string $dari_tanggal = '', ?string $sampai_tanggal = ''): void
    {
        // Konversi periode dari dashboard ke format inventaris
        $periode = $periode === 'semua_waktu' ? 'semua' : $periode;

        if ($periode === 'kustom' && $dari_tanggal && $sampai_tanggal) {
            $this->dariTanggal = $dari_tanggal;
            $this->sampaiTanggal = $sampai_tanggal;
            $this->periode = 'kustom';
        } else {
            $this->setPeriodeFilter($periode);
        }

        // Teruskan juga ke chart inventaris untuk sinkronisasi
        $this->dispatch('inventaris-filter-changed', periode: $periode, dari: $dari_tanggal, sampai: $sampai_tanggal);
    }

    protected function getStats(): array
    {
        $tenantId = Filament::getTenant()->id;

        // Periode untuk ditampilkan
        $periodeText = $this->getPeriodeDisplayText();

        // Total keseluruhan (tidak difilter)
         $totalInventaris = Inventaris::where('company_id', $tenantId)->count();
        $totalUnit = Inventaris::where('company_id', $tenantId)->sum('jumlah');
        $totalNilai = Inventaris::where('company_id', $tenantId)->sum('nominal_harga');

        // Query untuk data filter
        $filteredQuery = Inventaris::query()->where('company_id', $tenantId);

        // Terapkan filter tanggal jika ada
        if ($this->dariTanggal && $this->sampaiTanggal) {
            $filteredQuery->whereBetween('tanggal_perolehan', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay()
            ]);
        }

        // Data untuk periode yang dipilih
        $inventarisPeriode = $filteredQuery->clone()->count();
        $unitPeriode = $filteredQuery->clone()->sum('jumlah');
        $nilaiPeriode = $filteredQuery->clone()->sum('nominal_harga');

        // Hitung persentase perbandingan periode dengan total
        $inventarisPersentase = $totalInventaris > 0 ? round(($inventarisPeriode / $totalInventaris) * 100) : 0;
        $unitPersentase = $totalUnit > 0 ? round(($unitPeriode / $totalUnit) * 100) : 0;
        $nilaiPersentase = $totalNilai > 0 ? round(($nilaiPeriode / $totalNilai) * 100) : 0;

        // Kondisi Inventaris
        $kondisiBaik = Inventaris::where('company_id', $tenantId)->where('kondisi', 'Baik')->count();
        $kondisiRusakRingan = Inventaris::where('company_id', $tenantId)->where('kondisi', 'Rusak Ringan')->count();
        $persentaseBaik = $totalInventaris > 0 ? round(($kondisiBaik / $totalInventaris) * 100) : 0;
        $persentaseRusakRingan = $totalInventaris > 0 ? round(($kondisiRusakRingan / $totalInventaris) * 100) : 0;

        // Persentase kondisi
        $persentaseBaik = $totalInventaris > 0 ? round(($kondisiBaik / $totalInventaris) * 100) : 0;
        $persentaseRusakRingan = $totalInventaris > 0 ? round(($kondisiRusakRingan / $totalInventaris) * 100) : 0;

        // Fix description to explicitly show inventory count
        $unitDescription = $this->periode !== 'semua' ?
            $unitPeriode . ' Dari ' . number_format($totalInventaris, 0, ',', '.') . ' item (' . $periodeText . ')' :
            'Dari ' . number_format($totalInventaris, 0, ',', '.') . ' item (' . $periodeText . ')';

        return [
            Stat::make('Total Unit', number_format($totalUnit, 0, ',', '.') . ' unit')
                ->description($unitDescription)
                ->color('primary')
                ->icon('heroicon-o-cube'),

            Stat::make('Total Nilai', $this->formatLargeNumber($totalNilai))
                ->description($periodeText . ': ' . $this->formatLargeNumber($nilaiPeriode) . ' (' . $nilaiPersentase . '%)')
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Kondisi Baik', $kondisiBaik . ' item (' . $persentaseBaik . '%)')
                ->description('Rusak Ringan: ' . $kondisiRusakRingan . ' item (' . $persentaseRusakRingan . '%)')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
        ];
    }

    // Menghasilkan teks periode yang ditampilkan
    private function getPeriodeDisplayText(): string
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
            'semua' => 'Semua Waktu',
            default => 'Periode'
        };
    }

    // Modify formatLargeNumber to properly handle zero values
    protected function formatLargeNumber(float $number): string
    {
        // Handle zero value explicitly
        if ($number == 0) {
            return 'Rp 0';
        }

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
