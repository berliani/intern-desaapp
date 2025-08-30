<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengaduanResource\Pages;
use App\Models\Pengaduan;
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
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Collection;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Facades\Filament;
use App\Models\Penduduk;

class PengaduanResource extends Resource
{
    protected static ?string $model = Pengaduan::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Layanan Warga';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'judul';
    protected static ?string $tenantOwnershipRelationshipName = 'penduduk';

    public static function getNavigationLabel(): string
    {
        return 'Kelola Pengaduan';
    }

    public static function getPluralLabel(): string
    {
        return 'Kelola Pengaduan';
    }

    public static function getNavigationBadge(): ?string
    {

        if ($tenant = Filament::getTenant()) {
            return Pengaduan::where('company_id', $tenant->id)->count();
        }

        return 0;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pengaduan')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->label('Judul Pengaduan')
                            ->disabled()
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('penduduk.nama')
                                    ->label('Nama Pelapor')
                                    ->disabled(),

                                Forms\Components\TextInput::make('kategori')
                                    ->disabled(),
                            ]),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Pengaduan')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Bukti')
                            ->image()
                            ->directory('pengaduan')
                            ->disabled()
                            ->visible(fn($record) => filled($record->foto))
                            ->columnSpanFull(),
                    ]),

                Section::make('Tanggapan Pengaduan')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(Pengaduan::getStatusOptions())
                            ->required(),

                        Forms\Components\Select::make('prioritas')
                            ->options(Pengaduan::getPrioritasOptions())
                            ->required(),

                        Forms\Components\Textarea::make('tanggapan')
                            ->label('Tanggapan untuk Pelapor')
                            ->required()
                            ->helperText('Tanggapan ini akan dilihat oleh pelapor')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_public')
                            ->label('Tampilkan ke Publik')
                            ->helperText('Jika diaktifkan, pengaduan ini akan terlihat oleh publik'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn(Pengaduan $record) => $record->judul)
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('penduduk.nama')
                    ->label('Pelapor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Kesehatan' => 'success',
                        'Keamanan' => 'danger',
                        'Pelayanan Publik' => 'primary',
                        'Sosial' => 'info',
                        'Lainnya' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('prioritas')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Tinggi' => 'danger',
                        'Sedang' => 'warning',
                        'Rendah' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Belum Ditangani' => 'danger',
                        'Sedang Diproses' => 'warning',
                        'Selesai' => 'success',
                        'Ditolak' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('sudahDitanggapi')
                    ->label('Ditanggapi')
                    ->boolean()
                    ->getStateUsing(fn(Pengaduan $record) => $record->sudahDitanggapi())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Lapor')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('tanggal_tanggapan')
                    ->label('Tanggal Tanggapan')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([

                Tables\Filters\SelectFilter::make('kategori')
                    ->options(Pengaduan::getKategoriOptions()),

                Tables\Filters\SelectFilter::make('status')
                    ->options(Pengaduan::getStatusOptions()),

                Tables\Filters\SelectFilter::make('prioritas')
                    ->options(Pengaduan::getPrioritasOptions()),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),


                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                // Tambahkan action ekspor untuk single pengaduan
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
                    ->action(function (Pengaduan $record, array $data) {
                        return redirect()->route('pengaduan.export', [
                            'pengaduan' => $record,
                            'format' => $data['format'] ?? 'pdf'
                        ]);
                    }),

                // Action untuk respon cepat
                Tables\Actions\Action::make('tanggapi')
                    ->label('Tanggapi')
                    ->icon('heroicon-o-chat-bubble-left')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options(Pengaduan::getStatusOptions())
                            ->default('Sedang Diproses')
                            ->required(),

                        Forms\Components\Select::make('prioritas')
                            ->options(Pengaduan::getPrioritasOptions())
                            ->default(fn(Pengaduan $record) => $record->prioritas)
                            ->required(),

                        Forms\Components\Textarea::make('tanggapan')
                            ->required()
                            ->placeholder('Masukkan tanggapan untuk pengaduan ini')
                            ->rows(3),
                    ])
                    ->action(function (Pengaduan $record, array $data): void {
                        $record->update([
                            'status' => $data['status'],
                            'prioritas' => $data['prioritas'],
                            'tanggapan' => $data['tanggapan'],
                            'tanggal_tanggapan' => now(),
                            'ditangani_oleh' => auth()->id(),
                        ]);
                    }),

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),

                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Bulk action untuk mengubah status
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-check-circle')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options(Pengaduan::getStatusOptions())
                                ->required(),
                            Forms\Components\Textarea::make('tanggapan')
                                ->placeholder('Tanggapan umum untuk semua pengaduan yang dipilih')
                                ->rows(2),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $records->each(function ($record) use ($data): void {
                                $record->update([
                                    'status' => $data['status'],
                                    'tanggapan' => $data['tanggapan'] ?? $record->tanggapan,
                                    'tanggal_tanggapan' => now(),
                                    'ditangani_oleh' => auth()->id(),
                                ]);
                            });
                        }),

                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),

                    // Tambahkan bulk action untuk ekspor
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
                            return redirect()->route('pengaduan.export.selected', [
                                'ids' => $records->pluck('id')->toArray(),
                                'format' => $data['format'] ?? 'excel'
                            ]);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Pengaduan')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('judul')
                                    ->label('Judul Pengaduan')
                                    ->weight(FontWeight::Bold)
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Dilaporkan Pada')
                                    ->dateTime('d M Y, H:i'),
                            ]),

                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('penduduk.nama')
                                    ->label('Pelapor'),

                                Infolists\Components\TextEntry::make('kategori')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'Kesehatan' => 'success',
                                        'Keamanan' => 'danger',
                                        'Pelayanan Publik' => 'primary',
                                        'Sosial' => 'info',
                                        'Lainnya' => 'warning',
                                        default => 'gray',
                                    }),

                                Infolists\Components\TextEntry::make('prioritas')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'Tinggi' => 'danger',
                                        'Sedang' => 'warning',
                                        'Rendah' => 'success',
                                        default => 'gray',
                                    }),
                            ]),

                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('penduduk.no_hp')
                                    ->label('Nomor HP')
                                    ->icon('heroicon-o-phone')
                                    ->copyable()
                                    ->copyMessageDuration(2000)
                                    ->url(fn($state) => $state ? "tel:{$state}" : null)
                                    ->visible(fn($record) => !empty($record->penduduk?->no_hp)),

                                Infolists\Components\TextEntry::make('penduduk.email')
                                    ->label('Email')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->copyMessageDuration(2000)
                                    ->url(fn($state) => $state ? "mailto:{$state}" : null)
                                    ->visible(fn($record) => !empty($record->penduduk?->email)),

                                Infolists\Components\TextEntry::make('penduduk.alamat')
                                    ->label('Alamat')
                                    ->icon('heroicon-o-home')
                                    ->copyable()
                                    ->copyMessageDuration(2000)
                                    ->visible(fn($record) => !empty($record->penduduk?->alamat)),
                            ])
                            ->visible(
                                fn($record) =>
                                !empty($record->penduduk?->no_hp) ||
                                    !empty($record->penduduk?->email) ||
                                    !empty($record->penduduk?->alamat)
                            ),

                        Infolists\Components\TextEntry::make('deskripsi')
                            ->label('Deskripsi Pengaduan')
                            ->markdown()
                            ->columnSpanFull(),

                        Infolists\Components\ImageEntry::make('foto')
                            ->label('Foto Bukti')
                            ->visible(fn(Pengaduan $record) => filled($record->foto))
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Status dan Tanggapan')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn(string $state): string => match ($state) {
                                        'Belum Ditangani' => 'danger',
                                        'Sedang Diproses' => 'warning',
                                        'Selesai' => 'success',
                                        'Ditolak' => 'gray',
                                        default => 'gray',
                                    }),

                                Infolists\Components\IconEntry::make('is_public')
                                    ->label('Tampil di Publik')
                                    ->boolean(),
                            ]),

                        Infolists\Components\TextEntry::make('tanggapan')
                            ->label('Tanggapan')
                            ->markdown()
                            ->columnSpanFull()
                            ->visible(fn(Pengaduan $record) => filled($record->tanggapan)),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('petugas.name')
                                    ->label('Ditangani Oleh')
                                    ->visible(fn(Pengaduan $record) => filled($record->ditangani_oleh)),

                                Infolists\Components\TextEntry::make('tanggal_tanggapan')
                                    ->label('Tanggal Tanggapan')
                                    ->dateTime('d M Y, H:i')
                                    ->visible(fn(Pengaduan $record) => filled($record->tanggal_tanggapan)),
                            ]),

                        Infolists\Components\TextEntry::make('waktu_penanganan')
                            ->label('Waktu Penanganan')
                            ->state(function (Pengaduan $record): ?string {
                                $waktu = $record->waktuPenanganan();
                                return $waktu !== null ? "$waktu jam" : null;
                            })
                            ->visible(fn(Pengaduan $record) => $record->waktuPenanganan() !== null),
                    ]),
            ]);
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
            'index' => Pages\ListPengaduan::route('/'),
            'view' => Pages\ViewPengaduan::route('/{record}'),
            'edit' => Pages\EditPengaduan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {

        return static::getModel()::query()->where('company_id', Filament::getTenant()->id);
    }


    public static function getWidgets(): array
    {
        return [
            PengaduanResource\Widgets\PengaduanStats::class,
            PengaduanResource\Widgets\PengaduanChart::class,
        ];
    }
}
