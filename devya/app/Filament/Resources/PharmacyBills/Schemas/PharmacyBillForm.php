<?php

namespace App\Filament\Resources\PharmacyBills\Schemas;

use App\Models\Medicine;
use App\Models\Patient;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class PharmacyBillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            /*
            |--------------------------------------------------------------------------
            | FULL WIDTH MAIN POS AREA
            |--------------------------------------------------------------------------
            */

            Grid::make(12)
                ->schema([

                    /*
                    |--------------------------------------------------------------------------
                    | LEFT SIDE
                    |--------------------------------------------------------------------------
                    */

                    Group::make([

                        /*
                        |--------------------------------------------------------------------------
                        | PATIENT / CUSTOMER
                        |--------------------------------------------------------------------------
                        */

                        Section::make()
                            ->schema([

                                Select::make('patient_id')
                                    ->label('Patient / Customer')
                                    ->placeholder(
                                        'Walk-in Customer / Search Patient'
                                    )
                                    ->searchable()
                                    ->preload(false)
                                    ->native(false)
                                    ->optionsLimit(20)
                                    ->getSearchResultsUsing(
                                        function (string $search): array {

                                            $search = trim($search);

                                            if ($search === '') {
                                                return [];
                                            }

                                            return Patient::query()
                                                ->where(
                                                    function ($query) use ($search) {

                                                        $query
                                                            ->where(
                                                                'full_name',
                                                                'like',
                                                                "%{$search}%"
                                                            )
                                                            ->orWhere(
                                                                'nic_or_passport',
                                                                'like',
                                                                "%{$search}%"
                                                            )
                                                            ->orWhere(
                                                                'phone_number',
                                                                'like',
                                                                "%{$search}%"
                                                            );
                                                    }
                                                )
                                                ->orderBy('full_name')
                                                ->limit(30)
                                                ->get()
                                                ->mapWithKeys(
                                                    function (
                                                        Patient $patient
                                                    ): array {

                                                        $label =
                                                            $patient->full_name;

                                                        if (
                                                            filled(
                                                                $patient->nic_or_passport
                                                            )
                                                        ) {
                                                            $label .=
                                                                ' | '.
                                                                $patient->nic_or_passport;
                                                        }

                                                        if (
                                                            filled(
                                                                $patient->phone_number
                                                            )
                                                        ) {
                                                            $label .=
                                                                ' | '.
                                                                $patient->phone_number;
                                                        }

                                                        return [
                                                            $patient->id => $label,
                                                        ];
                                                    }
                                                )
                                                ->toArray();
                                        }
                                    )
                                    ->getOptionLabelUsing(
                                        function ($value): ?string {

                                            if (! $value) {
                                                return null;
                                            }

                                            $patient =
                                                Patient::find($value);

                                            if (! $patient) {
                                                return null;
                                            }

                                            $label =
                                                $patient->full_name;

                                            if (
                                                filled(
                                                    $patient->nic_or_passport
                                                )
                                            ) {
                                                $label .=
                                                    ' | '.
                                                    $patient->nic_or_passport;
                                            }

                                            if (
                                                filled(
                                                    $patient->phone_number
                                                )
                                            ) {
                                                $label .=
                                                    ' | '.
                                                    $patient->phone_number;
                                            }

                                            return $label;
                                        }
                                    )
                                    ->live()
                                    ->afterStateUpdated(
                                        function (
                                            $state,
                                            Set $set,
                                            Get $get
                                        ): void {

                                            $currency = 'LKR';

                                            if ($state) {

                                                $patient =
                                                    Patient::find($state);

                                                if ($patient) {

                                                    $isForeign =
                                                        strtolower(
                                                            (string) $patient
                                                                ->patient_type
                                                        ) === 'foreign';

                                                    $currency =
                                                        $isForeign
                                                            ? 'USD'
                                                            : 'LKR';
                                                }
                                            }

                                            $set(
                                                'currency',
                                                $currency
                                            );

                                            self::recalculateExistingItems(
                                                $set,
                                                $get
                                            );
                                        }
                                    )
                                    ->columnSpan([
                                        'default' => 12,
                                        'lg' => 7,
                                    ]),

                                Select::make('currency')
                                    ->label('Currency')
                                    ->options([
                                        'LKR' => 'LKR',
                                        'USD' => 'USD',
                                    ])
                                    ->default('LKR')
                                    ->disabled()
                                    ->dehydrated()
                                    ->native(false)
                                    ->columnSpan([
                                        'default' => 6,
                                        'lg' => 2,
                                    ]),

                                TextInput::make('bill_number')
                                    ->label('Bill Number')
                                    ->default(
                                        fn (): string => 'PHARM-'.
                                            now()->format('YmdHis').
                                            '-'.
                                            random_int(100, 999)
                                    )
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan([
                                        'default' => 6,
                                        'lg' => 3,
                                    ]),

                            ])
                            ->columns(12)
                            ->extraAttributes([
                                'class' => 'w-full max-w-none pharmacy-pos-header',
                            ]),

                        /*
                        |--------------------------------------------------------------------------
                        | ADD MEDICINE
                        |--------------------------------------------------------------------------
                        */

                        Section::make()
                            ->heading('Add Medicine')
                            ->description(
                                'Search medicine by name, code or barcode'
                            )
                            ->icon('heroicon-o-plus-circle')
                            ->schema([

                                Select::make('add_medicine_id')
                                    ->label('Medicine Search')
                                    ->dehydrated(false)
                                    ->placeholder(
                                        'Search Name / Code / Barcode...'
                                    )
                                    ->prefixIcon(
                                        'heroicon-o-magnifying-glass'
                                    )
                                    ->searchable()
                                    ->native(false)
                                    ->live()
                                    ->optionsLimit(30)
                                    ->getSearchResultsUsing(
                                        function (
                                            string $search
                                        ): array {

                                            $search =
                                                trim($search);

                                            if ($search === '') {
                                                return [];
                                            }

                                            return Medicine::query()
                                                ->where(
                                                    'is_active',
                                                    true
                                                )
                                                ->where(
                                                    function (
                                                        $query
                                                    ) use (
                                                        $search
                                                    ) {

                                                        $query
                                                            ->where(
                                                                'name',
                                                                'like',
                                                                "%{$search}%"
                                                            )
                                                            ->orWhere(
                                                                'code',
                                                                'like',
                                                                "%{$search}%"
                                                            )
                                                            ->orWhere(
                                                                'barcode',
                                                                'like',
                                                                "%{$search}%"
                                                            );
                                                    }
                                                )
                                                ->orderBy('name')
                                                ->limit(30)
                                                ->get()
                                                ->mapWithKeys(
                                                    function (
                                                        Medicine $medicine
                                                    ): array {

                                                        $parts = [
                                                            $medicine->name,
                                                        ];

                                                        if (
                                                            filled(
                                                                $medicine->code
                                                            )
                                                        ) {
                                                            $parts[] =
                                                                'Code: '.
                                                                $medicine->code;
                                                        }

                                                        if (
                                                            filled(
                                                                $medicine->barcode
                                                            )
                                                        ) {
                                                            $parts[] =
                                                                'Barcode: '.
                                                                $medicine->barcode;
                                                        }

                                                        return [
                                                            $medicine->id => implode(
                                                                ' • ',
                                                                $parts
                                                            ),
                                                        ];
                                                    }
                                                )
                                                ->toArray();
                                        }
                                    )
                                    ->getOptionLabelUsing(
                                        function (
                                            $value
                                        ): ?string {

                                            if (! $value) {
                                                return null;
                                            }

                                            $medicine =
                                                Medicine::find($value);

                                            if (! $medicine) {
                                                return null;
                                            }

                                            $parts = [
                                                $medicine->name,
                                            ];

                                            if (
                                                filled(
                                                    $medicine->code
                                                )
                                            ) {
                                                $parts[] =
                                                    'Code: '.
                                                    $medicine->code;
                                            }

                                            if (
                                                filled(
                                                    $medicine->barcode
                                                )
                                            ) {
                                                $parts[] =
                                                    'Barcode: '.
                                                    $medicine->barcode;
                                            }

                                            return implode(
                                                ' • ',
                                                $parts
                                            );
                                        }
                                    )
                                    ->afterStateUpdated(
                                        function (
                                            $state,
                                            Set $set,
                                            Get $get
                                        ): void {

                                            if (! $state) {

                                                $set(
                                                    'add_unit_price',
                                                    0
                                                );

                                                $set(
                                                    'add_total',
                                                    0
                                                );

                                                $set(
                                                    'add_available_stock',
                                                    0
                                                );

                                                return;
                                            }

                                            $medicine =
                                                Medicine::find($state);

                                            if (! $medicine) {
                                                return;
                                            }

                                            $price =
                                                self::getMedicinePrice(
                                                    $medicine,
                                                    $get(
                                                        'patient_id'
                                                    )
                                                );

                                            $quantity =
                                                max(
                                                    1,
                                                    (float) (
                                                        $get(
                                                            'add_quantity'
                                                        ) ?? 1
                                                    )
                                                );

                                            $set(
                                                'add_quantity',
                                                $quantity
                                            );

                                            $set(
                                                'add_unit_price',
                                                $price
                                            );

                                            $set(
                                                'add_total',
                                                round(
                                                    $quantity *
                                                        $price,
                                                    2
                                                )
                                            );

                                            $set(
                                                'add_available_stock',
                                                (float) $medicine
                                                    ->stock_quantity
                                            );
                                        }
                                    )
                                    ->columnSpanFull(),

                                /*
                                |--------------------------------------------------------------------------
                                | QUANTITY / PRICE / TOTAL / STOCK
                                |--------------------------------------------------------------------------
                                */

                                Grid::make(12)
                                    ->schema([

                                        TextInput::make(
                                            'add_quantity'
                                        )
                                            ->label('Quantity')
                                            ->dehydrated(false)
                                            ->numeric()
                                            ->default(1)
                                            ->minValue(0.01)
                                            ->step(0.01)
                                            ->live(
                                                debounce: 250
                                            )
                                            ->afterStateUpdated(
                                                function (
                                                    $state,
                                                    Set $set,
                                                    Get $get
                                                ): void {

                                                    $quantity =
                                                        max(
                                                            0,
                                                            (float) (
                                                                $state ?? 0
                                                            )
                                                        );

                                                    $price =
                                                        (float) (
                                                            $get(
                                                                'add_unit_price'
                                                            ) ?? 0
                                                        );

                                                    $set(
                                                        'add_total',
                                                        round(
                                                            $quantity *
                                                                $price,
                                                            2
                                                        )
                                                    );
                                                }
                                            )
                                            ->columnSpan([
                                                'default' => 6,
                                                'md' => 3,
                                            ]),

                                        TextInput::make(
                                            'add_unit_price'
                                        )
                                            ->label('Unit Price')
                                            ->dehydrated(false)
                                            ->numeric()
                                            ->disabled()
                                            ->prefix(
                                                fn (
                                                    Get $get
                                                ): string => (string) (
                                                    $get(
                                                        'currency'
                                                    ) ?? 'LKR'
                                                )
                                            )
                                            ->columnSpan([
                                                'default' => 6,
                                                'md' => 3,
                                            ]),

                                        TextInput::make(
                                            'add_total'
                                        )
                                            ->label('Total')
                                            ->dehydrated(false)
                                            ->numeric()
                                            ->disabled()
                                            ->prefix(
                                                fn (
                                                    Get $get
                                                ): string => (string) (
                                                    $get(
                                                        'currency'
                                                    ) ?? 'LKR'
                                                )
                                            )
                                            ->columnSpan([
                                                'default' => 6,
                                                'md' => 3,
                                            ]),

                                        TextInput::make(
                                            'add_available_stock'
                                        )
                                            ->label('Available Stock')
                                            ->dehydrated(false)
                                            ->numeric()
                                            ->disabled()
                                            ->default(0)
                                            ->extraAttributes([
                                                'class' => 'font-bold',
                                            ])
                                            ->columnSpan([
                                                'default' => 6,
                                                'md' => 3,
                                            ]),

                                    ])
                                    ->columns(12)
                                    ->columnSpanFull(),

                            ])
                            ->footerActions([

                                Action::make('addMedicine')
                                    ->label('Add Medicine')
                                    ->icon('heroicon-o-plus')
                                    ->color('success')
                                    ->size('lg')
                                    ->extraAttributes([
                                        'class' => 'w-full justify-center',
                                    ])
                                    ->action(
                                        function (
                                            Get $get,
                                            Set $set
                                        ): void {

                                            $medicineId =
                                                $get(
                                                    'add_medicine_id'
                                                );

                                            $quantity =
                                                (float) (
                                                    $get(
                                                        'add_quantity'
                                                    ) ?? 0
                                                );

                                            if (! $medicineId) {

                                                Notification::make()
                                                    ->title(
                                                        'Select a medicine'
                                                    )
                                                    ->body(
                                                        'Please select a medicine before adding it.'
                                                    )
                                                    ->warning()
                                                    ->send();

                                                return;
                                            }

                                            if ($quantity <= 0) {

                                                Notification::make()
                                                    ->title(
                                                        'Invalid Quantity'
                                                    )
                                                    ->body(
                                                        'Quantity must be greater than zero.'
                                                    )
                                                    ->warning()
                                                    ->send();

                                                return;
                                            }

                                            $medicine =
                                                Medicine::find(
                                                    $medicineId
                                                );

                                            if (! $medicine) {

                                                Notification::make()
                                                    ->title(
                                                        'Medicine Not Found'
                                                    )
                                                    ->body(
                                                        'The selected medicine could not be found.'
                                                    )
                                                    ->danger()
                                                    ->send();

                                                return;
                                            }

                                            $availableStock =
                                                (float) $medicine
                                                    ->stock_quantity;

                                            $items =
                                                $get(
                                                    'items'
                                                ) ?? [];

                                            if (
                                                ! is_array(
                                                    $items
                                                )
                                            ) {
                                                $items = [];
                                            }

                                            $existingIndex =
                                                null;

                                            $existingQuantity =
                                                0;

                                            foreach (
                                                $items as $index => $item
                                            ) {

                                                if (
                                                    (string) (
                                                        $item[
                                                            'medicine_id'
                                                        ] ?? ''
                                                    ) ===
                                                    (string) $medicineId
                                                ) {

                                                    $existingIndex =
                                                        $index;

                                                    $existingQuantity =
                                                        (float) (
                                                            $item[
                                                                'quantity'
                                                            ] ?? 0
                                                        );

                                                    break;
                                                }
                                            }

                                            $newQuantity =
                                                $existingQuantity +
                                                $quantity;

                                            if (
                                                $newQuantity >
                                                $availableStock
                                            ) {

                                                Notification::make()
                                                    ->title(
                                                        'Insufficient Stock'
                                                    )
                                                    ->body(
                                                        'Available stock: '.
                                                            number_format(
                                                                $availableStock,
                                                                2
                                                            ).
                                                            ' | Requested: '.
                                                            number_format(
                                                                $newQuantity,
                                                                2
                                                            )
                                                    )
                                                    ->danger()
                                                    ->send();

                                                return;
                                            }

                                            $unitPrice =
                                                self::getMedicinePrice(
                                                    $medicine,
                                                    $get(
                                                        'patient_id'
                                                    )
                                                );

                                            if (
                                                $existingIndex !==
                                                null
                                            ) {

                                                $items[
                                                    $existingIndex
                                                ][
                                                    'medicine_name'
                                                ] =
                                                    $medicine->name;

                                                $items[
                                                    $existingIndex
                                                ][
                                                    'quantity'
                                                ] =
                                                    $newQuantity;

                                                $items[
                                                    $existingIndex
                                                ][
                                                    'unit_price'
                                                ] =
                                                    $unitPrice;

                                                $items[
                                                    $existingIndex
                                                ][
                                                    'total'
                                                ] =
                                                    round(
                                                        $newQuantity *
                                                            $unitPrice,
                                                        2
                                                    );

                                                $items[
                                                    $existingIndex
                                                ][
                                                    'stock'
                                                ] =
                                                    $availableStock;

                                            } else {

                                                $items[] = [

                                                    'medicine_id' => $medicine->id,

                                                    'medicine_name' => $medicine->name,

                                                    'quantity' => $quantity,

                                                    'unit_price' => $unitPrice,

                                                    'total' => round(
                                                        $quantity *
                                                            $unitPrice,
                                                        2
                                                    ),

                                                    'stock' => $availableStock,

                                                    'issued_quantity' => 0,
                                                ];
                                            }

                                            $set(
                                                'items',
                                                array_values(
                                                    $items
                                                )
                                            );

                                            self::updateTotals(
                                                $set,
                                                $get
                                            );

                                            /*
                                            |--------------------------------------------------------------------------
                                            | RESET ADD MEDICINE AREA
                                            |--------------------------------------------------------------------------
                                            */

                                            $set(
                                                'add_medicine_id',
                                                null
                                            );

                                            $set(
                                                'add_quantity',
                                                1
                                            );

                                            $set(
                                                'add_unit_price',
                                                0
                                            );

                                            $set(
                                                'add_total',
                                                0
                                            );

                                            $set(
                                                'add_available_stock',
                                                0
                                            );

                                            Notification::make()
                                                ->title(
                                                    'Medicine Added'
                                                )
                                                ->body(
                                                    $medicine->name.
                                                        ' added to the bill.'
                                                )
                                                ->success()
                                                ->send();
                                        }
                                    ),

                            ])
                            ->columns(12)
                            ->extraAttributes([
                                'class' => 'w-full max-w-none pharmacy-pos-add-medicine',
                            ]),

                        /*
                        |--------------------------------------------------------------------------
                        | ADDED MEDICINES - TABLE
                        |--------------------------------------------------------------------------
                        */

                        Section::make()
                            ->heading('Added Medicines')
                            ->description(
                                'Medicines added to this pharmacy bill'
                            )
                            ->icon('heroicon-o-shopping-cart')
                            ->schema([

                                Repeater::make('items')

                                    /*
                                    |--------------------------------------------------------------------------
                                    | IMPORTANT
                                    |--------------------------------------------------------------------------
                                    |
                                    | Items are temporary form data.
                                    | They will be saved manually to
                                    | pharmacy_bill_items in CreatePharmacyBill.
                                    |
                                    */

                                    ->dehydrated(false)

                                    ->label('')

                                    /*
                                    |--------------------------------------------------------------------------
                                    | TABLE COLUMNS
                                    |--------------------------------------------------------------------------
                                    |
                                    | IMPORTANT:
                                    | Filament 4 TableColumn does NOT use ->label().
                                    |
                                    */

                                    ->table([

                                        TableColumn::make(
                                            'Item Name'
                                        ),

                                        TableColumn::make(
                                            'Qty'
                                        ),

                                        TableColumn::make(
                                            'Unit Price'
                                        ),

                                        TableColumn::make(
                                            'Total'
                                        ),

                                    ])

                                    ->schema([

                                        /*
                                        |--------------------------------------------------------------------------
                                        | ITEM NAME
                                        |--------------------------------------------------------------------------
                                        */

                                        TextInput::make(
                                            'medicine_name'
                                        )
                                            ->label('Item Name')
                                            ->disabled()
                                            ->dehydrated()
                                            ->extraInputAttributes([
                                                'style' => 'font-weight:700;',
                                            ]),

                                        /*
                                        |--------------------------------------------------------------------------
                                        | QUANTITY
                                        |--------------------------------------------------------------------------
                                        */

                                        TextInput::make(
                                            'quantity'
                                        )
                                            ->label('Qty')
                                            ->numeric()
                                            ->minValue(0.01)
                                            ->step(0.01)
                                            ->live(
                                                debounce: 250
                                            )
                                            ->afterStateUpdated(
                                                function (
                                                    $state,
                                                    Set $set,
                                                    Get $get
                                                ): void {

                                                    $quantity =
                                                        max(
                                                            0,
                                                            (float) (
                                                                $state ?? 0
                                                            )
                                                        );

                                                    $price =
                                                        (float) (
                                                            $get(
                                                                'unit_price'
                                                            ) ?? 0
                                                        );

                                                    $set(
                                                        'total',
                                                        round(
                                                            $quantity *
                                                                $price,
                                                            2
                                                        )
                                                    );

                                                    /*
                                                    |--------------------------------------------------------------------------
                                                    | Recalculate main bill
                                                    |--------------------------------------------------------------------------
                                                    */

                                                    self::updateTotals(
                                                        $set,
                                                        $get
                                                    );
                                                }
                                            ),

                                        /*
                                        |--------------------------------------------------------------------------
                                        | UNIT PRICE
                                        |--------------------------------------------------------------------------
                                        */

                                        TextInput::make(
                                            'unit_price'
                                        )
                                            ->label('Unit Price')
                                            ->numeric()
                                            ->disabled()
                                            ->dehydrated(),

                                        /*
                                        |--------------------------------------------------------------------------
                                        | TOTAL
                                        |--------------------------------------------------------------------------
                                        */

                                        TextInput::make(
                                            'total'
                                        )
                                            ->label('Total')
                                            ->numeric()
                                            ->disabled()
                                            ->dehydrated(),

                                        /*
                                        |--------------------------------------------------------------------------
                                        | HIDDEN FIELDS
                                        |--------------------------------------------------------------------------
                                        */

                                        Hidden::make(
                                            'medicine_id'
                                        ),

                                        Hidden::make(
                                            'stock'
                                        ),

                                        Hidden::make(
                                            'issued_quantity'
                                        )
                                            ->default(0),

                                    ])

                                    /*
                                    |--------------------------------------------------------------------------
                                    | START EMPTY
                                    |--------------------------------------------------------------------------
                                    */

                                    ->defaultItems(0)

                                    /*
                                    |--------------------------------------------------------------------------
                                    | USER CANNOT ADD DIRECTLY HERE
                                    |--------------------------------------------------------------------------
                                    */

                                    ->addable(false)

                                    /*
                                    |--------------------------------------------------------------------------
                                    | DELETE ENABLED
                                    |--------------------------------------------------------------------------
                                    */

                                    ->deletable(true)

                                    /*
                                    |--------------------------------------------------------------------------
                                    | NO REORDER
                                    |--------------------------------------------------------------------------
                                    */

                                    ->reorderable(false)

                                    /*
                                    |--------------------------------------------------------------------------
                                    | DELETE ACTION
                                    |--------------------------------------------------------------------------
                                    */

                                    ->deleteAction(
                                        fn (
                                            Action $action
                                        ) => $action
                                            ->label('Delete')
                                            ->icon('heroicon-o-trash')
                                            ->color('danger')
                                            ->requiresConfirmation()
                                            ->modalHeading(
                                                'Remove Medicine?'
                                            )
                                            ->modalDescription(
                                                'Are you sure you want to remove this medicine from the bill?'
                                            )
                                            ->modalSubmitActionLabel(
                                                'Yes, Remove'
                                            )
                                    )

                                    ->extraAttributes([
                                        'class' => 'w-full pharmacy-added-medicines-table',
                                    ]),

                            ])
                            ->collapsible(false)
                            ->extraAttributes([
                                'class' => 'w-full max-w-none pharmacy-pos-items',
                            ]),

                    ])
                        ->columnSpan([
                            'default' => 12,
                            'lg' => 9,
                        ])
                        ->extraAttributes([
                            'class' => 'w-full max-w-none',
                        ]),

                    /*
                    |--------------------------------------------------------------------------
                    | RIGHT SIDE - BILL SUMMARY
                    |--------------------------------------------------------------------------
                    */

                    Section::make()
                        ->heading('Bill Summary')
                        ->icon('heroicon-o-calculator')
                        ->schema([

                            /*
                            |--------------------------------------------------------------------------
                            | SUMMARY HEADER
                            |--------------------------------------------------------------------------
                            */

                            Placeholder::make(
                                'summary_header'
                            )
                                ->hiddenLabel()
                                ->content(
                                    function (
                                        Get $get
                                    ): HtmlString {

                                        $currency =
                                            $get('currency') === 'USD'
                                                ? 'USD'
                                                : 'LKR';

                                        $itemCount =
                                            count(
                                                array_filter(
                                                    $get('items') ?? [],
                                                    fn ($row) => ! blank(
                                                        $row[
                                                            'medicine_id'
                                                        ] ?? null
                                                    )
                                                )
                                            );

                                        $itemLabel =
                                            $itemCount === 1
                                                ? 'Item'
                                                : 'Items';

                                        return new HtmlString(
                                            <<<HTML
                                            <div style="
                                                display:flex;
                                                align-items:center;
                                                justify-content:space-between;
                                                padding:10px 14px;
                                                background:#f8fafc;
                                                border:1px solid #e5e7eb;
                                                border-radius:10px;
                                            ">

                                                <div style="
                                                    display:flex;
                                                    align-items:center;
                                                    gap:8px;
                                                ">

                                                    <span style="
                                                        display:flex;
                                                        align-items:center;
                                                        justify-content:center;
                                                        width:28px;
                                                        height:28px;
                                                        border-radius:8px;
                                                        background:#eef2ff;
                                                        color:#4f46e5;
                                                        font-size:13px;
                                                        font-weight:800;
                                                    ">
                                                        {$itemCount}
                                                    </span>

                                                    <span style="
                                                        font-size:12.5px;
                                                        font-weight:700;
                                                        color:#475569;
                                                    ">
                                                        {$itemLabel} in bill
                                                    </span>

                                                </div>

                                                <span style="
                                                    display:inline-flex;
                                                    align-items:center;
                                                    padding:4px 10px;
                                                    border-radius:999px;
                                                    background:#111827;
                                                    color:#ffffff;
                                                    font-size:11px;
                                                    font-weight:800;
                                                    letter-spacing:0.03em;
                                                ">
                                                    {$currency}
                                                </span>

                                            </div>
                                            HTML
                                        );
                                    }
                                )
                                ->columnSpanFull(),

                            /*
                            |--------------------------------------------------------------------------
                            | SUBTOTAL
                            |--------------------------------------------------------------------------
                            */

                            Placeholder::make(
                                'summary_subtotal'
                            )
                                ->hiddenLabel()
                                ->content(
                                    function (
                                        Get $get
                                    ): HtmlString {

                                        $currency =
                                            $get('currency') === 'USD'
                                                ? '$'
                                                : 'Rs.';

                                        $subtotal =
                                            number_format(
                                                (float) (
                                                    $get(
                                                        'subtotal'
                                                    ) ?? 0
                                                ),
                                                2
                                            );

                                        return new HtmlString(
                                            <<<HTML
                                            <div style="
                                                display:flex;
                                                align-items:center;
                                                justify-content:space-between;
                                                padding:9px 4px;
                                            ">

                                                <span style="
                                                    font-size:12.5px;
                                                    color:#64748b;
                                                    font-weight:600;
                                                ">
                                                    Subtotal
                                                </span>

                                                <span style="
                                                    font-size:14px;
                                                    font-weight:800;
                                                    color:#1f2937;
                                                ">
                                                    {$currency} {$subtotal}
                                                </span>

                                            </div>
                                            HTML
                                        );
                                    }
                                )
                                ->columnSpanFull(),

                            /*
                            |--------------------------------------------------------------------------
                            | DISCOUNT
                            |--------------------------------------------------------------------------
                            */

                            TextInput::make('discount')
                                ->label('Discount')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->step(0.01)
                                ->live(
                                    debounce: 250
                                )
                                ->prefix(
                                    fn (
                                        Get $get
                                    ): string => (string) (
                                        $get(
                                            'currency'
                                        ) ?? 'LKR'
                                    )
                                )
                                ->afterStateUpdated(
                                    function (
                                        Set $set,
                                        Get $get
                                    ): void {

                                        self::updateTotals(
                                            $set,
                                            $get
                                        );
                                    }
                                )
                                ->columnSpanFull(),

                            /*
                            |--------------------------------------------------------------------------
                            | DIVIDER
                            |--------------------------------------------------------------------------
                            */

                            Placeholder::make(
                                'summary_divider'
                            )
                                ->hiddenLabel()
                                ->content(
                                    new HtmlString(
                                        '<div style="border-top:1px dashed #d1d5db;margin:2px 0 4px;"></div>'
                                    )
                                )
                                ->columnSpanFull(),

                            /*
                            |--------------------------------------------------------------------------
                            | GRAND TOTAL
                            |--------------------------------------------------------------------------
                            */

                            Placeholder::make(
                                'summary_grand_total'
                            )
                                ->hiddenLabel()
                                ->content(
                                    function (
                                        Get $get
                                    ): HtmlString {

                                        $currency =
                                            $get('currency') === 'USD'
                                                ? '$'
                                                : 'Rs.';

                                        $grandTotal =
                                            number_format(
                                                (float) (
                                                    $get(
                                                        'grand_total'
                                                    ) ?? 0
                                                ),
                                                2
                                            );

                                        return new HtmlString(
                                            <<<HTML
                                            <div style="
                                                display:flex;
                                                align-items:center;
                                                justify-content:space-between;
                                                padding:14px 16px;
                                                background:linear-gradient(
                                                    135deg,
                                                    #4f46e5,
                                                    #4338ca
                                                );
                                                border-radius:12px;
                                                box-shadow:
                                                    0 4px 12px
                                                    rgba(
                                                        79,
                                                        70,
                                                        229,
                                                        0.25
                                                    );
                                            ">

                                                <span style="
                                                    font-size:12.5px;
                                                    font-weight:700;
                                                    color:#e0e7ff;
                                                    text-transform:uppercase;
                                                    letter-spacing:0.04em;
                                                ">
                                                    Grand Total
                                                </span>

                                                <span style="
                                                    font-size:22px;
                                                    font-weight:800;
                                                    color:#ffffff;
                                                ">
                                                    {$currency} {$grandTotal}
                                                </span>

                                            </div>
                                            HTML
                                        );
                                    }
                                )
                                ->columnSpanFull(),

                            /*
                            |--------------------------------------------------------------------------
                            | BALANCE DUE
                            |--------------------------------------------------------------------------
                            */

                            Placeholder::make(
                                'summary_balance_due'
                            )
                                ->hiddenLabel()
                                ->content(
                                    function (
                                        Get $get
                                    ): HtmlString {

                                        $currency =
                                            $get('currency') === 'USD'
                                                ? '$'
                                                : 'Rs.';

                                        $balance =
                                            (float) (
                                                $get(
                                                    'balance_due'
                                                ) ?? 0
                                            );

                                        $balanceFormatted =
                                            number_format(
                                                $balance,
                                                2
                                            );

                                        $isPaid =
                                            $balance <= 0;

                                        $bg =
                                            $isPaid
                                                ? '#f0fdf4'
                                                : '#fff7ed';

                                        $border =
                                            $isPaid
                                                ? '#bbf7d0'
                                                : '#fed7aa';

                                        $label =
                                            $isPaid
                                                ? '#166534'
                                                : '#9a3412';

                                        $value =
                                            $isPaid
                                                ? '#15803d'
                                                : '#c2410c';

                                        return new HtmlString(
                                            <<<HTML
                                            <div style="
                                                display:flex;
                                                align-items:center;
                                                justify-content:space-between;
                                                padding:12px 14px;
                                                background:{$bg};
                                                border:1px solid {$border};
                                                border-radius:10px;
                                                margin-top:6px;
                                            ">

                                                <span style="
                                                    font-size:11px;
                                                    font-weight:800;
                                                    color:{$label};
                                                    text-transform:uppercase;
                                                    letter-spacing:0.03em;
                                                ">
                                                    Balance Due
                                                </span>

                                                <span style="
                                                    font-size:16px;
                                                    font-weight:800;
                                                    color:{$value};
                                                ">
                                                    {$currency} {$balanceFormatted}
                                                </span>

                                            </div>
                                            HTML
                                        );
                                    }
                                )
                                ->columnSpanFull(),

                            /*
                            |--------------------------------------------------------------------------
                            | PAYMENT STATUS
                            |--------------------------------------------------------------------------
                            */

                            Placeholder::make(
                                'summary_payment_status'
                            )
                                ->hiddenLabel()
                                ->content(
                                    function (
                                        Get $get
                                    ): HtmlString {

                                        $status =
                                            $get(
                                                'payment_status'
                                            ) ?? 'unpaid';

                                        $isPaid =
                                            $status === 'paid';

                                        $text =
                                            strtoupper(
                                                $status
                                            );

                                        $bg =
                                            $isPaid
                                                ? '#dcfce7'
                                                : '#fee2e2';

                                        $color =
                                            $isPaid
                                                ? '#166534'
                                                : '#991b1b';

                                        $dot =
                                            $isPaid
                                                ? '#16a34a'
                                                : '#dc2626';

                                        return new HtmlString(
                                            <<<HTML
                                            <div style="
                                                display:flex;
                                                justify-content:center;
                                                margin-top:6px;
                                            ">

                                                <span style="
                                                    display:inline-flex;
                                                    align-items:center;
                                                    gap:6px;
                                                    padding:6px 14px;
                                                    border-radius:999px;
                                                    background:{$bg};
                                                    color:{$color};
                                                    font-size:11px;
                                                    font-weight:800;
                                                    letter-spacing:0.04em;
                                                ">

                                                    <span style="
                                                        width:6px;
                                                        height:6px;
                                                        border-radius:50%;
                                                        background:{$dot};
                                                    "></span>

                                                    {$text}

                                                </span>

                                            </div>
                                            HTML
                                        );
                                    }
                                )
                                ->columnSpanFull(),

                        ])
                        ->columnSpan([
                            'default' => 12,
                            'lg' => 3,
                        ])
                        ->extraAttributes([
                            'class' => 'w-full max-w-none pharmacy-pos-summary',
                        ]),

                ])
                ->columns(12)
                ->columnSpanFull()
                ->extraAttributes([
                    'class' => 'w-full max-w-none pharmacy-pos-work-area',
                    'style' => 'width:100%;max-width:none;',
                ]),

            /*
            |--------------------------------------------------------------------------
            | HIDDEN BILL FIELDS
            |--------------------------------------------------------------------------
            */

            Hidden::make('subtotal')
                ->default(0),

            Hidden::make('grand_total')
                ->default(0),

            Hidden::make('amount_received')
                ->default(0),

            Hidden::make('change_amount')
                ->default(0),

            Hidden::make('balance_due')
                ->default(0),

            Hidden::make('payment_status')
                ->default('unpaid'),

            Hidden::make('created_by_user_id')
                ->default(
                    fn (): ?int => auth()->id()
                ),

            Hidden::make('payment_method'),

            Hidden::make('payment_reference'),

            Hidden::make('receipt_number'),

            Hidden::make('paid_by_user_id'),

            Hidden::make('paid_at'),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MEDICINE PRICE
    |--------------------------------------------------------------------------
    */

    protected static function getMedicinePrice(
        Medicine $medicine,
        $patientId
    ): float {

        $isForeign = false;

        if ($patientId) {

            $patient =
                Patient::find($patientId);

            if ($patient) {

                $isForeign =
                    strtolower(
                        (string) $patient->patient_type
                    ) === 'foreign';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FOREIGN PATIENT
        |--------------------------------------------------------------------------
        */

        if ($isForeign) {

            $foreignPrice =
                (float) $medicine->foreign_price;

            if ($foreignPrice > 0) {
                return $foreignPrice;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | LOCAL PRICE / FALLBACK
        |--------------------------------------------------------------------------
        */

        return (float) $medicine->unit_price;
    }

    /*
    |--------------------------------------------------------------------------
    | RECALCULATE EXISTING ITEMS
    |--------------------------------------------------------------------------
    */

    protected static function recalculateExistingItems(
        Set $set,
        Get $get
    ): void {

        $items =
            $get('items') ?? [];

        if (! is_array($items)) {
            $items = [];
        }

        $patientId =
            $get('patient_id');

        foreach (
            $items as $index => $item
        ) {

            $medicineId =
                $item['medicine_id'] ?? null;

            if (! $medicineId) {
                continue;
            }

            $medicine =
                Medicine::find(
                    $medicineId
                );

            if (! $medicine) {
                continue;
            }

            $price =
                self::getMedicinePrice(
                    $medicine,
                    $patientId
                );

            $quantity =
                (float) (
                    $item['quantity'] ?? 0
                );

            $items[$index]['medicine_name'] =
                $medicine->name;

            $items[$index]['unit_price'] =
                $price;

            $items[$index]['total'] =
                round(
                    $quantity * $price,
                    2
                );

            $items[$index]['stock'] =
                (float) $medicine
                    ->stock_quantity;
        }

        $set(
            'items',
            array_values($items)
        );

        self::updateTotals(
            $set,
            $get
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TOTALS
    |--------------------------------------------------------------------------
    */

    protected static function updateTotals(
        Set $set,
        Get $get
    ): void {

        $items =
            $get('items') ?? [];

        if (! is_array($items)) {
            $items = [];
        }

        $subtotal = 0;

        foreach (
            $items as $item
        ) {

            $quantity =
                (float) (
                    $item['quantity'] ?? 0
                );

            $unitPrice =
                (float) (
                    $item['unit_price'] ?? 0
                );

            $subtotal +=
                $quantity * $unitPrice;
        }

        $subtotal =
            round(
                $subtotal,
                2
            );

        /*
        |--------------------------------------------------------------------------
        | DISCOUNT
        |--------------------------------------------------------------------------
        */

        $discount =
            max(
                0,
                (float) (
                    $get('discount') ?? 0
                )
            );

        if (
            $discount >
            $subtotal
        ) {

            $discount =
                $subtotal;

            $set(
                'discount',
                $discount
            );
        }

        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        $grandTotal =
            max(
                0,
                $subtotal - $discount
            );

        $grandTotal =
            round(
                $grandTotal,
                2
            );

        /*
        |--------------------------------------------------------------------------
        | SAVE CALCULATED VALUES TO FORM STATE
        |--------------------------------------------------------------------------
        */

        $set(
            'subtotal',
            $subtotal
        );

        $set(
            'grand_total',
            $grandTotal
        );

        $set(
            'amount_received',
            0
        );

        $set(
            'change_amount',
            0
        );

        $set(
            'balance_due',
            $grandTotal
        );

        $set(
            'payment_status',
            'unpaid'
        );
    }
}
