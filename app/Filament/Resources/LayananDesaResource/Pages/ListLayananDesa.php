<?php

namespace App\Filament\Resources\LayananDesaResource\Pages;

use App\Filament\Resources\LayananDesaResource;
use App\Filament\Resources\LayananDesaResource\Widgets\LayananStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Carbon\Carbon;
use Filament\Forms;
use App\Models\LayananDesa;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

class ListLayananDesa extends ListRecords
{
    protected static string $resource = LayananDesaResource::class;

    // Tambahkan properti untuk menyimpan filter
    public ?string $filterPeriode = 'bulan_ini';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    // Inisialisasi tanggal default
    public function mount(): void
    {
        parent::mount();
        $this->applyPeriodeFilter('bulan_ini');
    }

    // Method untuk menerapkan filter periode
    public function applyPeriodeFilter(string $periode): void
    {
        $this->filterPeriode = $periode;

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
                $this->dariTanggal = null;
                $this->sampaiTanggal = null;
                break;
            default:
                // Untuk kustom, tanggal akan diisi oleh form
                break;
        }

        // Broadcast event ke semua widget
        $this->dispatch('global-filter-changed', [
            'periode' => $this->filterPeriode,
            'dariTanggal' => $this->dariTanggal,
            'sampaiTanggal' => $this->sampaiTanggal
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Layanan Baru')
                ->icon('heroicon-o-plus'),

            // Ekspor dengan form yang lebih sederhana
            Actions\Action::make('exportAll')
                ->label('Ekspor Semua')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    // Hanya kategori dan pilihan periode sederhana
                    Forms\Components\Select::make('kategori')
                        ->label('Kategori')
                        ->options(LayananDesaResource::getKategoriLayanan())
                        ->placeholder('Semua Kategori'),

                    // Periode yang disederhanakan
                    Forms\Components\Select::make('periode')
                        ->label('Periode')
                        ->options([
                            'semua' => 'Semua Waktu',
                            'hari_ini' => 'Hari Ini',
                            'minggu_ini' => 'Minggu Ini',
                            'bulan_ini' => 'Bulan Ini',
                            'bulan_lalu' => 'Bulan Lalu',
                            'tahun_ini' => 'Tahun Ini',
                            'kustom' => 'Kustom (Pilih Tanggal)',
                        ])
                        ->default('bulan_ini')
                        ->reactive()
                        ->afterStateUpdated(function (callable $get, callable $set, ?string $state) {
                            // Reset tanggal kustom jika periode bukan kustom
                            if ($state !== 'kustom') {
                                $set('dari_tanggal', null);
                                $set('sampai_tanggal', null);
                            }
                        }),

                    // Tanggal kustom hanya ditampilkan jika periode = kustom
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('dari_tanggal')
                                ->label('Dari Tanggal'),
                            Forms\Components\DatePicker::make('sampai_tanggal')
                                ->label('Sampai Tanggal'),
                        ])
                        ->visible(fn (callable $get) => $get('periode') === 'kustom'),

                    // Hanya format PDF dan Excel
                    Forms\Components\Select::make('format')
                        ->label('Format Ekspor')
                        ->options([
                            'pdf' => 'PDF',
                            'excel' => 'Excel',
                        ])
                        ->default('pdf')
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Proses periode yang dipilih menjadi tanggal
                    $dariTanggal = null;
                    $sampaiTanggal = null;

                    if ($data['periode'] !== 'kustom') {
                        switch ($data['periode']) {
                            case 'hari_ini':
                                $dariTanggal = now()->toDateString();
                                $sampaiTanggal = now()->toDateString();
                                break;
                            case 'minggu_ini':
                                $dariTanggal = now()->startOfWeek()->toDateString();
                                $sampaiTanggal = now()->endOfWeek()->toDateString();
                                break;
                            case 'bulan_ini':
                                $dariTanggal = now()->startOfMonth()->toDateString();
                                $sampaiTanggal = now()->endOfMonth()->toDateString();
                                break;
                            case 'bulan_lalu':
                                $dariTanggal = now()->subMonth()->startOfMonth()->toDateString();
                                $sampaiTanggal = now()->subMonth()->endOfMonth()->toDateString();
                                break;
                            case 'tahun_ini':
                                $dariTanggal = now()->startOfYear()->toDateString();
                                $sampaiTanggal = now()->endOfYear()->toDateString();
                                break;
                            // Untuk 'semua', biarkan null
                        }
                    } else {
                        // Gunakan tanggal yang dipilih pengguna
                        $dariTanggal = $data['dari_tanggal'] ?? null;
                        $sampaiTanggal = $data['sampai_tanggal'] ?? null;
                    }

                    // Gunakan nama route yang sudah diupdate (layanan.export.all)
                    return redirect()->route('layanan.export.all', [
                        'kategori' => $data['kategori'] ?? null,
                        'dari_tanggal' => $dariTanggal,
                        'sampai_tanggal' => $sampaiTanggal,
                        'format' => $data['format'] ?? 'pdf',
                    ]);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LayananStats::class,
        ];
    }


   protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {

        $query = parent::getTableQuery();


        $query->where('company_id', Filament::getTenant()->id);


        if ($this->dariTanggal && $this->sampaiTanggal) {
            $query->whereBetween('created_at', [
                \Carbon\Carbon::parse($this->dariTanggal)->startOfDay(),
                \Carbon\Carbon::parse($this->sampaiTanggal)->endOfDay(),
            ]);
        }

        return $query;
    }
}
