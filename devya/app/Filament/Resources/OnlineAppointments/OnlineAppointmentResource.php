<?php

namespace App\Filament\Resources\OnlineAppointments;

use App\Filament\Resources\OnlineAppointments\Pages\ListOnlineAppointments;
use App\Filament\Resources\OnlineAppointments\Tables\OnlineAppointmentsTable;
use App\Models\Doctor;
use App\Models\OnlineAppointment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OnlineAppointmentResource extends Resource
{
    protected static ?string $model = OnlineAppointment::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Appointments';

    protected static ?string $modelLabel = 'Appointment';

    protected static ?string $pluralModelLabel = 'Appointments';

    protected static string|\UnitEnum|null $navigationGroup =
        'CONSULTATIONS';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('consultations') ?? false;
    }

    public static function getNavigationBadge(): ?string
    {
        if (! static::canAccess() || static::getLoggedInDoctor() !== null) {
            return null;
        }

        $pendingRequests = static::getEloquentQuery()
            ->where('source', 'Website')
            ->where('status', 'pending')
            ->count();

        return $pendingRequests > 0 ? (string) $pendingRequests : null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Pending website appointment requests';
    }

    public static function table(Table $table): Table
    {
        return OnlineAppointmentsTable::configure($table);
    }

    protected static function getLoggedInDoctor(): ?Doctor
    {
        return auth()->user()?->getActiveDoctor();
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with([
                'patient',
                'doctor',
                'consultation',
            ]);

        $doctor = static::getLoggedInDoctor();

        if ($doctor) {
            $query->where(
                'doctor_id',
                $doctor->id
            );
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return static::getLoggedInDoctor() === null;
    }

    public static function canEdit(Model $record): bool
    {
        return static::getLoggedInDoctor() === null;
    }

    public static function canDelete(Model $record): bool
    {
        return static::getLoggedInDoctor() === null;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOnlineAppointments::route('/'),
        ];
    }
}
