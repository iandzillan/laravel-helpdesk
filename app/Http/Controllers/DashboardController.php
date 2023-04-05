<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                $total_ticket    = Ticket::all()->count();
                $new_ticket      = Ticket::where('status', 3)->get()->count();
                $onwork_ticket   = Ticket::where('status', 4)->get()->count();
                $pending_ticket  = Ticket::where('status', 5)->get()->count();
                $closed_ticket   = Ticket::where('status', 6)->get()->count();
                $rejected_ticket = Ticket::where('status', 7)->get()->count();
                break;

            case 'Approver1':
                $view  = 'approver1.dashboard';
                $title = 'Manager Dashboard - Helpdesk Ticketing System';
                break;

            case 'Approver2':
                $view  = 'approver2.dashboard';
                $title = 'Sub Dept Head Dashboard - Helpdesk Ticketing System';
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
                'total'    => $total_ticket,
                'new'      => $new_ticket,
                'onwork'   => $onwork_ticket,
                'pending'  => $pending_ticket,
                'closed'   => $closed_ticket,
                'rejected' => $rejected_ticket
            ]
        ]);
    }
}
