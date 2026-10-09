<?php

namespace App\Filament\Resources\ReceptionCatalogItems\Pages;

use App\Filament\Resources\ReceptionCatalogItems\ReceptionCatalogItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReceptionCatalogItems extends ListRecords
{
    protected static string $resource = ReceptionCatalogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
