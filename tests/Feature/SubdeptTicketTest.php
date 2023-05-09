<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SubdeptTicketTest extends TestCase
{
    public function test_auth_subdept_get_create_ticket_route()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();

        $response = $this->actingAs($subdept)->get('/subdept/create-ticket');
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_category_ticket_route()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/create-ticket/category');
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_subCategory_ticket_route()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();
        $category = Category::where('id', 2)->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/create-ticket/sub-category/' . $category->id);
        $response->assertStatus(200);
    }

    // public function test_auth_subdept_create_ticket()
    // {
    //     $subdept = User::where('username', 'ellapuspasari')->first();
    //     $subcategory = SubCategory::where('category_id', 2)->first();

    //     $response = $this->actingAs($subdept)->postJson('/subdept/create-ticket', [
    //         'subject'         => 'Testing Subdept Tickets Test',
    //         'category_id'     => $subcategory->category->id,
    //         'sub_category_id' => $subcategory->id,
    //         'image'           => UploadedFile::fake()->image('ticket.jpg')->size(100),
    //         'description'     => 'Testing Subdept Ticket Test'
    //     ]);
    //     $response->assertStatus(200);
    // }

    public function test_auth_subdept_get_my_tickets_dataTables()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/my-tickets');
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_my_ticket_detail()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();
        $ticket_number = "T15783620230509-000030";

        $response = $this->actingAs($subdept)->getJson('/subdept/my-tickets/' . $ticket_number);
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_all_tickets_dataTables()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/all-tickets');
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_all_entry_ticket_dataTables()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/entry-tickets');
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_entry_ticket_detail()
    {
        $subdept = User::where('username', 'lamarmayasari')->first();
    }
}
