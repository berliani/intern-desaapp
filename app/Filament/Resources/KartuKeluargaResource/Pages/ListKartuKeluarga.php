<?php

namespace App\Filament\Resources\KartuKeluargaResource\Pages;

use App\Filament\Resources\KartuKeluargaResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Forms;
use App\Models\ProfilDesa;
use App\Models\Penduduk;
use Illuminate\Support\Collection;
use Filament\Tables;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;
use App\Exports\KartuKeluargaExporter; // Import Exporter yang baru dibuat
use Maatwebsite\Excel\Facades\Excel; // Import facade Excel

class ListKartuKeluarga extends ListRecords
{
    protected static string $resource = KartuKeluargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportAll')
                ->label('Ekspor Semua')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('id_desa')
                        ->label('Desa')
                        ->options(ProfilDesa::pluck('nama_desa', 'id'))
                        ->placeholder('Semua Desa'),

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
                    $query = $this->getFilteredTableQuery();

                    if (!empty($data['id_desa'])) {
                        $query->where('id_desa', $data['id_desa']);
                    }

                    $kepalaKeluargas = $query->get();

                    if ($kepalaKeluargas->isEmpty()) {
                        Notification::make()
                            ->title('Tidak ada data untuk diekspor')
                            ->warning()
                            ->send();
                        return;
                    }

                    $kkSearchHashes = $kepalaKeluargas->pluck('kk_search_hash')->unique()->filter();
                    
                    if ($data['format'] === 'pdf') {
                        $semuaPenduduk = Penduduk::whereIn('kk_search_hash', $kkSearchHashes)->get();
                        $keluargas = $semuaPenduduk->groupBy('kk_search_hash')->map(function ($anggota) {
                            $nomor_kk_asli = $anggota->first()->kk;
                            return collect([$nomor_kk_asli => $anggota]);
                        })->collapse();
                        
                        $dataForView = ['keluargas' => $keluargas];
                        $pdf = Pdf::loadView('pdf.kartu-keluarga', $dataForView)->setPaper('a4', 'landscape');
                        return response()->streamDownload(fn() => print($pdf->output()), 'semua-kartu-keluarga.pdf');
                    } 
                    elseif ($data['format'] === 'excel') {
                        return Excel::download(new KartuKeluargaExporter($kkSearchHashes), 'semua-kartu-keluarga.xlsx');
                    }
                }),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkAction::make('exportSelected')
                ->label('Ekspor Terpilih')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('format')
                        ->label('Format Ekspor')
                        ->options([
                            'pdf' => 'PDF',
                            'excel' => 'Excel',
                        ])
                        ->default('pdf')
                        ->required(),
                ])
                ->action(function (Collection $records, array $data) {
                    $kkSearchHashes = $records->pluck('kk_search_hash')->unique()->filter();
                    
                    if ($data['format'] === 'pdf') {
                        $semuaPenduduk = Penduduk::whereIn('kk_search_hash', $kkSearchHashes)->get();
                        $keluargas = $semuaPenduduk->groupBy('kk_search_hash')->map(function ($anggota) {
                             $nomor_kk_asli = $anggota->first()->kk;
                             return collect([$nomor_kk_asli => $anggota]);
                        })->collapse();
                        
                        $dataForView = ['keluargas' => $keluargas];
                        $pdf = Pdf::loadView('pdf.kartu-keluarga', $dataForView)->setPaper('a4', 'landscape');
                        return response()->streamDownload(fn() => print($pdf->output()), 'kartu-keluarga-terpilih.pdf');
                    }
                    elseif ($data['format'] === 'excel') {
                        return Excel::download(new KartuKeluargaExporter($kkSearchHashes), 'kartu-keluarga-terpilih.xlsx');
                    }
                })
                ->deselectRecordsAfterCompletion(),
        ];
    }
}