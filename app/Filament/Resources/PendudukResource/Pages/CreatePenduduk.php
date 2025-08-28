<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use App\Models\Penduduk;
use Filament\Notifications\Notification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;

class CreatePenduduk extends CreateRecord
{
    protected static string $resource = PendudukResource::class;

     protected function mutateFormDataBeforeCreate(array $data): array
    {
        // PENTING: Menambahkan company_id dari tenant yang aktif
        $data['company_id'] = Filament::getTenant()->id;

        return $data;
    }
    protected function afterCreate(): void
    {
        $record = $this->record;

        if ($record->kepala_keluarga) {
            // Ini kepala keluarga, atur self-reference
            $record->kepala_keluarga_id = $record->id;
            $record->save();

            // Cari dan update semua anggota dengan nomor KK yang sama
            Penduduk::where('kk', $record->kk)
                ->where('id', '!=', $record->id)
                ->whereNull('kepala_keluarga_id')
                ->update(['kepala_keluarga_id' => $record->id]);
        } else {
            // Ini anggota keluarga, cari kepala keluarga dengan nomor KK yang sama
            $kepalaKeluarga = Penduduk::where('kk', $record->kk)
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
