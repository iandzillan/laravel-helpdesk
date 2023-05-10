<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DeptTicketTest extends TestCase
{
    public function test_auth_dept_get_create_ticket_form()
    {
        $dept = User::where('username', 'wawanhabibi')->first();

        $response = $this->actingAs($dept)->get('/dept/create-ticket');
        $response->assertStatus(200);
    }

    public function test_auth_dept_get_select_category_form()
    {
        $dept = User::where('username', 'wawanhabibi')->first();

        $response = $this->actingAs($dept)->getJson('/dept/create-ticket/category');
        $response->assertStatus(200);
    }

    public function test_auth_dept_get_select_sub_category_form()
    {
        $dept     = User::where('username', 'wawanhabibi')->first();
        $category = Category::where('id', 2)->first();

        $response = $this->actingAs($dept)->getJson('/dept/create-ticket/sub-category/' . $category->id);
        $response->assertStatus(200);
    }

    public function test_auth_dept_create_ticket()
    {
        $dept = User::where('username', 'wawanhabibi')->first();
        $subcategory = SubCategory::where('id', 5)->first();

        $response = $this->actingAs($dept)->postJson('/dept/create-ticket', [
            'subject'         => 'Ticket Feature Testing',
            'category_id'     => $subcategory->category->id,
            'sub_category_id' => $subcategory->id,
            'image'           => UploadedFile::fake()->image('test.jpg')->size(100),
            'description'     => 'Ticket feature testing'
        ]);
        $response->assertStatus(200);
    }
}
