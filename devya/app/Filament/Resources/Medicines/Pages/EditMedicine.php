<?php

namespace App\Filament\Resources\Medicines\Pages;

use App\Filament\Resources\Medicines\MedicineResource;
use App\Models\Medicine;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditMedicine extends EditRecord
{
    protected static string $resource = MedicineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $medicine = $this->getRecord();

        if (! $medicine instanceof Medicine) {
            return;
        }

        Notification::make()
            ->success()
            ->title('Medicine updated')
            ->body(
                "{$medicine->name} was updated successfully."
            )
            ->send();
    }
}
