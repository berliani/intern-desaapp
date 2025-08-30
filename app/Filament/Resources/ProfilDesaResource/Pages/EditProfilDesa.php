<?php

namespace App\Filament\Resources\ProfilDesaResource\Pages;

use App\Filament\Resources\ProfilDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfilDesa extends EditRecord
{
    protected static string $resource = ProfilDesaResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Fungsi mengambil data yang sudah didekripsi
     * dari model dan memasukkannya ke dalam form secara otomatis.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Mengambil data 'telepon' yang sudah didekripsi melalui accessor di model
        $data['telepon'] = $this->record->telepon;

        // Mengambil data 'email' yang sudah didekripsi melalui accessor di model
        $data['email'] = $this->record->email;

        return $data;
    }
}
}
