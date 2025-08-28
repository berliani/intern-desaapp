<?php

namespace App\Filament\Resources\KeuanganDesaResource\Pages;

use App\Filament\Resources\KeuanganDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;

class CreateKeuanganDesa extends CreateRecord
{
    protected static string $resource = KeuanganDesaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        
        $data['company_id'] = Filament::getTenant()->id;

        if (isset($data['jumlah']) && is_string($data['jumlah'])) {
            $data['jumlah'] = (float) str_replace(['Rp', '.', ','], ['', '', '.'], $data['jumlah']);
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
