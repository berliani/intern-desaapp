<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayananDesaResource\Pages;
use App\Models\LayananDesa;
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
use Carbon\Carbon;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Collection;
use Filament\Support\RawJs;
use Filament\Facades\Filament;

class LayananDesaResource extends Resource
{
    protected static ?string $model = LayananDesa::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    // Pindahkan ke grup "Desa" bersama dengan ProfilDesa
    protected static ?string $navigationGroup = 'Layanan Warga';

    protected static ?string $recordTitleAttribute = 'nama_layanan';

    // Atur urutan di bawah ProfilDesa
    protected static ?int $navigationSort = 2;

    // Nama navigasi dan model tanpa "s"
    protected static ?string $modelLabel = 'Informasi Layanan Desa';

    protected static ?string $pluralModelLabel = 'Informasi Layanan Desa';

    protected static ?string $tenantOwnershipRelationshipName = 'company';

    // public static function getTenantRelationshipName(): string
    // {
    //     return 'layananDesas';
    // }

    public static function getNavigationBadge(): ?string
    {

        if ($tenant = Filament::getTenant()) {
            return LayananDesa::where('company_id', $tenant->id)->count();
        }

        return 0;
    }
    // Kategori layanan
    public static function getKategoriLayanan(): array
    {
        return [
            'Surat' => 'Surat',
            'Kesehatan' => 'Kesehatan',
            'Pendidikan' => 'Pendidikan',
            'Sosial' => 'Sosial',
            'Infrastruktur' => 'Infrastruktur'
        ];
    }

