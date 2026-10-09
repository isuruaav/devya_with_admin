<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('users')->whereNotIn('role', array_keys(User::ROLES))->exists()) {
            throw new RuntimeException('Unknown user roles found. Map them before migrating permissions.');
        }

        DB::transaction(function (): void {
            app(RolesAndPermissionsSeeder::class)->run();

            User::query()->orderBy('id')->chunkById(100, function ($users): void {
                foreach ($users as $user) {
                    $user->syncRoles($user->getRawOriginal('role'));
                }
            });
        });

        // Retain the original assignment for rollback; Spatie is now authoritative.
        Schema::table('users', function (Blueprint $table): void {
            $table->renameColumn('role', 'legacy_role');
        });

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        User::query()->with('roles')->orderBy('id')->chunkById(100, function ($users): void {
            foreach ($users as $user) {
                $role = $user->getRoleNames()->first();

                if ($role !== null) {
                    DB::table('users')->where('id', $user->id)->update(['legacy_role' => $role]);
                }
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->renameColumn('legacy_role', 'role');
        });
    }
};
