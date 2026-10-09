<?php

namespace App\Filament\Pages;

use App\Models\Consultation;
use App\Models\Expense;
use Carbon\Carbon;
use Filament\Pages\Page;

class ExpenseReports extends Page
{
    protected static ?string $navigationLabel = 'Expense & Net Profit';

    protected static ?string $navigationParentItem = 'Reports';

    protected static ?string $title = 'Expense & Net Profit Report';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected string $view = 'filament.pages.expense-reports';

    public string $dateFrom;

    public string $dateTo;

    public function mount(): void
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo = Carbon::now()->endOfMonth()->toDateString();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('reports') ?? false;
    }

    public function applyDateRange(): void
    {
        $this->validate([
            'dateFrom' => ['required', 'date_format:Y-m-d', 'before_or_equal:dateTo'],
            'dateTo' => ['required', 'date_format:Y-m-d', 'after_or_equal:dateFrom'],
        ], [
            'dateFrom.before_or_equal' => 'The start date must be before or equal to the end date.',
            'dateTo.after_or_equal' => 'The end date must be after or equal to the start date.',
        ]);
    }

    public function resetDateRange(): void
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo = Carbon::now()->endOfMonth()->toDateString();
        $this->resetValidation();
    }

    public function getSummary(): array
    {
        $from = Carbon::createFromFormat('Y-m-d', $this->dateFrom)->startOfDay();
        $to = Carbon::createFromFormat('Y-m-d', $this->dateTo)->endOfDay();

        return [
            'local_income' => (float) Consultation::query()
                ->where('status', '!=', 'cancelled')
                ->where('currency', 'LKR')
                ->whereBetween('consultation_date', [$from, $to])
                ->sum('grand_total'),
            'local_expenses' => (float) Expense::query()
                ->where('currency', 'LKR')
                ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
                ->sum('amount'),
            'foreign_income' => (float) Consultation::query()
                ->where('status', '!=', 'cancelled')
                ->where('currency', 'USD')
                ->whereBetween('consultation_date', [$from, $to])
                ->sum('grand_total'),
            'foreign_expenses' => (float) Expense::query()
                ->where('currency', 'USD')
                ->whereBetween('expense_date', [$from->toDateString(), $to->toDateString()])
                ->sum('amount'),
        ];
    }
}
