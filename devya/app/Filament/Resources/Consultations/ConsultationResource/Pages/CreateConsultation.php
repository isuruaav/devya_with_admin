<?php

namespace App\Filament\Resources\Consultations\ConsultationResource\Pages;

use App\Filament\Resources\Consultations\ConsultationResource;
use App\Models\Consultation;
use App\Models\OnlineAppointment;
use App\Models\Patient;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateConsultation extends CreateRecord
{
    protected static string $resource =

        ConsultationResource::class;

    /*

    |--------------------------------------------------------------------------

    | FULL PAGE WIDTH

    |--------------------------------------------------------------------------

    */

    public function getMaxContentWidth(): Width|string|null
    {

        return Width::Full;

    }

    /*

    |--------------------------------------------------------------------------

    | APPOINTMENT ID

    |--------------------------------------------------------------------------

    */

    protected ?int $appointmentId = null;

    /*

    |--------------------------------------------------------------------------

    | DOCTOR ID

    |--------------------------------------------------------------------------

    */

    protected ?int $doctorId = null;

    /*

    |--------------------------------------------------------------------------

    | CHECK ACCESS

    |--------------------------------------------------------------------------

    */

    public static function canAccess(array $parameters = []): bool
    {

        if (! auth()->check()) {

            return false;

        }

        $appointmentId =

            $parameters['appointment_id']

            ?? $parameters['appointment']

            ?? request()->query('appointment_id')

            ?? request()->query('appointment')

            ?? session(

                'consultation_create_appointment_id'

            );

        if (! $appointmentId) {

            return false;

        }

        $appointment =

            OnlineAppointment::query()

                ->with([

                    'patient',

                    'doctor',

                ])

                ->find($appointmentId);

        if (! $appointment) {

            return false;

        }

        if ($appointment->consultation_id) {

            return false;

        }

        if (

            strtolower(

                (string) $appointment->status

            ) !== 'confirmed'

        ) {

            return false;

        }

        if (! $appointment->doctor_id) {

            return false;

        }

        $user = auth()->user();

        /*

        |--------------------------------------------------------------------------

        | ADMIN / SUPER ADMIN / RECEPTION / OPD

        |--------------------------------------------------------------------------

        */

        if ($user->canAccessModule('consultations')) {
            return true;
        }

        /*

        |--------------------------------------------------------------------------

        | DOCTOR ACCESS

        |--------------------------------------------------------------------------

        */

        if (

            $user->email

            && $appointment->doctor

            && $appointment->doctor->email

            && strtolower(

                $user->email

            ) === strtolower(

                $appointment->doctor->email

            )

        ) {

            return true;

        }

        return false;

    }

    /*

    |--------------------------------------------------------------------------

    | MOUNT

    |--------------------------------------------------------------------------

    */

    public function mount(): void
    {

        parent::mount();

        $appointmentId =

            request()->query('appointment_id')

            ?? request()->query('appointment')

            ?? session(

                'consultation_create_appointment_id'

            );

        if (! $appointmentId) {

            Notification::make()

                ->title(

                    'Appointment Not Found'

                )

                ->body(

                    'Please open the Consultation from a confirmed appointment.'

                )

                ->danger()

                ->send();

            $this->redirect(

                ConsultationResource::getUrl(

                    'index'

                )

            );

            return;

        }

        $appointment =

            OnlineAppointment::query()

                ->with([

                    'patient',

                    'doctor',

                ])

                ->find($appointmentId);

        if (! $appointment) {

            Notification::make()

                ->title(

                    'Appointment Not Found'

                )

                ->body(

                    'The selected appointment could not be found.'

                )

                ->danger()

                ->send();

            $this->redirect(

                ConsultationResource::getUrl(

                    'index'

                )

            );

            return;

        }

        if ($appointment->consultation_id) {

            Notification::make()

                ->title(

                    'Consultation Already Exists'

                )

                ->body(

                    'This appointment is already linked to a consultation.'

                )

                ->warning()

                ->send();

            $this->redirect(

                ConsultationResource::getUrl(

                    'index'

                )

            );

            return;

        }

        if (

            strtolower(

                (string) $appointment->status

            ) !== 'confirmed'

        ) {

            Notification::make()

                ->title(

                    'Appointment Not Confirmed'

                )

                ->body(

                    'Only confirmed appointments can create consultations.'

                )

                ->danger()

                ->send();

            $this->redirect(

                ConsultationResource::getUrl(

                    'index'

                )

            );

            return;

        }

        $this->appointmentId =

            (int) $appointment->id;

        $this->doctorId =

            $appointment->doctor_id

                ? (int) $appointment->doctor_id

                : null;

        session([

            'consultation_create_appointment_id' => $this->appointmentId,

            'consultation_create_doctor_id' => $this->doctorId,

        ]);

        /*

        |--------------------------------------------------------------------------

        | PATIENT TYPE

        |--------------------------------------------------------------------------

        */

        $patientType = $this->resolvePatientType($appointment);

        /*

        |--------------------------------------------------------------------------

        | CURRENCY

        |--------------------------------------------------------------------------

        */

        $currency =

            $appointment->invoice_currency

            ?? (

                strcasecmp(

                    (string) $patientType,

                    'Foreign'

                ) === 0

                    ? 'USD'

                    : 'LKR'

            );

        /*

        |--------------------------------------------------------------------------

        | DOCTOR FEE

        |--------------------------------------------------------------------------

        */

        $doctorFee =

            $appointment->appointment_fee

            ?? 0;

        /*

        |--------------------------------------------------------------------------

        | FORM DEFAULT VALUES

        |--------------------------------------------------------------------------

        */

        $this->form->fill([

            'patient_id' => $appointment->patient_id,

            'doctor_id' => $appointment->doctor_id,

            'consultation_date' => $appointment->appointment_date

                    ?? now(),

            'patient_type' => $patientType,

            'currency' => $currency,

            'doctor_fee' => $doctorFee,

            'treatment_total' => 0,

            /*

             * Medicine total is still stored.

             * It will be used by Pharmacy billing.

             */

            'medicine_total' => 0,

            /*

             * IMPORTANT:

             * Consultation billing does NOT include medicines.

             *

             * Grand Total =

             * Doctor Fee + Treatment Total

             */

            'grand_total' => $doctorFee,

            'status' => 'pending',

            'notes' => null,

            'queue_status' => 'waiting',

        ]);

    }

    /*

    |--------------------------------------------------------------------------

    | VALIDATE DATA BEFORE CREATE

    |--------------------------------------------------------------------------

    */

    protected function mutateFormDataBeforeCreate(

        array $data

    ): array {

        $appointmentId =

            $this->appointmentId

            ?? session(

                'consultation_create_appointment_id'

            );

        if (! $appointmentId) {

            throw new \RuntimeException(
                'Appointment information is missing.'

            );

        }

        $appointment =

            OnlineAppointment::query()

                ->with([

                    'patient',

                    'doctor',

                ])

                ->find($appointmentId);

        if (! $appointment) {

            throw new \RuntimeException(
                'Selected appointment was not found.'

            );

        }

        if ($appointment->consultation_id) {

            throw new \RuntimeException(
                'This appointment already has a consultation.'

            );

        }

        if (

            strtolower(

                (string) $appointment->status

            ) !== 'confirmed'

        ) {

            throw new \RuntimeException(
                'Only confirmed appointments can create consultations.'

            );

        }

        /*

        |--------------------------------------------------------------------------

        | FORCE PATIENT

        |--------------------------------------------------------------------------

        */

        $data['patient_id'] =

            $appointment->patient_id;

        /*

        |--------------------------------------------------------------------------

        | FORCE DOCTOR

        |--------------------------------------------------------------------------

        */

        $data['doctor_id'] =

            $appointment->doctor_id;

        /*

        |--------------------------------------------------------------------------

        | PATIENT TYPE

        |--------------------------------------------------------------------------

        */

        $data['patient_type'] = $this->resolvePatientType($appointment);

        /*

        |--------------------------------------------------------------------------

        | CURRENCY

        |--------------------------------------------------------------------------

        */

        $data['currency'] =

            $appointment->invoice_currency

            ?? (

                strcasecmp(

                    (string) $data['patient_type'],

                    'Foreign'

                ) === 0

                    ? 'USD'

                    : 'LKR'

            );

        /*

        |--------------------------------------------------------------------------

        | CONSULTATION DATE

        |--------------------------------------------------------------------------

        */

        if (

            empty(

                $data['consultation_date']

            )

        ) {

            $data['consultation_date'] =

                $appointment->appointment_date

                ?? now();

        }

        /*

        |--------------------------------------------------------------------------

        | STATUS

        |--------------------------------------------------------------------------

        */

        $data['status'] =

            $data['status']

            ?? 'pending';

        /*

        |--------------------------------------------------------------------------

        | QUEUE STATUS

        |--------------------------------------------------------------------------

        */

        $data['queue_status'] =

            $data['queue_status']

            ?? 'waiting';

        /*

        |--------------------------------------------------------------------------

        | DOCTOR FEE

        |--------------------------------------------------------------------------

        */

        if (

            ! isset($data['doctor_fee'])
            || $data['doctor_fee'] === ''

        ) {

            $data['doctor_fee'] =

                $appointment->appointment_fee

                ?? 0;

        }

        /*

        |--------------------------------------------------------------------------

        | TREATMENT TOTAL

        |--------------------------------------------------------------------------

        */

        $data['treatment_total'] =

            $data['treatment_total']

            ?? 0;

        /*

        |--------------------------------------------------------------------------

        | MEDICINE TOTAL

        |--------------------------------------------------------------------------

        |

        | Keep this value because medicines are needed

        | for Pharmacy billing.

        |

        | BUT medicine_total is NOT included in

        | Consultation grand_total.

        |

        */

        $data['medicine_total'] =

            $data['medicine_total']

            ?? 0;

        /*

        |--------------------------------------------------------------------------

        | GRAND TOTAL

        |--------------------------------------------------------------------------

        |

        | IMPORTANT:

        |

        | Consultation Bill:

        |

        | Doctor Fee

        |      +

        | Treatment Total

        |

        | Medicines are NOT included.

        |

        | Pharmacy will bill medicines separately.

        |

        */

        $data['grand_total'] =

            (float) $data['doctor_fee']

            +

            (float) $data['treatment_total'];

        return $data;

    }

    /*

    |--------------------------------------------------------------------------

    | AFTER CREATE

    |--------------------------------------------------------------------------

    */

    protected function afterCreate(): void
    {

        $appointmentId =

            $this->appointmentId

            ?? session(

                'consultation_create_appointment_id'

            );

        if (! $appointmentId) {

            Notification::make()

                ->title(

                    'Consultation Created'

                )

                ->body(

                    'Consultation was created, but no appointment was linked.'

                )

                ->warning()

                ->send();

            return;

        }

        $appointment =

            OnlineAppointment::query()

                ->whereKey(

                    $appointmentId

                )

                ->whereNull(

                    'consultation_id'

                )

                ->first();

        if (! $appointment) {

            Notification::make()

                ->title(

                    'Appointment Link Error'

                )

                ->body(

                    'The appointment could not be linked to this consultation.'

                )

                ->danger()

                ->persistent()

                ->send();

            return;

        }

        /*

        |--------------------------------------------------------------------------

        | LINK APPOINTMENT TO CONSULTATION

        |--------------------------------------------------------------------------

        */

        $consultation = $this->record;

        if (! $consultation instanceof Consultation) {
            throw new \RuntimeException(
                'Created consultation record could not be resolved.'
            );
        }

        $appointment->update([

            'consultation_id' => $consultation->getKey(),

        ]);

        /*

        |--------------------------------------------------------------------------

        | CREATE CONSULTATION BILL

        |--------------------------------------------------------------------------

        */

        try {

            $consultation->refresh();

            $bill =

                $consultation->createBill();

            /*

            |--------------------------------------------------------------------------

            | SUCCESS NOTIFICATION

            |--------------------------------------------------------------------------

            */

            Notification::make()

                ->title(

                    'Consultation & Bill Created'

                )

                ->body(

                    'Bill: '

                    .$bill->bill_number

                    .' | Gross Total: '

                    .$bill->currency

                    .' '

                    .number_format(

                        (float)

                        $bill->grand_total,

                        2

                    )

                    .' | Channeling Paid: '

                    .$bill->currency

                    .' '

                    .number_format(

                        (float)

                        $bill->appointment_paid,

                        2

                    )

                    .' | Balance Due: '

                    .$bill->currency

                    .' '

                    .number_format(

                        (float)

                        $bill->balance_due,

                        2

                    )

                )

                ->success()

                ->send();

        } catch (\Throwable $e) {

            Notification::make()

                ->title(

                    'Consultation Bill Error'

                )

                ->body(

                    $e->getMessage()

                )

                ->danger()

                ->persistent()

                ->send();

        }

        /*

        |--------------------------------------------------------------------------

        | CLEAR SESSION

        |--------------------------------------------------------------------------

        */

        session()->forget([

            'consultation_create_appointment_id',

            'consultation_create_doctor_id',

        ]);

    }

    protected function resolvePatientType(
        OnlineAppointment $appointment
    ): string {
        $patientType = $appointment->getAttribute('patient_type');

        if (
            is_string($patientType)
            && $patientType !== ''
        ) {
            return $patientType;
        }

        $patient = $appointment->getRelationValue('patient');

        if ($patient instanceof Patient) {
            $relatedPatientType = $patient->getAttribute('patient_type');

            if (
                is_string($relatedPatientType)
                && $relatedPatientType !== ''
            ) {
                return $relatedPatientType;
            }
        }

        return 'Local';
    }
}
