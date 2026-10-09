<?php

namespace App\Filament\Resources\Consultations\ConsultationResource\Pages;

use App\Filament\Resources\Consultations\ConsultationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewConsultation extends ViewRecord
{
    protected static string $resource = ConsultationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
