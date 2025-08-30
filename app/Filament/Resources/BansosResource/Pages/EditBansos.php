<?php

namespace App\Filament\Resources\BansosResource\Pages;

use App\Filament\Resources\BansosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBansos extends EditRecord
{
    protected static string $resource = BansosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }

    protected function afterSave(): void
    {
        // Jika status berubah, catat di history
        if ($this->record->wasChanged('status')) {
            // Catat perubahan status
            $this->record->addStatusHistory(
                $this->record->status,
                'Status diubah melalui form edit'
            );

            // Set timestamp sesuai jenis status
            if ($this->record->status === 'Sudah Diterima' && !$this->record->tanggal_penerimaan) {
                $this->record->tanggal_penerimaan = now();
                $this->record->save();
            }
        }
    }
}