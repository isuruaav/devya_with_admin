<x-filament-panels::page>
    <div class="history-hero rounded-2xl p-6 text-white shadow-lg sm:p-8">
        <div class="history-hero-label">Clinical Archive</div>
        <div class="history-hero-title">History</div>
        <div class="history-hero-description">
            Select Patient History or Medical History from the History menu to view the required records.
        </div>
    </div>

    <div class="mt-7 grid gap-5 md:grid-cols-2">
        <a href="{{ \App\Filament\Pages\PatientHistory::getUrl() }}" class="history-choice-card group">
            <div class="history-choice-icon history-choice-indigo">PH</div>
            <div>
                <h2 class="history-choice-title">Patient History</h2>
                <p class="history-choice-description">Review previous consultations, doctors, dates, and billing totals.</p>
            </div>
            <span class="history-choice-arrow">→</span>
        </a>

        <a href="{{ \App\Filament\Pages\MedicalHistory::getUrl() }}" class="history-choice-card group">
            <div class="history-choice-icon history-choice-cyan">MH</div>
            <div>
                <h2 class="history-choice-title">Medical History</h2>
                <p class="history-choice-description">Review patient allergies and important medical information.</p>
            </div>
            <span class="history-choice-arrow">→</span>
        </a>
    </div>
</x-filament-panels::page>
