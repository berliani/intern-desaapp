<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Filament\Resources\KeuanganDesaResource\Widgets\KeuanganStats;
use App\Filament\Resources\KeuanganDesaResource\Widgets\KeuanganChart;
use App\Filament\Resources\PendudukResource\Widgets\PendudukStats;
use App\Filament\Resources\PendudukResource\Widgets\PendudukChart;
use App\Filament\Resources\InventarisResource\Widgets\InventarisStats;
use App\Filament\Resources\InventarisResource\Widgets\InventarisChart;
use App\Filament\Resources\BansosResource\Widgets\BansosStats;
use App\Filament\Resources\BansosResource\Widgets\BansosChart;
use App\Filament\Resources\JenisBansosResource\Widgets\JenisBansosStats;
use App\Filament\Resources\JenisBansosResource\Widgets\JenisBansosChart;
use App\Filament\Resources\PengaduanResource\Widgets\PengaduanStats;
use App\Filament\Resources\PengaduanResource\Widgets\PengaduanChart;
use App\Filament\Resources\LayananDesaResource\Widgets\LayananStats;
use App\Filament\Resources\LayananDesaResource\Widgets\LayananChart;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Dashboard Desa Digital';
    protected static ?string $navigationGroup = 'Dashboard';
    protected static ?int $navigationSort = 1;

    // Tab property untuk pelacakan tab aktif
    public ?string $activeTab = 'keuangan';

    // Metode untuk mengganti tab aktif
    public function switchTab(string $tabId): void
    {
        $this->clearWidgetCache();
        $this->activeTab = $tabId;
    }

    // Header actions untuk dropdown menu tab dan filter
    protected function getHeaderActions(): array
    {
        return [
            // Menu Tab
            Action::make('tabMenu')
                ->label('Menu Dashboard')
                ->extraAttributes([
                    'class' => 'filament-tab-dropdown !p-0 !shadow-none !bg-transparent',
                    'style' => 'min-width: auto;'
                ])
                ->view('filament.actions.dashboard-tab-dropdown'),

            // Filter periode yang sudah ada
            Action::make('filterPeriode')
                ->label('Filter Periode')
                ->icon('heroicon-o-funnel')
                ->form([
                    Select::make('periode')
                        ->label('Pilih Periode')
                        ->options([
                            'semua_waktu' => 'Semua Waktu',
                            'hari_ini' => 'Hari Ini',
                            'minggu_ini' => 'Minggu Ini',
                            'bulan_ini' => 'Bulan Ini',
                            'tahun_ini' => 'Tahun Ini',
                            'bulan_lalu' => 'Bulan Lalu',
                            'tahun_lalu' => 'Tahun Lalu',
                            'kustom' => 'Kustom (Pilih Tanggal)',
                        ])
                        ->default('bulan_ini')
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state === 'semua_waktu') {
                                $set('dari_tanggal', null);
                                $set('sampai_tanggal', null);
                                return;
                            }

                            switch ($state) {
                                case 'hari_ini':
                                    $set('dari_tanggal', now()->toDateString());
                                    $set('sampai_tanggal', now()->toDateString());
                                    break;
                                case 'minggu_ini':
                                    $set('dari_tanggal', now()->startOfWeek()->toDateString());
                                    $set('sampai_tanggal', now()->endOfWeek()->toDateString());
                                    break;
                                case 'bulan_ini':
                                    $set('dari_tanggal', now()->startOfMonth()->toDateString());
                                    $set('sampai_tanggal', now()->endOfMonth()->toDateString());
                                    break;
                                case 'tahun_ini':
                                    $set('dari_tanggal', now()->startOfYear()->toDateString());
                                    $set('sampai_tanggal', now()->endOfYear()->toDateString());
                                    break;
                                case 'bulan_lalu':
                                    $set('dari_tanggal', now()->subMonth()->startOfMonth()->toDateString());
                                    $set('sampai_tanggal', now()->subMonth()->endOfMonth()->toDateString());
                                    break;
                                case 'tahun_lalu':
                                    $set('dari_tanggal', now()->subYear()->startOfYear()->toDateString());
                                    $set('sampai_tanggal', now()->subYear()->endOfYear()->toDateString());
                                    break;
                            }
                        }),
                    DatePicker::make('dari_tanggal')
                        ->label('Dari Tanggal')
                        ->required()
                        ->visible(fn ($get) => $get('periode') === 'kustom'),
                    DatePicker::make('sampai_tanggal')
                        ->label('Sampai Tanggal')
                        ->required()
                        ->visible(fn ($get) => $get('periode') === 'kustom'),
                ])
                ->action(function (array $data): void {
                    $periode = $data['periode'];
                    $dari = $data['dari_tanggal'] ?? null;
                    $sampai = $data['sampai_tanggal'] ?? null;

                    $this->clearWidgetCache();

                    $this->dispatch('filter-changed',
                        dari_tanggal: $dari,
                        sampai_tanggal: $sampai,
                        periode: $periode
                    );
                }),
        ];
    }

    // Widget berdasarkan tab aktif dengan caching
    protected function getHeaderWidgets(): array
    {
        $cacheKey = "dashboard_widgets_{$this->activeTab}";

        return Cache::remember($cacheKey, 60, function () {
            if ($this->activeTab === 'keuangan') {
                return [
                    KeuanganStats::make(),
                    KeuanganChart::make(),
                ];
            } elseif ($this->activeTab === 'penduduk') {
                return [
                    PendudukStats::make(),
                    PendudukChart::make(),
                ];
            } elseif ($this->activeTab === 'inventaris') {
                return [
                    InventarisStats::make(),
                    InventarisChart::make(),
                ];
            } elseif ($this->activeTab === 'bansos') {
                return [
                    JenisBansosStats::make(),
                    JenisBansosChart::make(),
                ];
            } elseif ($this->activeTab === 'penerima_bansos') {
                return [
                    BansosStats::make(),
                    BansosChart::make(),
                ];
            } elseif ($this->activeTab === 'pengaduan') {
                return [
                    PengaduanStats::make(),
                    PengaduanChart::make(),
                ];
            } elseif ($this->activeTab === 'layanan') {
                return [
                    LayananStats::make(),
                    LayananChart::make(),
                ];
            }

            return [];
        });
    }

    // Tambahkan method untuk membersihkan cache saat filter berubah
    public function clearWidgetCache(): void
    {
        $cacheKey = "dashboard_widgets_{$this->activeTab}";
        Cache::forget($cacheKey);
    }

    // Widget yang akan ditampilkan di bawah header
    protected function getFooterWidgets(): array
    {
        return []; // Kosong karena semua sudah di StatsOverviewWidget
    }
}
