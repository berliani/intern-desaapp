<?php

namespace App\Filament\Resources\LayananDesaResource\Pages;

use App\Filament\Resources\LayananDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateLayananDesa extends CreateRecord
{
    protected static string $resource = LayananDesaResource::class;

    // Memastikan field created_by selalu terisi dengan user yang login
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }

    // Redirect ke halaman index setelah simpan
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
