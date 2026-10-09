<?php

namespace App\Filament\Resources\ReceptionBills;

use App\Filament\Resources\ReceptionBills\Pages\CreateReceptionBill;
use App\Filament\Resources\ReceptionBills\Pages\ListReceptionBills;
use App\Filament\Resources\ReceptionBills\Pages\ViewReceptionBill;
use App\Filament\Resources\ReceptionBills\Schemas\ReceptionBillForm;
use App\Filament\Resources\ReceptionBills\Schemas\ReceptionBillInfolist;
use App\Filament\Resources\ReceptionBills\Tables\ReceptionBillsTable;
use App\Models\ReceptionBill;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReceptionBillResource extends Resource
{
    protected static ?string $model = ReceptionBill::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $recordTitleAttribute = 'bill_number';

    protected static ?string $navigationLabel = 'Reception Billing';

    protected static ?string $modelLabel = 'Reception Bill';

    protected static ?string $pluralModelLabel = 'Reception Bills';

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return 'CONSULTATIONS';
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('billing') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return ReceptionBillForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReceptionBillInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReceptionBillsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReceptionBills::route('/'),
            'create' => CreateReceptionBill::route('/create'),
            'view' => ViewReceptionBill::route('/{record}'),
        ];
    }
}
