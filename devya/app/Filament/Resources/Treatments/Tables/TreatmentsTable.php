<?php

namespace App\Filament\Resources\Treatments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TreatmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Treatment Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('service_type')
                    ->label('Category')
                    ->formatStateUsing(fn (string $state): string => $state === 'salon' ? 'Salon' : 'Treatment')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'salon' ? 'info' : 'success'),

                TextColumn::make('duration_minutes')
                    ->label('Duration')
                    ->suffix(' mins')
                    ->sortable(),

                TextColumn::make('local_price')
                    ->label('Local Price')
                    ->money('LKR')
                    ->sortable(),

                TextColumn::make('foreign_price')
                    ->label('Foreign Price')
                    ->money('USD')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
