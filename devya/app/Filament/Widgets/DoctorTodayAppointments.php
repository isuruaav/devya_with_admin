<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Consultations\ConsultationResource;
use App\Models\Doctor;
use App\Models\OnlineAppointment;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Collection;

class DoctorTodayAppointments extends Widget
{
    protected string $view = 'filament.widgets.doctor-today-appointments';

    protected static ?int $sort = -10;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return static::getDoctorForLoggedInUser() !== null;
    }

    protected static function getDoctorForLoggedInUser(): ?Doctor
    {
        $user = auth()->user();

        if (! $user || blank($user->email)) {
            return null;
        }

        return Doctor::query()
            ->where('is_active', true)
            ->where('email', $user->email)
            ->first();
    }

    public function getDoctor(): ?Doctor
    {
        return static::getDoctorForLoggedInUser();
    }

    /**
     * @return Collection<int, OnlineAppointment>
     */
    public function getAppointments(): Collection
    {
        $doctor = $this->getDoctor();

        if (! $doctor) {
            return new Collection;
        }

        return OnlineAppointment::query()
            ->with([
                'patient',
                'doctor',
                'consultation',
            ])
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->where('status', 'confirmed')
            ->orderBy('appointment_date')
            ->get();
    }

    public function getTodayCount(): int
    {
        return $this->getAppointments()->count();
    }

    public function getWaitingCount(): int
    {
        return $this->getAppointments()
            ->filter(
                fn (OnlineAppointment $appointment): bool => ! $appointment->consultation_id
                    && $appointment->payment_status === 'paid'
            )
            ->count();
    }

    public function getPaidCount(): int
    {
        return $this->getAppointments()
            ->filter(
                fn (OnlineAppointment $appointment): bool => $appointment->payment_status === 'paid'
            )
            ->count();
    }

    public function getConsultationCreatedCount(): int
    {
        return $this->getAppointments()
            ->filter(
                fn (OnlineAppointment $appointment): bool => filled($appointment->consultation_id)
            )
            ->count();
    }

    public function startConsultation(int $appointmentId): void
    {
        $doctor = $this->getDoctor();

        if (! $doctor) {
            Notification::make()
                ->title('Doctor account not linked')
                ->body(
                    'Your login email is not linked with an active doctor record.'
                )
                ->danger()
                ->send();

            return;
        }

        $appointment = OnlineAppointment::query()
            ->whereKey($appointmentId)
            ->where('doctor_id', $doctor->id)
            ->with([
                'patient',
                'doctor',
            ])
            ->first();

        if (! $appointment) {
            Notification::make()
                ->title('Appointment not found')
                ->body(
                    'This appointment does not belong to your doctor account.'
                )
                ->danger()
                ->send();

            return;
        }

        if ($appointment->status !== 'confirmed') {
            Notification::make()
                ->title('Appointment not confirmed')
                ->body(
                    'Only confirmed appointments can start a consultation.'
                )
                ->warning()
                ->send();

            return;
        }

        if ($appointment->payment_status !== 'paid') {
            Notification::make()
                ->title('Payment not completed')
                ->body(
                    'Reception must complete the payment before the consultation can start.'
                )
                ->warning()
                ->send();

            return;
        }

        if ($appointment->consultation_id) {
            $this->redirect(
                ConsultationResource::getUrl(
                    'view',
                    [
                        'record' => $appointment->consultation_id,
                    ]
                )
            );

            return;
        }

        $this->redirect(
            ConsultationResource::getUrl(
                'create',
                [
                    'appointment' => $appointment->id,
                ]
            )
        );
    }

    public function viewConsultation(int $appointmentId): void
    {
        $doctor = $this->getDoctor();

        if (! $doctor) {
            return;
        }

        $appointment = OnlineAppointment::query()
            ->whereKey($appointmentId)
            ->where('doctor_id', $doctor->id)
            ->first();

        if (! $appointment || ! $appointment->consultation_id) {
            Notification::make()
                ->title('Consultation not found')
                ->warning()
                ->send();

            return;
        }

        $this->redirect(
            ConsultationResource::getUrl(
                'view',
                [
                    'record' => $appointment->consultation_id,
                ]
            )
        );
    }
}
