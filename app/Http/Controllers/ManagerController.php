<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ManagerController extends Controller
{
    public function index()
    {
        return view('admin.manager.index', [
            'title' => 'Add Manager - Helpdesk Ticketing System',
            'name'  => Auth::user()->userable->name
        ]);
    }

    public function getDepts()
    {
        $depts = Department::all();

        // return response
        return response()->json($depts);
    }

    public function list(Request $request)
    {
        // get all manager
        $managers = Manager::all();

        // draw table
        if ($request->ajax()) {
            return DataTables::of($managers)
                ->addIndexColumn()
                ->addColumn('dept', function ($row) {
                    return $row->department->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('admin.managers.show', $row->id) . '" id="btn-edit-manager" class="btn btn-primary btn-sm" title="Edit this manager"> <i class="fa-solid fa-pen-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-manager" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Delete this manager"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.manager.list', [
            'title' => 'Manager Master Data - Helpdesk Ticketing System',
            'name'  => Auth::user()->userable->name
        ]);
    }

    public function store(Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'nik'           => 'required|min_digits:6|max_digits:6|integer|unique:managers',
            'name'          => 'required',
            'position'      => 'required',
            'image'         => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
            'department_id' => 'required'
        ], [
            'department_id.required' => 'The department field is required.'
        ]);

        // check if validation has error
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

        // create manager
        $manager = Manager::create([
            'nik'           => $request->nik,
            'name'          => $request->name,
            'image'         => $image_name,
            'position'      => $request->position,
            'department_id' => $request->department_id,
            'isRequest'     => 1
        ]);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Manager has been added',
            'data'    => $manager
        ]);
    }

    public function show(Manager $manager)
    {
        // return response
        return view('admin.manager.edit', [
            'title'   => "Edit $manager->name - Helpdesk Ticketing System",
            'name'    => Auth::user()->userable->name,
            'manager' => $manager
        ]);
    }

    public function update(Manager $manager, Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'nik'           => 'required|min_digits:6|max_digits:6|integer|unique:managers,nik,' . $manager->id,
            'name'          => 'required',
            'position'      => 'required',
            'image'         => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
            'department_id' => 'required'
        ], [
            'department_id.required' => 'The department field is required.'
        ]);

        // check if validation has error
        if ($validator->fails()) {
            // return error response
            return response()->json($validator->errors(), 422);
        }

        // check if there's file
        if ($request->hasFile('image')) {
            // check if employee not use default image
            if ($manager->image != "avtar_1.png") {
                // delete old image
                Storage::delete('public/uploads/photo-profile/' . $manager->image);
            }

            // get ext file
            $image_ext = $request->image->getClientOriginalExtension();

            // set new name
            $image_name = "$request->nik-$request->name.$image_ext";

            // save to app storage
            $request->image->storeAs('public/uploads/photo-profile', $image_name);
        } else {
            // use old image
            $image_name = $manager->image;
        }

        // update manager
        $manager->update([
            'name'          => $request->name,
            'image'         => $image_name,
            'position'      => $request->position,
            'department_id' => $request->department_id
        ]);

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Manager has been updated',
            'data'    => $manager
        ]);
    }

    public function destroy(Manager $manager)
    {
        // check if employee not use deafult profile
        if ($manager->image != 'avtar_1.png') {
            // delete employe photo profile
            Storage::delete("public/uploads/photo-profile/$manager->image");
        }

        // delete manager
        $manager->delete();

        // return response
        return response()->json([
            'success' => true,
            'message' => 'Manager has been deleted'
        ]);
    }
}
