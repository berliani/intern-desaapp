<?php

namespace App\Filament\Imports;

use App\Models\Penduduk;
use App\Models\ProfilDesa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Carbon;

class PendudukImporter extends Importer
{
    protected static ?string $model = Penduduk::class;

    public static function getColumns(): array
    {
        return [
            // Informasi Identitas
            ImportColumn::make('nik')
                ->requiredMapping()
                ->rules([
                    'required',
                    'string',
                    'size:16',
                    function ($attribute, $value, $fail) {
                        if (!preg_match('/^[0-9]{16}$/', $value)) {
                            $fail("NIK harus berisi 16 digit angka.");
                        }
                    }
                ])
                ->example('3501020304050607')
                ->guess(['nik', 'nomor_induk_kependudukan']),

            ImportColumn::make('kk')
                ->label('Nomor KK')
                ->requiredMapping()
                ->rules([
                    'required',
                    'string',
                    'size:16',
                    function ($attribute, $value, $fail) {
                        if (!preg_match('/^[0-9]{16}$/', $value)) {
                            $fail("Nomor KK harus berisi 16 digit angka.");
                        }
                    }
                ])
                ->example('3501020304050001')
                ->guess(['kk', 'kartu_keluarga', 'nomor_kk']),

            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:100'])
                ->examples(['Budi Santoso', 'Siti Rahayu'])
                ->guess(['nama', 'nama_lengkap']),

            ImportColumn::make('jenis_kelamin')
                ->requiredMapping()
                ->rules(['required', 'in:L,P'])
                ->examples(['L', 'P'])
                ->helperText('Gunakan "L" untuk Laki-laki atau "P" untuk Perempuan')
                ->guess(['jenis_kelamin', 'gender']),

            ImportColumn::make('agama')
                ->rules(['nullable', 'string', 'in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Lainnya'])
                ->example('Islam')
                ->guess(['agama', 'religion']),

            // Data Kelahiran
            ImportColumn::make('tempat_lahir')
                ->rules(['nullable', 'string', 'max:100'])
                ->example('Jakarta')
                ->guess(['tempat_lahir', 'birthplace', 'pob']),

            ImportColumn::make('tanggal_lahir')
                ->rules(['nullable', 'date'])
                ->example('1990-01-01')
                ->helperText('Format: YYYY-MM-DD (Tahun-Bulan-Tanggal)')
                ->guess(['tanggal_lahir', 'birthdate', 'dob', 'tgl_lahir']),

            ImportColumn::make('golongan_darah')
                ->rules(['nullable', 'string', 'in:A,B,AB,O,A+,A-,B+,B-,AB+,AB-,O+,O-,Belum Diketahui'])
                ->example('O')
                ->guess(['golongan_darah', 'blood_type', 'blood']),

            // Alamat
            ImportColumn::make('alamat')
                ->rules(['required', 'string'])
                ->example('Jl. Contoh No. 123')
                ->guess(['alamat', 'address']),

            ImportColumn::make('rt_rw')
                ->rules(['required', 'string', 'max:10'])
                ->example('001/002')
                ->guess(['rt_rw', 'rt/rw']),

            ImportColumn::make('desa_kelurahan')
                ->rules(['required', 'string', 'max:100'])
                ->example('Sukamaju')
                ->guess(['desa_kelurahan', 'desa', 'kelurahan', 'village']),

            ImportColumn::make('kecamatan')
                ->rules(['required', 'string', 'max:100'])
                ->example('Cianjur')
                ->guess(['kecamatan', 'district']),

            ImportColumn::make('kabupaten')
                ->rules(['required', 'string', 'max:100'])
                ->example('Bandung')
                ->guess(['kabupaten', 'kota', 'city', 'regency']),

            // Status Keluarga
            ImportColumn::make('kepala_keluarga')
                ->boolean()
                ->rules(['nullable', 'boolean'])
                ->example('1')
                ->helperText('Isi dengan 1 jika Kepala Keluarga, 0 jika Anggota Keluarga')
                ->guess(['kepala_keluarga', 'kk_status', 'is_head']),

            // Data Tambahan
            ImportColumn::make('status_perkawinan')
                ->rules(['nullable', 'string', 'in:Belum Kawin,Kawin,Cerai Hidup,Cerai Mati'])
                ->example('Kawin')
                ->guess(['status_perkawinan', 'marital_status']),

            ImportColumn::make('pekerjaan')
                ->rules(['nullable', 'string', 'max:100'])
                ->example('Pegawai Swasta')
                ->guess(['pekerjaan', 'job', 'occupation']),

            ImportColumn::make('pendidikan')
                ->rules(['nullable', 'string', 'in:Tidak Sekolah,SD/Sederajat,SMP/Sederajat,SMA/Sederajat,D1/D2/D3,D4/S1,S2,S3'])
                ->example('SMA/Sederajat')
                ->guess(['pendidikan', 'education']),

            ImportColumn::make('no_hp')
                ->rules(['nullable', 'string', 'max:20'])
                ->example('081234567890')
                ->guess(['no_hp', 'hp', 'telepon', 'phone']),

            ImportColumn::make('email')
                ->rules(['nullable', 'email', 'max:255'])
                ->example('contoh@email.com')
                ->guess(['email', 'e-mail']),
        ];
    }

