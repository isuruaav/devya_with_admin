<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            CountrySeeder::class,
            DepartmentSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'kapila',
            'email' => 'kapila.kde@gmail.com',
            'password' => Hash::make('kapila@raj'),
            'is_active' => true,
        ])->assignRole('super_admin');
    }
}
