<?php

namespace App\Filament\Pages;

use App\Models\Consultation;
use App\Models\Doctor;
use Carbon\Carbon;
use Filament\Pages\Page;

class DoctorFeeReports extends Page
{
    protected static ?string $navigationLabel = 'Doctor Fee Report';

    protected static ?string $navigationParentItem = 'Reports';

    protected static ?string $title = 'Doctor Fee Report';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected string $view = 'filament.pages.doctor-fee-reports';

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
        ]);
    }

    public function resetDateRange(): void
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo = Carbon::now()->endOfMonth()->toDateString();
        $this->resetValidation();
    }

    public function setToday(): void
    {
        $this->dateFrom = Carbon::today()->toDateString();
        $this->dateTo = Carbon::today()->toDateString();
    }

    public function setThisMonth(): void
    {
        $this->dateFrom = Carbon::now()->startOfMonth()->toDateString();
        $this->dateTo = Carbon::now()->endOfMonth()->toDateString();
    }

    public function getRows(): array
    {
        $from = Carbon::createFromFormat('Y-m-d', $this->dateFrom)->startOfDay();
        $to = Carbon::createFromFormat('Y-m-d', $this->dateTo)->endOfDay();

        $totals = Consultation::query()
            ->selectRaw('doctor_id, currency, COUNT(*) as consultation_count, SUM(doctor_fee) as total_fee')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('consultation_date', [$from, $to])
            ->groupBy('doctor_id', 'currency')
            ->get()
            ->groupBy('doctor_id');

        return Doctor::query()
            ->whereIn('id', $totals->keys())
            ->orderBy('name')
            ->get()
            ->map(function (Doctor $doctor) use ($totals): array {
                $rows = $totals->get($doctor->id, collect())->keyBy('currency');

                $lkrRow = $rows->get('LKR');
                $usdRow = $rows->get('USD');

                return [
                    'name' => $doctor->name,
                    'consultations' => (int) $rows->sum('consultation_count'),
                    'lkr' => $lkrRow instanceof Consultation
                        ? (float) $lkrRow->getAttribute('total_fee')
                        : 0.0,
                    'usd' => $usdRow instanceof Consultation
                        ? (float) $usdRow->getAttribute('total_fee')
                        : 0.0,
                ];
            })
            ->all();
    }
}
