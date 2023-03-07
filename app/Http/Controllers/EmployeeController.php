<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\SubDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

    public function getSubDepts($dept)
    {
        // get all sub department based on department
        $subdepts = SubDepartment::where('department_id', $dept)->get();

        // return response
        return response()->json($subdepts);
    }

    public function getPositions($subdept)
    {
        // gel all position based on sub department
        $positions = Position::where('sub_department_id', $subdept)->get();

        // return response
        return response()->json($positions);
    }

    public function store(Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'nik'         => 'required|min_digits:6|max_digits:6|integer',
            'name'        => 'required',
            'dept'        => 'required',
            'subdept'     => 'required',
            'position_id' => 'required'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // create employee
        $employee = Employee::create([
            'nik'          => $request->nik,
            'name'         => $request->name,
            'position_id'  => $request->position_id,
        ]);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'New employee has been added',
            'data'    => $employee
        ]);
    }
}
