<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Mail\AccountStatusNotificationMail; 
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use App\Models\VerifikasiPenduduk;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail; 
use Twilio\Rest\Client as TwilioClient; 

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
    {
        $user = $this->record;

        if ($user->hasRole('warga') && is_null($user->penduduk_id)) {
            
            $verifikasi = VerifikasiPenduduk::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if ($verifikasi) {
                $profilDesa = $verifikasi->desa;
                if ($profilDesa) {
                    $penduduk = Penduduk::create([
                        'company_id' => $verifikasi->company_id,
                        'id_desa' => $verifikasi->id_desa,
                        'desa_kelurahan' => $profilDesa->nama_desa,
                        'kecamatan' => $profilDesa->kecamatan,
                        'kabupaten' => $profilDesa->kabupaten,
                        'provinsi' => $profilDesa->provinsi,
                        'nama' => $verifikasi->nama,
                        'nik_encrypted' => $verifikasi->nik_encrypted,
                        'nik_search_hash' => $verifikasi->nik_search_hash,
                        'nik_prefix_hash' => $verifikasi->nik_prefix_hash,
                        'kk_encrypted' => $verifikasi->kk_encrypted,
                        'kk_search_hash' => $verifikasi->kk_search_hash,
                        'alamat' => $verifikasi->alamat,
                        'rt_rw' => $verifikasi->rt_rw,
                        'tempat_lahir' => $verifikasi->tempat_lahir,
                        'tanggal_lahir_encrypted' => $verifikasi->tanggal_lahir_encrypted,
                        'tanggal_lahir_search_hash' => $verifikasi->tanggal_lahir_search_hash, 
                        'jenis_kelamin' => $verifikasi->jenis_kelamin,
                        'agama' => $verifikasi->agama,
                        'status_perkawinan' => $verifikasi->status_perkawinan,
                        'kepala_keluarga' => $verifikasi->kepala_keluarga, 
                        'kepala_keluarga_id' => $verifikasi->kepala_keluarga_id, 
                        'pekerjaan' => $verifikasi->pekerjaan,
                        'pendidikan' => $verifikasi->pendidikan,
                        'no_hp_encrypted' => $verifikasi->no_hp_encrypted,
                        'no_hp_search_hash' => $verifikasi->no_hp_search_hash,
                        'email_encrypted' => $verifikasi->email_encrypted,
                        'email_search_hash' => $verifikasi->email_search_hash,
                        'golongan_darah' => $verifikasi->golongan_darah,
                        'user_id' => $user->id,
                    ]);

                    $user->update(['penduduk_id' => $penduduk->id]);
                    $verifikasi->update(['status' => 'approved', 'penduduk_id' => $penduduk->id]);

                    $this->sendAccountStatusNotification($user, 'disetujui');

                    Notification::make()->title('Verifikasi Berhasil')->body('Data penduduk telah dibuat dan notifikasi telah dikirim ke warga.')->success()->send();
                }
            }
        }
    }

    private function sendAccountStatusNotification(Model $user, string $status, ?string $catatan = null): void
    {
        $namaWarga = $user->name;
        $namaDesa = $user->company->name ?? 'Desa Digital';
        $loginUrl = route('login'); 

        if ($user->email) {
            Mail::to($user->email)->send(new AccountStatusNotificationMail($namaWarga, $namaDesa, $status, $loginUrl, $catatan));
        }

        if ($user->telepon) {
            try {
                $sid    = env('TWILIO_SID');
                $token  = env('TWILIO_AUTH_TOKEN');
                $from   = env('TWILIO_WHATSAPP_FROM');
                
                if ($sid && $token && $from) {
                    $body = $status === 'disetujui'
                        ? "Selamat {$namaWarga}, akun Anda di portal {$namaDesa} telah berhasil diverifikasi. Silakan login untuk menggunakan semua layanan."
                        : "Mohon maaf {$namaWarga}, pengajuan verifikasi akun Anda di portal {$namaDesa} ditolak." . ($catatan ? " Alasan: {$catatan}" : "");

                    $client = new TwilioClient($sid, $token);
                    $client->messages->create('whatsapp:+' . $user->telepon, [
                        "from" => $from,
                        "body" => $body
                    ]);
                }
            } catch (\Exception $e) {
                Notification::make()->title('Gagal Kirim WhatsApp')->body($e->getMessage())->danger()->send();
            }
        }
    }
}
