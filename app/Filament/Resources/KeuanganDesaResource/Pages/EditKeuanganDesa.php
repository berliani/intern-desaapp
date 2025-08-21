<?php

namespace App\Filament\Resources\KeuanganDesaResource\Pages;

use App\Filament\Resources\KeuanganDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeuanganDesa extends EditRecord
{
    protected static string $resource = KeuanganDesaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    // Format data sebelum disimpan
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Pastikan jumlah diformat dengan benar (dari format rupiah ke decimal)
        if (isset($data['jumlah']) && is_string($data['jumlah'])) {
            $data['jumlah'] = (float) str_replace(['Rp', '.', ','], ['', '', '.'], $data['jumlah']);
        }

        return $data;
    }

    // Redirect ke halaman index setelah update
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}