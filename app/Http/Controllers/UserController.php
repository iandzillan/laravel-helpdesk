<?php

namespace App\Http\Controllers;

use App\Mail\MailAccountActive;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // get all user
        $users = User::latest()->get();

        // draw table
        if ($request->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('nik', function ($row) {
                    return $row->employee->nik;
                })
                ->addColumn('name', function ($row) {
                    return $row->employee->name;
                })
                ->editColumn('role', function ($row) {
                    $role = $row->role;
                    switch ($role) {
                        case 'Admin':
                            return $role = '<span class="badge bg-primary">Admin</span>';
                            break;
                        case 'Approver1':
                            return $role = '<span class="badge bg-info">Approver Lv.1</span>';
                            break;
                        case 'Approver2':
                            return $role = '<span class="badge bg-success">Approver Lv.2</span>';
                            break;
                        case 'User':
                            return $role = '<span class="badge bg-warning">User</span>';
                            break;
                        case 'Technician':
                            return $role = '<span class="badge bg-danger">Technician</span>';
                            break;
                        default:
                            return $role = '<span class="badge bg-secondary">Undefined</span>';
                            break;
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btn-edit-user" data-id="' . $row->id . '" class="btn btn-primary btn-sm" title="Edit this user"> <i class="fa-solid fa-pen-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-user" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Edit this user"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action', 'role'])
                ->make(true);
        }

        return view('admin.user.index', [
            'title' => 'Users - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name,
        ]);
    }

    public function getEmployees()
    {
        $employees = Employee::where('isRequest', 1)->get();
        return response()->json($employees);
    }

    public function getEmployee($nik)
    {
        $employee = Employee::where('nik', $nik)->first();
        return response()->json($employee);
    }

    public function store(Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'user'                  => 'required',
            'email'                 => 'required|unique:users|email',
            'username'              => 'required|unique:users',
            'password'              => 'required|confirmed',
            'password_confirmation' => 'required',
            'role'                  => 'required'
        ], [
            'password_confirmation:required' => 'The confirm password field is required.'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // create user 
        $employee = Employee::where('nik', $request->user)->first();
        $user = new User;
        $user->email    = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role     = $request->role;
        $employee->user()->save($user);

        // update employee isRequest
        $employee->update([
            'isRequest' => 2
        ]);

        // return success response
        return response()->json([
            'success' => true,
            'message' => 'User has been created',
            'data'    => $user
        ]);
    }

    public function accountActive(Request $request)
    {
        // get data name and email 
        $employee = Employee::where('nik', $request->user)->first();
        $name     = $employee->name;
        $email    = $request->email;

        $data = [
            'name'     => $name,
            'email'    => $email,
            'username' => $request->username,
            'password' => $request->password,
            'role'     => $request->role
        ];

        // send notification
        Mail::to($email)->send(new MailAccountActive($data));

        // return response
        return response()->json([
            'success' => true,
            'message' => 'User account activated',
            'text'    => 'Notification has been sent to user'
        ]);
    }

    public function show(User $user)
    {
        // return response
        return response()->json([
            'success'      => true,
            'message'      => 'Detail user',
            'data'         => $user,
            'dataRelation' => $user->employee
        ]);
    }

    public function update(User $user, Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'username'              => 'required|unique:users,username,' . $user->id,
            'password'              => 'sometimes|confirmed',
            'role'                  => 'required'
        ]);

        // check if validation fail
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // check if user has password
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // update user
        $user->update([
            'email'    => $request->email,
            'username' => $request->username,
            'password' => $user->password,
            'role'     => $request->role
        ]);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'User has been updated',
            'data'    => $user
        ]);
    }

    public function destroy(User $user)
    {
        $user->employee()->update([
            'isRequest' => 1
        ]);

        // delete user
        $user->delete();

        // return response 
        return response()->json([
            'success' => true,
            'message' => 'The user has been deleted'
        ]);
    }
}
