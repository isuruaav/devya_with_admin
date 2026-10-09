<?php

namespace App\Filament\Resources\ReceptionCatalogItems\Tables;

use App\Models\ReceptionBill;
use App\Models\ReceptionCatalogItem;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReceptionCatalogItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query): Builder => $query->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ])
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Item / Service')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('category')
                    ->label('Category')
                    ->formatStateUsing(
                        fn (?string $state): string => ReceptionBill::CATEGORIES[$state] ?? 'Other'
                    )
                    ->badge()
                    ->sortable(),

                TextColumn::make('unit_price_lkr')
                    ->label('Price (LKR)')
                    ->money('LKR')
                    ->sortable(),

                TextColumn::make('unit_price_usd')
                    ->label('Price (USD)')
                    ->money('USD')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Available')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('is_active')
                    ->label('Availability')
                    ->options([
                        '1' => 'Available',
                        '0' => 'Inactive',
                    ]),

                SelectFilter::make('category')
                    ->label('Category')
                    ->options(ReceptionBill::CATEGORIES),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn (ReceptionCatalogItem $record): bool => ! $record->trashed()),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('name')
            ->emptyStateHeading('Catalog is empty')
            ->emptyStateDescription('Add your services and sale items here. They will then be selectable on Reception Bills.');
    }
}
