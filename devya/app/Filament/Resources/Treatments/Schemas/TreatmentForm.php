<?php

namespace App\Filament\Resources\Treatments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TreatmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->extraAttributes(['class' => 'admin-form treatment-form'])
            ->components([
                Section::make('Treatment / Spa Service Details')
                    ->contained(false)
                    ->schema([
                        TextInput::make('name')
                            ->label('Treatment Name (ප්‍රතිකාරයේ නම)')
                            ->placeholder('e.g. Abhyanga Full Body Massage')
                            ->required(),

                        Select::make('service_type')
                            ->label('Service Category')
                            ->options([
                                'treatment' => 'Ayurvedic Treatment',
                                'salon' => 'Salon / Beauty Service',
                                'spa' => 'Spa',
                            ])
                            ->default('treatment')
                            ->required(),

                        TextInput::make('code')
                            ->label('Code')
                            ->placeholder('TRT-001')
                            ->unique(ignoreRecord: true),

                        TextInput::make('duration_minutes')
                            ->label('Duration (Minutes)')
                            ->numeric()
                            ->default(30)
                            ->suffix('mins'),

                        TextInput::make('local_price')
                            ->label('Local Charge (LKR)')
                            ->numeric()
                            ->prefix('Rs.')
                            ->default(0.00)
                            ->required(),

                        TextInput::make('foreign_price')
                            ->label('Foreign Charge (USD)')
                            ->numeric()
                            ->prefix('$')
                            ->default(0.00)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->inline(false)
                            ->default(true),

                        Textarea::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                    ])->columns(['default' => 1, 'md' => 2]),
            ]);
    }
}
