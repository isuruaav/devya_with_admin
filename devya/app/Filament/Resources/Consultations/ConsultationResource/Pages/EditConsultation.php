<?php

namespace App\Filament\Resources\Consultations\ConsultationResource\Pages;

use App\Filament\Resources\Consultations\ConsultationResource;
use App\Models\Consultation;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditConsultation extends EditRecord
{
    protected static string $resource =
        ConsultationResource::class;

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    protected function mutateFormDataBeforeSave(
        array $data
    ): array {
        $consultation = $this->getRecord();

        if (! $consultation instanceof Consultation) {
            throw new \RuntimeException(
                'Invalid consultation record.'
            );
        }

        ConsultationResource::validateAppointmentData(
            $data,
            $consultation->getKey()
        );

        return $data;
    }

    protected function afterSave(): void
    {
        $consultation = $this->getRecord();

        if (! $consultation instanceof Consultation) {
            throw new \RuntimeException(
                'Invalid consultation record.'
            );
        }

        $consultation->refresh();

        $consultation->load([
            'doctor',
            'patient',
            'treatments',
            'medicines',
        ]);

        try {
            $bill = $consultation->createBill();

            Notification::make()
                ->title('Consultation Bill Updated')
                ->body(
                    'Bill: '
                    .$bill->bill_number
                    .' | Gross Total: '
                    .$bill->currency
                    .' '
                    .number_format(
                        (float) $bill->grand_total,
                        2
                    )
                    .' | Channeling Paid: '
                    .$bill->currency
                    .' '
                    .number_format(
                        (float) $bill->appointment_paid,
                        2
                    )
                    .' | Balance Due: '
                    .$bill->currency
                    .' '
                    .number_format(
                        (float) $bill->balance_due,
                        2
                    )
                )
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Consultation Bill Error')
                ->body(
                    $e->getMessage()
                )
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
