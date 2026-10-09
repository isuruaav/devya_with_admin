<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $modules = array_keys(config('access.modules'));
        $modulePermissions = array_map(fn (string $module): string => 'access.'.$module, $modules);
        $userPermissions = array_keys(config('access.user_permissions'));

        foreach ([...$modulePermissions, ...$userPermissions] as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        foreach (config('access.role_modules') as $name => $allowedModules) {
            $permissions = array_map(
                fn (string $module): string => 'access.'.$module,
                $allowedModules === '*' ? $modules : $allowedModules,
            );

            if ($name === 'super_admin') {
                $permissions = [...$permissions, ...$userPermissions];
            } elseif ($name === 'admin') {
                $permissions = [...$permissions, 'users.view', 'users.edit'];
            }

            Role::findOrCreate($name, 'web')->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
