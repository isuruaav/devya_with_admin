<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffAttendanceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StaffAttendanceController extends Controller
{
    /*

    |--------------------------------------------------------------------------

    | Manual Attendance Request

    |--------------------------------------------------------------------------

    | NIC / Passport වලින් attendance request එකක් submit කරයි.

    | මෙය direct attendance record නොකර Pending request එකක් create කරයි.

    */

    public function manual(Request $request): JsonResponse
    {

        $this->authorizeStaffAccess();

        $validated = $request->validate([

            'identifier' => [

                'required',

                'string',

                'max:100',

            ],

        ]);

        $identifier = trim($validated['identifier']);

        $result = DB::transaction(function () use ($identifier, $request): array {

            $staff = Staff::query()

                ->where('nic_passport', $identifier)

                ->where('status', 'Active')

                ->lockForUpdate()

                ->first();

            if (! $staff) {

                return [

                    'success' => false,

                    'message' => 'Invalid or inactive staff NIC / Passport number.',

                ];

            }

            $now = now();

            $today = $now->toDateString();

            /*

            |--------------------------------------------------------------------------

            | Determine IN / OUT

            |--------------------------------------------------------------------------

            | Open attendance session තිබේ නම් OUT request.

            | Open attendance session නැත්නම් IN request.

            */

            $openAttendance = StaffAttendance::query()

                ->where('staff_id', $staff->id)

                ->whereDate('attendance_date', $today)

                ->whereNull('check_out')

                ->latest('check_in')

                ->lockForUpdate()

                ->first();

            $attendanceType = $openAttendance ? 'OUT' : 'IN';

            /*

            |--------------------------------------------------------------------------

            | Duplicate Pending Request Protection

            |--------------------------------------------------------------------------

            */

            $existingRequest = StaffAttendanceRequest::query()

                ->where('staff_id', $staff->id)

                ->whereDate('attendance_date', $today)

                ->where('attendance_type', $attendanceType)

                ->where('status', 'Pending')

                ->first();

            if ($existingRequest) {

                return [

                    'success' => false,

                    'message' => 'A pending attendance request already exists for this staff member.',

                ];

            }

            /*

            |--------------------------------------------------------------------------

            | Create Pending Request

            |--------------------------------------------------------------------------

            */

            $attendanceRequest = StaffAttendanceRequest::create([

                'staff_id' => $staff->id,

                'attendance_date' => $today,

                'requested_at' => $now,

                'attendance_type' => $attendanceType,

                'method' => 'NIC',

                'status' => 'Pending',

                'device_ip' => $request->ip(),

                'notes' => 'Manual attendance request submitted using NIC / Passport.',

            ]);

            return [

                'success' => true,

                'status' => 'Pending',

                'request_id' => $attendanceRequest->id,

                'staff_name' => $staff->full_name,

                'staff_code' => $staff->staff_code,

                'attendance_type' => $attendanceType,

                'requested_time' => $now->format('h:i A'),

                'message' => 'Attendance request submitted for Admin approval.',

            ];

        });

        return response()->json($result);

    }

    /*

    |--------------------------------------------------------------------------

    | QR Attendance

    |--------------------------------------------------------------------------

    | QR scan එකෙන් direct IN / OUT record කරයි.

    */

    public function scan(Request $request): JsonResponse
    {

        $this->authorizeStaffAccess();

        $validated = $request->validate([

            'token' => [

                'required',

                'string',

                'size:64',

            ],

        ]);

        $token = trim($validated['token']);

        $result = DB::transaction(function () use ($token): array {

            $staff = Staff::query()

                ->where('qr_token', $token)

                ->where('status', 'Active')

                ->lockForUpdate()

                ->first();

            if (! $staff) {

                return [

                    'success' => false,

                    'message' => 'Invalid or inactive Staff QR Code.',

                ];

            }

            $now = now();

            $today = $now->toDateString();

            /*

            |--------------------------------------------------------------------------

            | Find Latest Open Attendance

            |--------------------------------------------------------------------------

            */

            $openAttendance = StaffAttendance::query()

                ->where('staff_id', $staff->id)

                ->whereDate('attendance_date', $today)

                ->whereNull('check_out')

                ->latest('check_in')

                ->lockForUpdate()

                ->first();

            /*

            |--------------------------------------------------------------------------

            | No Open Session = IN

            |--------------------------------------------------------------------------

            */

            if (! $openAttendance) {

                $attendance = StaffAttendance::create([

                    'staff_id' => $staff->id,

                    'attendance_date' => $today,

                    'check_in' => $now,

                    'check_in_method' => 'QR',

                    'status' => 'Present',

                ]);

                return [

                    'success' => true,

                    'action' => 'IN',

                    'type' => 'IN',

                    'staff_name' => $staff->full_name,

                    'staff_code' => $staff->staff_code,

                    'time' => $now->format('h:i A'),

                    'message' => 'Attendance IN recorded successfully.',

                ];

            }

            /*

            |--------------------------------------------------------------------------

            | Open Session Exists = OUT

            |--------------------------------------------------------------------------

            */

            $openAttendance->update([

                'check_out' => $now,

                'check_out_method' => 'QR',

            ]);

            return [

                'success' => true,

                'action' => 'OUT',

                'type' => 'OUT',

                'staff_name' => $staff->full_name,

                'staff_code' => $staff->staff_code,

                'time' => $now->format('h:i A'),

                'message' => 'Attendance OUT recorded successfully.',

            ];

        });

        return response()->json($result);

    }

    /*

    |--------------------------------------------------------------------------

    | Approve Manual Attendance Request

    |--------------------------------------------------------------------------

    | Admin / Super Admin approval කිරීමෙන් පසුව actual attendance record වේ.

    */

    public function approve(

        Request $request,

        StaffAttendanceRequest $attendanceRequest

    ): JsonResponse {

        $this->authorizeStaffAccess();

        $result = DB::transaction(function () use ($attendanceRequest, $request): array {

            $attendanceRequest = StaffAttendanceRequest::query()

                ->with('staff')

                ->whereKey($attendanceRequest->id)

                ->lockForUpdate()

                ->first();

            if (! $attendanceRequest) {

                return [

                    'success' => false,

                    'message' => 'Attendance request not found.',

                ];

            }

            if ($attendanceRequest->status !== 'Pending') {

                return [

                    'success' => false,

                    'message' => 'This attendance request has already been processed.',

                ];

            }

            $staff = Staff::query()

                ->whereKey($attendanceRequest->staff_id)

                ->where('status', 'Active')

                ->lockForUpdate()

                ->first();

            if (! $staff) {

                return [

                    'success' => false,

                    'message' => 'Staff member is inactive or unavailable.',

                ];

            }

            $attendanceDate = Carbon::parse(
                (string) $attendanceRequest->attendance_date
            )->toDateString();

            /*

            |--------------------------------------------------------------------------

            | Approve IN Request

            |--------------------------------------------------------------------------

            */

            if ($attendanceRequest->attendance_type === 'IN') {

                $openAttendance = StaffAttendance::query()

                    ->where('staff_id', $staff->id)

                    ->whereDate('attendance_date', $attendanceDate)

                    ->whereNull('check_out')

                    ->latest('check_in')

                    ->lockForUpdate()

                    ->first();

                if ($openAttendance) {

                    return [

                        'success' => false,

                        'message' => 'This staff member already has an open attendance session.',

                    ];

                }

                StaffAttendance::create([

                    'staff_id' => $staff->id,

                    'attendance_date' => $attendanceDate,

                    'check_in' => $attendanceRequest->requested_at,

                    'check_in_method' => 'NIC',

                    'status' => 'Present',

                    'notes' => 'Approved manual attendance request #'.$attendanceRequest->id,

                ]);

            }

            /*

            |--------------------------------------------------------------------------

            | Approve OUT Request

            |--------------------------------------------------------------------------

            */

            if ($attendanceRequest->attendance_type === 'OUT') {

                $openAttendance = StaffAttendance::query()

                    ->where('staff_id', $staff->id)

                    ->whereDate('attendance_date', $attendanceDate)

                    ->whereNull('check_out')

                    ->latest('check_in')

                    ->lockForUpdate()

                    ->first();

                if (! $openAttendance) {

                    return [

                        'success' => false,

                        'message' => 'No open attendance session was found for this OUT request.',

                    ];

                }

                $openAttendance->update([

                    'check_out' => $attendanceRequest->requested_at,

                    'check_out_method' => 'NIC',

                    'notes' => 'Approved manual attendance request #'.$attendanceRequest->id,

                ]);

            }

            /*

            |--------------------------------------------------------------------------

            | Update Request Status

            |--------------------------------------------------------------------------

            */

            $attendanceRequest->update([

                'status' => 'Approved',

                'approved_by' => $request->user()->id,

                'approved_at' => now(),

            ]);

            return [

                'success' => true,

                'message' => 'Attendance request approved successfully.',

                'staff_name' => $staff->full_name,

                'attendance_type' => $attendanceRequest->attendance_type,

            ];

        });

        return response()->json($result);

    }

    /*

    |--------------------------------------------------------------------------

    | Reject Manual Attendance Request

    |--------------------------------------------------------------------------

    */

    public function reject(

        Request $request,

        StaffAttendanceRequest $attendanceRequest

    ): JsonResponse {

        $this->authorizeStaffAccess();

        $validated = $request->validate([

            'rejection_reason' => [

                'required',

                'string',

                'max:1000',

            ],

        ]);

        return DB::transaction(function () use ($attendanceRequest, $request, $validated): JsonResponse {

            $pending = StaffAttendanceRequest::query()

                ->whereKey($attendanceRequest->id)

                ->where('status', 'Pending')

                ->lockForUpdate()

                ->first();

            if (! $pending) {

                return response()->json(['success' => false, 'message' => 'Pending attendance request not found.'], 404);

            }

            $pending->update([

                'status' => 'Rejected',

                'approved_by' => $request->user()->id,

                'approved_at' => now(),

                'rejection_reason' => $validated['rejection_reason'],

            ]);

            return response()->json(['success' => true, 'message' => 'Attendance request rejected successfully.']);

        });

    }

    private function authorizeStaffAccess(): void
    {

        abort_unless(auth()->user()?->canAccessModule('staff_management') ?? false, 403);

    }
}
