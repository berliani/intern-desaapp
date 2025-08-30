<?php

namespace App\Filament\Resources\JenisBansosResource\Pages;

use App\Filament\Resources\JenisBansosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJenisBansos extends EditRecord
{
    protected static string $resource = JenisBansosResource::class;

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