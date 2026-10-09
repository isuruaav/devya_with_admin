<?php

namespace App\Filament\Resources\Staff\Pages;

use App\Filament\Resources\Staff\StaffResource;
use App\Models\Staff;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStaff extends EditRecord
{
    protected static string $resource = StaffResource::class;

    protected ?string $oldBasicSalary = null;

    protected ?string $oldAllowance = null;

    protected function staffRecord(): Staff
    {
        $record = $this->getRecord();

        if (! $record instanceof Staff) {
            throw new \RuntimeException(
                'Invalid staff record.'
            );
        }

        return $record;
    }

    protected function beforeSave(): void
    {
        $staff = $this->staffRecord();

        $this->oldBasicSalary = (string) ($staff->basic_salary ?? 0);
        $this->oldAllowance = (string) ($staff->allowance ?? 0);
    }

    protected function afterSave(): void
    {
        $staff = $this->staffRecord();

        $newBasicSalary = (string) ($staff->basic_salary ?? 0);
        $newAllowance = (string) ($staff->allowance ?? 0);

        $salaryChanged =
            $this->oldBasicSalary !== $newBasicSalary
            || $this->oldAllowance !== $newAllowance;

        if ($salaryChanged) {
            $staff->salaryHistories()->create([
                'basic_salary' => $staff->basic_salary ?? 0,
                'allowance' => $staff->allowance ?? 0,
                'effective_date' => now()->toDateString(),
                'reason' => 'Salary Revision',
                'changed_by' => auth()->id(),
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
