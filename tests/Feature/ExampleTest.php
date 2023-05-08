<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_login_route()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_login_process()
    {
        $response = $this->post('/login-process', [
            'username' => 'iandzillan',
            'password' => 'iandzillan'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/user/dashboard');
    }

    public function test_login_process_invalid()
    {
        $response = $this->post('/login-process', [
            'username' => '',
            'password' => ''
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['username', 'password']);
    }

    public function test_auth_user_access_create_ticket_link()
    {
        $user = User::where('username', 'iandzillan')->first();

        $response = $this->actingAs($user)->get('/user/create-ticket');
        $response->assertStatus(200);
    }

    public function test_auth_non_user_access_create_ticket_link()
    {
        $user = User::where('username', 'kamalagustina')->first();

        $response = $this->actingAs($user)->get('/user/create-ticket');
        $response->assertStatus(403);
    }

    public function test_auth_user_access_ticket_category_link()
    {
        $user = User::where('role', 'User')->first();

        $response = $this->actingAs($user)->get('/user/create-ticket/category');
        $response->assertStatus(200);
    }
}
