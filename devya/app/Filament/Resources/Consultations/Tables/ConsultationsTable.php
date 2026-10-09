<?php

namespace App\Filament\Resources\Consultations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ConsultationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Patient Full Name, NIC/Passport සහ Phone Number පෙන්වීම
                TextColumn::make('patient.full_name')
                    ->label('Patient Name')
                    ->description(fn ($record) => $record->patient ? "NIC/Passport: {$record->patient->nic_or_passport} | Mobile: {$record->patient->phone_number}" : null)
                    ->searchable(['patient.full_name', 'patient.nic_or_passport', 'patient.phone_number'])
                    ->sortable(),

                // Doctor Name
                TextColumn::make('doctor.name')
                    ->label('Doctor Name')
                    ->searchable()
                    ->sortable(),

                // Consultation Date
                TextColumn::make('consultation_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                // Grand Total
                TextColumn::make('grand_total')
                    ->label('Total (LKR)')
                    ->money('LKR')
                    ->sortable(),

                // Status Badge
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
