<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisBansosResource\Pages;
use App\Filament\Resources\JenisBansosResource\Widgets\JenisBansosStats;
use App\Models\JenisBansos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Collection;
use Filament\Facades\Filament;

class JenisBansosResource extends Resource
{
    protected static ?string $model = JenisBansos::class;

    protected static bool $isScopedToTenant = false;
    protected static ?string $navigationIcon = 'heroicon-o-gift';

    protected static ?string $navigationGroup = 'Bantuan Sosial';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'nama_bansos';



    public static function getNavigationLabel(): string
    {
        return 'Jenis Bantuan Sosial';
    }

    public static function getPluralLabel(): string
    {
        return 'Jenis Bantuan Sosial';
    }

    public static function getNavigationBadge(): ?string
    {

        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('nama_bansos')
                            ->label('Nama Program Bantuan')
                            ->required()
                            ->maxLength(255),

                        Select::make('kategori')
                            ->label('Kategori')
                            ->options(JenisBansos::getKategoriOptions())
                            ->required(),

                        Select::make('bentuk_bantuan')
                            ->label('Bentuk Bantuan')
                            ->options(JenisBansos::getBentukBantuanOptions())
                            ->required()
                            ->live()
                            ->afterStateUpdated(
                                fn($state, Set $set) =>
                                $state === 'uang' ? $set('satuan', null) : $set('nominal_standar', null)
                            ),

                        TextInput::make('nominal_standar')
                            ->label('Nominal Standar')
                            ->prefix('Rp')
                            ->inputMode('numeric')
                            ->mask(RawJs::make(<<<'JS'
                            function (value) {
                                // Hapus semua karakter non-digit
                                let numericValue = value.replace(/\D/g, '');

                                // Format dengan separator titik setiap 3 digit
                                if (numericValue === '') return '';

                                return numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                            }
                            JS))
                            ->rules(['required_if:bentuk_bantuan,uang'])
                            ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state))
                            ->formatStateUsing(function ($state) {
                                if (is_numeric($state)) {
                                    return number_format($state, 0, ',', '.');
                                }
                                return $state;
                            })
                            ->hidden(fn(Get $get) => $get('bentuk_bantuan') !== 'uang')
                            ->placeholder('0')
                            ->default('0'),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('jumlah_per_penerima')
                                    ->label('Jumlah per Penerima')
                                    ->rules(['numeric', 'required_if:bentuk_bantuan,barang,jasa,voucher,bantuan_modal,pelatihan,lainnya'])
                                    ->inputMode('numeric')
                                    ->hidden(fn(Get $get) => $get('bentuk_bantuan') === 'uang')
                                    ->placeholder('0')
                                    ->default('0'),

                                Select::make('satuan')
                                    ->label('Satuan')
                                    ->options(JenisBansos::getSatuanOptions())
                                    ->hidden(fn(Get $get) => $get('bentuk_bantuan') === 'uang')
                                    ->requiredIf('bentuk_bantuan', fn($state) => $state !== 'uang'),
                            ]),

                        TextInput::make('instansi_pemberi')
                            ->label('Instansi Pemberi')
                            ->required()
                            ->maxLength(255),

                        Select::make('periode')
                            ->label('Periode Bantuan')
                            ->options(JenisBansos::getPeriodeOptions())
                            ->required(),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi Program')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Status Program')
                            ->onColor('success')
                            ->offColor('danger')
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_bansos')
                    ->label('Nama Program')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kategori')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Sembako' => 'primary',
                        'Tunai' => 'success',
                        'Kesehatan' => 'info',
                        'Pendidikan' => 'warning',
                        'Perumahan' => 'danger',
                        'Pangan' => 'primary',
                        'Pertanian' => 'success',
                        'UMKM' => 'info',
                        'Lainnya' => 'gray',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),

                TextColumn::make('bentuk_bantuan')
                    ->label('Bentuk Bantuan')
                    ->formatStateUsing(
                        fn(?string $state): string =>
                        JenisBansos::getBentukBantuanOptions()[$state] ?? '-'
                    )
                    ->sortable(),

                TextColumn::make('nilai_bantuan_formatted')
                    ->label('Nilai Bantuan')
                    ->state(fn(JenisBansos $record): string => $record->getNilaiBantuanFormatted()),

                TextColumn::make('instansi_pemberi')
                    ->label('Instansi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('periode')
                    ->formatStateUsing(
                        fn(?string $state): string =>
                        JenisBansos::getPeriodeOptions()[$state] ?? '-'
                    )
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->options(JenisBansos::getKategoriOptions())
                    ->multiple(),
                Tables\Filters\SelectFilter::make('bentuk_bantuan')
                    ->options(JenisBansos::getBentukBantuanOptions())
                    ->multiple(),
                Tables\Filters\SelectFilter::make('periode')
                    ->options(JenisBansos::getPeriodeOptions())
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Program')
                    ->boolean()
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->native(false),
                Tables\Filters\TrashedFilter::make()
                    ->label('Status Penghapusan')
                    ->queries(
                        true: fn(Builder $query) => $query->onlyTrashed(),
                        false: fn(Builder $query) => $query->withTrashed(),
                        blank: fn(Builder $query) => $query->withoutTrashed()
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->tooltip('Mengembalikan jenis bantuan yang telah dihapus')
                    ->successNotificationTitle('Jenis bantuan berhasil dipulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('Hapus Permanen')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Menghapus jenis bantuan secara permanen')
                    ->successNotificationTitle('Jenis bantuan berhasil dihapus permanen'),
                Tables\Actions\Action::make('export')
                    ->label('Ekspor')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('format')
                            ->label('Format Ekspor')
                            ->options([
                                'pdf' => 'PDF',
                                'excel' => 'Excel',
                            ])
                            ->default('pdf')
                            ->required(),
                    ])
                    ->action(function (JenisBansos $record, array $data) {
                        $format = $data['format'] ?? 'pdf';
                        return redirect()->route('jenis-bansos.export', [
                            'jenisBansos' => $record,
                            'format' => $format
                        ]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan Massal')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->successNotificationTitle('Jenis bantuan terpilih berhasil dipulihkan'),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label('Hapus Permanen Massal')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->successNotificationTitle('Jenis bantuan terpilih berhasil dihapus permanen'),
                    Tables\Actions\BulkAction::make('exportSelected')
                        ->label('Ekspor Terpilih')
                        ->icon('heroicon-o-document-arrow-up')
                        ->color('success')
                        ->form([
                            Forms\Components\Select::make('format')
                                ->label('Format Ekspor')
                                ->options([
                                    'pdf' => 'PDF',
                                    'excel' => 'Excel',
                                ])
                                ->default('excel')
                                ->required(),
                        ])
                        ->action(function (Collection $records, array $data) {
                            return redirect()->route('jenis-bansos.export.selected', [
                                'ids' => $records->pluck('id')->join(','),
                                'format' => $data['format'] ?? 'excel',
                            ]);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn(Builder $query) => $query->latest());
    }

    public static function getWidgets(): array
    {
        return [
            JenisBansosResource\Widgets\JenisBansosStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJenisBansos::route('/'),
            'create' => Pages\CreateJenisBansos::route('/create'),
            'view' => Pages\ViewJenisBansos::route('/{record}'),
            'edit' => Pages\EditJenisBansos::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {

        return parent::getEloquentQuery();
    }
}
