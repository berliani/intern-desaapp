<?php

namespace App\Filament\Resources\KeuanganDesaResource\Pages;

use App\Filament\Resources\KeuanganDesaResource;
use App\Models\KeuanganDesa;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;

class ViewKeuanganDesa extends ViewRecord
{
    protected static string $resource = KeuanganDesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Ubah Transaksi')
                ->icon('heroicon-o-pencil-square')
                ,

            Actions\DeleteAction::make()
                ->label('Hapus Transaksi')
                ->icon('heroicon-o-trash')
                ->color('danger'),


        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Transaksi')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('jenis')
                                    ->label('Jenis Transaksi')
                                    ->badge()
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->color(fn (string $state): string => $state === 'Pemasukan' ? 'success' : 'danger')
                                    ->icon(fn (string $state): string => $state === 'Pemasukan' ? 'heroicon-m-arrow-down-tray' : 'heroicon-m-arrow-up-tray')
                                    ->iconColor(fn (string $state): string => $state === 'Pemasukan' ? 'success' : 'danger'),

                                Infolists\Components\TextEntry::make('deskripsi')
                                    ->label('Deskripsi Transaksi'),

                                Infolists\Components\TextEntry::make('tanggal')
                                    ->label('Tanggal Transaksi')
                                    ->date('d F Y')
                                    ->icon('heroicon-m-calendar'),
                            ]),

                        // Baris 2: Jumlah transaksi, Desa, Dibuat Oleh
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('jumlah')
                                    ->label('Jumlah Transaksi')
                                    ->money('IDR')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->color(fn ($record): string => $record->jenis === 'Pemasukan' ? 'success' : 'danger')
                                    ->icon('heroicon-m-banknotes'),

                                Infolists\Components\TextEntry::make('desa.nama_desa')
                                    ->label('Desa')
                                    ->icon('heroicon-m-home-modern'),

                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label('Dibuat Oleh')
                                    ->icon('heroicon-m-user'),
                            ]),

                        // Baris 3: Waktu Pencatatan
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Waktu Pencatatan')
                            ->dateTime('d/m/Y H:i')
                            ->icon('heroicon-m-clock'),
                    ]),

                // Ringkasan Keuangan dengan tampilan yang lebih modern
                Infolists\Components\Section::make('Ringkasan Keuangan')
                    ->icon('heroicon-o-chart-bar')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('Total Pemasukan')
                                    ->state(function () {
                                        $total = KeuanganDesa::pemasukan()->sum('jumlah');
                                        return $this->formatCurrency($total);
                                    })
                                    ->color('success')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->icon('heroicon-m-arrow-trending-up'),

                                Infolists\Components\TextEntry::make('Total Pengeluaran')
                                    ->state(function () {
                                        $total = KeuanganDesa::pengeluaran()->sum('jumlah');
                                        return $this->formatCurrency($total);
                                    })
                                    ->color('danger')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->icon('heroicon-m-arrow-trending-down'),

                                Infolists\Components\TextEntry::make('Saldo')
                                    ->state(function () {
                                        $pemasukan = KeuanganDesa::pemasukan()->sum('jumlah');
                                        $pengeluaran = KeuanganDesa::pengeluaran()->sum('jumlah');
                                        $saldo = $pemasukan - $pengeluaran;
                                        return $this->formatCurrency($saldo);
                                    })
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight(FontWeight::Bold)
                                    ->color(function () {
                                        $pemasukan = KeuanganDesa::pemasukan()->sum('jumlah');
                                        $pengeluaran = KeuanganDesa::pengeluaran()->sum('jumlah');
                                        return ($pemasukan - $pengeluaran) >= 0 ? 'success' : 'danger';
                                    })
                                    ->icon('heroicon-m-banknotes'),
                            ])
                    ]),
            ]);
    }

    // Helper untuk format nilai
    protected function formatCurrency($value)
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
