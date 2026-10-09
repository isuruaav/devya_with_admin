<?php

namespace App\Filament\Resources\PharmacyBills\Pages;

use App\Filament\Resources\PharmacyBills\PharmacyBillResource;
use App\Models\Medicine;
use App\Models\PharmacyBill;
use App\Models\PharmacyBillItem;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreatePharmacyBill extends CreateRecord
{
    protected static string $resource = PharmacyBillResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $items = $this->form->getRawState()['items'] ?? [];

        unset($data['items']);

        return DB::transaction(function () use ($data, $items): PharmacyBill {
            $record = PharmacyBill::create($data);

            if (is_array($items)) {
                foreach ($items as $item) {
                    $medicineId = (int) ($item['medicine_id'] ?? 0);
                    $quantity = (float) ($item['quantity'] ?? 0);
                    $unitPrice = (float) ($item['unit_price'] ?? 0);
                    $total = (float) ($item['total'] ?? 0);

                    if ($medicineId <= 0 || $quantity <= 0) {
                        continue;
                    }

                    $medicine = Medicine::find($medicineId);

                    if (! $medicine) {
                        throw new \RuntimeException('Medicine not found.');
                    }

                    if ((float) $medicine->stock_quantity < $quantity) {
                        throw new \RuntimeException(
                            'Insufficient stock for medicine: '
                            .$medicine->name
                            .'. Available: '
                            .$medicine->stock_quantity
                            .', Required: '
                            .$quantity
                        );
                    }

                    PharmacyBillItem::create([
                        'pharmacy_bill_id' => $record->getKey(),
                        'medicine_id' => $medicine->getKey(),
                        'medicine_name' => $medicine->name,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total' => $total,
                        'issued_quantity' => 0,
                        'issued_at' => null,
                        'issued_by_user_id' => null,
                    ]);
                }
            }

            return $record;
        });
    }

    protected function afterCreate(): void
    {
        $record = $this->getRecord();

        if (! $record instanceof PharmacyBill) {
            return;
        }

        Notification::make()
            ->title('Pharmacy Bill Created')
            ->body(
                'Bill '
                .$record->bill_number
                .' created successfully. Stock has NOT been deducted yet.'
            )
            ->success()
            ->send();
    }
}
