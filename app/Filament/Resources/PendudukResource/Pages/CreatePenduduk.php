<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;

class CreatePenduduk extends CreateRecord
{
    protected static string $resource = PendudukResource::class;

     protected function mutateFormDataBeforeCreate(array $data): array
    {
       // 1. Dapatkan tenant (desa/company) yang sedang aktif
        $tenant = Filament::getTenant();
        if ($tenant) {
            $data['company_id'] = $tenant->id;

            // 2. Cari profil desa yang berelasi dengan tenant
            $profilDesa = ProfilDesa::where('company_id', $tenant->id)->first();

            // 3. Jika profil desa ditemukan, tambahkan id-nya ke data yang akan disimpan
            if ($profilDesa) {
                $data['desa_id'] = $profilDesa->id;
            }
        }
        return $data;
    }
    protected function afterCreate(): void
    {
        $record = $this->record;

        // --- PERBAIKAN DIMULAI DI SINI ---
        // Kita perlu mendapatkan nilai KK mentah dari form data karena $record->kk akan terdekripsi
        $plainKk = $this->form->getState()['kk'];
        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
        if (!$pepperKey) {
            // Handle error jika pepper key tidak ada
            Notification::make()->title('Error Konfigurasi Server')->danger()->send();
            return;
        }
        $kkSearchHash = hash_hmac('sha256', $plainKk, $pepperKey);
        // --- AKHIR PERBAIKAN ---

        if ($record->kepala_keluarga) {
            // Ini kepala keluarga, atur self-reference
            $record->kepala_keluarga_id = $record->id;
            $record->save();

            // Cari dan update semua anggota dengan nomor KK yang sama menggunakan HASH
            Penduduk::where('kk_search_hash', $kkSearchHash)
                ->where('id', '!=', $record->id)
                ->whereNull('kepala_keluarga_id')
                ->update(['kepala_keluarga_id' => $record->id]);
        } else {
            // Ini anggota keluarga, cari kepala keluarga dengan nomor KK yang sama menggunakan HASH
            $kepalaKeluarga = Penduduk::where('kk_search_hash', $kkSearchHash)
                ->where('kepala_keluarga', true)
                ->first();

            if ($kepalaKeluarga) {
                $record->kepala_keluarga_id = $kepalaKeluarga->id;
                $record->save();
            }
        }

        Notification::make()
            ->title('Data penduduk berhasil disimpan')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
