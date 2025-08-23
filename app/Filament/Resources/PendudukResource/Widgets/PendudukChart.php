<?php

namespace App\Filament\Resources\PendudukResource\Widgets;

use App\Models\Penduduk;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Filament\Facades\Filament;

class PendudukChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Demografi Penduduk';
    protected static ?string $pollingInterval = '60s';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    // Filter properties
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;
    public ?string $periode = 'semua';

    // Filter standard Filament untuk chart widgets
    public ?string $filter = 'gender';

    public function mount(): void
    {
        $this->setPeriodeFilter('semua');
    }

    // Method ini akan menghasilkan dropdown filter di pojok kanan
    protected function getFilters(): ?array
    {
        return [
            'gender' => 'Jenis Kelamin',
            'age' => 'Kelompok Umur',
            'education' => 'Tingkat Pendidikan',
        ];
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
                // Untuk kustom, tanggal akan ditetapkan secara manual
                break;
        }
    }

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

    protected function getType(): string
    {
        // Menentukan jenis chart berdasarkan filter
        return match ($this->filter) {
            'age' => 'bar',
            'education' => 'pie',
            default => 'doughnut', // gender
        };
    }

    protected function getData(): array
    {
        // Query dasar
        $tenantId = Filament::getTenant()->id;

        
        $query = Penduduk::query()->where('company_id', $tenantId);

        // Terapkan filter tanggal jika ada dan bukan 'semua'
        if ($this->dariTanggal && $this->sampaiTanggal && !in_array($this->periode, ['semua', 'semua_waktu'])) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay()
            ]);
        }

        // Data berdasarkan filter yang dipilih
        switch ($this->filter) {
            case 'gender':
                return $this->getGenderData($query);
            case 'age':
                return $this->getAgeData($query);
            case 'education':
                return $this->getEducationData($query);
            default:
                return $this->getGenderData($query);
        }
    }

    protected function getGenderData($query)
    {
        $totalLakiLaki = (clone $query)->where('jenis_kelamin', 'L')->count();
        $totalPerempuan = (clone $query)->where('jenis_kelamin', 'P')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jenis Kelamin',
                    'data' => [$totalLakiLaki, $totalPerempuan],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)', // Biru untuk Laki-laki
                        'rgba(236, 72, 153, 0.8)', // Pink untuk Perempuan
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(236, 72, 153)',
                    ],
                    'borderWidth' => 1,
                    'cutout' => '70%',
                    'hoverOffset' => 15,
                ],
            ],
            'labels' => ['Laki-laki', 'Perempuan'],
        ];
    }

    protected function getAgeData($query)
    {
        // Menghitung jumlah penduduk berdasarkan kelompok umur
        $ageGroups = [
            'Balita (0-5)' => [0, 5],
            'Anak (6-12)' => [6, 12],
            'Remaja (13-18)' => [13, 18],
            'Dewasa Muda (19-30)' => [19, 30],
            'Dewasa (31-45)' => [31, 45],
            'Paruh Baya (46-60)' => [46, 60],
            'Lansia (>60)' => [61, 200],
        ];

        $groupedByAge = [];

        foreach ($ageGroups as $groupName => [$min, $max]) {
            $count = (clone $query)->where(function ($q) use ($min, $max) {
                $q->whereNotNull('tanggal_lahir')
                  ->whereRaw("TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) >= ?", [$min])
                  ->whereRaw("TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) <= ?", [$max]);
            })->count();

            $groupedByAge[$groupName] = $count;
        }

        // Gradasi warna biru ke ungu
        $colors = [
            'rgba(66, 133, 244, 0.7)',   // Biru muda
            'rgba(52, 120, 246, 0.7)',
            'rgba(94, 92, 230, 0.7)',
            'rgba(122, 79, 220, 0.7)',
            'rgba(151, 65, 209, 0.7)',
            'rgba(179, 51, 199, 0.7)',
            'rgba(218, 30, 184, 0.7)',   // Ungu
        ];

        $borderColors = [
            'rgb(66, 133, 244)',
            'rgb(52, 120, 246)',
            'rgb(94, 92, 230)',
            'rgb(122, 79, 220)',
            'rgb(151, 65, 209)',
            'rgb(179, 51, 199)',
            'rgb(218, 30, 184)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Penduduk',
                    'data' => array_values($groupedByAge),
                    'backgroundColor' => $colors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 1,
                    'borderRadius' => 6,
                    'maxBarThickness' => 50,
                ],
            ],
            'labels' => array_keys($groupedByAge),
        ];
    }

    protected function getEducationData($query)
    {
        // Ambil 6 tingkat pendidikan tertinggi
        $educationData = (clone $query)
            ->select('pendidikan', DB::raw('count(*) as total'))
            ->whereNotNull('pendidikan')
            ->where('pendidikan', '<>', '')
            ->groupBy('pendidikan')
            ->orderByDesc('total')
            ->limit(6)
            ->get()
            ->pluck('total', 'pendidikan')
            ->toArray();

        if (empty($educationData)) {
            $educationData = ['Tidak Ada Data' => 1];
        }

        // Warna-warna untuk chart
        $colors = [
            'rgba(52, 211, 153, 0.7)',  // Hijau
            'rgba(14, 165, 233, 0.7)',  // Biru
            'rgba(99, 102, 241, 0.7)',  // Indigo
            'rgba(168, 85, 247, 0.7)',  // Ungu
            'rgba(236, 72, 153, 0.7)',  // Pink
            'rgba(251, 146, 60, 0.7)',  // Oranye
        ];

        $borderColors = [
            'rgb(52, 211, 153)',
            'rgb(14, 165, 233)',
            'rgb(99, 102, 241)',
            'rgb(168, 85, 247)',
            'rgb(236, 72, 153)',
            'rgb(251, 146, 60)',
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Tingkat Pendidikan',
                    'data' => array_values($educationData),
                    'backgroundColor' => array_slice($colors, 0, count($educationData)),
                    'borderColor' => array_slice($borderColors, 0, count($educationData)),
                    'borderWidth' => 1,
                    'hoverOffset' => 15,
                ],
            ],
            'labels' => array_keys($educationData),
        ];
    }

    protected function getOptions(): array
    {
        // Opsi umum untuk semua jenis chart
        $options = [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 15,
                        'font' => [
                            'size' => 11,
                        ],
                    ],
                ],
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                    'padding' => 10,
                    'caretSize' => 6,
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
                'duration' => 2000,
                'easing' => 'easeOutQuart',
            ],
        ];

        // Tambahkan opsi khusus untuk jenis chart tertentu
        if ($this->filter === 'age') {
            $options['scales'] = [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'color' => 'rgba(200, 200, 200, 0.2)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ];
        }

        return $options;
    }

    protected function getChartTitle(): string
    {
        $title = match ($this->filter) {
            'gender' => 'Distribusi Jenis Kelamin',
            'age' => 'Distribusi Kelompok Umur',
            'education' => 'Distribusi Tingkat Pendidikan',
            default => 'Data Penduduk'
        };

        $periode = match ($this->periode) {
            'hari_ini' => '- Hari Ini',
            'minggu_ini' => '- Minggu Ini',
            'bulan_ini' => '- Bulan Ini',
            'tahun_ini' => '- Tahun Ini',
            'bulan_lalu' => '- Bulan Lalu',
            'tahun_lalu' => '- Tahun Lalu',
            'semua', 'semua_waktu' => '',
            default => ''
        };

        return $title . ' ' . $periode;
    }
}
