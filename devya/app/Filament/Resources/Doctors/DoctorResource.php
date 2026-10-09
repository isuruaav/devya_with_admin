<?php

namespace App\Filament\Resources\Doctors;

use App\Filament\Resources\Doctors\Pages\CreateDoctor;
use App\Filament\Resources\Doctors\Pages\EditDoctor;
use App\Filament\Resources\Doctors\Pages\ListDoctors;
use App\Filament\Resources\Doctors\Pages\ViewDoctor;
use App\Filament\Resources\Doctors\RelationManagers\AuditsRelationManager;
use App\Filament\Resources\Doctors\RelationManagers\ConsultationsRelationManager;
use App\Filament\Resources\Doctors\Schemas\DoctorForm;
use App\Filament\Resources\Doctors\Schemas\DoctorInfolist;
use App\Filament\Resources\Doctors\Tables\DoctorsTable;
use App\Models\Doctor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('doctors') ?? false;
    }

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Doctors';

    public static function form(Schema $schema): Schema
    {
        return DoctorForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DoctorInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DoctorsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        return parent::getEloquentQuery()
            ->withCount([
                'consultations',
                'consultations as today_consultations_count' => fn (Builder $query): Builder => $query
                    ->whereBetween('consultation_date', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
                    ->where('status', '!=', 'cancelled'),
                'consultations as month_consultations_count' => fn (Builder $query): Builder => $query
                    ->whereBetween('consultation_date', [$monthStart, $monthEnd])
                    ->where('status', '!=', 'cancelled'),
            ])
            ->withSum([
                'consultations as month_doctor_fees' => fn (Builder $query): Builder => $query
                    ->whereBetween('consultation_date', [$monthStart, $monthEnd])
                    ->where('status', '!=', 'cancelled'),
            ], 'doctor_fee');
    }

    public static function getRelations(): array
    {
        return [
            ConsultationsRelationManager::class,
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDoctors::route('/'),
            'create' => CreateDoctor::route('/create'),
            'view' => ViewDoctor::route('/{record}'),
            'edit' => EditDoctor::route('/{record}/edit'),
        ];
    }
}
