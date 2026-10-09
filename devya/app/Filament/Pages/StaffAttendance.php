<?php

namespace App\Filament\Pages;

use App\Models\Doctor;
use Filament\Pages\Page;

class StaffAttendance extends Page
{
    protected string $view =
        'filament.pages.staff-attendance';

    protected static ?string $navigationLabel =
        'Staff Attendance';

    protected static ?string $title =
        'Staff Attendance';

    protected static ?int $navigationSort = 2;

    protected static function isDoctorUser(): bool
    {
        $user = auth()->user();

        if (! $user || blank($user->email)) {
            return false;
        }

        return Doctor::query()
            ->where('is_active', true)
            ->where('email', $user->email)
            ->exists();
    }

    public static function getNavigationGroup(): ?string
    {
        return 'STAFF MANAGEMENT';
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('pharmacy')) {
            return false;
        }

        if (static::isDoctorUser()) {
            return false;
        }

        return $user->canAccessModule('staff_management');
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('pharmacy')) {
            return false;
        }

        if (static::isDoctorUser()) {
            return false;
        }

        return $user->canAccessModule('staff_management');
    }
}
