<?php

namespace App\Filament\Pages;

use App\Models\Medicine;
use App\Models\PharmacyBill;
use App\Models\PharmacyBillItem;
use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PharmacyDashboard extends Page
{
    protected string $view = 'filament.pages.pharmacy-dashboard';

    protected static ?string $title = 'Pharmacy Dashboard';

    protected static ?string $navigationLabel = 'Pharmacy Dashboard';

    protected static string|\BackedEnum|null $navigationIcon =

        'heroicon-o-chart-bar-square';

    public string $dateFrom;

    public string $dateTo;

    public string $paymentMethod = 'all';

    public string $paymentStatus = 'all';

    public string $medicineId = 'all';

    public string $category = 'all';

    public string $customerId = 'all';

    public string $staffId = 'all';

    /*

\|--------------------------------------------------------------------------

\| Access Control

\|--------------------------------------------------------------------------

*/

    public static function canAccess(): bool
    {

        $user = auth()->user();

        if (! $user) {

            return false;

        }

        // Doctorට Pharmacy Dashboard access නැහැ

        if ($user->hasRole('doctor')) {

            return false;

        }

        return true;

    }

    /*

    |--------------------------------------------------------------------------

    | Navigation

    |--------------------------------------------------------------------------

    */

    public static function shouldRegisterNavigation(): bool
    {

        $user = auth()->user();

        if (! $user) {

            return false;

        }

        // Doctorට Pharmacy Dashboard menu එක පෙන්වන්න එපා

        if ($user->hasRole('doctor')) {

            return false;

        }

        return true;

    }

    public function mount(): void
    {

        $this->dateFrom = now()->startOfDay()->format('Y-m-d');

        $this->dateTo = now()->endOfDay()->format('Y-m-d');

    }

    protected function getBillsQuery()
    {

        return PharmacyBill::query()

            ->whereBetween('created_at', [

                Carbon::parse($this->dateFrom)->startOfDay(),

                Carbon::parse($this->dateTo)->endOfDay(),

            ])

            ->when(

                $this->paymentMethod !== 'all',

                fn ($query) => $query->where(

                    'payment_method',

                    $this->paymentMethod

                )

            )

            ->when(

                $this->paymentStatus !== 'all',

                fn ($query) => $query->where(

                    'payment_status',

                    $this->paymentStatus

                )

            )

            ->when(

                $this->customerId !== 'all',

                fn ($query) => $query->where(

                    'patient_id',

                    $this->customerId

                )

            )

            ->when(

                $this->staffId !== 'all',

                fn ($query) => $query->where(

                    'created_by_user_id',

                    $this->staffId

                )

            );

    }

    public function updatedDateFrom(): void
    {

        $this->refreshDashboard();

    }

    public function updatedDateTo(): void
    {

        $this->refreshDashboard();

    }

    public function updatedPaymentMethod(): void
    {

        $this->refreshDashboard();

    }

    public function updatedPaymentStatus(): void
    {

        $this->refreshDashboard();

    }

    public function updatedMedicineId(): void
    {

        $this->refreshDashboard();

    }

    public function updatedCategory(): void
    {

        $this->refreshDashboard();

    }

    public function updatedCustomerId(): void
    {

        $this->refreshDashboard();

    }

    public function updatedStaffId(): void
    {

        $this->refreshDashboard();

    }

    public function refreshDashboard(): void
    {

        // Dashboard data is calculated directly from the database

        // when the Livewire component re-renders.

    }

    public function setDateRange(string $range): void
    {

        match ($range) {

            'today' => $this->setToday(),

            'yesterday' => $this->setYesterday(),

            'week' => $this->setThisWeek(),

            'month' => $this->setThisMonth(),

            default => null,

        };

    }

    public function setToday(): void
    {

        $this->dateFrom = now()->format('Y-m-d');

        $this->dateTo = now()->format('Y-m-d');

    }

    public function setYesterday(): void
    {

        $this->dateFrom = now()->subDay()->format('Y-m-d');

        $this->dateTo = now()->subDay()->format('Y-m-d');

    }

    public function setThisWeek(): void
    {

        $this->dateFrom = now()->startOfWeek()->format('Y-m-d');

        $this->dateTo = now()->endOfWeek()->format('Y-m-d');

    }

    public function setThisMonth(): void
    {

        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');

        $this->dateTo = now()->endOfMonth()->format('Y-m-d');

    }

    public function getTotalSalesProperty(): float
    {

        return (float) $this->getBillsQuery()

            ->where('payment_status', 'paid')

            ->sum('grand_total');

    }

    public function getBillCountProperty(): int
    {

        return $this->getBillsQuery()->count();

    }

    public function getCustomerCountProperty(): int
    {

        return $this->getBillsQuery()

            ->whereNotNull('patient_id')

            ->distinct('patient_id')

            ->count('patient_id');

    }

    public function getItemsSoldProperty(): float
    {

        return (float) PharmacyBillItem::query()

            ->whereHas(

                'pharmacyBill',

                fn ($query) => $this->applyBillFilters($query)

                    ->where('payment_status', 'paid')

            )

            ->when(

                $this->medicineId !== 'all',

                fn ($query) => $query->where(

                    'medicine_id',

                    $this->medicineId

                )

            )

            ->when(

                $this->category !== 'all',

                fn ($query) => $query->whereHas(

                    'medicine',

                    fn ($medicineQuery) => $medicineQuery->where(

                        'category',

                        $this->category

                    )

                )

            )

            ->sum('quantity');

    }

    protected function applyBillFilters($query)
    {

        return $query

            ->whereBetween('created_at', [

                Carbon::parse($this->dateFrom)->startOfDay(),

                Carbon::parse($this->dateTo)->endOfDay(),

            ])

            ->when(

                $this->paymentMethod !== 'all',

                fn ($query) => $query->where(

                    'payment_method',

                    $this->paymentMethod

                )

            )

            ->when(

                $this->paymentStatus !== 'all',

                fn ($query) => $query->where(

                    'payment_status',

                    $this->paymentStatus

                )

            )

            ->when(

                $this->customerId !== 'all',

                fn ($query) => $query->where(

                    'patient_id',

                    $this->customerId

                )

            )

            ->when(

                $this->staffId !== 'all',

                fn ($query) => $query->where(

                    'created_by_user_id',

                    $this->staffId

                )

            );

    }

    public function getAverageSaleProperty(): float
    {

        $count = $this->getBillsQuery()

            ->where('payment_status', 'paid')

            ->count();

        if ($count === 0) {

            return 0;

        }

        return $this->getTotalSalesProperty() / $count;

    }

    public function getDiscountTotalProperty(): float
    {

        return (float) $this->getBillsQuery()

            ->where('payment_status', 'paid')

            ->sum('discount');

    }

    public function getPaymentSummaryProperty()
    {

        return $this->getBillsQuery()

            ->where('payment_status', 'paid')

            ->select(

                'payment_method',

                DB::raw('SUM(grand_total) as total')

            )

            ->groupBy('payment_method')

            ->orderByDesc('total')

            ->get();

    }

    public function getLowStockMedicinesProperty()
    {

        return Medicine::query()

            ->where('is_active', true)

            ->whereColumn(

                'stock_quantity',

                '<=',

                'reorder_level'

            )

            ->orderBy('stock_quantity')

            ->limit(10)

            ->get();

    }

    public function getOutOfStockCountProperty(): int
    {

        return Medicine::query()

            ->where('is_active', true)

            ->where('stock_quantity', '<=', 0)

            ->count();

    }

    public function getLowStockCountProperty(): int
    {

        return Medicine::query()

            ->where('is_active', true)

            ->whereColumn(

                'stock_quantity',

                '<=',

                'reorder_level'

            )

            ->where('stock_quantity', '>', 0)

            ->count();

    }

    public function getTopSellingMedicinesProperty()
    {

        return PharmacyBillItem::query()

            ->select(

                'medicine_id',

                'medicine_name',

                DB::raw('SUM(quantity) as total_quantity'),

                DB::raw('SUM(total) as total_sales')

            )

            ->whereHas(

                'pharmacyBill',

                fn ($query) => $this->applyBillFilters($query)

                    ->where('payment_status', 'paid')

            )

            ->when(

                $this->category !== 'all',

                fn ($query) => $query->whereHas(

                    'medicine',

                    fn ($medicineQuery) => $medicineQuery->where(

                        'category',

                        $this->category

                    )

                )

            )

            ->groupBy(

                'medicine_id',

                'medicine_name'

            )

            ->orderByDesc('total_quantity')

            ->limit(10)

            ->get();

    }

    public function getRecentSalesProperty()
    {

        return $this->getBillsQuery()

            ->with([

                'patient',

                'createdBy',

                'items',

            ])

            ->latest()

            ->limit(15)

            ->get();

    }

    public function getMedicinesProperty()
    {

        return Medicine::query()

            ->where('is_active', true)

            ->orderBy('name')

            ->get([

                'id',

                'name',

                'category',

            ]);

    }

    public function getCategoriesProperty()
    {

        return Medicine::query()

            ->where('is_active', true)

            ->whereNotNull('category')

            ->where('category', '!=', '')

            ->distinct()

            ->orderBy('category')

            ->pluck('category');

    }

    public function getCustomersProperty()
    {

        return PharmacyBill::query()

            ->whereNotNull('patient_id')

            ->with('patient')

            ->select('patient_id')

            ->distinct()

            ->get()

            ->pluck('patient')

            ->filter();

    }

    public function getStaffProperty()
    {

        return User::query()

            ->whereIn(

                'id',

                PharmacyBill::query()

                    ->whereNotNull('created_by_user_id')

                    ->distinct()

                    ->pluck('created_by_user_id')

            )

            ->orderBy('name')

            ->get();

    }
}
