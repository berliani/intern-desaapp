<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\IMS\EnkripsiIMS;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EncryptRemainingData extends Command
{
    protected $signature = 'data:encrypt-remaining';
    protected $description = 'Encrypts existing data for various tables in the application';
    private ?EnkripsiIMS $encryptor = null;
    private ?string $pepperKey = null;

    public function handle()
    {
        $this->info('Starting data encryption process for remaining tables...');

        $encryptionKey = hex2bin(env('IMS_ENCRYPTION_KEY'));
        $this->pepperKey = hex2bin(env('IMS_PEPPER_KEY'));

        if (!$encryptionKey || !$this->pepperKey) {
            $this->error('Encryption keys are not set correctly in the .env file.');
            return 1;
        }

        $this->encryptor = new EnkripsiIMS($encryptionKey);
        DB::connection()->disableQueryLog();

        $this->processTable('profil_desa', ['telepon', 'email']);
        $this->processTable('penduduk', ['nik', 'kk', 'no_hp', 'email']); // Menggunakan 'kk'
        $this->processTable('kartu_keluarga', ['nomor_kk']); // Menggunakan 'kartu_keluarga'
        $this->processTable('aparat_desa', ['kontak']);
        $this->processTable('verifikasi_penduduk', ['nik', 'kk']); // Menggunakan 'kk'

        $this->info("\nData encryption for all tables completed successfully.");
        return 0;
    }

    private function processTable(string $tableName, array $fields)
    {
        $this->line("\nProcessing table: {$tableName}");

        if (!Schema::hasTable($tableName)) {
            $this->warn("Table {$tableName} does not exist. Skipping.");
            return;
        }

        $total = DB::table($tableName)->count();
        if ($total === 0) {
            $this->info("Table {$tableName} is empty. Skipping.");
            return;
        }

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        DB::table($tableName)->orderBy('id')->chunk(200, function ($records) use ($tableName, $fields, $progressBar) {
            foreach ($records as $record) {
                $updates = [];
                foreach ($fields as $field) {
                    $encryptedField = "{$field}_encrypted";
                    if (property_exists($record, $field) && !empty($record->{$field}) && empty($record->{$encryptedField})) {
                        $updates[$encryptedField] = $this->encryptor->encrypt($record->{$field});
                        $updates["{$field}_search_hash"] = hash_hmac('sha256', $record->{$field}, $this->pepperKey);

                        // PERBAIKAN: Logika khusus untuk prefix NIK, HANYA untuk tabel 'penduduk'
                        if ($tableName === 'penduduk' && $field === 'nik' && strlen($record->{$field}) >= 8) {
                            $prefix = substr($record->{$field}, 0, 8);
                            $updates['nik_prefix_hash'] = hash_hmac('sha256', $prefix, $this->pepperKey);
                        }
                    }
                }

                if (!empty($updates)) {
                    DB::table($tableName)->where('id', $record->id)->update($updates);
                }
                $progressBar->advance();
            }
        });

        $progressBar->finish();
    }
}
