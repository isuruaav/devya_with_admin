<?php

namespace App\Filament\Resources\Doctors\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConsultationsRelationManager extends RelationManager
{
    protected static string $relationship = 'consultations';

    protected static ?string $title = 'Consultation History';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('consultation_number')
                    ->label('Consultation')
                    ->searchable(),

                TextColumn::make('consultation_date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

                TextColumn::make('patient.full_name')
                    ->label('Patient'),

                TextColumn::make('doctor_fee')
                    ->label('Doctor Fee')
                    ->money(fn ($record) => $record->currency),

                TextColumn::make('status')
                    ->badge(),
            ])
            ->defaultSort('consultation_date', 'desc')
            ->paginated([5, 10, 25]);
    }
}
