<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.user.index', [
            'title' => 'Users - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name,
            'users' => $users
        ]);
    }
}
