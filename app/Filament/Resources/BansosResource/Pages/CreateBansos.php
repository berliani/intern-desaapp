<?php

namespace App\Filament\Resources\BansosResource\Pages;

use App\Filament\Resources\BansosResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\JenisBansos;
use App\Models\ProfilDesa;
use Filament\Facades\Filament;

class CreateBansos extends CreateRecord
{
    protected static string $resource = BansosResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenant = Filament::getTenant();
        $profilDesa = ProfilDesa::where('company_id', $tenant->id)->first();

        $data['company_id'] = $tenant->id;
        $data['desa_id'] = $profilDesa?->id;
        $data['approved_by'] = auth()->id();
        $data['verified_by'] = auth()->id();

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->addStatusHistory(
            $this->record->status,
            'Status awal saat pembuatan bantuan'
        );
    }
}
