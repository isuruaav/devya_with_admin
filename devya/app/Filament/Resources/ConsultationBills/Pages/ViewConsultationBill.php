<?php

namespace App\Filament\Resources\ConsultationBills\Pages;

use App\Filament\Resources\ConsultationBills\ConsultationBillResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewConsultationBill extends ViewRecord
{
    protected static string $resource = ConsultationBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
