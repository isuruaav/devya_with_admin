<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ConsultationsRelationManager extends RelationManager
{
    protected static string $relationship = 'consultations';

    protected static ?string $title = 'Previous Consultations';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query): Builder => $query->with([
                    'doctor',
                    'treatments.treatment',
                    'medicines.medicine',
                ])
            )
            ->columns([
                TextColumn::make('consultation_number')
                    ->label('Consultation')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('consultation_date')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),

                TextColumn::make('doctor.name')
                    ->label('Doctor')
                    ->placeholder('Not assigned'),

                TextColumn::make('treatments')
                    ->label('Treatments')
                    ->state(
                        fn ($record): string => $record->treatments
                            ->map(
                                fn ($item): string => $item->treatment->name
                                    ?? 'Unknown treatment'
                            )
                            ->filter()
                            ->implode(', ') ?: 'No treatments'
                    ),

                TextColumn::make('medicines')
                    ->label('Medicines')
                    ->state(
                        fn ($record): string => $record->medicines
                            ->map(
                                fn ($item): string => sprintf(
                                    '%s × %s',
                                    $item->medicine->name
                                        ?? 'Unknown medicine',
                                    $item->quantity,
                                )
                            )
                            ->filter()
                            ->implode(', ') ?: 'No medicines'
                    )
                    ->wrap(),

                TextColumn::make('grand_total')
                    ->label('Total')
                    ->state(
                        fn ($record): string => sprintf(
                            '%s %s',
                            $record->currency === 'USD'
                                ? '$'
                                : 'Rs.',
                            number_format(
                                (float) $record->grand_total,
                                2
                            ),
                        )
                    )
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(
                        fn (string $state): string => match ($state) {
                            'completed' => 'success',
                            'pending' => 'warning',
                            'cancelled' => 'danger',
                            default => 'gray',
                        }
                    ),
            ])
            ->defaultSort('consultation_date', 'desc')
            ->paginated([5, 10, 25]);
    }
}
