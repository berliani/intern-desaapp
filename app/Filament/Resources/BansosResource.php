<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BansosResource\Pages;
use App\Filament\Resources\BansosResource\Widgets;
use App\Models\Bansos;
use App\Models\JenisBansos;
use App\Models\Penduduk;
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
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Collection;
use Filament\Facades\Filament;

class BansosResource extends Resource
{
    protected static ?string $model = Bansos::class;
    protected static ?string $tenantOwnershipRelationshipName = 'company';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Bantuan Sosial';

    protected static ?int $navigationSort = 2;

    public static function getTenantRelationshipName(): string
    {
        return 'bansos';
    }
    public static function getNavigationLabel(): string
    {
        return 'Data Bantuan Sosial';
    }

    public static function getPluralLabel(): string
    {
        return 'Data Bantuan Sosial';
    }

    public static function getNavigationBadge(): ?string
    {

        if ($tenant = Filament::getTenant()) {
            return Bansos::where('company_id', $tenant->id)->count();
        }

        return 0;
    }
    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::where(function ($query) {
    //         $query->where('sumber_pengajuan', '!=', 'warga')
    //             ->orWhere(function ($query) {
    //                 $query->where('sumber_pengajuan', 'warga')
    //                     ->whereNotIn('status', ['Diajukan']);
    //             });
    //     })->count();
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Gunakan komponen Tabs dengan maxWidth = full
                Forms\Components\Tabs::make('Bantuan Sosial')
                    ->tabs([
                        // Tab 1: Data Penerima Bantuan
                        Forms\Components\Tabs\Tab::make('Data Penerima')
                            ->icon('heroicon-o-user')
                            ->schema([
                                // Section Identitas Penerima
                                Section::make('Identitas Penerima')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('desa_id')
                                                    ->label('Desa')
                                                    ->options(ProfilDesa::orderBy('nama_desa')->pluck('nama_desa', 'id'))
                                                    ->default(function () {
                                                        return ProfilDesa::orderBy('nama_desa')->value('id');
                                                    })
                                                    ->required()
                                                    ->searchable()
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set) {
                                                        $set('penduduk_id', null);
                                                    }),

                                                Forms\Components\Select::make('penduduk_id')
                                                    ->label('Penerima Bantuan')
                                                    ->options(function (Get $get) {
                                                        $desaId = $get('desa_id');
                                                        if (!$desaId) return [];

                                                        return Penduduk::where('desa_id', $desaId)
                                                            ->orderBy('nama')
                                                            ->get()
                                                            ->mapWithKeys(function ($penduduk) {
                                                                return [$penduduk->id => $penduduk->nama . ' - ' . $penduduk->nik];
                                                            });
                                                    })
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->live(),
                                            ]),
                                    ])
                                    ->columnSpanFull(),

                                // Section Riwayat Bantuan
                                Section::make('Riwayat Bantuan yang Diterima')
                                    ->description('Daftar bantuan yang sudah diterima oleh warga ini')
                                    ->schema([
                                        Forms\Components\Placeholder::make('bantuan_sebelumnya')
                                            ->content(function (Get $get) {
                                                $pendudukId = $get('penduduk_id');
                                                if (!$pendudukId) return 'Pilih penerima terlebih dahulu';

                                                $bantuanSebelumnya = Bansos::where('penduduk_id', $pendudukId)
                                                    ->with('jenisBansos')
                                                    ->orderByDesc('tanggal_pengajuan')
                                                    ->get();

                                                if ($bantuanSebelumnya->isEmpty()) {
                                                    return 'Belum ada bantuan yang diterima';
                                                }

                                                $html = "<div class='space-y-3 p-1'>";
                                                foreach ($bantuanSebelumnya as $index => $item) {
                                                    if (!$item->jenisBansos) continue;

                                                    // Buat badge status sesuai warna
                                                    $statusClass = match ($item->status) {
                                                        'Diajukan' => 'bg-gray-100 text-gray-700',
                                                        'Dalam Verifikasi' => 'bg-amber-100 text-amber-700',
                                                        'Diverifikasi' => 'bg-blue-100 text-blue-700',
                                                        'Disetujui' => 'bg-green-100 text-green-700',
                                                        'Ditolak' => 'bg-red-100 text-red-700',
                                                        'Sudah Diterima' => 'bg-indigo-100 text-indigo-700',
                                                        default => 'bg-gray-100 text-gray-700'
                                                    };

                                                    $html .= "<div class='flex items-center justify-between border rounded p-2'>";
                                                    $html .= "<div>";
                                                    $html .= "<div class='font-medium'>" . $item->jenisBansos->nama_bansos . "</div>";
                                                    $html .= "<div class='text-xs text-gray-500'>" . $item->tanggal_pengajuan->format('d M Y') . "</div>";
                                                    $html .= "</div>";
                                                    $html .= "<span class='px-2 py-1 text-xs rounded-full {$statusClass}'>{$item->status}</span>";
                                                    $html .= "</div>";
                                                }
                                                $html .= "</div>";

                                                return new \Illuminate\Support\HtmlString($html);
                                            })
                                            ->columnSpanFull()
                                    ])
                                    ->collapsible()
                                    ->collapsed(false)
                                    ->visible(fn(Get $get) => (bool) $get('penduduk_id'))
                                    ->columnSpanFull(),

