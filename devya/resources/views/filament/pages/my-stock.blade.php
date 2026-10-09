<x-filament-panels::page>
    <div class="stock-summary-grid grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        <div class="stock-card stock-card-blue overflow-hidden rounded-2xl p-5 text-white shadow-lg">
            <p class="text-sm font-medium text-blue-100">Medicine Types</p>
            <p class="mt-3 text-3xl font-bold">{{ number_format($this->getTotalMedicines()) }}</p>
            <p class="mt-1 text-xs text-blue-100">Active medicine records</p>
        </div>

        <div class="stock-card stock-card-orange overflow-hidden rounded-2xl p-5 text-white shadow-lg">
            <p class="text-sm font-medium text-orange-100">Total Used</p>
            <p class="mt-3 text-3xl font-bold">{{ number_format($this->getTotalUsed()) }}</p>
            <p class="mt-1 text-xs text-orange-100">Issued through consultations</p>
        </div>

        <div class="stock-card stock-card-green overflow-hidden rounded-2xl p-5 text-white shadow-lg">
            <p class="text-sm font-medium text-emerald-100">Total Remaining</p>
            <p class="mt-3 text-3xl font-bold">{{ number_format($this->getTotalRemaining()) }}</p>
            <p class="mt-1 text-xs text-emerald-100">Available stock quantity</p>
        </div>

        <div class="stock-card stock-card-red overflow-hidden rounded-2xl p-5 text-white shadow-lg">
            <p class="text-sm font-medium text-rose-100">Reorder Alerts</p>
            <p class="mt-3 text-3xl font-bold">{{ number_format($this->getLowStockCount()) }}</p>
            <p class="mt-1 text-xs text-rose-100">Medicines need attention</p>
        </div>
    </div>

    <div class="stock-table-panel mt-7 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        @php
            $stockPage = $this->getStockPage();
        @endphp
        <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Medicine Stock Details</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Track issued quantities, available stock, and reorder thresholds.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                    <tr>
                        <th class="px-5 py-4">Medicine</th>
                        <th class="px-5 py-4">Category</th>
                        <th class="px-5 py-4 text-right">Total Stocked</th>
                        <th class="px-5 py-4 text-right">Used</th>
                        <th class="px-5 py-4 text-right">Remaining</th>
                        <th class="px-5 py-4 text-right">Reorder Level</th>
                        <th class="px-5 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($stockPage as $medicine)
                        @php
                            $used = (int) ($medicine->used_quantity ?? 0);
                            $remaining = (int) $medicine->stock_quantity;
                            $isLow = $remaining <= $medicine->reorder_level;
                        @endphp
                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-5 py-4 font-semibold text-gray-950 dark:text-white">{{ $medicine->name }}</td>
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $medicine->category }}</td>
                            <td class="px-5 py-4 text-right tabular-nums">{{ number_format($used + $remaining) }} {{ $medicine->unit }}</td>
                            <td class="px-5 py-4 text-right tabular-nums text-amber-600 dark:text-amber-400">{{ number_format($used) }} {{ $medicine->unit }}</td>
                            <td class="px-5 py-4 text-right font-semibold tabular-nums">{{ number_format($remaining) }} {{ $medicine->unit }}</td>
                            <td class="px-5 py-4 text-right tabular-nums text-gray-500 dark:text-gray-400">{{ number_format($medicine->reorder_level) }} {{ $medicine->unit }}</td>
                            <td class="px-5 py-4">
                                <x-filament::badge :color="$isLow ? 'danger' : 'success'">
                                    {{ $isLow ? 'Reorder Needed' : 'In Stock' }}
                                </x-filament::badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-gray-500 dark:text-gray-400">
                                No medicine stock records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($stockPage->hasPages())
            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-800">
                {{ $stockPage->links() }}
            </div>
        @endif
    </div>
</x-filament-panels::page>
