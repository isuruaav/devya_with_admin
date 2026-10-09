<?php

namespace App\Filament\Pages;

use App\Models\Doctor;
use BackedEnum;
use Filament\Pages\Page;

class QueueManagement extends Page
{
    protected string $view = 'filament.pages.queue-management';

    protected static ?string $navigationLabel = 'Queue Management';

    protected static ?string $title = 'Queue Management';

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-tv';

    protected static ?int $navigationSort = 20;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->canAccessModule('queue') && $user->hasAnyRole(
            [
                'super_admin',
                'admin',
                'reception',
            ]
        );
    }

    public static function canAccess(
        array $parameters = []
    ): bool {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->canAccessModule('queue') && $user->hasAnyRole(
            [
                'super_admin',
                'admin',
                'reception',
            ]
        );
    }

    public static function getNavigationGroup(): ?string
    {
        return 'QUEUE MANAGEMENT';
    }

    public function getRooms()
    {
        return Doctor::query()
            ->where('is_active', true)
            ->whereNotNull('room_number')
            ->where('room_number', '!=', '')
            ->orderBy('room_number')
            ->pluck('room_number')
            ->unique()
            ->values();
    }
}
