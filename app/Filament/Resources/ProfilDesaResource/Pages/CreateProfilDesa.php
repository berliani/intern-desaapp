<?php

namespace App\Filament\Resources\ProfilDesaResource\Pages;

use App\Filament\Resources\ProfilDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProfilDesa extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = ProfilDesaResource::class;
}