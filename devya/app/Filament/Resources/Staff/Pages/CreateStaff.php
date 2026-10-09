<?php

namespace App\Filament\Resources\Staff\Pages;

use App\Filament\Resources\Staff\StaffResource;
use App\Models\Staff;
use Filament\Resources\Pages\CreateRecord;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $lastStaff = Staff::withTrashed()
            ->orderByDesc('id')
            ->first();

        $nextNumber = $lastStaff
            ? (int) $lastStaff->getKey() + 1
            : 1;

        $data['staff_code'] = 'STF-'.str_pad(
            (string) $nextNumber,
            4,
            '0',
            STR_PAD_LEFT
        );

        return $data;
    }

    protected function afterCreate(): void
    {
        $staff = $this->getRecord();

        if (! $staff instanceof Staff) {
            return;
        }

        $staff->salaryHistories()->create([
            'basic_salary' => $staff->basic_salary ?? 0,
            'allowance' => $staff->allowance ?? 0,
            'effective_date' => $staff->joining_date ?? now()->toDateString(),
            'reason' => 'Initial Salary',
            'changed_by' => auth()->id(),
        ]);
    }
}
