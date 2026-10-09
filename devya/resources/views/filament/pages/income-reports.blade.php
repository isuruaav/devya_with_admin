<x-filament-panels::page>
    @php
        $daily = $this->getDailyTotals();
        $monthly = $this->getMonthlyTotals();
    @endphp
    <div class="space-y-6">
        <section class="report-filter-panel">
            <div>
                <h2 class="text-lg font-bold text-gray-950 dark:text-white">Income Filters</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Select a date and month to compare income. Paid reception bills and Facilities / Service Fees are included by payment date.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="report-filter-label">Daily report
                    <input type="date" wire:model.live.debounce.300ms="reportDate" wire:key="income-report-date" class="report-filter-input">
                </label>
                <label class="report-filter-label">Monthly report
                    <input type="month" wire:model.live.debounce.300ms="reportMonth" wire:key="income-report-month" class="report-filter-input">
                </label>
            </div>
        </section>
        @foreach (['daily' => ['title' => 'Daily Income', 'data' => $daily], 'monthly' => ['title' => 'Monthly Income', 'data' => $monthly]] as $period)
            <section class="report-summary-panel">
                <div class="report-section-heading">{{ $period['title'] }}</div>
                <div class="grid gap-5 p-5 lg:grid-cols-2">
                    @foreach (['local' => ['label' => 'Local Income', 'prefix' => 'Rs.', 'color' => 'green'], 'foreign' => ['label' => 'Foreign Income', 'prefix' => '$', 'color' => 'blue']] as $currencyKey => $currency)
                        @php
                            $data = $period['data'][$currencyKey];
                        @endphp
                        <div class="income-card income-card-{{ $currency['color'] }}">
                            <div class="flex items-center justify-between"><h3>{{ $currency['label'] }}</h3><span>{{ $data['consultations'] }} consultations</span></div>
                            <strong>{{ $currency['prefix'] }} {{ number_format($data['total'], 2) }}</strong>
                            <div class="income-breakdown">
                                <span>Doctor Fees <b>{{ number_format($data['doctor'], 2) }}</b></span>
                                <span>Treatments <b>{{ number_format($data['treatments'], 2) }}</b></span>
                                <span>Medicines <b>{{ number_format($data['medicines'], 2) }}</b></span>
                                <span>Facilities / Service Fees <b>{{ number_format($data['facility_service_fees'], 2) }}</b></span>
                                <span>Reception Bills ({{ $data['reception_bills_count'] }}) <b>{{ number_format($data['reception_bills'], 2) }}</b></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</x-filament-panels::page>
