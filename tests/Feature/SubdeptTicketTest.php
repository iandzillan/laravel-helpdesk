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

    public function test_auth_subdept_get_ticket_detail()
    {
        $subdept = User::where('username', 'lamarmayasari')->first();
        $ticket_number = "T12345620230510-000034";

        $response = $this->actingAs($subdept)->get('/subdept/all-tickets/' . $ticket_number);
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_new_entry_ticket_dataTables()
    {
        $subdept = User::where('username', 'ellapuspasari')->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/entry-tickets');
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_aNew_entry_ticket_detail()
    {
        $subdept       = User::where('username', 'lamarmayasari')->first();
        $ticket_number = "T12345620230509-000029";

        $response = $this->actingAs($subdept)->getJson('/subdept/entry-tickets/' . $ticket_number);
        $response->assertStatus(200);
    }

    // public function test_auth_subdept_approve_aNew_entry_ticket()
    // {
    //     $subdept       = User::where('username', 'lamarmayasari')->first();
    //     $ticket_number = "T12345620230510-000033";

    //     $response = $this->actingAs($subdept)->putJson('/subdept/entry-tickets/' . $ticket_number . '/approve');
    //     $response->assertStatus(200);
    // }

    // public function test_auth_subdept_reject_aNew_entry_ticket()
    // {
    //     $subdept       = User::where('username', 'lamarmayasari')->first();
    //     $ticket_number = "T12345620230510-000031";

    //     $response = $this->actingAs($subdept)->putJson('/subdept/entry-tickets/' . $ticket_number . '/reject', [
    //         'note' => 'Unit Test Reject'
    //     ]);
    //     $response->assertStatus(200);
    // }

    public function test_auth_subdept_get_onwork_ticket_dataTables()
    {
        $subdept = User::where('username', 'lamarmayasari')->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/onwork-tickets');
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_onwork_ticket_detail()
    {
        $subdept       = User::where('username', 'lamarmayasari')->first();
        $ticket_number = "T12345620230502-000011";

        $response = $this->actingAs($subdept)->get('/subdept/onwork-tickets/' . $ticket_number);
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_closed_ticket_dataTables()
    {
        $subdept = User::where('username', 'lamarmayasari')->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/closed-tickets');
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_closed_ticket_detail()
    {
        $subdept       = User::where('username', 'lamarmayasari')->first();
        $ticket_number = "T12345620230419-000004";

        $response = $this->actingAs($subdept)->get('/subdept/closed-tickets/' . $ticket_number);
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_rejected_ticket_dataTables()
    {
        $subdept = User::where('username', 'lamarmayasari')->first();

        $response = $this->actingAs($subdept)->getJson('/subdept/rejected-tickets');
        $response->assertStatus(200);
    }

    public function test_auth_subdept_get_rejected_ticket_detail()
    {
        $subdept       = User::where('username', 'lamarmayasari')->first();
        $ticket_number = "T12345620230510-000031";

        $response = $this->actingAs($subdept)->get('/subdept/rejected-tickets/' . $ticket_number);
        $response->assertStatus(200);
    }
}
