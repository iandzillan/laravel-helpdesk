<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SubdeptEmployeeTest extends TestCase
{
    public function test_auth_subdept_access_new_employee_route()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();

        $response = $this->actingAs($subdept)->get('/subdept/new-employee')->assertStatus(200); //OK
    }

    // public function test_auth_subdept_store_new_employee()
    // {
    //     $subdept = User::where('username', 'ellapuspasari')->first();

    //     $response = $this->actingAs($subdept)->postJson('/subdept/new-employee', [
    //         'nik'       => 123620,
    //         'name'      => 'Test Employee 01',
    //         'image'     => UploadedFile::fake()->image('avatar.jpg')->size(100),
    //         'position'  => 'Team Member'
    //     ]);
    //     $response->assertStatus(200); //OK
    // }

    public function test_auth_subdept_access_employee_list_dataTables()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/employees');
        $response->assertStatus(200); //OK
    }

    // public function test_auth_subdept_edit_employee()
    // {
    //     $subdept = User::where('username', 'ellapuspasari')->first();
    //     $employee = 123620;

    //     $response = $this->actingAs($subdept)->getJson('/subdept/employees/' . $employee . '/edit');
    //     $response->assertStatus(200); //OK
    // }

    // public function test_auth_subdept_update_employee()
    // {
    //     $subdept = User::where('username', 'ellapuspasari')->first();
    //     $employee = Employee::where('nik', 123620)->first();

    //     $response = $this->actingAs($subdept)->putJson('/subdept/employees/' . $employee, [
    //         'nik'       => $employee->nik,
    //         'name'      => 'Testing Employee 01',
    //         'position'  => 'Team Member',
    //         'image'     => UploadedFile::fake()->image('avatar.jpg')->size(100)
    //     ]);
    //     $response->assertStatus(200);
    // }

    // public function test_auth_subdept_delete_employee()
    // {
    //     $subdept = User::where('username', 'ellapuspasari')->first();
    //     $employee = 123620;

    //     $response = $this->actingAs($subdept)->deleteJson('/subdept/employees/' . $employee);
    //     $response->assertStatus(200);
    // }

    public function test_auth_subdept_access_user_request_list_dataTables()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/user-request');
        $response->assertStatus(200);
    }

    // public function test_auth_subdept_access_aUser_request_employee()
    // {
    //     $subdept = User::where('username', 'ellapuspasari')->first();
    //     $employee = 123620;

    //     $response = $this->actingAs($subdept)->getJson('/subdept/user-request/' . $employee);
    //     $response->assertStatus(200);
    // }

    // public function test_auth_subdept_isRequest_aUser_account_request_for_employee()
    // {
    //     $subdept = User::where('username', 'ellapuspasari')->first();
    //     $employee = Employee::where('nik', 123620)->first();

    //     $response = $this->actingAs($subdept)->patchJson('/subdept/is-request/' . $employee->nik, [
    //         'nik'                   => $employee->nik,
    //         'name'                  => $employee->name,
    //         'email'                 => 'testemployee@example.com',
    //         'username'              => 'testemployee',
    //         'password'              => 'password',
    //         'password_confirmation' => 'password',
    //         'role'                  => 'User'
    //     ]);
    //     $response->assertStatus(200);
    // }
}
