<?php

namespace App\Filament\Resources\PengaduanResource\Pages;

use App\Filament\Resources\PengaduanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPengaduan extends ViewRecord
{
    protected static string $resource = PengaduanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('tanggapi')
                ->label('Tanggapi')
                ->icon('heroicon-o-chat-bubble-left')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Select::make('status')
                        ->options(\App\Models\Pengaduan::getStatusOptions())
                        ->default('Sedang Diproses')
                        ->required(),
                    \Filament\Forms\Components\Textarea::make('tanggapan')
                        ->required()
                        ->placeholder('Masukkan tanggapan untuk pengaduan ini')
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $this->record->update([
                        'status' => $data['status'],
                        'tanggapan' => $data['tanggapan'],
                        'tanggal_tanggapan' => now(),
                        'ditangani_oleh' => auth()->id(),
                    ]);

                    $this->redirect($this->getResource()::getUrl('view', ['record' => $this->record]));
                }),

            Actions\EditAction::make()
            ->label('Ubah Data')
            ->icon('heroicon-o-pencil-square')
            ,
            Actions\DeleteAction::make()
            ->label('Hapus Data')
                ->icon('heroicon-o-trash')
                ->color('danger'),
            Actions\RestoreAction::make(),
        ];
    }
}