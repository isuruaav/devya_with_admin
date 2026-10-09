<?php

return [
    'modules' => [
        'patients' => 'Patients',
        'consultations' => 'Consultations / Appointments',
        'billing' => 'Reception Billing',
        'history' => 'Patient History',
        'doctor_search' => 'Doctor Availability',
        'queue' => 'Queue',
        'medicines' => 'Medicines',
        'stock' => 'Stock',
        'pharmacy_bills' => 'Pharmacy Billing',
        'treatments' => 'Treatments',
        'reports' => 'Reports',
        'countries' => 'Countries',
        'doctors' => 'Doctors',
        'expenses' => 'Expenses',
        'staff_management' => 'Staff Management',
    ],

    'role_modules' => [
        'super_admin' => '*',
        'admin' => '*',
        'reception' => ['patients', 'consultations', 'billing', 'history', 'doctor_search', 'queue'],
        'opd' => ['patients', 'consultations', 'medicines', 'stock', 'history', 'queue'],
        'pharmacy' => ['pharmacy_bills', 'medicines', 'stock'],
        'salon' => ['patients', 'consultations', 'treatments', 'billing', 'history'],
        'doctor' => ['consultations', 'history', 'queue'],
    ],

    'user_permissions' => [
        'users.view' => 'View Users',
        'users.edit' => 'Edit Users',
        'users.create' => 'Create Users',
        'users.delete' => 'Delete Users',
        'users.enable' => 'Enable / Disable Users',
        'roles.manage' => 'Manage Role Permissions',
    ],
];
