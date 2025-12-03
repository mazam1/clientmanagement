<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_super_admin_has_all_permissions(): void
    {
        $user = User::where('email', 'superadmin@example.com')->first();

        $this->assertTrue($user->hasRole('Super Admin'));
        $this->assertTrue($user->can('manage-roles'));
        $this->assertTrue($user->can('delete-clients'));
        $this->assertTrue($user->can('view-dashboard'));
    }

    public function test_staff_cannot_create_clients(): void
    {
        $user = User::where('email', 'staff@example.com')->first();

        $this->assertTrue($user->hasRole('Staff'));
        $this->assertFalse($user->can('create-clients'));
        $this->assertTrue($user->can('view-clients'));
    }

    public function test_manager_can_edit_but_not_delete_clients(): void
    {
        $user = User::where('email', 'manager@example.com')->first();

        $this->assertTrue($user->can('edit-clients'));
        $this->assertFalse($user->can('delete-clients'));
    }

    public function test_admin_cannot_manage_roles(): void
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->assertTrue($user->can('manage-users'));
        $this->assertFalse($user->can('manage-roles'));
    }

    public function test_staff_redirected_from_protected_route(): void
    {
        $user = User::where('email', 'staff@example.com')->first();

        $response = $this->actingAs($user)->get('/admin-only');
        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_admin_route(): void
    {
        $user = User::where('email', 'superadmin@example.com')->first();

        $response = $this->actingAs($user)->get('/admin-only');
        $response->assertStatus(200);
    }

    public function test_staff_can_access_dashboard(): void
    {
        $user = User::where('email', 'staff@example.com')->first();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_manager_can_access_clients_test_route(): void
    {
        $user = User::where('email', 'manager@example.com')->first();

        $response = $this->actingAs($user)->get('/clients-test');
        $response->assertStatus(200);
        $response->assertSee('Clients Page');
    }

    public function test_admin_has_manage_users_permission(): void
    {
        $user = User::where('email', 'admin@example.com')->first();

        $this->assertTrue($user->hasRole('Admin'));
        $this->assertTrue($user->can('manage-users'));
        $this->assertTrue($user->can('view-settings'));
        $this->assertTrue($user->can('edit-settings'));
    }

    public function test_staff_has_limited_permissions(): void
    {
        $user = User::where('email', 'staff@example.com')->first();

        $this->assertTrue($user->can('view-dashboard'));
        $this->assertTrue($user->can('view-clients'));
        $this->assertTrue($user->can('view-sessions'));
        $this->assertFalse($user->can('view-invoices'));
        $this->assertFalse($user->can('view-reports'));
    }
}
