<?php

namespace App\Filament\Resources\Staff\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalaryHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'salaryHistories';

    protected static ?string $title = 'Salary History';

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('effective_date')
                    ->label('Effective Date')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('basic_salary')
                    ->label('Basic Salary')
                    ->money('LKR')
                    ->sortable(),

                TextColumn::make('allowance')
                    ->label('Allowance')
                    ->money('LKR')
                    ->sortable(),

                TextColumn::make('total_salary')
                    ->label('Total Salary')
                    ->state(function ($record): float {
                        return (float) $record->basic_salary
                            + (float) $record->allowance;
                    })
                    ->money('LKR'),

                TextColumn::make('reason')
                    ->label('Reason')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Initial Salary' => 'success',
                        'Salary Revision' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('changedBy.name')
                    ->label('Changed By')
                    ->default('-'),

                TextColumn::make('created_at')
                    ->label('Recorded At')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

            ])
            ->defaultSort('effective_date', 'desc');
    }
}
