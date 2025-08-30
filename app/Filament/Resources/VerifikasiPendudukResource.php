<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VerifikasiPendudukResource\Pages;
use App\Mail\VerifikasiStatusMail;
use App\Models\Penduduk;
use App\Models\VerifikasiPenduduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client as TwilioClient;

class VerifikasiPendudukResource extends Resource
{
    protected static ?string $model = VerifikasiPenduduk::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Kependudukan';
    protected static ?string $recordTitleAttribute = 'nama';
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return 'Verifikasi Data Warga';
    }

    public static function getPluralLabel(): string
    {
        return 'Verifikasi Data Warga';
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->where('status', 'pending')->count();
        return $count > 0 ? $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getEloquentQuery()->where('status', 'pending')->count() > 0 ? 'warning' : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Diri')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nik')->label('NIK')->disabled()->formatStateUsing(fn ($record) => $record?->nik),
                        Forms\Components\TextInput::make('kk')->label('Nomor KK')->disabled()->formatStateUsing(fn ($record) => $record?->kk),
                        Forms\Components\TextInput::make('nama')->disabled(),
                        Forms\Components\TextInput::make('tempat_lahir')->label('Tempat Lahir')->disabled(),
                        Forms\Components\TextInput::make('tanggal_lahir')->label('Tanggal Lahir')->disabled()->formatStateUsing(fn ($record) => $record?->tanggal_lahir),
                        Forms\Components\TextInput::make('jenis_kelamin')->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan')->disabled(),
                        Forms\Components\TextInput::make('golongan_darah')->label('Golongan Darah')->disabled(),
                    ]),
                Forms\Components\Section::make('Alamat')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Textarea::make('alamat')->columnSpan(2)->disabled()->formatStateUsing(fn ($record) => $record?->alamat),
                        Forms\Components\TextInput::make('rt')->disabled(),
                        Forms\Components\TextInput::make('rw')->disabled(),
                    ]),
                Forms\Components\Section::make('Informasi Kontak')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('no_hp')->label('Nomor HP')->disabled()->formatStateUsing(fn ($record) => $record?->no_hp),
                        Forms\Components\TextInput::make('email')->label('Email')->disabled()->formatStateUsing(fn ($record) => $record?->email),
                    ]),
                Forms\Components\Section::make('Informasi Tambahan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('agama')->disabled(),
                        Forms\Components\TextInput::make('status_perkawinan')->disabled(),
                        Forms\Components\TextInput::make('pekerjaan')->disabled(),
                        Forms\Components\TextInput::make('pendidikan')->disabled(),
                        Forms\Components\Checkbox::make('kepala_keluarga')->label('Kepala Keluarga')->disabled(),
                    ]),
                Forms\Components\Section::make('Informasi Pengajuan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('user.name')->label('Nama Pengaju')->disabled(),
                        Forms\Components\TextInput::make('user.email')->label('Email Pengaju')->disabled()->formatStateUsing(fn ($record) => $record?->user?->email),
                        Forms\Components\DateTimePicker::make('created_at')->label('Tanggal Pengajuan')->disabled(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->searchable(),
                Tables\Columns\TextColumn::make('nik')->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->where('nik_search_hash', VerifikasiPenduduk::hashForSearch($search));
                }),
                Tables\Columns\TextColumn::make('kk')->label('Nomor KK')->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->where('kk_search_hash', VerifikasiPenduduk::hashForSearch($search));
                })->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')->label('Pengaju')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal')->date('d/m/Y H:i')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (VerifikasiPenduduk $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (VerifikasiPenduduk $record) {
                        DB::beginTransaction();
                        try {
                            $nikSearchHash = Penduduk::hashForSearch($record->nik);
                            $existingPenduduk = Penduduk::where('nik_search_hash', $nikSearchHash)->first();
                            
                            $desa = $record->desa;

                            // PENAMBAHAN VALIDASI
                            if (!$desa || !$desa->nama_desa || !$desa->kecamatan || !$desa->kabupaten || !$desa->provinsi) {
                                DB::rollBack();
                                Notification::make()
                                    ->title('Gagal Memproses Verifikasi')
                                    ->body('Data profil desa (nama, kecamatan, kabupaten, atau provinsi) tidak lengkap. Silakan periksa data master desa.')
                                    ->danger()
                                    ->send();
                                return;
                            }

                            $pendudukData = [
                                'company_id' => $record->company_id, 'desa_id' => $record->desa_id,
                                'nik' => $record->nik, 'kk' => $record->kk, 'nama' => $record->nama,
                                'alamat' => $record->alamat, 'rt' => $record->rt, 'rw' => $record->rw,
                                'desa_kelurahan' => $desa->nama_desa,
                                'kecamatan' => $desa->kecamatan,
                                'kabupaten' => $desa->kabupaten,
                                'provinsi' => $desa->provinsi,
                                'tempat_lahir' => $record->tempat_lahir, 'tanggal_lahir' => $record->tanggal_lahir,
                                'jenis_kelamin' => $record->jenis_kelamin, 'agama' => $record->agama,
                                'status_perkawinan' => $record->status_perkawinan, 'kepala_keluarga' => $record->kepala_keluarga,
                                'pekerjaan' => $record->pekerjaan, 'pendidikan' => $record->pendidikan,
                                'email' => $record->email, 'no_hp' => $record->no_hp,
                                'golongan_darah' => $record->golongan_darah, 'user_id' => $record->user_id,
                            ];

                            if ($existingPenduduk) {
                                $existingPenduduk->update($pendudukData);
                                $penduduk = $existingPenduduk;
                            } else {
                                $penduduk = Penduduk::create($pendudukData);
                            }

                            $record->update(['penduduk_id' => $penduduk->id, 'status' => 'approved']);

                            $user = $record->user;
                            if ($user) {
                                $user->penduduk_id = $penduduk->id;
                                $user->save();
                                $user->assignRole('warga');
                            }

                            DB::commit();
                            Notification::make()->title('Verifikasi berhasil disetujui')->success()->send();
                            self::kirimNotifikasiStatusVerifikasi($record);
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Notification::make()->title('Gagal memproses verifikasi')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn (VerifikasiPenduduk $record) => $record->status === 'pending')
                    ->form([Forms\Components\Textarea::make('catatan')->label('Alasan Penolakan')->required()])
                    ->action(function (VerifikasiPenduduk $record, array $data) {
                        $record->update(['status' => 'rejected', 'catatan' => $data['catatan']]);
                        Notification::make()->title('Verifikasi telah ditolak')->success()->send();
                        self::kirimNotifikasiStatusVerifikasi($record);
                    }),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVerifikasiPenduduks::route('/'),
            'view' => Pages\ViewVerifikasiPenduduk::route('/{record}'),
        ];
    }

    public static function kirimNotifikasiStatusVerifikasi(VerifikasiPenduduk $verifikasi): void
    {
        if (!$verifikasi->user) {
            Log::warning("Percobaan notifikasi untuk verifikasi ID: {$verifikasi->id} tanpa user terkait.");
            return;
        }

        try {
            if (!empty($verifikasi->email)) {
                self::sendEmailVerifikasi($verifikasi);
            } elseif (!empty($verifikasi->no_hp)) {
                self::sendWhatsAppVerifikasi($verifikasi);
            } else {
                Log::info("Tidak ada email atau nomor HP untuk mengirim notifikasi verifikasi ID: {$verifikasi->id}");
            }
        } catch (\Exception $e) {
            Log::error('Gagal mengirim notifikasi verifikasi: ' . $e->getMessage());
            Notification::make()
                ->title('Notifikasi Gagal Terkirim')
                ->body('Verifikasi berhasil, namun notifikasi ke warga gagal dikirim. Periksa log untuk detail.')
                ->warning()
                ->send();
        }
    }

    private static function sendEmailVerifikasi(VerifikasiPenduduk $verifikasi): void
    {
        Mail::to($verifikasi->email)->send(new VerifikasiStatusMail($verifikasi));
    }

    private static function sendWhatsAppVerifikasi(VerifikasiPenduduk $verifikasi): void
    {
        $sid    = env('TWILIO_SID');
        $token  = env('TWILIO_AUTH_TOKEN');
        $from   = env('TWILIO_WHATSAPP_FROM');

        if (!$sid || !$token || !$from) {
            Log::error('Konfigurasi Twilio tidak lengkap.');
            return;
        }

        $client = new TwilioClient($sid, $token);
        $to = 'whatsapp:+' . self::normalizePhoneNumber($verifikasi->no_hp);
        
        $body = '';
        if ($verifikasi->status === 'approved') {
            $body = "Kabar baik, {$verifikasi->nama}! Pengajuan verifikasi data kependudukan Anda telah DISETUJUI. Anda sekarang dapat mengakses layanan warga di portal desa kami.";
        } elseif ($verifikasi->status === 'rejected') {
            $body = "Mohon maaf, {$verifikasi->nama}. Pengajuan verifikasi data kependudukan Anda DITOLAK.\n\nAlasan: {$verifikasi->catatan}\n\nSilakan hubungi kantor desa untuk informasi lebih lanjut.";
        }

        if ($body) {
            $client->messages->create($to, ["from" => $from, "body" => $body]);
        }
    }

    private static function normalizePhoneNumber(?string $phoneNumber): ?string
    {
        if (empty($phoneNumber)) return null;
        $number = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }
        if (!str_starts_with($number, '62')) {
            return '62' . $number;
        }
        return $number;
    }
}

