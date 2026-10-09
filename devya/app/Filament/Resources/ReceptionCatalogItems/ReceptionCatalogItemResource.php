<?php

namespace App\Filament\Resources\ReceptionCatalogItems;

use App\Filament\Resources\ReceptionCatalogItems\Pages\CreateReceptionCatalogItem;
use App\Filament\Resources\ReceptionCatalogItems\Pages\EditReceptionCatalogItem;
use App\Filament\Resources\ReceptionCatalogItems\Pages\ListReceptionCatalogItems;
use App\Filament\Resources\ReceptionCatalogItems\Schemas\ReceptionCatalogItemForm;
use App\Filament\Resources\ReceptionCatalogItems\Tables\ReceptionCatalogItemsTable;
use App\Models\ReceptionCatalogItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReceptionCatalogItemResource extends Resource
{
    protected static ?string $model = ReceptionCatalogItem::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel =
        'Item & Service Catalog';

    protected static ?string $modelLabel =
        'Catalog Item';

    protected static ?string $pluralModelLabel =
        'Item & Service Catalog';

    protected static string|\UnitEnum|null $navigationGroup =
        'CONSULTATIONS';

    protected static ?int $navigationSort = 6;

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('doctor')) {
            return false;
        }

        return $user->canAccessModule('consultations');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function form(
        Schema $schema
    ): Schema {
        return ReceptionCatalogItemForm::configure($schema);
    }

    public static function table(
        Table $table
    ): Table {
        return ReceptionCatalogItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReceptionCatalogItems::route('/'),
            'create' => CreateReceptionCatalogItem::route('/create'),
            'edit' => EditReceptionCatalogItem::route('/{record}/edit'),
        ];
    }
}
