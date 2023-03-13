<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        // get position
        $positions = Position::whereHas('subDepartment', function ($query) {
            $query->where('name', Auth::user()->employee->position->subDepartment->name);
        })->withCount('employees')->latest()->get();

        // draw table
        if ($request->ajax()) {
            return DataTables::of($positions)
                ->addIndexColumn()
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

        return view('approver.position.index', [
            'title' => 'Positions - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function store(Request $request)
    {
        // get subdept id
        $subdept = Auth::user()->employee->position->subDepartment->id;

        // set validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:positions,name,NULL,id,sub_department_id,' . $subdept
        ]);

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
