<?php

namespace App\Filament\Resources\LayananDesaResource\Pages;

use App\Filament\Resources\LayananDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\ViewEntry;

class ViewLayananDesa extends ViewRecord
{
    protected static string $resource = LayananDesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->label('Ubah Layanan')
                ->icon('heroicon-o-pencil-square'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Informasi Utama Layanan
                Infolists\Components\Section::make('Informasi Layanan')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        // Nama layanan, kategori, dan biaya dalam 1 baris (nama layanan lebih besar)
                        Infolists\Components\Grid::make(6)
                            ->schema([
                                Infolists\Components\TextEntry::make('nama_layanan')
                                    ->label('Nama Layanan')
                                    ->columnSpan(3) // Mengambil setengah dari grid (lebih besar)
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->icon('heroicon-m-document-text'),

                                Infolists\Components\TextEntry::make('kategori')
                                    ->columnSpan(1)
                                    ->badge()
                                    ->color(fn (string $state): string =>
                                        match($state) {
                                            'Surat' => 'primary',
                                            'Kesehatan' => 'success',
                                            'Pendidikan' => 'warning',
                                            'Sosial' => 'secondary',
                                            'Infrastruktur' => 'danger',
                                            default => 'gray',
                                        }
                                    )
                                    ->icon('heroicon-m-tag'),

                                Infolists\Components\TextEntry::make('biaya')
                                    ->label('Biaya Layanan')
                                    ->columnSpan(2)
                                    ->formatStateUsing(fn ($state) =>
                                        $state == 0
                                            ? 'Gratis'
                                            : 'Rp ' . number_format($state, 0, ',', '.')
                                    )
                                    ->color(fn ($state) => $state == 0 ? 'success' : 'gray')
                                    ->icon('heroicon-m-banknotes'),
                            ]),

                        // Deskripsi
                        Infolists\Components\TextEntry::make('deskripsi')
                            ->html()
                            ->columnSpanFull(),

                        // Lokasi, jadwal, dan kontak dalam 1 baris
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('lokasi_layanan')
                                    ->label('Lokasi Layanan')
                                    ->icon('heroicon-m-map-pin')
                                    ->default('-')
                                    ->visible(fn ($state) => !empty($state)),

                                Infolists\Components\TextEntry::make('jadwal_pelayanan')
                                    ->label('Jadwal Pelayanan')
                                    ->icon('heroicon-m-clock')
                                    ->default('-')
                                    ->visible(fn ($state) => !empty($state)),

                                Infolists\Components\TextEntry::make('kontak_layanan')
                                    ->label('Kontak Layanan')
                                    ->icon('heroicon-m-phone')
                                    ->default('-')
                                    ->visible(fn ($state) => !empty($state)),
                            ]),
                    ]),

                // Persyaratan Layanan dengan view kustom
                Infolists\Components\Section::make('Persyaratan Layanan')
                    ->icon('heroicon-o-document-check')
                    ->collapsible()
                    ->schema([
                        ViewEntry::make('persyaratan')
                            ->view('filament.resources.layanan-desa-resource.persyaratan-layanan')
                    ])
                    ->hidden(fn ($record) => empty($record->persyaratan)),

                // Prosedur Layanan dengan view kustom
                Infolists\Components\Section::make('Prosedur Layanan')
                    ->icon('heroicon-o-list-bullet')
                    ->collapsible()
                    ->schema([
                        ViewEntry::make('prosedur')
                            ->view('filament.resources.layanan-desa-resource.prosedur-layanan')
                    ])
                    ->hidden(fn ($record) => empty($record->prosedur)),

                // Informasi Tambahan
                Infolists\Components\Section::make('Informasi Tambahan')
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label('Dibuat Oleh')
                                    ->icon('heroicon-m-user'),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Tanggal Dibuat')
                                    ->dateTime('d/m/Y H:i')
                                    ->icon('heroicon-m-clock'),

                                Infolists\Components\TextEntry::make('desa.nama_desa')
                                    ->label('Desa')
                                    ->icon('heroicon-m-home-modern'),
                            ]),
                    ]),
            ]);
    }
}