    // Warna kategori
    public static function getKategoriColors(): array
    {
        return [
            'Surat' => 'primary',
            'Kesehatan' => 'success',
            'Pendidikan' => 'warning',
            'Sosial' => 'secondary',
            'Infrastruktur' => 'danger',
        ];
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Layanan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // Kolom id_desa sekarang otomatis diisi dan disembunyikan
                                // Forms\Components\Hidden::make('id_desa')
                                //     ->default(fn() => Filament::getTenant()->profilDesa?->id),

                                Forms\Components\Select::make('kategori')
                                    ->options(self::getKategoriLayanan())
                                    ->required()
                                    ->placeholder('Pilih kategori layanan'),
                            ]),
                        Forms\Components\TextInput::make('nama_layanan')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Masukkan nama layanan')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('deskripsi')
                            ->required()
                            ->placeholder('Masukkan deskripsi layanan')
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('lokasi_layanan')
                                    ->label('Lokasi Layanan')
                                    ->placeholder('Contoh: Puskesmas Desa, Balai Desa, dsb')
                                    ->maxLength(150),
                                Forms\Components\TextInput::make('kontak_layanan')
                                    ->label('Kontak Layanan')
                                    ->placeholder('Contoh: 08123456789 (Pak Budi)')
                                    ->maxLength(100),
                            ]),
                        Forms\Components\Textarea::make('jadwal_pelayanan')
                            ->label('Jadwal Pelayanan')
                            ->placeholder('Contoh: Senin-Jumat: 08.00-15.00, Sabtu: 08.00-12.00')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('biaya')
                            ->label('Biaya Layanan')
                            ->prefix('Rp')
                            ->placeholder('Masukkan biaya (0 untuk gratis)')
                            ->mask(RawJs::make(<<<'JS'
                                function (value) {
                                    let numericValue = value.replace(/\D/g, '');
                                    if (numericValue === '') return '';
                                    return numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                }
                                JS))
                            ->dehydrateStateUsing(fn($state) => preg_replace('/\D/', '', $state))
                            ->formatStateUsing(function ($state) {
                                if (is_numeric($state)) {
                                    if ($state == 0) return '0';
                                    return number_format($state, 0, ',', '.');
                                }
                                return $state;
                            })
                            ->default('0'),
                        Forms\Components\Hidden::make('created_by')->default(fn() => auth()->id()),
                    ]),
                Section::make('Persyaratan Layanan')
                    ->collapsed(false)
                    ->schema([
                        Forms\Components\Repeater::make('persyaratan')
                            ->schema([
                                Forms\Components\TextInput::make('dokumen')
                                    ->required()->label('Dokumen yang Diperlukan')
                                    ->placeholder('Contoh: KTP, KK, dll'),
                                Forms\Components\Textarea::make('keterangan')
                                    ->label('Keterangan')->placeholder('Masukkan keterangan tambahan'),
                            ])
                            ->columnSpanFull()
                            ->itemLabel(fn(array $state): ?string => $state['dokumen'] ?? null)
                            ->reorderable(),
                    ]),
                Section::make('Prosedur Layanan')
                    ->collapsed(false)
                    ->schema([
                        Forms\Components\Repeater::make('prosedur')
                            ->schema([
                                Forms\Components\TextInput::make('langkah')
                                    ->required()->label('Langkah')
                                    ->placeholder('Contoh: Mengisi formulir permohonan'),
                                Forms\Components\Textarea::make('keterangan')
                                    ->label('Keterangan')->placeholder('Masukkan detail tambahan untuk langkah ini'),
                            ])
                            ->columnSpanFull()
                            ->itemLabel(fn(array $state): ?string => $state['langkah'] ?? null)
                            ->reorderable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_layanan')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('kategori')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Surat' => 'primary',
                        'Kesehatan' => 'success',
                        'Pendidikan' => 'warning',
                        'Sosial' => 'secondary',
                        'Infrastruktur' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('lokasi_layanan')
                    ->label('Lokasi')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('biaya')
                    ->formatStateUsing(function ($state) {
                        if ($state == 0) {
                            return 'Gratis';
                        }
                        return 'Rp ' . number_format($state, 0, ',', '.');
                    })
                    ->sortable()
                    ->alignRight(),
                Tables\Columns\TextColumn::make('jadwal_pelayanan')
                    ->label('Jadwal')
                    ->limit(30)
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('kontak_layanan')
                    ->label('Kontak')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Hanya filter kategori tetap
                Tables\Filters\SelectFilter::make('kategori')
                    ->options(self::getKategoriLayanan())
                    ->placeholder('Semua Kategori')
                    ->multiple(),

                // Filter tanggal yang disederhanakan
                Filter::make('created_at')
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
                            ->placeholder('Pilih periode...')
                            ->live()
                            ->afterStateUpdated(function (callable $get, callable $set, ?string $state) {
                                if (!$state || $state === 'custom') {
                                    return;
                                }

                                if ($state === 'today') {
                                    $set('created_from', today());
                                    $set('created_until', today());
                                } elseif ($state === 'yesterday') {
                                    $set('created_from', today()->subDay());
                                    $set('created_until', today()->subDay());
                                } elseif ($state === 'last7days') {
                                    $set('created_from', today()->subDays(6));
                                    $set('created_until', today());
                                } elseif ($state === 'last30days') {
                                    $set('created_from', today()->subDays(29));
                                    $set('created_until', today());
                                } elseif ($state === 'thismonth') {
                                    $set('created_from', today()->startOfMonth());
                                    $set('created_until', today()->endOfMonth());
                                } elseif ($state === 'lastmonth') {
                                    $set('created_from', today()->subMonth()->startOfMonth());
                                    $set('created_until', today()->subMonth()->endOfMonth());
                                } elseif ($state === 'thisyear') {
                                    $set('created_from', today()->startOfYear());
                                    $set('created_until', today()->endOfYear());
                                } elseif ($state === 'lastyear') {
                                    $set('created_from', today()->subYear()->startOfYear());
                                    $set('created_until', today()->subYear()->endOfYear());
                                }
                            }),
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal')
                            ->visible(fn($get) => $get('preset_periode') === 'custom'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                            ->visible(fn($get) => $get('preset_periode') === 'custom'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // Jangan terapkan filter jika tidak ada data yang diisi
                        if (empty($data['created_from']) && empty($data['created_until']) && empty($data['preset_periode'])) {
                            return $query;
                        }

                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        // Tampilkan preset sebagai indikator
                        if (isset($data['preset_periode']) && $data['preset_periode'] !== 'custom' && $data['preset_periode'] !== '') {
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

                        // Tampilkan rentang tanggal kustom
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Dari: ' . Carbon::parse($data['created_from'])->format('d/m/Y');
                        }

                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Sampai: ' . Carbon::parse($data['created_until'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),

                // Ubah filter sampah agar berfungsi dengan baik
                Tables\Filters\TrashedFilter::make()
                    ->label('Status Penghapusan')
                    ->placeholder('Hanya yang aktif')
                    ->trueLabel('Hanya yang dihapus')
                    ->falseLabel('Semua (aktif & dihapus)')
                    ->queries(
                        true: fn(Builder $query) => $query->onlyTrashed(),
                        false: fn(Builder $query) => $query->withTrashed(),
                        blank: fn(Builder $query) => $query->withoutTrashed()
                    )
                    ->indicator('Item Terhapus'),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->label('Pulihkan')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->tooltip('Mengembalikan layanan desa yang telah dihapus')
                    ->successNotificationTitle('Layanan desa berhasil dipulihkan'),
                Tables\Actions\ForceDeleteAction::make()
                    ->label('Hapus Permanen')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->tooltip('Menghapus layanan desa secara permanen')
                    ->successNotificationTitle('Layanan desa berhasil dihapus permanen'),
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
                    ->action(function (LayananDesa $record, array $data) {
                        $format = $data['format'] ?? 'pdf';

                        if ($format === 'excel') {
                            // Ekspor ke Excel menggunakan metode yang ada
                            return self::exportToExcel(collect([$record]));
                        } else {
                            // Gunakan route PDF yang sudah ada
                            return redirect()->route('layanan.export', [
                                'layanan' => $record,
                                'format' => 'pdf'
                            ]);
                        }
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Massal')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->successNotificationTitle('Layanan desa terpilih berhasil dihapus'),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->label('Hapus Permanen Massal')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->successNotificationTitle('Layanan desa terpilih berhasil dihapus permanen'),
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Pulihkan Massal')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->successNotificationTitle('Layanan desa terpilih berhasil dipulihkan'),
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
                            return redirect()->route('layanan.export.selected', [
                                'ids' => $records->pluck('id')->join(','),
                                'format' => $data['format'] ?? 'excel',
                            ]);
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListLayananDesa::route('/'),
            'create' => Pages\CreateLayananDesa::route('/create'),
            'view' => Pages\ViewLayananDesa::route('/{record}'),
            'edit' => Pages\EditLayananDesa::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {

        return LayananDesa::query()->where('company_id', Filament::getTenant()->id);
    }


    public static function getWidgets(): array
    {
        return [
            LayananDesaResource\Widgets\LayananStats::class,

        ];
    }

    // Tambahkan metode baru untuk ekspor Excel
    protected static function exportToExcel($records)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $headers = [
            'Nama Layanan',
            'Kategori',
            'Desa',
            'Deskripsi',
            'Biaya',
            'Jadwal',
            'Lokasi',
            'Kontak',
            'Persyaratan',  // Tambahkan kolom persyaratan
            'Prosedur',     // Tambahkan kolom prosedur
            'Dibuat Oleh',
            'Tanggal Dibuat'
        ];

        foreach (array_values($headers) as $idx => $header) {
            $sheet->setCellValue(chr(65 + $idx) . '1', $header);
            $sheet->getStyle(chr(65 + $idx) . '1')->getFont()->setBold(true);
        }

        // Set data
        $row = 2;
        foreach ($records as $record) {
            // Format persyaratan sebagai string
            $persyaratan = collect($record->persyaratan ?? [])
                ->map(function ($item) {
                    return "- {$item['dokumen']}" . (isset($item['keterangan']) && !empty($item['keterangan']) ? ": {$item['keterangan']}" : "");
                })
                ->join("\n");

            // Format prosedur sebagai string
            $prosedur = collect($record->prosedur ?? [])
                ->map(function ($item, $index) {
                    return ($index + 1) . ". {$item['langkah']}" . (isset($item['keterangan']) && !empty($item['keterangan']) ? ": {$item['keterangan']}" : "");
                })
                ->join("\n");

            // Format biaya
            $biaya = $record->biaya == 0 ? 'Gratis' : 'Rp ' . number_format($record->biaya, 0, ',', '.');

            $data = [
                $record->nama_layanan,
                $record->kategori,
                $record->desa->nama_desa ?? 'N/A',
                strip_tags($record->deskripsi),
                $biaya,
                $record->jadwal_pelayanan,
                $record->lokasi_layanan,
                $record->kontak_layanan,
                $persyaratan ?: '-',
                $prosedur ?: '-',
                $record->creator->name ?? 'Sistem',
                $record->created_at->format('d/m/Y H:i:s'),
            ];

            foreach (array_values($data) as $idx => $value) {
                $sheet->setCellValue(chr(65 + $idx) . $row, $value);

                // Set text wrap untuk kolom deskripsi, persyaratan, dan prosedur
                if ($idx == 3 || $idx == 7 || $idx == 8) {
                    $sheet->getStyle(chr(65 + $idx) . $row)->getAlignment()->setWrapText(true);
                }
            }

            $row++;
        }

        // Atur lebar kolom
        $sheet->getColumnDimension('A')->setWidth(25); // Nama Layanan
        $sheet->getColumnDimension('B')->setWidth(15); // Kategori
        $sheet->getColumnDimension('C')->setWidth(20); // Desa
        $sheet->getColumnDimension('D')->setWidth(40); // Deskripsi
        $sheet->getColumnDimension('E')->setWidth(15); // Biaya
        $sheet->getColumnDimension('F')->setWidth(20); // Jadwal
        $sheet->getColumnDimension('G')->setWidth(20); // Lokasi
        $sheet->getColumnDimension('H')->setWidth(20); // Kontak
        $sheet->getColumnDimension('I')->setWidth(40); // Persyaratan
        $sheet->getColumnDimension('J')->setWidth(40); // Prosedur
        $sheet->getColumnDimension('K')->setWidth(20); // Dibuat Oleh
        $sheet->getColumnDimension('L')->setWidth(20); // Tanggal Dibuat

        // Set tinggi baris
        $sheet->getDefaultRowDimension()->setRowHeight(20);

        // Buat writer Excel dan output
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'layanan-desa-' . now()->format('Y-m-d-His') . '.xlsx';

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response()->streamDownload(
            fn() => print($content),
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }
}
