<?php

namespace App\Filament\Resources\Treatments\Pages;

use App\Filament\Resources\Treatments\TreatmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTreatment extends ViewRecord
{
    protected static string $resource = TreatmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
