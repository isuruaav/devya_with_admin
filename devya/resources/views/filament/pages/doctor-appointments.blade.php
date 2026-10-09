<x-filament-panels::page>

    @php
        $doctor = $this->getDoctor();
        $appointments = $this->getAppointments();

        $todayCount = $this->getTodayCount();
        $waitingCount = $this->getWaitingCount();
        $paidCount = $this->getPaidCount();
        $inConsultationCount = $this->getInConsultationCount();
    @endphp

    <style>
        .doctor-page {
            width: 100%;
        }

        .doctor-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 22px;
            border-radius: 18px;
            background: linear-gradient(135deg, #78350f 0%, #b45309 50%, #d97706 100%);
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(120, 53, 15, 0.18);
        }

        .doctor-header-title {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.2;
        }

        .doctor-header-subtitle {
            margin-top: 6px;
            font-size: 13px;
            opacity: 0.9;
        }

        .doctor-room {
            padding: 10px 14px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }

        .doctor-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-top: 20px;
        }

        .doctor-stat {
            padding: 18px;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        }

        .doctor-stat-label {
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .doctor-stat-value {
            margin-top: 7px;
            font-size: 28px;
            font-weight: 900;
            color: #0f172a;
        }

        .doctor-table-card {
            margin-top: 20px;
            overflow: hidden;
            border-radius: 18px;
            background: #ffffff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
        }

        .doctor-table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 22px;
            border-bottom: 1px solid #e2e8f0;
        }

        .doctor-table-title {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
        }

        .doctor-table-subtitle {
            margin-top: 3px;
            font-size: 12px;
            color: #64748b;
        }

        .doctor-table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .doctor-table {
            width: 100%;
            min-width: 950px;
            border-collapse: collapse;
        }

        .doctor-table th {
            padding: 13px 16px;
            background: #f8fafc;
            color: #64748b;
            text-align: left;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .doctor-table td {
            padding: 15px 16px;
            border-top: 1px solid #eef2f7;
            color: #334155;
            font-size: 12px;
            vertical-align: middle;
        }

        .doctor-time {
            font-weight: 900;
            color: #0f172a;
            white-space: nowrap;
        }

        .doctor-patient {
            font-weight: 800;
            color: #0f172a;
        }

        .doctor-small {
            margin-top: 3px;
            color: #64748b;
            font-size: 11px;
        }

        .doctor-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 900;
            white-space: nowrap;
        }

        .badge-paid {
            background: #dcfce7;
            color: #166534;
        }

        .badge-unpaid {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-waiting {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-created {
            background: #ede9fe;
            color: #6d28d9;
        }

        .doctor-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border: 0;
            border-radius: 9px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        .doctor-start {
            background: #059669;
            color: #ffffff;
        }

        .doctor-view {
            background: #7c3aed;
            color: #ffffff;
        }

        .doctor-disabled {
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
        }

        .doctor-empty {
            padding: 48px 20px;
            text-align: center;
        }

        .doctor-empty-title {
            font-size: 16px;
            font-weight: 800;
            color: #334155;
        }

        .doctor-empty-text {
            margin-top: 5px;
            font-size: 12px;
            color: #64748b;
        }

        .doctor-warning {
            padding: 18px;
            border-radius: 14px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
        }

        .doctor-warning-title {
            font-weight: 800;
            font-size: 15px;
        }

        .doctor-warning-text {
            margin-top: 4px;
            font-size: 12px;
        }

        @media (max-width: 900px) {
            .doctor-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .doctor-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 600px) {
            .doctor-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="doctor-page">

        @if (! $doctor)

            <div class="doctor-warning">
                <div class="doctor-warning-title">
                    Doctor account not linked
                </div>

                <div class="doctor-warning-text">
                    Your login email is not linked with an active doctor record.
                </div>
            </div>

        @else

            <div class="doctor-header">

                <div>
                    <div class="doctor-header-title">
                        My Appointments
                    </div>

                    <div class="doctor-header-subtitle">
                        Dr. {{ $doctor->name }} — Today's confirmed appointments
                    </div>
                </div>

                <div class="doctor-room">
                    @if ($doctor->room_number)
                        Consulting Room {{ $doctor->room_number }}
                    @else
                        Room Not Assigned
                    @endif
                </div>

            </div>

            <div class="doctor-stats">

                <div class="doctor-stat">
                    <div class="doctor-stat-label">
                        Today's Patients
                    </div>

                    <div class="doctor-stat-value">
                        {{ $todayCount }}
                    </div>
                </div>

                <div class="doctor-stat">
                    <div class="doctor-stat-label">
                        Waiting
                    </div>

                    <div class="doctor-stat-value">
                        {{ $waitingCount }}
                    </div>
                </div>

                <div class="doctor-stat">
                    <div class="doctor-stat-label">
                        Paid
                    </div>

                    <div class="doctor-stat-value">
                        {{ $paidCount }}
                    </div>
                </div>

                <div class="doctor-stat">
                    <div class="doctor-stat-label">
                        Consultation Created
                    </div>

                    <div class="doctor-stat-value">
                        {{ $inConsultationCount }}
                    </div>
                </div>

            </div>

            <div class="doctor-table-card">

                <div class="doctor-table-header">

                    <div>
                        <div class="doctor-table-title">
                            Today's Appointments
                        </div>

                        <div class="doctor-table-subtitle">
                            Only confirmed appointments assigned to this doctor
                        </div>
                    </div>

                </div>

                @if ($appointments->isEmpty())

                    <div class="doctor-empty">

                        <div class="doctor-empty-title">
                            No appointments for today
                        </div>

                        <div class="doctor-empty-text">
                            There are no confirmed appointments assigned to you today.
                        </div>

                    </div>

                @else

                    <div class="doctor-table-wrap">

                        <table class="doctor-table">

                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Patient</th>
                                    <th>Type</th>
                                    <th>Room</th>
                                    <th>Source</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($appointments as $appointment)

                                    @php
                                        $patientType =
                                            $appointment->patient_type
                                            ?? $appointment->patient?->patient_type
                                            ?? 'Local';

                                        $isPaid =
                                            $appointment->payment_status === 'paid';

                                        $hasConsultation =
                                            filled($appointment->consultation_id);
                                    @endphp

                                    <tr>

                                        <td>
                                            <div class="doctor-time">
                                                {{ $appointment->appointment_date?->format('h:i A') }}
                                            </div>
                                        </td>

                                        <td>

                                            <div class="doctor-patient">
                                                {{ $appointment->patient?->full_name
                                                    ?? $appointment->full_name
                                                    ?? 'Unknown Patient' }}
                                            </div>

                                            @if ($appointment->booking_number)
                                                <div class="doctor-small">
                                                    {{ $appointment->booking_number }}
                                                </div>
                                            @endif

                                        </td>

                                        <td>
                                            <div class="doctor-small">
                                                {{ $patientType }}
                                            </div>
                                        </td>

                                        <td>

                                            <div class="doctor-small">
                                                @if ($appointment->doctor?->room_number)
                                                    Room {{ $appointment->doctor->room_number }}
                                                @else
                                                    —
                                                @endif
                                            </div>

                                        </td>

                                        <td>

                                            <span class="doctor-badge badge-waiting">
                                                {{ $appointment->source ?? 'Online' }}
                                            </span>

                                        </td>

                                        <td>

                                            @if ($isPaid)

                                                <span class="doctor-badge badge-paid">
                                                    Paid
                                                </span>

                                            @else

                                                <span class="doctor-badge badge-unpaid">
                                                    Payment Pending
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            @if ($hasConsultation)

                                                <span class="doctor-badge badge-created">
                                                    Consultation Created
                                                </span>

                                            @elseif ($isPaid)

                                                <span class="doctor-badge badge-waiting">
                                                    Waiting
                                                </span>

                                            @else

                                                <span class="doctor-badge badge-unpaid">
                                                    Payment Pending
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            @if ($hasConsultation)

                                                <button
                                                    type="button"
                                                    wire:click="viewConsultation({{ $appointment->id }})"
                                                    class="doctor-action doctor-view"
                                                >
                                                    View Consultation
                                                </button>

                                            @elseif ($isPaid)

                                                <button
                                                    type="button"
                                                    wire:click="startConsultation({{ $appointment->id }})"
                                                    class="doctor-action doctor-start"
                                                >
                                                    Start Consultation
                                                </button>

                                            @else

                                                <span class="doctor-disabled">
                                                    Waiting for Payment
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @endif

            </div>

        @endif

    </div>

</x-filament-panels::page>
