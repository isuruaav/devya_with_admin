<?php

namespace App\Filament\Resources\Consultations\ConsultationResource\Pages;

use App\Filament\Resources\Consultations\ConsultationResource;
use App\Models\Doctor;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsultations extends ListRecords
{
    protected static string $resource =
        ConsultationResource::class;

    /*
    |--------------------------------------------------------------------------
    | Doctor Check
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Page Access
    |--------------------------------------------------------------------------
    */

    public static function canAccess(
        array $parameters = []
    ): bool {
        return ! static::isDoctorUser();
    }

    /*
    |--------------------------------------------------------------------------
    | Header Actions
    |--------------------------------------------------------------------------
    */

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
