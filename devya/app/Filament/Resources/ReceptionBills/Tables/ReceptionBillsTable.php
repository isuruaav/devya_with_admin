<?php

namespace App\Filament\Resources\ReceptionBills\Tables;

use App\Models\ReceptionBill;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReceptionBillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bill_number')
                    ->label('Bill No.')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),

                TextColumn::make('patient.full_name')
                    ->label('Customer')
                    ->searchable()
                    ->state(fn (ReceptionBill $record): string => $record->patient?->full_name
                        ?: 'Walk-in sale')
                    ->sortable(),

                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->sortable(),

                TextColumn::make('grand_total')
                    ->label('Total')
                    ->formatStateUsing(fn ($state, ReceptionBill $record): string => $record->currency.' '.number_format((float) $state, 2))
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('balance_due')
                    ->label('Balance Due')
                    ->formatStateUsing(fn ($state, ReceptionBill $record): string => $record->currency.' '.number_format((float) $state, 2))
                    ->color(fn ($state): string => (float) $state > 0 ? 'danger' : 'success')
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?? 'unpaid'))
                    ->color(fn (?string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('bill_date')
                    ->label('Bill Date')
                    ->schema([
                        DatePicker::make('from')
                            ->label('From Date')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->closeOnDateSelection(),
                        DatePicker::make('until')
                            ->label('To Date')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->closeOnDateSelection(),
                    ])
                    ->query(
                        fn (Builder $query, array $data): Builder => self::applyDateRange($query, $data)
                    ),

                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'partial' => 'Partial',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->url(fn (ReceptionBill $record): string => route('reception-bills.print', $record))
                    ->openUrlInNewTab(),

                Action::make('receivePayment')
                    ->label('Receive Payment')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn (ReceptionBill $record): bool => $record->payment_status !== 'paid'
                        && $record->payment_status !== 'cancelled'
                        && (float) $record->balance_due > 0)
                    ->form([
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'cash' => 'Cash',
                                'card' => 'Card',
                                'bank_transfer' => 'Bank Transfer',
                                'online' => 'Online',
                            ])
                            ->default('cash')
                            ->required()
                            ->native(false),

                        TextInput::make('amount_received')
                            ->label('Amount Received')
                            ->numeric()
                            ->required()
                            ->step(0.01)
                            ->minValue(fn (ReceptionBill $record): float => (float) $record->balance_due)
                            ->default(fn (ReceptionBill $record): float => (float) $record->balance_due)
                            ->prefix(fn (ReceptionBill $record): string => $record->currency),

                        TextInput::make('payment_reference')
                            ->label('Payment Reference')
                            ->maxLength(191),
                    ])
                    ->modalHeading(fn (ReceptionBill $record): string => 'Receive payment for '.$record->bill_number)
                    ->action(function (ReceptionBill $record, array $data): void {
                        $record->markAsPaid(
                            $data['payment_method'],
                            (float) $data['amount_received'],
                            $data['payment_reference'] ?? null
                        );

                        Notification::make()
                            ->title('Payment received')
                            ->body('Reception bill '.$record->bill_number.' is paid.')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function applyDateRange(Builder $query, array $data): Builder
    {
        return $query
            ->when(
                $data['from'] ?? null,
                fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '>=', $date)
            )
            ->when(
                $data['until'] ?? null,
                fn (Builder $query, string $date): Builder => $query->whereDate('created_at', '<=', $date)
            );
    }
}
