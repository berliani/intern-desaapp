<?php

namespace App\Filament\Resources;

use App\Models\Role;
use BezhanSalleh\FilamentShield\Resources\RoleResource as BaseRoleResource;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class RoleResource extends BaseRoleResource
{
    public static ?string $tenantOwnershipRelationshipName = 'company';

    protected static ?string $model = Role::class;

    public static function getFormSchema(string $section = null): array
    {
        return [
            Grid::make()
                ->schema([
                    Section::make()
                        ->schema([
                            TextInput::make('name')
                                ->label(__('filament-shield::filament-shield.field.name'))
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(255),
                            Select::make('guard_name')
                                ->label(__('filament-shield::filament-shield.field.guard_name'))
                                ->options(fn () => collect(config('auth.guards'))->mapWithKeys(fn ($guard, $key) => [$key => $key])->all())
                                ->default(config('auth.defaults.guard'))
                                ->required(),
                            Hidden::make('company_id')
                                ->default(fn () => Filament::getTenant()->id)
                                ->required(),
                        ])
                        ->columns(2),
                ]),
        ];
    }
}

