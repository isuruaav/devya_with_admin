<?php

namespace App\Filament\Resources\Consultations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ConsultationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Consultation Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('consultation_number')->label('Consultation No.'),
                                TextEntry::make('patient.full_name')->label('Patient'),
                                TextEntry::make('doctor.name')->label('Doctor'),
                                TextEntry::make('consultation_date')->label('Date')->dateTime('d M Y, h:i A'),
                                TextEntry::make('patient_type')->label('Patient Type'),
                                TextEntry::make('status')->badge(),
                            ]),
                    ]),
                Section::make('Billing Summary')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('doctor_fee')->label('Doctor Fee')->money(fn ($record) => $record->currency ?? 'LKR'),
                                TextEntry::make('treatment_total')->label('Treatments')->money(fn ($record) => $record->currency ?? 'LKR'),
                                TextEntry::make('medicine_total')->label('Medicines')->money(fn ($record) => $record->currency ?? 'LKR'),
                                TextEntry::make('grand_total')->label('Grand Total')->weight('bold')->money(fn ($record) => $record->currency ?? 'LKR'),
                            ]),
                    ]),
                Section::make('Notes')
                    ->schema([
                        TextEntry::make('symptoms_and_notes')
                            ->placeholder('No notes recorded.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
