<?php

namespace App\Filament\Resources\Medicines\Pages;

use App\Filament\Resources\Medicines\MedicineResource;
use App\Models\Medicine;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMedicine extends CreateRecord
{
    protected static string $resource = MedicineResource::class;

    protected function afterCreate(): void
    {
        $medicine = $this->getRecord();

        if (! $medicine instanceof Medicine) {
            return;
        }

        Notification::make()
            ->success()
            ->title('Medicine added')
            ->body(
                "{$medicine->name} was added with code {$medicine->code}."
            )
            ->send();
    }
}
