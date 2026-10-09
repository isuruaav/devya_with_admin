<x-filament-panels::page>
    <div class="admin-workspace doctor-fee-report">
        <form wire:submit="applyDateRange" class="admin-report-filters">
            <div class="grid gap-4 md:grid-cols-2">
                <label class="admin-filter-field" for="doctor-fees-from">
                    <span>From Date</span>
                    <input id="doctor-fees-from" type="date" wire:model.defer="dateFrom" required class="admin-filter-input">
                    @error('dateFrom') <small class="text-red-600 dark:text-red-400">{{ $message }}</small> @enderror
                </label>
                <label class="admin-filter-field" for="doctor-fees-to">
                    <span>To Date</span>
                    <input id="doctor-fees-to" type="date" wire:model.defer="dateTo" required class="admin-filter-input">
                    @error('dateTo') <small class="text-red-600 dark:text-red-400">{{ $message }}</small> @enderror
                </label>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <x-filament::button type="submit" icon="heroicon-m-magnifying-glass" wire:target="applyDateRange">Search Report</x-filament::button>
                <x-filament::button color="gray" icon="heroicon-m-calendar-days" wire:click="setToday">Today</x-filament::button>
                <x-filament::button color="gray" icon="heroicon-m-calendar" wire:click="setThisMonth">This Month</x-filament::button>
                <x-filament::icon-button color="gray" icon="heroicon-m-arrow-path" wire:click="resetDateRange"
                    label="Reset date range" tooltip="Reset date range" />
            </div>
        </form>

        <div class="admin-records-body" wire:loading.class="opacity-60" wire:target="applyDateRange,setToday,setThisMonth,resetDateRange">
            <table class="admin-responsive-table" role="table" aria-label="Doctor fee report">
                <thead>
                    <tr>
                        <th scope="col">Doctor</th>
                        <th scope="col">Consultations</th>
                        <th scope="col" class="text-right">LKR Doctor Fees</th>
                        <th scope="col" class="text-right">USD Doctor Fees</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->getRows() as $row)
                        <tr>
                            <td data-label="Doctor" class="font-semibold">{{ $row['name'] }}</td>
                            <td data-label="Consultations" class="tabular-nums">{{ $row['consultations'] }}</td>
                            <td data-label="LKR Fees" class="text-right tabular-nums text-emerald-700 dark:text-emerald-400">
                                Rs. {{ number_format($row['lkr'], 2) }}
                            </td>
                            <td data-label="USD Fees" class="text-right tabular-nums text-blue-700 dark:text-blue-400">
                                $ {{ number_format($row['usd'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 dark:text-gray-400">
                                No doctor fees found for this period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
