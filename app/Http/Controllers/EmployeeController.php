<?php

namespace App\Http\Controllers;

use App\Mail\MailUserRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\SubDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                return view('admin.employee.index', [
                    'title' => 'New employee - Helpdesk Ticketing System',
                    'name'  => Auth::user()->employee->name
                ]);
                break;

            case 'Approver1':
                return view('approver1.employee.index', [
                    'title' => 'New employee - Helpdesk Ticketing System',
                    'name'  => Auth::user()->employee->name
                ]);
                break;

            case 'Approver2':
                return view('approver2.employee.index', [
                    'title' => 'New employee - Helpdesk Ticketing System',
                    'name'  => Auth::user()->employee->name
                ]);
                break;

            default:
                return abort(404);
                break;
        }
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
        $positions = Position::where('sub_department_id', $request->id)->get();

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
                    'nik'               => 'required|min_digits:6|max_digits:6|integer|unique:employees',
                    'name'              => 'required',
                    'image'             => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                    'department_id'     => 'required',
                    'sub_department_id' => 'required',
                    'position_id'       => 'required'
                ], [
                    'department_id.required'     => 'The department field is required',
                    'sub_department_id.required' => 'The sub department field is required',
                    'position_id.required'       => 'The position field is required'
                ]);
                break;

            case 'Approver1':
                // set validation
                $validator = Validator::make($request->all(), [
                    'nik'               => 'required|min_digits:6|max_digits:6|integer|unique:employees',
                    'name'              => 'required',
                    'image'             => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                    'sub_department_id' => 'required',
                    'position_id'       => 'required'
                ], [
                    'sub_department_id.required' => 'The sub department field is required',
                    'position_id.required'       => 'The position field is required'
                ]);
                break;

            case 'Approver2':
                // set validation
                $validator = Validator::make($request->all(), [
                    'nik'         => 'required|min_digits:6|max_digits:6|integer|unique:employees',
                    'name'        => 'required',
                    'image'       => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                    'position_id' => 'required'
                ], [
                    'position_id.required'       => 'The position field is required'
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
        $employee = Employee::create([
            'nik'          => $request->nik,
            'name'         => $request->name,
            'image'        => $image_name,
            'position_id'  => $request->position_id
        ]);

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
                # code...
                break;

            case 'Approver1':
                // query employee edger loading
                $employees = Employee::with(['position', 'position.subDepartment'])->whereHas('position.subDepartment', function ($query) {
                    $query->where('department_id', Auth::user()->employee->position->subDepartment->department_id);
                })->where('id', '!=', Auth::user()->employee_id)->latest()->get();

                // route show employee
                $route = 'dept.employees.show';

                // view approver1
                $view = 'approver1.employee.list';
                break;

            case 'Approver2':
                // query employee edger loading
                $employees = Employee::with(['position', 'position.subDepartment'])->whereHas('position.subDepartment', function ($query) {
                    $query->where('id', Auth::user()->employee->position->sub_department_id);
                })->where('id', '!=', Auth::user()->employee_id)->latest()->get();

                // route show employee
                $route = 'subdept.employees.show';

                // view approver1
                $view = 'approver2.employee.list';
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
                    return $row->position->subDepartment->name;
                })
                ->addColumn('position', function ($row) {
                    return $row->position->name;
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
            'title' => 'List Employess - Helpdesk Ticketing System',
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
                # code...
                break;

            case 'Approver1':
                // set validation
                $validator = Validator::make($request->all(), [
                    'name'              => 'required',
                    'position_id'       => 'required',
                    'sub_department_id' => 'required',
                    'image'             => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                ], [
                    'sub_department_id.required' => 'The sub department field is required',
                    'position_id.required'       => 'The position field is required'
                ]);
                break;

            case 'Approver2':
                // set validation
                $validator = Validator::make($request->all(), [
                    'name'        => 'required',
                    'position_id' => 'required',
                    'image'       => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
                ], [
                    'position_id.required'       => 'The position field is required'
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
            // use old image
            $image_name = $employee->image;
        }

        // update employee
        $employee->update([
            'name'         => $request->name,
            'image'        => $image_name,
            'position_id'  => $request->position_id
        ]);

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
                $employees = Employee::with(['position', 'position.subDepartment'])->whereHas('position.subDepartment', function ($query) {
                    $query->where('department_id', Auth::user()->employee->position->subDepartment->department_id);
                })->where('isRequest', 0)->latest()->get();

                // define view
                $view = 'approver1.employee.user-request';
                break;

            case 'Approver2':
                // query employee with edger loading
                $employees = Employee::with('position')->whereHas('position', function ($query) {
                    $query->where('sub_department_id', Auth::user()->employee->position->sub_department_id);
                })->where('isRequest', 0)->latest()->get();

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
                    return $row->position->subDepartment->name;
                })
                ->addColumn('position', function ($row) {
                    return $row->position->name;
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

    public function isRequest(Request $request)
    {
        // get employee who has account request
        $employee = Employee::where('nik', $request->nik)->first();

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
}
