<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\SubDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        // get user role
        $role = Auth::user()->role;

        // check role
        switch ($role) {
            case 'Admin':
                # code...
                break;

            case 'Approver1':
                // get position
                $positions = Position::whereHas('subDepartment', function ($query) {
                    $query->where('department_id', Auth::user()->employee->position->subDepartment->department_id);
                })->withCount('employees')->latest()->get();

                // define view
                $view = 'approver1.position.index';
                break;

            case 'Approver2':
                // get position
                $positions = Position::whereHas('subDepartment', function ($query) {
                    $query->where('id', Auth::user()->employee->position->sub_department_id);
                })->withCount('employees')->latest()->get();

                // define view
                $view = 'approver2.position.index';
                break;

            default:
                abort(404);
                break;
        }

        // draw table
        if ($request->ajax()) {
            return DataTables::of($positions)
                ->addIndexColumn()
                ->addColumn('subdept', function ($row) {
                    return $row->subDepartment->name;
                })
                ->addColumn('total', function ($row) {
                    return $row->employees_count;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btn-edit-position" data-id="' . $row->id . '" class="btn btn-primary btn-sm" title="Edit this position"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-position" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Delete this position"><i class="fa-solid fa-eraser"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view($view, [
            'title' => 'Positions - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function getSubdept()
    {
        // query sub dept in user dept
        $subdept = SubDepartment::where('department_id', Auth::user()->employee->position->subDepartment->department_id)->get();

        // return response
        return response()->json($subdept);
    }

    public function store(Request $request)
    {
        // get user role
        $role = Auth::user()->role;

        // check role 
        switch ($role) {
            case 'Admin':
                # code...
                break;

            case 'Approver1':
                // get subdept id
                $subdept = $request->sub_department_id;

                // set validation
                $validator = Validator::make($request->all(), [
                    'sub_department_id' => 'required',
                    'name' => [
                        'required',
                        Rule::unique('positions')->where('sub_department_id', $subdept)
                    ]
                ]);
                break;

            case 'Approver2':
                // get subdept id
                $subdept = Auth::user()->employee->position->subDepartment->id;

                // set validation
                $validator = Validator::make($request->all(), [
                    'name' => [
                        'required',
                        Rule::unique('positions')->where('sub_department_id', $subdept)
                    ]
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

        // create position
        $position = new Position;
        $position->name = $request->name;
        $position->subDepartment()->associate($subdept);
        $position->save();

        // return success response
        return response()->json([
            'success' => true,
            'message' => 'New position has been added',
            'data'    => $position
        ]);
    }

    public function show(Position $position)
    {
        // return response
        return response()->json([
            'success' => true,
            'message' => 'Position detail',
            'data'    => $position
        ]);
    }

    public function update(Position $position, Request $request)
    {
        // get subdept id
        $subdept = Auth::user()->employee->position->subDepartment->id;

        // set validation
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('positions')->ignore($position->id)->where('sub_department_id', $subdept)
            ]
        ]);

        // check if validation fails
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // update position
        $position->name = $request->name;
        $position->subDepartment()->associate($subdept);
        $position->save();

        // return success response
        return response()->json([
            'success' => true,
            'message' => 'The position has been updated',
            'data'    => $position
        ]);
    }

    public function destroy(Position $position)
    {
        // delete position
        $position->delete();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'The position has been deleted'
        ]);
    }
}
