<?php

namespace App\Filament\Resources\ReceptionBills\Pages;

use App\Filament\Resources\ReceptionBills\ReceptionBillResource;
use App\Models\ReceptionBill;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateReceptionBill extends CreateRecord
{
    protected static string $resource = ReceptionBillResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $items = $this->form->getRawState()['items'] ?? [];
        unset($data['items']);

        if (! is_array($items) || $items === []) {
            throw ValidationException::withMessages([
                'data.items' => 'Add at least one bill item.',
            ]);
        }

        return DB::transaction(function () use ($data, $items): ReceptionBill {
            try {
                $amounts = ReceptionBill::calculateCatalogAmounts(
                    $items,
                    $data['currency'] ?? 'LKR',
                    (float) ($data['discount'] ?? 0)
                );
            } catch (\InvalidArgumentException $exception) {
                $field = str_starts_with($exception->getMessage(), 'Discount')
                    ? 'data.discount'
                    : (str_contains($exception->getMessage(), 'currency')
                        ? 'data.currency'
                        : 'data.items');

                throw ValidationException::withMessages([
                    $field => $exception->getMessage(),
                ]);
            }

            $bill = ReceptionBill::query()->create([
                'patient_id' => $data['patient_id'] ?? null,
                'currency' => $data['currency'] ?? 'LKR',
                'subtotal' => $amounts['subtotal'],
                'discount' => $amounts['discount'],
                'grand_total' => $amounts['grand_total'],
                'balance_due' => $amounts['grand_total'],
                'payment_status' => 'unpaid',
                'created_by_user_id' => auth()->id(),
            ]);

            $bill->update([
                'bill_number' => 'REC-BILL-'.str_pad((string) $bill->getKey(), 6, '0', STR_PAD_LEFT),
            ]);

            $bill->items()->createMany($amounts['items']);

            return $bill;
        });
    }

    protected function getRedirectUrl(): string
    {
        return ReceptionBillResource::getUrl('view', [
            'record' => $this->record,
        ]);
    }
}
