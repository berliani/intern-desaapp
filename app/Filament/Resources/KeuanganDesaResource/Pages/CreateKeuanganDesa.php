<?php

namespace App\Filament\Resources\KeuanganDesaResource\Pages;

use App\Filament\Resources\KeuanganDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateKeuanganDesa extends CreateRecord
{
    protected static string $resource = KeuanganDesaResource::class;

    // Memastikan field created_by selalu terisi dengan user yang login
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        // Pastikan jumlah diformat dengan benar (dari format rupiah ke decimal)
        if (isset($data['jumlah']) && is_string($data['jumlah'])) {
            $data['jumlah'] = (float) str_replace(['Rp', '.', ','], ['', '', '.'], $data['jumlah']);
        }

        return $data;
    }

    // Redirect ke halaman index setelah simpan
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}