<?php

namespace App\Http\Controllers;

use App\Models\Urgency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UrgencyController extends Controller
{
    public function index(Request $request)
    {
        $urgencies = Urgency::orderBy('hours', 'ASC')->get();

        // draw table
        if ($request->ajax()) {
            return DataTables::of($urgencies)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btn-edit-urgency" data-id="' . $row->id . '" class="btn btn-primary btn-sm" title="Edit this urgency"> <i class="fa-solid fa-pen-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-urgency" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Delete this urgency"> <i class="fa-solid fa-eraser"></i> </a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.urgency.index', [
            'title'     => 'Urgencies - Helpdesk Ticketing System',
            'name'      => Auth::user()->employee->name,
            'urgencies' => $urgencies
        ]);
    }

    public function store(Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'name'  => 'required|unique:urgencies',
            'hours' => 'required|integer',
        ], [
            'hours.required' => 'The duration field is required',
            'hours.integer' => 'The duration field must be number'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // ungercy create
        $urgency = Urgency::create([
            'name'  => $request->name,
            'hours' => $request->hours
        ]);

        // return response
        return response()->json([
            'success'   => true,
            'message'   => 'New urgnecy has been added',
            'data'      => $urgency
        ]);
    }

    public function show(Urgency $urgency)
    {
        // return response
        return response()->json([
            'success' => true,
            'message' => 'Detail urgency',
            'data'    => $urgency
        ]);
    }

    public function update(Request $request, Urgency $urgency)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'name'  => 'required|unique:urgencies,name,' . $urgency->id,
            'hours' => 'required|integer'
        ], [
            'hours.required' => 'The duration field is required',
            'hours.integer'  => 'The duration field must be number'
        ]);

        // check if validation fails()
        if ($validator->fails()) {
            // return response error
            return response()->json($validator->errors(), 422);
        }

        // update urgency
        $urgency->update([
            'name'  => $request->name,
            'hours' => $request->hours
        ]);

        // return response success
        return response()->json([
            'success' => true,
            'message' => 'The urgnecy has been updated',
            'data'    => $urgency
        ]);
    }

    public function destroy(Urgency $urgency)
    {
        // delete urgency
        $urgency->delete();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'The urgency has been deleted'
        ]);
    }
}
