<?php

namespace App\Filament\Resources\Staff\Tables;

use App\Models\Department;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StaffTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn ($query) => $query->with('department')
            )
            ->columns([
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('staff_code')
                    ->label('Staff ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nic_passport')
                    ->label('NIC / Passport')
                    ->searchable(),

                TextColumn::make('designation')
                    ->label('Designation')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('department_name')
                    ->label('Department')
                    ->state(
                        function ($record): string {
                            $name = Department::query()
                                ->whereKey($record->department_id)
                                ->value('name');

                            return is_string($name) && $name !== ''
                                ? $name
                                : '-';
                        }
                    ),

                TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                TextColumn::make('basic_salary')
                    ->label('Basic Salary')
                    ->money('LKR')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(
                        fn (string $state): string => match ($state) {
                            'Active' => 'success',
                            'Inactive' => 'danger',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('joining_date')
                    ->label('Joining Date')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                    ]),

                SelectFilter::make('employment_type')
                    ->label('Employment Type')
                    ->options([
                        'Permanent' => 'Permanent',
                        'Temporary' => 'Temporary',
                        'Contract' => 'Contract',
                        'Part Time' => 'Part Time',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
