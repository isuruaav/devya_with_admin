<?php

namespace App\Filament\Pages;

use App\Models\ConsultationBill;
use App\Models\OnlineAppointment;
use App\Models\ReceptionBill;
use Carbon\Carbon;
use Filament\Pages\Page;

class IncomeReports extends Page
{
    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('reports') ?? false;
    }

    public string $reportDate;

    public string $reportMonth;

    protected static ?string $navigationLabel = 'Income Reports';

    protected static ?string $navigationParentItem = 'Reports';

    protected static ?string $title = 'Income Reports';

    protected string $view = 'filament.pages.income-reports';

    public function mount(): void
    {
        $now = Carbon::now();
        $this->reportDate = $now->toDateString();
        $this->reportMonth = $now->format('Y-m');
    }

    public function getDailyTotals(): array
    {
        $date = Carbon::createFromFormat('Y-m-d', $this->reportDate);

        return $this->totals(
            $date->copy()->startOfDay(),
            $date->copy()->endOfDay()
        );
    }

    public function getMonthlyTotals(): array
    {
        $month = Carbon::createFromFormat(
            'Y-m',
            $this->reportMonth ?: Carbon::now()->format('Y-m')
        );

        return $this->totals(
            $month->copy()->startOfMonth(),
            $month->copy()->endOfMonth()
        );
    }

    protected function totals(Carbon $from, Carbon $to): array
    {
        $query = ConsultationBill::query()
            ->whereHas(
                'consultation',
                fn ($consultationQuery) => $consultationQuery
                    ->where('status', '!=', 'cancelled')
                    ->whereBetween('consultation_date', [$from, $to])
            );

        return [
            'local' => $this->currencyTotals(
                clone $query,
                'LKR',
                $from,
                $to
            ),
            'foreign' => $this->currencyTotals(
                clone $query,
                'USD',
                $from,
                $to
            ),
        ];
    }

    public function updatedReportDate(string $value): void
    {
        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            $this->reportDate = Carbon::now()->toDateString();
        }
    }

    public function updatedReportMonth(string $value): void
    {
        if (! preg_match('/^\d{4}-\d{2}$/', $value)) {
            $this->reportMonth = Carbon::now()->format('Y-m');
        }
    }

    protected function currencyTotals(
        $query,
        string $currency,
        Carbon $from,
        Carbon $to
    ): array {
        $query->where('currency', $currency);

        $facilityServiceFees = OnlineAppointment::query()
            ->where('payment_status', 'paid')
            ->where('invoice_currency', $currency)
            ->where('facility_service_fee', '>', 0)
            ->whereBetween('paid_at', [$from, $to]);
        $receptionBills = ReceptionBill::query()
            ->where('payment_status', 'paid')
            ->where('currency', $currency)
            ->whereBetween('paid_at', [$from, $to]);

        $facilityServiceFeeTotal = (float) (clone $facilityServiceFees)
            ->sum('facility_service_fee');
        $receptionBillTotal = (float) (clone $receptionBills)
            ->sum('grand_total');

        return [
            'total' => (float) (clone $query)->sum('grand_total')
                + $facilityServiceFeeTotal
                + $receptionBillTotal,
            'doctor' => (float) (clone $query)->sum('doctor_fee'),
            'treatments' => (float) (clone $query)->sum('treatment_total'),
            'medicines' => (float) (clone $query)->sum('medicine_total'),
            'facility_service_fees' => $facilityServiceFeeTotal,
            'reception_bills' => $receptionBillTotal,
            'consultations' => (int) (clone $query)->count(),
            'paid_appointments' => (int) (clone $facilityServiceFees)->count(),
            'reception_bills_count' => (int) (clone $receptionBills)->count(),
        ];
    }
}
