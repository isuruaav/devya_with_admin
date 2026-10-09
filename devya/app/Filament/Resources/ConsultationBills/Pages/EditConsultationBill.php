<?php

namespace App\Filament\Resources\ConsultationBills\Pages;

use App\Filament\Resources\ConsultationBills\ConsultationBillResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditConsultationBill extends EditRecord
{
    protected static string $resource = ConsultationBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
