<?php

namespace App\Filament\Resources\ConsultationBills\Pages;

use App\Filament\Resources\ConsultationBills\ConsultationBillResource;
use Filament\Resources\Pages\ListRecords;

class ListConsultationBills extends ListRecords
{
    protected static string $resource =
        ConsultationBillResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
