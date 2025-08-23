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
use Filament\Facades\Filament; // <-- DITAMBAHKAN

class PendudukResource extends Resource
{
    protected static ?string $model = Penduduk::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Kependudukan';
    protected static ?string $recordTitleAttribute = 'nama';
    protected static ?int $navigationSort = 1;

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
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Section::make('Informasi Identitas')
                    ->description('Data identitas dasar penduduk')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('nik')
                                    ->label('NIK')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(16),
                                Forms\Components\TextInput::make('kk')
                                    ->label('Nomor KK')
                                    ->required()
                                    ->maxLength(16),
                            ])
                            ->columns(2),

                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(100)
                            ->columnSpanFull(),

                        Forms\Components\Grid::make()
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
                            ])
                            ->columns(2),
                    ]),

                // Data Kelahiran - Full Width
                Forms\Components\Section::make('Data Kelahiran')
                    ->schema([
                        Forms\Components\Grid::make()
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
                                        'B' => 'B',
                                        'AB' => 'AB',
                                        'O' => 'O',
                                        'A+' => 'A+',
                                        'A-' => 'A-',
                                        'B+' => 'B+',
                                        'B-' => 'B-',
                                        'AB+' => 'AB+',
                                        'AB-' => 'AB-',
                                        'O+' => 'O+',
                                        'O-' => 'O-',
                                        'Belum Diketahui' => 'Belum Diketahui',
                                    ]),
                            ])
                            ->columns(3),
                    ]),

                // Alamat & Status - Full Width
                Forms\Components\Section::make('Alamat & Status')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('rt_rw')
                                    ->label('RT/RW')
                                    ->required()
                                    ->maxLength(10)
                                    ->placeholder('000/000'),
                                Forms\Components\TextInput::make('desa_kelurahan')
                                    ->label('Desa/Kelurahan')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('kecamatan')
                                    ->label('Kecamatan')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('kabupaten')
                                    ->label('Kabupaten/Kota')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\Textarea::make('alamat')
                                    ->label('Alamat Lengkap')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ])
                    ]),

                // Status Keluarga - Full Width
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
                            ->reactive(),

                        Forms\Components\Placeholder::make('info_kk')
                            ->label('Informasi Kepala Keluarga')
                            ->content('Anggota keluarga akan otomatis terhubung dengan Kepala Keluarga dengan nomor KK yang sama.')
                            ->visible(fn (Forms\Get $get) => $get('kepala_keluarga') === 0),
                    ]),

                // Informasi Tambahan - Full Width, pindah ke bawah
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

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('pekerjaan')
                                    ->maxLength(100),
                                Forms\Components\Select::make('pendidikan')
                                    ->options([
                                        'Tidak Sekolah' => 'Tidak Sekolah',
                                        'SD/Sederajat' => 'SD/Sederajat',
                                        'SMP/Sederajat' => 'SMP/Sederajat',
                                        'SMA/Sederajat' => 'SMA/Sederajat',
                                        'D1/D2/D3' => 'D1/D2/D3',
                                        'D4/S1' => 'D4/S1',
                                        'S2' => 'S2',
                                        'S3' => 'S3',
                                    ]),
                            ])
                            ->columns(2),

                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('no_hp')
                                    ->label('Nomor HP')
                                    ->tel()
                                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->url(fn (Penduduk $record): string => route('filament.admin.resources.penduduks.view', ['record' => $record])),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                        default => 'Tidak Diketahui',
                    })
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'L' => 'info',
                        'P' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('golongan_darah')
                    ->label('Gol. Darah')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'A', 'A+', 'A-' => 'success',
                        'B', 'B+', 'B-' => 'info',
                        'AB', 'AB+', 'AB-' => 'warning',
                        'O', 'O+', 'O-' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),
                Tables\Columns\IconColumn::make('kepala_keluarga')
                    ->label('KK')
                    ->boolean(),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('rt_rw')
                    ->label('RT/RW')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->label('Nomor HP')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('desa_kelurahan')
                    ->label('Desa/Kelurahan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kecamatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kabupaten')
                    ->label('Kabupaten/Kota')
                    ->searchable()
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
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),
                Tables\Filters\SelectFilter::make('agama')
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Katolik' => 'Katolik',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                        'Konghucu' => 'Konghucu',
                        'Lainnya' => 'Lainnya',
                    ]),
                Tables\Filters\SelectFilter::make('golongan_darah')
                    ->label('Golongan Darah')
                    ->options([
                        'A' => 'A',
                        'B' => 'B',
                        'AB' => 'AB',
                        'O' => 'O',
                        'A+' => 'A+',
                        'A-' => 'A-',
                        'B+' => 'B+',
                        'B-' => 'B-',
                        'AB+' => 'AB+',
                        'AB-' => 'AB-',
                        'O+' => 'O+',
                        'O-' => 'O-',
                    ]),
            ])
            ->headerActions([
                // Tombol download template baru
                Tables\Actions\Action::make('downloadTemplate')
                    ->label('Download Template')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('primary')
                    ->action(function () {
                        // Buat CSV dari kolom-kolom di importer
                        $columns = array_map(function ($column) {
                            return $column->getName();
                        }, PendudukImporter::getColumns());

                        // Buat contoh data untuk template
                        $exampleData = [
                            [
                                'nik' => '3501020304050607',
                                'kk' => '3501020304050001',
                                'nama' => 'Budi Santoso',
                                'jenis_kelamin' => 'L',
                                'agama' => 'Islam',
                                'tempat_lahir' => 'Jakarta',
                                'tanggal_lahir' => '1990-01-01',
                                'golongan_darah' => 'O',
                                'alamat' => 'Jl. Contoh No. 123',
                                'rt_rw' => '001/002',
                                'desa_kelurahan' => 'Sukamaju',
                                'kecamatan' => 'Cianjur',
                                'kabupaten' => 'Bandung',
                                'kepala_keluarga' => '1',
                                'status_perkawinan' => 'Kawin',
                                'pekerjaan' => 'Pegawai Swasta',
                                'pendidikan' => 'SMA/Sederajat',
                                'no_hp' => '081234567890',
                                'email' => 'contoh@email.com',
                            ],
                            [
                                'nik' => '3501020304050608',
                                'kk' => '3501020304050001',
                                'nama' => 'Siti Rahayu',
                                'jenis_kelamin' => 'P',
                                'agama' => 'Islam',
                                'tempat_lahir' => 'Bandung',
                                'tanggal_lahir' => '1992-05-15',
                                'golongan_darah' => 'A',
                                'alamat' => 'Jl. Contoh No. 123',
                                'rt_rw' => '001/002',
                                'desa_kelurahan' => 'Sukamaju',
                                'kecamatan' => 'Cianjur',
                                'kabupaten' => 'Bandung',
                                'kepala_keluarga' => '0',
                                'status_perkawinan' => 'Kawin',
                                'pekerjaan' => 'Guru',
                                'pendidikan' => 'D4/S1',
                                'no_hp' => '081234567891',
                                'email' => 'siti@email.com',
                            ],
                        ];

                        // Buat file CSV
                        $filename = 'template_import_penduduk.csv';
                        $path = storage_path('app/' . $filename);

                        $handle = fopen($path, 'w');

                        // Tulis header
                        fputcsv($handle, $columns);

                        // Tulis contoh data
                        foreach ($exampleData as $row) {
                            $rowData = [];
                            foreach ($columns as $column) {
                                $rowData[] = $row[$column] ?? '';
                            }
                            fputcsv($handle, $rowData);
                        }

                        fclose($handle);

                        return response()->download($path, $filename, [
                            'Content-Type' => 'text/csv',
                        ])->deleteFileAfterSend();
                    }),

                // Tombol import yang sudah ada
                ImportAction::make()
                    ->importer(PendudukImporter::class)
                    ->chunkSize(100)
                    ->maxRows(1000)
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->label('Impor Penduduk'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // Tambahkan action untuk restore individual
                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->tooltip('Mengembalikan data penduduk yang telah dihapus')
                    ->successNotificationTitle('Data penduduk berhasil dipulihkan'),

                // Tambahkan action untuk force delete individual
                Tables\Actions\ForceDeleteAction::make()
                    ->label('Hapus Permanen')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Menghapus data penduduk secara permanen')
                    ->successNotificationTitle('Data penduduk berhasil dihapus permanen'),

                Tables\Actions\Action::make('export')
                    ->label('Ekspor')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('success')
                    ->form([
                        Forms\Components\Radio::make('format')
                            ->label('Format')
                            ->options([
                                'pdf' => 'PDF',
                                'excel' => 'Excel',
                            ])
                            ->default('pdf')
                            ->required()
                            ->inline(),
                    ])
                    ->action(function (Penduduk $record, array $data): void {
                        $url = route('export.penduduk', [
                            'penduduk' => $record->id,
                            'format' => $data['format']
                        ]);
                        redirect()->away($url);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // Action ini sudah ada, tapi mari tingkatkan labelnya
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label('Hapus Permanen Masal')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->successNotificationTitle('Data penduduk terpilih berhasil dihapus permanen'),

                    // Action ini juga sudah ada, tapi mari tingkatkan labelnya
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan Masal')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->successNotificationTitle('Data penduduk terpilih berhasil dipulihkan'),

                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-check-circle')
                        ->form([
                            Forms\Components\Select::make('status_perkawinan')
                                ->label('Status Perkawinan')
                                ->options([
                                    'Belum Kawin' => 'Belum Kawin',
                                    'Kawin' => 'Kawin',
                                    'Cerai Hidup' => 'Cerai Hidup',
                                    'Cerai Mati' => 'Cerai Mati',
                                ])
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            foreach ($records as $record) {
                                $record->status_perkawinan = $data['status_perkawinan'];
                                $record->save();
                            }
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('exportSelected')
                        ->label('Ekspor Terpilih')
                        ->icon('heroicon-o-document-arrow-up')
                        ->color('success')
                        ->form([
                            Forms\Components\Select::make('periode')
                                ->label('Periode Data')
                                ->options([
                                    'semua' => 'Semua Waktu',
                                    'hari_ini' => 'Hari Ini',
                                    'minggu_ini' => 'Minggu Ini',
                                    'bulan_ini' => 'Bulan Ini',
                                    'tahun_ini' => 'Tahun Ini',
                                    'bulan_lalu' => 'Bulan Lalu',
                                    'tahun_lalu' => 'Tahun Lalu',
                                    'kustom' => 'Kustom (Pilih Tanggal)',
                                ])
                                ->default('semua')
                                ->live()
                                ->afterStateUpdated(function($state, callable $set) {
                                    if ($state !== 'kustom') {
                                        $set('dari_tanggal', null);
                                        $set('sampai_tanggal', null);
                                    }
                                }),

                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\DatePicker::make('dari_tanggal')
                                        ->label('Dari Tanggal')
                                        ->visible(fn ($get) => $get('periode') === 'kustom'),

                                    Forms\Components\DatePicker::make('sampai_tanggal')
                                        ->label('Sampai Tanggal')
                                        ->visible(fn ($get) => $get('periode') === 'kustom'),
                                ]),

                            Forms\Components\Radio::make('format')
                                ->label('Format Ekspor')
                                ->options([
                                    'pdf' => 'PDF',
                                    'excel' => 'Excel',
                                ])
                                ->default('pdf')
                                ->required()
                                ->inline(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $dariTanggal = null;
                            $sampaiTanggal = null;

                            if ($data['periode'] === 'hari_ini') {
                                $dariTanggal = Carbon::today()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->format('Y-m-d');
                            }
                            elseif ($data['periode'] === 'minggu_ini') {
                                $dariTanggal = Carbon::today()->startOfWeek()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->endOfWeek()->format('Y-m-d');
                            }
                            elseif ($data['periode'] === 'bulan_ini') {
                                $dariTanggal = Carbon::today()->startOfMonth()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->endOfMonth()->format('Y-m-d');
                            }
                            elseif ($data['periode'] === 'tahun_ini') {
                                $dariTanggal = Carbon::today()->startOfYear()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->endOfYear()->format('Y-m-d');
                            }
                            elseif ($data['periode'] === 'bulan_lalu') {
                                $dariTanggal = Carbon::today()->subMonth()->startOfMonth()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->subMonth()->endOfMonth()->format('Y-m-d');
                            }
                            elseif ($data['periode'] === 'tahun_lalu') {
                                $dariTanggal = Carbon::today()->subYear()->startOfYear()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->subYear()->endOfYear()->format('Y-m-d');
                            }
                            elseif ($data['periode'] === 'kustom') {
                                $dariTanggal = isset($data['dari_tanggal']) ? $data['dari_tanggal']->format('Y-m-d') : null;
                                $sampaiTanggal = isset($data['sampai_tanggal']) ? $data['sampai_tanggal']->format('Y-m-d') : null;
                            }

                            $params = [
                                'ids' => $records->pluck('id')->join(','),
                                'format' => $data['format'] ?? 'pdf',
                            ];

                            if ($dariTanggal) {
                                $params['dari_tanggal'] = $dariTanggal;
                            }

                            if ($sampaiTanggal) {
                                $params['sampai_tanggal'] = $sampaiTanggal;
                            }

                            return redirect()->route('export.penduduk.selected', $params);
                        }),
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
        return parent::getEloquentQuery()->where('company_id', Filament::getTenant()->id);
    }
        public static function getWidgets(): array
    {
        return [
            PendudukResource\Widgets\PendudukStats::class,
        ];
    }
}

