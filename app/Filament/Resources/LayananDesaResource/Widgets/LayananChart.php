<?php

namespace App\Filament\Resources\LayananDesaResource\Widgets;

use App\Models\LayananDesa;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class LayananChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Layanan Desa';
    protected static ?string $pollingInterval = '60s';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    // Filter properties
    public ?string $filter = 'kategori';
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
            'kategori' => 'Berdasarkan Kategori',
        ];
    }

    // Event listener for dashboard filter
    #[On('filter-changed')]
    public function onDashboardFilterChanged(string $dari_tanggal = '', string $sampai_tanggal = '', string $periode = 'bulan_ini'): void
    {
        // Simpan periode
        $this->periode = $periode;

        // Simpan tanggal kustom jika ada
        if ($periode === 'kustom' && $dari_tanggal && $sampai_tanggal) {
            $this->dariTanggal = $dari_tanggal;
            $this->sampaiTanggal = $sampai_tanggal;
        } else {
            // Konversi periode dashboard ke format widget
            $this->setPeriodeFilter($periode);
        }

        // Refresh chart
        $this->dispatch('$refresh');
    }

    // Convert dashboard period filter
    public function setPeriodeFilter(string $periode): void
    {
        $periodeMap = [
            'semua_waktu' => 'semua',
            'hari_ini' => 'hari_ini',
            'minggu_ini' => 'minggu_ini',
            'bulan_ini' => 'bulan_ini',
            'tahun_ini' => 'tahun_ini',
            'bulan_lalu' => 'bulan_lalu',
            'tahun_lalu' => 'tahun_lalu',
        ];

        $this->periode = $periodeMap[$periode] ?? 'semua';

        // Set tanggal sesuai periode
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
        }
    }

    // Switch chart type
    public function switchChartType(string $type): void
    {
        $this->chartType = $type;
    }

    // Chart header actions
    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('switchChartType')
                ->label('Tipe Chart')
                ->icon('heroicon-m-chart-pie')
                ->button()
                ->color('gray')
                ->modalHeading('Pilih Tipe Chart')
                ->modalSubmitActionLabel('Pilih')
                ->form([
                    \Filament\Forms\Components\Grid::make(3)
                        ->schema([
                            \Filament\Forms\Components\Radio::make('type')
                                ->label('Tipe Chart')
                                ->options([
                                    'doughnut' => 'Doughnut',
                                    'pie' => 'Pie',
                                    'bar' => 'Bar',
                                    'polarArea' => 'Polar Area',
                                ])
                                ->default($this->chartType)
                                ->required(),
                        ]),
                ])
                ->action(function (array $data): void {
                    $this->switchChartType($data['type']);
                }),
        ];
    }

    // Get chart title
    protected function getChartTitle(): string
    {
        $periodeLabel = match ($this->periode) {
            'hari_ini' => 'Hari Ini',
            'minggu_ini' => 'Minggu Ini',
            'bulan_ini' => 'Bulan Ini',
            'tahun_ini' => 'Tahun Ini',
            'bulan_lalu' => 'Bulan Lalu',
            'tahun_lalu' => 'Tahun Lalu',
            default => 'Semua Waktu'
        };

        return "Layanan Desa Berdasarkan Kategori - $periodeLabel";
    }

    // Data for chart
    protected function getData(): array
    {
        $tenant = Filament::getTenant();

        // --- PERUBAHAN UTAMA DI SINI ---
        $baseQuery = LayananDesa::query()
            ->withTrashed()
            ->where('company_id', $tenant->id);

        if ($this->dariTanggal && $this->sampaiTanggal) {
            $baseQuery->whereBetween('created_at', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay(),
            ]);
        }

        return $this->getKategoriData($baseQuery);
    }    // Chart data by kategori
    protected function getKategoriData($query)
    {
        $data = $query->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
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
                    'label' => 'Jumlah Layanan',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => array_slice($colors, 0, $data->count()),
                    'borderColor' => array_slice($colors, 0, $data->count()),
                    'borderWidth' => 1,
                    'hoverOffset' => 15,
                ],
            ],
        ];
    }

    // Empty data placeholder
    protected function getEmptyData()
    {
        return [
            'labels' => ['Tidak Ada Data'],
            'datasets' => [
                [
                    'label' => 'Tidak Ada Data',
                    'data' => [100],
                    'backgroundColor' => ['#d1d5db'],
                    'borderColor' => ['#d1d5db'],
                ],
            ],
        ];
    }

    // Chart colors
    protected function getChartColors(): array
    {
        return [
            '#3b82f6', // blue-500
            '#10b981', // emerald-500
            '#f59e0b', // amber-500
            '#8b5cf6', // violet-500
            '#ec4899', // pink-500
            '#6366f1', // indigo-500
            '#14b8a6', // teal-500
            '#f97316', // orange-500
            '#ef4444', // red-500
            '#06b6d4', // cyan-500
            '#a855f7', // purple-500
            '#84cc16', // lime-500
        ];
    }

    // Chart options
    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
            'animation' => [
                'animateScale' => true,
                'animateRotate' => true,
            ],
        ];
    }
}
