<?php

namespace App\Filament\Resources\PharmacyBills\Pages;

use App\Filament\Resources\PharmacyBills\PharmacyBillResource;
use App\Models\PharmacyBill;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPharmacyBill extends ViewRecord
{
    protected static string $resource = PharmacyBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')
                ->label('Print Bill')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(function (): string {
                    $record = $this->getRecord();

                    if (! $record instanceof PharmacyBill) {
                        throw new \RuntimeException(
                            'Invalid pharmacy bill record.'
                        );
                    }

                    return route('pharmacy-bills.print', [
                        'bill' => $record->getKey(),
                    ]);
                })
                ->openUrlInNewTab(),

            EditAction::make(),
        ];
    }
}
