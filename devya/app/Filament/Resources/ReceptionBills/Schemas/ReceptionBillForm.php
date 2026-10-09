<?php

namespace App\Filament\Resources\ReceptionBills\Schemas;

use App\Models\Patient;
use App\Models\ReceptionBill;
use App\Models\ReceptionCatalogItem;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ReceptionBillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer')
                    ->description('Patient details are optional. Leave this blank for a walk-in sale.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Select::make('patient_id')
                                    ->label('Patient')
                                    ->placeholder('Walk-in sale or search patient')
                                    ->searchable()
                                    ->preload(false)
                                    ->native(false)
                                    ->getSearchResultsUsing(
                                        fn (string $search): array => Patient::query()
                                            ->where('full_name', 'like', '%'.trim($search).'%')
                                            ->orderBy('full_name')
                                            ->limit(30)
                                            ->pluck('full_name', 'id')
                                            ->all()
                                    )
                                    ->getOptionLabelUsing(
                                        fn ($value): ?string => $value
                                            ? Patient::query()->whereKey($value)->value('full_name')
                                            : null
                                    ),

                                Select::make('currency')
                                    ->live()
                                    ->afterStateUpdated(
                                        function (
                                            ?string $state,
                                            Get $get,
                                            Set $set
                                        ): void {
                                            $items = $get('items') ?? [];
                                            $catalogItemIds = collect($items)
                                                ->pluck('catalog_item_id')
                                                ->filter()
                                                ->unique()
                                                ->values();

                                            if ($catalogItemIds->isEmpty()) {
                                                return;
                                            }

                                            $catalogItems = ReceptionCatalogItem::query()
                                                ->whereIn('id', $catalogItemIds)
                                                ->where('is_active', true)
                                                ->get()
                                                ->keyBy('id');

                                            foreach ($items as $key => $item) {
                                                $catalogItem = $catalogItems->get(
                                                    (int) ($item['catalog_item_id'] ?? 0)
                                                );

                                                if ($catalogItem) {
                                                    $items[$key]['unit_price'] = $catalogItem
                                                        ->unitPriceForCurrency($state ?? 'LKR');
                                                }
                                            }

                                            $set('items', $items);
                                        }
                                    )
                                    ->options([
                                        'LKR' => 'LKR',
                                        'USD' => 'USD',
                                    ])
                                    ->default('LKR')
                                    ->required()
                                    ->native(false),

                                TextInput::make('discount')
                                    ->label('Discount')
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->default(0)
                                    ->prefix(fn (Get $get): string => $get('currency') ?: 'LKR')
                                    ->live(debounce: 250),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Services & Items')
                    ->description('Select saved services and sale items to use their catalog prices. Each bill line can be removed separately. These items do not change pharmacy stock.')
                    ->icon('heroicon-o-rectangle-stack')
                    ->schema([
                        Repeater::make('items')
                            ->label('')
                            ->required()
                            ->minItems(1)
                            ->dehydrated(false)
                            ->live()
                            ->schema([
                                Select::make('catalog_item_id')
                                    ->label('Item / Service')
                                    ->placeholder('Search the item catalog')
                                    ->searchable()
                                    ->required()
                                    ->native(false)
                                    ->getSearchResultsUsing(
                                        fn (string $search): array => ReceptionCatalogItem::query()
                                            ->where('is_active', true)
                                            ->where('name', 'like', '%'.trim($search).'%')
                                            ->orderBy('name')
                                            ->limit(30)
                                            ->get()
                                            ->mapWithKeys(
                                                fn (ReceptionCatalogItem $item): array => [
                                                    $item->getKey() => $item->name
                                                        .' — '
                                                        .(ReceptionBill::CATEGORIES[$item->category] ?? 'Other'),
                                                ]
                                            )
                                            ->all()
                                    )
                                    ->getOptionLabelUsing(
                                        fn ($value): ?string => $value
                                            ? ReceptionCatalogItem::query()
                                                ->where('is_active', true)
                                                ->whereKey($value)
                                                ->value('name')
                                            : null
                                    )
                                    ->live()
                                    ->afterStateUpdated(
                                        function (
                                            $state,
                                            Get $get,
                                            Set $set
                                        ): void {
                                            $catalogItem = $state
                                                ? ReceptionCatalogItem::query()
                                                    ->where('is_active', true)
                                                    ->find($state)
                                                : null;

                                            $set('category', $catalogItem?->category);
                                            $set('description', $catalogItem?->name);
                                            $set(
                                                'unit_price',
                                                $catalogItem?->unitPriceForCurrency(
                                                    $get('../../currency') ?: 'LKR'
                                                )
                                            );
                                        }
                                    ),

                                Select::make('category')
                                    ->label('Category')
                                    ->options(ReceptionBill::CATEGORIES)
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('description')
                                    ->label('Catalog Name')
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0.01)
                                    ->maxValue(9999999999.99)
                                    ->step(0.01)
                                    ->default(1)
                                    ->live(debounce: 250),

                                TextInput::make('unit_price')
                                    ->label('Catalog Price')
                                    ->numeric()
                                    ->required()
                                    ->disabled()
                                    ->dehydrated()
                                    ->prefix(fn (Get $get): string => $get('../../currency') ?: 'LKR')
                                    ->live(),
                            ])
                            ->columns([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->reorderable(false)
                            ->itemLabel(
                                fn (array $state): string => filled($state['description'] ?? null)
                                    ? $state['description']
                                    : 'New bill item'
                            )
                            ->deleteAction(
                                fn (Action $action): Action => $action
                                    ->label('Remove item')
                                    ->icon('heroicon-o-trash')
                                    ->color('danger')
                                    ->requiresConfirmation()
                                    ->modalHeading('Remove this bill item?')
                                    ->modalDescription('This item will be removed from the bill.')
                                    ->modalSubmitActionLabel('Remove item')
                            )
                            ->addActionLabel('Add another item'),
                    ])
                    ->columnSpanFull(),

                Section::make('Bill Summary')
                    ->schema([
                        Placeholder::make('total_preview')
                            ->label('Total Due')
                            ->content(function (Get $get): string {
                                $subtotal = collect($get('items') ?? [])
                                    ->sum(fn (array $item): float => round(
                                        (float) ($item['quantity'] ?? 0) * (float) ($item['unit_price'] ?? 0),
                                        2
                                    ));
                                $discount = (float) ($get('discount') ?? 0);

                                return ($get('currency') ?: 'LKR').' '.number_format(max(0, $subtotal - $discount), 2);
                            }),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
