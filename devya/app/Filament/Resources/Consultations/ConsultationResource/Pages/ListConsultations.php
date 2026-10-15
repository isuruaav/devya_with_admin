<?php

namespace App\Filament\Resources\Consultations\ConsultationResource\Pages;

use App\Filament\Resources\Consultations\ConsultationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsultations extends ListRecords
{
    protected static string $resource = ConsultationResource::class;

    /**
     * Check actual roles and module permissions rather than comparing email
     * addresses with doctors. A non-doctor admin may share a doctor's email.
     * Doctor accounts continue to use their dedicated dashboard workflow.
     *
     * @param array<string, mixed> $parameters
     */
    public static function canAccess(array $parameters = []): bool
    {
        $user = auth()->user();

        return $user !== null
            && ConsultationResource::canViewAny()
            && ! $user->hasRole('doctor');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
