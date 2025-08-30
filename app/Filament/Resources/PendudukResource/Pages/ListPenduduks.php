<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use App\Filament\Resources\PendudukResource\Widgets\PendudukStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Radio;
use Carbon\Carbon;
use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Model;
use Closure;
use Filament\Facades\Filament;

class ListPenduduks extends ListRecords
{
    protected static string $resource = PendudukResource::class;

    // Tambahkan properti untuk menyimpan filter
    public ?string $filterPeriode = 'semua';
    public ?string $dariTanggal = null;
    public ?string $sampaiTanggal = null;

    // Inisialisasi tanggal default
    public function mount(): void
    {
        parent::mount();
        $this->applyPeriodeFilter('semua');
    }

    /**
     * --- INI PERBAIKAN UTAMANYA ---
     * Mengganti penggunaan route() dengan getUrl() untuk memastikan
     * parameter 'tenant' disertakan secara otomatis.
     */
    protected function getTableRecordUrlUsing(): ?Closure
    {
        return fn (Model $record): string => static::getResource()::getUrl('view', [
        return fn(Model $record): string => static::getResource()::getUrl('view', [
            'record' => $record,
            'tenant' => Filament::getTenant(),
        ]);
    }

    // Method untuk menerapkan filter periode
    public function applyPeriodeFilter(string $periode, ?string $dariTanggal = null, ?string $sampaiTanggal = null): void
    {
        $this->filterPeriode = $periode;

        // Setel filter berdasarkan periode yang dipilih
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
            default:
                // Semua data
                $this->dariTanggal = null;
                $this->sampaiTanggal = null;
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
                ->label('Tambah Penduduk')
                ->icon('heroicon-o-plus'),

            // Filter periode
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
                        ->default(fn() => $this->filterPeriode)
                        ->live()
                        ->afterStateUpdated(fn($state, callable $set) =>
                        $state === 'kustom' ?: $set('dariTanggal', null) & $set('sampaiTanggal', null)),

                    DatePicker::make('dariTanggal')
                        ->label('Dari Tanggal')
                        ->default(fn() => $this->dariTanggal)
                        ->visible(fn(\Filament\Forms\Get $get) => $get('periode') === 'kustom'),

                    DatePicker::make('sampaiTanggal')
                        ->label('Sampai Tanggal')
                        ->default(fn() => $this->sampaiTanggal)
                        ->visible(fn(\Filament\Forms\Get $get) => $get('periode') === 'kustom'),
                ])
                ->action(function (array $data): void {
                    $this->applyPeriodeFilter($data['periode'], $data['dariTanggal'] ?? null, $data['sampaiTanggal'] ?? null);
                }),

            // Satu tombol Ekspor Data
            Actions\Action::make('exportPenduduk')
                ->label('Ekspor Semua')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    // Filter tambahan jika diperlukan
                    Select::make('jenis_kelamin')
                        ->label('Jenis Kelamin')
                        ->options([
                            '' => 'Semua',
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan',
                        ])
                        ->default(''),

                    Select::make('status_perkawinan')
                        ->label('Status Perkawinan')
                        ->options([
                            '' => 'Semua',
                            'Belum Kawin' => 'Belum Kawin',
                            'Kawin' => 'Kawin',
                            'Cerai Hidup' => 'Cerai Hidup',
                            'Cerai Mati' => 'Cerai Mati',
                        ])
                        ->default(''),

                    // Pilihan format ekspor
                    Radio::make('format')
                        ->label('Format')
                        ->options([
                            'pdf' => 'PDF',
                            'excel' => 'Excel',
                        ])
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

    protected function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkAction::make('export')
                ->label('Ekspor Data')
                ->icon('heroicon-o-document-arrow-up')
                ->form([
                    Radio::make('format')
                        ->label('Format')
                        ->options([
                            'pdf' => 'PDF',
                            'excel' => 'Excel',
                        ])
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
            Tables\Actions\Action::make('export')
                ->label('Ekspor')
                ->icon('heroicon-o-document-arrow-up')
                ->form([
                    Radio::make('format')
                        ->label('Format')
                        ->options([
                            'pdf' => 'PDF',
                            'excel' => 'Excel',
                        ])
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

    // Menerapkan filter ke query tabel
    protected function applyFiltersToTableQuery(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::applyFiltersToTableQuery($query);

        if ($this->dariTanggal && $this->sampaiTanggal) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay(),
            ]);
        }

        return $query;
    }
}
