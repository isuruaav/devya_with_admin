<?php

namespace App\Filament\Pages;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\OnlineAppointment;
use App\Models\Patient;
use App\Models\ReceptionBill;
use App\Models\Treatment;
use App\Models\User;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Dashboard extends Page
{
    protected static string $routePath = '/';

    protected static ?int $navigationSort = -2;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'Dashboard';

    protected static string|\BackedEnum|null $navigationIcon =

        'heroicon-o-home';

    protected string $view =

        'filament.pages.dashboard';

    /*

    |--------------------------------------------------------------------------

    | DOCTOR DASHBOARD FILTER STATE

    |--------------------------------------------------------------------------

    */

    public string $doctorCalendarMonth;

    public string $doctorSelectedDate;

    public string $doctorPatientTypeFilter = 'all';

    public string $doctorPaymentFilter = 'all';

    public string $doctorStatusFilter = 'all';

    public string $doctorTimeFilter = 'all';

    public string $doctorSearch = '';

    public ?string $doctorDateFrom = null;

    public ?string $doctorDateTo = null;

    /*

    |--------------------------------------------------------------------------

    | INITIALIZE DOCTOR CALENDAR

    |--------------------------------------------------------------------------

    */

    public function mount(): void
    {

        $this->doctorCalendarMonth = now()

            ->startOfMonth()

            ->format('Y-m-d');

        $this->doctorSelectedDate = today()

            ->format('Y-m-d');

    }

    /*

    |--------------------------------------------------------------------------

    | NORMAL ADMIN / SUPER ADMIN / RECEPTION DASHBOARD

    |--------------------------------------------------------------------------

    */

    public function getStats(): array
    {

        return [

            'patients' => Patient::query()

                ->count(),

            'consultations' => Consultation::query()

                ->where('status', '!=', 'cancelled')

                ->count(),

            'medicines' => Medicine::query()

                ->where('is_active', true)

                ->count(),

            'treatments' => Treatment::query()

                ->count(),

            'low_stock' => Medicine::query()

                ->where('is_active', true)

                ->whereColumn(

                    'stock_quantity',

                    '<=',

                    'reorder_level'

                )

                ->count(),

            'today_appointments' => OnlineAppointment::query()

                ->whereDate(

                    'appointment_date',

                    today()

                )

                ->where(

                    'status',

                    'confirmed'

                )

                ->count(),

            'today_consultations' => Consultation::query()

                ->whereDate(

                    'consultation_date',

                    today()

                )

                ->where(

                    'status',

                    '!=',

                    'cancelled'

                )

                ->count(),

            'waiting_queue' => Consultation::query()

                ->whereDate(

                    'consultation_date',

                    today()

                )

                ->where(

                    'queue_status',

                    'waiting'

                )

                ->where(

                    'status',

                    '!=',

                    'cancelled'

                )

                ->count(),

            'active_doctors' => Doctor::query()

                ->where(

                    'is_active',

                    true

                )

                ->count(),

            'pending_balance' => $this->getTodayPendingBalance(),

            'today_income' => $this->getTodayIncome(),

            'local_patients' => $this->getTodayLocalPatients(),

            'foreign_patients' => $this->getTodayForeignPatients(),

        ];

    }

    /*

    |--------------------------------------------------------------------------

    | TODAY'S INCOME

    |--------------------------------------------------------------------------

    */

    public function getTodayIncome(): array
    {

        $lkrAppointmentIncome = OnlineAppointment::query()

            ->whereDate(

                'paid_at',

                today()

            )

            ->where(

                'payment_status',

                'paid'

            )

            ->where(

                'invoice_currency',

                'LKR'

            )

            ->selectRaw(

                'COALESCE(SUM(appointment_fee), 0) + COALESCE(SUM(facility_service_fee), 0) as total'

            )

            ->value('total');

        $usdAppointmentIncome = OnlineAppointment::query()

            ->whereDate(

                'paid_at',

                today()

            )

            ->where(

                'payment_status',

                'paid'

            )

            ->where(

                'invoice_currency',

                'USD'

            )

            ->selectRaw(

                'COALESCE(SUM(appointment_fee), 0) + COALESCE(SUM(facility_service_fee), 0) as total'

            )

            ->value('total');

        $lkrReceptionIncome = ReceptionBill::query()

            ->whereDate('paid_at', today())

            ->where('payment_status', 'paid')

            ->where('currency', 'LKR')

            ->sum('grand_total');

        $usdReceptionIncome = ReceptionBill::query()

            ->whereDate('paid_at', today())

            ->where('payment_status', 'paid')

            ->where('currency', 'USD')

            ->sum('grand_total');

        $lkrConsultationIncome =

            $this->getConsultationPaymentByCurrency('LKR');

        $usdConsultationIncome =

            $this->getConsultationPaymentByCurrency('USD');

        return [

            'LKR' => (float) $lkrAppointmentIncome

                + (float) $lkrReceptionIncome

                + (float) $lkrConsultationIncome,

            'USD' => (float) $usdAppointmentIncome

                + (float) $usdReceptionIncome

                + (float) $usdConsultationIncome,

        ];

    }

    /*

    |--------------------------------------------------------------------------

    | CONSULTATION PAYMENTS TODAY

    |--------------------------------------------------------------------------

    */

    protected function getConsultationPaymentByCurrency(

        string $currency

    ): float {

        return (float) DB::table('consultation_bills')

            ->whereDate(

                'paid_at',

                today()

            )

            ->where(

                'currency',

                $currency

            )

            ->where(

                'payment_status',

                'paid'

            )

            ->sum(

                DB::raw(

                    'GREATEST(grand_total - appointment_paid, 0)'

                )

            );

    }

    /*

    |--------------------------------------------------------------------------

    | TODAY'S PENDING BALANCE

    |--------------------------------------------------------------------------

    */

    public function getTodayPendingBalance(): array
    {

        $balances = DB::table('consultation_bills')

            ->where(

                'balance_due',

                '>',

                0

            )

            ->whereIn(

                'currency',

                [

                    'LKR',

                    'USD',

                ]

            )

            ->selectRaw(

                'currency, SUM(balance_due) as total'

            )

            ->groupBy(

                'currency'

            )

            ->pluck(

                'total',

                'currency'

            );

        return [

            'LKR' => (float) (

                $balances['LKR'] ?? 0

            ),

            'USD' => (float) (

                $balances['USD'] ?? 0

            ),

        ];

    }

    /*

    |--------------------------------------------------------------------------

    | TODAY'S LOCAL PATIENTS

    |--------------------------------------------------------------------------

    */

    public function getTodayLocalPatients(): int
    {

        return OnlineAppointment::query()

            ->whereDate(

                'appointment_date',

                today()

            )

            ->where(

                'status',

                'confirmed'

            )

            ->where(

                function ($query) {

                    $query

                        ->where(

                            'patient_type',

                            'Local'

                        )

                        ->orWhereNull(

                            'patient_type'

                        );

                }

            )

            ->distinct(

                'patient_id'

            )

            ->whereNotNull(

                'patient_id'

            )

            ->count(

                'patient_id'

            );

    }

    /*

    |--------------------------------------------------------------------------

    | TODAY'S FOREIGN PATIENTS

    |--------------------------------------------------------------------------

    */

    public function getTodayForeignPatients(): int
    {

        return OnlineAppointment::query()

            ->whereDate(

                'appointment_date',

                today()

            )

            ->where(

                'status',

                'confirmed'

            )

            ->where(

                'patient_type',

                'Foreign'

            )

            ->whereNotNull(

                'patient_id'

            )

            ->distinct(

                'patient_id'

            )

            ->count(

                'patient_id'

            );

    }

    /*

    |--------------------------------------------------------------------------

    | TODAY'S APPOINTMENTS

    |--------------------------------------------------------------------------

    */

    public function getTodayAppointments(): Collection
    {

        return OnlineAppointment::query()

            ->with([

                'patient',

                'doctor',

                'consultation',

            ])

            ->whereDate(

                'appointment_date',

                today()

            )

            ->where(

                'status',

                'confirmed'

            )

            ->orderBy(

                'appointment_date',

                'asc'

            )

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | TODAY'S CONSULTATIONS

    |--------------------------------------------------------------------------

    */

    public function getTodayConsultations(): Collection
    {

        return Consultation::query()

            ->with([

                'patient',

                'doctor',

            ])

            ->whereDate(

                'consultation_date',

                today()

            )

            ->where(

                'status',

                '!=',

                'cancelled'

            )

            ->orderBy(

                'queue_number',

                'asc'

            )

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | WAITING QUEUE

    |--------------------------------------------------------------------------

    */

    public function getWaitingQueue(): Collection
    {

        return Consultation::query()

            ->with([

                'patient',

                'doctor',

            ])

            ->whereDate(

                'consultation_date',

                today()

            )

            ->where(

                'queue_status',

                'waiting'

            )

            ->where(

                'status',

                '!=',

                'cancelled'

            )

            ->orderBy(

                'queue_number',

                'asc'

            )

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | LOW STOCK MEDICINES

    |--------------------------------------------------------------------------

    */

    public function getLowStockMedicines(): Collection
    {

        return Medicine::query()

            ->where(

                'is_active',

                true

            )

            ->whereColumn(

                'stock_quantity',

                '<=',

                'reorder_level'

            )

            ->orderBy(

                'stock_quantity',

                'asc'

            )

            ->limit(6)

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | ACTIVE DOCTORS

    |--------------------------------------------------------------------------

    */

    public function getActiveDoctors(): Collection
    {

        return Doctor::query()

            ->where(

                'is_active',

                true

            )

            ->orderBy(

                'name',

                'asc'

            )

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR

    |--------------------------------------------------------------------------

    */

    public function getDoctor(): ?Doctor
    {

        $user = Auth::user();

        if (! $user instanceof User) {

            return null;

        }

        $doctor = $user->getActiveDoctor();

        return $doctor instanceof Doctor ? $doctor : null;

    }

    /*

    |--------------------------------------------------------------------------

    | IS DOCTOR

    |--------------------------------------------------------------------------

    */

    public function isDoctor(): bool
    {

        return $this->getDoctor() !== null;

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR APPOINTMENTS BASE QUERY

    |--------------------------------------------------------------------------

    */

    protected function doctorAppointmentsQuery()
    {

        $doctor = $this->getDoctor();

        if (! $doctor) {

            return OnlineAppointment::query()

                ->whereRaw(

                    '1 = 0'

                );

        }

        return OnlineAppointment::query()

            ->where(

                'doctor_id',

                $doctor->getKey()

            );

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR TODAY'S CONFIRMED APPOINTMENTS

    |--------------------------------------------------------------------------

    */

    public function getDoctorTodayAppointments(): Collection
    {

        return $this

            ->doctorAppointmentsQuery()

            ->with([

                'patient',

                'doctor',

                'consultation',

            ])

            ->whereDate(

                'appointment_date',

                today()

            )

            ->where(

                'status',

                'confirmed'

            )

            ->orderBy(

                'appointment_date',

                'asc'

            )

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR MY PATIENT COUNT

    |--------------------------------------------------------------------------

    */

    public function getMyPatientCount(): int
    {

        return $this

            ->doctorAppointmentsQuery()

            ->where(

                'status',

                'confirmed'

            )

            ->whereNotNull(

                'patient_id'

            )

            ->distinct(

                'patient_id'

            )

            ->count(

                'patient_id'

            );

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR TODAY'S APPOINTMENT COUNT

    |--------------------------------------------------------------------------

    */

    public function getTodayAppointmentCount(): int
    {

        return $this

            ->getDoctorTodayAppointments()

            ->count();

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR PENDING TODAY

    |--------------------------------------------------------------------------

    */

    public function getPendingAppointmentCount(): int
    {

        return $this

            ->getDoctorTodayAppointments()

            ->filter(

                fn ($appointment): bool => ! filled(

                    $this->appointmentAttribute($appointment, 'consultation_id')

                )

            )

            ->count();

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR SEEN TODAY

    |--------------------------------------------------------------------------

    */

    public function getSeenAppointmentCount(): int
    {

        return $this

            ->getDoctorTodayAppointments()

            ->filter(

                fn ($appointment): bool => filled(

                    $this->appointmentAttribute($appointment, 'consultation_id')

                )

            )

            ->count();

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR PAID TODAY

    |--------------------------------------------------------------------------

    */

    public function getPaidAppointmentCount(): int
    {

        return $this

            ->getDoctorTodayAppointments()

            ->filter(

                fn ($appointment): bool => $this->appointmentAttribute($appointment, 'payment_status') === 'paid'

            )

            ->count();

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR LOCAL PATIENTS TODAY

    |--------------------------------------------------------------------------

    */

    public function getLocalPatientCount(): int
    {

        return $this

            ->getDoctorTodayAppointments()

            ->filter(

                fn ($appointment): bool => strcasecmp(

                    $this->appointmentPatientType($appointment),

                    'Local'

                ) === 0

            )

            ->pluck(

                'patient_id'

            )

            ->filter()

            ->unique()

            ->count();

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR FOREIGN PATIENTS TODAY

    |--------------------------------------------------------------------------

    */

    public function getForeignPatientCount(): int
    {

        return $this

            ->getDoctorTodayAppointments()

            ->filter(

                fn ($appointment): bool => strcasecmp(

                    $this->appointmentPatientType($appointment),

                    'Foreign'

                ) === 0

            )

            ->pluck(

                'patient_id'

            )

            ->filter()

            ->unique()

            ->count();

    }

    /*

    |--------------------------------------------------------------------------

    | DOCTOR CALENDAR

    |--------------------------------------------------------------------------

    */

    public function getDoctorCalendarMonthDate(): Carbon
    {

        try {

            return Carbon::parse(

                $this->doctorCalendarMonth

            )->startOfMonth();

        } catch (\Throwable) {

            return Carbon::now()->startOfMonth();

        }

    }

    /*

    |--------------------------------------------------------------------------

    | CALENDAR MONTH TITLE

    |--------------------------------------------------------------------------

    */

    public function getDoctorCalendarMonthTitle(): string
    {

        return $this

            ->getDoctorCalendarMonthDate()

            ->format('F Y');

    }

    /*

    |--------------------------------------------------------------------------

    | CALENDAR DAYS

    |--------------------------------------------------------------------------

    */

    public function getDoctorCalendarDays(): array
    {

        $month = $this->getDoctorCalendarMonthDate();

        $calendarStart = $month

            ->copy()

            ->startOfWeek(Carbon::MONDAY);

        $calendarEnd = $month

            ->copy()

            ->endOfMonth()

            ->endOfWeek(Carbon::SUNDAY);

        $appointments = $this

            ->doctorAppointmentsQuery()

            ->where(

                'status',

                'confirmed'

            )

            ->whereBetween(

                'appointment_date',

                [

                    $calendarStart->copy()->startOfDay(),

                    $calendarEnd->copy()->endOfDay(),

                ]

            )

            ->get([

                'id',

                'appointment_date',

                'consultation_id',

                'payment_status',

                'patient_type',

            ]);

        $appointmentGroups = $appointments

            ->groupBy(

                fn ($appointment) => Carbon::parse(

                    $appointment->appointment_date

                )->format('Y-m-d')

            );

        $days = [];

        for (

            $date = $calendarStart->copy();

            $date->lte($calendarEnd);

            $date->addDay()

        ) {

            $dateKey = $date->format('Y-m-d');

            $dayAppointments =

                $appointmentGroups->get(

                    $dateKey,

                    collect()

                );

            $days[] = [

                'date' => $dateKey,

                'day' => $date->day,

                'isCurrentMonth' => $date->month === $month->month,

                'isToday' => $date->isToday(),

                'isSelected' => $dateKey === $this->doctorSelectedDate,

                'count' => $dayAppointments->count(),

                'seen' => $dayAppointments
                    ->filter(

                        fn ($appointment) => filled(

                            $this->appointmentAttribute($appointment, 'consultation_id')

                        )

                    )
                    ->count(),

                'waiting' => $dayAppointments
                    ->filter(

                        fn ($appointment) => ! filled(

                            $this->appointmentAttribute($appointment, 'consultation_id')

                        )

                    )
                    ->count(),

                'paid' => $dayAppointments
                    ->filter(

                        fn ($appointment) => $this->appointmentAttribute($appointment, 'payment_status') === 'paid'

                    )
                    ->count(),

                'foreign' => $dayAppointments
                    ->filter(

                        fn ($appointment) => strcasecmp(

                            $this->appointmentPatientType($appointment),

                            'Foreign'

                        ) === 0

                    )
                    ->count(),

            ];

        }

        return $days;

    }

    /*

    |--------------------------------------------------------------------------

    | SELECT CALENDAR DATE

    |--------------------------------------------------------------------------

    */

    public function selectDoctorCalendarDate(

        string $date

    ): void {

        try {

            $selected = Carbon::parse($date);

            $this->doctorSelectedDate =

                $selected->format('Y-m-d');

            $this->doctorDateFrom =

                $selected->format('Y-m-d');

            $this->doctorDateTo =

                $selected->format('Y-m-d');

        } catch (\Throwable) {

            $this->doctorSelectedDate =

                today()->format('Y-m-d');

        }

    }

    /*

    |--------------------------------------------------------------------------

    | PREVIOUS MONTH

    |--------------------------------------------------------------------------

    */

    public function previousDoctorMonth(): void
    {

        $this->doctorCalendarMonth =

            $this

                ->getDoctorCalendarMonthDate()

                ->subMonth()

                ->startOfMonth()

                ->format('Y-m-d');

    }

    /*

    |--------------------------------------------------------------------------

    | NEXT MONTH

    |--------------------------------------------------------------------------

    */

    public function nextDoctorMonth(): void
    {

        $this->doctorCalendarMonth =

            $this

                ->getDoctorCalendarMonthDate()

                ->addMonth()

                ->startOfMonth()

                ->format('Y-m-d');

    }

    /*

    |--------------------------------------------------------------------------

    | TODAY

    |--------------------------------------------------------------------------

    */

    public function todayDoctorCalendar(): void
    {

        $today = today();

        $this->doctorCalendarMonth =

            $today

                ->copy()

                ->startOfMonth()

                ->format('Y-m-d');

        $this->doctorSelectedDate =

            $today->format('Y-m-d');

        $this->doctorDateFrom =

            $today->format('Y-m-d');

        $this->doctorDateTo =

            $today->format('Y-m-d');

    }

    /*

    |--------------------------------------------------------------------------

    | QUICK FILTER - TODAY

    |--------------------------------------------------------------------------

    */

    public function doctorFilterToday(): void
    {

        $date = today()->format('Y-m-d');

        $this->doctorDateFrom = $date;

        $this->doctorDateTo = $date;

        $this->doctorSelectedDate = $date;

        $this->doctorCalendarMonth =

            today()

                ->startOfMonth()

                ->format('Y-m-d');

    }

    /*

    |--------------------------------------------------------------------------

    | QUICK FILTER - TOMORROW

    |--------------------------------------------------------------------------

    */

    public function doctorFilterTomorrow(): void
    {

        $date = today()

            ->addDay()

            ->format('Y-m-d');

        $this->doctorDateFrom = $date;

        $this->doctorDateTo = $date;

        $this->doctorSelectedDate = $date;

        $this->doctorCalendarMonth =

            Carbon::parse($date)

                ->startOfMonth()

                ->format('Y-m-d');

    }

    /*

    |--------------------------------------------------------------------------

    | QUICK FILTER - THIS WEEK

    |--------------------------------------------------------------------------

    */

    public function doctorFilterThisWeek(): void
    {

        $start = today()

            ->startOfWeek(Carbon::MONDAY);

        $end = today()

            ->endOfWeek(Carbon::SUNDAY);

        $this->doctorDateFrom =

            $start->format('Y-m-d');

        $this->doctorDateTo =

            $end->format('Y-m-d');

        $this->doctorSelectedDate =

            today()->format('Y-m-d');

    }

    /*

    |--------------------------------------------------------------------------

    | QUICK FILTER - THIS MONTH

    |--------------------------------------------------------------------------

    */

    public function doctorFilterThisMonth(): void
    {

        $this->doctorDateFrom =

            today()

                ->startOfMonth()

                ->format('Y-m-d');

        $this->doctorDateTo =

            today()

                ->endOfMonth()

                ->format('Y-m-d');

        $this->doctorSelectedDate =

            today()->format('Y-m-d');

        $this->doctorCalendarMonth =

            today()

                ->startOfMonth()

                ->format('Y-m-d');

    }

    /*

    |--------------------------------------------------------------------------

    | FILTERED DOCTOR APPOINTMENTS

    |--------------------------------------------------------------------------

    */

    public function getDoctorFilteredAppointments(): Collection
    {

        $query = $this

            ->doctorAppointmentsQuery()

            ->with([

                'patient',

                'doctor',

                'consultation',

            ])

            ->where(

                'status',

                'confirmed'

            );

        /*

        |--------------------------------------------------------------------------

        | DATE FILTER

        |--------------------------------------------------------------------------

        */

        if (

            filled($this->doctorDateFrom)

            && filled($this->doctorDateTo)

        ) {

            $from = Carbon::parse(

                $this->doctorDateFrom

            )->startOfDay();

            $to = Carbon::parse(

                $this->doctorDateTo

            )->endOfDay();

            $query->whereBetween(

                'appointment_date',

                [

                    $from,

                    $to,

                ]

            );

        } elseif (filled($this->doctorDateFrom)) {

            $query->whereDate(

                'appointment_date',

                '>=',

                $this->doctorDateFrom

            );

        } elseif (filled($this->doctorDateTo)) {

            $query->whereDate(

                'appointment_date',

                '<=',

                $this->doctorDateTo

            );

        }

        /*

        |--------------------------------------------------------------------------

        | PATIENT TYPE

        |--------------------------------------------------------------------------

        */

        if (

            $this->doctorPatientTypeFilter !== 'all'

        ) {

            $patientType =

                $this->doctorPatientTypeFilter;

            $query->where(

                function ($q) use ($patientType) {

                    $q->where(

                        'patient_type',

                        $patientType

                    )->orWhereHas(

                        'patient',

                        function ($patientQuery) use (

                            $patientType

                        ) {

                            $patientQuery->where(

                                'patient_type',

                                $patientType

                            );

                        }

                    );

                }

            );

        }

        /*

        |--------------------------------------------------------------------------

        | PAYMENT

        |--------------------------------------------------------------------------

        */

        if (

            $this->doctorPaymentFilter !== 'all'

        ) {

            $query->where(

                'payment_status',

                $this->doctorPaymentFilter

            );

        }

        /*

        |--------------------------------------------------------------------------

        | STATUS

        |--------------------------------------------------------------------------

        */

        if (

            $this->doctorStatusFilter === 'seen'

        ) {

            $query->whereNotNull(

                'consultation_id'

            );

        }

        if (

            $this->doctorStatusFilter === 'waiting'

        ) {

            $query->whereNull(

                'consultation_id'

            );

        }

        /*

        |--------------------------------------------------------------------------

        | SEARCH

        |--------------------------------------------------------------------------

        */

        if (

            filled(

                trim($this->doctorSearch)

            )

        ) {

            $search =

                trim(

                    $this->doctorSearch

                );

            $query->where(

                function ($q) use ($search) {

                    $q->where(

                        'phone_number',

                        'like',

                        "%{$search}%"

                    )
                        ->orWhere(

                            'token_number',

                            'like',

                            "%{$search}%"

                        )
                        ->orWhereHas(

                            'patient',

                            function ($patientQuery) use (

                                $search

                            ) {

                                $patientQuery->where(

                                    'full_name',

                                    'like',

                                    "%{$search}%"

                                );

                            }

                        );

                }

            );

        }

        /*

        |--------------------------------------------------------------------------

        | TIME FILTER

        |--------------------------------------------------------------------------

        */

        if (

            $this->doctorTimeFilter !== 'all'

        ) {

            $query->where(

                function ($q) {

                    if (

                        $this->doctorTimeFilter ===

                        'morning'

                    ) {

                        $q->whereTime(

                            'appointment_date',

                            '>=',

                            '06:00:00'

                        )->whereTime(

                            'appointment_date',

                            '<',

                            '12:00:00'

                        );

                    }

                    if (

                        $this->doctorTimeFilter ===

                        'afternoon'

                    ) {

                        $q->whereTime(

                            'appointment_date',

                            '>=',

                            '12:00:00'

                        )->whereTime(

                            'appointment_date',

                            '<',

                            '17:00:00'

                        );

                    }

                    if (

                        $this->doctorTimeFilter ===

                        'evening'

                    ) {

                        $q->whereTime(

                            'appointment_date',

                            '>=',

                            '17:00:00'

                        )->whereTime(

                            'appointment_date',

                            '<=',

                            '23:59:59'

                        );

                    }

                }

            );

        }

        return $query

            ->orderBy(

                'appointment_date',

                'asc'

            )

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | SELECTED DATE APPOINTMENTS

    |--------------------------------------------------------------------------

    */

    public function getDoctorSelectedDateAppointments(): Collection
    {

        if (

            filled($this->doctorDateFrom)

            || filled($this->doctorDateTo)

            || filled(

                trim($this->doctorSearch)

            )

            || $this->doctorPatientTypeFilter !== 'all'

            || $this->doctorPaymentFilter !== 'all'

            || $this->doctorStatusFilter !== 'all'

            || $this->doctorTimeFilter !== 'all'

        ) {

            return $this->getDoctorFilteredAppointments();

        }

        return $this

            ->doctorAppointmentsQuery()

            ->with([

                'patient',

                'doctor',

                'consultation',

            ])

            ->where(

                'status',

                'confirmed'

            )

            ->whereDate(

                'appointment_date',

                $this->doctorSelectedDate

            )

            ->orderBy(

                'appointment_date',

                'asc'

            )

            ->get();

    }

    /*

    |--------------------------------------------------------------------------

    | FILTERED SUMMARY

    |--------------------------------------------------------------------------

    */

    public function getDoctorFilteredSummary(): array
    {

        $appointments =

            $this->getDoctorFilteredAppointments();

        return [

            'total' => $appointments->count(),

            'seen' => $appointments
                ->filter(

                    fn ($appointment) => filled(

                        $this->appointmentAttribute($appointment, 'consultation_id')

                    )

                )
                ->count(),

            'waiting' => $appointments
                ->filter(

                    fn ($appointment) => ! filled(

                        $this->appointmentAttribute($appointment, 'consultation_id')

                    )

                )
                ->count(),

            'paid' => $appointments
                ->filter(

                    fn ($appointment) => $this->appointmentAttribute($appointment, 'payment_status') ===

                        'paid'

                )
                ->count(),

            'unpaid' => $appointments
                ->filter(

                    fn ($appointment) => $this->appointmentAttribute($appointment, 'payment_status') !==

                        'paid'

                )
                ->count(),

            'local' => $appointments
                ->filter(

                    fn ($appointment) => strcasecmp(

                        $this->appointmentPatientType($appointment),

                        'Local'

                    ) === 0

                )
                ->count(),

            'foreign' => $appointments
                ->filter(

                    fn ($appointment) => strcasecmp(

                        $this->appointmentPatientType($appointment),

                        'Foreign'

                    ) === 0

                )
                ->count(),

        ];

    }

    /*

    |--------------------------------------------------------------------------

    | RESET DOCTOR FILTERS

    |--------------------------------------------------------------------------

    */

    public function resetDoctorFilters(): void
    {

        $this->doctorPatientTypeFilter = 'all';

        $this->doctorPaymentFilter = 'all';

        $this->doctorStatusFilter = 'all';

        $this->doctorTimeFilter = 'all';

        $this->doctorSearch = '';

        $this->doctorDateFrom = null;

        $this->doctorDateTo = null;

        $this->doctorSelectedDate =

            today()->format('Y-m-d');

        $this->doctorCalendarMonth =

            today()

                ->startOfMonth()

                ->format('Y-m-d');

    }

    /*

    |--------------------------------------------------------------------------

    | SELECTED DATE LABEL

    |--------------------------------------------------------------------------

    */

    public function getDoctorSelectedDateLabel(): string
    {

        try {

            return Carbon::parse(

                $this->doctorSelectedDate

            )->format(

                'l, d F Y'

            );

        } catch (\Throwable) {

            return today()->format(

                'l, d F Y'

            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | APPOINTMENT ATTRIBUTE HELPERS
    |--------------------------------------------------------------------------
    */

    protected function appointmentAttribute(mixed $appointment, string $attribute): mixed
    {
        if (! $appointment instanceof Model) {
            return null;
        }

        return $appointment->getAttribute($attribute);
    }

    protected function appointmentPatientType(mixed $appointment): string
    {
        if (! $appointment instanceof Model) {
            return 'Local';
        }

        $patientType = $appointment->getAttribute('patient_type');

        if (is_string($patientType) && $patientType !== '') {
            return $patientType;
        }

        $patient = $appointment->getRelationValue('patient');

        if ($patient instanceof Model) {
            $relatedPatientType = $patient->getAttribute('patient_type');

            if (is_string($relatedPatientType) && $relatedPatientType !== '') {
                return $relatedPatientType;
            }
        }

        return 'Local';
    }
}
