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

    public function deptHome()
    {
        return view('approver1.dashboard', [
            'title' => 'Dept Head Dashboard - Helpdesk Ticketing system',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function subdeptHome()
    {
        return view('approver2.dashboard', [
            'title' => 'Sub Dept Head Dashboard - Helpdesk Ticketing system',
            'name'  => Auth::user()->employee->name
        ]);
    }
}
