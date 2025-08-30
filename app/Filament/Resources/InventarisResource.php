<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventarisResource\Pages;
use App\Filament\Resources\InventarisResource\Widgets\InventarisStats;
use App\Models\Inventaris;
use App\Models\ProfilDesa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Str;
use Filament\Resources\Widgets\Widget;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use Filament\Facades\Filament;

class InventarisResource extends Resource
{
    protected static ?string $model = Inventaris::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Desa';

    protected static ?string $navigationLabel = 'Inventaris';

    protected static ?string $recordTitleAttribute = 'nama_barang';
    protected static ?string $tenantOwnershipRelationshipName = 'company';

    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return 'Inventaris';
    }

    public static function getNavigationBadge(): ?string
    {

        if ($tenant = Filament::getTenant()) {
            return Inventaris::where('company_id', $tenant->id)->count();
        }

        return 0;
    }

    //     public static function getTenantRelationshipName(): string
    // {
    //     return 'inventaris';
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Barang')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('kode_barang')
                                    ->label('Kode Barang')
                                    ->default('Akan dibuat otomatis setelah disimpan')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nama_barang')
                                    ->label('Nama Barang')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Masukkan nama barang'),
                                Forms\Components\Select::make('status')
                                    ->label('Status Barang')
                                    ->options(Inventaris::getStatusOptions())
                                    ->default('Tersedia')
                                    ->required()
                                    ->placeholder('Pilih status'),
                            ]),
                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('kategori')
                                    ->options(Inventaris::getKategoriOptions())
                                    ->required()
                                    ->placeholder('Pilih kategori'),
                                Forms\Components\TextInput::make('jumlah')
                                    ->label('Jumlah Barang')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->placeholder('Jumlah barang'),
                                Forms\Components\Select::make('kondisi')
                                    ->options(Inventaris::getKondisiOptions())
                                    ->required()
                                    ->placeholder('Pilih kondisi'),
                            ]),
                    ]),

                Section::make('Informasi Pengadaan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_perolehan')
                                    ->label('Tanggal Perolehan')
                                    ->displayFormat('d/m/Y')
                                    ->default(now())
                                    ->placeholder('Pilih tanggal'),
                                TextInput::make('nominal_harga')
                                    ->label('Nominal Harga')
                                    ->required()
                                    ->prefix('Rp')
                                    ->mask(RawJs::make(<<<'JS'
                                    function (value) {
                                        // Hapus semua karakter non-digit
                                        let numericValue = value.replace(/\D/g, '');

                                        // Format dengan separator titik setiap 3 digit
                                        if (numericValue === '') return '';

                                        return numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                    }
                                    JS))
                                    ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state))
                                    ->formatStateUsing(function ($state) {
                                        if (is_numeric($state)) {
                                            return number_format($state, 0, ',', '.');
                                        }
                                        return $state;
                                    })
                                    ->placeholder('Masukkan nominal harga')
                                    ->default('0'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('sumber_dana')
                                    ->label('Sumber Dana')
                                    ->options(Inventaris::getSumberDanaOptions())
                                    ->placeholder('Pilih sumber dana'),
                                Forms\Components\TextInput::make('lokasi')
                                    ->label('Lokasi Penyimpanan')
                                    ->maxLength(150)
                                    ->placeholder('Masukkan lokasi penyimpanan'),
                            ]),
                    ]),

                Section::make('Keterangan & Foto')
                    ->schema([
                        Forms\Components\Textarea::make('keterangan')
                            ->rows(3)
                            ->maxLength(255)
                            ->placeholder('Masukkan keterangan tambahan')
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Barang')
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1280')
                            ->imageResizeTargetHeight('720')
                            ->directory('inventaris-foto')
                            ->placeholder('Unggah foto barang')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('created_by')
                            ->default(fn() => auth()->id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_barang')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_barang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kategori')
                    ->badge(),
                Tables\Columns\TextColumn::make('jumlah')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kondisi')
                    ->badge()
                    ->colors([
                        'success' => 'Baik',
                        'warning' => 'Rusak Ringan',
                        'danger' => 'Rusak Berat',
                    ]),
                Tables\Columns\TextColumn::make('nominal_harga')
                    ->label('Nominal Harga')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->alignRight(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'success' => 'Digunakan',
                        'warning' => 'Dipinjam',
                        'danger' => 'Rusak/Tidak Digunakan',
                        'gray' => 'Dihapus',
                    ]),
                Tables\Columns\TextColumn::make('tanggal_perolehan')
                    ->label('Tanggal Perolehan')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('desa.nama_desa')
                    ->label('Desa')
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_desa')
                    ->label('Desa')
                    ->options(ProfilDesa::pluck('nama_desa', 'id')),
                Tables\Filters\SelectFilter::make('kategori')
                    ->options(Inventaris::getKategoriOptions()),
                Tables\Filters\SelectFilter::make('kondisi')
                    ->options(Inventaris::getKondisiOptions()),
                Tables\Filters\SelectFilter::make('status')
                    ->options(Inventaris::getStatusOptions()),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->tooltip('Mengembalikan barang inventaris yang telah dihapus')
                    ->successNotificationTitle('Barang inventaris berhasil dipulihkan'),

                Tables\Actions\ForceDeleteAction::make()
                    ->label('Hapus Permanen')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Menghapus barang inventaris secara permanen')
                    ->successNotificationTitle('Barang inventaris berhasil dihapus permanen'),

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
                    ->action(function (Inventaris $record, array $data): void {
                        $url = route('inventaris.export', [
                            'inventaris' => $record->id,
                            'format' => $data['format']
                        ]);
                        redirect()->away($url);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label('Hapus Permanen Massal')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->successNotificationTitle('Barang inventaris terpilih berhasil dihapus permanen'),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan Massal')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->successNotificationTitle('Barang inventaris terpilih berhasil dipulihkan'),

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
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state !== 'kustom') {
                                        $set('dari_tanggal', null);
                                        $set('sampai_tanggal', null);
                                    }
                                }),

                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\DatePicker::make('dari_tanggal')
                                        ->label('Dari Tanggal')
                                        ->visible(fn($get) => $get('periode') === 'kustom'),

                                    Forms\Components\DatePicker::make('sampai_tanggal')
                                        ->label('Sampai Tanggal')
                                        ->visible(fn($get) => $get('periode') === 'kustom'),
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
                            } elseif ($data['periode'] === 'minggu_ini') {
                                $dariTanggal = Carbon::today()->startOfWeek()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->endOfWeek()->format('Y-m-d');
                            } elseif ($data['periode'] === 'bulan_ini') {
                                $dariTanggal = Carbon::today()->startOfMonth()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->endOfMonth()->format('Y-m-d');
                            } elseif ($data['periode'] === 'tahun_ini') {
                                $dariTanggal = Carbon::today()->startOfYear()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->endOfYear()->format('Y-m-d');
                            } elseif ($data['periode'] === 'bulan_lalu') {
                                $dariTanggal = Carbon::today()->subMonth()->startOfMonth()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->subMonth()->endOfMonth()->format('Y-m-d');
                            } elseif ($data['periode'] === 'tahun_lalu') {
                                $dariTanggal = Carbon::today()->subYear()->startOfYear()->format('Y-m-d');
                                $sampaiTanggal = Carbon::today()->subYear()->endOfYear()->format('Y-m-d');
                            } elseif ($data['periode'] === 'kustom') {
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

                            return redirect()->route('inventaris.export.selected', $params);
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventaris::route('/'),
            'create' => Pages\CreateInventaris::route('/create'),
            'view' => Pages\ViewInventaris::route('/{record}'),
            'edit' => Pages\EditInventaris::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return Inventaris::query()->where('company_id', Filament::getTenant()->id);
    }

    public static function getWidgets(): array
    {
        return [
            InventarisResource\Widgets\InventarisStats::class,
        ];
    }
}
