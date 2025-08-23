<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\IMS\EnkripsiIMS;
use Illuminate\Support\Facades\DB;

class EncryptExistingUsers extends Command
{
    protected $signature = 'data:encrypt-users';
    protected $description = 'Encrypt existing NIK, email, and telepon data for users';

    public function handle()
    {
        $this->info('Starting user data encryption...');
        $encryptionKey = hex2bin(env('IMS_ENCRYPTION_KEY'));
        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
        if (!$encryptionKey || !$pepperKey) {
            $this->error('Encryption keys are not set in the .env file.');
            return 1;
        }
        $encryptor = new EnkripsiIMS($encryptionKey);
        DB::connection()->disableQueryLog();
        User::withoutEvents(function () use ($encryptor, $pepperKey) {
            $totalUsers = User::whereNull('nik_encrypted')->orWhereNull('email_encrypted')->count();
            if ($totalUsers == 0) {
                $this->info('All user data seems to be encrypted already. No action taken.');
                return;
            }
            $progressBar = $this->output->createProgressBar($totalUsers);
            $progressBar->start();
            User::whereNull('nik_encrypted')->orWhereNull('email_encrypted')->chunk(200, function ($users) use ($encryptor, $pepperKey, $progressBar) {
                foreach ($users as $user) {
                    $updates = [];
                    if (!empty($user->nik) && empty($user->nik_encrypted)) {
                        $updates['nik_encrypted'] = $encryptor->encrypt($user->nik);
                        $updates['nik_search_hash'] = hash_hmac('sha256', $user->nik, $pepperKey);
                        if (strlen($user->nik) >= 8) {
                            $prefix = substr($user->nik, 0, 8);
                            $updates['nik_prefix_hash'] = hash_hmac('sha256', $prefix, $pepperKey);
                        }
                    }
                    if (!empty($user->email) && empty($user->email_encrypted)) {
                        $updates['email_encrypted'] = $encryptor->encrypt($user->email);
                        $updates['email_search_hash'] = hash_hmac('sha256', $user->email, $pepperKey);
                    }
                    if (isset($user->telepon) && !empty($user->telepon) && empty($user->telepon_encrypted)) {
                        $updates['telepon_encrypted'] = $encryptor->encrypt($user->telepon);
                        $updates['telepon_search_hash'] = hash_hmac('sha256', $user->telepon, $pepperKey);
                    }
                    if (!empty($updates)) {
                        User::where('id', $user->id)->update($updates);
                    }
                    $progressBar->advance();
                }
            });
            $progressBar->finish();
            $this->info("\nUser data encryption completed successfully.");
        });
        return 0;
    }
}