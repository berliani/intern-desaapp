<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use App\Models\Penduduk;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Exception;

class ViewPenduduk extends ViewRecord
{
    protected static string $resource = PendudukResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        // Dapatkan KK mentah dari record yang sudah didekripsi oleh accessor di Model
        $plainKk = $this->record->kk;
        $anggotaKeluarga = collect(); // Default ke collection kosong

        if ($plainKk) {
            $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
            if ($pepperKey) {
                $kkSearchHash = hash_hmac('sha256', $plainKk, $pepperKey);

                // Mendapatkan data anggota keluarga lainnya menggunakan HASH
                $queryResult = Penduduk::where('kk_search_hash', $kkSearchHash)
                    ->where('id', '!=', $this->record->id)
                    ->get();
                
                // Urutkan di collection karena tanggal_lahir dienkripsi
                $anggotaKeluarga = $queryResult
                    ->sortBy(function ($penduduk) {
                        try {
                            // Coba parse tanggal lahir untuk sorting
                            return Carbon::parse($penduduk->tanggal_lahir);
                        } catch (Exception $e) {
                            // Jika gagal parse (misalnya karena data tidak valid),
                            // kembalikan tanggal yang sangat lampau agar tidak error dan item ini muncul di akhir.
                            return Carbon::create(1, 1, 1);
                        }
                    })
                    ->sortByDesc('kepala_keluarga');
            }
        }
    
        return $infolist
            ->schema([
                // Bagian informasi pribadi penduduk
                Components\Section::make('Informasi Pribadi')
                    ->icon('heroicon-o-user-circle')
                    ->description('Data utama penduduk')
                    ->collapsible()
                    ->schema([
                        // Nama dan status dalam keluarga di baris pertama
                        Components\Grid::make(3)
                            ->schema([
                                Components\TextEntry::make('nama')
                                    ->size(Components\TextEntry\TextEntrySize::Large)
                                    ->weight(\Filament\Support\Enums\FontWeight::Bold)
                                    ->icon('heroicon-o-user')
                                    ->columnSpan(2),

                                Components\TextEntry::make('status_dalam_keluarga')
                                    ->label('Status')
                                    ->state(fn () => $this->record->kepala_keluarga ? 'Kepala Keluarga' : 'Anggota Keluarga')
                                    ->badge()
                                    ->color(fn () => $this->record->kepala_keluarga ? 'success' : 'info')
                                    ->icon('heroicon-o-user-group')
                                    ->columnSpan(1),
                            ]),

                        // NIK, Nomor KK, dan Golongan Darah di baris berikutnya
                        Components\Grid::make(3)
                            ->schema([
                                Components\TextEntry::make('nik')
                                    ->label('NIK')
                                    ->copyable()
                                    ->icon('heroicon-o-identification'),

                                Components\TextEntry::make('kk')
                                    ->label('Nomor KK')
                                    ->copyable()
                                    ->icon('heroicon-o-document-text'),
                                
                                Components\TextEntry::make('golongan_darah')
                                    ->label('Golongan Darah')
                                    ->icon('heroicon-o-beaker')
                                    ->badge()
                                    ->color(fn (?string $state): string => match ($state ?? '') {
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
                    ->schema([
                        Components\Grid::make(2)
                            ->schema([
                                Components\TextEntry::make('no_hp')
                                    ->label('Nomor HP')
                                    ->icon('heroicon-o-phone')
                                    ->copyable()
                                    ->url(fn ($state) => $state ? "tel:{$state}" : null)
                                    ->visible(fn ($state) => !empty($state)),

                                Components\TextEntry::make('email')
                                    ->label('Email')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->url(fn ($state) => $state ? "mailto:{$state}" : null)
                                    ->visible(fn ($state) => !empty($state)),
                            ]),
                    ]),

                // Card untuk Agama, Status Perkawinan, Pendidikan & Pekerjaan
                Components\Section::make('Informasi Tambahan')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->description('Agama, status perkawinan, dan riwayat pendidikan')
                    ->collapsible()
                    ->schema([
                        Components\Grid::make(2)
                            ->schema([
                                Components\TextEntry::make('agama')
                                    ->label('Agama')
                                    ->icon('heroicon-o-heart'),

                                Components\TextEntry::make('status_perkawinan')
                                    ->label('Status Perkawinan')
                                    ->icon('heroicon-o-users')
                                    ->badge()
                                    ->color(fn (string $state): string =>
                                        match (strtolower($state ?? '')) {
                                            'kawin' => 'success',
                                            'belum kawin' => 'gray',
                                            'cerai hidup' => 'warning',
                                            'cerai mati' => 'danger',
                                            default => 'gray'
                                        }),
                                Components\TextEntry::make('pendidikan')
                                    ->label('Pendidikan')
                                    ->icon('heroicon-o-academic-cap'),

                                Components\TextEntry::make('pekerjaan')
                                    ->label('Pekerjaan')
                                    ->icon('heroicon-o-briefcase'),
                            ]),
                    ]),

                // Bagian alamat
                Components\Section::make('Alamat')
                    ->icon('heroicon-o-home')
                    ->schema([
                        Components\TextEntry::make('alamat')
                            ->label('Alamat Lengkap')
                            ->icon('heroicon-o-map')
                            ->columnSpanFull(),
                        Components\Grid::make(4)
                            ->schema([
                                Components\TextEntry::make('rt_rw')
                                    ->label('RT/RW')
                                    ->state(fn (Penduduk $record): string => "{$record->rt}/{$record->rw}"),
                                Components\TextEntry::make('desa_kelurahan')->label('Desa/Kelurahan'),
                                Components\TextEntry::make('kecamatan'),
                                Components\TextEntry::make('kabupaten'),
                            ]),
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
                                'tenant' => Filament::getTenant(), // Menambahkan data tenant
                            ]),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Ubah Data')
                ->icon('heroicon-o-pencil-square'),

            // <<< PERBAIKAN: Kode duplikat dan sintaks error dihapus.
            Actions\Action::make('lihat_kk')
                ->label('Lihat Kartu Keluarga')
                ->url(function () {
                    $kkRecord = \App\Models\KartuKeluarga::where('nomor_kk_search_hash', hash_hmac('sha256', $this->record->kk, hex2bin(env('IMS_PEPPER_KEY'))))->first();
                    if ($kkRecord) {
                        // Menggunakan getUrl() untuk memastikan URL tenant-aware
                        return \App\Filament\Resources\KartuKeluargaResource::getUrl('view', [
                            'record' => $kkRecord->id,
                            'tenant' => Filament::getTenant(),
                        ]);
                    }
                    return null;
                })
                ->icon('heroicon-o-identification')
                ->color('info')
                ->openUrlInNewTab(),
        ];
    }
}
