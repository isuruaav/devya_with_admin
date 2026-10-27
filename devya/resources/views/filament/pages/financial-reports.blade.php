<x-filament-panels::page>
    @php
        $summary = $this->getSummary();
    @endphp
    <div class="grid gap-6 xl:grid-cols-2">
        @foreach (['all_time' => 'All-Time Financial Summary', 'this_month' => 'This Month'] as $key => $heading)
            @php
                $data = $summary[$key];
            @endphp
            <section class="report-summary-panel">
                <div class="report-section-heading">{{ $heading }}</div>
                <div class="grid gap-3 p-5 sm:grid-cols-2">
                    <div class="report-metric report-metric-purple" data-tooltip="Total local patient revenue in LKR"><span><i class="fa-solid fa-money-bill-wave mr-1 text-emerald-500"></i> Local Income</span><strong>Rs. {{ number_format($data['local'], 2) }}</strong></div>
                    <div class="report-metric report-metric-blue" data-tooltip="Total foreign patient revenue in USD"><span><i class="fa-solid fa-globe mr-1 text-blue-500"></i> Foreign Income</span><strong>$ {{ number_format($data['foreign'], 2) }}</strong></div>
                    <div class="report-metric" data-tooltip="Doctor consultation fees"><span><i class="fa-solid fa-user-doctor mr-1 text-indigo-500"></i> Doctor Fees</span><strong>{{ number_format($data['doctor'], 2) }}</strong></div>
                    <div class="report-metric" data-tooltip="Treatments and therapy procedures"><span><i class="fa-solid fa-spa mr-1 text-teal-500"></i> Treatments</span><strong>{{ number_format($data['treatments'], 2) }}</strong></div>
                    <div class="report-metric" data-tooltip="Dispensed medicines and pharmacy items"><span><i class="fa-solid fa-pills mr-1 text-purple-500"></i> Medicines</span><strong>{{ number_format($data['medicines'], 2) }}</strong></div>
                    <div class="report-metric" data-tooltip="Clinical facilities and service fees"><span><i class="fa-solid fa-hospital mr-1 text-amber-500"></i> Facilities / Service Fees</span><strong>{{ number_format($data['facility_service_fees'], 2) }}</strong></div>
                    <div class="report-metric" data-tooltip="Direct bills issued through front desk reception"><span><i class="fa-solid fa-receipt mr-1 text-sky-500"></i> Reception Bills</span><strong>{{ number_format($data['reception_bills'], 2) }}</strong></div>
                </div>
            </section>
        @endforeach
    </div>
</x-filament-panels::page>
