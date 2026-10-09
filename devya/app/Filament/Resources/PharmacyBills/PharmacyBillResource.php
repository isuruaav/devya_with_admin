<?php

namespace App\Filament\Resources\PharmacyBills;

use App\Filament\Resources\PharmacyBills\Pages\CreatePharmacyBill;
use App\Filament\Resources\PharmacyBills\Pages\EditPharmacyBill;
use App\Filament\Resources\PharmacyBills\Pages\ListPharmacyBills;
use App\Filament\Resources\PharmacyBills\Pages\ViewPharmacyBill;
use App\Filament\Resources\PharmacyBills\Schemas\PharmacyBillForm;
use App\Filament\Resources\PharmacyBills\Schemas\PharmacyBillInfolist;
use App\Filament\Resources\PharmacyBills\Tables\PharmacyBillsTable;
use App\Models\PharmacyBill;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PharmacyBillResource extends Resource
{
    protected static ?string $model = PharmacyBill::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'PHARMACY';

    protected static ?string $navigationLabel = 'Pharmacy Bills';

    protected static ?string $recordTitleAttribute = 'bill_number';

    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('pharmacy_bills') ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return PharmacyBillForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PharmacyBillInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PharmacyBillsTable::configure($table);
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
            'index' => ListPharmacyBills::route('/'),
            'create' => CreatePharmacyBill::route('/create'),
            'view' => ViewPharmacyBill::route('/{record}'),
            'edit' => EditPharmacyBill::route('/{record}/edit'),
        ];
    }
}
