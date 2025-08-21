<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeuanganDesaResource\Pages;
use App\Models\KeuanganDesa;
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
use Filament\Support\RawJs;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\KeuanganDesaResource\Widgets\KeuanganStats;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Collection;

class KeuanganDesaResource extends Resource
{
    protected static ?string $model = KeuanganDesa::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Desa';

    protected static ?string $recordTitleAttribute = 'deskripsi';

    protected static ?string $modelLabel = 'Keuangan Desa';

    protected static ?string $pluralModelLabel = 'Keuangan Desa';

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return 'Keuangan Desa';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Transaksi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('id_desa')
                                    ->label('Desa')
                                    ->options(ProfilDesa::pluck('nama_desa', 'id'))
                                    ->required(),
                                Forms\Components\Select::make('jenis')
                                    ->options([
                                        'Pemasukan' => 'Pemasukan',
                                        'Pengeluaran' => 'Pengeluaran',
                                    ])
                                    ->required(),
                            ]),
                        Forms\Components\TextInput::make('deskripsi')
                            ->required()
                            ->maxLength(255),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('jumlah')
                                    ->label('Jumlah Transaksi')
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
                                    ->dehydrateStateUsing(fn ($state) => preg_replace('/\D/', '', $state))
                                    ->formatStateUsing(function ($state) {
                                        if (is_numeric($state)) {
                                            return number_format($state, 0, ',', '.');
                                        }
                                        return $state;
                                    })
                                    ->rules(['required'])
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!empty($state)) {
                                            $numericValue = preg_replace('/[^0-9]/', '', $state);
                                            if ($numericValue) {
                                                $formattedValue = number_format((int)$numericValue, 0, ',', '.');
                                                $set('jumlah', $formattedValue);
                                            }
                                        }
                                    })
                                    ->placeholder('0')
                                    ->columnSpanFull(),
                                Forms\Components\DatePicker::make('tanggal')
                                    ->required(),
                            ]),
                        Forms\Components\Hidden::make('created_by')
                            ->default(fn () => auth()->id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('desa.nama_desa')
                    ->label('Desa')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis')
                    ->badge()
                    ->colors([
                        'success' => 'Pemasukan',
                        'danger' => 'Pengeluaran',
                    ]),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('jumlah')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable()
                    ->alignRight()
                    ->weight('bold')
                    ->color(fn ($record) => $record->jenis === 'Pemasukan' ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('jenis')
                    ->options([
                        'pemasukan' => 'Pemasukan',
                        'pengeluaran' => 'Pengeluaran',
                    ])
                    ->label('Jenis Transaksi'),

                Filter::make('tanggal')
                    ->form([
                        Forms\Components\Select::make('preset_periode')
                            ->label('Periode')
                            ->options([
                                'today' => 'Hari Ini',
                                'yesterday' => 'Kemarin',
                                'last7days' => '7 Hari Terakhir',
                                'last30days' => '30 Hari Terakhir',
                                'thismonth' => 'Bulan Ini',
                                'lastmonth' => 'Bulan Lalu',
                                'thisyear' => 'Tahun Ini',
                                'lastyear' => 'Tahun Lalu',
                                'custom' => 'Kustom (Pilih Tanggal)',
                            ])
                            // ->default('thismonth')
                            ->live()
                            ->afterStateUpdated(function (callable $get, callable $set, ?string $state) {
                                if ($state === 'custom') {
                                    return;
                                }

                                if ($state === 'today') {
                                    $set('dari_tanggal', today());
                                    $set('sampai_tanggal', today());
                                } elseif ($state === 'yesterday') {
                                    $set('dari_tanggal', today()->subDay());
                                    $set('sampai_tanggal', today()->subDay());
                                } elseif ($state === 'last7days') {
                                    $set('dari_tanggal', today()->subDays(6));
                                    $set('sampai_tanggal', today());
                                } elseif ($state === 'last30days') {
                                    $set('dari_tanggal', today()->subDays(29));
                                    $set('sampai_tanggal', today());
                                } elseif ($state === 'thismonth') {
                                    $set('dari_tanggal', today()->startOfMonth());
                                    $set('sampai_tanggal', today()->endOfMonth());
                                } elseif ($state === 'lastmonth') {
                                    $set('dari_tanggal', today()->subMonth()->startOfMonth());
                                    $set('sampai_tanggal', today()->subMonth()->endOfMonth());
                                } elseif ($state === 'thisyear') {
                                    $set('dari_tanggal', today()->startOfYear());
                                    $set('sampai_tanggal', today()->endOfYear());
                                } elseif ($state === 'lastyear') {
                                    $set('dari_tanggal', today()->subYear()->startOfYear());
                                    $set('sampai_tanggal', today()->subYear()->endOfYear());
                                }
                            }),
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal')
                            ->visible(fn ($get) => $get('preset_periode') === 'custom'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal')
                            ->visible(fn ($get) => $get('preset_periode') === 'custom'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (isset($data['preset_periode']) && $data['preset_periode'] !== 'custom') {
                            $periodNames = [
                                'today' => 'Hari Ini',
                                'yesterday' => 'Kemarin',
                                'last7days' => '7 Hari Terakhir',
                                'last30days' => '30 Hari Terakhir',
                                'thismonth' => 'Bulan Ini',
                                'lastmonth' => 'Bulan Lalu',
                                'thisyear' => 'Tahun Ini',
                                'lastyear' => 'Tahun Lalu',
                            ];

                            if (isset($periodNames[$data['preset_periode']])) {
                                $indicators['periode'] = 'Periode: ' . $periodNames[$data['preset_periode']];
                                return $indicators;
                            }
                        }

                        if ($data['dari_tanggal'] ?? null) {
                            $indicators['dari_tanggal'] = 'Dari: ' . Carbon::parse($data['dari_tanggal'])->format('d/m/Y');
                        }

                        if ($data['sampai_tanggal'] ?? null) {
                            $indicators['sampai_tanggal'] = 'Sampai: ' . Carbon::parse($data['sampai_tanggal'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),

                Tables\Filters\TrashedFilter::make()->label('Item Terhapus'),
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
                    ->tooltip('Mengembalikan data yang telah dihapus')
                    ->successNotificationTitle('Data berhasil dipulihkan'),

                // Tambahkan action untuk force delete individual
                Tables\Actions\ForceDeleteAction::make()
                    ->label('Hapus Permanen')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Menghapus data secara permanen'),

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
                    ->action(function (KeuanganDesa $record, array $data): void {
                        $url = route('keuangan.export', [
                            'keuangan' => $record->id,
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
                        ->label('Hapus Permanen Massal')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->successNotificationTitle('Data terpilih berhasil dihapus permanen'),

                    // Action ini juga sudah ada, tapi mari tingkatkan labelnya
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan Massal')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->successNotificationTitle('Data terpilih berhasil dipulihkan'),

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

                            // Format eksport di bawah periode
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
                            // Proses periode ke tanggal
                            $dariTanggal = null;
                            $sampaiTanggal = null;

                            // Konversi periode ke tanggal
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

                            // Buat array parameter
                            $params = [
                                'ids' => $records->pluck('id')->join(','),
                                'format' => $data['format'] ?? 'pdf',
                            ];

                            // Tambahkan parameter yang tidak null
                            if ($dariTanggal) {
                                $params['dari_tanggal'] = $dariTanggal;
                            }

                            if ($sampaiTanggal) {
                                $params['sampai_tanggal'] = $sampaiTanggal;
                            }

                            return redirect()->route('keuangan.export.selected', $params);
                        }),
                ]),
            ])
            ->defaultSort('tanggal', 'desc')
            ->emptyStateDescription(function() {
                $totalPemasukan = KeuanganDesa::where('jenis', 'Pemasukan')->sum('jumlah');
                $totalPengeluaran = KeuanganDesa::where('jenis', 'Pengeluaran')->sum('jumlah');
                $saldo = $totalPemasukan - $totalPengeluaran;

                $formattedPemasukan = 'Rp ' . number_format($totalPemasukan, 0, ',', '.');
                $formattedPengeluaran = 'Rp ' . number_format($totalPengeluaran, 0, ',', '.');
                $formattedSaldo = 'Rp ' . number_format($saldo, 0, ',', '.');

                return 'Belum ada data transaksi. Total: Pemasukan ' . $formattedPemasukan . ', Pengeluaran ' . $formattedPengeluaran . ', Saldo ' . $formattedSaldo;
            });
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
            'index' => Pages\ListKeuanganDesa::route('/'),
            'create' => Pages\CreateKeuanganDesa::route('/create'),
            'view' => Pages\ViewKeuanganDesa::route('/{record}'),
            'edit' => Pages\EditKeuanganDesa::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            KeuanganStats::class,
        ];
    }
}