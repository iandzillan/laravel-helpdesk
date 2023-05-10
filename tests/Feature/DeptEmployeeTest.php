<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;

class DeptEmployeeTest extends TestCase
{
    public function test_auth_dept_get_new_employee_form()
    {
        $dept = User::where('username', 'wawanhabibi')->first();

        $response = $this->actingAs($dept)->getJson('dept/new-employees');
        $response->assertStatus(200);
    }

    public function test_auth_dept_get_select_subdept_form()
    {
        $dept = User::where('username', 'wawanhabibi')->first();

        $response = $this->actingAs($dept)->getJson('dept/new-employees/subdept');
        $response->assertStatus(200);
    }

    public function test_auth_dept_get_select_position_form()
    {
        $dept = User::where('username', 'wawanhabibi')->first();

        $response = $this->actingAs($dept)->getJson('dept/new-employees/position');
        $response->assertStatus(200);
    }

    public function test_auth_dept_store_new_employee()
    {
        $faker = Faker::create('id_ID');
        $dept = User::where('username', 'wawanhabibi')->first();
        $employee = Employee::latest()->first();

        $response = $this->actingAs($dept)->postJson('dept/employees', [
            'nik'               => $employee->nik + 1,
            'name'              => $faker->firstName() . ' ' . $faker->lastName(),
            'sub_department_id' => 14,
            'position'          => 'Team Member'
        ]);
        $response->assertStatus(200);
    }

    public function test_auth_dept_get_employee_dataTables()
    {
        $dept = User::where('username', 'wawanhabibi')->first();

        $response = $this->actingAs($dept)->getJson('/dept/employees');
        $response->assertStatus(200);
    }

    public function test_auth_dept_edit_employee_form()
    {
        $dept = User::where('username', 'wawanhabibi')->first();
        $employee = Employee::latest()->first();

        $response = $this->actingAs($dept)->get('/dept/employees/' . $employee->nik . '/edit');
        $response->assertStatus(200);
    }

    public function test_auth_dept_update_employee()
    {
        $faker = Faker::create('id_ID');
        $dept = User::where('username', 'wawanhabibi')->first();
        $employee = Employee::latest()->first();

        $response = $this->actingAs($dept)->patchJson('/dept/employees/' . $employee, [
            'nik'               => $employee->nik,
            'name'              => $faker->firstName() . ' ' . $faker->lastName(),
            'position'          => $employee->position,
            'sub_department_id' => $employee->sub_department_id,
        ]);
        $response->assertStatus(200);
    }

    public function test_auth_dept_delete_employee()
    {
        $dept = User::where('username', 'wawanhabibi')->first();
        $employee = Employee::latest()->first();

        $response = $this->actingAs($dept)->deleteJson('/dept/employees/' . $employee->nik);
        $response->assertStatus(200);
    }

    public function test_auth_dept_get_user_request_dataTables()
    {
        $dept = User::where('username', 'wawanhabibi')->first();

        $response = $this->actingAs($dept)->getJson('/dept/user-request');
        $response->assertStatus(200);
    }

    public function test_auth_dept_get_user_request_employee()
    {
        $dept = User::where('username', 'wawanhabibi')->first();
        $employee = Employee::where('isRequest', 0)->latest()->first();

        $response = $this->actingAs($dept)->getJson('/dept/user-request/' . $employee->nik);
        $response->assertStatus(200);
    }

    public function test_auth_dept_update_user_request()
    {
        $dept = User::where('username', 'wawanhabibi')->first();
        $employee = Employee::where('isRequest', 0)->latest()->first();

        $response = $this->actingAs($dept)->patchJson('/dept/is-request/' . $employee->nik, [
            'nik'                   => $employee->nik,
            'name'                  => $employee->name,
            'email'                 => strtolower(str_replace(" ", "", $employee->name)) . "@example.com",
            'username'              => strtolower(str_replace(" ", "", $employee->name)),
            'password'              => 'password',
            'password_confirmation' => 'password',
            'role'                  => 'User'
        ]);
        $response->assertStatus(200);
    }
}
