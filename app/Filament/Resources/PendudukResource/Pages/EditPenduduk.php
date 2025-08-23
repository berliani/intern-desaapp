<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Models\Penduduk;

class EditPenduduk extends EditRecord
{
    protected static string $resource = PendudukResource::class;

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

        if ($record->kepala_keluarga) {
            // Jika ini kepala keluarga, atur self-reference jika belum
            if ($record->kepala_keluarga_id !== $record->id) {
                $record->kepala_keluarga_id = $record->id;
                $record->save();
            }

            // Update semua anggota dengan KK yang sama
            Penduduk::where('kk', $record->kk)
                ->where('id', '!=', $record->id)
                ->whereNull('kepala_keluarga_id')
                ->update(['kepala_keluarga_id' => $record->id]);
        } else {
            // Cek jika ada anggota yang terkait dengan KK ini
            $dependentCount = Penduduk::where('kepala_keluarga_id', $record->id)
                ->where('id', '!=', $record->id)
                ->count();

            if ($dependentCount > 0) {
                // Tampilkan peringatan jika ada anggota yang terkait
                Notification::make()
                    ->title('Peringatan: Tidak dapat mengubah status')
                    ->body('Penduduk ini adalah kepala keluarga dengan ' . $dependentCount . ' anggota. Mohon pindahkan anggota ke kepala keluarga lain terlebih dahulu.')
                    ->danger()
                    ->send();

                // Kembalikan status menjadi kepala keluarga
                $record->kepala_keluarga = true;
                $record->kepala_keluarga_id = $record->id;
                $record->save();
            } else {
                // Ini anggota keluarga, cari kepala keluarga dengan nomor KK yang sama
                $kepalaKeluarga = Penduduk::where('kk', $record->kk)
                    ->where('kepala_keluarga', true)
                    ->where('id', '!=', $record->id)
                    ->first();

                if ($kepalaKeluarga && $record->kepala_keluarga_id !== $kepalaKeluarga->id) {
                    $record->kepala_keluarga_id = $kepalaKeluarga->id;
                    $record->save();
                }
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