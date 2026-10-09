<?php

namespace App\Filament\Resources\ReceptionBills\Pages;

use App\Filament\Resources\ReceptionBills\ReceptionBillResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReceptionBills extends ListRecords
{
    protected static string $resource = ReceptionBillResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()?->canAccessModule('billing') ?? false;
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
