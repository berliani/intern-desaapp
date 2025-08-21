<?php

namespace App\Filament\Resources\KeuanganDesaResource\Widgets;

use App\Models\KeuanganDesa;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Attributes\On;

class KeuanganChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Keuangan Desa';
    protected static ?string $pollingInterval = '60s';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

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

    protected function getData(): array
    {
        // Periksa apakah ini filter "semua waktu"
        if ($this->periode === 'semua_waktu' || !$this->dariTanggal || !$this->sampaiTanggal) {
            // Cari rentang waktu dari data yang ada
            $earliestRecord = KeuanganDesa::orderBy('tanggal', 'asc')->first();
            $latestRecord = KeuanganDesa::orderBy('tanggal', 'desc')->first();

            if ($earliestRecord && $latestRecord) {
                $startDate = Carbon::parse($earliestRecord->tanggal)->startOfDay();
                $endDate = Carbon::parse($latestRecord->tanggal)->endOfDay();
            } else {
                // Fallback jika tidak ada data
                $startDate = now()->subYears(2)->startOfYear();
                $endDate = now()->endOfYear();
            }

            // Untuk data yang panjang, gunakan month atau year
            $diffInDays = $startDate->diffInDays($endDate);

            if ($diffInDays > 366 * 2) {
                $unit = 'year';
                $sqlFormat = '%Y-01-01';
                $labelFormat = 'Y';
            } else {
                $unit = 'month';
                $sqlFormat = '%Y-%m-01';
                $labelFormat = 'M Y';
            }
        } else {
            // Tanggal awal dan akhir untuk query dari filter
            $startDate = Carbon::parse($this->dariTanggal)->startOfDay();
            $endDate = Carbon::parse($this->sampaiTanggal)->endOfDay();

            // Tentukan unit waktu dan format SQL berdasarkan rentang tanggal
            $diffInDays = $startDate->diffInDays($endDate);

            if ($diffInDays <= 1) {
                $unit = 'hour';
                $sqlFormat = '%Y-%m-%d %H:00:00';
                $labelFormat = 'H:i';
            } elseif ($diffInDays <= 31) {
                $unit = 'day';
                $sqlFormat = '%Y-%m-%d';
                $labelFormat = 'd M';
            } elseif ($diffInDays <= 366) {
                $unit = 'month';
                $sqlFormat = '%Y-%m-01';
                $labelFormat = 'M Y';
            } else {
                $unit = 'year';
                $sqlFormat = '%Y-01-01';
                $labelFormat = 'Y';
            }
        }

        // Override untuk tahun lalu
        if ($this->periode === 'tahun_lalu') {
            $unit = 'month';
            $sqlFormat = '%Y-%m-01';
            $labelFormat = 'M Y';
        }

        // Query untuk pemasukan (dikelompokkan berdasarkan format tanggal)
        $pemasukan = DB::table('keuangan_desa')
            ->select(DB::raw("DATE_FORMAT(tanggal, '$sqlFormat') as date"), DB::raw('SUM(jumlah) as total'))
            ->whereIn('jenis', ['pemasukan', 'Pemasukan', 'PEMASUKAN'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Query untuk pengeluaran (dikelompokkan berdasarkan format tanggal)
        $pengeluaran = DB::table('keuangan_desa')
            ->select(DB::raw("DATE_FORMAT(tanggal, '$sqlFormat') as date"), DB::raw('SUM(jumlah) as total'))
            ->whereIn('jenis', ['pengeluaran', 'Pengeluaran', 'PENGELUARAN'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Buat rentang periode yang lengkap (termasuk periode tanpa data)
        $dateRange = $this->generateDateRange($startDate, $endDate, $unit, $labelFormat);

        // Siapkan data untuk chart
        $pemasukanData = [];
        $pengeluaranData = [];
        $labels = [];

        foreach ($dateRange as $date => $formattedDate) {
            $labels[] = $formattedDate;

            // Ambil data jika ada, 0 jika tidak
            $pemasukanData[] = $pemasukan->get($date) ? $pemasukan->get($date)->total : 0;
            $pengeluaranData[] = $pengeluaran->get($date) ? $pengeluaran->get($date)->total : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pemasukan',
                    'data' => $pemasukanData,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.7)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Pengeluaran',
                    'data' => $pengeluaranData,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.7)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 1,
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    // Helper untuk menghasilkan rentang tanggal lengkap
    protected function generateDateRange(Carbon $startDate, Carbon $endDate, string $unit, string $labelFormat = ''): array
    {
        $result = [];
        $current = clone $startDate;

        // Format label untuk setiap unit waktu jika tidak diberikan
        if (!$labelFormat) {
            $labelFormat = match($unit) {
                'hour' => 'H:i',
                'day' => 'd M',
                'month' => 'M Y',
                'year' => 'Y',
                default => 'd M Y',
            };
        }

        // Format tanggal untuk key array
        $keyFormat = match($unit) {
            'hour' => 'Y-m-d H:00:00',
            'day' => 'Y-m-d',
            'month' => 'Y-m-01',
            'year' => 'Y-01-01',
            default => 'Y-m-d',
        };

        // Buat array dengan key tanggal dan value label
        while ($current <= $endDate) {
            $key = $current->format($keyFormat);
            $result[$key] = $current->format($labelFormat);

            // Increment sesuai unit
            match($unit) {
                'hour' => $current->addHour(),
                'day' => $current->addDay(),
                'month' => $current->addMonth(),
                'year' => $current->addYear(),
                default => $current->addDay(),
            };
        }

        return $result;
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 15,
                    ],
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(200, 200, 200, 0.2)',
                    ],
                    'ticks' => [
                        'callback' => '(value) => { return "Rp " + new Intl.NumberFormat("id-ID").format(value) }',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'barPercentage' => 0.8,
            'categoryPercentage' => 0.9,
            'elements' => [
                'bar' => [
                    'borderRadius' => 4,
                    'borderSkipped' => false,
                ]
            ],
        ];
    }
}