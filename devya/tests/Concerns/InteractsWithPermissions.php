<?php

namespace Tests\Concerns;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Filament\Facades\Filament;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait InteractsWithPermissions
{
    protected function setUpPermissions(): void
    {
        config(['app.url' => 'http://localhost']);
        $this->app['url']->forceRootUrl('http://localhost');

        (require database_path('migrations/0001_01_01_000000_create_users_table.php'))->up();
        Schema::table('users', function (Blueprint $table): void {
            $table->string('legacy_role')->default('reception');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('doctor_id')->nullable();
        });
        Schema::create('doctors', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
        });

        $migration = glob(database_path('migrations/*_create_permission_tables.php'))[0];
        (require $migration)->up();
        $this->seed(RolesAndPermissionsSeeder::class);

        Filament::setCurrentPanel(Filament::getPanel('admin'));
        Filament::bootCurrentPanel();
    }

    protected function userWithRole(string $role): User
    {
        return User::factory()->withRole($role)->create(['is_active' => true]);
    }
}
