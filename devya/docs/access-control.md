# Access Control

Access uses Spatie Laravel Permission with the `web` guard. The existing roles
are `super_admin`, `admin`, `reception`, `opd`, `pharmacy`, `salon`, and `doctor`.
Users keep one role through the Administration > Users form. Super Admin can
edit role permissions under Administration > Roles.

## Deployment

```sh
composer install
php artisan migrate
php artisan permission:cache-reset
```

The migration copies existing role assignments into Spatie's tables without
changing passwords. The old `users.role` column becomes `users.legacy_role` for
rollback only. Changing that column does not change access.

Default permissions are defined in `config/access.php`. Running
`php artisan db:seed --class=RolesAndPermissionsSeeder` resets the seven roles
to those defaults, including any permission changes made in the Roles page.
Do not rerun that seeder to preserve customized permissions.

## Application Code

Use `$user->syncRoles('reception')` to replace a user's role, `hasRole()` or
`hasAnyRole()` for role-specific workflows, and `can('access.patients')` or
`canAccessModule('patients')` for module authorization. Do not set a `role`
attribute on User. Tests can use `User::factory()->withRole('reception')`.

Super Admin has full access through Laravel's Gate. Disabled accounts cannot
access permissions or the panel. Doctor accounts require an active linked
doctor; legacy doctor accounts can also match an active doctor by email.
Admin can view and edit ordinary users by default, but cannot create, delete,
enable accounts, change role permissions, or edit a Super Admin account.
