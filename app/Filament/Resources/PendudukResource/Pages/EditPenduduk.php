<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Models\Penduduk;
use Filament\Facades\Filament;

class EditPenduduk extends EditRecord
{
    protected static string $resource = PendudukResource::class;


     protected function mutateFormDataBeforeFill(array $data): array
    {

        $data['nik'] = $this->record->nik;
        $data['kk'] = $this->record->kk;
        $data['tanggal_lahir'] = $this->record->tanggal_lahir;
        $data['no_hp'] = $this->record->no_hp;
        $data['email'] = $this->record->email;


        $profilDesa = Filament::getTenant()?->profilDesa;

        if ($profilDesa) {
          
            $data['desa_kelurahan'] = $profilDesa->nama_desa;
            $data['kecamatan'] = $profilDesa->kecamatan;
            $data['kabupaten'] = $profilDesa->kabupaten;
            $data['provinsi'] = $profilDesa->provinsi;
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make()
                ->visible(fn ($record) => $record->trashed()),
            Actions\RestoreAction::make()
                ->visible(fn ($record) => $record->trashed()),
        ];
    }

     protected function afterSave(): void
    {
        $record = $this->record;

        // --- PERBAIKAN LOGIKA KK ---
        $plainKk = $this->form->getState()['kk'];
        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
        if (!$pepperKey) {
            Notification::make()->title('Error Konfigurasi Server')->danger()->send();
            return;
        }
        $kkSearchHash = hash_hmac('sha256', $plainKk, $pepperKey);

        if ($record->kepala_keluarga) {
            if ($record->kepala_keluarga_id !== $record->id) {
                $record->kepala_keluarga_id = $record->id;
                $record->save();
            }

            Penduduk::where('kk_search_hash', $kkSearchHash)
                ->where('id', '!=', $record->id)
                ->update(['kepala_keluarga_id' => $record->id]);
        } else {
             $dependentCount = Penduduk::where('kepala_keluarga_id', $record->id)
                ->where('id', '!=', $record->id)
                ->count();

            if ($dependentCount > 0) {
                Notification::make()
                    ->title('Peringatan: Tidak dapat mengubah status')
                    ->body('Penduduk ini adalah kepala keluarga dengan ' . $dependentCount . ' anggota. Mohon pindahkan anggota ke kepala keluarga lain terlebih dahulu.')
                    ->danger()
                    ->send();

                $record->kepala_keluarga = true;
                $record->kepala_keluarga_id = $record->id;
                $record->save();
            } else {
                $kepalaKeluarga = Penduduk::where('kk_search_hash', $kkSearchHash)
                    ->where('kepala_keluarga', true)
                    ->first();

                $record->kepala_keluarga_id = $kepalaKeluarga?->id;
                $record->save();
            }
        }

        // Notifikasi sukses
        Notification::make()
            ->title('Data penduduk berhasil diperbarui')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
