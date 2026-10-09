<x-filament-panels::page>
    @php($summary = $this->getSummary())

    <section class="expense-report-filter">
        <div>
            <h2 class="expense-report-filter-title">Report Date Range</h2>
            <p class="expense-report-filter-description">Choose a date range to calculate income, expenses, and net profit.</p>
        </div>

        <div class="expense-report-filter-fields">
            <label class="expense-report-filter-field">
                <span>From date</span>
                <input type="date" wire:model.defer="dateFrom">
                @error('dateFrom') <small>{{ $message }}</small> @enderror
            </label>

            <label class="expense-report-filter-field">
                <span>To date</span>
                <input type="date" wire:model.defer="dateTo">
                @error('dateTo') <small>{{ $message }}</small> @enderror
            </label>

            <div class="expense-report-filter-actions">
                <button type="button" wire:click="applyDateRange" class="expense-report-filter-button expense-report-filter-button-primary">
                    Search Report
                </button>
                <button type="button" wire:click="resetDateRange" class="expense-report-filter-button expense-report-filter-button-secondary">
                    Reset
                </button>
            </div>
        </div>
    </section>

    <div class="expense-report-grid">
        <div class="expense-report-card expense-report-card-lkr">
            <p class="expense-report-label">LKR Net Profit (Selected Period)</p>
            <p class="expense-report-total">Rs. {{ number_format($summary['local_income'] - $summary['local_expenses'], 2) }}</p>
            <div class="expense-report-breakdown">
                <span>Income: Rs. {{ number_format($summary['local_income'], 2) }}</span>
                <span>Expenses: Rs. {{ number_format($summary['local_expenses'], 2) }}</span>
            </div>
        </div>
        <div class="expense-report-card expense-report-card-usd">
            <p class="expense-report-label">USD Net Profit (Selected Period)</p>
            <p class="expense-report-total">$ {{ number_format($summary['foreign_income'] - $summary['foreign_expenses'], 2) }}</p>
            <div class="expense-report-breakdown">
                <span>Income: $ {{ number_format($summary['foreign_income'], 2) }}</span>
                <span>Expenses: $ {{ number_format($summary['foreign_expenses'], 2) }}</span>
            </div>
        </div>
    </div>
</x-filament-panels::page>
