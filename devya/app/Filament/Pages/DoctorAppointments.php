<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Consultations\ConsultationResource;
use App\Models\Doctor;
use App\Models\OnlineAppointment;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class DoctorAppointments extends Page
{
    protected string $view = 'filament.pages.doctor-appointments';

    protected static ?string $navigationLabel = 'My Appointments';

    protected static ?string $title = 'My Appointments';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'CONSULTATIONS';
    }

    public static function canAccess(): bool
    {
        return static::getDoctorForLoggedInUser() !== null;
    }

    protected static function getDoctorForLoggedInUser(): ?Doctor
    {
        return auth()->user()?->getActiveDoctor();
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
            return (new OnlineAppointment)->newCollection();
        }

        return OnlineAppointment::query()
            ->with([
                'patient',
                'doctor',
                'consultation',
            ])
            ->where('doctor_id', $doctor->getKey())
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

    public function getInConsultationCount(): int
    {
        return $this->getAppointments()
            ->filter(
                fn (OnlineAppointment $appointment): bool => filled($appointment->consultation_id)
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
            ->where('doctor_id', $doctor->getKey())
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
                    'appointment' => $appointment->getKey(),
                ]
            )
        );
    }

    public function viewConsultation(int $appointmentId): void
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
            ->where('doctor_id', $doctor->getKey())
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

        if (! $appointment->consultation_id) {
            Notification::make()
                ->title('Consultation not found')
                ->body(
                    'A consultation has not been created for this appointment yet.'
                )
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
