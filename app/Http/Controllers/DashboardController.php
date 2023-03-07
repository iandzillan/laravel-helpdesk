<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function adminHome()
    {
        return view('admin.dashboard', [
            'title' => 'Admin Dashboard - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function approverHome()
    {
        return view('approver.dashboard', [
            'title' => 'Approver Dashboard - Helpdesk Ticketing system',
            'name'  => Auth::user()->employee->name
        ]);
    }
}
