<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Models\User;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('roles.manage') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return static::canAccess();
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('guard_name', 'web');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Role Permissions')->schema([
                TextInput::make('name')->label('Role')->disabled(),
                CheckboxList::make('permissions')
                    ->relationship('permissions', 'name', modifyQueryUsing: fn (Builder $query): Builder => $query->where('guard_name', 'web'))
                    ->options(function (): array {
                        $labels = config('access.user_permissions');

                        foreach (config('access.modules') as $module => $label) {
                            $labels['access.'.$module] = $label;
                        }

                        return Permission::query()->where('guard_name', 'web')->orderBy('name')->get()
                            ->mapWithKeys(fn (Permission $permission): array => [$permission->id => $labels[$permission->name] ?? $permission->name])
                            ->all();
                    })
                    ->searchable()
                    ->bulkToggleable()
                    ->columns(2)
                    ->disabled(fn (?Role $record): bool => $record?->name === 'super_admin'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Role')
                ->formatStateUsing(fn (string $state): string => User::ROLES[$state] ?? $state)
                ->searchable()->sortable(),
            TextColumn::make('permissions_count')->label('Permissions')->counts('permissions'),
            TextColumn::make('users_count')->label('Users')->counts('users'),
        ])->recordActions([EditAction::make()])->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
