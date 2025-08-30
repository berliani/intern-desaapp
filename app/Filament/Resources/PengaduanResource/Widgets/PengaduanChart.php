<?php

namespace App\Filament\Resources\PengaduanResource\Widgets;

use App\Models\Pengaduan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder; // Import Builder

class PengaduanChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pengaduan Warga';
    protected static ?string $pollingInterval = '60s';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    public ?string $filter = 'status';
    protected ?string $chartType = 'doughnut';
    public ?string $periode = 'semua';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    protected function getType(): string
    {
        return $this->filter === 'bulanan' ? 'line' : $this->chartType;
    }

    protected function getFilters(): ?array
    {
        return [
            'status' => 'Berdasarkan Status',
            'kategori' => 'Berdasarkan Kategori',
            'prioritas' => 'Berdasarkan Prioritas',
            'bulanan' => 'Trend Bulanan',
        ];
    }

    #[On('filter-changed')]
    public function onDashboardFilterChanged(string $dari_tanggal = '', string $sampai_tanggal = '', string $periode = 'bulan_ini'): void
    {
        $this->periode = $periode;
        if ($periode === 'kustom' && $dari_tanggal && $sampai_tanggal) {
            $this->dariTanggal = $dari_tanggal;
            $this->sampaiTanggal = $sampai_tanggal;
        } else {
            $this->setPeriodeFilter($periode);
        }
        $this->dispatch('$refresh');
    }

    public function setPeriodeFilter(string $periode): void
    {
        $periodeMap = [
            'semua_waktu' => 'semua',
            'hari_ini' => 'today',
            'minggu_ini' => 'this_week',
            'bulan_ini' => 'this_month',
            'tahun_ini' => 'this_year',
            'bulan_lalu' => 'last_month',
            'tahun_lalu' => 'last_year',
        ];
        $this->periode = $periodeMap[$periode] ?? 'semua';

        if ($this->periode === 'semua') {
            $this->dariTanggal = null;
            $this->sampaiTanggal = null;
            return;
        }
        $now = now();
        switch ($this->periode) {
            case 'today':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'this_week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'this_month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'this_year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth();
                $end = $now->copy()->subMonth()->endOfMonth();
                break;
            case 'last_year':
                $start = $now->copy()->subYear()->startOfYear();
                $end = $now->copy()->subYear()->endOfYear();
                break;
            default:
                $start = null;
                $end = null;
        }
        $this->dariTanggal = $start?->toDateString();
        $this->sampaiTanggal = $end?->toDateString();
    }

    protected function getData(): array
    {
        $tenant = Filament::getTenant();

        // --- PERUBAHAN UTAMA: Menambahkan withTrashed() ---
      $baseQuery = Pengaduan::query()
    ->withTrashed()
    ->where('company_id', $tenant->id);
        // Terapkan filter periode
        if ($this->dariTanggal && $this->sampaiTanggal) {
            $baseQuery->whereBetween('created_at', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay(),
            ]);
        }

        // Generate chart data berdasarkan filter yang dipilih
        switch ($this->filter) {
            case 'kategori':
                return $this->getKategoriData($baseQuery);
            case 'prioritas':
                return $this->getPrioritasData($baseQuery);
            case 'bulanan':
                return $this->getBulananData($baseQuery);
            case 'status':
            default:
                return $this->getStatusData($baseQuery);
        }
    }

    protected function getStatusData(Builder $query): array
    {
        $data = (clone $query)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status')
            ->get();

        if ($data->isEmpty()) return $this->getEmptyData();

        $colors = ['Belum Ditangani' => '#f59e0b', 'Sedang Diproses' => '#3b82f6', 'Selesai' => '#10b981', 'Ditolak' => '#ef4444'];
        $backgroundColors = $data->pluck('status')->map(fn($status) => $colors[$status] ?? '#6b7280')->toArray();

        return [
            'labels' => $data->pluck('status')->toArray(),
            'datasets' => [['label' => 'Jumlah Pengaduan', 'data' => $data->pluck('total')->toArray(), 'backgroundColor' => $backgroundColors]],
        ];
    }

    protected function getKategoriData(Builder $query): array
    {
        $data = (clone $query)
            ->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        if ($data->isEmpty()) return $this->getEmptyData();

        return [
            'labels' => $data->pluck('kategori')->toArray(),
            'datasets' => [['label' => 'Jumlah Pengaduan', 'data' => $data->pluck('total')->toArray(), 'backgroundColor' => $this->getChartColors()]],
        ];
    }

    protected function getPrioritasData(Builder $query): array
    {
        $data = (clone $query)
            ->select('prioritas', DB::raw('count(*) as total'))
            ->groupBy('prioritas')
            ->orderByRaw("CASE WHEN prioritas = 'Tinggi' THEN 1 WHEN prioritas = 'Sedang' THEN 2 WHEN prioritas = 'Rendah' THEN 3 ELSE 4 END")
            ->get();

        if ($data->isEmpty()) return $this->getEmptyData();

        $colors = ['Tinggi' => '#ef4444', 'Sedang' => '#f59e0b', 'Rendah' => '#22c55e'];
        $backgroundColors = $data->pluck('prioritas')->map(fn($p) => $colors[$p] ?? '#6b7280')->toArray();

        return [
            'labels' => $data->pluck('prioritas')->toArray(),
            'datasets' => [['label' => 'Jumlah Pengaduan', 'data' => $data->pluck('total')->toArray(), 'backgroundColor' => $backgroundColors]],
        ];
    }

    protected function getBulananData(Builder $baseQuery): array
    {
        $startDate = $this->dariTanggal ? Carbon::parse($this->dariTanggal) : now()->subMonths(5)->startOfMonth();
        $endDate = $this->sampaiTanggal ? Carbon::parse($this->sampaiTanggal) : now()->endOfMonth();

        $data = (clone $baseQuery)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"), 'status', DB::raw('count(*) as total'))
            ->groupBy('bulan', 'status')
            ->orderBy('bulan')
            ->get();

        if ($data->isEmpty()) return $this->getEmptyDataForLineChart();

        $labels = $data->pluck('bulan')->unique()->map(fn($b) => Carbon::parse($b)->format('M Y'))->values();
        $statuses = $data->pluck('status')->unique()->values();
        $colors = ['Belum Ditangani' => '#f59e0b', 'Sedang Diproses' => '#3b82f6', 'Selesai' => '#10b981', 'Ditolak' => '#ef4444'];

        $datasets = $statuses->map(function ($status) use ($data, $labels, $colors) {
            $statusData = $labels->map(function ($label) use ($data, $status) {
                $bulan = Carbon::parse($label)->format('Y-m');
                return $data->where('bulan', $bulan)->where('status', $status)->first()?->total ?? 0;
            });
            return [
                'label' => $status,
                'data' => $statusData,
                'borderColor' => $colors[$status] ?? '#6b7280',
                'backgroundColor' => ($colors[$status] ?? '#6b7280') . '33',
                'tension' => 0.3,
            ];
        });

        return ['labels' => $labels, 'datasets' => $datasets];
    }

    protected function getEmptyData(): array
    {
        return ['labels' => ['Tidak Ada Data'], 'datasets' => [['label' => 'Tidak Ada Data', 'data' => [100], 'backgroundColor' => ['#d1d5db']]]];
    }

    protected function getEmptyDataForLineChart(): array
    {
        return ['labels' => [now()->format('M Y')], 'datasets' => [['label' => 'Tidak Ada Data', 'data' => [0], 'borderColor' => '#9ca3af']]];
    }

    protected function getChartColors(): array
    {
        return ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#6366f1', '#14b8a6'];
    }

    protected function getOptions(): array
    {
        return ['responsive' => true, 'maintainAspectRatio' => false, 'plugins' => ['legend' => ['position' => 'top']]];
    }
}