    public function resolveRecord(): ?Penduduk
    {
        // Cari berdasarkan NIK jika ada
        if (!empty($this->data['nik'])) {
            $penduduk = Penduduk::firstWhere('nik', $this->data['nik']);

            if ($penduduk !== null) {
                return $penduduk;
            }
        }

        // Jika tidak ditemukan, buat instance baru
        return new Penduduk();
    }

    protected function beforeSave(): void
    {
        // Set ID desa default jika tidak diinput
        if (empty($this->record->id_desa)) {
            $this->record->id_desa = ProfilDesa::first()?->id;
        }

        // Jika kepala keluarga=1, pastikan kepala_keluarga_id kosong
        if ($this->record->kepala_keluarga) {
            $this->record->kepala_keluarga_id = null;
        }
    }

    // Setelah semua impor selesai, tetapkan kepala_keluarga_id untuk anggota keluarga
    protected function afterImportRow(): void
    {
        // Jika bukan kepala keluarga, cari kepala keluarga berdasarkan nomor KK
        if (isset($this->data['kk']) && isset($this->data['kepala_keluarga']) && !$this->data['kepala_keluarga']) {
            $kepalaKeluarga = Penduduk::where('kk', $this->data['kk'])
                ->where('kepala_keluarga', true)
                ->first();

            if ($kepalaKeluarga) {
                $this->record->kepala_keluarga_id = $kepalaKeluarga->id;
                $this->record->save();
            }
        }
    }

    // Opsi impor tambahan
    public static function getOptionsFormComponents(): array
    {
        return [
            \Filament\Forms\Components\Checkbox::make('update_existing')
                ->label('Update data yang sudah ada')
                ->default(true)
                ->helperText('Jika dicentang, data yang sudah ada akan diupdate. Jika tidak, hanya akan membuat data baru.'),

            \Filament\Forms\Components\Checkbox::make('skip_empty_values')
                ->label('Lewati nilai kosong')
                ->default(true)
                ->helperText('Jika dicentang, nilai kosong pada file CSV tidak akan mengubah data yang sudah ada.'),

            \Filament\Forms\Components\Select::make('default_desa')
                ->label('Desa Default')
                ->options(fn () => ProfilDesa::pluck('nama_desa', 'id'))
                ->helperText('Pilih desa default untuk data yang diimpor'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        if ($import->successful_rows) {
            return "Berhasil mengimpor {$import->successful_rows} data penduduk. Pastikan data Kepala Keluarga diimpor terlebih dahulu untuk hubungan anggota keluarga yang benar.";
        }

        return parent::getCompletedNotificationBody($import);
    }

    public static function getImportingDescription(): string
    {
        return 'Unggah data penduduk dalam format CSV. Untuk struktur keluarga yang benar, impor Kepala Keluarga terlebih dahulu, kemudian anggota keluarga. Contoh struktur data bisa diunduh dari tombol "Download Template".';
    }
}