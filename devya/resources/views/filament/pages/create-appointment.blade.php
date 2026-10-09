<x-filament-panels::page>
    <form wire:submit="save" class="admin-workspace appointment-workflow">
        {{ $this->form }}

        <div class="admin-form-actions">
            <x-filament::button
                tag="a"
                color="gray"
                icon="heroicon-m-x-mark"
                href="{{ \App\Filament\Resources\OnlineAppointments\OnlineAppointmentResource::getUrl('index') }}"
            >
                Cancel
            </x-filament::button>

            <x-filament::button
                type="submit"
                icon="heroicon-o-calendar-days"
                wire:target="save"
                wire:loading.attr="disabled"
            >
                Create Appointment
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
