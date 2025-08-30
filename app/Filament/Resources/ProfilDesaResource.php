<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfilDesaResource\Pages;
use App\Models\ProfilDesa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;

class ProfilDesaResource extends Resource
{
    protected static ?string $model = ProfilDesa::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Desa';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $recordTitleAttribute = 'nama_desa';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return 'Profil Desa';
    }

    public static function getPluralLabel(): string
    {
        return 'Profil Desa';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Profil Desa')
                    ->tabs([
                        Tab::make('Informasi Umum')
                            ->schema([
                                Section::make('Galeri Desa & Logo')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                Forms\Components\FileUpload::make('thumbnails')
                                                    ->label('Foto Galeri Desa')
                                                    ->image()
                                                    ->multiple()
                                                    ->maxFiles(5)
                                                    ->reorderable()
                                                    ->imageResizeTargetWidth('1200')
                                                    ->imageResizeTargetHeight('675')
                                                    ->imageCropAspectRatio('16:9')
                                                    ->directory('uploads/desa')
                                                    ->maxSize(5120) // 5MB
                                                    ->columnSpan(1)
                                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                                                    ->helperText('Upload hingga 5 foto desa dengan rasio 16:9. Foto pertama akan digunakan sebagai thumbnail utama.')
                                                    ->loadingIndicatorPosition('left')
                                                    ->uploadProgressIndicatorPosition('left')
                                                    ->removeUploadedFileButtonPosition('right')
                                                    ->uploadButtonPosition('left')
                                                    ->panelAspectRatio('16:9')
                                                    ->validationMessages([
                                                        'accept' => 'File harus berupa gambar (JPG, PNG, GIF)',
                                                        'max' => 'Ukuran maksimal file adalah 5MB',
                                                        'image' => 'File harus berupa gambar',
                                                        'dimensions' => 'Gambar harus memiliki rasio 16:9',
                                                        'uploaded' => 'Upload gagal - periksa ukuran file dan format gambar'
                                                    ])
                                                    ->openable(),

                                                Forms\Components\FileUpload::make('logo')
                                                    ->label('Logo Desa')
                                                    ->image()
                                                    ->imageCropAspectRatio('1:1')
                                                    ->imageResizeTargetWidth('300')
                                                    ->imageResizeTargetHeight('300')
                                                    ->directory('uploads/desa/logo')
                                                    ->maxSize(2048) // 2MB
                                                    ->columnSpan(1)
                                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif'])
                                                    ->helperText('Upload logo desa dengan format persegi (1:1). Ukuran maksimal 2MB.')
                                                    ->validationMessages([
                                                        'accept' => 'File harus berupa gambar (JPG, PNG, GIF)',
                                                        'max' => 'Ukuran maksimal file adalah 2MB',
                                                        'image' => 'File harus berupa gambar',
                                                        'dimensions' => 'Logo harus memiliki rasio 1:1 (persegi)',
                                                        'uploaded' => 'Upload gagal - periksa ukuran file dan format gambar'
                                                    ])
                                                    ->openable(),
                                            ]),
                                    ]),

                                Section::make('Identitas Desa')
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('nama_desa')
                                            ->label('Nama Desa')
                                            ->required()
                                            ->maxLength(100),
                                        Forms\Components\TextInput::make('kecamatan')
                                            ->required()
                                            ->maxLength(100),
                                        Forms\Components\TextInput::make('kabupaten')
                                            ->required()
                                            ->maxLength(100),
                                        Forms\Components\TextInput::make('provinsi')
                                            ->required()
                                            ->maxLength(100),
                                        Forms\Components\TextInput::make('kode_pos')
                                            ->maxLength(10),
                                    ]),

                                Section::make('Kontak')
                                    ->columns(2)
                                    ->schema([
                                        Forms\Components\Textarea::make('alamat')
                                            ->label('Alamat Kantor Desa')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('telepon')
                                            ->tel()
                                            ->maxLength(15),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->maxLength(100),
                                        Forms\Components\TextInput::make('website')
                                            ->url()
                                            ->maxLength(100),
                                    ]),
                            ]),

                        Tab::make('Visi & Misi')
                            ->schema([
                                Forms\Components\RichEditor::make('visi')
                                    ->label('Visi Desa')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('uploads/desa')
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ])
                                    ->columnSpanFull(),
                                Forms\Components\RichEditor::make('misi')
                                    ->label('Misi Desa')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('uploads/desa')
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Sejarah & Tentang Desa')
                            ->schema([
                                Forms\Components\RichEditor::make('sejarah')
                                    ->label('Sejarah Desa')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('uploads/desa')
                                    ->toolbarButtons([
                                        'attachFiles',
                                        'blockquote',
                                        'bold',
                                        'bulletList',
                                        'h2',
                                        'h3',
                                        'italic',
                                        'link',
                                        'orderedList',
                                        'redo',
                                        'strike',
                                        'underline',
                                        'undo',
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
                // DITAMBAHKAN: Field tersembunyi untuk menyimpan data otomatis
                Forms\Components\Hidden::make('company_id')->default(fn () => Auth::user()->company_id),
                Forms\Components\Hidden::make('created_by')->default(fn () => Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-logo.png')),

                ImageColumn::make('thumbnails')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->circular()
                   ,

                TextColumn::make('nama_desa')
                    ->label('Nama Desa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kecamatan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kabupaten')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('provinsi')
                    ->searchable()
                    ->sortable(),

                // Kolom telepon dengan fungsi pencarian enkripsi
                TextColumn::make('telepon')
                    ->label('Telepon')
                    ->searchable(isIndividual: true, isGlobal: true, query: function (Builder $query, string $search): Builder {
                        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                        $hashedSearch = hash_hmac('sha256', $search, $pepperKey);
                        return $query->where('telepon_search_hash', $hashedSearch);
                    }),
                
                // Kolom email dengan fungsi pencarian enkripsi
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(isIndividual: true, isGlobal: true, query: function (Builder $query, string $search): Builder {
                        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                        $hashedSearch = hash_hmac('sha256', $search, $pepperKey);
                        return $query->where('email_search_hash', $hashedSearch);
                    }),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(30)
                    ->tooltip(fn (ProfilDesa $record): string => $record->alamat ?? '')
                    ->searchable(isIndividual: true, isGlobal: true, query: function (Builder $query, string $search): Builder {
                        $pepperKey = hex2bin(env('IMS_PEPPER_KEY'));
                        $hashedSearch = hash_hmac('sha256', $search, $pepperKey);
                        return $query->where('alamat_search_hash', $hashedSearch);
                    }),

                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make()
                    ->visible(fn ($record) => $record->trashed()),
                Tables\Actions\ForceDeleteAction::make()
                    ->visible(fn ($record) => $record->trashed()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->paginated(false); // Menonaktifkan untuk Showing list
    }

    public static function getRelations(): array
    {
        return [
            // Relation manager dihapus
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfilDesas::route('/'),
            'create' => Pages\CreateProfilDesa::route('/create'),
            'view' => Pages\ViewProfilDesa::route('/{record}'),
            'edit' => Pages\EditProfilDesa::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}