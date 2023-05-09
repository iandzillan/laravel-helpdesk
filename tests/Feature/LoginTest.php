<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
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

        $response->assertSessionHasErrors();
    }
}
