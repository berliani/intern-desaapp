<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['company_id'] = auth()->user()->company_id;

        if (!empty($data['telepon'])) {
            $number = preg_replace('/[^0-9]/', '', $data['telepon']);
            if (str_starts_with($number, '0')) {
                $number = '62' . substr($number, 1);
            }
            $data['telepon'] = $number;
        }

        unset($data['contact_method']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

