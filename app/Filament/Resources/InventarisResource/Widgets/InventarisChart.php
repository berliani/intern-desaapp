<?php

namespace App\Filament\Resources\InventarisResource\Widgets;

use App\Models\Inventaris;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Filament\Facades\Filament;

class InventarisChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Inventaris Desa';
    protected static ?string $pollingInterval = '60s';
    protected int|string|array $columnSpan = 'full';

    // Tambahkan batas tinggi maksimum chart
    protected static ?string $maxHeight = '300px';

    // Properti untuk menyimpan filter periode
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;
    public ?string $periode = 'semua';

    // Properti filter standar Filament - PENTING
    public ?string $filter = 'kategori';

    // Properti untuk tipe chart
    protected ?string $chartType = 'doughnut';

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
            case 'semua_waktu': // Format dari dashboard
            case 'semua': // Format lokal
                $this->dariTanggal = null;
                $this->sampaiTanggal = null;
                break;
            default:
                // Untuk kustom, tanggal akan ditetapkan secara manual
                break;
        }
    }

    // Menerima event filter dari dashboard
    #[On('filter-changed')]
    public function onFilterChanged(string $periode = 'semua_waktu', ?string $dari_tanggal = '', ?string $sampai_tanggal = ''): void
    {
        // Konversi format periode dashboard ke format lokal
        $periode = $periode === 'semua_waktu' ? 'semua' : $periode;

        if ($periode === 'kustom' && $dari_tanggal && $sampai_tanggal) {
            $this->dariTanggal = $dari_tanggal;
            $this->sampaiTanggal = $sampai_tanggal;
            $this->periode = 'kustom';
        } else {
            $this->setPeriodeFilter($periode);
        }
    }

    // Listener khusus untuk filter inventaris
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

    // Sesuai dokumentasi Filament - gunakan getFilters() untuk menambahkan dropdown filter
    protected function getFilters(): ?array
    {
        return [
            'kategori' => 'Kategori',
            'kondisi' => 'Kondisi',
            'status' => 'Status',
            'sumberDana' => 'Sumber Dana',
            'perolehanTahunan' => 'Perolehan Tahunan',
        ];
    }

    // Fungsi untuk mengubah tipe chart
    public function switchChartType(string $type): void
    {
        $this->chartType = $type;
    }

    protected function getType(): string
    {
        // Pilih tipe chart berdasarkan data yang ditampilkan
        if ($this->filter === 'perolehanTahunan') {
            return 'line';
        }

        return $this->chartType;
    }

    protected function getData(): array
    {
        $tenantId = Filament::getTenant()->id;

        // Persiapkan query dasar yang sudah difilter per tenant
        $query = Inventaris::query()->where('company_id', $tenantId);

        // Terapkan filter tanggal jika ada
        if ($this->dariTanggal && $this->sampaiTanggal) {
            $query->whereBetween('tanggal_perolehan', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay()
            ]);
        }

        // Log untuk debugging
        Log::info('Filter chart inventaris: ' . $this->filter);

        // Berdasarkan filter yang dipilih dari dropdown
        switch ($this->filter) {
            case 'kategori':
                return $this->getKategoriData($query);
            case 'kondisi':
                return $this->getKondisiData($query);
            case 'status':
                return $this->getStatusData($query);
            case 'sumberDana':
                return $this->getSumberDanaData($query);
            case 'perolehanTahunan':
                return $this->getPerolehanTahunanData($query);
            default:
                return $this->getKategoriData($query);
        }
    }

    protected function getKategoriData($query)
    {
        $kategoriData = $query->select('kategori', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori')
            ->get();

        $labels = $kategoriData->pluck('kategori')->toArray();
        $data = $kategoriData->pluck('total')->toArray();

        $colors = $this->generateChartColors(count($labels));
        $borderColors = array_map(function ($color) {
            return str_replace('0.8', '1', $color);
        }, $colors);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Barang per Kategori',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 2,
                    'hoverOffset' => 15,      // Efek hover yang lebih terlihat
                    'cutout' => '70%',        // Membuat donut lebih tipis (efek 3D)
                    'hoverBorderWidth' => 3,  // Border lebih tebal saat hover
                ],
            ],
        ];
    }

    protected function getKondisiData($query)
    {
        $kondisiData = $query->select('kondisi', DB::raw('SUM(jumlah) as total'))
            ->groupBy('kondisi')
            ->get();

        $labels = $kondisiData->pluck('kondisi')->toArray();
        $data = $kondisiData->pluck('total')->toArray();

        // Warna khusus untuk kondisi
        $colors = [
            'Baik' => 'rgba(34, 197, 94, 0.8)',
            'Rusak Ringan' => 'rgba(234, 179, 8, 0.8)',
            'Rusak Berat' => 'rgba(239, 68, 68, 0.8)',
            'Hilang' => 'rgba(100, 116, 139, 0.8)',
        ];

        $backgroundColors = array_map(function ($label) use ($colors) {
            return $colors[$label] ?? 'rgba(107, 114, 128, 0.8)';
        }, $labels);

        $borderColors = array_map(function ($color) {
            return str_replace('0.8', '1', $color);
        }, $backgroundColors);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Barang per Kondisi',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 2,
                    'hoverOffset' => 15,
                    'cutout' => '70%',
                    'hoverBorderWidth' => 3,
                ],
            ],
        ];
    }

    protected function getStatusData($query)
    {
        $statusData = $query->select('status', DB::raw('SUM(jumlah) as total'))
            ->groupBy('status')
            ->get();

        $labels = $statusData->pluck('status')->toArray();
        $data = $statusData->pluck('total')->toArray();

        // Warna khusus untuk status
        $colors = [
            'Tersedia' => 'rgba(34, 197, 94, 0.8)',
            'Digunakan' => 'rgba(59, 130, 246, 0.8)',
            'Dipinjam' => 'rgba(234, 179, 8, 0.8)',
            'Rusak/Tidak Digunakan' => 'rgba(239, 68, 68, 0.8)',
            'Dihapus' => 'rgba(100, 116, 139, 0.8)',
            'Dalam Perbaikan' => 'rgba(168, 85, 247, 0.8)',
            'Tidak Aktif' => 'rgba(156, 163, 175, 0.8)',
        ];

        $backgroundColors = array_map(function ($label) use ($colors) {
            return $colors[$label] ?? 'rgba(107, 114, 128, 0.8)';
        }, $labels);

        $borderColors = array_map(function ($color) {
            return str_replace('0.8', '1', $color);
        }, $backgroundColors);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Barang per Status',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 2,
                    'hoverOffset' => 15,
                    'cutout' => '70%',
                    'hoverBorderWidth' => 3,
                ],
            ],
        ];
    }

    protected function getSumberDanaData($query)
    {
        $sumberDanaData = $query->select('sumber_dana', DB::raw('SUM(nominal_harga) as total_nilai'))
            ->groupBy('sumber_dana')
            ->get();

        $labels = $sumberDanaData->pluck('sumber_dana')->toArray();
        $data = $sumberDanaData->pluck('total_nilai')->toArray();

        $colors = $this->generateChartColors(count($labels));
        $borderColors = array_map(function ($color) {
            return str_replace('0.8', '1', $color);
        }, $colors);

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nilai Inventaris per Sumber Dana',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 2,
                    'hoverOffset' => 15,
                    'cutout' => '70%',
                    'hoverBorderWidth' => 3,
                ],
            ],
        ];
    }

    protected function getPerolehanTahunanData($query)
    {
        $perolehanData = $query->select(
            DB::raw('YEAR(tanggal_perolehan) as tahun'),
            DB::raw('SUM(jumlah) as total_barang'),
            DB::raw('SUM(nominal_harga) as total_nilai')
        )
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();

        $labels = $perolehanData->pluck('tahun')->toArray();
        $dataBarang = $perolehanData->pluck('total_barang')->toArray();
        $dataNilai = $perolehanData->pluck('total_nilai')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Jumlah Barang',
                    'data' => $dataBarang,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.6)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.3,        // Membuat kurva lebih halus
                    'fill' => true,          // Area di bawah garis terisi
                    'pointRadius' => 4,      // Titik data lebih besar
                    'pointHoverRadius' => 6, // Titik data lebih besar saat hover
                    'pointBackgroundColor' => 'rgba(59, 130, 246, 1)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Nilai Inventaris (dalam Rp Juta)',
                    'data' => array_map(function ($nilai) {
                        return round($nilai / 1000000, 2); // Konversi ke juta
                    }, $dataNilai),
                    'backgroundColor' => 'rgba(236, 72, 153, 0.6)',
                    'borderColor' => 'rgba(236, 72, 153, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.3,
                    'fill' => true,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'pointBackgroundColor' => 'rgba(236, 72, 153, 1)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'yAxisID' => 'y1',
                ],
            ],
        ];
    }

    // Fungsi untuk membuat warna chart yang variatif
    protected function generateChartColors(int $count): array
    {
        $colors = [
            'rgba(59, 130, 246, 0.8)',   // Biru
            'rgba(236, 72, 153, 0.8)',   // Pink
            'rgba(34, 197, 94, 0.8)',    // Hijau
            'rgba(249, 115, 22, 0.8)',   // Oranye
            'rgba(139, 92, 246, 0.8)',   // Ungu
            'rgba(234, 179, 8, 0.8)',    // Kuning
            'rgba(14, 165, 233, 0.8)',   // Cyan
            'rgba(239, 68, 68, 0.8)',    // Merah
            'rgba(168, 85, 247, 0.8)',   // Indigo
            'rgba(20, 184, 166, 0.8)',   // Teal
        ];

        // Jika warna lebih sedikit dari jumlah data, ulangi warna
        if ($count <= count($colors)) {
            return array_slice($colors, 0, $count);
        }

        // Jika warna lebih banyak dari yang tersedia, buat warna tambahan
        $result = $colors;
        while (count($result) < $count) {
            $result = array_merge(
                $result,
                array_map(function ($color) {
                    // Sedikit ubah opacity untuk membuat warna berbeda
                    return preg_replace('/0\.\d+\)$/', (0.3 + (mt_rand(0, 5) / 10)) . ')', $color);
                }, $colors)
            );
        }

        return array_slice($result, 0, $count);
    }

    // Tambahkan metode getHeaderActions() untuk tombol switch tipe chart
    protected function getHeaderActions(): array
    {
        $actions = [];

        // Tombol tipe chart hanya ditampilkan untuk data non-tahunan
        if ($this->filter !== 'perolehanTahunan') {
            $actions[] = \Filament\Actions\Action::make('chartType')
                ->label('Tipe Chart')
                ->icon('heroicon-m-chart-pie')
                ->form([
                    \Filament\Forms\Components\Select::make('type')
                        ->label('Pilih Tipe Chart')
                        ->options([
                            'doughnut' => 'Donut',
                            'pie' => 'Pie',
                            'bar' => 'Bar',
                            'polarArea' => 'Polar Area',
                        ])
                        ->default($this->chartType)
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->switchChartType($data['type']);
                });
        }

        return $actions;
    }

    protected function getOptions(): array
    {
        // Opsi dasar yang sederhana
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
                'duration' => 1000, // Lebih cepat, lebih aman
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

        // Opsi default
        return $baseOptions;
    }

    // Tambahkan method untuk judul chart
    protected function getChartTitle(): string
    {
        $title = match ($this->filter) {
            'kategori' => 'Distribusi Kategori Inventaris',
            'kondisi' => 'Distribusi Kondisi Inventaris',
            'status' => 'Distribusi Status Inventaris',
            'sumberDana' => 'Distribusi Sumber Dana',
            'perolehanTahunan' => 'Tren Perolehan per Tahun',
            default => 'Data Inventaris'
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
