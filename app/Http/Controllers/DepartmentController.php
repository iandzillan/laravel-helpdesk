<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::latest()->get();

        if ($request->ajax()) {
            return DataTables::of($departments)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btn-edit-department" data-id=' . $row->id . ' title="Edit this department" class="btn btn-primary btn-sm"> <i class="fa-solid fa-pen-to-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-department" data-id=' . $row->id . ' title="Delete this department" class="btn btn-danger btn-sm"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.department.index', [
            'title'         => 'Departments - Helpdesk Ticketing System',
            'name'          => Auth::user()->userable->name,
            'departments'   => $departments
        ]);
    }

    public function store(Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:departments'
        ]);

        // check if validation fails()
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // create department
        $department = Department::create([
            'name' => $request->name
        ]);

        // return success response
        return response()->json([
            'success' => true,
            'message' => 'New department has been added',
            'data'    => $department
        ]);
    }

    public function show(Department $department)
    {
        // return response
        return response()->json([
            'success' => true,
            'message' => 'Department detail',
            'data'    => $department
        ]);
    }

    public function update(Request $request, Department $department)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:departments,name,' . $department->id
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // update department
        $department->update([
            'name' => $request->name
        ]);

        // return success response
        return response()->json([
            'success' => true,
            'message' => 'The department has been updated',
            'data'    => $department
        ]);
    }

    public function destroy(Department $department)
    {
        // delete department
        $department->delete();

        // return success response
        return response()->json([
            'success' => true,
            'message' => 'The department has been deleted'
        ]);
    }
}
