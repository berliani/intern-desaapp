<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\IMS\EnkripsiIMS;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['company_id'] = Auth::user()->company_id;
        
        $key = hex2bin(env('IMS_ENCRYPTION_KEY'));
        if (!$key) {
            throw new \Exception("Kunci enkripsi IMS tidak valid.");
        }
        $encryptor = new EnkripsiIMS($key);
        if (!empty($data['email'])) {
            $data['email_encrypted'] = $encryptor->encrypt($data['email']);
            $data['email_search_hash'] = hash('sha256', strtolower($data['email']));
        }
        unset($data['email']);

        return $data;
    }
}
