<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StaffDepartment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Super Admin can list departments.
     */
    public function test_super_admin_can_access_departments_page()
    {
        // 1. Create Super Admin user
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@feeeasy.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Access index page
        $response = $this->actingAs($admin)
            ->get(route('departments.index'));

        $response->assertStatus(200);
        $response->assertViewIs('departments.index');
    }

    /**
     * Test Super Admin can create a global department.
     */
    public function test_super_admin_can_create_department_globally()
    {
        // 1. Create Super Admin user
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@feeeasy.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Post to store route
        $response = $this->actingAs($admin)
            ->post(route('departments.store'), [
                'name' => 'Artificial Intelligence',
            ]);

        // 3. Verify database and redirection
        $response->assertRedirect(route('departments.index'));
        $this->assertDatabaseHas('staff_departments', [
            'name' => 'Artificial Intelligence',
        ]);
    }

    /**
     * Test Super Admin can edit/update a department.
     */
    public function test_super_admin_can_update_department()
    {
        // 1. Create Super Admin user
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@feeeasy.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Create Department
        $dept = StaffDepartment::create([
            'name' => 'Physics Dept',
        ]);

        // 3. Put/Patch update route
        $response = $this->actingAs($admin)
            ->put(route('departments.update', $dept->id), [
                'name' => 'Advanced Physics',
            ]);

        // 4. Verify database update and redirection
        $response->assertRedirect(route('departments.index'));
        $this->assertDatabaseHas('staff_departments', [
            'id' => $dept->id,
            'name' => 'Advanced Physics',
        ]);
    }

    /**
     * Test Super Admin can delete a department.
     */
    public function test_super_admin_can_delete_department()
    {
        // 1. Create Super Admin user
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@feeeasy.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Create Department
        $dept = StaffDepartment::create([
            'name' => 'Chemistry Dept',
        ]);

        // 3. Delete route
        $response = $this->actingAs($admin)
            ->delete(route('departments.destroy', $dept->id));

        // 4. Verify database missing and redirection
        $response->assertRedirect(route('departments.index'));
        $this->assertDatabaseMissing('staff_departments', [
            'id' => $dept->id
        ]);
    }
}
