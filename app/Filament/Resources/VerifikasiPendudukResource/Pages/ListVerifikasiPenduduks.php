<?php

namespace App\Filament\Resources\VerifikasiPendudukResource\Pages;

use App\Filament\Resources\VerifikasiPendudukResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Carbon\Carbon;
use Filament\Forms;

class ListVerifikasiPenduduks extends ListRecords
{
    protected static string $resource = VerifikasiPendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tambahkan action ekspor semua
            Actions\Action::make('exportAll')
                ->label('Ekspor Semua')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('status')
                        ->label('Status Verifikasi')
                        ->options([
                            'all' => 'Semua Status',
                            'pending' => 'Pending',
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                        ])
                        ->default('all'),

                    Forms\Components\Select::make('periode')
                        ->label('Periode Data')
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
                        ->default('semua')
                        ->live()
                        ->afterStateUpdated(function($state, callable $set) {
                            if ($state !== 'kustom') {
                                $set('dari_tanggal', null);
                                $set('sampai_tanggal', null);
                            }
                        }),

                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\DatePicker::make('dari_tanggal')
                                ->label('Dari Tanggal')
                                ->visible(fn ($get) => $get('periode') === 'kustom'),

                            Forms\Components\DatePicker::make('sampai_tanggal')
                                ->label('Sampai Tanggal')
                                ->visible(fn ($get) => $get('periode') === 'kustom'),
                        ]),

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
                ->action(function (array $data) {
                    // Proses periode ke tanggal
                    $dariTanggal = null;
                    $sampaiTanggal = null;

                    // Konversi periode ke tanggal
                    if ($data['periode'] === 'hari_ini') {
                        $dariTanggal = Carbon::today()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->format('Y-m-d');
                    }
                    elseif ($data['periode'] === 'minggu_ini') {
                        $dariTanggal = Carbon::today()->startOfWeek()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->endOfWeek()->format('Y-m-d');
                    }
                    elseif ($data['periode'] === 'bulan_ini') {
                        $dariTanggal = Carbon::today()->startOfMonth()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->endOfMonth()->format('Y-m-d');
                    }
                    elseif ($data['periode'] === 'tahun_ini') {
                        $dariTanggal = Carbon::today()->startOfYear()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->endOfYear()->format('Y-m-d');
                    }
                    elseif ($data['periode'] === 'bulan_lalu') {
                        $dariTanggal = Carbon::today()->subMonth()->startOfMonth()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->subMonth()->endOfMonth()->format('Y-m-d');
                    }
                    elseif ($data['periode'] === 'tahun_lalu') {
                        $dariTanggal = Carbon::today()->subYear()->startOfYear()->format('Y-m-d');
                        $sampaiTanggal = Carbon::today()->subYear()->endOfYear()->format('Y-m-d');
                    }
                    elseif ($data['periode'] === 'kustom') {
                        $dariTanggal = isset($data['dari_tanggal']) ? $data['dari_tanggal']->format('Y-m-d') : null;
                        $sampaiTanggal = isset($data['sampai_tanggal']) ? $data['sampai_tanggal']->format('Y-m-d') : null;
                    }

                    // Buat array parameter
                    $params = [
                        'format' => $data['format'] ?? 'pdf',
                    ];

                    // Tambahkan status jika bukan 'all'
                    if ($data['status'] !== 'all') {
                        $params['status'] = $data['status'];
                    }

                    // Tambahkan parameter yang tidak null
                    if ($dariTanggal) {
                        $params['dari_tanggal'] = $dariTanggal;
                    }

                    if ($sampaiTanggal) {
                        $params['sampai_tanggal'] = $sampaiTanggal;
                    }

                    return redirect()->route('verifikasi.export.all', $params);
                }),
        ];
    }
}