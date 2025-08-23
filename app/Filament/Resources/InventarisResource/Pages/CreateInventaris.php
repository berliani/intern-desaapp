<?php

namespace App\Filament\Resources\InventarisResource\Pages;

use App\Filament\Resources\InventarisResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;

class CreateInventaris extends CreateRecord
{
    protected static string $resource = InventarisResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['company_id'] = Filament::getTenant()->id;
        $data['created_by'] = auth()->id();

  
        if (isset($data['nominal_harga'])) {
            $data['nominal_harga'] = (float) str_replace(['Rp', '.', ','], ['', '', '.'], $data['nominal_harga']);
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
