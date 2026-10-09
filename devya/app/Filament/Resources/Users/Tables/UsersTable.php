<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->copyable(),
                TextColumn::make('roles.name')
                    ->label('Role')
                    ->formatStateUsing(fn (string $state): string => User::ROLES[$state] ?? $state)
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin' => 'warning',
                        'opd' => 'info',
                        'salon' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Enabled')
                    ->disabled(fn (User $record): bool => ! auth()->user()?->can('users.enable') || ! UserResource::canEdit($record)),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->disabled(fn ($record): bool => $record->is(auth()->user())),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->authorizeIndividualRecords(fn (User $record): bool => UserResource::canDelete($record))
                        ->visible(fn (): bool => auth()->user()?->can('users.delete') ?? false),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
