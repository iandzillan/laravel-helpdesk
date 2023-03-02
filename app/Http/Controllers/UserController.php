<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.user.index', [
            'title' => 'Users - Helpdesk Ticketing System',
            'users' => $users
        ]);
    }
}
