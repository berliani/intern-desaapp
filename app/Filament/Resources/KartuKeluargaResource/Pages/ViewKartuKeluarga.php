<?php

namespace App\Filament\Resources\KartuKeluargaResource\Pages;

use App\Filament\Resources\KartuKeluargaResource;
use App\Filament\Resources\PendudukResource;
use App\Models\Penduduk;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Actions;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\HtmlString;
use Filament\Forms;
use Carbon\Carbon;
use Filament\Facades\Filament;

class ViewKartuKeluarga extends ViewRecord
{
    protected static string $resource = KartuKeluargaResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        // Dapatkan hash KK dari record kepala keluarga yang sudah di-load oleh Filament
        $kkSearchHash = null;
        if ($this->record && $this->record->kk) {
             $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
             if ($pepperKey) {
                $kkSearchHash = hash_hmac('sha256', $this->record->kk, $pepperKey);
             }
        }

        return $infolist
            ->record($this->record) // Pastikan infolist menggunakan record yang benar
            ->schema([
                Components\Section::make('Informasi Kartu Keluarga')
                    ->icon('heroicon-o-identification')
                    ->columns(2)
                    ->schema([
                        Components\TextEntry::make('kk')
                            ->label('Nomor KK')
                            ->weight(FontWeight::Bold)
                            ->size(Components\TextEntry\TextEntrySize::Large)
                            ->copyable()
                            ->icon('heroicon-o-document-text'),

                        Components\TextEntry::make('Jumlah Anggota')
                            ->state(function () use ($kkSearchHash) { 
                                if (!$kkSearchHash) return '0 orang';
                                return Penduduk::where('kk_search_hash', $kkSearchHash)
                                    ->where('kepala_keluarga', false)
                                    ->count() . ' orang';
                            })
                            ->icon('heroicon-o-users'),

                        Components\TextEntry::make('nama')
                            ->label('Kepala Keluarga')
                            ->weight(FontWeight::Bold)
                            ->icon('heroicon-o-user-circle'),

                        Components\TextEntry::make('nik')
                            ->label('NIK Kepala Keluarga')
                            ->copyable()
                            ->icon('heroicon-o-identification'),
                    ]),

                Components\Section::make('Alamat')
                    ->icon('heroicon-o-home')
                    ->schema([
                        Components\Grid::make(4)
                            ->schema([
                                Components\TextEntry::make('rt_rw')
                                    ->label('RT/RW')
                                    ->state(fn (Penduduk $record): string => "{$record->rt}/{$record->rw}")
                                    ->icon('heroicon-o-home-modern'),

                                Components\TextEntry::make('desa_kelurahan')
                                    ->label('Desa/Kelurahan')
                                    ->icon('heroicon-o-building-office-2'),

                                Components\TextEntry::make('kecamatan')
                                    ->icon('heroicon-o-building-office'),

                                Components\TextEntry::make('kabupaten')
                                    ->icon('heroicon-o-building-library'),
                            ]),

                        Components\TextEntry::make('alamat')
                            ->columnSpanFull()
                            ->icon('heroicon-o-map'),
                    ]),

                Components\Section::make('Anggota Keluarga')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Components\ViewEntry::make('anggota_keluarga')
                            ->label(false)
                            ->view('filament.resources.penduduk-resource.anggota-keluarga-table')
                            ->state(function () use ($kkSearchHash) {
                                $anggota = collect();
                                if ($kkSearchHash) {
                                    // Menggunakan hash untuk query
                                    $semuaAnggota = Penduduk::where('kk_search_hash', $kkSearchHash)->get();
                                    $anggota = $semuaAnggota->sortBy(fn($p) => Carbon::parse($p->tanggal_lahir));
                                }
                                return [
                                    'anggota' => $anggota,
                                    'tenant' => Filament::getTenant(),
                                ];
                            }),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('tambah_anggota')
                ->label('Tambah Anggota')
                ->icon('heroicon-o-user-plus')
                ->url(fn () => PendudukResource::getUrl('create', [
                    'tenant' => Filament::getTenant(),
                    // Mengirim nomor KK dari record saat ini ke halaman create
                    'kk' => $this->record->kk, 
                ])),

            Actions\Action::make('export')
                ->label('Ekspor')
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
                ->action(function (array $data) {
                    return redirect()->route('kartu-keluarga.export', [
                        'kk' => $this->record->kk,
                        'format' => $data['format'] ?? 'pdf'
                    ]);
                }),
        ];
    }
}