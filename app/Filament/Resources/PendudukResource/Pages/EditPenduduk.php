<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Models\Penduduk;
use Filament\Facades\Filament; // <<< PERBAIKAN: Menghapus duplikat 'use' statement

class EditPenduduk extends EditRecord
{
    protected static string $resource = PendudukResource::class;

    /**
     * PERBAIKAN: Menggabungkan dua fungsi `mutateFormDataBeforeFill` yang duplikat.
     * Fungsi ini akan mengisi form dengan data yang sudah didekripsi dari model
     * dan data alamat dari profil desa yang aktif.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Mengisi data terenkripsi dari accessor model
        $data['nik'] = $this->record->nik;
        $data['kk'] = $this->record->kk;
        $data['tanggal_lahir'] = $this->record->tanggal_lahir;
        $data['no_hp'] = $this->record->no_hp;
        $data['email'] = $this->record->email;
        $data['alamat'] = $this->record->alamat; // Menambahkan alamat

        // Mengisi data alamat dari profil desa (tenant)
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
            // <<< PERBAIKAN: Menghapus duplikat ViewAction, mempertahankan versi yang tenant-aware
            Actions\ViewAction::make()->url(fn (): string => $this->getResource()::getUrl('view', [
                'record' => $this->getRecord(),
                'tenant' => $this->getRecord()->company_id,
            ])),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make()
                ->visible(fn ($record) => $record->trashed()),
            Actions\RestoreAction::make()
                ->visible(fn ($record) => $record->trashed()),
        ];
    }

    /**
     * PERBAIKAN: Menghapus deklarasi fungsi `afterSave` yang bersarang (nested).
     * Fungsi ini dijalankan setelah data disimpan untuk menangani logika
     * kompleks terkait status Kepala Keluarga (KK).
     */
    protected function afterSave(): void
    {
        $record = $this->record;

        $plainKk = $this->form->getState()['kk'];

        // Menggunakan helper hashForSearch dari model untuk konsistensi
        $kkSearchHash = Penduduk::hashForSearch($plainKk);

        // Jika penduduk ditandai sebagai Kepala Keluarga
        if ($record->kepala_keluarga) {
            // Pastikan ID kepala keluarga adalah ID-nya sendiri
            if ($record->kepala_keluarga_id !== $record->id) {
                $record->kepala_keluarga_id = $record->id;
                $record->saveQuietly(); // Simpan tanpa memicu event lagi
            }

            // Update semua anggota keluarga lain dengan No. KK yang sama
            Penduduk::where('kk_search_hash', $kkSearchHash)
                ->where('id', '!=', $record->id)
                ->update(['kepala_keluarga_id' => $record->id]);
        } else {
            // Jika status Kepala Keluarga dihapus
            // Cek apakah masih ada anggota yang bergantung padanya
            $dependentCount = Penduduk::where('kepala_keluarga_id', $record->id)
                ->where('id', '!=', $record->id)
                ->count();

            if ($dependentCount > 0) {
                // Jika masih ada, batalkan perubahan dan beri notifikasi
                Notification::make()
                    ->title('Peringatan: Tidak dapat mengubah status')
                    ->body('Penduduk ini adalah kepala keluarga dengan ' . $dependentCount . ' anggota. Mohon pindahkan anggota ke kepala keluarga lain terlebih dahulu.')
                    ->danger()
                    ->send();

                // Kembalikan statusnya menjadi Kepala Keluarga
                $record->kepala_keluarga = true;
                $record->kepala_keluarga_id = $record->id;
                $record->saveQuietly();
            } else {
                // Jika tidak ada anggota, cari kepala keluarga baru di KK yang sama
                $kepalaKeluargaBaru = Penduduk::where('kk_search_hash', $kkSearchHash)
                    ->where('kepala_keluarga', true)
                    ->where('id', '!=', $record->id) // Pastikan bukan dirinya sendiri
                    ->first();

                // Set kepala keluarga baru, atau null jika tidak ada
                $record->kepala_keluarga_id = $kepalaKeluargaBaru?->id;
                $record->saveQuietly();
            }
        }

        Notification::make()
            ->title('Data penduduk berhasil diperbarui')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        // Pastikan URL redirect juga menyertakan tenant
        return $this->getResource()::getUrl('view', [
            'record' => $this->getRecord(),
            'tenant' => Filament::getTenant(),
        ]);
    }
}
