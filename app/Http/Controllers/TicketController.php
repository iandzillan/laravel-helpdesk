<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Ticket;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    public function newTicket()
    {
        // return view
        return view('ticket.new-ticket', [
            'title' => 'Create New Ticket - Helpdesk Ticketing System',
            'name'  => Auth::user()->userable->name
        ]);
    }

    public function getCategory()
    {
        // get all category
        $category = Category::all('name', 'id');

        // return response
        return response()->json($category);
    }

    public function getSubCategory($category)
    {
        // get all sub category based on category
        $subCategory = SubCategory::where('category_id', $category)->get();

        // return function
        return response()->json($subCategory);
    }

    public function getCode()
    {
        // get last ticket number
        $latest_ticket = Ticket::latest()->first();

        // check if ticket is null or not
        if ($latest_ticket == null) {
            // get today date
            $today = date('Y-m-d', strtotime('today' . date('Y')));

            // get first date of the year
            $first_date = date('Y-m-d', strtotime('first day of January' . date('Y')));

            // check if today is the first day of the year
            if ($today == $first_date) {
                // generate new code from 1
                $code = 'T' . Auth::user()->userable->nik . date('Ymd') . '-' . '000001';
            } else {
                // generate first code
                $code = 'T' . Auth::user()->userable->nik . date('Ymd') . '-' . '000001';
            }
        } else {
            // if no, increase code ticket from previous ticket code 
            $number = explode('-', $latest_ticket->ticket_number);
            $number = ($number[1] + 1);
            $code   = 'T' . Auth::user()->userable->nik . date('Ymd') . '-' . sprintf('%06d', $number);
        }

        return $code;
    }

    public function store(Request $request)
    {
        // set validation 
        $validator = Validator::make($request->all(), [
            'subject'         => 'required',
            'category_id'     => 'required',
            'sub_category_id' => 'required',
            'image'           => 'required|mimes:jpg,jpeg,png|image|max:2024',
            'description'     => 'required'
        ], [
            'category_id.required'     => 'The category field is required',
            'sub_category_id.required' => 'The sub category field is required',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }


        // get image extension
        $img_ext  = $request->image->getClientOriginalExtension();
        // set image name
        $img_name = $this->getCode() . '.' . $img_ext;

        // store tiket
        $ticket                  = new Ticket;
        $ticket->ticket_number   = $this->getCode();
        $ticket->subject         = $request->subject;
        $ticket->sub_category_id = $request->sub_category_id;
        $ticket->image           = $img_name;
        $ticket->description     = $request->description;
        $ticket->status          = 1;
        $ticket->progress        = 1;
        $ticket->user()->associate(Auth::user()->id);
        $ticket->save();

        // save image
        $request->image->storeAs('public/uploads/tickets', $img_name);

        // store tracking
        $tracking         = new Tracking;
        $tracking->status = "Ticket has been created";
        $ticket->trackings()->save($tracking);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Ticket has been created',
            'data'    => $ticket
        ]);
    }

    public function allTicket(Request $request)
    {
        // get user role 
        $role = Auth::user()->role;

        // check role
        switch ($role) {
            case 'Admin':
                # code...
                break;

            case 'Approver1':
                # code...
                break;

            case 'Approver2':
                # code...
                break;

            case 'User':
                // define route for user
                $route = 'user.show.ticket';
                break;

            case 'Technician':
                # code...
                break;

            default:
                abort(404);
                break;
        }

        // query all ticket owned by user
        $tickets = Ticket::where('user_id', Auth::user()->id)->latest()->get();

        // draw table
        if ($request->ajax()) {
            return DataTables::of($tickets)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    // get status
                    $status = $row->status;
                    switch ($status) {
                        case 1:
                            $status = '<span class="badge bg-light text-dark">Open</span>';
                            break;

                        case 2:
                            $status = '<span class="badge bg-secondary">Approved by supervisor</span>';
                            break;

                        case 3:
                            $status = '<span class="badge bg-success">Approved by Manager</span>';
                            break;

                        case 4:
                            $status = '<span class="badge bg-dark">Waiting to be assigned</span>';
                            break;

                        case 5:
                            $status = '<span class="badge bg-info">On work</span>';
                            break;

                        case 6:
                            $status = '<span class="badge bg-warning">Pending</span>';
                            break;

                        case 7:
                            $status = '<span class="badge bg-primary">Closed</span>';
                            break;

                        case 8:
                            $status = '<span class="badge bg-danger">Rejected</span>';
                            break;

                        default:
                            $status = '<span class="badge bg-danger">Undefined</span>';
                            break;
                    }

                    return $status;
                })
                ->addColumn('action', function ($row) use ($route) {
                    $btn = '<a href="' . route($route, $row->ticket_number) . '" class="btn btn-primary btn-sm" title="Show this ticket"> <i class="fa-solid fa-magnifying-glass"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // return view
        return view('ticket.all-ticket', [
            'title'  => 'All Tickets - Helpdesk Ticketing System',
            'name'   => Auth::user()->userable->name
        ]);
    }

    public function show($ticket)
    {
        $ticket = Ticket::where('ticket_number', $ticket)->first();

        // return view
        return view('ticket.show-ticket', [
            'title'  => "$ticket->ticket_number - Helpdesk Ticketing System",
            'name'   => Auth::user()->userable->name,
            'ticket' => $ticket
        ]);
    }
}
