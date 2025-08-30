<?php

namespace App\Filament\Resources\ProfilDesaResource\Pages;

use App\Filament\Resources\ProfilDesaResource;
use App\Models\BatasWilayahPotensi;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\HtmlString;
use Filament\Facades\Filament;

class ViewProfilDesa extends ViewRecord
{
    protected static string $resource = ProfilDesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil-square')
                ->label('Ubah Profil'),
            Actions\Action::make('lihatPeta')
                ->label('G-Maps Desa')
                ->url(fn($record) => "https://www.google.com/maps/search/{$record->nama_desa}+{$record->kecamatan}+{$record->kabupaten}", true)
                ->icon('heroicon-o-map')
                ->color('warning'),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        // Mengambil data batas wilayah dan potensi
        $batasWilayah = BatasWilayahPotensi::where('profil_desa_id', $this->record->id)->first();

        return $infolist
            ->schema([
                // Bagian untuk thumbnail dan logo dalam satu baris
                Components\Section::make('Thumbnail & Logo Desa')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Components\Grid::make()
                            ->schema([
                                Components\ImageEntry::make('thumbnail')
                                    ->label('Thumbnail Desa')
                                    ->disk('public')
                                    ->height(300)
                                    ->extraImgAttributes(['class' => 'rounded shadow-sm object-cover w-full h-full'])
                                    ->columnSpan([
                                        'default' => 'full',
                                        'sm' => 2,
                                    ]),

                                Components\ImageEntry::make('logo')
                                    ->label('Logo Desa')
                                    ->disk('public')
                                    ->height(150)
                                    ->extraImgAttributes(['class' => 'rounded shadow-sm mx-auto'])
                                    ->columnSpan([
                                        'default' => 'full',
                                        'sm' => 1,
                                    ]),
                            ])
                            ->columns([
                                'default' => 1,
                                'sm' => 3,  // 3 kolom total, dengan thumbnail menempati 2/3
                            ]),
                    ])
                    ->collapsible(),

                Components\Section::make('Identitas Desa')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Components\TextEntry::make('nama_desa')
                            ->label('Nama Desa')
                            ->size(Components\TextEntry\TextEntrySize::Large)
                            ->weight(FontWeight::Bold),

                        Components\TextEntry::make('alamat')
                            ->label('Alamat Lengkap')
                            ->formatStateUsing(function ($record) {
                                // Start with the base address
                                $address = $record->alamat;

                                // Add the province if it exists
                                if (!empty($record->provinsi)) {
                                    $address .= ', ' . $record->provinsi;
                                }

                                // Add the postal code if it exists
                                if (!empty($record->kode_pos)) {
                                    $address .= ' ' . $record->kode_pos;
                                }

                                return $address;
                            })
                            ->icon('heroicon-o-map-pin')
                            ->columnSpanFull(),

                        Components\TextEntry::make('telepon')
                            ->label('Telepon')
                            ->icon('heroicon-o-phone'),

                        Components\TextEntry::make('email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope'),

                        Components\TextEntry::make('website')
                            ->label('Website')
                            ->url(fn($state) => $state)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-globe-alt'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Components\Section::make('Visi & Misi')
                    ->icon('heroicon-o-flag')
                    ->schema([
                        Components\TextEntry::make('visi')
                            ->label('Visi')
                            ->markdown()
                            ->columnSpanFull(),

                        Components\TextEntry::make('misi')
                            ->label('Misi')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Components\Section::make('Sejarah Desa')
                    ->icon('heroicon-o-book-open')
                    ->schema([
                        Components\TextEntry::make('sejarah')
                            ->hiddenLabel()
                            ->html(),
                    ])
                    ->collapsible(),

                Components\Section::make('Lokasi Desa')
                    ->icon('heroicon-o-map')
                    ->schema([
                        Components\ViewEntry::make('google_map')
                            ->hiddenLabel()
                            ->view('filament.infolists.components.google-map')
                            ->state(function ($record) {
                                return [
                                    'query' => "{$record->nama_desa} {$record->kecamatan} {$record->kabupaten}",
                                    'title' => $record->nama_desa,
                                ];
                            }),
                    ])
                    ->collapsed(false)
                    ->collapsible(),

                // Bagian baru untuk Batas Wilayah
                Components\Section::make('Batas Wilayah')
                    ->icon('heroicon-o-map')
                    ->visible(fn() => $batasWilayah !== null)
                    ->schema([
                        Components\TextEntry::make('luas_wilayah')
                            ->label('Luas Wilayah')
                            ->state(function () use ($batasWilayah) {
                                if (!$batasWilayah) return '-';
                                return number_format($batasWilayah->luas_wilayah, 0, ',', '.') . ' m²';
                            })
                            ->icon('heroicon-o-square-2-stack')
                            ->columnSpanFull(),

                        Components\Grid::make(2)
                            ->schema([
                                Components\TextEntry::make('batas_utara')
                                    ->label('Batas Utara')
                                    ->state(fn() => $batasWilayah?->batas_utara ?? '-')
                                    ->icon('heroicon-o-arrow-up'),

                                Components\TextEntry::make('batas_selatan')
                                    ->label('Batas Selatan')
                                    ->state(fn() => $batasWilayah?->batas_selatan ?? '-')
                                    ->icon('heroicon-o-arrow-down'),

                                Components\TextEntry::make('batas_timur')
                                    ->label('Batas Timur')
                                    ->state(fn() => $batasWilayah?->batas_timur ?? '-')
                                    ->icon('heroicon-o-arrow-right'),

                                Components\TextEntry::make('batas_barat')
                                    ->label('Batas Barat')
                                    ->state(fn() => $batasWilayah?->batas_barat ?? '-')
                                    ->icon('heroicon-o-arrow-left'),
                            ]),

                        Components\TextEntry::make('keterangan_batas')
                            ->label('Keterangan Tambahan')
                            ->state(fn() => $batasWilayah?->keterangan_batas ?? '-')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                // Bagian untuk Potensi Desa
                Components\Section::make('Potensi Desa')
                    ->icon('heroicon-o-light-bulb')
                    ->visible(fn() => $batasWilayah !== null && !empty($batasWilayah->potensi_desa))
                    ->schema([
                        Components\ViewEntry::make('potensi_desa')
                            ->label(false)
                            ->view('filament.resources.profil-desa-resource.potensi-table')
                            ->state(function () use ($batasWilayah) {
                                return [
                                    'potensi' => $batasWilayah?->potensi_desa ?? [],
                                ];
                            }),

                        Components\TextEntry::make('keterangan_potensi')
                            ->label('Keterangan Tambahan')
                            ->state(fn() => $batasWilayah?->keterangan_potensi ?? '-')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                // Pesan jika tidak ada data batas wilayah dan potensi
                Components\Section::make('Batas Wilayah & Potensi')
                    ->icon('heroicon-o-exclamation-triangle')
                    ->visible(fn() => $batasWilayah === null)
                    ->schema([
                        Components\TextEntry::make('no_data')
                            ->label(false)
                            ->state('Data batas wilayah dan potensi desa belum tersedia.')
                            ->columnSpanFull(),

                        Components\Actions::make([
                            Components\Actions\Action::make('tambah_data')
                                ->label('Tambah Data Batas & Potensi')
                                ->url(fn(): string => route('filament.admin.resources.batas-wilayah-potensis.create', [
                                    'tenant' => Filament::getTenant(),
                                    'profil_desa_id' => $this->record->id
                                ]))
                                ->icon('heroicon-o-plus')
                                ->button()
                                ->color('primary')
                        ])
                            ->alignment('center')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    protected function getFooterWidgets(): array
    {
        return [];
    }
}
