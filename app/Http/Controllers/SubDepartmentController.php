<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\SubDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SubDepartmentController extends Controller
{
    public function index(Request $request)
    {
        $sub_departments = SubDepartment::where('department_id', Auth::user()->employee->department_id)->withCount('employees')->latest()->get();

        if ($request->ajax()) {
            return DataTables::of($sub_departments)
                ->addIndexColumn()
                ->addColumn('total', function ($row) {
                    return $row->employees_count;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btn-edit-subdept" data-id="' . $row->id . '" class="btn btn-primary btn-sm" title="Edit this sub department"> <i class="fa-solid fa-pen-to-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-subdept" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Delete this sub department"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('approver1.subdepartment.index', [
            'title' => 'Sub Departments - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name,
        ]);
    }

    public function store(Request $request)
    {
        // get dept id
        $dept = Auth::user()->employee->department_id;
        // set validation
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('sub_departments')->where('department_id', $dept)
            ]
        ]);

        // check if validation fails()
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // create sub department
        $dept = Department::where('id', Auth::user()->employee->department_id)->first();
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

    public function show(SubDepartment $subdept)
    {
        return response()->json([
            'success' => true,
            'message' => 'Sub department detail',
            'data'    => $subdept
        ]);
    }

    public function update(Request $request, SubDepartment $subdept)
    {
        // get dept id
        $dept = Auth::user()->employee->department_id;

        // set validation
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('sub_departments')->ignore($subdept->id)->where('department_id', $dept)
            ]
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // update sub department
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