                                // Section Pilihan Bantuan
                                Section::make('Pengajuan Bantuan')
                                    ->description('Pilih jenis bantuan dan alasan pengajuan')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('jenis_bansos_id')
                                                    ->label('Jenis Bantuan')
                                                    ->options(function () {
                                                        return JenisBansos::where('is_active', true)
                                                            ->orderBy('kategori')
                                                            ->orderBy('nama_bansos')
                                                            ->get()
                                                            ->mapWithKeys(function ($jenisBansos) {
                                                                return [$jenisBansos->id => $jenisBansos->nama_bansos];
                                                            });
                                                    })
                                                    ->preload()
                                                    ->searchable()
                                                    ->required(),

                                                Forms\Components\DatePicker::make('tanggal_pengajuan')
                                                    ->label('Tanggal Pengajuan')
                                                    ->required()
                                                    ->default(now()),
                                            ]),

                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('prioritas')
                                                    ->label('Prioritas')
                                                    ->options(Bansos::getPrioritasOptions())
                                                    ->default('Sedang')
                                                    ->required(),

                                                Forms\Components\Select::make('sumber_pengajuan')
                                                    ->label('Sumber Pengajuan')
                                                    ->options(Bansos::getSumberPengajuanOptions())
                                                    ->default('admin')
                                                    ->required(),
                                            ]),

                                        Forms\Components\Toggle::make('is_urgent')
                                            ->label('Bantuan Mendesak')
                                            ->helperText('Tandai jika bantuan ini perlu ditangani segera')
                                            ->default(false)
                                            ->onColor('danger')
                                            ->offColor('gray'),

                                        Forms\Components\Textarea::make('alasan_pengajuan')
                                            ->label('Alasan Pengajuan')
                                            ->helperText('Jelaskan alasan warga membutuhkan bantuan ini')
                                            ->rows(3)
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),

                        // Tab 2: Status Bantuan
                        Forms\Components\Tabs\Tab::make('Status Bantuan')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Section::make('Status Bantuan')
                                    ->description('Status bantuan dan informasi penting lainnya')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\Select::make('status')
                                                    ->label('Status Bantuan')
                                                    ->options(Bansos::getStatusOptions())
                                                    ->required()
                                                    ->default('Diajukan')
                                                    ->live(),

                                                Forms\Components\DatePicker::make('tanggal_penerimaan')
                                                    ->label('Tanggal Penerimaan')
                                                    ->helperText('Isi ketika bantuan sudah diterima')
                                                    ->visible(fn(Get $get) => $get('status') === 'Sudah Diterima'),
                                            ]),

                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\DatePicker::make('tenggat_pengambilan')
                                                    ->label('Tenggat Pengambilan')
                                                    ->helperText('Batas waktu pengambilan bantuan')
                                                    ->visible(fn(Get $get) => $get('status') === 'Disetujui')
                                                    ->default(now()->addDays(7)),

                                                Forms\Components\TextInput::make('lokasi_pengambilan')
                                                    ->label('Lokasi Pengambilan')
                                                    ->helperText('Lokasi dimana bantuan dapat diambil')
                                                    ->placeholder('Contoh: Kantor Desa, Balai Pertemuan, dll')
                                                    ->visible(fn(Get $get) => in_array($get('status'), ['Disetujui', 'Diverifikasi']))
                                                    ->maxLength(255),
                                            ]),

                                        Forms\Components\Textarea::make('keterangan')
                                            ->label('Keterangan')
                                            ->helperText('Catatan, informasi tambahan, hasil verifikasi, dll.')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // Tab 3: Dokumen Pendukung
                        Forms\Components\Tabs\Tab::make('Dokumen')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make('Dokumen Pendukung')
                                    ->description('Dokumen untuk proses administrasi bantuan')
                                    ->schema([
                                        Forms\Components\FileUpload::make('dokumen_pendukung')
                                            ->label('Dokumen Pendukung')
                                            ->directory('bansos/dokumen')
                                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                                            ->maxSize(2048)
                                            ->helperText('Maks. 2MB (PDF, Gambar). Contoh: KK, KTP, SKTM, dll.')
                                            ->downloadable()
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Bukti Penerimaan')
                                    ->description('Bukti penerima telah menerima bantuan')
                                    ->schema([
                                        Forms\Components\FileUpload::make('bukti_penerimaan')
                                            ->label('Bukti Penerimaan')
                                            ->directory('bansos/bukti')
                                            ->acceptedFileTypes(['application/pdf', 'image/*'])
                                            ->maxSize(2048)
                                            ->helperText('Maks. 2MB (PDF, Gambar). Foto saat penerima menerima bantuan.')
                                            ->downloadable()
                                            ->columnSpanFull(),
                                    ])
                                    ->visible(fn(Get $get) => $get('status') === 'Sudah Diterima'),

                                Section::make('Foto Rumah')
                                    ->description('Dokumentasi kondisi rumah untuk verifikasi')
                                    ->schema([
                                        Forms\Components\FileUpload::make('foto_rumah')
                                            ->label('Foto Rumah')
                                            ->directory('bansos/rumah')
                                            ->acceptedFileTypes(['image/*'])
                                            ->maxSize(2048)
                                            ->helperText('Maks. 2MB (Gambar). Foto rumah untuk verifikasi kondisi ekonomi.')
                                            ->downloadable()
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ])
                    ->activeTab(1)
                    ->persistTab(true)
                    ->columnSpanFull(),

                // Hidden fields
                Forms\Components\Hidden::make('diubah_oleh')
                    ->default(fn() => auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom untuk informasi penduduk
                Tables\Columns\TextColumn::make('penduduk.nama')
                    ->label('Nama Penerima')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('penduduk.nik')
                    ->label('NIK')
                    ->searchable(),

                // Kolom jenis bantuan
                Tables\Columns\TextColumn::make('jenisBansos.nama_bansos')
                    ->label('Jenis Bantuan')
                    ->searchable()
                    ->sortable(),

                // Kolom kategori
                Tables\Columns\TextColumn::make('jenisBansos.kategori')
                    ->label('Kategori')
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
                    }),

                // Kolom status
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Diajukan' => 'gray',
                        'Dalam Verifikasi' => 'warning',
                        'Diverifikasi' => 'info',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        'Sudah Diterima' => 'primary',
                        'Dibatalkan' => 'warning',
                        default => 'gray',
                    }),

                // Tambahkan kolom sumber pengajuan
                Tables\Columns\TextColumn::make('sumber_pengajuan')
                    ->label('Sumber Pengajuan')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'admin' => 'Admin/Petugas Desa',
                        'warga' => 'Pengajuan Warga',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'admin' => 'info',
                        'warga' => 'success',
                        default => 'gray',
                    }),

                // Tanggal pengajuan
                Tables\Columns\TextColumn::make('tanggal_pengajuan')
                    ->label('Tgl Pengajuan')
                    ->date('d/m/Y')
                    ->sortable(),

                // Kolom prioritas
                Tables\Columns\TextColumn::make('prioritas')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Tinggi' => 'danger',
                        'Sedang' => 'warning',
                        'Rendah' => 'success',
                        default => 'gray',
                    }),

                // Kolom alasan pengajuan (ringkas)
                Tables\Columns\TextColumn::make('alasan_pengajuan')
                    ->label('Alasan Pengajuan')
                    ->limit(30)
                    ->tooltip(function ($record) {
                        return $record->alasan_pengajuan;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                // Menampilkan status urgent
                Tables\Columns\IconColumn::make('is_urgent')
                    ->label('Mendesak')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('danger')
                    ->toggleable(),

                // Tambahkan kolom lokasi pengambilan
                Tables\Columns\TextColumn::make('lokasi_pengambilan')
                    ->label('Lokasi Pengambilan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // TAMBAHAN: Menampilkan terakhir diubah oleh siapa
                Tables\Columns\TextColumn::make('editor.name')
                    ->label('Diubah Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter desa
                Tables\Filters\SelectFilter::make('desa_id')
                    ->label('Desa')
                    ->options(function () {
                        return \App\Models\ProfilDesa::pluck('nama_desa', 'id')->toArray();
                    }),

                // Filter jenis bantuan
                Tables\Filters\SelectFilter::make('jenis_bansos_id')
                    ->label('Jenis Bantuan')
                    ->options(function () {
                        return \App\Models\JenisBansos::pluck('nama_bansos', 'id')->toArray();
                    }),

                // Filter status
                Tables\Filters\SelectFilter::make('status')
                    ->options(\App\Models\Bansos::getStatusOptions()),

                // Filter prioritas
                Tables\Filters\SelectFilter::make('prioritas')
                    ->options(\App\Models\Bansos::getPrioritasOptions()),

                // Filter untuk menampilkan hanya yang urgent
                Tables\Filters\Filter::make('is_urgent')
                    ->label('Mendesak/Urgent')
                    ->query(fn(Builder $query): Builder => $query->where('is_urgent', true))
                    ->toggle(),

                // Filter sumber pengajuan
                Tables\Filters\SelectFilter::make('sumber_pengajuan')
                    ->label('Sumber Pengajuan')
                    ->options(\App\Models\Bansos::getSumberPengajuanOptions()),

                // Gunakan TrashedFilter bawaan Filament
                Tables\Filters\TrashedFilter::make()
                    ->label('Data Terhapus'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                // Satu tombol ekspor dengan pilihan format
                Tables\Actions\Action::make('ekspor')
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
                    ->action(function (Bansos $record, array $data): void {
                        $url = route('export.bansos.single', [
                            'bansos' => $record->id,
                            'format' => $data['format'],
                        ]);
                        redirect()->away($url);
                    }),

                // Tombol tambah bantuan lain
                Tables\Actions\Action::make('tambah_bantuan')
                    ->label('Tambah Bantuan')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->url(function (Bansos $record) {
                        if (!$record || !$record->penduduk_id) {
                            return '#';
                        }
                        return route('filament.admin.resources.bansos.create', [
                            'desa_id' => $record->desa_id,
                            'penduduk_id' => $record->penduduk_id,
                            'tenant' => \Filament\Facades\Filament::getTenant(),
                        ]);
                    }),

                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    // Satu tombol bulk export dengan pilihan format
                    Tables\Actions\BulkAction::make('eksporTerpilih')
                        ->label('Ekspor Terpilih')
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
                        ->action(function (Collection $records, array $data): void {
                            if ($records->isEmpty()) {
                                \Filament\Notifications\Notification::make()
                                    ->warning()
                                    ->title('Tidak ada data yang dipilih')
                                    ->body('Pilih setidaknya satu data untuk diekspor')
                                    ->send();
                                return;
                            }

                            // Gunakan query string dengan format yang benar
                            $url = route('export.bansos.selected', []) . '?' . http_build_query([
                                'ids' => $records->pluck('id')->implode(','),
                                'format' => $data['format'] ?? 'pdf',
                            ]);

                            redirect()->away($url);
                        }),

                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),

                    // Bulk action lainnya tetap ada
                    Tables\Actions\BulkAction::make('updateStatus')
                        ->label('Update Status')
                        ->icon('heroicon-o-check-circle')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options(\App\Models\Bansos::getStatusOptions())
                                ->required(),
                            Forms\Components\Textarea::make('keterangan')
                                ->label('Keterangan')
                                ->placeholder('Masukkan keterangan perubahan status')
                                ->rows(2),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data) {
                            foreach ($records as $record) {
                                // Catat perubahan status
                                $record->addStatusHistory(
                                    $data['status'],
                                    $data['keterangan'] ?? 'Status diperbarui secara massal'
                                );

                                // Update status
                                $record->status = $data['status'];
                                $record->diubah_oleh = auth()->id();

                                // Set timestamp sesuai jenis status
                                if ($data['status'] === 'Sudah Diterima') {
                                    $record->tanggal_penerimaan = now();
                                }

                                $record->save();
                            }
                        }),
                ]),
            ])
            // Pengurutan dan eagerly loading
            ->defaultSort('tanggal_pengajuan', 'desc')
            // Aktifkan grouping
            ->groups([
                Tables\Grouping\Group::make('penduduk.nama')
                    ->label('Penerima Bantuan')
                    ->collapsible()
            ])
            // Modifikasi query
            ->modifyQueryUsing(function (Builder $query) {
                // Tidak menampilkan pengajuan warga yang masih berstatus Diajukan
                return $query->where(function ($query) {
                    $query->where('sumber_pengajuan', '!=', 'warga')
                        ->orWhere(function ($query) {
                            $query->where('sumber_pengajuan', 'warga')
                                ->whereNotIn('status', ['Diajukan']);
                        });
                })->with(['penduduk', 'jenisBansos', 'desa']);
            });
    }

    public static function getWidgets(): array
    {
        return [
            BansosResource\Widgets\BansosStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBansos::route('/'),
            'create' => Pages\CreateBansos::route('/create'),
            'view' => Pages\ViewBansos::route('/{record}'),
            'edit' => Pages\EditBansos::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {

        return Bansos::query()->where('company_id', Filament::getTenant()->id);
    }

}
