<?php

return [
    'notification_email' => env('APPOINTMENT_NOTIFICATION_EMAIL', 'info@devyaceylon.lk'),
    'notification_whatsapp' => env('APPOINTMENT_NOTIFICATION_WHATSAPP', '94713423173'),
    'website_consultation_fee_lkr' => (float) env('WEBSITE_CONSULTATION_FEE_LKR', 3000),
    'website_booking_advance_lkr' => (float) env('WEBSITE_BOOKING_ADVANCE_LKR', 1500),
];
