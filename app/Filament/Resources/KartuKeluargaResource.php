<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KartuKeluargaResource\Pages;
use App\Models\Penduduk;
use App\Models\ProfilDesa;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Form;
use Filament\Forms\Components\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KartuKeluargaExporter;
use Maatwebsite\Excel\Facades\Excel;

class KartuKeluargaResource extends Resource
{
    protected static ?string $model = Penduduk::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Kependudukan';

    protected static ?string $navigationLabel = 'Kartu Keluarga';

    protected static ?string $modelLabel = 'Kartu Keluarga';

    protected static ?string $pluralModelLabel = 'Kartu Keluarga';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                // Query untuk mendapatkan penduduk yang kepala keluarga
                Penduduk::query()
                    ->where('kepala_keluarga', true)
                // ->orderBy('kk')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Kepala Keluarga')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nik')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                        if (!$pepperKey) return $query;
                        $hashedSearch = hash_hmac('sha256', $search, $pepperKey);
                        return $query->where('nik_search_hash', $hashedSearch);
                    }),

                Tables\Columns\TextColumn::make('kk')
                    ->label('Nomor KK')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                        if (!$pepperKey) return $query;
                        $hashedSearch = hash_hmac('sha256', $search, $pepperKey);
                        return $query->where('kk_search_hash', $hashedSearch);
                    })
                    ->copyable(),

                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn(string $state): string => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                    ->badge()
                    ->color(fn(string $state): string => $state === 'L' ? 'info' : 'danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('alamat')
                    ->limit(30)
                    ->tooltip(function (Penduduk $record): string {
                        return $record->alamat;
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                        if (!$pepperKey) return $query;
                        $hashedSearch = hash_hmac('sha256', $search, $pepperKey);
                        return $query->where('alamat_search_hash', $hashedSearch);
                    }),
                Tables\Columns\TextColumn::make('rt_rw')
                    ->label('RT/RW')
                    ->formatStateUsing(fn (Penduduk $record): string => "{$record->rt}/{$record->rw}"),

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
                // Jumlah anggota keluarga (tidak termasuk kepala keluarga)
                Tables\Columns\TextColumn::make('Jumlah Anggota')
                    ->getStateUsing(function (Penduduk $record) {
                        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                        if (!$pepperKey || !$record->kk) return 0;

                        $kkSearchHash = hash_hmac('sha256', $record->kk, $pepperKey);

                        return Penduduk::where('kk_search_hash', $kkSearchHash)
                            ->where('kepala_keluarga', false)
                            ->count();
                    }),
                Tables\Columns\TextColumn::make('desa.nama_desa')
                    ->label('Desa')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('nama', 'asc')
            ->filters([

                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->label('Jenis Kelamin Kepala Keluarga')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),

                Tables\Filters\SelectFilter::make('status_perkawinan')
                    ->label('Status Perkawinan')
                    ->options([
                        'Belum Kawin' => 'Belum Kawin',
                        'Kawin' => 'Kawin',
                        'Cerai Hidup' => 'Cerai Hidup',
                        'Cerai Mati' => 'Cerai Mati',
                    ]),


                Tables\Filters\Filter::make('memiliki_anggota')
                    ->label('Memiliki Anggota')
                    ->query(function (Builder $query): Builder {
                        $kkHashesWithMembers = Penduduk::query()
                            ->select('kk_search_hash')
                            ->whereNotNull('kk_search_hash')
                            ->where('kepala_keluarga', false)
                            ->groupBy('kk_search_hash')
                            ->havingRaw('COUNT(*) > 0')
                            ->pluck('kk_search_hash');

                        return $query->whereIn('kk_search_hash', $kkHashesWithMembers);
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn(Penduduk $record): string => static::getUrl('view', [
                        'record' => $record->id,
                        'tenant' => Filament::getTenant()
                    ])),

                Tables\Actions\Action::make('export')
                    ->label('Ekspor')
                    ->icon('heroicon-o-document-arrow-up')
                    ->color('success')
                    ->form([
                        Select::make('format')
                            ->label('Format Ekspor')
                            ->options([
                                'pdf' => 'PDF',
                                'excel' => 'Excel',
                            ])
                            ->default('pdf')
                            ->required(),
                    ])
                    ->action(function (Penduduk $record, array $data) {
                        $kkSearchHashes = collect([$record->kk_search_hash]);

                        if ($data['format'] === 'pdf') {
                            $semuaPenduduk = Penduduk::whereIn('kk_search_hash', $kkSearchHashes)->get();
                            $keluargas = $semuaPenduduk->groupBy('kk_search_hash')->map(function ($anggota) {
                                $nomor_kk_asli = $anggota->first()->kk;
                                return collect([$nomor_kk_asli => $anggota]);
                            })->collapse();

                            $dataForView = ['keluargas' => $keluargas];
                            $pdf = Pdf::loadView('pdf.kartu-keluarga', $dataForView)->setPaper('a4', 'landscape');
                            return response()->streamDownload(fn() => print($pdf->output()), 'kartu-keluarga-' . $record->kk . '.pdf');
                        } elseif ($data['format'] === 'excel') {
                            return Excel::download(new KartuKeluargaExporter($kkSearchHashes), 'kartu-keluarga-' . $record->kk . '.xlsx');
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('exportSelected')
                        ->label('Ekspor Terpilih')
                        ->icon('heroicon-o-document-arrow-up')
                        ->color('success')
                        ->form([
                            // Form Anda untuk bulk export...
                        ])
                        ->action(function (Collection $records, array $data) {
                            // Logika bulk export Anda...
                        })
                        ->deselectRecordsAfterCompletion(),
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
            'index' => Pages\ListKartuKeluarga::route('/'),
            'view' => Pages\ViewKartuKeluarga::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('company_id', Filament::getTenant()->id);
    }

    public static function getNavigationBadge(): ?string
    {
        if ($tenant = Filament::getTenant()) {
            return static::getModel()::where('company_id', $tenant->id)->where('kepala_keluarga', true)->count();
        }
        return 0;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
