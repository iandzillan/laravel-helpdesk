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

        dump(session('url.intended'));

        return view('login', [
            'title' => 'Login - Helpdesk Ticketing'
        ]);
    }

    public function loginProcess(Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        // check validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // check username & password
        if (Auth::attempt($request->only('username', 'password'))) {
            switch (Auth::user()->role) {
                case 'Admin':
                    if (session()->has('url.intended')) {
                        $redirect = session()->get('url.intended');
                    } else {
                        $redirect = '/admin/dashboard';
                    }
                    break;

                case 'Approver1':
                    if (session()->has('url.intended')) {
                        $redirect = session()->get('url.intended');
                    } else {
                        $redirect = '/dept/dashboard';
                    }
                    break;

                case 'Approver2':
                    if (session()->has('url.intended')) {
                        $redirect = session()->get('url.intended');
                    } else {
                        $redirect = '/subdept/dashboard';
                    }
                    break;

                case 'User':
                    if (session()->has('url.intended')) {
                        $redirect = session()->get('url.intended');
                    } else {
                        $redirect = '/user/dashboard';
                    }
                    break;

                case 'Technician':
                    if (session()->has('url.intended')) {
                        $redirect = session()->get('url.intended');
                    } else {
                        $redirect = '/technician/dashboard';
                    }
                    break;
            }

            return response()->json([
                'success'  => true,
                'message'  => 'Login Successfully',
                'link'     => $redirect
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed'
            ]);
        }
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->flush();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
