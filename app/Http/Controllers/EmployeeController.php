<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\SubDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('approver.employee.index', [
            'title' => 'New employee - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function getDepts()
    {
        // get all department
        $depts = Department::all('id', 'name');

        // return response
        return response()->json($depts);
    }

    public function getSubDepts(Request $request)
    {
        // get all sub department based on department
        $subdepts = SubDepartment::where('department_id', $request->id)->get();

        // return response
        return response()->json($subdepts);
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
        // set validation
        $validator = Validator::make($request->all(), [
            'nik'         => 'required|min_digits:6|max_digits:6|integer|unique:employees',
            'name'        => 'required',
            'image'       => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
            'dept'        => 'required',
            'subdept'     => 'required',
            'position_id' => 'required'
        ]);

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
        $employees = Employee::latest()->get();

        // draw table
        if ($request->ajax()) {
            return DataTables::of($employees)
                ->addIndexColumn()
                ->addColumn('dept', function ($row) {
                    return $row->position->subDepartment->department->name;
                })
                ->addColumn('subdept', function ($row) {
                    return $row->position->subDepartment->name;
                })
                ->addColumn('position', function ($row) {
                    return $row->position->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('approver.employees.show', $row->nik) . '" class="btn btn-primary btn-sm" title="Edit this employee"> <i class="fa-solid fa-pen-to-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-employee" data-id="' . $row->nik . '" class="btn btn-danger btn-sm" title="Delete this employee"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // return to view
        return view('approver.employee.list', [
            'title' => 'List Employess - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function show($employee)
    {
        // get employee
        $employee = Employee::where('nik', $employee)->first();

        // return view
        return view('approver.employee.edit', [
            'title'    => "$employee->name - Helpdesk Ticketing System",
            'name'     => Auth::user()->employee->name,
            'employee' => $employee
        ]);
    }

    public function update(Request $request)
    {
        // get employe
        $employee = Employee::where('nik', $request->nik)->first();

        // set validation
        $validator = Validator::make($request->all(), [
            'name'        => 'required',
            'position_id' => 'required',
            'image'       => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
            'dept'        => 'required',
            'subdept'     => 'required',
        ]);

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

        // check if employee use deafult profile
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
}
