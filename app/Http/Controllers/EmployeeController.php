<?php

namespace App\Http\Controllers;

use App\Mail\MailUserRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\SubDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index()
    {
        // get user role in session
        $role = Auth::user()->role;

        // check role
        switch ($role) {
            case 'Admin':
                $view = 'admin.employee.index';
                break;

            case 'Approver1':
                $view = 'approver1.employee.index';
                break;

            case 'Approver2':
                $view = 'approver2.employee.index';
                break;

            default:
                return abort(404);
                break;
        }

        return view($view, [
            'title' => 'New employee - Helpdesk Ticketing System',
            'name' => Auth::user()->employee->name
        ]);
    }

    public function getDepts()
    {
        // get all dept
        $depts = Department::doesntHave('employees')->get();

        // return response
        return response()->json($depts);
    }

    public function getSubdepts(Request $request)
    {
        // get all subdept
        $subdept = SubDepartment::where('department_id', $request->id)->get();

        // return response
        return response()->json($subdept);
    }

    public function getPositions(Request $request)
    {
        // gel all position based on sub department
        $positions = Employee::select('position')->where('sub_department_id', $request->id)->groupBy('position')->pluck('position');

        // return response
        return response()->json($positions);
    }

    public function store(Request $request)
    {
        // get user role
        $role = Auth::user()->role;

        // check role
        switch ($role) {
            case 'Admin':
                // set validation
                $validator = Validator::make($request->all(), [
                    'nik'           => 'required|min_digits:6|max_digits:6|integer|unique:employees',
                    'name'          => 'required',
                    'position'      => 'required',
                    'image'         => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                    'department_id' => 'required'
                ], [
                    'department_id.required' => 'The department field is required.'
                ]);
                break;

            case 'Approver1':
                // set validation
                $validator = Validator::make($request->all(), [
                    'nik'               => 'required|min_digits:6|max_digits:6|integer|unique:employees',
                    'name'              => 'required',
                    'image'             => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                    'sub_department_id' => 'required',
                    'position'          => 'required'
                ], [
                    'sub_department_id.required' => 'The sub department field is required'
                ]);
                break;

            case 'Approver2':
                // set validation
                $validator = Validator::make($request->all(), [
                    'nik'         => 'required|min_digits:6|max_digits:6|integer|unique:employees',
                    'name'        => 'required',
                    'image'       => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                    'position'    => 'required'
                ]);
                break;

            default:
                abort(404);
                break;
        }

        // check if validation fails
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // check if there is image
        if ($request->hasFile('image')) {
            // set name for image
            $image_ext  = $request->image->getClientOriginalExtension();
            $image_name = $request->nik . '-' . $request->name . '.' . $image_ext;
            // save to app storage
            $request->image->storeAs('public/uploads/photo-profile', $image_name);
        } else {
            // default name
            $image_name = "avtar_1.png";
        }

        // create employee
        switch ($role) {
            case 'Admin':
                $employee = Employee::create([
                    'nik'           => $request->nik,
                    'name'          => $request->name,
                    'image'         => $image_name,
                    'position'      => $request->position,
                    'department_id' => $request->department_id,
                    'isRequest'     => 1
                ]);
                break;

            case 'Approver1':
                $employee = Employee::create([
                    'nik'               => $request->nik,
                    'name'              => $request->name,
                    'image'             => $image_name,
                    'position'          => $request->position,
                    'department_id'     => Auth::user()->employee->department_id,
                    'sub_department_id' => $request->sub_department_id
                ]);
                break;

            default:
                $employee = Employee::create([
                    'nik'               => $request->nik,
                    'name'              => $request->name,
                    'image'             => $image_name,
                    'position'          => $request->position,
                    'department_id'     => Auth::user()->employee->department_id,
                    'sub_department_id' => Auth::user()->employee->sub_department_id
                ]);
                break;
        }

        // return response
        return response()->json([
            'success' => true,
            'message' => 'New employee has been added',
            'data'    => $employee
        ]);
    }

    public function list(Request $request)
    {
        // get user role 
        $role = Auth::user()->role;

        // check role
        switch ($role) {
            case 'Admin':
                // get all manager
                $employees = Employee::where('position', 'Manager')->latest()->get();

                // route show employee
                $route = 'admin.managers.show';

                // view admin
                $view = 'admin.employee.list';

                // title page
                $title = 'List Managers - Helpdesk Ticketing System';
                break;

            case 'Approver1':
                // query employee edger loading
                $employees = Employee::where('department_id', Auth::user()->employee->department_id)->where('sub_department_id', '!=', 'NULL')->latest()->get();

                // route show employee
                $route = 'dept.employees.show';

                // view approver1
                $view = 'approver1.employee.list';

                // title page
                $title = 'List Employees - Helpdesk Ticketing System';
                break;

            case 'Approver2':
                // query employee edger loading
                $employees = Employee::where('sub_department_id', Auth::user()->employee->sub_department_id)->where('name', '!=', Auth::user()->employee->name)->latest()->get();

                // route show employee
                $route = 'subdept.employees.show';

                // view approver1
                $view = 'approver2.employee.list';

                // title page
                $title = 'List Employees - Helpdesk Ticketing System';
                break;

            default:
                abort(404);
                break;
        }

        // draw table
        if ($request->ajax()) {
            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('dept', function ($row) {
                    return $row->department->name;
                })
                ->addColumn('subdept', function ($row) {
                    if ($row->position != 'Manager') {
                        return $row->subDepartment->name;
                    }
                })
                ->addColumn('action', function ($row) use ($route) {
                    $btn = '<a href="' . route($route, $row->nik) . '" class="btn btn-primary btn-sm" title="Edit this employee"> <i class="fa-solid fa-pen-to-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" data-id="' . $row->nik . '" id="btn-delete-employee" class="btn btn-danger btn-sm" title="Delete this employee"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // return to view
        return view($view, [
            'title' => $title,
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function show($employee)
    {
        // get user role
        $role = Auth::user()->role;

        // get employee
        $employee = Employee::where('nik', $employee)->first();

        // check role
        switch ($role) {
            case 'Admin':
                $view = 'admin.employee.edit';
                break;

            case 'Approver1':
                $view = 'approver1.employee.edit';
                break;

            case 'Approver2':
                $view = 'approver2.employee.edit';
                break;

            default:
                abort(404);
                break;
        }

        // return view
        return view($view, [
            'title'    => "$employee->name - Helpdesk Ticketing System",
            'name'     => Auth::user()->employee->name,
            'employee' => $employee
        ]);
    }

    public function update(Request $request)
    {
        // get user role
        $role = Auth::user()->role;

        // get employee
        $employee = Employee::where('nik', $request->nik)->first();

        // check role
        switch ($role) {
            case 'Admin':
                // set validation
                $validator = Validator::make($request->all(), [
                    'name'          => 'required',
                    'position'      => 'required',
                    'image'         => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                    'department_id' => 'required'
                ], [
                    'department_id.required' => 'The department field is required.'
                ]);
                break;

            case 'Approver1':
                // set validation
                $validator = Validator::make($request->all(), [
                    'name'              => 'required',
                    'position'          => 'required',
                    'sub_department_id' => 'required',
                    'image'             => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                ], [
                    'sub_department_id.required' => 'The sub department field is required'
                ]);
                break;

            case 'Approver2':
                // set validation
                $validator = Validator::make($request->all(), [
                    'name'     => 'required',
                    'position' => 'required',
                    'image'    => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                ]);
                break;

            default:
                abort(404);
                break;
        }

        // check if validation fails
        if ($validator->fails()) {
            // return respone error
            return response()->json($validator->errors(), 422);
        }

        // check if there's file
        if ($request->hasFile('image')) {
            // check if employee not use default image
            if ($employee->image != "avtar_1.png") {
                // delete old image
                Storage::delete('public/uploads/photo-profile/' . $employee->image);
            }

            // get ext file
            $image_ext = $request->image->getClientOriginalExtension();

            // set new name
            $image_name = "$request->nik-$request->name.$image_ext";

            // save to app storage
            $request->image->storeAs('public/uploads/photo-profile', $image_name);
        } else {
            // check if employee not use default image
            if ($employee->image != "avtar_1.png") {
                // get ext file
                $image_ext = explode(".", $employee->image, 2);
                $image_ext = end($image_ext);

                // set name image
                $image_name = "$employee->nik-$request->name.$image_ext";

                // Rename image on app storage
                Storage::move('public/uploads/photo-profile/' . $employee->image, 'public/uploads/photo-profile/' . $image_name);
            }
            // use old image
            $image_name = $employee->image;
        }

        switch ($role) {
            case 'Admin':
                // update manager
                $employee->update([
                    'name'          => $request->name,
                    'image'         => $image_name,
                    'position'      => $request->position,
                    'department_id' => $request->department_id
                ]);
                break;

            case 'Approver1':
                // update manager
                $employee->update([
                    'name'              => $request->name,
                    'image'             => $image_name,
                    'position'          => $request->position,
                    'sub_department_id' => $request->sub_department_id
                ]);
                break;

            default:
                // update employee
                $employee->update([
                    'name'         => $request->name,
                    'image'        => $image_name,
                    'position'     => $request->position
                ]);
                break;
        }

        // return success response
        return response()->json([
            'success' => true,
            'message' => 'The employee has been updated',
            'data'    => $employee
        ]);
    }

    public function destroy($employee)
    {
        // get employee
        $employee = Employee::where('nik', $employee)->first();

        // check if employee not use deafult profile
        if ($employee->image != 'avtar_1.png') {
            // delete employe photo profile
            Storage::delete("public/uploads/photo-profile/$employee->image");
        }

        // delete employee
        $employee->delete();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'The employee has been deleted'
        ]);
    }

    public function userRequestList(Request $request)
    {
        // get user role
        $role = Auth::user()->role;

        // check role
        switch ($role) {
            case 'Approver1':
                // query employee with edger loading
                $employees = Employee::where('department_id', Auth::user()->employee->department_id)->where('sub_department_id', '!=', 'null')->where('isRequest', 0)->latest()->get();

                // define view
                $view = 'approver1.employee.user-request';
                break;

            case 'Approver2':
                // query employee with edger loading
                $employees = Employee::where('sub_department_id', Auth::user()->employee->sub_department_id)->where('sub_department_id', '!=', 'null')->where('isRequest', 0)->latest()->get();

                // defien view 
                $view = 'approver2.employee.user-request';
                break;

            default:
                abort(404);
                break;
        }

        // draw table
        if ($request->ajax()) {
            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('subdept', function ($row) {
                    return $row->subDepartment->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="user-request-button" data-id="' . $row->nik . '" class="btn btn-primary btn-sm" title="Request user account"><i class="fa-solid fa-user-plus"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // return view
        return view($view, [
            'title' => 'User Account Request - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function userRequest($employee)
    {
        // get employee
        $employee = Employee::where('nik', $employee)->first();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Detail employee',
            'data'    => $employee
        ]);
    }

    public function isRequest(Request $request, $employee)
    {
        // get employee who has account request
        $employee = Employee::where('nik', $employee)->first();

        // set validation
        $validator = Validator::make($request->all(), [
            'nik'      => 'required',
            'name'     => 'required',
            'email'    => 'required|unique:users|email',
            'username' => 'required|unique:users',
            'password' => 'required|confirmed',
            'role'     => 'required',
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // update employee status request
        $employee->update([
            'isRequest' => 1
        ]);

        // return success response
        return response()->json([
            'success' => true,
            'message' => 'The employee data has been updated',
            'data'    => $employee
        ]);
    }

    public function sendRequest(Request $request)
    {
        $email_admin = 'admin@example.com';
        $data = [
            'nik'      => $request->nik,
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => $request->password,
            'role'     => $request->role,
        ];

        Mail::to($email_admin)->send(new MailUserRequest($data));

        return response()->json([
            'success' => true,
            'message' => 'User account request has been sended'
        ]);
    }

    public function accountProfile()
    {
        // get employee
        $employee = Employee::where('id', Auth::user()->employee_id)->first();

        return view('layouts.account-profile', [
            'title'    => 'Account profile - Helpdesk Ticketing System',
            'name'     => Auth::user()->employee->name,
            'employee' => $employee
        ]);
    }

    public function updateProfile(Request $request, $nik)
    {
        // get employee
        $employee = Employee::where('nik', $nik)->first();

        // set validation
        if ($request->oldpassword || $request->password || $request->password_confirmation) {
            $validator = Validator::make($request->all(), [
                'image'                 => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                'name'                  => 'required',
                'oldpassword'           => 'required',
                'password'              => 'required|confirmed',
                'password_confirmation' => 'required'
            ], [
                'oldpassword.required'  => 'The old password field is required'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'image'                 => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                'name'                  => 'required',
                'oldpassword'           => 'sometimes',
                'password'              => 'sometimes|confirmed',
                'password_confirmation' => 'sometimes'
            ]);
        }

        // check validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // check if there's file
        if ($request->hasFile('image')) {
            // check if employee not use default image
            if ($employee->image != "avtar_1.png") {
                // delete old image
                Storage::delete('public/uploads/photo-profile/' . $employee->image);
            }

            // get ext file
            $image_ext = $request->image->getClientOriginalExtension();

            // set new name
            $image_name = "$request->nik-$request->name.$image_ext";

            // save to app storage
            $request->image->storeAs('public/uploads/photo-profile', $image_name);
        } else {
            // check if employee not use default image
            if ($employee->image != "avtar_1.png") {
                // get ext file
                $image_ext = explode(".", $employee->image, 2);
                $image_ext = end($image_ext);

                // set name image
                $image_name = "$employee->nik-$request->name.$image_ext";

                // Rename image on app storage
                Storage::move('public/uploads/photo-profile/' . $employee->image, 'public/uploads/photo-profile/' . $image_name);
            }
            // use old image
            $image_name = $employee->image;
        }

        if ($request->oldpassword) {
            // check password
            if (Hash::check($request->oldpassword, Auth::user()->password)) {
                $employee->image          = $image_name;
                $employee->name           = $request->name;
                $employee->user->password = Hash::make($request->password);
                $employee->push();

                $success = true;
                $message = 'Your profile has been updated';
            } else {
                $success = false;
                $message = 'Your old password is wrong';
            }
        } else {
            $employee->image          = $image_name;
            $employee->name           = $request->name;
            $employee->push();

            $success = true;
            $message = 'Your profile has been updated';
        }


        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $employee
        ]);
    }
}
