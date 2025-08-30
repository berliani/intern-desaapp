<?php

namespace App\Filament\Resources\JenisBansosResource\Pages;

use App\Filament\Resources\JenisBansosResource;
use App\Models\Bansos;
use App\Models\JenisBansos;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ViewJenisBansos extends ViewRecord
{
    protected static string $resource = JenisBansosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->icon('heroicon-o-pencil-square')
            ->label('Ubah Jenis Bansos'),

            // Actions\Action::make('tambahPenerima')
            //     ->label('Tambah Penerima')
            //     ->icon('heroicon-o-plus')
            //     ->url(fn () => route('filament.admin.resources.bansos.create', ['jenis_bansos_id' => $this->record->id]))
            //     ->color('success'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        $daftarPenerima = Bansos::where('jenis_bansos_id', $this->record->id)
            ->with('penduduk')
            ->latest('updated_at')
            ->get();

        return $infolist
            ->schema([
                // Informasi Bantuan Sosial
                Infolists\Components\Section::make('Informasi Bantuan Sosial')
                    ->schema([
                        // Baris 1: Nama Program, Status, Kategori
                        Infolists\Components\Grid::make([
                            'default' => 1,
                            'sm' => 3,
                            'lg' => 6,
                        ])
                        ->schema([
                            Infolists\Components\TextEntry::make('nama_bansos')
                                ->label('Nama Program Bantuan')
                                ->weight(FontWeight::Bold)
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                ->icon('heroicon-o-gift')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 2,
                                    'lg' => 3,
                                ]),

                            Infolists\Components\TextEntry::make('is_active')
                                ->label('Status Program')
                                ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif')
                                ->badge()
                                ->color(fn ($state) => $state ? 'success' : 'danger')
                                ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                                ->columnSpan(1),

                            Infolists\Components\TextEntry::make('kategori')
                                ->label('Kategori')
                                ->badge()
                                ->icon('heroicon-o-tag')
                                ->color(fn (string $state): string => JenisBansos::getKategoriColors()[$state] ?? 'gray')
                                ->columnSpan([
                                    'default' => 1,
                                    'sm' => 1,
                                    'lg' => 2,
                                ]),
                        ]),

                        // Baris 2: Instansi, Bentuk, Periode, Nilai Bantuan
                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('instansi_pemberi')
                                    ->label('Instansi Pemberi')
                                    ->icon('heroicon-o-building-office'),

                                Infolists\Components\TextEntry::make('bentuk_bantuan')
                                    ->label('Bentuk Bantuan')
                                    ->formatStateUsing(fn (?string $state): string =>
                                        JenisBansos::getBentukBantuanOptions()[$state] ?? '-')
                                    ->badge()
                                    ->icon('heroicon-o-cube')
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('periode')
                                    ->label('Periode Bantuan')
                                    ->icon('heroicon-o-calendar'),

                                Infolists\Components\TextEntry::make('nilai_bantuan')
                                    ->label('Nilai Bantuan')
                                    ->state(fn (Model $record): string => $record->getNilaiBantuanFormatted())
                                    ->icon(fn (Model $record) => $record->bentuk_bantuan === 'uang' ?
                                        'heroicon-o-banknotes' : 'heroicon-o-scale'),
                            ]),

                        // Baris 3: Deskripsi Program
                        Infolists\Components\TextEntry::make('deskripsi')
                            ->label('Deskripsi Program')
                            ->markdown()
                            ->columnSpanFull(),
                    ]),

                // Statistik Penerima
                Infolists\Components\Section::make('Statistik Penerima')
                    ->visible(fn () => !$daftarPenerima->isEmpty())
                    ->collapsible()
                    ->schema([
                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('total_penerima')
                                    ->label('Total Pengajuan')
                                    ->state(fn () => $daftarPenerima->count())
                                    ->icon('heroicon-o-users')
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('diajukan')
                                    ->label('Diajukan')
                                    ->state(fn () => $daftarPenerima->where('status', 'Diajukan')->count())
                                    ->icon('heroicon-o-document-text')
                                    ->color('info'),

                                Infolists\Components\TextEntry::make('diverifikasi')
                                    ->label('Diverifikasi')
                                    ->state(fn () => $daftarPenerima->where('status', 'Diverifikasi')->count())
                                    ->icon('heroicon-o-check')
                                    ->color('primary'),

                                Infolists\Components\TextEntry::make('disetujui')
                                    ->label('Disetujui')
                                    ->state(fn () => $daftarPenerima->where('status', 'Disetujui')->count())
                                    ->icon('heroicon-o-check-circle')
                                    ->color('success'),
                            ]),

                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('sudah_diterima')
                                    ->label('Sudah Diterima')
                                    ->state(fn () => $daftarPenerima->where('status', 'Sudah Diterima')->count())
                                    ->icon('heroicon-o-check-badge')
                                    ->color('success'),

                                Infolists\Components\TextEntry::make('ditolak')
                                    ->label('Ditolak')
                                    ->state(fn () => $daftarPenerima->where('status', 'Ditolak')->count())
                                    ->icon('heroicon-o-x-circle')
                                    ->color('danger'),

                                Infolists\Components\TextEntry::make('dibatalkan')
                                    ->label('Dibatalkan')
                                    ->state(fn () => $daftarPenerima->where('status', 'Dibatalkan')->count())
                                    ->icon('heroicon-o-x-mark')
                                    ->color('gray'),
                            ]),
                    ]),


                // Daftar penerima - ditampilkan jika ada penerima
                Infolists\Components\Section::make('')
                    ->heading('Daftar Penerima Bantuan')
                    ->icon('heroicon-o-users')
                    ->headerActions([
                        Infolists\Components\Actions\Action::make('tambahPenerima')
                            ->label('Tambah Penerima')
                            ->icon('heroicon-o-plus')
                            ->url(fn () => route('filament.admin.resources.bansos.create', ['jenis_bansos_id' => $this->record->id]))
                            ->color('success')
                    ])
                    ->visible(fn () => !$daftarPenerima->isEmpty())
                    ->schema([
                        Infolists\Components\ViewEntry::make('penerima_bansos')
                            ->label(false)
                            ->view('filament.resources.jenis-bansos-resource.penerima-bansos-table')
                            ->state([
                                'penerima' => $daftarPenerima,
                            ]),
                    ]),

                // Pesan jika tidak ada penerima
                Infolists\Components\Section::make('')
                    ->icon('heroicon-o-users')
                    ->heading('Daftar Penerima Bantuan')
                    ->headerActions([
                        Infolists\Components\Actions\Action::make('tambahPenerimaBaru')
                            ->label('Tambah Penerima')
                            ->icon('heroicon-o-plus')
                            ->url(fn () => route('filament.admin.resources.bansos.create', ['jenis_bansos_id' => $this->record->id]))
                            ->color('success')
                    ])
                    ->visible(fn () => $daftarPenerima->isEmpty())
                    ->schema([
                        Infolists\Components\TextEntry::make('no_penerima')
                            ->label(false)
                            ->state('Belum ada penerima untuk program bantuan ini.')
                            ->extraAttributes(['class' => 'text-center py-4'])
                            ->color('danger')
                            ->columnSpanFull(),
                    ]),

                // Section untuk informasi tambahan
                Infolists\Components\Section::make('Informasi Tambahan')
                    ->collapsed()
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime('d M Y, H:i')
                                    ->icon('heroicon-o-clock'),

                                Infolists\Components\TextEntry::make('updated_at')
                                    ->label('Diperbarui Pada')
                                    ->dateTime('d M Y, H:i')
                                    ->icon('heroicon-o-arrow-path'),

                                // Infolists\Components\TextEntry::make('creator.name')
                                //     ->label('Dibuat Oleh')
                                //     ->icon('heroicon-o-user'),
                            ]),
                    ]),
            ]);
    }
}