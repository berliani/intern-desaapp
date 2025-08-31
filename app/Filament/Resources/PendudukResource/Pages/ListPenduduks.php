<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use App\Filament\Resources\PendudukResource\Widgets\PendudukStats;
use App\Models\Penduduk;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Closure;

class ListPenduduks extends ListRecords
{
    protected static string $resource = PendudukResource::class;

    // Properti untuk menyimpan state filter
    public ?string $filterPeriode = 'semua';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    // Inisialisasi tanggal default saat komponen dimuat
    public function mount(): void
    {
        parent::mount();
        $this->applyPeriodeFilter('semua');
    }

    // Method untuk menerapkan filter periode pada properti publik
    public function applyPeriodeFilter(string $periode, ?string $dariTanggal = null, ?string $sampaiTanggal = null): void
    {
        $this->filterPeriode = $periode;

        // Setel rentang tanggal berdasarkan periode yang dipilih
        switch ($periode) {
            case 'hari_ini':
                $this->dariTanggal = Carbon::today()->format('Y-m-d');
                $this->sampaiTanggal = Carbon::today()->format('Y-m-d');
                break;
            case 'minggu_ini':
                $this->dariTanggal = Carbon::now()->startOfWeek()->format('Y-m-d');
                $this->sampaiTanggal = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'bulan_ini':
                $this->dariTanggal = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->sampaiTanggal = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'tahun_ini':
                $this->dariTanggal = Carbon::now()->startOfYear()->format('Y-m-d');
                $this->sampaiTanggal = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
            case 'bulan_lalu':
                $this->dariTanggal = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->sampaiTanggal = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'tahun_lalu':
                $this->dariTanggal = Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
                $this->sampaiTanggal = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
                break;
            case 'kustom':
                $this->dariTanggal = $dariTanggal;
                $this->sampaiTanggal = $sampaiTanggal;
                break;
            default: // 'semua'
                $this->dariTanggal = null;
                $this->sampaiTanggal = null;
                break;
        }

        // Kirim event yang bisa didengarkan oleh widget untuk memperbarui data mereka
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
                ->label('Tambah Penduduk')
                ->icon('heroicon-o-plus'),

            Actions\Action::make('filterPeriode')
                ->label('Filter Periode')
                ->icon('heroicon-o-funnel')
                ->form([
                    Select::make('periode')
                        ->label('Periode')
                        ->options([
                            'semua' => 'Semua Waktu',
                            'hari_ini' => 'Hari Ini',
                            'minggu_ini' => 'Minggu Ini',
                            'bulan_ini' => 'Bulan Ini',
                            'tahun_ini' => 'Tahun Ini',
                            'bulan_lalu' => 'Bulan Lalu',
                            'tahun_lalu' => 'Tahun Lalu',
                            'kustom' => 'Kustom',
                        ])
                        ->default(fn () => $this->filterPeriode)
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state !== 'kustom') {
                                $set('dariTanggal', null);
                                $set('sampaiTanggal', null);
                            }
                        }),
                    DatePicker::make('dariTanggal')
                        ->label('Dari Tanggal')
                        ->default(fn () => $this->dariTanggal)
                        ->visible(fn (\Filament\Forms\Get $get) => $get('periode') === 'kustom'),
                    DatePicker::make('sampaiTanggal')
                        ->label('Sampai Tanggal')
                        ->default(fn () => $this->sampaiTanggal)
                        ->visible(fn (\Filament\Forms\Get $get) => $get('periode') === 'kustom'),
                ])
                ->action(function (array $data): void {
                    $this->applyPeriodeFilter($data['periode'], $data['dariTanggal'] ?? null, $data['sampaiTanggal'] ?? null);
                }),

            Actions\Action::make('exportPenduduk')
                ->label('Ekspor Semua')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options(['' => 'Semua', 'L' => 'Laki-laki', 'P' => 'Perempuan'])
                        ->default(''),
                    Select::make('status_perkawinan')
                        ->label('Status Perkawinan')
                        ->options(['' => 'Semua', 'Belum Kawin' => 'Belum Kawin', 'Kawin' => 'Kawin', 'Cerai Hidup' => 'Cerai Hidup', 'Cerai Mati' => 'Cerai Mati'])
                        ->default(''),
                    Radio::make('format')
                        ->label('Format')
                        ->options(['pdf' => 'PDF', 'excel' => 'Excel'])
                        ->default('pdf')
                        ->required()
                        ->inline(),
                ])
                ->action(function (array $data): void {
                    $params = [
                        'format' => $data['format'],
                        'jenis_kelamin' => $data['jenis_kelamin'],
                        'status_perkawinan' => $data['status_perkawinan'],
                    ];

                    if ($this->filterPeriode && $this->filterPeriode !== 'semua') {
                        $params['dari_tanggal'] = $this->dariTanggal;
                        $params['sampai_tanggal'] = $this->sampaiTanggal;
                    }

                    $url = route('export.penduduk.all', $params);
                    $this->redirect($url);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PendudukStats::class,
        ];
    }

    /**
     * PERBAIKAN: Mengubah hak akses menjadi public agar sesuai dengan class induk (ListRecords).
     * Ini akan mengatasi error FatalError mengenai access level.
     */
    public function getFilteredTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getFilteredTableQuery();

        if ($this->dariTanggal && $this->sampaiTanggal) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay(),
            ]);
        }

        return $query;
    }

    protected function getTableBulkActions(): array
    {
        return [
            DeleteBulkAction::make(),
            BulkAction::make('export')
                ->label('Ekspor Data Terpilih')
                ->icon('heroicon-o-document-arrow-up')
                ->form([
                    Radio::make('format')
                        ->label('Format')
                        ->options(['pdf' => 'PDF', 'excel' => 'Excel'])
                        ->default('pdf')
                        ->required()
                        ->inline(),
                ])
                ->action(function (Collection $records, array $data): void {
                    $ids = $records->pluck('id')->implode(',');
                    $url = route('export.penduduk.selected', [
                        'ids' => $ids,
                        'format' => $data['format']
                    ]);
                    $this->redirect($url);
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\EditAction::make(),
            Actions\Action::make('export')
                ->label('Ekspor')
                ->icon('heroicon-o-document-arrow-up')
                ->form([
                    Radio::make('format')
                        ->label('Format')
                        ->options(['pdf' => 'PDF', 'excel' => 'Excel'])
                        ->default('pdf')
                        ->required()
                        ->inline(),
                ])
                ->action(function (Penduduk $record, array $data): void {
                    $url = route('export.penduduk', [
                        'penduduk' => $record,
                        'format' => $data['format']
                    ]);
                    $this->redirect($url);
                }),
        ];
    }
}
