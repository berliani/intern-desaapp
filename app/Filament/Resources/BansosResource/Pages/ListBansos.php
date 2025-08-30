<?php

namespace App\Filament\Resources\BansosResource\Pages;

use App\Filament\Resources\BansosResource;
use App\Filament\Resources\BansosResource\Widgets\BansosStats;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ListBansos extends ListRecords
{
    protected static string $resource = BansosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label('Tambah Bansos'),

            // Tombol ekspor dengan filter yang disederhanakan
            Actions\Action::make('ekspor')
                ->label('Ekspor Data')
                ->color('success')
                ->icon('heroicon-o-document-arrow-up')
                ->form([
                    Forms\Components\Select::make('jenis_bansos_id')
                        ->label('Jenis Bantuan')
                        ->options(function () {
                            $options = ['' => 'Semua Jenis Bantuan'];
                            return $options + \App\Models\JenisBansos::pluck('nama_bansos', 'id')->toArray();
                        })
                        ->default('')
                        ->searchable()
                        ->preload(),

                    Forms\Components\Select::make('status')
                        ->label('Status Bantuan')
                        ->options(function () {
                            $options = ['' => 'Semua Status'];
                            return $options + \App\Models\Bansos::getStatusOptions();
                        })
                        ->default(''),

                    Forms\Components\Select::make('prioritas')
                        ->label('Prioritas')
                        ->options(function () {
                            $options = ['' => 'Semua Prioritas'];
                            return $options + \App\Models\Bansos::getPrioritasOptions();
                        })
                        ->default(''),

                    Forms\Components\Select::make('sumber_pengajuan')
                        ->label('Sumber Pengajuan')
                        ->options(function () {
                            $options = ['' => 'Semua Sumber'];
                            return $options + \App\Models\Bansos::getSumberPengajuanOptions();
                        })
                        ->default(''),

                    // Periode dengan opsi yang disederhanakan
                    Forms\Components\Select::make('periode')
                        ->label('Periode Pengajuan')
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
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state !== 'kustom') {
                                $set('dari_tanggal', null);
                                $set('sampai_tanggal', null);
                            }
                        }),

                    // Menggunakan dua DatePicker terpisah dalam grid
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('dari_tanggal')
                                ->label('Dari Tanggal')
                                ->visible(fn(Forms\Get $get) => $get('periode') === 'kustom'),

                            Forms\Components\DatePicker::make('sampai_tanggal')
                                ->label('Sampai Tanggal')
                                ->visible(fn(Forms\Get $get) => $get('periode') === 'kustom'),
                        ]),

                    // Format ekspor
                    Forms\Components\Radio::make('format')
                        ->label('Format Ekspor')
                        ->options([
                            'pdf' => 'PDF',
                            'excel' => 'Excel',
                        ])
                        ->default('pdf')
                        ->required()
                        ->inline(),
                ])
                ->action(function (array $data): void {
                    // Proses periode ke tanggal
                    $dariTanggal = null;
                    $sampaiTanggal = null;

                    if ($data['periode'] === 'hari_ini') {
                        $dariTanggal = today()->format('Y-m-d');
                        $sampaiTanggal = today()->format('Y-m-d');
                    } elseif ($data['periode'] === 'minggu_ini') {
                        $dariTanggal = today()->startOfWeek()->format('Y-m-d');
                        $sampaiTanggal = today()->endOfWeek()->format('Y-m-d');
                    } elseif ($data['periode'] === 'bulan_ini') {
                        $dariTanggal = today()->startOfMonth()->format('Y-m-d');
                        $sampaiTanggal = today()->endOfMonth()->format('Y-m-d');
                    } elseif ($data['periode'] === 'tahun_ini') {
                        $dariTanggal = today()->startOfYear()->format('Y-m-d');
                        $sampaiTanggal = today()->endOfYear()->format('Y-m-d');
                    } elseif ($data['periode'] === 'bulan_lalu') {
                        $dariTanggal = today()->subMonth()->startOfMonth()->format('Y-m-d');
                        $sampaiTanggal = today()->subMonth()->endOfMonth()->format('Y-m-d');
                    } elseif ($data['periode'] === 'tahun_lalu') {
                        $dariTanggal = today()->subYear()->startOfYear()->format('Y-m-d');
                        $sampaiTanggal = today()->subYear()->endOfYear()->format('Y-m-d');
                    } elseif ($data['periode'] === 'kustom') {
                        $dariTanggal = isset($data['dari_tanggal']) ? Carbon::parse($data['dari_tanggal'])->format('Y-m-d') : null;
                        $sampaiTanggal = isset($data['sampai_tanggal']) ? Carbon::parse($data['sampai_tanggal'])->format('Y-m-d') : null;
                    }

                    // Redirect ke halaman export dengan filter
                    $query = http_build_query([
                        'jenis_bansos_id' => $data['jenis_bansos_id'] ?? null,
                        'status' => $data['status'] ?? null,
                        'prioritas' => $data['prioritas'] ?? null,
                        'sumber_pengajuan' => $data['sumber_pengajuan'] ?? null,
                        'dari_tanggal' => $dariTanggal,
                        'sampai_tanggal' => $sampaiTanggal,
                        'format' => $data['format'] ?? 'pdf',
                    ]);

                    redirect()->to(route('export.bansos.all') . '?' . $query);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            BansosStats::class,
        ];
    }
}
