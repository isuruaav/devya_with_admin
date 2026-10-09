<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Reception',
                'code' => 'REC',
                'description' => 'Patient registration, appointments and reception services.',
            ],
            [
                'name' => 'OPD',
                'code' => 'OPD',
                'description' => 'Outpatient Department and consultation services.',
            ],
            [
                'name' => 'SPA',
                'code' => 'SPA',
                'description' => 'Ayurvedic spa and wellness services.',
            ],
            [
                'name' => 'Saloon',
                'code' => 'SAL',
                'description' => 'Ayurvedic beauty and saloon services.',
            ],
            [
                'name' => 'Pharmacy',
                'code' => 'PHA',
                'description' => 'Medicine dispensing and pharmacy operations.',
            ],
            [
                'name' => 'Administration',
                'code' => 'ADM',
                'description' => 'Administrative and management operations.',
            ],
            [
                'name' => 'Accounts',
                'code' => 'ACC',
                'description' => 'Finance, billing and accounting operations.',
            ],
            [
                'name' => 'IT',
                'code' => 'IT',
                'description' => 'System, network and technical support.',
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['code' => $department['code']],
                [
                    'name' => $department['name'],
                    'description' => $department['description'],
                    'status' => true,
                ]
            );
        }
    }
}
