<?php

namespace App\Filament\Resources\VerifikasiPendudukResource\Pages;

use App\Filament\Resources\VerifikasiPendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

class ViewVerifikasiPenduduk extends ViewRecord
{
    protected static string $resource = VerifikasiPendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Setujui')
                ->icon('heroicon-o-check')
                ->color('success')
                ->visible(fn () => $this->record->status === 'pending')
                ->action(function () {
                    DB::beginTransaction();
                    try {
                        // Check if penduduk with this NIK already exists
                        $existingPenduduk = \App\Models\Penduduk::where('nik', $this->record->nik)->first();

                        if ($existingPenduduk) {
                            // Update existing penduduk record
                            $existingPenduduk->update([
                                'id_desa' => $this->record->id_desa ?? 1,
                                'kk' => $this->record->kk,
                                'nama' => $this->record->nama,
                                'tempat_lahir' => $this->record->tempat_lahir,
                                'tanggal_lahir' => $this->record->tanggal_lahir,
                                'jenis_kelamin' => $this->record->jenis_kelamin,
                                'alamat' => $this->record->alamat,
                                'rt_rw' => $this->record->rt_rw,
                                'agama' => $this->record->agama,
                                'status_perkawinan' => $this->record->status_perkawinan,
                                'kepala_keluarga' => $this->record->kepala_keluarga,
                                'kepala_keluarga_id' => $this->record->kepala_keluarga_id,
                                'pekerjaan' => $this->record->pekerjaan,
                                'pendidikan' => $this->record->pendidikan,
                                'no_hp' => $this->record->no_hp,
                                'email' => $this->record->email,
                                'golongan_darah' => $this->record->golongan_darah,
                            ]);
                            $penduduk = $existingPenduduk;
                        } else {
                            // Create new penduduk record
                            $penduduk = \App\Models\Penduduk::create([
                                'id_desa' => $this->record->id_desa ?? 1,
                                'nik' => $this->record->nik,
                                'kk' => $this->record->kk,
                                'nama' => $this->record->nama,
                                'tempat_lahir' => $this->record->tempat_lahir,
                                'tanggal_lahir' => $this->record->tanggal_lahir,
                                'jenis_kelamin' => $this->record->jenis_kelamin,
                                'alamat' => $this->record->alamat,
                                'rt_rw' => $this->record->rt_rw,
                                'agama' => $this->record->agama,
                                'status_perkawinan' => $this->record->status_perkawinan,
                                'kepala_keluarga' => $this->record->kepala_keluarga,
                                'kepala_keluarga_id' => $this->record->kepala_keluarga_id,
                                'pekerjaan' => $this->record->pekerjaan,
                                'pendidikan' => $this->record->pendidikan,
                                'no_hp' => $this->record->no_hp,
                                'email' => $this->record->email,
                                'golongan_darah' => $this->record->golongan_darah,
                            ]);
                        }

                        // 2. Update verifikasi
                        $this->record->update([
                            'status' => 'approved',
                            'catatan' => 'Verifikasi disetujui',
                            'penduduk_id' => $penduduk->id
                        ]);

                        // 3. Tangani user
                        $userEmail = '';
                        $userName = '';
                        $userPassword = 'password';
                        $isNewUser = false;

                        if (!$this->record->user_id) {
                            // Jika tidak ada user, buat baru
                            $isNewUser = true;
                            $email = strtolower(str_replace(' ', '', $this->record->nama)) . '@desaku.com';

                            // Cek jika email sudah ada
                            $existingUser = \App\Models\User::where('email', $email)->first();
                            if ($existingUser) {
                                $email = strtolower(str_replace(' ', '', $this->record->nama)) . rand(100, 999) . '@desaku.com';
                            }

                            $user = \App\Models\User::create([
                                'name' => $this->record->nama,
                                'email' => $email,
                                'password' => \Illuminate\Support\Facades\Hash::make($userPassword),
                                'penduduk_id' => $penduduk->id,
                                'nik' => $this->record->nik
                            ]);

                            // Tambahkan role warga
                            $user->assignRole('warga');

                            // Update verifikasi dengan user_id
                            $this->record->update(['user_id' => $user->id]);

                            $userEmail = $email;
                            $userName = $user->name;
                        } else {
                            // Jika user sudah ada
                            $user = \App\Models\User::find($this->record->user_id);
                            if ($user) {
                                // Update penduduk_id
                                $user->penduduk_id = $penduduk->id;
                                $user->save();

                                // Ganti role
                                $user->removeRole('unverified');
                                $user->assignRole('warga');

                                $userEmail = $user->email;
                                $userName = $user->name;
                            }
                        }

                        DB::commit();

                        // Tampilkan notifikasi dengan info akun
                        if ($isNewUser) {
                            // Notifikasi untuk user baru
                            \Filament\Notifications\Notification::make()
                                ->title('Verifikasi berhasil disetujui')
                                ->body("Data penduduk telah dibuat dan akun untuk login telah dibuat.<br>
                                       <strong>Nama:</strong> {$userName}<br>
                                       <strong>Email:</strong> {$userEmail}<br>
                                       <strong>Password:</strong> {$userPassword}")
                                ->success()
                                ->persistent()
                                ->send();
                        } else {
                            // Notifikasi untuk user existing
                            \Filament\Notifications\Notification::make()
                                ->title('Verifikasi berhasil disetujui')
                                ->body("Data penduduk telah dibuat dan user telah diberi role warga.<br>
                                       <strong>Nama:</strong> {$userName}<br>
                                       <strong>Email:</strong> {$userEmail}")
                                ->success()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        DB::rollBack();
                        \Filament\Notifications\Notification::make()
                            ->title('Gagal memproses verifikasi')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }

                    $this->redirect($this->getResource()::getUrl('index'));
                }),
            Actions\Action::make('reject')
                ->label('Tolak')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->visible(fn () => $this->record->status === 'pending')
                ->form([
                    \Filament\Forms\Components\Textarea::make('catatan')
                        ->label('Alasan Penolakan')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => 'rejected',
                        'catatan' => $data['catatan']
                    ]);

                    \Filament\Notifications\Notification::make()
                        ->title('Verifikasi telah ditolak')
                        ->success()
                        ->send();

                    $this->redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
}