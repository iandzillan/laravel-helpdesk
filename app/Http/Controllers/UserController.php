<?php

namespace App\Http\Controllers;

use App\Mail\MailAccountActive;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\SubDepartment;
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
                    if ($row->role == 'Admin') {
                        $role = '<span class="badge bg-primary">' . $row->role . '</span>';
                    }
                    if ($row->role == 'Approver1') {
                        $role = '<span class="badge bg-info">' . $row->role . '</span>';
                    }
                    if ($row->role == 'Approver2') {
                        $role = '<span class="badge bg-success">' . $row->role . '</span>';
                    }
                    if ($row->role == 'User') {
                        $role = '<span class="badge bg-secondary">' . $row->role . '</span>';
                    }
                    if ($row->role == 'Technician') {
                        $role = '<span class="badge bg-warning">' . $row->role . '</span>';
                    }

                    return $role;
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

    public function getEmployee()
    {
        $employee = Employee::where('isRequest', 1)->get();
        return response()->json($employee);
    }

    public function store(Request $request)
    {
        // get employee id
        $employee = Employee::where('nik', $request->employee)->first();

        // set validation
        $validator = Validator::make($request->all(), [
            'employee'              => 'required',
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
        $user = new User;
        $user->email    = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role     = $request->role;
        $user->employee()->associate($employee);
        $user->save();

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
        // get data employee
        $employee = Employee::where('nik', $request->employee)->first();

        // get email user
        $email = $request->email;

        $data = [
            'name'     => $employee->name,
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

    public function destroy(User $user)
    {
        // update isRequest employee to 1 again
        $employee = Employee::where('id', $user->employee_id)->first();
        $employee->update([
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
