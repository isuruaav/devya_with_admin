<?php

namespace App\Filament\Pages;

use App\Models\Doctor;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffAttendanceRequest;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StaffAttendanceRequests extends Page
{
    protected string $view =
        'filament.pages.staff-attendance-requests';

    protected static ?string $navigationLabel =
        'Attendance Requests';

    protected static ?string $title =
        'Staff Attendance Requests';

    protected static ?int $navigationSort = 3;

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): ?string
    {
        return 'STAFF MANAGEMENT';
    }

    protected static function isDoctorUser(): bool
    {
        $user = auth()->user();

        if (! $user || blank($user->email)) {
            return false;
        }

        return Doctor::query()
            ->where('is_active', true)
            ->where('email', $user->email)
            ->exists();
    }

    protected static function hasStaffManagementAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->canAccessModule('staff_management');
    }

    public static function canAccess(): bool
    {
        return static::hasStaffManagementAccess()
            && ! static::isDoctorUser();
    }

    public function approve(int $requestId): void
    {
        if (! static::canAccess()) {
            abort(403);
        }

        $result = DB::transaction(
            function () use ($requestId): array {
                $attendanceRequest =
                    StaffAttendanceRequest::query()
                        ->with('staff')
                        ->lockForUpdate()
                        ->find($requestId);

                if (! $attendanceRequest) {
                    return [
                        'success' => false,
                        'message' => 'Attendance request not found.',
                    ];
                }

                if (
                    (string) $attendanceRequest->getAttribute('status')
                    !== 'Pending'
                ) {
                    return [
                        'success' => false,
                        'message' => 'This attendance request has already been processed.',
                    ];
                }

                $staff = $attendanceRequest->getRelationValue('staff');

                if (! $staff instanceof Staff) {
                    return [
                        'success' => false,
                        'message' => 'Staff member was not found.',
                    ];
                }

                if (
                    (string) $staff->getAttribute('status')
                    !== 'Active'
                ) {
                    return [
                        'success' => false,
                        'message' => 'This staff member is inactive.',
                    ];
                }

                $attendanceDate = Carbon::parse(
                    (string) $attendanceRequest->getAttribute('attendance_date')
                )->toDateString();

                $openAttendance =
                    StaffAttendance::query()
                        ->where(
                            'staff_id',
                            $staff->getKey()
                        )
                        ->where(
                            'attendance_date',
                            $attendanceDate
                        )
                        ->whereNull('check_out')
                        ->latest('check_in')
                        ->lockForUpdate()
                        ->first();

                $attendanceType = (string) $attendanceRequest
                    ->getAttribute('attendance_type');

                if ($attendanceType === 'IN') {
                    if ($openAttendance) {
                        return [
                            'success' => false,
                            'message' => 'This staff member already has an open attendance session.',
                        ];
                    }

                    StaffAttendance::create([
                        'staff_id' => $staff->getKey(),
                        'attendance_date' => $attendanceDate,
                        'check_in' => $attendanceRequest->getAttribute('requested_at'),
                        'check_in_method' => 'NIC',
                        'status' => 'Present',
                        'notes' => 'Approved manual attendance request #'
                            .$attendanceRequest->getKey(),
                    ]);
                }

                if ($attendanceType === 'OUT') {
                    if (! $openAttendance) {
                        return [
                            'success' => false,
                            'message' => 'No open attendance session was found for this OUT request.',
                        ];
                    }

                    $openAttendance->update([
                        'check_out' => $attendanceRequest->getAttribute('requested_at'),
                        'check_out_method' => 'NIC',
                        'notes' => 'Approved manual attendance request #'
                            .$attendanceRequest->getKey(),
                    ]);
                }

                $attendanceRequest->update([
                    'status' => 'Approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                return [
                    'success' => true,
                    'message' => (string) $staff->getAttribute('full_name')
                        .' attendance request approved successfully.',
                ];
            }
        );

        if ($result['success']) {
            Notification::make()
                ->title('Approved Successfully')
                ->body($result['message'])
                ->success()
                ->send();

            return;
        }

        Notification::make()
            ->title('Approval Failed')
            ->body($result['message'])
            ->danger()
            ->send();
    }

    public function reject(int $requestId): void
    {
        if (! static::canAccess()) {
            abort(403);
        }

        $attendanceRequest =
            StaffAttendanceRequest::query()
                ->whereKey($requestId)
                ->where('status', 'Pending')
                ->first();

        if (! $attendanceRequest) {
            Notification::make()
                ->title('Request Not Found')
                ->body(
                    'Pending attendance request was not found.'
                )
                ->danger()
                ->send();

            return;
        }

        $attendanceRequest->update([
            'status' => 'Rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => 'Rejected by administrator.',
        ]);

        Notification::make()
            ->title('Request Rejected')
            ->body(
                'The attendance request has been rejected successfully.'
            )
            ->warning()
            ->send();
    }
}
