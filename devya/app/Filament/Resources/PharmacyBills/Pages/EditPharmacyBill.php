<?php

namespace App\Filament\Resources\PharmacyBills\Pages;

use App\Filament\Resources\PharmacyBills\PharmacyBillResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPharmacyBill extends EditRecord
{
    protected static string $resource = PharmacyBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
