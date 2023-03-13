<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\SubDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SubDepartmentController extends Controller
{
    public function index(Request $request)
    {
        $sub_departments = SubDepartment::with('department')->where('department_id', Auth::user()->employee->position->subDepartment->department_id)->latest()->get();

        if ($request->ajax()) {
            return DataTables::of($sub_departments)
                ->addIndexColumn()
                ->addColumn('department', function ($row) {
                    return $row->department->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btn-edit-subdept" data-id="' . $row->id . '" class="btn btn-primary btn-sm" title="Edit this sub department"> <i class="fa-solid fa-pen-to-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-subdept" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Delete this sub department"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.subdepartment.index', [
            'title'             => 'Sub Departments - Helpdesk Ticketing System',
            'name'              => Auth::user()->employee->name,
            'sub_departments'   => $sub_departments
        ]);
    }

    public function getDepts()
    {
        $depts = Department::all('id', 'name');
        return response()->json($depts);
    }

    public function store(Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'department_id' => 'required',
            'name'          => 'required|unique:sub_departments'
        ]);

        // check if validation fails()
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // create sub department
        $dept = Department::where('id', $request->department_id)->first();
        $subdept = new SubDepartment;
        $subdept->name = $request->name;
        $subdept->department()->associate($dept);
        $subdept->save();

        // return success response
        return response()->json([
            'success' => true,
            'message' => 'New sub department has been added',
            'data'    => $subdept
        ]);
    }

    public function show($subdept)
    {
        $subdept = SubDepartment::with('department')->where('id', $subdept)->first();
        $depts = Department::all('id', 'name');

        return response()->json([
            'success' => true,
            'message' => 'Sub department detail',
            'data'    => $subdept,
            'dept'    => $subdept->department,
            'depts'   => $depts
        ]);
    }

    public function update(Request $request, SubDepartment $subdept)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sub_departments,name,' . $subdept->id,
            'department_id' => 'required'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // update sub department
        $dept = Department::where('id', $request->department_id)->first();
        $subdept->name = $request->name;
        $subdept->department()->associate($dept);
        $subdept->save();

        // return success response
        return response()->json([
            'success' => true,
            'message' => 'The sub department has been updated',
            'data'    => $subdept
        ]);
    }

    public function destroy(SubDepartment $subdept)
    {
        // delete sub department
        $subdept->delete();

        // return success respone
        return response()->json([
            'success' => true,
            'message' => 'The sub department has been deleted'
        ]);
    }
}
