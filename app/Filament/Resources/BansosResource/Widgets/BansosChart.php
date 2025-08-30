<?php

namespace App\Filament\Resources\BansosResource\Widgets;

use App\Models\Bansos;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Filament\Facades\Filament; // Import Filament Facade

class BansosChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Bantuan Sosial';
    protected static ?string $pollingInterval = '60s';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    // Filter properties
    public ?string $filter = 'status';
    protected ?string $chartType = 'doughnut';
    public ?string $periode = 'semua';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    // Determine chart type
    protected function getType(): string
    {
        return $this->chartType;
    }

    // Filter dropdown
    protected function getFilters(): ?array
    {
        return [
            'status' => 'Berdasarkan Status',
            'jenis' => 'Berdasarkan Jenis Bantuan',
            'kategori' => 'Berdasarkan Kategori Bantuan',
            'prioritas' => 'Berdasarkan Prioritas',
            'sumber' => 'Berdasarkan Sumber Pengajuan',
        ];
    }

    // Event listener for dashboard filter
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
            default:
                break;
        }
    }


    // Data for chart
    protected function getData(): array
    {
        // Dapatkan ID tenant (desa) yang sedang login
        $tenantId = Filament::getTenant()->id;

        // Query dasar dengan filter tenant
        $query = Bansos::query()->where('company_id', $tenantId);

        // Terapkan filter tanggal jika ada
        if ($this->dariTanggal && $this->sampaiTanggal && !in_array($this->periode, ['semua', 'semua_waktu'])) {
             $query->whereBetween('tanggal_pengajuan', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay()
            ]);
        }

        // Generate chart data based on selected filter
        switch ($this->filter) {
            case 'jenis':
                return $this->getJenisBantuanData($query);
            case 'kategori':
                return $this->getKategoriBantuanData($query);
            case 'prioritas':
                return $this->getPrioritasData($query);
            case 'sumber':
                return $this->getSumberPengajuanData($query);
            case 'status':
            default:
                return $this->getStatusData($query);
        }
    }

    // Data berdasarkan status bantuan
    protected function getStatusData($query)
    {
        $data = (clone $query)->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        if ($data->isEmpty()) {
            return $this->getEmptyData();
        }

        $colors = [
            'Diajukan' => '#f59e0b',
            'Dalam Verifikasi' => '#60a5fa',
            'Diverifikasi' => '#3b82f6',
            'Disetujui' => '#10b981',
            'Ditolak' => '#ef4444',
            'Sudah Diterima' => '#84cc16',
            'Dibatalkan' => '#9ca3af',
        ];

        return [
            'labels' => $data->pluck('status')->toArray(),
            'datasets' => [
                [
                    'label' => 'Jumlah Bantuan',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => $data->map(fn($item) => $colors[$item->status] ?? '#6b7280')->toArray(),
                    'borderColor' => $data->map(fn($item) => $colors[$item->status] ?? '#6b7280')->toArray(),


        // return [
        //     'labels' => $data->pluck('status')->toArray(),
        //     'datasets' => [
        //         [
        //             'label' => 'Jumlah Bantuan',
        //             'data' => $data->pluck('total')->toArray(),
        //             'backgroundColor' => $backgroundColors,
        //             'borderColor' => $borderColors,
        //             'borderWidth' => 1,
        //             'hoverOffset' => 15,
                ],
            ],
        ];
    }

    // Data berdasarkan jenis bantuan
    protected function getJenisBantuanData($query)
    {
        $data = (clone $query)->select('jenis_bansos_id', DB::raw('count(*) as total'))
            ->groupBy('jenis_bansos_id')
            ->orderByDesc('total')
            ->limit(10) // Batasi 10 teratas
            ->with('jenisBansos:id,nama_bansos')
            ->get();

        if ($data->isEmpty()) {
            return $this->getEmptyData();
        }

        $colors = $this->getChartColors();

        $labels = $data->map(function ($item) {
            return $item->jenisBansos ? $item->jenisBansos->nama_bansos : 'Tidak ada data';
        })->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Bantuan',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderColor' => array_slice($colors, 0, count($data)),
                ],
            ],
        ];
    }

    // Data berdasarkan kategori bantuan
    protected function getKategoriBantuanData($query)
    {
        $data = (clone $query)->join('jenis_bansos', 'bansos.jenis_bansos_id', '=', 'jenis_bansos.id')
            ->select('jenis_bansos.kategori', DB::raw('count(*) as total'))
            ->groupBy('jenis_bansos.kategori')
            ->orderByDesc('total')
            ->get();

        if ($data->isEmpty()) {
            return $this->getEmptyData();
        }

        $colors = $this->getChartColors();

        return [
            'labels' => $data->pluck('kategori')->toArray(),
            'datasets' => [
                [
                    'label' => 'Jumlah Bantuan',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderColor' => array_slice($colors, 0, count($data)),
                ],
            ],
        ];
    }

    // Data berdasarkan prioritas
    protected function getPrioritasData($query)
    {
        $data = (clone $query)->select('prioritas', DB::raw('count(*) as total'))
            ->groupBy('prioritas')
            ->orderByRaw("CASE WHEN prioritas = 'Tinggi' THEN 1 WHEN prioritas = 'Sedang' THEN 2 ELSE 3 END")
            ->get();

        if ($data->isEmpty()) {
            return $this->getEmptyData();
        }

        $prioritasColors = [
            'Tinggi' => '#ef4444',
            'Sedang' => '#f59e0b',
            'Rendah' => '#22c55e',
        ];

        return [
            'labels' => $data->pluck('prioritas')->toArray(),
            'datasets' => [
                [
                    'label' => 'Jumlah Bantuan',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => $data->map(fn($item) => $prioritasColors[$item->prioritas] ?? '#6b7280')->toArray(),
                    'borderColor' => $data->map(fn($item) => $prioritasColors[$item->prioritas] ?? '#6b7280')->toArray(),
                ],
            ],
        ];
    }

    // Data berdasarkan sumber pengajuan
    protected function getSumberPengajuanData($query)
    {
        $data = (clone $query)->select('sumber_pengajuan', DB::raw('count(*) as total'))
            ->groupBy('sumber_pengajuan')
            ->get();

        if ($data->isEmpty()) {
            return $this->getEmptyData();
        }

        $sumberLabels = [
            'admin' => 'Admin/Petugas Desa',
            'warga' => 'Pengajuan Warga',
        ];

        $sumberColors = [
            'admin' => '#3b82f6',
            'warga' => '#10b981',
        ];

        return [
            'labels' => $data->map(fn($item) => $sumberLabels[$item->sumber_pengajuan] ?? $item->sumber_pengajuan)->toArray(),
            'datasets' => [
                [
                    'label' => 'Jumlah Bantuan',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => $data->map(fn($item) => $sumberColors[$item->sumber_pengajuan] ?? '#6b7280')->toArray(),
                    'borderColor' => $data->map(fn($item) => $sumberColors[$item->sumber_pengajuan] ?? '#6b7280')->toArray(),
                ],
            ],
        ];
    }

    protected function getEmptyData()
    {
        return [
            'labels' => ['Tidak Ada Data'],
            'datasets' => [
                [
                    'label' => 'Tidak Ada Data',
                    'data' => [0],
                    'backgroundColor' => ['#d1d5db'],
                    'borderColor' => ['#d1d5db'],
                ],
            ],
        ];
    }

    protected function getChartColors(): array
    {
        return [
            '#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899',
            '#6366f1', '#14b8a6', '#f97316', '#06b6d4', '#a855f7', '#84cc16',
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
