<?php

namespace App\Filament\Pages;

use App\Models\Doctor;
use Carbon\Carbon;
use Filament\Pages\Page;

class DoctorAvailability extends Page
{
    protected static ?string $navigationLabel = 'Doctor Availability';

    protected static ?string $navigationParentItem = 'Consultations';

    protected static ?string $title = 'Find Available Doctors';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';

    protected string $view = 'filament.pages.doctor-availability';

    public string $specialization = '';

    public string $doctorSearch = '';

    public string $date;

    public function mount(): void
    {
        $this->date = Carbon::today()->toDateString();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('doctor_search') ?? false;
    }

    public function clearSearch(): void
    {
        $this->specialization = '';
        $this->doctorSearch = '';
        $this->date = Carbon::today()->toDateString();
    }

    public function getSelectedDateLabel(): string
    {
        return Carbon::createFromFormat('Y-m-d', $this->date)->format('l, d M Y');
    }

    public function getSpecializations(): array
    {
        return Doctor::query()
            ->whereNotNull('specialization')
            ->where('specialization', '!=', '')
            ->distinct()
            ->orderBy('specialization')
            ->pluck('specialization')
            ->values()
            ->all();
    }

    public function getDoctors()
    {
        return Doctor::query()
            ->availableForDate($this->date)
            ->when(
                $this->specialization,
                fn ($query) => $query->where(
                    'specialization',
                    'like',
                    '%'.$this->specialization.'%'
                )
            )
            ->when(
                $this->doctorSearch,
                fn ($query) => $query->where(function ($search) {
                    $search
                        ->where(
                            'name',
                            'like',
                            '%'.$this->doctorSearch.'%'
                        )
                        ->orWhere(
                            'specialization',
                            'like',
                            '%'.$this->doctorSearch.'%'
                        );
                })
            )
            ->with([
                'schedules' => fn ($query) => $query->whereDate(
                    'schedule_date',
                    $this->date
                ),
            ])
            ->withCount([
                'consultations as booked_count' => fn ($query) => $query
                    ->whereDate('consultation_date', $this->date)
                    ->where('status', '!=', 'cancelled'),
            ])
            ->orderBy('name')
            ->get()
            ->map(function (Doctor $doctor): Doctor {
                $bookedCount = (int) $doctor->getAttribute('booked_count');

                $doctor->setAttribute(
                    'remaining_count',
                    max(
                        0,
                        (int) ($doctor->max_patients_per_day ?? 0) - $bookedCount,
                    )
                );

                $doctor->setAttribute(
                    'date_schedule',
                    $doctor->schedules->first()
                );

                return $doctor;
            });
    }
}
