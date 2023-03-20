<?php

namespace App\Http\Controllers;

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
            'title' => $title,
            'name'  => Auth::user()->userable->name
        ]);
    }
}
