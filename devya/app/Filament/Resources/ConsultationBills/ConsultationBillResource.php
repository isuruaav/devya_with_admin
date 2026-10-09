<?php

namespace App\Filament\Resources\ConsultationBills;

use App\Filament\Resources\ConsultationBills\Pages\EditConsultationBill;
use App\Filament\Resources\ConsultationBills\Pages\ListConsultationBills;
use App\Filament\Resources\ConsultationBills\Pages\ViewConsultationBill;
use App\Filament\Resources\ConsultationBills\Schemas\ConsultationBillForm;
use App\Filament\Resources\ConsultationBills\Schemas\ConsultationBillInfolist;
use App\Filament\Resources\ConsultationBills\Tables\ConsultationBillsTable;
use App\Models\ConsultationBill;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ConsultationBillResource extends Resource
{
    protected static ?string $model = ConsultationBill::class;

    protected static string|\BackedEnum|null $navigationIcon =
        Heroicon::OutlinedRectangleStack;

    protected static \UnitEnum|string|null $navigationGroup =
        'CONSULTATIONS';

    protected static ?string $navigationLabel =
        'Consultation Bills';

    protected static ?string $modelLabel =
        'Consultation Bill';

    protected static ?string $pluralModelLabel =
        'Consultation Bills';

    protected static ?int $navigationSort = 2;

    /*
    |--------------------------------------------------------------------------
    | Access
    |--------------------------------------------------------------------------
    */

    public static function canAccess(): bool
    {
        return auth()->check()
            && auth()->user()->canAccessModule('billing');
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    |--------------------------------------------------------------------------
    | Consultation Bills are created automatically from Consultation.
    |
    | Therefore users must NOT be able to create a Consultation Bill
    | manually from the Consultations menu.
    |
    */

    public static function canCreate(): bool
    {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */

    public static function form(
        Schema $schema
    ): Schema {
        return ConsultationBillForm::configure($schema);
    }

    /*
    |--------------------------------------------------------------------------
    | Infolist
    |--------------------------------------------------------------------------
    */

    public static function infolist(
        Schema $schema
    ): Schema {
        return ConsultationBillInfolist::configure($schema);
    }

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    public static function table(
        Table $table
    ): Table {
        return ConsultationBillsTable::configure($table);
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public static function getRelations(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    |
    | There is NO Create page.
    |
    | Consultation Bill is automatically created from Consultation.
    |
    */

    public static function getPages(): array
    {
        return [
            'index' => ListConsultationBills::route('/'),

            'view' => ViewConsultationBill::route('/{record}'),

            'edit' => EditConsultationBill::route('/{record}/edit'),
        ];
    }
}
