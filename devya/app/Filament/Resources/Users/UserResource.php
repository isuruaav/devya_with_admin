<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Schemas\UserInfolist;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|UnitEnum|null $navigationGroup = 'Administration';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canAccess(): bool
    {
        return auth()->user()?->canManageUsers() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->can('users.create') ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        if (! $user || ! $record instanceof User) {
            return false;
        }

        return $user->isSuperAdmin()
            || (
                $user->can('users.edit')
                && ! $record->isSuperAdmin()
            );
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        if (! $user || ! $record instanceof User) {
            return false;
        }

        return $user->can('users.delete')
            && ! $record->is($user)
            && (
                ! $record->isSuperAdmin()
                || $user->isSuperAdmin()
            );
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->can('users.delete') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
