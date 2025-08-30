<?php

namespace App\Filament\Resources\PengaduanResource\Pages;

use App\Filament\Resources\PengaduanResource;
use App\Filament\Resources\PengaduanResource\Widgets\PengaduanStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Form;
use Illuminate\Support\Facades\Route;
use App\Models\Pengaduan;
use App\Models\ProfilDesa;
use Filament\Forms\Components\Radio;
use Carbon\Carbon;
use Filament\Facades\Filament;

class ListPengaduan extends ListRecords
{
    protected static string $resource = PengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\Action::make('exportAll')
                ->label('Ekspor Semua')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    Select::make('status')
                        ->label('Status')
                        ->options(Pengaduan::getStatusOptions())
                        ->placeholder('Semua Status'),

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
                            'kustom' => 'Kustom (Pilih Tanggal)',
                        ])
                        ->default('bulan_ini')
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state !== 'kustom') {
                                // Reset tanggal kustom jika bukan pilihan kustom
                                $set('dari_tanggal', null);
                                $set('sampai_tanggal', null);
                            }
                        }),

                    // Grid untuk tanggal kustom
                    Grid::make(2)
                        ->schema([
                            DatePicker::make('dari_tanggal')
                                ->label('Dari Tanggal')
                                ->visible(fn($get) => $get('periode') === 'kustom'),

                            DatePicker::make('sampai_tanggal')
                                ->label('Sampai Tanggal')
                                ->visible(fn($get) => $get('periode') === 'kustom'),
                        ]),

                    Radio::make('format')
                        ->label('Format Ekspor')
                        ->options([
                            'pdf' => 'PDF',
                            'excel' => 'Excel',
                        ])
                        ->default('pdf')
                        ->required()
                        ->inline(),
                ])
                ->action(function (array $data) {
                    // Proses periode ke tanggal
                    $dariTanggal = null;
                    $sampaiTanggal = null;

                    // Konversi periode ke tanggal - lebih eksplisit untuk debugging
                    if ($data['periode'] === 'hari_ini') {
                        $dariTanggal = Carbon::today()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->format('Y-m-d');
                    } elseif ($data['periode'] === 'minggu_ini') {
                        $dariTanggal = Carbon::today()->startOfWeek()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->endOfWeek()->format('Y-m-d');
                    } elseif ($data['periode'] === 'bulan_ini') {
                        $dariTanggal = Carbon::today()->startOfMonth()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->endOfMonth()->format('Y-m-d');
                    } elseif ($data['periode'] === 'tahun_ini') {
                        $dariTanggal = Carbon::today()->startOfYear()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->endOfYear()->format('Y-m-d');
                    } elseif ($data['periode'] === 'bulan_lalu') {
                        $dariTanggal = Carbon::today()->subMonth()->startOfMonth()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->subMonth()->endOfMonth()->format('Y-m-d');
                    } elseif ($data['periode'] === 'tahun_lalu') {
                        $dariTanggal = Carbon::today()->subYear()->startOfYear()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->subYear()->endOfYear()->format('Y-m-d');
                    } elseif ($data['periode'] === 'kustom') {
                        $dariTanggal = isset($data['dari_tanggal']) ? $data['dari_tanggal']->format('Y-m-d') : null;
                        $sampaiTanggal = isset($data['sampai_tanggal']) ? $data['sampai_tanggal']->format('Y-m-d') : null;
                    }

                    // Buat array parameter
                    $params = [
                        'format' => $data['format'] ?? 'pdf',
                    ];

                    // Tambahkan parameter yang tidak null
                    if (!empty($data['status'])) {
                        $params['status'] = $data['status'];
                    }

                    if ($dariTanggal) {
                        $params['dari_tanggal'] = $dariTanggal;
                    }

                    if ($sampaiTanggal) {
                        $params['sampai_tanggal'] = $sampaiTanggal;
                    }

                    return redirect()->route('pengaduan.export.all', $params);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PengaduanStats::class,
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            // Bulk action untuk export telah dihapus karena sudah didefinisikan
            // di PengaduanResource
        ];
    }
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {

        $query = parent::getTableQuery();

        if (property_exists($this, 'dariTanggal') && $this->dariTanggal && $this->sampaiTanggal) {
            $query->whereBetween('created_at', [
                \Carbon\Carbon::parse($this->dariTanggal)->startOfDay(),
                \Carbon\Carbon::parse($this->sampaiTanggal)->endOfDay(),
            ]);
        }

        return $query;
    }
}
