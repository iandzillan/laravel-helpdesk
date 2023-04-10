<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        // get role
        $role = Auth::user()->role;

        switch ($role) {
            case 'Admin':
                $view  = 'admin.dashboard';
                $title = 'Admin Dashboard - Helpdesk Ticketing System';
                $total_ticket      = Ticket::all();
                $approval_tl       = Ticket::where('status', 1)->get();
                $approval_manager  = Ticket::where('status', 2)->get();
                $unassigned_ticket = Ticket::where('status', 3)->get();
                $onwork_ticket     = Ticket::where('status', 4)->get();
                $pending_ticket    = Ticket::where('status', 5)->get();
                $closed_ticket     = Ticket::where('status', 6)->get();
                $rejected_ticket   = Ticket::where('status', 7)->get();
                break;

            case 'Approver1':
                $view  = 'approver1.dashboard';
                $title = 'Manager Dashboard - Helpdesk Ticketing System';
                $total_ticket      = Ticket::whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $approval_tl       = Ticket::where('status', 1)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $approval_manager  = Ticket::where('status', 2)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $unassigned_ticket = Ticket::where('status', 3)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $onwork_ticket     = Ticket::where('status', 4)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $pending_ticket    = Ticket::where('status', 5)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $closed_ticket     = Ticket::where('status', 6)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $rejected_ticket   = Ticket::where('status', 7)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                break;

            case 'Approver2':
                $view  = 'approver2.dashboard';
                $title = 'Sub Dept Head Dashboard - Helpdesk Ticketing System';
                $total_ticket      = Ticket::whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $approval_tl       = Ticket::where('status', 1)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $approval_manager  = Ticket::where('status', 2)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $unassigned_ticket = Ticket::where('status', 3)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $onwork_ticket     = Ticket::where('status', 4)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $pending_ticket    = Ticket::where('status', 5)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $closed_ticket     = Ticket::where('status', 6)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $rejected_ticket   = Ticket::where('status', 7)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                break;

            case 'User':
                $view  = 'user.dashboard';
                $title = 'User Dashboard - Helpdesk Ticketing System';
                break;

            case 'Technician':
                $view  = 'technician.dashboard';
                $title = 'Technician Dashboard - Helpdesk Ticketing System';
                break;

            default:
                abort(404);
                break;
        }

        return view($view, [
            'title'  => $title,
            'name'   => Auth::user()->employee->name,
            'ticket' => [
                'total'            => $total_ticket,
                'new'              => $approval_tl,
                'approval_manager' => $approval_manager,
                'unassigned'       => $unassigned_ticket,
                'onwork'           => $onwork_ticket,
                'pending'          => $pending_ticket,
                'closed'           => $closed_ticket,
                'rejected'         => $rejected_ticket
            ]
        ]);
    }

    public function getCategoryYear()
    {
        switch (Auth::user()->role) {
            case 'Admin':
                $months = Ticket::select(DB::raw('MONTHNAME(created_at) as month'))->groupBy(DB::raw('MONTHNAME(created_at)'))->orderBy(DB::raw('MONTH(created_at)', 'asc'))->pluck('month')->all();

                $tickets = Ticket::join('sub_categories', 'sub_categories.id', '=', 'tickets.sub_category_id')
                    ->join('categories', 'categories.id', '=', 'sub_categories.category_id')
                    ->select(DB::raw('categories.name as name, count(tickets.id) as count, MONTH(tickets.created_at)'))
                    ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'))
                    ->groupBy(DB::raw('categories.name, MONTH(tickets.created_at)'))
                    ->get();

                $tickets = $tickets->mapToGroups(function ($item, $key) {
                    return [$item['name'] => $item['count']];
                });
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            'name'  => $tickets->keys(),
            'data'  => $tickets->values(),
            'month' => $months
        ]);
    }

    public function getCategoryMonth()
    {
        switch (Auth::user()->role) {
            case 'Admin':
                $months = Ticket::select(DB::raw('MONTHNAME(created_at) as month'))->where(DB::raw('MONTHNAME(created_at)'), date('F'))->groupBy(DB::raw('MONTHNAME(created_at)'))->pluck('month');

                $tickets = Ticket::join('sub_categories', 'sub_categories.id', '=', 'tickets.sub_category_id')
                    ->join('categories', 'categories.id', '=', 'sub_categories.category_id')
                    ->select(DB::raw('categories.name as name, count(tickets.id) as count, MONTH(tickets.created_at)'))
                    ->where(DB::raw('MONTHNAME(tickets.created_at)'), date('F'))
                    ->groupBy(DB::raw('categories.name, MONTH(tickets.created_at)'))
                    ->get();

                $tickets = $tickets->mapToGroups(function ($item, $key) {
                    return [$item['name'] => $item['count']];
                });
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            'name' => $tickets->keys(),
            'data' => $tickets->values(),
            'month' => $months
        ]);
    }

    public function getCategoryWeek()
    {
        switch (Auth::user()->role) {
            case 'Admin':
                $week = Ticket::select(DB::raw('DATE(created_at) AS date'))->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->groupBy('date')->pluck('date');

                $tickets = Ticket::join('sub_categories', 'sub_categories.id', '=', 'tickets.sub_category_id')
                    ->join('categories', 'categories.id', '=', 'sub_categories.category_id')
                    ->select(DB::raw('categories.name as name, count(tickets.id) as count, DATE(tickets.created_at)'))
                    ->whereBetween('tickets.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy(DB::raw('categories.name, DATE(tickets.created_at)'))
                    ->get();

                $tickets = $tickets->mapToGroups(function ($item, $key) {
                    return [$item['name'] => $item['count']];
                });
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            'name' => $tickets->keys(),
            'data' => $tickets->values(),
            'week' => $week
        ]);
    }
}
