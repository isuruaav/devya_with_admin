<?php

namespace App\Filament\Resources\OnlineAppointments\Pages;

use App\Filament\Resources\OnlineAppointments\OnlineAppointmentResource;
use App\Models\Doctor;
use App\Models\OnlineAppointment;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ListOnlineAppointments extends ListRecords
{
    protected static string $resource =
        OnlineAppointmentResource::class;

    public string $selectedDate = '';

    public function mount(): void
    {
        parent::mount();

        $this->selectedDate = today()->toDateString();
    }

    public function getTabs(): array
    {
        $tabs = [
            'daily' => Tab::make('Daily Appointments')
                ->icon('heroicon-o-calendar-days'),
        ];

        if ($this->getLoggedInDoctor() === null) {
            $tabs['website_requests'] = Tab::make('Website Requests')
                ->icon('heroicon-o-globe-alt')
                ->badge(fn (): int => $this->websiteRequestsQuery()->count());
        }

        return $tabs;
    }

    public function getDefaultActiveTab(): string
    {
        return $this->getLoggedInDoctor() === null
            && $this->websiteRequestsQuery()->exists()
                ? 'website_requests'
                : 'daily';
    }

    public function isShowingWebsiteRequests(): bool
    {
        return $this->activeTab === 'website_requests'
            && $this->getLoggedInDoctor() === null;
    }

    /**
     * @return Builder<OnlineAppointment>
     */
    protected function websiteRequestsQuery(): Builder
    {
        /** @var Builder<OnlineAppointment> $query */
        $query = OnlineAppointmentResource::getEloquentQuery();

        return $query
            ->where('source', 'Website')
            ->where('status', 'pending');
    }

    protected function getLoggedInDoctor(): ?Doctor
    {
        return auth()->user()?->getActiveDoctor();
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    protected function getSelectedDateRange(): array
    {
        $date = blank($this->selectedDate)
            ? today()->toDateString()
            : $this->selectedDate;

        /*
         * IMPORTANT:
         * appointment_date values in the database are stored
         * as Sri Lanka local time.
         *
         * Therefore DO NOT convert this range to UTC.
         */
        $start = Carbon::parse(
            $date,
            'Asia/Colombo'
        )->startOfDay();

        $end = Carbon::parse(
            $date,
            'Asia/Colombo'
        )->endOfDay();

        return [
            $start,
            $end,
        ];
    }

    /**
     * @return Builder<OnlineAppointment>
     */
    protected function appointmentsQuery(): Builder
    {
        if ($this->isShowingWebsiteRequests()) {
            return $this->websiteRequestsQuery();
        }

        $query = OnlineAppointment::query()
            ->with([
                'patient',
                'doctor',
                'consultation',
            ]);

        $doctor = $this->getLoggedInDoctor();

        /*
         * Doctor:
         * show only his/her appointments.
         *
         * Reception/Admin:
         * no doctor filter -> show ALL appointments.
         */
        if ($doctor) {
            $query->where(
                'online_appointments.doctor_id',
                $doctor->getKey()
            );
        }

        [
            $start,
            $end,
        ] = $this->getSelectedDateRange();

        $query->whereBetween(
            'online_appointments.appointment_date',
            [
                $start,
                $end,
            ]
        );

        return $query;
    }

    /**
     * @return Builder<OnlineAppointment>
     */
    protected function getTableQuery(): Builder
    {
        return $this
            ->appointmentsQuery()
            ->orderBy(
                'appointment_date',
                'asc'
            );
    }

    /**
     * @return array{
     *     total: int,
     *     paid: int,
     *     cancelled: int,
     *     online: int,
     *     reception: int,
     *     local: int,
     *     foreign: int
     * }
     */
    public function getDateStats(): array
    {
        $appointments = $this
            ->appointmentsQuery()
            ->get();

        $total = $appointments->count();

        $paid = $appointments
            ->filter(
                fn (OnlineAppointment $appointment): bool => $appointment->payment_status === 'paid'
            )
            ->count();

        $cancelled = $appointments
            ->filter(
                fn (OnlineAppointment $appointment): bool => in_array(
                    strtolower((string) $appointment->status),
                    [
                        'cancelled',
                        'rejected',
                    ],
                    true
                )
            )
            ->count();

        $online = $appointments
            ->filter(
                fn (OnlineAppointment $appointment): bool => in_array(
                    strtolower((string) $appointment->source),
                    [
                        'online',
                        'website',
                    ],
                    true
                )
            )
            ->count();

        $reception = $appointments
            ->filter(
                fn (OnlineAppointment $appointment): bool => strcasecmp(
                    (string) $appointment->source,
                    'Reception'
                ) === 0
            )
            ->count();

        $local = $appointments
            ->filter(
                fn (OnlineAppointment $appointment): bool => strcasecmp(
                    (string) (
                        $appointment->patient_type
                        ?? $appointment->patient->patient_type
                        ?? 'Local'
                    ),
                    'Local'
                ) === 0
            )
            ->count();

        $foreign = $appointments
            ->filter(
                fn (OnlineAppointment $appointment): bool => strcasecmp(
                    (string) (
                        $appointment->patient_type
                        ?? $appointment->patient->patient_type
                        ?? 'Local'
                    ),
                    'Foreign'
                ) === 0
            )
            ->count();

        return [
            'total' => $total,
            'paid' => $paid,
            'cancelled' => $cancelled,
            'online' => $online,
            'reception' => $reception,
            'local' => $local,
            'foreign' => $foreign,
        ];
    }

    public function getSelectedDateLabel(): string
    {
        if (blank($this->selectedDate)) {
            return today()->format('d M Y');
        }

        return Carbon::parse(
            $this->selectedDate
        )->format('d M Y');
    }
}
