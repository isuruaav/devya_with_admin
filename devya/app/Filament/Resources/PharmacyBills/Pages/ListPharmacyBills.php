<?php

namespace App\Filament\Resources\PharmacyBills\Pages;

use App\Filament\Resources\PharmacyBills\PharmacyBillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPharmacyBills extends ListRecords
{
    protected static string $resource = PharmacyBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
