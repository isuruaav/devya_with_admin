<?php

namespace App\Filament\Resources\Countries\Schemas;

use App\Models\Country;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CountryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),

                TextEntry::make('code')
                    ->placeholder('-'),

                IconEntry::make('is_active')
                    ->boolean(),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),

                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(
                        fn (Country $record): bool => filled($record->getAttribute('deleted_at'))
                    ),
            ]);
    }
}
