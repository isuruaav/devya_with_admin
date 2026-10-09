<?php

namespace App\Filament\Resources\Doctors\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditsRelationManager extends RelationManager
{
    protected static string $relationship = 'audits';

    protected static ?string $title = 'Audit History';

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('event')->badge(),
            TextColumn::make('user.name')->label('Changed By')->placeholder('System'),
            TextColumn::make('created_at')->dateTime('d M Y, h:i A')->sortable(),
        ])->defaultSort('created_at', 'desc')->paginated([10, 25, 50]);
    }
}
