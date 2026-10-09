<?php

use App\Http\Controllers\BillingDocumentController;
use App\Http\Controllers\ConsultationReceiptController;
use App\Http\Controllers\OnlineAppointmentController;
use App\Http\Controllers\PharmacyBillPrintController;
use App\Http\Controllers\PharmacyBillReceiptController;
use App\Http\Controllers\PrivateDocumentController;
use App\Http\Controllers\PublicAdvertisementController;
use App\Http\Controllers\PublicQueueDisplayController;
use App\Http\Controllers\ReceptionBillPrintController;
use App\Http\Controllers\StaffAttendanceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/documents', [PrivateDocumentController::class, 'show'])
        ->middleware('signed')->name('documents.show');

    /*
    |--------------------------------------------------------------------------
    | Billing Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin/billing')
        ->name('billing.')
        ->group(function () {

            Route::get(
                '{consultation}/invoice',
                [BillingDocumentController::class, 'invoice']
            )->name('invoice');

            Route::get(
                '{consultation}/prescription',
                [BillingDocumentController::class, 'prescription']
            )->name('prescription');

            Route::post(
                '{consultation}/email',
                [BillingDocumentController::class, 'emailInvoice']
            )->name('email');

            Route::get(
                'appointment/{appointment}/invoice',
                [BillingDocumentController::class, 'appointmentInvoice']
            )->name('appointment.invoice');

            Route::get(
                'appointment/{appointment}/receipt',
                [BillingDocumentController::class, 'appointmentReceipt']
            )->name('appointment.receipt');

            Route::get(
                'appointment/{appointment}/print',
                [BillingDocumentController::class, 'appointmentBillPrint']
            )->name('appointment.print');

            Route::get(
                'appointment/{appointment}/receipt/print',
                [BillingDocumentController::class, 'appointmentReceiptPrint']
            )->name('appointment.receipt.print');
        });

    /*
    |--------------------------------------------------------------------------
    | Staff Attendance - QR Scan
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/staff-attendance/scan',
        [StaffAttendanceController::class, 'scan']
    )->name('staff-attendance.scan');

    /*
    |--------------------------------------------------------------------------
    | Staff Attendance - NIC / Passport Manual Request
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/staff-attendance/manual',
        [StaffAttendanceController::class, 'manual']
    )->name('staff-attendance.manual');

    /*
    |--------------------------------------------------------------------------
    | Staff Attendance - Approve Manual Request
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/staff-attendance/requests/{attendanceRequest}/approve',
        [StaffAttendanceController::class, 'approve']
    )->name('staff-attendance.requests.approve');

    /*
    |--------------------------------------------------------------------------
    | Staff Attendance - Reject Manual Request
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/staff-attendance/requests/{attendanceRequest}/reject',
        [StaffAttendanceController::class, 'reject']
    )->name('staff-attendance.requests.reject');

});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Public Queue Display
|--------------------------------------------------------------------------
|
| Main display page:
| /queue-display?room=Room%2002
|
*/

Route::get(
    '/queue-display',
    [PublicQueueDisplayController::class, 'index']
)->name('queue.public');

/*
|--------------------------------------------------------------------------
| Public Queue Display - AJAX / Auto Refresh Data
|--------------------------------------------------------------------------
|
| JavaScript calls this URL every 3 seconds.
|
*/

Route::get(
    '/queue-display/data',
    [PublicQueueDisplayController::class, 'data']
)->name('queue.public.data');

/*
|--------------------------------------------------------------------------
| Online Appointment
|--------------------------------------------------------------------------
*/

Route::get(
    '/online-appointment',
    [OnlineAppointmentController::class, 'create']
)->name('online-appointments.create');

Route::post(
    '/online-appointment',
    [OnlineAppointmentController::class, 'store']
)->middleware('throttle:public-appointments')->name('online-appointments.store');

Route::post(
    '/website-appointment',
    [OnlineAppointmentController::class, 'storeWebsite']
)->middleware('throttle:public-appointments')->name('website-appointments.store');

Route::get(
    '/online-appointment/{onlineAppointment}/success',
    [OnlineAppointmentController::class, 'success']
)->name('online-appointments.success');

/*
|--------------------------------------------------------------------------
| Temporary Consultation Bill Sharing
|--------------------------------------------------------------------------
*/

Route::get(
    '/shared/consultation-bills/{consultationBill}',
    [ConsultationReceiptController::class, 'shared']
)
    ->middleware('signed')
    ->name('consultation-bills.shared');

Route::get(
    '/shared/pharmacy-bills/{pharmacyBill}',
    [PharmacyBillReceiptController::class, 'shared']
)
    ->middleware('signed')
    ->name('pharmacy-bills.shared');

/*
|--------------------------------------------------------------------------
| Consultation Bills
|--------------------------------------------------------------------------
|
| Consultation Bill is automatically created from Consultation.
|
| No "New Consultation Bill" route is required.
|
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Print Consultation Bill
    |--------------------------------------------------------------------------
    |
    | Used before payment.
    |
    */

    Route::get(
        '/consultation-bills/{consultationBill}/bill',
        [ConsultationReceiptController::class, 'bill']
    )->name(
        'consultation-bills.bill'
    );

    /*
    |--------------------------------------------------------------------------
    | Print Consultation Receipt
    |--------------------------------------------------------------------------
    |
    | Used after payment.
    |
    */

    Route::get(
        '/consultation-bills/{consultationBill}/receipt',
        [ConsultationReceiptController::class, 'print']
    )->name(
        'consultation-bills.receipt'
    );

    Route::get(
        '/queue-display/advertisements',
        [PublicAdvertisementController::class, 'index']
    )->name('queue.public.advertisements');

    Route::get('/pharmacy-bills/{bill}/print', [PharmacyBillPrintController::class, 'print'])->name('pharmacy-bills.print');

    Route::get(
        '/pharmacy-bills/{pharmacyBill}/receipt',
        [PharmacyBillReceiptController::class, 'show']
    )->name('pharmacy-bills.receipt');

    Route::get(
        '/reception-bills/{receptionBill}/print/standard',
        [ReceptionBillPrintController::class, 'printStandard']
    )->name('reception-bills.print.standard');

    Route::get(
        '/reception-bills/{receptionBill}/print',
        [ReceptionBillPrintController::class, 'print']
    )->name('reception-bills.print');

});
