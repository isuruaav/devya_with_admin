<x-filament-panels::page>
    @php
        $doctors = $this->getDoctors();
        $specializations = $this->getSpecializations();
    @endphp

    <div class="admin-workspace doctor-availability-page">
        <div class="admin-filter-bar">
            <label class="admin-filter-field" for="doctor-specialization">
                <span>Specialization</span>
                <input id="doctor-specialization" type="search" wire:model.live.debounce.100ms="specialization"
                    list="doctor-specializations" autocomplete="off" class="admin-filter-input"
                    placeholder="Search specialization">
                <datalist id="doctor-specializations">
                    @foreach ($specializations as $specialization)
                        <option value="{{ $specialization }}"></option>
                    @endforeach
                </datalist>
            </label>
            <label class="admin-filter-field" for="doctor-search">
                <span>Doctor Name or Specialization</span>
                <input id="doctor-search" type="search" wire:model.live.debounce.100ms="doctorSearch"
                    autocomplete="off" class="admin-filter-input" placeholder="Search doctors">
            </label>
            <label class="admin-filter-field" for="doctor-appointment-date">
                <span>Appointment Date</span>
                <input id="doctor-appointment-date" type="date" wire:model.live="date" class="admin-filter-input">
            </label>
            <x-filament::icon-button
                color="gray" icon="heroicon-m-x-mark" wire:click="clearSearch"
                label="Clear filters" tooltip="Clear filters" class="admin-filter-clear"
            />
        </div>

        <div class="admin-records-header">
            <p>{{ $this->getSelectedDateLabel() }}</p>
            <span class="admin-record-count">
                {{ $doctors->count() }} {{ $doctors->count() === 1 ? 'doctor' : 'doctors' }} available
            </span>
        </div>

        <div class="admin-records-body" wire:loading.class="opacity-60" wire:target="specialization,doctorSearch,date,clearSearch">
            <table class="admin-responsive-table" role="table" aria-label="Available doctors">
                <thead>
                    <tr>
                        <th scope="col"><i class="fa-solid fa-user-doctor mr-1 text-emerald-600"></i> Doctor</th>
                        <th scope="col"><i class="fa-solid fa-stethoscope mr-1 text-emerald-600"></i> Specialization</th>
                        <th scope="col"><i class="fa-regular fa-clock mr-1 text-emerald-600"></i> Visiting Hours</th>
                        <th scope="col"><i class="fa-solid fa-door-open mr-1 text-emerald-600"></i> Room</th>
                        <th scope="col"><i class="fa-solid fa-user-check mr-1 text-emerald-600"></i> Booked</th>
                        <th scope="col"><i class="fa-solid fa-chair mr-1 text-emerald-600"></i> Remaining</th>
                        <th scope="col"><i class="fa-solid fa-address-book mr-1 text-emerald-600"></i> Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($doctors as $doctor)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5" wire:key="available-doctor-{{ $doctor->getKey() }}">
                            <td data-label="Doctor">
                                <div class="flex items-center gap-3">
                                    @if ($doctor->doctor_photo)
                                        <img src="{{ asset('storage/'.ltrim($doctor->doctor_photo, '/')) }}"
                                            alt="{{ $doctor->name }}" loading="lazy"
                                            class="h-10 w-10 shrink-0 rounded-lg object-cover"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <span class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 font-semibold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200">
                                            {{ mb_strtoupper(mb_substr($doctor->name, 0, 1)) }}
                                        </span>
                                    @else
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 font-semibold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-200">
                                            {{ mb_strtoupper(mb_substr($doctor->name, 0, 1)) }}
                                        </span>
                                    @endif
                                    <span class="min-w-0 font-semibold">{{ $doctor->name }}</span>
                                </div>
                            </td>
                            <td data-label="Specialization">{{ $doctor->specialization ?: 'General Consultation' }}</td>
                            <td data-label="Visiting Hours">
                                {{ $doctor->date_schedule?->start_time ?: $doctor->schedule_start ?: 'Any time' }}
                                -
                                {{ $doctor->date_schedule?->end_time ?: $doctor->schedule_end ?: 'Any time' }}
                            </td>
                            <td data-label="Room">{{ $doctor->room_number ?: 'Not assigned' }}</td>
                            <td data-label="Booked" class="tabular-nums">
                                {{ $doctor->booked_count }} / {{ $doctor->max_patients_per_day ?? 'N/A' }}
                            </td>
                            <td data-label="Remaining" class="font-semibold tabular-nums text-emerald-700 dark:text-emerald-400">
                                {{ $doctor->remaining_count }}
                            </td>
                            <td data-label="Contact">
                                <div class="text-xs">
                                    @if ($doctor->phone_number)
                                        <div><a href="tel:{{ $doctor->phone_number }}" class="inline-flex items-center gap-1 text-emerald-700 hover:underline dark:text-emerald-400" data-tooltip="Call doctor"><i class="fa-solid fa-phone text-[10px]"></i> {{ $doctor->phone_number }}</a></div>
                                    @else
                                        <div class="text-gray-400">N/A</div>
                                    @endif
                                    @if ($doctor->email)
                                        <div class="mt-1"><a href="mailto:{{ $doctor->email }}" class="inline-flex items-center gap-1 text-blue-600 hover:underline dark:text-blue-400" data-tooltip="Email doctor"><i class="fa-solid fa-envelope text-[10px]"></i> {{ $doctor->email }}</a></div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 dark:text-gray-400">
                                No available doctors found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
