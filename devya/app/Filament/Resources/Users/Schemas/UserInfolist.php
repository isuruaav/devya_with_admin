<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('User Account')
                ->schema([
                    TextEntry::make('name')->label('Name'),
                    TextEntry::make('email')->label('Email')->copyable(),
                    TextEntry::make('roles.name')->label('Role')->badge()
                        ->formatStateUsing(fn (string $state): string => User::ROLES[$state] ?? $state),
                    TextEntry::make('created_at')->label('Created')->dateTime(),
                ])
                ->columns(2),
        ]);
    }
}
