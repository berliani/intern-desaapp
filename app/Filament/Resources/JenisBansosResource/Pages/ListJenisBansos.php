<?php

namespace App\Filament\Resources\JenisBansosResource\Pages;

use App\Filament\Resources\JenisBansosResource;
use App\Filament\Resources\JenisBansosResource\Widgets\JenisBansosStats;
use App\Models\JenisBansos;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;

class ListJenisBansos extends ListRecords
{
    protected static string $resource = JenisBansosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label('Tambah Jenis Bansos'),

            Actions\Action::make('exportAll')
                ->label('Ekspor Semua')
                ->icon('heroicon-o-document-arrow-up')
               
                ->form([
                    Forms\Components\Select::make('format')
                        ->label('Format Ekspor')
                        ->options([
                            'pdf' => 'PDF',
                            'excel' => 'Excel',
                        ])
                        ->default('excel')
                        ->required(),

                    Forms\Components\Select::make('kategori')
                        ->label('Filter Kategori')
                        ->options(JenisBansos::getKategoriOptions())
                        ->multiple()
                        ->placeholder('Semua Kategori'),

                    Forms\Components\Select::make('bentuk_bantuan')
                        ->label('Filter Bentuk Bantuan')
                        ->options(JenisBansos::getBentukBantuanOptions())
                        ->multiple()
                        ->placeholder('Semua Bentuk'),

                    Forms\Components\Select::make('periode')
                        ->label('Filter Periode')
                        ->options(JenisBansos::getPeriodeOptions())
                        ->multiple()
                        ->placeholder('Semua Periode'),

                    Forms\Components\Select::make('is_active')
                        ->label('Filter Status')
                        ->options([
                            'true' => 'Aktif',
                            'false' => 'Tidak Aktif',
                        ])
                        ->placeholder('Semua Status'),
                ])
                ->action(function (array $data) {
                    // Persiapkan parameter untuk ekspor
                    $params = [
                        'format' => $data['format'] ?? 'excel'
                    ];

                    // Tambahkan filter jika dipilih
                    if (!empty($data['kategori'])) {
                        $params['kategori'] = implode(',', $data['kategori']);
                    }

                    if (!empty($data['bentuk_bantuan'])) {
                        $params['bentuk_bantuan'] = implode(',', $data['bentuk_bantuan']);
                    }

                    if (!empty($data['periode'])) {
                        $params['periode'] = implode(',', $data['periode']);
                    }

                    if (isset($data['is_active']) && $data['is_active'] !== '') {
                        $params['is_active'] = $data['is_active'];
                    }

                    return redirect()->route('jenis-bansos.export.all', $params);
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            JenisBansosStats::class,
        ];
    }
}