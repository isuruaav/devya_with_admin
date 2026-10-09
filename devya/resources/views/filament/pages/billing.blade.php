<x-filament-panels::page>
    @php
        $bills = $this->getBills();
    @endphp

    <div class="admin-workspace billing-page">
        @if (session('status'))
            <div role="status" class="border-b border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <div class="admin-filter-bar">
            <div class="admin-filter-field">
                <label for="billNumber">Bill Number</label>
                <input id="billNumber" type="search" wire:model.live.debounce.400ms="billNumber"
                    placeholder="Search bill number" class="admin-filter-input">
            </div>
            <div class="admin-filter-field">
                <label for="patientName">Patient Name</label>
                <input id="patientName" type="search" wire:model.live.debounce.400ms="patientName"
                    placeholder="Search patient" class="admin-filter-input">
            </div>
            <div class="admin-filter-field">
                <label for="billDate">Bill Date</label>
                <input id="billDate" type="date" wire:model.live="billDate" class="admin-filter-input">
            </div>
            <x-filament::icon-button
                color="gray" icon="heroicon-m-x-mark" wire:click="clearFilters"
                label="Clear filters" tooltip="Clear filters" class="admin-filter-clear"
            />
        </div>

        <div class="admin-records-header">
            <h2>Consultation Bills</h2>
            <span class="admin-record-count">{{ $bills->total() }} {{ $bills->total() === 1 ? 'bill' : 'bills' }}</span>
        </div>

        <div class="admin-records-body" wire:loading.class="opacity-60" wire:target="billNumber,patientName,billDate,clearFilters">
            <table class="admin-responsive-table" role="table" aria-label="Consultation bills">
                <thead>
                    <tr>
                        <th scope="col">Bill No.</th>
                        <th scope="col">Patient</th>
                        <th scope="col">Doctor</th>
                        <th scope="col">Bill Date</th>
                        <th scope="col" class="text-right">Total</th>
                        <th scope="col">Payment</th>
                        <th scope="col" class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bills as $bill)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5" wire:key="consultation-bill-{{ $bill->getKey() }}">
                            <td data-label="Bill No.">
                                <div class="font-semibold">
                                    {{ $bill->bill_number }}
                                    @if ($bill->consultation?->consultation_number)
                                        <div class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                                            {{ $bill->consultation->consultation_number }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Patient">{{ $bill->consultation?->patient?->full_name ?? 'N/A' }}</td>
                            <td data-label="Doctor">{{ $bill->consultation?->doctor?->name ?? 'N/A' }}</td>
                            <td data-label="Bill Date">{{ $bill->created_at?->format('d M Y') ?? 'N/A' }}</td>
                            <td data-label="Total" class="text-right font-semibold tabular-nums">
                                {{ $bill->currency }} {{ number_format((float) $bill->grand_total, 2) }}
                            </td>
                            <td data-label="Payment">
                                <span @class([
                                    'inline-flex w-fit rounded px-2 py-1 text-xs font-semibold',
                                    'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200' => $bill->payment_status === 'paid',
                                    'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-200' => $bill->payment_status !== 'paid',
                                ])>
                                    {{ ucfirst($bill->payment_status ?? 'unpaid') }}
                                </span>
                            </td>
                            <td data-label="Actions">
                                <div class="admin-table-actions">
                                    <x-filament::icon-button
                                        tag="a" color="success" icon="heroicon-m-printer"
                                        href="{{ route('consultation-bills.bill', $bill) }}" target="_blank"
                                        label="Print consultation bill" tooltip="Print consultation bill"
                                    />

                                    @if ($bill->consultation)
                                        <x-filament::icon-button
                                            tag="a" color="gray" icon="heroicon-m-clipboard-document-list"
                                            href="{{ route('billing.prescription', $bill->consultation) }}"
                                            label="Prescription" tooltip="Prescription"
                                        />

                                        @if ($bill->consultation->patient?->email)
                                            <form method="POST" action="{{ route('billing.email', $bill->consultation) }}">
                                                @csrf
                                                <x-filament::icon-button
                                                    type="submit" color="gray" icon="heroicon-m-envelope"
                                                    :disabled="!\App\Support\OutboundMail::isEnabled()"
                                                    :label="\App\Support\OutboundMail::isEnabled() ? 'Email bill' : 'Email delivery is not configured.'"
                                                    :tooltip="\App\Support\OutboundMail::isEnabled() ? 'Email bill' : null"
                                                />
                                            </form>
                                        @endif
                                    @endif

                                    @if ($whatsAppUrl = $this->getWhatsAppUrl($bill))
                                        <x-filament::icon-button
                                            tag="a" color="success" icon="heroicon-m-chat-bubble-left-right"
                                            href="{{ $whatsAppUrl }}" target="_blank"
                                            label="Share on WhatsApp" tooltip="Share on WhatsApp"
                                        />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 dark:text-gray-400">
                                No consultation bills match these filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bills->hasPages())
            <div class="admin-pagination">
                {{ $bills->links() }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
