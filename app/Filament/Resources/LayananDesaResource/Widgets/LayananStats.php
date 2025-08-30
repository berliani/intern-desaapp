<?php

namespace App\Filament\Resources\LayananDesaResource\Widgets;

use App\Filament\Resources\LayananDesaResource;
use App\Models\LayananDesa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Livewire\Attributes\On;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;

class LayananStats extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected int | string | array $columnSpan = 'full';

    // Properties untuk filter
    public ?string $periode = 'bulan_ini';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    public function mount(): void
    {
        // Set nilai default untuk periode bulan ini
        $this->setPeriodeFilter('bulan_ini');
    }

    // Helper untuk mengatur periode filter
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

        $this->periode = $periodeMap[$periode] ?? $periode;

        // Set tanggal sesuai periode
        switch ($this->periode) {
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
                $this->dariTanggal = null;
                $this->sampaiTanggal = null;
                break;
        }
    }

    // Listener untuk event global-filter-changed dari page
    #[On('global-filter-changed')]
    public function handleGlobalFilterChanged($data): void
    {
        if (isset($data['periode'])) {
            $this->periode = $data['periode'];
        }

        if (isset($data['dariTanggal'])) {
            $this->dariTanggal = $data['dariTanggal'];
        }

        if (isset($data['sampaiTanggal'])) {
            $this->sampaiTanggal = $data['sampaiTanggal'];
        }

        // Debug log untuk memastikan filter berhasil diterima
        \Log::info('LayananStats menerima filter', [
            'periode' => $this->periode,
            'dariTanggal' => $this->dariTanggal,
            'sampaiTanggal' => $this->sampaiTanggal
        ]);
    }

    // Tambahkan listener untuk filter-changed (event dari dashboard)
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
            // Gunakan setPeriodeFilter untuk mengatur tanggal berdasarkan periode
            $this->setPeriodeFilter($periode);
        }

        // Refresh widget
        $this->dispatch('$refresh');

        // Log debug (opsional)
        \Log::info('LayananStats menerima filter dari dashboard', [
            'periode' => $this->periode,
            'dariTanggal' => $this->dariTanggal,
            'sampaiTanggal' => $this->sampaiTanggal
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('filter')
                ->label('Filter Periode')
                ->icon('heroicon-m-funnel')
                ->form([
                    Select::make('filter')
                        ->label('Filter Waktu')
                        ->options([
                            'semua' => 'Semua Waktu',
                            'hari_ini' => 'Hari Ini',
                            'minggu_ini' => 'Minggu Ini',
                            'bulan_ini' => 'Bulan Ini',
                            'tahun_ini' => 'Tahun Ini',
                            'kustom' => 'Kustom',
                        ])
                        ->default($this->periode)
                        ->live(),

                    \Filament\Forms\Components\DatePicker::make('dariTanggal')
                        ->label('Dari Tanggal')
                        ->default($this->dariTanggal ?? now()->startOfMonth())
                        ->visible(fn (callable $get) => $get('filter') === 'kustom'),

                    \Filament\Forms\Components\DatePicker::make('sampaiTanggal')
                        ->label('Sampai Tanggal')
                        ->default($this->sampaiTanggal ?? now()->endOfMonth())
                        ->visible(fn (callable $get) => $get('filter') === 'kustom'),
                ])
                ->action(function (array $data): void {
                    $this->dispatch('filter-changed',
                        filter: $data['filter'],
                        startDate: $data['dariTanggal'] ?? null,
                        endDate: $data['sampaiTanggal'] ?? null
                    );
                }),
        ];
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
            'semua' => 'Semua Waktu',
            default => 'Periode'
        };
    }

    protected function getStats(): array
    {
        $tenant = Filament::getTenant();

        // --- PERUBAHAN UTAMA DI SINI ---
        $baseQuery = LayananDesa::query()
        ->withTrashed()
        ->where('company_id', $tenant->id);

        if ($this->dariTanggal && $this->sampaiTanggal) {
            $baseQuery->whereBetween('created_at', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay()
            ]);
        }

        $totalLayanan = (clone $baseQuery)->count();
        $kategoriStats = (clone $baseQuery)
            ->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        $kategoriTerbanyak = (clone $baseQuery)
            ->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->first();

        $periodeDisplay = $this->getPeriodeDisplayText();

        // Debug untuk memeriksa nilai filter
        \Log::info('LayananStats Filter', [
            'periode' => $this->periode,
            'dariTanggal' => $this->dariTanggal,
            'sampaiTanggal' => $this->sampaiTanggal
        ]);

        // Hitung total layanan dengan filter yang diterapkan
        $totalLayanan = $baseQuery->count();

        // Hitung layanan per kategori dengan filter yang diterapkan
        $kategoriStats = $this->hitungKategori($baseQuery);

        // Kategori dengan layanan terbanyak dengan filter yang diterapkan
        $kategoriTerbanyak = $this->getKategoriTerbanyak($baseQuery);

        // Layanan terbaru
        $layananTerbaru = $this->getLayananTerbaru($baseQuery);

        // Hitung tren bulanan
        $trendBulanan = $this->getTrendBulanan();

        // Informasi periode yang digunakan
        $periodeDisplay = $this->getPeriodeDisplayText();

        return [
            Stat::make('Total Layanan', number_format($totalLayanan, 0, ',', '.'))
                ->description("Periode: {$periodeDisplay}")
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary')
                ->chart($trendBulanan),

            Stat::make($kategoriTerbanyak['nama'], number_format($kategoriTerbanyak['jumlah'], 0, ',', '.'))
                ->description('Kategori layanan terbanyak')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($this->getColorForCategory($kategoriTerbanyak['nama'])),

            Stat::make('Layanan Surat', number_format($kategoriStats['Surat'] ?? 0, 0, ',', '.'))
                ->description('Layanan administratif')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Layanan Kesehatan', number_format($kategoriStats['Kesehatan'] ?? 0, 0, ',', '.'))
                ->description('Layanan kesehatan')
                ->descriptionIcon('heroicon-m-heart')
                ->color('success'),

            Stat::make('Layanan Pendidikan', number_format($kategoriStats['Pendidikan'] ?? 0, 0, ',', '.'))
                ->description('Layanan pendidikan')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('warning'),

            // Stat::make('Aktivitas', $layananTerbaru)
            //     ->description('Layanan terbaru')
            //     ->descriptionIcon('heroicon-m-clock')
            //     ->color('gray'),
        ];
    }

    protected function getTrendBulanan(): array
    {
        $trendData = [];

        // Ambil 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            // Siapkan query dasar
            $query = LayananDesa::query();

            // Jika ada filter aktif, terapkan batasan periode global ke tren
            if ($this->periode === 'tahun_ini') {
                // Batasi hanya pada tahun ini
                $query->whereYear('created_at', now()->year);
            } elseif ($this->periode === 'tahun_lalu') {
                // Batasi hanya pada tahun lalu
                $query->whereYear('created_at', now()->subYear()->year);
            }

            // Hitung layanan per bulan
            $count = $query->whereBetween('created_at', [
                $startOfMonth->toDateTimeString(),
                $endOfMonth->toDateTimeString()
            ])->count();

            $trendData[] = $count;
        }

        return $trendData;
    }

    protected function hitungKategori($query = null): array
    {
        if (!$query) {
            $query = LayananDesa::query();

            // Terapkan filter tanggal jika ada
            if ($this->dariTanggal && $this->sampaiTanggal && $this->periode !== 'semua') {
                $startDateTime = Carbon::parse($this->dariTanggal)->startOfDay();
                $endDateTime = Carbon::parse($this->sampaiTanggal)->endOfDay();

                $query->whereBetween('created_at', [
                    $startDateTime->toDateTimeString(),
                    $endDateTime->toDateTimeString()
                ]);
            }
        }

        // Clone query untuk menghindari modifikasi query asli
        $kategoriCounts = (clone $query)
            ->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->toArray();

        return $kategoriCounts;
    }

    protected function getKategoriTerbanyak($query = null): array
    {
        if (!$query) {
            $query = LayananDesa::query();

            // Terapkan filter tanggal jika ada
            if ($this->dariTanggal && $this->sampaiTanggal && $this->periode !== 'semua') {
                $startDateTime = Carbon::parse($this->dariTanggal)->startOfDay();
                $endDateTime = Carbon::parse($this->sampaiTanggal)->endOfDay();

                $query->whereBetween('created_at', [
                    $startDateTime->toDateTimeString(),
                    $endDateTime->toDateTimeString()
                ]);
            }
        }

        // Clone query untuk menghindari modifikasi query asli
        $kategoriStats = (clone $query)
            ->select('kategori', DB::raw('count(*) as total'))
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->first();

        if (!$kategoriStats) {
            return [
                'nama' => 'Tidak Ada Data',
                'jumlah' => 0
            ];
        }

        return [
            'nama' => $kategoriStats->kategori,
            'jumlah' => $kategoriStats->total
        ];
    }

    protected function getLayananTerbaru($query = null): string
    {
        if (!$query) {
            $query = LayananDesa::query();

            // Terapkan filter tanggal jika ada
            if ($this->dariTanggal && $this->sampaiTanggal && $this->periode !== 'semua') {
                $startDateTime = Carbon::parse($this->dariTanggal)->startOfDay();
                $endDateTime = Carbon::parse($this->sampaiTanggal)->endOfDay();

                $query->whereBetween('created_at', [
                    $startDateTime->toDateTimeString(),
                    $endDateTime->toDateTimeString()
                ]);
            }
        }

        // Clone query untuk menghindari modifikasi query asli
        $layanan = (clone $query)
            ->orderByDesc('created_at')
            ->first();

        if (!$layanan) {
            return 'Belum ada layanan';
        }

        // Tampilkan informasi lebih lengkap
        $timeDiff = Carbon::parse($layanan->created_at)->diffForHumans();
        return "{$layanan->nama_layanan} ({$timeDiff})";
    }

    protected function getColorForCategory(string $category): string
    {
        $colors = LayananDesaResource::getKategoriColors();
        return $colors[$category] ?? 'gray';
    }
}
