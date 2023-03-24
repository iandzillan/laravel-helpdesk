<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Employee;
use App\Models\Position;
use App\Models\SubCategory;
use App\Models\Ticket;
use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    public function createTicket()
    {
        // get role
        $role = Auth::user()->role;

        switch ($role) {
            case 'Approver1':
                $view = 'approver1.ticket.new-ticket';
                break;

            case 'Approver2':
                $view = 'approver2.ticket.new-ticket';
                break;

            case 'User':
                $view = 'user.ticket.new-ticket';
                break;

            default:
                abort(404);
                break;
        }

        // return view
        return view($view, [
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
        $ticket->progress        = 0;
        $ticket->user()->associate(Auth::user()->id);
        $ticket->save();

        // save image
        $request->image->storeAs('public/uploads/tickets', $img_name);

        // store tracking
        $tracking         = new Tracking;
        $tracking->status = "Ticket created";
        $ticket->trackings()->save($tracking);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Ticket has been created',
            'data'    => $ticket
        ]);
    }

    public function myTicket(Request $request)
    {
        // get user role 
        $role = Auth::user()->role;

        // check role
        switch ($role) {
            case 'Approver1':
                // define vie & route
                $view  = 'approver1.ticket.my-ticket';
                $route = 'dept.show.ticket';
                break;
                break;

            case 'Approver2':
                // define vie & route
                $view  = 'approver2.ticket.my-ticket';
                $route = 'subdept.show.ticket';
                break;

            case 'User':
                // define view & route 
                $view  = 'user.ticket.my-ticket';
                $route = 'user.show.ticket';
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
                        case 'Open':
                            $status = '<span class="badge bg-light text-dark">' . $status . '</span>';
                            break;

                        case 'Approved by supervisor':
                            $status = '<span class="badge bg-secondary">' . $status . '</span>';
                            break;

                        case 'Approved by Manager':
                            $status = '<span class="badge bg-success">' . $status . '</span>';
                            break;

                        case 'Waiting to be assigned':
                            $status = '<span class="badge bg-dark">' . $status . '</span>';
                            break;

                        case 'On work':
                            $status = '<span class="badge bg-info">' . $status . '</span>';
                            break;

                        case 'Pending':
                            $status = '<span class="badge bg-warning">' . $status . '</span>';
                            break;

                        case 'Closed':
                            $status = '<span class="badge bg-primary">' . $status . '</span>';
                            break;

                        case 'Rejected':
                            $status = '<span class="badge bg-danger">' . $status . '</span>';
                            break;

                        default:
                            $status = '<span class="badge bg-danger">' . $status . '</span>';
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
        return view($view, [
            'title'  => 'My Tickets - Helpdesk Ticketing System',
            'name'   => Auth::user()->userable->name
        ]);
    }

    public function show($ticket)
    {
        // get user role
        $role = Auth::user()->role;

        // check role
        switch ($role) {
            case 'Admin':
                // define view
                $view = 'admin.ticket.show-ticket';
                break;

            case 'Approver1':
                // define view
                $view = 'approver1.ticket.show-ticket';
                break;

            case 'Approver2':
                // define view
                $view = 'approver2.ticket.show-ticket';
                // query ticket based on sub department
                break;

            case 'User':
                // define view
                $view = 'user.ticket.show-ticket';
                $ticket = Ticket::whereHas('user', function ($query) {
                    $query->where('user_id', Auth::user()->id);
                })->where('ticket_number', $ticket)->first();
                break;

            case 'Technician':
                // define view
                $view = 'technician.ticket.show-ticket';
                break;

            default:
                abort(404);
                break;
        }

        // return view
        return view($view, [
            'title'     => "$ticket->ticket_number - Helpdesk Ticketing System",
            'name'      => Auth::user()->userable->name,
            'ticket'    => $ticket,
            'trackings' => $ticket->trackings
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
                // query ticket based on sub department
                $tickets = Ticket::select(DB::raw('tickets.ticket_number, employees.nik as nik, employees.name as name, tickets.subject, tickets.status, tickets.created_at, tickets.updated_at'))
                    ->leftJoin('users', 'user_id', '=', 'users.id')
                    ->leftJoin('employees', 'employees.id', '=', 'users.userable_id')
                    ->leftJoin('positions', 'positions.id', '=', 'employees.position_id')
                    ->leftJoin('sub_departments', 'sub_departments.id', '=', 'positions.sub_department_id')
                    ->where('users.userable_type', '=', 'App\Models\Employee')
                    ->where('sub_departments.id', '=', Auth::user()->userable->position->sub_department_id)
                    ->get();

                $view  = 'approver2.ticket.all-ticket';
                $route = 'subdept.all.ticket.show';
                break;

            case 'Technician':
                # code...
                break;

            default:
                # code...
                break;
        }

        // draw table
        if ($request->ajax()) {
            return DataTables::of($tickets)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    // get status
                    $status = $row->status;
                    switch ($status) {
                        case 'Open':
                            $status = '<span class="badge bg-light text-dark">' . $status . '</span>';
                            break;

                        case 'Approved by supervisor':
                            $status = '<span class="badge bg-secondary">' . $status . '</span>';
                            break;

                        case 'Approved by Manager':
                            $status = '<span class="badge bg-success">' . $status . '</span>';
                            break;

                        case 'Waiting to be assigned':
                            $status = '<span class="badge bg-dark">' . $status . '</span>';
                            break;

                        case 'On work':
                            $status = '<span class="badge bg-info">' . $status . '</span>';
                            break;

                        case 'Pending':
                            $status = '<span class="badge bg-warning">' . $status . '</span>';
                            break;

                        case 'Closed':
                            $status = '<span class="badge bg-primary">' . $status . '</span>';
                            break;

                        case 'Rejected':
                            $status = '<span class="badge bg-danger">' . $status . '</span>';
                            break;

                        default:
                            $status = '<span class="badge bg-danger">' . $status . '</span>';
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

        return view($view, [
            'title'  => 'All Tickets - Helpdesk Ticketing System',
            'name'   => Auth::user()->userable->name
        ]);
    }
}
