<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\Concerns\InteractsWithPermissions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use InteractsWithPermissions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpPermissions();
    }

    public function test_pharmacy_role_is_available_to_user_administrators(): void
    {
        $roles = User::availableRolesFor($this->userWithRole('admin'));

        $this->assertSame('Pharmacy', $roles['pharmacy']);
        $this->assertArrayNotHasKey('super_admin', $roles);
    }

    public function test_pharmacy_role_is_limited_to_pharmacy_bills_and_stock_modules(): void
    {
        $user = $this->userWithRole('pharmacy');

        $this->assertTrue($user->canAccessModule('pharmacy_bills'));
        $this->assertTrue($user->canAccessModule('medicines'));
        $this->assertTrue($user->canAccessModule('stock'));
        $this->assertFalse($user->canAccessModule('billing'));
        $this->assertFalse($user->canAccessModule('patients'));
        $this->assertFalse($user->canAccessModule('reports'));
    }
}
