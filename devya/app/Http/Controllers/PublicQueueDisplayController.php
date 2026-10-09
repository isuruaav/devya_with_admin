<?php

namespace App\Http\Controllers;

use App\Models\OnlineAppointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicQueueDisplayController extends Controller
{
    /**
     * Public Queue Display Page
     */
    public function index(Request $request): View
    {

        $room = trim((string) $request->query('room', ''));

        return view('queue.public-display', [

            'room' => $room,

        ]);

    }

    /**
     * Queue Data

     *

     * This endpoint is called by JavaScript every 3 seconds.
     */
    public function data(Request $request): JsonResponse
    {

        $room = trim((string) $request->query('room', ''));

        /*

        |--------------------------------------------------------------------------

        | TODAY

        |--------------------------------------------------------------------------

        */

        $today = now()->toDateString();

        /*

        |--------------------------------------------------------------------------

        | GET TODAY'S APPOINTMENTS

        |--------------------------------------------------------------------------

        |

        | IMPORTANT:

        |

        | Queue order is TOKEN NUMBER.

        |

        | 1

        | 2

        | 3

        | 4

        | 5

        |

        | Appointment time is NOT used for queue ordering.

        |--------------------------------------------------------------------------

        */

        $appointments = OnlineAppointment::query()

            ->with([

                'patient',

                'doctor',

                'consultation',

            ])

            ->where('status', 'confirmed')

            ->whereDate('appointment_date', $today)

            ->whereNotNull('token_number')

            ->whereHas('doctor', function ($query) use ($room): void {

                $query->where('is_active', true);

                if ($room !== '') {

                    $query->where(

                        'room_number',

                        $room

                    );

                }

            })

            ->orderBy(

                'token_number',

                'asc'

            )

            ->get();

        /*

        |--------------------------------------------------------------------------

        | DOCTOR NAME

        |--------------------------------------------------------------------------

        */

        $doctorName = $appointments

            ->first()

            ?->doctor

            ->name

            ?? 'Doctor';

        $doctorSpecialization = $appointments

            ->first()

            ?->doctor

            ->specialization

            ?? '';

        /*

        |--------------------------------------------------------------------------

        | NOW SERVING

        |--------------------------------------------------------------------------

        |

        | NOW SERVING only when:

        |

        | queue_status = seen

        | status       = pending

        |

        | When consultation becomes completed,

        | patient disappears from NOW SERVING.

        |--------------------------------------------------------------------------

        */

        $currentAppointment = $appointments

            ->filter(function (

                OnlineAppointment $appointment

            ): bool {

                $consultation =

                    $appointment->consultation;

                if (! $consultation) {

                    return false;

                }

                return

                    $consultation->queue_status === 'seen'

                    &&

                    $consultation->status === 'pending';

            })

            ->sortByDesc(function (

                OnlineAppointment $appointment

            ) {

                return optional(

                    $appointment->consultation

                )->doctor_seen_at;

            })

            ->first();

        /*

        |--------------------------------------------------------------------------

        | NEXT PATIENT

        |--------------------------------------------------------------------------

        |

        | NEXT PATIENT:

        |

        | 1. Payment must be PAID

        | 2. Consultation must NOT exist

        | 3. Must not be current patient

        | 4. Lowest token number first

        |--------------------------------------------------------------------------

        */

        $nextAppointment = $appointments

            ->filter(function (

                OnlineAppointment $appointment

            ) use ($currentAppointment): bool {

                /*

                | Do not show current patient as NEXT

                */

                if (

                    $currentAppointment

                    &&

                    $appointment->id ===

                    $currentAppointment->id

                ) {

                    return false;

                }

                /*

                | Payment must be PAID

                */

                if (

                    $appointment->payment_status !== 'paid'

                ) {

                    return false;

                }

                /*

                | Consultation already exists

                */

                if (

                    $appointment->consultation !== null

                ) {

                    return false;

                }

                return true;

            })

            ->sortBy(function (

                OnlineAppointment $appointment

            ) {

                return (int) $appointment->token_number;

            })

            ->first();

        /*

        |--------------------------------------------------------------------------

        | WAITING PATIENTS

        |--------------------------------------------------------------------------

        |

        | Waiting:

        |

        | - consultation does not exist

        | - current excluded

        | - next excluded

        | - token number ascending

        |

        | Appointment time is NOT included.

        |--------------------------------------------------------------------------

        */

        $waitingAppointments = $appointments

            ->filter(function (

                OnlineAppointment $appointment

            ) use (

                $currentAppointment,

                $nextAppointment

            ): bool {

                /*

                | Do not show current

                */

                if (

                    $currentAppointment

                    &&

                    $appointment->id ===

                    $currentAppointment->id

                ) {

                    return false;

                }

                /*

                | Do not show next again

                */

                if (

                    $nextAppointment

                    &&

                    $appointment->id ===

                    $nextAppointment->id

                ) {

                    return false;

                }

                /*

                | Consultation already exists

                */

                if (

                    $appointment->consultation !== null

                ) {

                    return false;

                }

                return true;

            })

            ->sortBy(function (

                OnlineAppointment $appointment

            ) {

                return (int) $appointment->token_number;

            })

            ->values();

        /*

        |--------------------------------------------------------------------------

        | CURRENT DATA

        |--------------------------------------------------------------------------

        */

        $current = null;

        if ($currentAppointment) {

            $current = [

                'id' => $currentAppointment->id,

                'token' => (int) $currentAppointment->token_number,

                'patient_name' => $currentAppointment

                    ->patient

                    ->full_name

                    ?? 'Patient',

                'doctor_name' => $currentAppointment

                    ->doctor

                    ->name

                    ?? 'Doctor',

                'room' => $currentAppointment

                    ->doctor

                    ->room_number

                    ?? $room,

                /*

                | This is only DISPLAY information.

                | It does NOT control queue order.

                */

                'appointment_time' => optional(

                    $currentAppointment

                        ->appointment_date

                )->format('h:i A'),

                'queue_status' => $currentAppointment

                    ->consultation

                    ?->queue_status,

                'consultation_status' => $currentAppointment

                    ->consultation

                    ?->status,

            ];

        }

        /*

        |--------------------------------------------------------------------------

        | NEXT DATA

        |--------------------------------------------------------------------------

        */

        $next = null;

        if ($nextAppointment) {

            $next = [

                'id' => $nextAppointment->id,

                'token' => (int) $nextAppointment->token_number,

                'patient_name' => $nextAppointment

                    ->patient

                    ->full_name

                    ?? 'Patient',

                'doctor_name' => $nextAppointment

                    ->doctor

                    ->name

                    ?? 'Doctor',

                'room' => $nextAppointment

                    ->doctor

                    ->room_number

                    ?? $room,

                'payment_status' => $nextAppointment

                    ->payment_status,

                'paid' => $nextAppointment

                    ->payment_status === 'paid',

            ];

        }

        /*

        |--------------------------------------------------------------------------

        | WAITING DATA

        |--------------------------------------------------------------------------

        */

        $waiting = $waitingAppointments

            ->map(function (

                OnlineAppointment $appointment

            ): array {

                return [

                    'id' => $appointment->id,

                    'token' => (int) $appointment->token_number,

                    'patient_name' => $appointment

                        ->patient

                        ->full_name

                        ?? 'Patient',

                    'doctor_name' => $appointment

                        ->doctor

                        ->name

                        ?? 'Doctor',

                    'room' => $appointment

                        ->doctor

                        ->room_number

                        ?? '',

                ];

            })

            ->values();

        /*

        |--------------------------------------------------------------------------

        | JSON RESPONSE

        |--------------------------------------------------------------------------

        */

        return response()->json([

            'success' => true,

            'room' => $room,

            'doctor_name' => $doctorName,

            'doctor_specialization' => $doctorSpecialization,

            'current' => $current,

            'next' => $next,

            'waiting' => $waiting,

            'waiting_count' => $waiting->count(),

            'updated_at' => now()->toIso8601String(),

        ]);

    }
}
