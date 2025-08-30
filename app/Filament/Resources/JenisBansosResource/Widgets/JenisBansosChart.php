<?php

namespace App\Filament\Resources\JenisBansosResource\Widgets;

use App\Models\Bansos;
use App\Models\JenisBansos;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Filament\Facades\Filament;

class JenisBansosChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pengajuan Bantuan di Desa';
    protected static ?string $pollingInterval = '60s';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    // Filter properties
    public ?string $filter = 'kategori';
    protected ?string $chartType = 'doughnut';
    public ?string $periode = 'semua';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    protected function getType(): string
    {
        return $this->filter === 'tahunan' ? 'line' : $this->chartType;
    }

    protected function getFilters(): ?array
    {
        return [
            'kategori' => 'Berdasarkan Kategori',
            'bentuk' => 'Berdasarkan Bentuk Bantuan',
            'periode' => 'Berdasarkan Periode Bantuan',
            'instansi' => 'Berdasarkan Instansi Pemberi',
            'tahunan' => 'Trend Tahunan',
        ];
    }

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
            default:
                $this->dariTanggal = null;
                $this->sampaiTanggal = null;
                break;
        }
    }

    protected function getData(): array
    {
        if ($this->filter === 'tahunan') {
            return $this->getTahunanData();
        }

        $tenantId = Filament::getTenant()->id;
        $query = Bansos::query()->where('company_id', $tenantId);

        if ($this->dariTanggal && $this->sampaiTanggal) {
            $query->whereBetween('tanggal_pengajuan', [Carbon::parse($this->dariTanggal)->startOfDay(), Carbon::parse($this->sampaiTanggal)->endOfDay()]);
        }

        switch ($this->filter) {
            case 'bentuk':
                return $this->getBentukBantuanData($query);
            case 'periode':
                return $this->getPeriodeBantuanData($query);
            case 'instansi':
                return $this->getInstansiData($query);
            case 'kategori':
            default:
                return $this->getKategoriData($query);
        }
    }

    protected function getKategoriData($query)
    {
        $data = (clone $query)->join('jenis_bansos', 'bansos.jenis_bansos_id', '=', 'jenis_bansos.id')
            ->select('jenis_bansos.kategori', DB::raw('count(*) as total'))
            ->groupBy('jenis_bansos.kategori')
            ->orderByDesc('total')
            ->get();

        if ($data->isEmpty()) return $this->getEmptyData();
        $colors = $this->getChartColors();
        return [
            'labels' => $data->pluck('kategori')->toArray(),
            'datasets' => [['label' => 'Jumlah Pengajuan', 'data' => $data->pluck('total')->toArray(), 'backgroundColor' => array_slice($colors, 0, $data->count()), 'borderColor' => array_slice($colors, 0, $data->count())]],
        ];
    }

    protected function getBentukBantuanData($query)
    {
        $data = (clone $query)->join('jenis_bansos', 'bansos.jenis_bansos_id', '=', 'jenis_bansos.id')
            ->select('jenis_bansos.bentuk_bantuan', DB::raw('count(*) as total'))
            ->groupBy('jenis_bansos.bentuk_bantuan')
            ->orderByDesc('total')
            ->get();

        if ($data->isEmpty()) return $this->getEmptyData();
        $colors = $this->getChartColors();
        $bentukOptions = JenisBansos::getBentukBantuanOptions();
        return [
            'labels' => $data->pluck('bentuk_bantuan')->map(fn($item) => $bentukOptions[$item] ?? $item)->toArray(),
            'datasets' => [['label' => 'Jumlah Pengajuan', 'data' => $data->pluck('total')->toArray(), 'backgroundColor' => array_slice($colors, 0, $data->count()), 'borderColor' => array_slice($colors, 0, $data->count())]],
        ];
    }

    protected function getPeriodeBantuanData($query)
    {
        $data = (clone $query)->join('jenis_bansos', 'bansos.jenis_bansos_id', '=', 'jenis_bansos.id')
            ->select('jenis_bansos.periode', DB::raw('count(*) as total'))
            ->groupBy('jenis_bansos.periode')
            ->orderByDesc('total')
            ->get();

        if ($data->isEmpty()) return $this->getEmptyData();
        $colors = $this->getChartColors();
        return [
            'labels' => $data->pluck('periode')->toArray(),
            'datasets' => [['label' => 'Jumlah Pengajuan', 'data' => $data->pluck('total')->toArray(), 'backgroundColor' => array_slice($colors, 0, $data->count()), 'borderColor' => array_slice($colors, 0, $data->count())]],
        ];
    }

    protected function getInstansiData($query)
    {
        $data = (clone $query)->join('jenis_bansos', 'bansos.jenis_bansos_id', '=', 'jenis_bansos.id')
            ->select('jenis_bansos.instansi_pemberi', DB::raw('count(*) as total'))
            ->groupBy('jenis_bansos.instansi_pemberi')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        if ($data->isEmpty()) return $this->getEmptyData();
        $colors = $this->getChartColors();
        return [
            'labels' => $data->pluck('instansi_pemberi')->toArray(),
            'datasets' => [['label' => 'Jumlah Pengajuan', 'data' => $data->pluck('total')->toArray(), 'backgroundColor' => array_slice($colors, 0, $data->count()), 'borderColor' => array_slice($colors, 0, $data->count())]],
        ];
    }

    protected function getTahunanData()
    {
        $tenantId = Filament::getTenant()->id;
        $currentYear = Carbon::now()->year;
        $years = range($currentYear - 4, $currentYear);

        // GLOBAL: Total jenis bantuan baru yang dibuat per tahun
        $bantuanPerTahun = DB::table('jenis_bansos')
            ->selectRaw('YEAR(created_at) as year, COUNT(*) as total')
            ->whereIn(DB::raw('YEAR(created_at)'), $years)
            ->groupBy('year')->orderBy('year')->get()->keyBy('year');

        // SPESIFIK TENANT: Total pengajuan bantuan per tahun di desa ini
        $penerimaPerTahun = DB::table('bansos')
            ->where('company_id', $tenantId)
            ->selectRaw('YEAR(tanggal_pengajuan) as year, COUNT(*) as total')
            ->whereIn(DB::raw('YEAR(tanggal_pengajuan)'), $years)
            ->groupBy('year')->orderBy('year')->get()->keyBy('year');

        $bantuanData = array_map(fn($year) => $bantuanPerTahun[$year]->total ?? 0, $years);
        $penerimaData = array_map(fn($year) => $penerimaPerTahun[$year]->total ?? 0, $years);

        return [
            'labels' => array_map('strval', $years),
            'datasets' => [
                ['label' => 'Jenis Bantuan Baru (Global)', 'data' => $bantuanData, 'borderColor' => '#3b82f6', 'tension' => 0.3],
                ['label' => 'Pengajuan di Desa Ini', 'data' => $penerimaData, 'borderColor' => '#10b981', 'tension' => 0.3],
            ],
        ];
    }

    protected function getEmptyData()
    {
        return ['labels' => ['Tidak Ada Data'], 'datasets' => [['label' => 'Tidak Ada Data', 'data' => [0], 'backgroundColor' => ['#d1d5db']]]];
    }

    // Warna untuk chart
    protected function getChartColors(): array
    {
        return [
            '#3b82f6', // blue-500
            '#ef4444', // red-500
            '#10b981', // emerald-500
            '#f59e0b', // amber-500
            '#8b5cf6', // violet-500
            '#ec4899', // pink-500
            '#6366f1', // indigo-500
            '#14b8a6', // teal-500
            '#f97316', // orange-500
            '#06b6d4', // cyan-500
            '#a855f7', // purple-500
            '#84cc16', // lime-500
        ];
    }

    // Chart title
    protected function getChartTitle(): string
    {
        $periodeLabel = match ($this->periode) {
            'today' => 'Hari Ini',
            'this_week' => 'Minggu Ini',
            'this_month' => 'Bulan Ini',
            'this_year' => 'Tahun Ini',
            'last_month' => 'Bulan Lalu',
            'last_year' => 'Tahun Lalu',
            default => 'Semua Waktu'
        };

        $filterLabel = match ($this->filter) {
            'kategori' => 'Kategori Bantuan',
            'bentuk' => 'Bentuk Bantuan',
            'periode' => 'Periode Bantuan',
            'instansi' => 'Instansi Pemberi',
            'tahunan' => 'Trend Bantuan 5 Tahun Terakhir',
            default => 'Kategori Bantuan'
        };

        return "Distribusi {$filterLabel} ({$periodeLabel})";
    }

    // Chart options
    protected function getOptions(): array
    {
        // Opsi dasar
        $baseOptions = [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'labels' => [
                        'font' => [
                            'size' => 11,
                        ],
                        'padding' => 15,
                        'usePointStyle' => true,
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                    'backgroundColor' => 'rgba(17, 24, 39, 0.95)',
                    'padding' => 10,
                ],
                'title' => [
                    'display' => true,
                    'text' => $this->getChartTitle(),
                    'font' => [
                        'size' => 14,
                        'weight' => 'bold',
                    ],
                    'padding' => [
                        'top' => 10,
                        'bottom' => 20
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'animation' => [
                'duration' => 1000,
            ],
            'layout' => [
                'padding' => [
                    'left' => 5,
                    'right' => 5,
                    'top' => 10,
                    'bottom' => 10
                ],
            ],
        ];

        // Opsi untuk chart doughnut/pie
        if (in_array($this->chartType, ['doughnut', 'pie'])) {
            return array_merge($baseOptions, [
                'cutout' => $this->chartType === 'doughnut' ? '65%' : '0',
                'radius' => '90%',
            ]);
        }

        // Opsi untuk chart bar
        if ($this->chartType === 'bar') {
            return array_merge($baseOptions, [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ],
                    'x' => [
                        'grid' => [
                            'display' => false,
                        ],
                    ],
                ],
            ]);
        }

        // Opsi untuk chart line (tren tahunan)
        if ($this->filter === 'tahunan' || $this->chartType === 'line') {
            return array_merge($baseOptions, [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ],
                    'x' => [
                        'grid' => [
                            'display' => true,
                        ],
                    ],
                ],
                'elements' => [
                    'line' => [
                        'tension' => 0.3,
                    ],
                    'point' => [
                        'radius' => 4,
                        'hoverRadius' => 6,
                    ],
                ],
            ]);
        }

        // Opsi default
        return $baseOptions;
    }
}
