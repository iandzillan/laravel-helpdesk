<?php

namespace App\Http\Controllers;

use App\Exports\TicketExport;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Position;
use App\Models\SubCategory;
use App\Models\Ticket;
use App\Models\Tracking;
use App\Models\Urgency;
use App\Models\User;
use App\Notifications\StatusUpdateNotification;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
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
            'name'  => Auth::user()->employee->name
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
                $code = 'T' . Auth::user()->employee->nik . date('Ymd') . '-' . '000001';
            } else {
                // generate first code
                $code = 'T' . Auth::user()->employee->nik . date('Ymd') . '-' . '000001';
            }
        } else {
            // if no, increase code ticket from previous ticket code 
            $number = explode('-', $latest_ticket->ticket_number);
            $number = ($number[1] + 1);
            $code   = 'T' . Auth::user()->employee->nik . date('Ymd') . '-' . sprintf('%06d', $number);
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

        // check role for define status
        switch (Auth::user()->role) {
            case 'Approver1':
                $status = 3;
                $tracking_note = "Waiting to be Assigned to technician by Admin";
                break;

            case 'Approver2':
                $status = 2;
                $tracking_note = "Waiting to be Approved by Manager";
                break;

            case 'User':
                $status = 1;
                $tracking_note = "Waiting to be Approved by Team Leader";
                break;

            default:
                $status = 0;
                break;
        }

        // store tiket
        $ticket                  = new Ticket;
        $ticket->ticket_number   = $this->getCode();
        $ticket->subject         = $request->subject;
        $ticket->sub_category_id = $request->sub_category_id;
        $ticket->image           = $img_name;
        $ticket->description     = $request->description;
        $ticket->status          = $status;
        $ticket->progress        = 0;
        $ticket->user()->associate(Auth::user()->id);
        $ticket->save();

        // save image
        $request->image->storeAs('public/uploads/tickets', $img_name);

        // store tracking
        $tracking         = new Tracking;
        $tracking->status = "Ticket created";
        $tracking->note   = $tracking_note;
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
                // define view & route
                $view  = 'approver1.ticket.my-ticket';
                $route = 'dept.my.tickets.show';
                break;
                break;

            case 'Approver2':
                // define view & route
                $view  = 'approver2.ticket.my-ticket';
                $route = 'subdept.my.tickets.show';
                break;

            case 'User':
                // define view & route 
                $view  = 'user.ticket.my-ticket';
                $route = 'user.my.tickets.show';
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

                        case 'Approved by Team Leader':
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
            'name'   => Auth::user()->employee->name
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
                $view     = 'admin.ticket.show-ticket';
                // get ticket
                $ticket   = Ticket::where('ticket_number', $ticket)->first();
                // get sum(duration)
                $sum      = Tracking::where('ticket_id', $ticket->id)->where('status', '!=', 'Ticket Continued')->sum('duration');
                $duration = gmdate('H:i:s', $sum);
                break;

            case 'Approver1':
                // define view
                $view     = 'approver1.ticket.show-ticket';
                // get ticket
                $ticket   = Ticket::with('user', 'user.employee')->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->where('ticket_number', $ticket)->first();
                // get sum(duration)
                $sum      = Tracking::where('ticket_id', $ticket->id)->where('status', '!=', 'Ticket Continued')->sum('duration');
                $duration = gmdate('H:i:s', $sum);
                break;

            case 'Approver2':
                // define view
                $view     = 'approver2.ticket.show-ticket';
                // get ticket
                $ticket   = Ticket::with('user', 'user.employee')->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->where('ticket_number', $ticket)->first();
                // get sum(duration)
                $sum      = Tracking::where('ticket_id', $ticket->id)->where('status', '!=', 'Ticket Continued')->sum('duration');
                $duration = gmdate('H:i:s', $sum);
                break;

            case 'User':
                // define view
                $view     = 'user.ticket.show-ticket';
                // get ticket
                $ticket   = Ticket::whereHas('user', function ($q) {
                    $q->where('user_id', Auth::user()->id);
                })->where('ticket_number', $ticket)->first();
                // get sum(duration)
                $sum      = Tracking::where('ticket_id', $ticket->id)->where('status', '!=', 'Ticket Continued')->sum('duration');
                $duration = gmdate('H:i:s', $sum);
                break;

            case 'Technician':
                // define view
                $view     = 'technician.ticket.show-ticket';
                // get ticket
                $ticket   = Ticket::where('technician_id', Auth::user()->id)->where('ticket_number', $ticket)->first();
                // get sum(duration)
                $sum      = Tracking::where('ticket_id', $ticket->id)->where('status', '!=', 'Ticket Continued')->sum('duration');
                $duration = gmdate('H:i:s', $sum);
                break;

            default:
                abort(404);
                break;
        }

        // return view
        return view($view, [
            'title'     => "$ticket->ticket_number - Helpdesk Ticketing System",
            'name'      => Auth::user()->employee->name,
            'ticket'    => $ticket,
            'trackings' => $ticket->trackings,
            'duration'  => $duration
        ]);
    }

    public function allTicket(Request $request)
    {
        // get user role
        $role = Auth::user()->role;

        // check role
        switch ($role) {
            case 'Admin':
                // get ticket
                $tickets = Ticket::latest()->get();
                // set view
                $view = 'admin.ticket.all-ticket';
                // set route
                $route = 'admin.all.tickets.show';
                break;

            case 'Approver1':
                // get ticket
                $tickets = Ticket::with('user', 'user.employee')->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->latest()->get();
                // set view
                $view  = 'approver1.ticket.all-ticket';
                // set route
                $route = 'dept.all.tickets.show';
                break;

            case 'Approver2':
                // get ticket
                $tickets = Ticket::with('user', 'user.employee')->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->latest()->get();
                // set view
                $view  = 'approver2.ticket.all-ticket';
                // set route 
                $route = 'subdept.all.tickets.show';
                break;

            default:
                abort(404);
                break;
        }

        // draw table
        if ($request->ajax()) {
            return DataTables::of($tickets)
                ->addIndexColumn()
                ->addColumn('subdept', function ($row) {
                    if (Auth::user()->employee->position == 'Team Leader') {
                        return $row->user->employee->subDepartment->name;
                    }
                })
                ->addColumn('dept', function ($row) {
                    return $row->user->employee->department->name;
                })
                ->addColumn('name', function ($row) {
                    return $row->user->employee->name;
                })
                ->addColumn('sub_category', function ($row) {
                    return $row->subCategory->name;
                })
                ->addColumn('status', function ($row) {
                    // get status
                    $status = $row->status;
                    switch ($status) {
                        case 'Open':
                            $status = '<span class="badge bg-light text-dark">' . $status . '</span>';
                            break;

                        case 'Approved by Team Leader':
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
            'name'   => Auth::user()->employee->name
        ]);
    }

    public function newEntry(Request $request)
    {
        // get user role
        $role = Auth::user()->role;

        // check role 
        switch ($role) {
            case 'Admin':
                // query ticket
                $tickets = Ticket::where('status', 3)->get();
                // route
                $route = 'admin.entry.tickets.show';
                // view
                $view = 'admin.ticket.new-entry';
                break;

            case 'Approver1':
                // query ticket
                $tickets = Ticket::with('user', 'user.employee')->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->where('status', 2)->get();
                // route
                $route = 'dept.entry.tickets.show';
                // view 
                $view = 'approver1.ticket.new-entry';
                break;

            case 'Approver2':
                // query tickets
                $tickets = Ticket::with(['user', 'user.employee'])->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->where('status', 1)->get();
                // route
                $route = 'subdept.entry.tickets.show';
                // view
                $view = 'approver2.ticket.new-entry';
                break;

            default:
                abort(404);
                break;
        }

        if ($request->ajax()) {
            // draw table
            return DataTables::of($tickets)
                ->addIndexColumn()
                ->addColumn('subdept', function ($row) {
                    if (Auth::user()->employee->position == 'Team Leader') {
                        return $row->user->employee->subDepartment->name;
                    }
                })
                ->addColumn('dept', function ($row) {
                    return $row->user->employee->department->name;
                })
                ->addColumn('name', function ($row) {
                    return $row->user->employee->name;
                })
                ->addColumn('sub_category', function ($row) {
                    return $row->subCategory->name;
                })
                ->addColumn('status', function ($row) {
                    // get status
                    $status = $row->status;
                    switch ($status) {
                        case 'Open':
                            $status = '<span class="badge bg-light text-dark">' . $status . '</span>';
                            break;

                        case 'Approved by Team Leader':
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
            'title' => 'New Entry Tickets - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function onWork(Request $request)
    {
        // get role of user
        $role = Auth::user()->role;

        // check role 
        switch ($role) {
            case 'Admin':
                // get all on work tickets 
                $tickets = Ticket::where('status', 4)->latest('progress_at')->get();

                // define view
                $view = 'admin.ticket.onwork';

                // define route
                $route = 'admin.tickets.onwork.show';
                break;

            case 'Approver1':
                // get all on work tickets 
                $tickets = Ticket::with(['user', 'user.employee'])->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->where('status', 4)->latest('progress_at')->get();

                // define view
                $view = 'approver1.ticket.onwork';

                // define route
                $route = 'dept.tickets.onwork.show';
                break;

            case 'Approver2':
                // get all on work tickets 
                $tickets = Ticket::with(['user', 'user.employee'])->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->where('status', 4)->latest('progress_at')->get();

                // define view
                $view = 'approver2.ticket.onwork';

                // define route
                $route = 'subdept.tickets.onwork.show';
                break;

            case 'User':
                // get all on work tickets 
                $tickets = Ticket::where('user_id', Auth::user()->id)->where('status', 4)->latest('progress_at')->get();

                // define view
                $view = 'user.ticket.onwork';

                // define route
                $route = 'user.tickets.onwork.show';
                break;

            case 'Technician':
                // get all on work tickets
                $tickets = Ticket::where('tickets.technician_id', Auth::user()->id)->where('tickets.status', 4)->get()->sortBy(function ($query) {
                    return $query->urgency->hours;
                });

                // define view
                $view = 'technician.ticket.onwork';

                // define route
                $route = 'technician.tickets.onwork.show';
                break;

            default:
                abort(404);
                break;
        }

        // draw table
        if ($request->ajax()) {
            return DataTables::of($tickets)
                ->addIndexColumn()
                ->addColumn('subdept', function ($row) {
                    if (Auth::user()->employee->position == 'Team Leader') {
                        return $row->user->employee->subDepartment->name;
                    }
                })
                ->addColumn('dept', function ($row) {
                    return $row->user->employee->department->name;
                })
                ->addColumn('name', function ($row) {
                    return $row->user->employee->name;
                })
                ->addColumn('technician', function ($row) {
                    return $row->technician->employee->name;
                })
                ->addColumn('sub_category', function ($row) {
                    return $row->subCategory->name;
                })
                ->addColumn('urgency', function ($row) {
                    return $row->urgency->hours . ' Hours';
                })
                ->addColumn('status', function ($row) {
                    // get status
                    $status = $row->status;
                    switch ($status) {
                        case 'Open':
                            $status = '<span class="badge bg-light text-dark">' . $status . '</span>';
                            break;

                        case 'Approved by Team Leader':
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
                    $btn = '<a href="' . route($route, $row->ticket_number) . '" class="btn btn-primary btn-sm" title="Show this ticket"> <i class="fa-solid fa-magnifying-glass"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view($view, [
            'title' => 'On Work Tickets - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function closed(Request $request)
    {
        // get role of user
        $role = Auth::user()->role;

        // check role 
        switch ($role) {
            case 'Admin':
                // get all closed tickets 
                $tickets = Ticket::where('status', 6)->latest('updated_at')->get();

                // define view
                $view = 'admin.ticket.closed';

                // define route
                $route = 'admin.tickets.closed.show';
                break;

            case 'Approver1':
                // get all closed tickets 
                $tickets = Ticket::with(['user', 'user.employee'])->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->where('status', 6)->latest('updated_at')->get();

                // define view
                $view = 'approver1.ticket.closed';

                // define route
                $route = 'dept.tickets.closed.show';
                break;

            case 'Approver2':
                // get all closed tickets 
                $tickets = Ticket::with(['user', 'user.employee'])->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->where('status', 6)->latest('updated_at')->get();

                // define view
                $view = 'approver2.ticket.closed';

                // define route
                $route = 'subdept.tickets.closed.show';
                break;

            case 'User':
                // get all closed tickets 
                $tickets = Ticket::where('user_id', Auth::user()->id)->where('status', 6)->latest('updated_at')->get();

                // define view
                $view = 'user.ticket.closed';

                // define route
                $route = 'user.tickets.closed.show';
                break;

            case 'Technician':
                // get all on work tickets
                $tickets = Ticket::where('tickets.technician_id', Auth::user()->id)->where('tickets.status', 6)->latest('updated_at')->get();

                // define view
                $view = 'technician.ticket.closed';

                // define route
                $route = 'technician.tickets.closed.show';
                break;

            default:
                abort(404);
                break;
        }

        // draw table
        if ($request->ajax()) {
            return DataTables::of($tickets)
                ->addIndexColumn()
                ->addColumn('subdept', function ($row) {
                    if (Auth::user()->employee->position == 'Team Leader') {
                        return $row->user->employee->subDepartment->name;
                    }
                })
                ->addColumn('dept', function ($row) {
                    return $row->user->employee->department->name;
                })
                ->addColumn('name', function ($row) {
                    return $row->user->employee->name;
                })
                ->addColumn('sub_category', function ($row) {
                    return $row->subCategory->name;
                })
                ->addColumn('status', function ($row) {
                    // get status
                    $status = $row->status;
                    switch ($status) {
                        case 'Open':
                            $status = '<span class="badge bg-light text-dark">' . $status . '</span>';
                            break;

                        case 'Approved by Team Leader':
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
                    $btn = '<a href="' . route($route, $row->ticket_number) . '" class="btn btn-primary btn-sm" title="Show this ticket"> <i class="fa-solid fa-magnifying-glass"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view($view, [
            'title' => 'Closed Tickets - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function rejected(Request $request)
    {
        // get role of user
        $role = Auth::user()->role;

        // check role 
        switch ($role) {
            case 'Admin':
                // get all rejected tickets 
                $tickets = Ticket::where('status', 7)->latest('updated_at')->get();

                // define view
                $view = 'admin.ticket.rejected';

                // define route
                $route = 'admin.tickets.rejected.show';
                break;

            case 'Approver1':
                // get all rejected tickets 
                $tickets = Ticket::with(['user', 'user.employee'])->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->where('status', 7)->latest('updated_at')->get();

                // define view
                $view = 'approver1.ticket.rejected';

                // define route
                $route = 'dept.tickets.rejected.show';
                break;

            case 'Approver2':
                // get all rejected tickets 
                $tickets = Ticket::with(['user', 'user.employee'])->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->where('status', 7)->latest('updated_at')->get();

                // define view
                $view = 'approver2.ticket.rejected';

                // define route
                $route = 'subdept.tickets.rejected.show';
                break;

            case 'User':
                // get all rejected tickets 
                $tickets = Ticket::where('user_id', Auth::user()->id)->where('status', 7)->latest('updated_at')->get();

                // define view
                $view = 'user.ticket.rejected';

                // define route
                $route = 'user.tickets.rejected.show';
                break;

            default:
                abort(404);
                break;
        }

        // draw table
        if ($request->ajax()) {
            return DataTables::of($tickets)
                ->addIndexColumn()
                ->addColumn('subdept', function ($row) {
                    if (Auth::user()->employee->position == 'Team Leader') {
                        return $row->user->employee->subDepartment->name;
                    }
                })
                ->addColumn('dept', function ($row) {
                    return $row->user->employee->department->name;
                })
                ->addColumn('name', function ($row) {
                    return $row->user->employee->name;
                })
                ->addColumn('sub_category', function ($row) {
                    return $row->subCategory->name;
                })
                ->addColumn('status', function ($row) {
                    // get status
                    $status = $row->status;
                    switch ($status) {
                        case 'Open':
                            $status = '<span class="badge bg-light text-dark">' . $status . '</span>';
                            break;

                        case 'Approved by Team Leader':
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
                    $btn = '<a href="' . route($route, $row->ticket_number) . '" class="btn btn-primary btn-sm" title="Show this ticket"> <i class="fa-solid fa-magnifying-glass"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view($view, [
            'title' => 'Rejected Tickets - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function approve($ticket)
    {
        // get role
        $role = Auth::user()->role;

        // check role 
        switch ($role) {
            case 'Approver1':
                // get ticket
                $ticket = Ticket::whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->where('ticket_number', $ticket)->first();
                // set status
                $status = 3;
                // set tracking
                $status_tracking = 'Ticket Approved by Manager';
                $note_tracking   = 'Waiting to be assigned to technician by Admin';
                break;

            case 'Approver2':
                // get ticket
                $ticket = Ticket::whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->where('ticket_number', $ticket)->first();
                // set status
                $status = 2;
                // set tracking
                $status_tracking = 'Ticket Approved by Team Leader';
                $note_tracking   = 'Waiting to be approved by Manager';
                break;

            default:
                abort(404);
                break;
        }

        // update ticket
        $ticket->status = $status;
        $ticket->save();

        // update tracking ticket
        $tracking         = new Tracking;
        $tracking->status = $status_tracking;
        $tracking->note   = $note_tracking;
        $ticket->trackings()->save($tracking);

        // return response
        return response()->json([
            'success'  => true,
            'message'  => 'Ticket Approved',
            'data'     => $ticket
        ]);
    }

    public function reject(Request $request, $ticket)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'note' => 'required'
        ]);

        // check validation
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // check role
        switch (Auth::user()->role) {
            case 'Admin':
                // get the ticket
                $ticket = Ticket::where('ticket_number', $ticket)->first();

                // set the tracking
                $status_tracking = "Ticket rejected by Admin";
                break;

            case 'Approver1':
                // get the ticket
                $ticket = Ticket::whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->where('ticket_number', $ticket)->first();

                // set the tracking
                $status_tracking = "Ticket rejected by Manager";
                break;

            case 'Approver2':
                // get the ticket
                $ticket = Ticket::whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->where('ticket_number', $ticket)->first();

                // set the tracking
                $status_tracking = "Ticket rejected by Team Leader";
                break;

            default:
                abort(404);
                break;
        }

        // update ticket
        $ticket->status = 7;
        $ticket->save();

        // update tracking
        $tracking         = new Tracking;
        $tracking->status = $status_tracking;
        $tracking->note   = $request->note;
        $ticket->trackings()->save($tracking);

        // return response
        return response()->json([
            'success' => true,
            'message' => "Ticket has been rejected",
            'data'    => $ticket
        ]);
    }

    public function getTechnicians($ticket)
    {
        $subCategory = SubCategory::where('id', $ticket)->first();
        $technicians = Employee::whereHas('user', function ($q) use ($subCategory) {
            $q->where('id', $subCategory->technician_id);
        })->get();

        return response()->json($technicians);
    }

    public function getUrgencies($ticket)
    {
        $urgencies = Urgency::all();
        return response()->json($urgencies);
    }

    public function assign(Request $request, $ticket)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'technician_id' => 'required',
            'urgency_id'    => 'required'
        ], [
            'technician_id.required' => 'The technician field is required',
            'urgency.required'       => 'The urgency field is required'
        ]);

        // check validation
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // get user
        $user = User::where('employee_id', $request->technician_id)->first();

        // get ticket
        $ticket = Ticket::where('ticket_number', $ticket)->first();

        // get urgency
        $urgency = Urgency::where('id', $request->urgency_id)->first();

        // count expected resolve time
        $time_second = $urgency->hours * 3600;
        $progress_at = Carbon::now();
        $expected    = $progress_at->addSecond($time_second);

        // update ticket
        $ticket->status               = 4;
        $ticket->urgency_id           = $request->urgency_id;
        $ticket->technician_id        = $user->id;
        $ticket->progress_at          = Carbon::now();
        $ticket->expected_finish_at   = $expected;
        $ticket->save();

        // get technician
        $technician = Employee::where('id', $request->technician_id)->first();
        // update tracking
        $tracking = new Tracking;
        $tracking->status = "Ticket has been assigned to technician";
        $tracking->note   = "Ticket has been assigned to $technician->name";
        $ticket->trackings()->save($tracking);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Ticket has been assigned to technician',
            'data'    => $ticket
        ]);
    }

    public function getProgress($ticket)
    {
        // get progress ticket
        $progress = Ticket::where('ticket_number', $ticket)->first();

        // return response
        return response()->json($progress);
    }

    public function update(Request $request, $ticket)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'progress' => 'required',
            'note'     => 'required'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // get ticket
        $ticket = Ticket::where('ticket_number', $ticket)->first();

        // get SLA time
        $sla_timer = $ticket->urgency->hours * 3600;

        // get last tracking time
        $last_track = Tracking::where('ticket_id', $ticket->id)->latest()->first();

        // count duration in second
        $duration = Carbon::now('Asia/Jakarta')->diffInSeconds($last_track->created_at);

        // count resolve duration
        $sum_resolve        = Tracking::where('ticket_id', $ticket->id)->where('status', '!=', 'Ticket Continued')->sum('duration');
        $sum_resolve        = $sum_resolve + $duration;
        $sum_resolve_humans = CarbonInterval::second($sum_resolve)->cascade();

        // count pending duration
        $sum_pending        = Tracking::where('ticket_id', $ticket->id)->where('status', 'Ticket Continued')->sum('duration');
        $sum_pending_humans = CarbonInterval::second($sum_pending)->cascade();

        // check if progress == 100
        if ($request->progress == 100) {
            if ($sum_resolve < $sla_timer) {
                $isUnderSla = 1;
            } else {
                $isUnderSla = 0;
            }

            $ticket->status     = 6;
            $ticket->finish_at  = Carbon::now();
            $ticket->isUnderSla = $isUnderSla;
            $ticket->progress   = $request->progress;
            $status_tracking    = 'Ticket Closed.';
            $note               = 'Resolve duration: ' . $sum_resolve_humans->forHumans() . '. Pending duration: ' . $sum_pending_humans->forHumans() . '.';
        } else {
            $ticket->progress = $request->progress;
            $status_tracking  = 'On work';
            $note             = $request->note;
        }
        // update ticket
        $ticket->save();

        // update tracking
        $tracking           = new Tracking;
        $tracking->status   = $status_tracking;
        $tracking->note     = $note;
        $tracking->duration = $duration;
        $ticket->trackings()->save($tracking);

        // return response
        return response()->json([
            'success'  => true,
            'message'  => 'Ticket has been updated',
            'data'     => $ticket
        ]);
    }

    public function pending(Request $request, $ticket)
    {
        // set validation
        $validator = validator::make($request->all(), [
            'note' => 'required'
        ]);

        // check validation
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // get the ticket
        $ticket         = Ticket::where('ticket_number', $ticket)->first();
        $ticket->status = 5;
        $ticket->save();

        // update tracking
        $tracking           = new Tracking;
        $tracking->status   = 'Ticket Postponed';
        $tracking->note     = $request->note;
        $ticket->trackings()->save($tracking);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Ticket has been postponed',
            'data'    => $ticket
        ]);
    }

    public function continue($ticket)
    {
        // get the ticket
        $ticket = Ticket::where('ticket_number', $ticket)->first();
        // update status ticket
        $ticket->status = 4;
        $ticket->save();

        // get technician
        $technician = Employee::whereHas('user', function ($q) use ($ticket) {
            $q->where('id', $ticket->technician_id);
        })->first();

        // get last tracking time
        $last_track = Tracking::where('ticket_id', $ticket->id)->latest()->first();

        // count duration in second
        $duration = Carbon::now('Asia/Jakarta')->diffInSeconds($last_track->created_at);
        $sum      = CarbonInterval::second($duration)->cascade();

        // update tracking
        $tracking         = new Tracking;
        $tracking->status = 'Ticket Continued';
        $tracking->note   = 'Ticket continued. Pending duration: ' . $sum->forHumans();
        $tracking->duration = $duration;
        $ticket->trackings()->save($tracking);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Ticket has been continued',
            'data'    => $ticket
        ]);
    }

    public function statusUpdate($ticket)
    {
        $ticket = Ticket::where('ticket_number', $ticket)->first();
        switch ($ticket->status) {
            case 'Open':
                $sub_department = Auth::user()->employee->sub_department_id;
                $team_leader    = Employee::where('sub_department_id', $sub_department)->where('position', 'Team Leader')->first();
                $reciever       = $team_leader->user->email;
                $data_email     = [
                    'greeting'    => "Dear $team_leader->name",
                    'body'        => "We would like to inform you there's a new ticket that need your approval. Please click button below to see the detail",
                    'thanks'      => "Thank you from Helpdesk Ticketing System. No need to reply this email",
                    'action_text' => "View Ticket",
                    'action_url'  => route('subdept.entry.tickets.show', $ticket->ticket_number)
                ];
                break;

            case 'Approved by Team Leader':
                $department     = Auth::user()->employee->department_id;
                $manager        = Employee::where('department_id', $department)->where('position', 'Manager')->first();
                $reciever       = $manager->user->email;
                $data_email     = [
                    'greeting'    => "Dear $manager->name",
                    'body'        => "We would like to inform you there's a new ticket that need your approval. Please click button below to see the detail",
                    'thanks'      => "Thank you from Helpdesk Ticketing System. No need to reply this email",
                    'action_text' => "View Ticket",
                    'action_url'  => route('dept.entry.tickets.show', $ticket->ticket_number)
                ];
                break;

            case 'Approved by Manager':
                $admin          = User::where('role', 'Admin')->first();
                $reciever       = $admin->email;
                $data_email     = [
                    'greeting'    => "Dear {$admin->employee->name}",
                    'body'        => "We would like to inform you there's a new ticket that need your approval. Please click button below to see the detail",
                    'thanks'      => "Thank you from Helpdesk Ticketing System. No need to reply this email",
                    'action_text' => "View Ticket",
                    'action_url'  => route('admin.entry.tickets.show', $ticket->ticket_number)
                ];
                break;

            case 'On work':
                $technician     = User::where('id', $ticket->technician_id)->first();
                $reciever       = $technician->email;
                $data_email     = [
                    'greeting'    => "Dear {$technician->employee->name}",
                    'body'        => "We would like to inform you there's a new ticket that assign to you with " . strtolower($ticket->urgency->name) . " urgency. Please click button below to see the detail",
                    'thanks'      => "Thank you from Helpdesk Ticketing System. No need to reply this email",
                    'action_text' => "View Ticket",
                    'action_url'  => route('technician.tickets.onwork.show', $ticket->ticket_number)
                ];
                break;

            case 'Closed':
                $user           = User::where('id', $ticket->user_id)->first();
                $reciever       = $user->email;
                $data_email     = [
                    'greeting'    => "Dear {$user->employee->name}",
                    'body'        => "We would like to inform your that your tiket has been completed. Please click button below to see the detail",
                    'thanks'      => "Thank you from Helpdesk Ticketing System. No need to reply this email",
                    'action_text' => "View Ticket",
                    'action_url'  => route('user.my.tickets.show', $ticket->ticket_number)
                ];
                break;

            case 'Rejected':
                $user           = User::where('id', $ticket->user_id)->first();
                $reciever       = $user->email;
                $data_email     = [
                    'greeting'    => "Dear {$user->employee->name}",
                    'body'        => "We would like to inform your that your tiket has been rejected. Please click button below to see the detail",
                    'thanks'      => "Thank you from Helpdesk Ticketing System. No need to reply this email",
                    'action_text' => "View Ticket",
                    'action_url'  => route('user.my.tickets.show', $ticket->ticket_number)
                ];
                break;

            default:
                # code...
                break;
        }

        Notification::route('mail', $reciever)->notify(new StatusUpdateNotification($data_email));
    }

    public function slaReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tickets = Ticket::whereBetween('created_at', [Carbon::parse($request->from), Carbon::parse($request->to)])->get();

        if ($tickets->count() == 0) {
            return response()->json([
                'success' => false,
                'message' => 'No data in this date interval',
                'data'    => $tickets
            ]);
        } else {
            return Excel::download(new TicketExport($tickets), 'SLA_Report_' . $request->from . '_' . $request->to . '.xlsx');
        }
    }
}
