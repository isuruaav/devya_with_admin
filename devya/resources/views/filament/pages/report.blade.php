<x-filament-panels::page>
    <div class="reports-hero rounded-2xl p-6 text-white shadow-lg sm:p-8">
        <div class="reports-hero-label">Business Intelligence</div>
        <div class="reports-hero-title">Reports</div>
        <div class="reports-hero-description">Review hospital income and financial performance from one place.</div>
    </div>
    <div class="mt-7 grid gap-5 md:grid-cols-2">
        <a href="{{ \App\Filament\Pages\FinancialReports::getUrl() }}" class="report-choice-card">
            <div class="report-choice-icon report-choice-purple">FR</div>
            <div><h2 class="report-choice-title">Financial Reports</h2><p class="report-choice-description">View total income and revenue breakdown across the hospital.</p></div>
            <span class="report-choice-arrow">→</span>
        </a>
        <a href="{{ \App\Filament\Pages\IncomeReports::getUrl() }}" class="report-choice-card">
            <div class="report-choice-icon report-choice-green">IR</div>
            <div><h2 class="report-choice-title">Income Reports</h2><p class="report-choice-description">Compare daily and monthly Local and Foreign income.</p></div>
            <span class="report-choice-arrow">→</span>
        </a>
    </div>
</x-filament-panels::page>
