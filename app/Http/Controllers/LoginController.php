<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        return view('login', [
            'title' => 'Login - Helpdesk Ticketing'
        ]);
    }

    public function loginProcess(Request $request)
    {
        // set validation
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        // check username & password
        if (Auth::attempt($request->only('username', 'password'))) {
            // check role
            if (Auth::user()->role == 'Admin') {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->role == 'Approver1') {
                return redirect()->route('dept.dashboard');
            } elseif (Auth::user()->role == 'Approver2') {
                return redirect()->route('subdept.dashboard');
            } elseif (Auth::user()->role == 'User') {
                return redirect()->route('user.dashboard');
            } elseif (Auth::user()->role == 'Technician') {
                return redirect()->route('technician.dashboard');
            } else {
                abort(404);
            }
        } else {
            return redirect('/')->with('error', 'Email or password wrong');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
