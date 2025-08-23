<?php

namespace App\Filament\Resources\InventarisResource\Pages;

use App\Filament\Resources\InventarisResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;

class ViewInventaris extends ViewRecord
{
    protected static string $resource = InventarisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Ubah Data')
                ->icon('heroicon-o-pencil-square'),
            Actions\DeleteAction::make()
                ->label('Hapus Data')
                ->icon('heroicon-o-trash')
                ->color('danger'),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Barang')
                    ->icon('heroicon-o-cube')
                    ->schema([
                        Infolists\Components\TextEntry::make('kode_barang')
                            ->label('Kode Barang')
                            ->badge()
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::Bold)
                            ->copyable()
                            ->icon('heroicon-m-qr-code'),

                        Infolists\Components\TextEntry::make('nama_barang')
                            ->label('Nama Barang')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull(),

                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('desa.nama_desa')
                                    ->label('Desa')
                                    ->icon('heroicon-m-home-modern'),

                                Infolists\Components\TextEntry::make('kategori')
                                    ->icon('heroicon-m-tag'),

                                Infolists\Components\TextEntry::make('jumlah')
                                    ->suffix(' unit')
                                    ->icon('heroicon-m-calculator'),
                            ]),

                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('kondisi')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'Baik' => 'success',
                                        'Rusak Ringan' => 'warning',
                                        'Rusak Berat' => 'danger',
                                        'Hilang' => 'gray',
                                        default => 'gray',
                                    })
                                    ->icon('heroicon-m-wrench-screwdriver'),

                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'Tersedia' => 'success',
                                        'Dipinjam' => 'warning',
                                        'Dalam Perbaikan' => 'danger',
                                        'Tidak Aktif' => 'gray',
                                        default => 'gray',
                                    })
                                    ->icon('heroicon-m-information-circle'),

                                Infolists\Components\TextEntry::make('lokasi')
                                    ->icon('heroicon-m-map-pin'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Informasi Pengadaan')
                    ->icon('heroicon-o-shopping-bag')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('tanggal_perolehan')
                                    ->date('d F Y')
                                    ->icon('heroicon-m-calendar'),

                                Infolists\Components\TextEntry::make('nominal_harga')
                                    ->label('Nominal/Harga')
                                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->color('success')
                                    ->icon('heroicon-m-banknotes'),

                                Infolists\Components\TextEntry::make('sumber_dana')
                                    ->icon('heroicon-m-currency-dollar'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Dokumentasi')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Infolists\Components\ImageEntry::make('foto')
                            ->disk('public')
                            ->visibility('public')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('keterangan')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Informasi Tambahan')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label('Dibuat Oleh')
                                    ->icon('heroicon-m-user'),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Tanggal Dibuat')
                                    ->dateTime('d/m/Y H:i')
                                    ->icon('heroicon-m-clock'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}