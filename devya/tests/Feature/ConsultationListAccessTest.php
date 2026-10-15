<?php

namespace Tests\Feature;

use App\Filament\Resources\Consultations\ConsultationResource\Pages\ListConsultations;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Tests\Concerns\InteractsWithPermissions;
use Tests\TestCase;

class ConsultationListAccessTest extends TestCase
{
    use InteractsWithPermissions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpPermissions();
    }

    public function test_admin_with_email_matching_active_doctor_can_access_consultation_list(): void
    {
        $user = $this->userWithRole('admin');
        DB::table('doctors')->insert([
            'name' => 'Another Doctor',
            'email' => $user->email,
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $this->assertNull($user->getActiveDoctor());
        $this->assertTrue(ListConsultations::canAccess());
    }

    public function test_staff_with_consultations_permission_can_access_list(): void
    {
        $this->actingAs($this->userWithRole('reception'));

        $this->assertTrue(ListConsultations::canAccess());
    }

    public function test_staff_without_consultations_permission_cannot_access_list(): void
    {
        $this->actingAs($this->userWithRole('pharmacy'));

        $this->assertFalse(ListConsultations::canAccess());
    }

    public function test_reception_role_loses_access_if_consultations_permission_is_revoked(): void
    {
        $user = $this->userWithRole('reception');
        Role::findByName('reception', 'web')->revokePermissionTo('access.consultations');
        $user->unsetRelation('roles');
        $user->unsetRelation('permissions');
        $this->actingAs($user);

        $this->assertFalse(ListConsultations::canAccess());
    }

    public function test_doctor_uses_dedicated_dashboard_not_general_consultations_list(): void
    {
        $doctorId = DB::table('doctors')->insertGetId([
            'name' => 'Active Doctor',
            'email' => 'doctor-consultations@example.test',
            'is_active' => true,
        ]);
        $user = $this->userWithRole('doctor');
        $user->update(['doctor_id' => $doctorId]);
        $this->actingAs($user);

        $this->assertTrue($user->canAccessModule('consultations'));
        $this->assertFalse(ListConsultations::canAccess());
    }

    public function test_guest_cannot_access_consultations_list(): void
    {
        $this->assertFalse(ListConsultations::canAccess());
    }
}
