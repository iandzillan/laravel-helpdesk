<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        $previousURL = session()->get('url.intended');
        $login       = url()->to('/');

        if ($previousURL != $login) {
            $path        = parse_url($previousURL);
            $segment     = explode('/', $path['path']);
            if (count($segment) >= 3) {
                if ($segment[2] == 'entry-tickets' || $segment[2] == 'unassigned-tickets' || $segment[2] == 'my-tickets' || $segment[2] == 'onwork-tickets') {
                    session()->put('url.intended', $previousURL);
                } else {
                    session()->put('url.intended', null);
                }
            } else {
                session()->put('url.intended', null);
            }
        }

        return view('login', [
            'title' => 'Login - Helpdesk Ticketing'
        ]);
    }

    public function loginProcess(Request $request)
    {
        // set validation
        $this->validate($request, [
            'username'  => 'required',
            'password'  => 'required',
        ]);


        // check username & password
        if (Auth::attempt($request->only('username', 'password'), true, false)) {
            switch (Auth::user()->role) {
                case 'Admin':
                    if (session()->has('url.intended')) {
                        return redirect(session()->get('url.intended'));
                    } else {
                        return redirect('/admin/dashboard');
                    }
                    break;

                case 'Approver1':
                    if (session()->has('url.intended')) {
                        return redirect(session()->get('url.intended'));
                    } else {
                        return redirect('/dept/dashboard');
                    }
                    break;

                case 'Approver2':
                    if (session()->has('url.intended')) {
                        return redirect(session()->get('url.intended'));
                    } else {
                        return redirect('/subdept/dashboard');
                    }
                    break;

                case 'User':
                    if (session()->has('url.intended')) {
                        return redirect(session()->get('url.intended'));
                    } else {
                        return redirect('/user/dashboard');
                    }
                    break;

                case 'Technician':
                    if (session()->has('url.intended')) {
                        return redirect(session()->get('url.intended'));
                    } else {
                        return redirect('/technician/dashboard');
                    }
                    break;
            }
        } else {
            return redirect('/')->with('error', 'Username or password might be wrong');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
