<?php

namespace App\Filament\Pages;

use App\Models\ConsultationBill;
use App\Models\OnlineAppointment;
use App\Models\ReceptionBill;
use Carbon\Carbon;
use Filament\Pages\Page;

class FinancialReports extends Page
{
    public static function canAccess(): bool
    {
        return auth()->user()?->canAccessModule('reports') ?? false;
    }

    protected static ?string $navigationLabel = 'Financial Reports';

    protected static ?string $navigationParentItem = 'Reports';

    protected static ?string $title = 'Financial Reports';

    protected string $view = 'filament.pages.financial-reports';

    public function getSummary(): array
    {
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        return [
            'all_time' => $this->totals(),
            'this_month' => $this->totals($monthStart, $monthEnd),
        ];
    }

    protected function totals(?Carbon $from = null, ?Carbon $to = null): array
    {
        $query = ConsultationBill::query();
        $appointments = OnlineAppointment::query()
            ->where('payment_status', 'paid');
        $receptionBills = ReceptionBill::query()
            ->where('payment_status', 'paid');

        if ($from && $to) {
            $query->whereHas(
                'consultation',
                fn ($consultationQuery) => $consultationQuery
                    ->where('status', '!=', 'cancelled')
                    ->whereBetween('consultation_date', [$from, $to])
            );
            $appointments->whereBetween('paid_at', [$from, $to]);
            $receptionBills->whereBetween('paid_at', [$from, $to]);
        } else {
            $query->whereHas(
                'consultation',
                fn ($consultationQuery) => $consultationQuery
                    ->where('status', '!=', 'cancelled')
            );
        }

        $localFacilityFees = (clone $appointments)
            ->where('invoice_currency', 'LKR')
            ->sum('facility_service_fee');
        $foreignFacilityFees = (clone $appointments)
            ->where('invoice_currency', 'USD')
            ->sum('facility_service_fee');
        $localReceptionIncome = (clone $receptionBills)
            ->where('currency', 'LKR')
            ->sum('grand_total');
        $foreignReceptionIncome = (clone $receptionBills)
            ->where('currency', 'USD')
            ->sum('grand_total');

        return [
            'local' => (float) ((clone $query)->where('currency', 'LKR')->sum('grand_total')
                + $localFacilityFees
                + $localReceptionIncome),
            'foreign' => (float) ((clone $query)->where('currency', 'USD')->sum('grand_total')
                + $foreignFacilityFees
                + $foreignReceptionIncome),
            'doctor' => (float) (clone $query)->sum('doctor_fee'),
            'treatments' => (float) (clone $query)->sum('treatment_total'),
            'medicines' => (float) (clone $query)->sum('medicine_total'),
            'facility_service_fees' => (float) ($localFacilityFees + $foreignFacilityFees),
            'reception_bills' => (float) ($localReceptionIncome + $foreignReceptionIncome),
        ];
    }
}
