<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\IMS\EnkripsiIMS;
use App\Mail\AccountStatusNotificationMail;
use App\Models\User;
use App\Models\VerifikasiPenduduk;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administrasi Sistem';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'super_admin');
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nama')
                    ->disabled(fn (string $context): bool => $context === 'edit')
                    ->dehydrated(),

                TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->alphaDash()
                    ->label('Username')
                    ->disabled(fn (string $context): bool => $context === 'edit')
                    ->dehydrated(),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email')
                    ->disabled(fn (string $context): bool => $context === 'edit')
                    ->dehydrated(fn (string $context): bool => $context === 'create')
                    ->afterStateHydrated(function (TextInput $component, ?Model $record, string $context) {
                        if ($context === 'edit' && $record) {
                            $encryptedEmail = $record->getAttributeValue('email_encrypted');
                            if ($encryptedEmail) {
                                try {
                                    $key = hex2bin(env('IMS_ENCRYPTION_KEY'));
                                    $encryptor = new EnkripsiIMS($key);
                                    $decryptedEmail = $encryptor->decrypt($encryptedEmail);
                                    $component->state($decryptedEmail);
                                } catch (\Exception $e) {
                                    $component->state('ERROR: Gagal Dekripsi');
                                }
                            }
                        }
                    }),

                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->visibleOn('create'),

                Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name', fn (Builder $query) => $query->where('name', '!=', 'super_admin'))
                    ->preload()
                    ->label('Role Pengguna'),

                Select::make('penduduk_id')
                    ->relationship('penduduk', 'nama')
                    ->searchable()
                    ->preload()
                    ->label('Data Penduduk Terkait')
                    ->helperText('Isi jika user ini adalah warga desa')
                    ->disabled()
                    ->dehydrated(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->label('Nama'),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('roles.name')->badge()->label('Role'),
                IconColumn::make('penduduk_id')->boolean()->label('Warga Desa')->state(fn ($record): bool => $record->penduduk_id !== null),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->relationship('roles', 'name', fn (Builder $query) => $query->where('name', '!=', 'super_admin'))
                    ->label('Filter berdasarkan Role'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        TextInput::make('catatan')
                            ->label('Alasan Penolakan (Opsional)')
                            ->helperText('Alasan ini akan dikirimkan ke warga.'),
                    ])
                    ->action(function (Model $record, array $data) {
                        $verifikasi = VerifikasiPenduduk::where('user_id', $record->id)
                            ->where('status', 'pending')
                            ->first();
                        
                        if ($verifikasi) {
                            $verifikasi->update([
                                'status' => 'rejected', 
                                'catatan' => $data['catatan']
                            ]);

                            self::sendAccountStatusNotification($record, 'ditolak', $data['catatan']);

                            Notification::make()->title('Verifikasi Ditolak')->success()->send();
                        } else {
                            Notification::make()->title('Gagal')->body('Tidak ada data verifikasi pending untuk user ini.')->warning()->send();
                        }
                    })
                    ->visible(fn (Model $record): bool => VerifikasiPenduduk::where('user_id', $record->id)->where('status', 'pending')->exists()),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function sendAccountStatusNotification(Model $user, string $status, ?string $catatan = null): void
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
