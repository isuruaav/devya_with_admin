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
                    <div class="report-metric report-metric-purple"><span>Local Income</span><strong>Rs. {{ number_format($data['local'], 2) }}</strong></div>
                    <div class="report-metric report-metric-blue"><span>Foreign Income</span><strong>$ {{ number_format($data['foreign'], 2) }}</strong></div>
                    <div class="report-metric"><span>Doctor Fees</span><strong>{{ number_format($data['doctor'], 2) }}</strong></div>
                    <div class="report-metric"><span>Treatments</span><strong>{{ number_format($data['treatments'], 2) }}</strong></div>
                    <div class="report-metric"><span>Medicines</span><strong>{{ number_format($data['medicines'], 2) }}</strong></div>
                    <div class="report-metric"><span>Facilities / Service Fees</span><strong>{{ number_format($data['facility_service_fees'], 2) }}</strong></div>
                    <div class="report-metric"><span>Reception Bills</span><strong>{{ number_format($data['reception_bills'], 2) }}</strong></div>
                </div>
            </section>
        @endforeach
    </div>
</x-filament-panels::page>
