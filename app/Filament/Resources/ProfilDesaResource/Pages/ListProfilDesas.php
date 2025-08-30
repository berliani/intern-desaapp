<?php

namespace App\Filament\Resources\ProfilDesaResource\Pages;

use App\Filament\Resources\ProfilDesaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ListProfilDesas extends ListRecords
{
    protected static string $resource = ProfilDesaResource::class;
    public function mount(): void
    {
        parent::mount();

        // Ambil query builder untuk resource ini
        $query = static::getResource()::getEloquentQuery();

        // Hitung jumlah record
        $totalRecords = $query->count();

        // Jika hanya ada satu record, redirect ke halaman view
        if ($totalRecords === 1) {
            $profilDesa = $query->first();
            $this->redirect(static::getResource()::getUrl('view', ['record' => $profilDesa->getkey()]));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()
            //     ->icon('heroicon-o-plus')
            //     ->label('Tambah Profil Desa')

        ];
    }
}
