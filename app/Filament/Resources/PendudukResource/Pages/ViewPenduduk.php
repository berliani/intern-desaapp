<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use App\Models\Penduduk;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;

class ViewPenduduk extends ViewRecord
{
    protected static string $resource = PendudukResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        // Mendapatkan data anggota keluarga lainnya
        $anggotaKeluarga = Penduduk::where('kk', $this->record->kk)
            ->where('id', '!=', $this->record->id)
            ->orderBy('kepala_keluarga', 'desc')
            ->orderBy('tanggal_lahir')
            ->get();

        // Mendapatkan kepala keluarga
        $kepalaKeluarga = Penduduk::where('kk', $this->record->kk)
            ->where('kepala_keluarga', true)
            ->first();

        return $infolist
            ->schema([
                // Bagian informasi pribadi penduduk
                Components\Section::make('Informasi Pribadi')
                    ->icon('heroicon-o-user-circle')
                    ->description('Data utama penduduk')
                    ->collapsible()
                    ->extraAttributes(['class' => 'border border-gray-200 rounded-xl shadow-sm'])
                    ->schema([
                        // Nama dan status dalam keluarga di baris pertama
                        Components\Grid::make(3)
                            ->schema([
                                Components\TextEntry::make('nama')
                                    ->size(Components\TextEntry\TextEntrySize::Large)
                                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                                    ->icon('heroicon-o-user')
                                    ->extraAttributes(['class' => 'text-primary-600'])
                                    ->columnSpan(1),

                                Components\TextEntry::make('status_dalam_keluarga')
                                    ->label('Status dalam Keluarga')
                                    ->state(fn () => $this->record->kepala_keluarga ? 'Kepala Keluarga' : 'Anggota Keluarga')
                                    ->badge()
                                    ->color(fn () => $this->record->kepala_keluarga ? 'success' : 'info')
                                    ->icon('heroicon-o-user-group')
                                    ->columnSpan(2),
                            ]),

                        // NIK, Nomor KK, dan Golongan Darah di baris berikutnya
                        Components\Grid::make(3)
                            ->schema([
                                Components\TextEntry::make('nik')
                                    ->label('NIK')
                                    ->copyable()
                                    ->icon('heroicon-o-identification')
                                    ->copyMessageDuration(2000),

                                Components\TextEntry::make('kk')
                                    ->label('Nomor KK')
                                    ->copyable()
                                    ->icon('heroicon-o-document-text')
                                    ->copyMessageDuration(2000)
                                    ->url(fn () => route('filament.admin.resources.kartu-keluargas.view', ['record' => $this->record->kk]))
                                    ->openUrlInNewTab(),

                                // Golongan darah dipindahkan ke sini
                                Components\TextEntry::make('golongan_darah')
                                    ->label('Golongan Darah')
                                    ->icon('heroicon-o-beaker')
                                    ->badge()
                                    ->color(fn (string $state): string => match($state ?? '') {
                                        'A', 'A+', 'A-' => 'success',
                                        'B', 'B+', 'B-' => 'info',
                                        'AB', 'AB+', 'AB-' => 'warning',
                                        'O', 'O+', 'O-' => 'danger',
                                        default => 'gray',
                                    }),
                            ]),

                        Components\Grid::make(3)
                            ->schema([
                                Components\TextEntry::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->icon('heroicon-o-map-pin'),

                                Components\TextEntry::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->date('d F Y')
                                    ->icon('heroicon-o-calendar'),

                                Components\TextEntry::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->formatStateUsing(fn (string $state): string => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                                    ->icon('heroicon-o-user'),
                            ]),
                    ]),

                // Section untuk informasi kontak
                Components\Section::make('Informasi Kontak')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->description('Kontak dan informasi komunikasi')
                    ->collapsible()
                    ->extraAttributes(['class' => 'border border-gray-200 rounded-xl shadow-sm'])
                    ->schema([
                        Components\Grid::make(2)
                            ->schema([
                                Components\TextEntry::make('no_hp')
                                    ->label('Nomor HP')
                                    ->icon('heroicon-o-phone')
                                    ->copyable()
                                    ->copyMessageDuration(2000)
                                    ->url(fn ($state) => $state ? "tel:{$state}" : null)
                                    ->visible(fn ($state) => !empty($state)),

                                Components\TextEntry::make('email')
                                    ->label('Email')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->copyMessageDuration(2000)
                                    ->url(fn ($state) => $state ? "mailto:{$state}" : null)
                                    ->visible(fn ($state) => !empty($state)),
                            ]),

                        // Tampilkan pesan jika tidak ada kontak
                        Components\TextEntry::make('no_kontak')
                            ->label('')
                            ->state('Tidak ada informasi kontak yang tersedia.')
                            ->icon('heroicon-o-information-circle')
                            ->visible(fn ($record) => empty($record->no_hp) && empty($record->email))
                            ->columnSpanFull(),
                    ]),

                // Card untuk Agama, Status Perkawinan, Pendidikan & Pekerjaan
                Components\Section::make('Informasi Tambahan')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->description('Agama, status perkawinan, dan riwayat pendidikan')
                    ->collapsible()
                    ->extraAttributes(['class' => 'border border-gray-200 rounded-xl shadow-sm'])
                    ->schema([
                        Components\Grid::make(4)
                            ->schema([
                                Components\TextEntry::make('agama')
                                    ->label('Agama')
                                    ->icon('heroicon-o-heart'),

                                Components\TextEntry::make('status_perkawinan')
                                    ->label('Status Perkawinan')
                                    ->icon('heroicon-o-home-modern')
                                    ->badge()
                                    ->color(fn (string $state): string =>
                                        match(strtolower($state ?? '')) {
                                            'kawin' => 'success',
                                            'belum kawin' => 'gray',
                                            'cerai hidup' => 'warning',
                                            'cerai mati' => 'danger',
                                            default => 'gray'
                                        }
                                    ),

                                Components\TextEntry::make('pendidikan')
                                    ->label('Pendidikan')
                                    ->icon('heroicon-o-academic-cap'),

                                Components\TextEntry::make('pekerjaan')
                                    ->label('Pekerjaan')
                                    ->icon('heroicon-o-briefcase'),
                            ]),
                    ])
                    ->columns(1),

                // Bagian alamat
                Components\Section::make('Alamat')
                    ->icon('heroicon-o-home')
                    ->schema([
                        Components\Grid::make(4)
                            ->schema([
                                Components\TextEntry::make('rt_rw')
                                    ->label('RT/RW')
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
                            ->label('Alamat Lengkap')
                            ->icon('heroicon-o-map')
                            ->columnSpanFull(),
                    ]),

                // Bagian anggota keluarga lainnya
                Components\Section::make('Anggota Keluarga Lainnya')
                    ->icon('heroicon-o-user-group')
                    ->visible(fn () => $anggotaKeluarga->isNotEmpty())
                    ->schema([
                        Components\ViewEntry::make('anggota_keluarga')
                            ->label(false)
                            ->view('filament.resources.penduduk-resource.anggota-keluarga-table')
                            ->state([
                                'anggota' => $anggotaKeluarga,
                            ]),
                    ]),

                // Pesan jika tidak ada anggota keluarga lainnya
                Components\Section::make('Anggota Keluarga Lainnya')
                    ->icon('heroicon-o-user-group')
                    ->visible(fn () => $anggotaKeluarga->isEmpty())
                    ->schema([
                        Components\TextEntry::make('no_anggota')
                            ->label(false)
                            ->state('Tidak ada anggota keluarga lainnya dalam KK ini.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Ubah Data')
                ->icon('heroicon-o-pencil-square'),

            Actions\Action::make('lihat_kk')
                ->label('Lihat Kartu Keluarga')
                ->url(fn () => route('filament.admin.resources.kartu-keluargas.view', ['record' => $this->record->kk]))
                ->icon('heroicon-o-identification')
                ->color('info'),
                
        ];
    }
}