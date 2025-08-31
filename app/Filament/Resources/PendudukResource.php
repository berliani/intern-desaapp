<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendudukResource\Pages;
use App\Models\Penduduk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;
use App\Filament\Imports\PendudukImporter;
use Illuminate\Database\Eloquent\Model;
use Closure;
use Carbon\Carbon;
use Exception;

class PendudukResource extends Resource
{
    protected static ?string $model = Penduduk::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Kependudukan';
    protected static ?string $recordTitleAttribute = 'nama';
    protected static ?int $navigationSort = 1;

    public static ?string $tenantOwnershipRelationshipName = 'company';

    public static function getNavigationLabel(): string
    {
        return 'Data Penduduk';
    }

    public static function getPluralLabel(): string
    {
        return 'Data Penduduk';
    }

    public static function getNavigationBadge(): ?string
    {
        // PERBAIKAN: Menggunakan getEloquentQuery() yang sudah otomatis terfilter oleh tenant.
        $count = static::getEloquentQuery()->count();
        return $count > 0 ? $count : null;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Identitas')
                    ->description('Data identitas dasar penduduk')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nik')
                                    ->label('NIK')
                                    ->required()
                                    ->length(16)
                                    ->rule(function (?Model $record): Closure {
                                        return function (string $attribute, $value, Closure $fail) use ($record) {
                                            // Menggunakan metode hashForSearch dari model untuk konsistensi
                                            $hashedNik = Penduduk::hashForSearch($value);
                                            $query = Penduduk::where('nik_search_hash', $hashedNik);

                                            if ($record) {
                                                $query->where('id', '!=', $record->id);
                                            }

                                            if ($query->exists()) {
                                                $fail('NIK sudah terdaftar di sistem.');
                                            }
                                        };
                                    }),
                                Forms\Components\TextInput::make('kk')
                                    ->label('Nomor KK')
                                    ->required()
                                    ->length(16),
                            ]),
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(100)
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('jenis_kelamin')
                                    ->label('Jenis Kelamin')
                                    ->options([
                                        'L' => 'Laki-laki',
                                        'P' => 'Perempuan',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('agama')
                                    ->options([
                                        'Islam' => 'Islam',
                                        'Kristen' => 'Kristen',
                                        'Katolik' => 'Katolik',
                                        'Hindu' => 'Hindu',
                                        'Buddha' => 'Buddha',
                                        'Konghucu' => 'Konghucu',
                                        'Lainnya' => 'Lainnya',
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Data Kelahiran')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('tempat_lahir')
                                    ->label('Tempat Lahir')
                                    ->maxLength(100),
                                Forms\Components\DatePicker::make('tanggal_lahir')
                                    ->label('Tanggal Lahir')
                                    ->displayFormat('d M Y')
                                    ->required(),
                                Forms\Components\Select::make('golongan_darah')
                                    ->label('Golongan Darah')
                                    ->options([
                                        'A' => 'A',
                                        'A+' => 'A+',
                                        'A-' => 'A-',
                                        'B' => 'B',
                                        'B+' => 'B+',
                                        'B-' => 'B-',
                                        'AB' => 'AB',
                                        'AB-' => 'AB-',
                                        'O' => 'O',
                                        'O-' => 'O-',
                                        'Tidak Tahu' => 'Tidak Tahu',
                                    ]),
                            ]),
                    ]),

                Forms\Components\Section::make('Alamat & Status')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('rt')
                                    ->label('RT')
                                    ->mask('999')
                                    ->placeholder('001')
                                    ->required(),
                                Forms\Components\TextInput::make('rw')
                                    ->label('RW')
                                    ->mask('999')
                                    ->placeholder('001')
                                    ->required(),
                            ]),
                        Forms\Components\Textarea::make('alamat')
                            ->label('Alamat Lengkap (Contoh: Kp. Sukamaju RT 001/RW 001)')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('desa_kelurahan')
                                    ->label('Desa/Kelurahan')
                                    ->required()
                                    ->readOnly()
                                    ->default(fn() => Filament::getTenant()?->profilDesa?->nama_desa),
                                Forms\Components\TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->required()
                                    ->readOnly()
                                    ->default(fn() => Filament::getTenant()?->profilDesa?->kecamatan),
                                Forms\Components\TextInput::make('kabupaten')
                                    ->label('Kabupaten/Kota')
                                    ->required()
                                    ->readOnly()
                                    ->default(fn() => Filament::getTenant()?->profilDesa?->kabupaten),
                                Forms\Components\TextInput::make('provinsi')
                                    ->label('Provinsi')
                                    ->required()
                                    ->readOnly()
                                    ->default(fn() => Filament::getTenant()?->profilDesa?->provinsi),
                            ]),
                    ]),

                Forms\Components\Section::make('Status Keluarga')
                    ->schema([
                        Forms\Components\Radio::make('kepala_keluarga')
                            ->label('Status dalam Keluarga')
                            ->boolean()
                            ->options([
                                1 => 'Kepala Keluarga',
                                0 => 'Anggota Keluarga',
                            ])
                            ->default(0)
                            ->required()
                            ->live(),
                        Forms\Components\Placeholder::make('info_kk')
                            ->label('Informasi Kepala Keluarga')
                            ->content('Anggota keluarga akan otomatis terhubung dengan Kepala Keluarga dengan nomor KK yang sama.')
                            ->visible(fn(Forms\Get $get): bool => $get('kepala_keluarga') == 0),
                    ]),

                Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Select::make('status_perkawinan')
                            ->label('Status Perkawinan')
                            ->options([
                                'Belum Kawin' => 'Belum Kawin',
                                'Kawin' => 'Kawin',
                                'Cerai Hidup' => 'Cerai Hidup',
                                'Cerai Mati' => 'Cerai Mati',
                            ])
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('pekerjaan')
                                    ->maxLength(100),
                                Forms\Components\Select::make('pendidikan')
                                    ->options([
                                        'Tidak Sekolah' => 'Tidak Sekolah',
                                        'Belum Sekolah' => 'Belum Sekolah',
                                        'SD/Sederajat' => 'SD/Sederajat',
                                        'SMP/Sederajat' => 'SMP/Sederajat',
                                        'SMA/Sederajat' => 'SMA/Sederajat',
                                        'D1' => 'D1',
                                        'D2' => 'D2',
                                        'D3' => 'D3',
                                        'D4/S1' => 'D4/S1',
                                        'S2' => 'S2',
                                        'S3' => 'S3',
                                    ]),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('no_hp')
                                    ->label('Nomor HP')
                                    ->tel()
                                    ->telRegex('/^(\+62|62|0)8[1-9][0-9]{6,11}$/')
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                            ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('nik_search_hash', Penduduk::hashForSearch($search));
                    }),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->url(fn(Penduduk $record): string => static::getUrl('view', [
                        'record' => $record,
                        'tenant' => Filament::getTenant(),
                    ])),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => 'Tidak Diketahui',
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'L' => 'info',
                        'P' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->formatStateUsing(function ($state): ?string {
                        if (empty($state)) {
                            return null;
                        }
                        try {
                            // Coba format jika ini adalah tanggal yang valid
                            return Carbon::parse($state)->translatedFormat('d F Y');
                        } catch (Exception $e) {
                            // Jika gagal (misalnya, nilainya "Gagal Dekripsi"), kembalikan teks aslinya
                            return $state;
                        }
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('golongan_darah')
                    ->label('Gol. Darah')
                    ->badge()
                    ->color(fn(?string $state): string => match ($state) {
                        'A' => 'success',
                        'A+' => 'success',
                        'A-' => 'success',
                        'B' => 'success',
                        'B+' => 'info',
                        'B-' => 'info',
                        'AB' => 'warning',
                        'AB-' => 'warning',
                        'O' => 'danger',
                        'O-' => 'danger',
                        'Tidak Tahu' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),
                Tables\Columns\IconColumn::make('kepala_keluarga')
                    ->label('KK')
                    ->boolean(),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('alamat_search_hash', Penduduk::hashForSearch($search));
                    })
                    ->limit(30),
                Tables\Columns\TextColumn::make('rt')
                    ->label('RT/RW')
                    ->formatStateUsing(fn($record) => $record->rt . '/' . $record->rw)
                    ->searchable(['rt', 'rw']),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('Nomor HP')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('no_hp_search_hash', Penduduk::hashForSearch($search));
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where('email_search_hash', Penduduk::hashForSearch($search));
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options(['L' => 'Laki-laki', 'P' => 'Perempuan']),
                Tables\Filters\SelectFilter::make('agama'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('downloadTemplate')
                    ->label('Download Template')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->action(function () {
                        // Logika download 
                    }),
                Tables\Actions\ImportAction::make()
                    ->importer(PendudukImporter::class)
                    ->chunkSize(100)->maxRows(1000)
                    ->icon('heroicon-o-document-arrow-down')->color('success')->label('Impor Penduduk'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenduduks::route('/'),
            'create' => Pages\CreatePenduduk::route('/create'),
            'view' => Pages\ViewPenduduk::route('/{record}'),
            'edit' => Pages\EditPenduduk::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getWidgets(): array
    {
        return [
            PendudukResource\Widgets\PendudukStats::class,
        ];
    }
}
