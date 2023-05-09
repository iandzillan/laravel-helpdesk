<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UserTicketTest extends TestCase
{
    public function test_auth_user_access_create_ticket()
    {
        $user = User::where('username', 'iandzillan')->first();

        $response = $this->actingAs($user)->get('/user/create-ticket');
        $response->assertStatus(200); // OK
    }

    public function test_auth_non_user_access_create_ticket()
    {
        $user = User::where('username', 'kamalagustina')->first();

        $response = $this->actingAs($user)->get('/user/create-ticket');
        $response->assertStatus(403); // FORBIDDEN
    }

    public function test_unauth_user_access_create_ticket()
    {
        $response = $this->get('/user/create-ticket');
        $response->assertStatus(302); // REDIRECT
        $response->assertRedirect('/');
    }

    public function test_auth_user_access_ticket_category_route()
    {
        $user = User::where('role', 'User')->first();

        $response = $this->actingAs($user)->get('/user/create-ticket/category');
        $response->assertStatus(200); // OK
    }

    public function test_auth_non_user_access_ticket_category_route()
    {
        $user = User::where('role', 'Admin')->first();

        $response = $this->actingAs($user)->json('GET', '/user/create-ticket/category');
        $response->assertStatus(403); //FORBIDDEN
    }

    public function test_unauth_user_access_ticket_category_route()
    {
        $response = $this->json('GET', '/user/create-ticket/category');
        $response->assertStatus(401); // UNAUTHORIZED
    }

    public function test_auth_user_access_ticket_sub_category_route()
    {
        $user = User::where('role', 'User')->first();
        $category = Category::where('id', 1)->first();

        $response = $this->actingAs($user)->json('GET', '/user/create-ticket/sub-category/' . $category->id);
        $response->assertStatus(200); // OK
    }

    public function test_auth_non_user_access_ticket_sub_category_route()
    {
        $user = User::where('role', 'Admin')->first();
        $category = Category::where('id', 1)->first();

        $response = $this->actingAs($user)->json('GET', '/user/create-ticket/sub-category/' . $category->id);
        $response->assertStatus(403); // FORBIDDEN
    }

    public function test_unauth_user_access_ticket_sub_category_route()
    {
        $category = Category::where('id', 1)->first();

        $response = $this->json('GET', '/user/create-ticket/sub-category/' . $category->id);
        $response->assertStatus(401); // UNAUTHORIZED
    }

    // public function test_auth_user_store_ticket()
    // {
    //     $user          = User::where('username', 'iandzillan')->first();
    //     $category      = Category::where('name', 'Hardware')->first();
    //     $subcategory   = SubCategory::where('category_id', $category->id)->first();
    //     $data          = [
    //         'subject'           => 'Feature testing create ticket',
    //         'category_id'       => $category->id,
    //         'sub_category_id'   => $subcategory->id,
    //         'image'             => UploadedFile::fake()->image('avatar.jpg')->size(100),
    //         'description'       => 'Feature testing create ticket'
    //     ];

    //     $response = $this->actingAs($user)->json('POST', '/user/create-ticket', $data);
    //     $response->assertStatus(200); //OK
    // }

    public function test_auth_non_user_store_ticket()
    {
        $user          = User::where('username', 'kamalagustina')->first();
        $category      = Category::where('name', 'Hardware')->first();
        $subcategory   = SubCategory::where('category_id', $category->id)->first();
        $data          = [
            'subject'           => 'Feature testing create ticket',
            'category_id'       => $category->id,
            'sub_category_id'   => $subcategory->id,
            'image'             => UploadedFile::fake()->image('avatar.jpg')->size(100),
            'description'       => 'Feature testing create ticket'
        ];

        $response = $this->actingAs($user)->json('POST', '/user/create-ticket', $data);
        $response->assertStatus(403); //FORBIDDEN
    }

    public function test_unauth_user_store_ticket()
    {
        $category      = Category::where('name', 'Hardware')->first();
        $subcategory   = SubCategory::where('category_id', $category->id)->first();
        $data          = [
            'subject'           => 'Feature testing create ticket',
            'category_id'       => $category->id,
            'sub_category_id'   => $subcategory->id,
            'image'             => UploadedFile::fake()->image('avatar.jpg')->size(100),
            'description'       => 'Feature testing create ticket'
        ];

        $response = $this->json('POST', '/user/create-ticket', $data);
        $response->assertStatus(401); //UNAUTHORIZED
    }

    public function test_auth_user_access_my_ticket_datatable_route()
    {
        $user = User::where('role', 'User')->first();

        $response = $this->actingAs($user)->json('GET', '/user/my-tickets');
        $response->assertStatus(200); //OK
    }

    public function test_auth_non_user_access_my_ticket_datatable_route()
    {
        $user = User::where('role', 'Admin')->first();

        $response = $this->actingAs($user)->json('GET', '/user/my-tickets');
        $response->assertStatus(403); //FORBIDDEN
    }

    public function test_unauth_user_access_my_ticket_datatable_route()
    {
        $response = $this->json('GET', '/user/my-tickets');
        $response->assertStatus(401); //UNAUTHORIZED
    }

    public function test_auth_user_access_detail_my_ticket_route()
    {
        $user   = User::where('username', 'iandzillan')->first();
        $ticket = 'T12345620230419-000001';

        $response = $this->actingAs($user)->get('/user/my-tickets/' . $ticket);
        $response->assertStatus(200); //OK
    }

    public function test_auth_user_access_detail_my_ticket_route_but_not_owner_the_ticket()
    {
        $user   = User::where('username', 'kaylamandala')->first();
        $data   = 'T12345620230419-000001';

        $response = $this->actingAs($user)->get('/user/my-tickets/' . $data);
        $response->assertStatus(403); //FORBIDDEN
    }

    public function test_auth_non_user_access_deatil_my_ticket_route()
    {
        $user = User::where('role', 'Admin')->first();
        $data   = 'T12345620230419-000001';

        $response = $this->actingAs($user)->get('/user/my-tickets/' . $data);
        $response->assertStatus(403); //FORBIDDEN
    }

    public function test_unauth_user_access_deatil_my_ticket_route()
    {
        $data   = 'T12345620230419-000001';

        $response = $this->get('/user/my-tickets/' . $data);
        $response->assertStatus(302); //REDIRECT
        $response->assertRedirect('/');
    }

    public function test_auth_user_access_onwork_ticket_dataTables_route()
    {
        $user = User::where('username', 'iandzillan')->first();

        $response = $this->actingAs($user)->getJson('/user/onwork-tickets');
        $response->assertStatus(200); //OK
    }

    public function test_auth_non_user_access_onwork_ticket_dataTables_route()
    {
        $user = User::where('username', 'kamalagustina')->first();

        $response = $this->actingAs($user)->getJson('/user/onwork-tickets');
        $response->assertStatus(403); //FORBIDDEN
    }

    public function test_unauth_user_access_onwork_ticket_dataTables_route()
    {
        $response = $this->getJson('/user/onwork-tickets');
        $response->assertStatus(401); //UNAUTHORIZED
    }

    public function test_auth_user_access_feedback_ticket_dataTables()
    {
        $user = User::where('username', 'iandzillan')->first();

        $response = $this->actingAs($user)->getJson('/user/feedback');
        $response->assertStatus(200); //OK
    }
}
