<?php

namespace App\Filament\Resources\ReceptionCatalogItems\Pages;

use App\Filament\Resources\ReceptionCatalogItems\ReceptionCatalogItemResource;
use Filament\Resources\Pages\EditRecord;

class EditReceptionCatalogItem extends EditRecord
{
    protected static string $resource = ReceptionCatalogItemResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
