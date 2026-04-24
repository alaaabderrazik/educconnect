<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'professor']);
        Role::create(['name' => 'student']);
    }

    public function test_admin_can_access_admin_dashboard_but_not_student(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);

        $response = $this->actingAs($admin)->get('/dashboard/admin');
        $response->assertStatus(200);

        $response = $this->actingAs($admin)->get('/dashboard/student');
        $response->assertStatus(403);
    }

    public function test_student_can_access_student_dashboard_but_not_admin(): void
    {
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'student')->first()->id,
        ]);

        $response = $this->actingAs($student)->get('/dashboard/student');
        $response->assertStatus(200);

        $response = $this->actingAs($student)->get('/dashboard/admin');
        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_dashboard_root_redirects_to_correct_role_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'admin')->first()->id,
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertRedirect('/dashboard/admin');

        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@test.com',
            'password' => bcrypt('password'),
            'role_id' => Role::where('name', 'student')->first()->id,
        ]);

        $response = $this->actingAs($student)->get('/dashboard');
        $response->assertRedirect('/dashboard/student');
    }
}
