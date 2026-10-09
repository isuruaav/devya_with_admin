<?php

namespace Tests\Feature;

use App\Filament\Pages\QueueManagement;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\Concerns\InteractsWithPermissions;
use Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    use InteractsWithPermissions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpPermissions();
    }

    public static function roles(): array
    {
        return [
            'super admin' => ['super_admin', '*'],
            'admin' => ['admin', '*'],
            'reception' => ['reception', ['patients', 'consultations', 'billing', 'history', 'doctor_search', 'queue']],
            'OPD' => ['opd', ['patients', 'consultations', 'medicines', 'stock', 'history', 'queue']],
            'pharmacy' => ['pharmacy', ['pharmacy_bills', 'medicines', 'stock']],
            'salon' => ['salon', ['patients', 'consultations', 'treatments', 'billing', 'history']],
            'doctor' => ['doctor', ['consultations', 'history', 'queue']],
        ];
    }

    #[DataProvider('roles')]
    public function test_existing_module_access_is_preserved(string $role, array|string $allowed): void
    {
        $user = $this->userWithRole($role);

        foreach (array_keys(config('access.modules')) as $module) {
            $this->assertSame($allowed === '*' || in_array($module, $allowed, true), $user->canAccessModule($module), $role.': '.$module);
        }
    }

    public function test_role_permissions_and_direct_permissions_control_access(): void
    {
        $user = $this->userWithRole('reception');
        Role::findByName('reception', 'web')->revokePermissionTo('access.patients');
        $this->assertFalse($user->fresh()->canAccessModule('patients'));

        $user->givePermissionTo('access.patients');
        $this->assertTrue($user->fresh()->canAccessModule('patients'));

        $user->syncRoles('pharmacy');
        $user->revokePermissionTo('access.patients');
        $this->assertTrue($user->fresh()->canAccessModule('pharmacy_bills'));
        $this->assertFalse($user->fresh()->canAccessModule('billing'));
    }

    public function test_legacy_role_cannot_grant_access(): void
    {
        $user = $this->userWithRole('pharmacy');
        DB::table('users')->where('id', $user->id)->update(['legacy_role' => 'super_admin']);

        $this->assertFalse($user->fresh()->isSuperAdmin());
        $this->assertFalse($user->fresh()->canAccessModule('reports'));
    }

    public function test_inactive_and_unassigned_users_cannot_access_the_panel(): void
    {
        $user = $this->userWithRole('super_admin');
        $user->update(['is_active' => false]);
        $this->assertFalse($user->canAccessPanel(Filament::getPanel('admin')));
        $this->assertFalse($user->canAccessModule('patients'));

        $unassigned = User::factory()->create(['is_active' => true]);
        $this->assertFalse($unassigned->canAccessPanel(Filament::getPanel('admin')));
        $this->assertFalse($unassigned->canAccessModule('patients'));
    }

    public function test_admin_cannot_manage_roles_or_edit_super_admins(): void
    {
        $admin = $this->userWithRole('admin');
        $owner = $this->userWithRole('super_admin');
        $this->actingAs($admin);

        $this->assertTrue(UserResource::canAccess());
        $this->assertFalse(UserResource::canCreate());
        $this->assertFalse(UserResource::canEdit($owner));
        $this->assertFalse(UserResource::canDelete($owner));
        $this->assertFalse(RoleResource::canAccess());
    }

    public function test_doctor_role_uses_the_linked_doctor_and_not_an_admin_email_match(): void
    {
        $doctorId = DB::table('doctors')->insertGetId(['name' => 'Doctor Test', 'email' => 'doctor@example.test', 'is_active' => true]);
        $user = $this->userWithRole('doctor');
        $this->assertFalse($user->canAccessPanel(Filament::getPanel('admin')));
        $user->update(['doctor_id' => $doctorId]);
        $this->assertSame($doctorId, $user->getActiveDoctor()?->id);
        $this->assertTrue($user->canAccessPanel(Filament::getPanel('admin')));

        $admin = $this->userWithRole('admin');
        $admin->update(['email' => 'doctor@example.test']);
        $this->assertNull($admin->getActiveDoctor());
    }

    public function test_super_admin_can_create_a_user_with_a_spatie_role(): void
    {
        $this->actingAs($this->userWithRole('super_admin'));

        Livewire::test(CreateUser::class)
            ->assertStatus(200)
            ->fillForm([
                'name' => 'Reception Test',
                'email' => 'reception@example.test',
                'roles' => [Role::findByName('reception', 'web')->id],
                'is_active' => true,
                'password' => 'test-password',
                'password_confirmation' => 'test-password',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $user = User::query()->where('email', 'reception@example.test')->firstOrFail();
        $this->assertTrue($user->hasRole('reception'));
        $this->assertTrue($user->canAccessModule('billing'));
    }

    public function test_admin_cannot_promote_a_user_through_a_tampered_role_selection(): void
    {
        $user = $this->userWithRole('reception');
        $this->actingAs($this->userWithRole('admin'));

        Livewire::test(EditUser::class, ['record' => $user->getKey()])
            ->assertStatus(200)
            ->fillForm(['roles' => [Role::findByName('super_admin', 'web')->id]])
            ->call('save')
            ->assertHasFormErrors(['roles']);

        $this->assertTrue($user->fresh()->hasRole('reception'));
        $this->assertFalse($user->fresh()->isSuperAdmin());
    }

    public function test_admin_can_assign_an_allowed_role_without_changing_the_password(): void
    {
        $user = $this->userWithRole('reception');
        $passwordHash = $user->getAuthPassword();
        $this->actingAs($this->userWithRole('admin'));

        Livewire::test(EditUser::class, ['record' => $user->getKey()])
            ->fillForm(['roles' => [Role::findByName('pharmacy', 'web')->id]])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue($user->fresh()->hasRole('pharmacy'));
        $this->assertSame($passwordHash, $user->fresh()->getAuthPassword());
    }

    public function test_bulk_delete_preserves_the_current_user_and_super_admin_accounts(): void
    {
        $admin = $this->userWithRole('admin');
        $admin->givePermissionTo('users.delete');
        $owner = $this->userWithRole('super_admin');
        $reception = $this->userWithRole('reception');
        $this->actingAs($admin);

        Livewire::test(ListUsers::class)
            ->callTableBulkAction('delete', [$admin, $owner, $reception]);

        $this->assertModelExists($admin);
        $this->assertModelExists($owner);
        $this->assertModelMissing($reception);
    }

    public function test_revoking_queue_permission_removes_queue_page_access(): void
    {
        $user = $this->userWithRole('reception');
        $this->actingAs($user);
        $this->assertTrue(QueueManagement::canAccess());

        Role::findByName('reception', 'web')->revokePermissionTo('access.queue');
        $user->unsetRelation('roles');
        $this->assertFalse(QueueManagement::canAccess());
        $this->assertFalse(QueueManagement::shouldRegisterNavigation());
    }

    public function test_super_admin_can_edit_module_permissions_from_the_roles_page(): void
    {
        $user = $this->userWithRole('reception');
        $this->actingAs($this->userWithRole('super_admin'));

        Livewire::test(EditRole::class, ['record' => Role::findByName('reception', 'web')->id])
            ->assertStatus(200)
            ->fillForm(['permissions' => [Permission::findByName('access.patients', 'web')->id]])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue($user->fresh()->canAccessModule('patients'));
        $this->assertFalse($user->fresh()->canAccessModule('billing'));
    }

    public function test_migration_preserves_assignments_and_rollback_preserves_updated_roles(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->renameColumn('legacy_role', 'role'));
        $user = User::factory()->create(['is_active' => true]);
        DB::table('users')->where('id', $user->id)->update(['role' => 'super_admin']);
        $passwordHash = $user->getAuthPassword();
        $migration = require database_path('migrations/2026_10_07_235900_migrate_user_roles_to_spatie.php');
        $migration->up();

        $this->assertTrue($user->fresh()->hasRole('super_admin'));
        $this->assertSame($passwordHash, $user->fresh()->getAuthPassword());
        $this->assertFalse(Schema::hasColumn('users', 'role'));

        $user->syncRoles('doctor');
        $migration->down();
        $this->assertSame('doctor', DB::table('users')->where('id', $user->id)->value('role'));
    }
}
