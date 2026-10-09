<?php

namespace App\Filament\Resources\Doctors\Tables;

use App\Models\Doctor;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DoctorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Doctor Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('specialization')
                    ->label('Specialization')
                    ->searchable(),

                TextColumn::make('phone_number')
                    ->label('Phone'),

                TextColumn::make('local_fee')
                    ->label('Local Fee')
                    ->money('LKR')
                    ->sortable(),

                TextColumn::make('foreign_fee')
                    ->label('Foreign Fee')
                    ->money('USD')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                TextColumn::make('availability_status')
                    ->label('Availability')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => str_replace(
                            '_',
                            ' ',
                            ucfirst($state)
                        )
                    ),

                TextColumn::make('today_consultations_count')
                    ->label('Booked Today')
                    ->state(
                        fn ($record): string => sprintf(
                            '%d / %d',
                            $record->today_consultations_count,
                            $record->max_patients_per_day ?? 0,
                        )
                    )
                    ->badge(),

            ])

            ->filters([
                SelectFilter::make('specialization'),

                SelectFilter::make('availability_status')
                    ->options([
                        'available' => 'Available',

                        'on_leave' => 'On Leave',

                        'temporarily_unavailable' => 'Temporarily Unavailable',
                    ]),

                SelectFilter::make('is_active')
                    ->options([
                        1 => 'Active',
                        0 => 'Inactive',
                    ]),

                SelectFilter::make('room_number')
                    ->label('Room'),

                SelectFilter::make('local_fee')
                    ->label('Local Fee'),

                SelectFilter::make('foreign_fee')
                    ->label('Foreign Fee'),
            ])

            ->actions([

                ViewAction::make(),

                EditAction::make(),

                Action::make('schedule')
                    ->label('Schedule')
                    ->icon('heroicon-o-calendar-days')
                    ->color('info')
                    ->modalHeading(
                        fn (Doctor $record): string => 'Doctor Schedule - '.$record->name
                    )
                    ->modalWidth('md')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(function (Doctor $record) {

                        return view(
                            'filament.doctors.doctor-schedule',
                            [
                                'doctor' => $record,
                            ]
                        );
                    }),

                DeleteAction::make(),

            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
