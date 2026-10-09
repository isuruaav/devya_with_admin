<?php

namespace App\Filament\Resources\ReceptionBills\Pages;

use App\Filament\Resources\ReceptionBills\ReceptionBillResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewReceptionBill extends ViewRecord
{
    protected static string $resource = ReceptionBillResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->canAccessModule('billing') ?? false;
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
        $this->record->loadMissing(['patient', 'items']);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('printStandard')
                ->label('Print Bill')
                ->icon('heroicon-o-printer')
                ->url(fn (): string => route('reception-bills.print.standard', $this->record))
                ->openUrlInNewTab(),

            Action::make('print80mm')
                ->label('Print 80mm Bill')
                ->icon('heroicon-o-printer')
                ->url(fn (): string => route('reception-bills.print', $this->record))
                ->openUrlInNewTab(),
        ];
    }
}
