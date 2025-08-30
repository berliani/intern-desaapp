<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administrasi Sistem';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'super_admin');
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Nama')
                    ->disabled(fn (string $context): bool => $context === 'edit'),

                TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->alphaDash()
                    ->label('Username')
                    ->disabled(fn (string $context): bool => $context === 'edit'),

                Select::make('contact_method')
                    ->label('Metode Kontak')
                    ->options([
                        'email' => 'Email',
                        'telepon' => 'Nomor Telepon',
                    ])
                    ->required()
                    ->live()
                    ->default('email')
                    ->visibleOn('create'),

                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(fn (string $context, callable $get): bool => $context === 'create' && $get('contact_method') === 'email')
                    ->unique(
                        table: User::class,
                        column: 'email_search_hash',
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule, $get) {
                            $email = $get('email');
                            if (blank($email)) {
                                return $rule;
                            }
                            return $rule->where('email_search_hash', User::hashForSearch($email));
                        }
                    )
                    ->visible(fn (string $context, callable $get): bool => ($context === 'create' && $get('contact_method') === 'email') || ($context === 'edit' && !empty($form->getRecord()?->email)))
                    ->disabled(fn (string $context): bool => $context === 'edit'),

                TextInput::make('telepon')
                    ->label('Nomor Telepon')
                    ->tel()
                    ->required(fn (string $context, callable $get): bool => $context === 'create' && $get('contact_method') === 'telepon')
                    ->unique(
                        table: User::class,
                        column: 'telepon_search_hash',
                        ignoreRecord: true,
                        modifyRuleUsing: function ($rule, $get) {
                            $phone = $get('telepon');
                            if (blank($phone)) {
                                return $rule;
                            }
                            $number = preg_replace('/[^0-9]/', '', $phone);
                            if (str_starts_with($number, '0')) {
                                $number = '62' . substr($number, 1);
                            }
                            return $rule->where('telepon_search_hash', User::hashForSearch($number));
                        }
                    )
                    ->visible(fn (string $context, callable $get): bool => ($context === 'create' && $get('contact_method') === 'telepon') || ($context === 'edit' && !empty($form->getRecord()?->telepon)))
                    ->disabled(fn (string $context): bool => $context === 'edit'),

                TextInput::make('password')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->confirmed()
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->label('Password')
                    ->visibleOn('create'),

                TextInput::make('password_confirmation')
                    ->password()
                    ->required(fn (string $context): bool => $context === 'create')
                    ->dehydrated(false)
                    ->label('Konfirmasi Password')
                    ->visibleOn('create'),

                Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name', fn (Builder $query) => $query->where('name', '!=', 'super_admin'))
                    ->preload()
                    ->required()
                    ->label('Role Pengguna'),

                Select::make('penduduk_id')
                    ->relationship('penduduk', 'nama')
                    ->label('Data Penduduk Terkait')
                    ->helperText('Opsional: Hubungkan user dengan data penduduk yang ada.')
                    ->searchable()
                    ->preload()
                    ->disabled(fn (string $context): bool => $context === 'edit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->label('Nama'),
                TextColumn::make('contact')
                    ->label('Kontak')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $normalizedPhone = preg_replace('/[^0-9]/', '', $search);
                         if (str_starts_with($normalizedPhone, '0')) {
                            $normalizedPhone = '62' . substr($normalizedPhone, 1);
                        }
                        
                        return $query
                            ->where('email_search_hash', User::hashForSearch(strtolower($search)))
                            ->orWhere('telepon_search_hash', User::hashForSearch($normalizedPhone));
                    })
                    ->state(fn (Model $record): string => $record->email ?? $record->telepon ?? 'Tidak ada kontak'),
                TextColumn::make('roles.name')->badge()->label('Role'),
                IconColumn::make('penduduk_id')->boolean()->label('Warga Desa')->state(fn ($record): bool => $record->penduduk_id !== null),
                TextColumn::make('created_at')->dateTime('d/m/Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->relationship('roles', 'name', fn (Builder $query) => $query->where('name', '!=', 'super_admin'))
                    ->label('Filter berdasarkan Role'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

