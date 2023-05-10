<?php

namespace Tests\Feature;

use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;

class DeptSubdeptTest extends TestCase
{
    public function test_auth_dept_get_subdept_dataTables()
    {
        $dept = User::where('username', 'wawanhabibi')->first();

        $response = $this->actingAs($dept)->getJson('/dept/sub-departments');
        $response->assertStatus(200);
    }

    public function test_auth_dept_store_subdept()
    {
        $faker = Faker::create();
        $dept = User::where('username', 'wawanhabibi')->first();

        $response = $this->actingAs($dept)->postJson('/dept/sub-departments', [
            'name' => $faker->jobTitle()
        ]);
        $response->assertStatus(200);
    }

    public function test_auth_dept_show_subdept()
    {
        $dept = User::where('username', 'wawanhabibi')->first();
        $subdept = SubDepartment::latest()->first();

        $response = $this->actingAs($dept)->getJson('/dept/sub-departments/' . $subdept->id . '/edit');
        $response->assertStatus(200);
    }

    public function test_auth_dept_update_subdept()
    {
        $faker = Faker::create();
        $dept = User::where('username', 'wawanhabibi')->first();
        $subdept = SubDepartment::latest()->first();

        $response = $this->actingAs($dept)->patchJson('/dept/sub-departments/' . $subdept->id, [
            'name' => $faker->jobTitle()
        ]);
        $response->assertStatus(200);
    }

    public function test_auth_dept_delete_subdept()
    {
        $dept = User::where('username', 'wawanhabibi')->first();
        $subdept = SubDepartment::latest()->first();

        $response = $this->actingAs($dept)->deleteJson('/dept/sub-departments/' . $subdept->id);
        $response->assertStatus(200);
    }
}
