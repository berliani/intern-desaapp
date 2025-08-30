<?php

namespace App\Filament\Resources\LayananDesaResource\Pages;

use App\Filament\Resources\LayananDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLayananDesa extends EditRecord
{
    protected static string $resource = LayananDesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}