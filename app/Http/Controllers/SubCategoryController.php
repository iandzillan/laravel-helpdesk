<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Employee;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $sub_categories = SubCategory::with(['category', 'user'])->latest()->get();

        // Draw Data Table
        if ($request->ajax()) {
            return DataTables::of($sub_categories)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('technician', function ($row) {
                    return $row->user->employee->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btn-edit-subcategory" data-id="' . $row->id . '" class="btn btn-primary btn-sm" title="Edit this sub category"> <i class="fa-solid fa-pen-to-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-subcategory" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Delete this sub category"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.subcategory.index', [
            'title'             => 'Sub Categories - Helpdesk Ticketing System',
            'name'              => Auth::user()->employee->name
        ]);
    }

    public function getCategories()
    {
        $categories = Category::all('id', 'name');
        return response()->json($categories);
    }

    public function getTechnicians()
    {
        $employees = Employee::whereHas('user', function ($q) {
            $q->where('role', 'Technician');
        })->get(['id', 'name']);
        return response()->json($employees);
    }

    public function store(Request $request)
    {
        // get category id
        $category = Category::where('id', $request->category_id)->first();

        // get user id 
        $user = User::where('employee_id', $request->technician_id)->first();

        // set validation
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'category_id'   => 'required',
            'technician_id' => 'required'
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // create sub category
        $sub_category = new SubCategory;
        $sub_category->name = $request->name;
        $sub_category->category()->associate($category);
        $sub_category->user()->associate($user);
        $sub_category->save();

        // return response
        return response()->json([
            'success'   => true,
            'message'   => 'New sub category has been added',
            'data'      => $sub_category
        ]);
    }

    public function show($subcategory)
    {
        $sub_category = SubCategory::with('category')->where('id', $subcategory)->first();
        $categories   = Category::all('id', 'name');
        $technicians  = Employee::whereHas('user', function ($q) {
            $q->where('role', 'Technician');
        })->get(['id', 'name']);

        return response()->json([
            'success'     => true,
            'message'     => 'Detail sub category',
            'data'        => $sub_category,
            'category'    => $sub_category->category,
            'technician'  => $sub_category->user->employee,
            'categories'  => $categories,
            'technicians' => $technicians
        ]);
    }

    public function update(SubCategory $subcategory, Request $request)
    {
        // get category id
        $category = Category::where('id', $request->category_id)->first();

        // get technician (user id)
        $technician = User::where('employee_id', $request->technician_id)->first();

        // set validation
        $validator = Validator::make($request->all(), [
            'name'          => [
                'required',
                Rule::unique('sub_categories')->ignore($subcategory->id)->where('category_id', $category)
            ],
            'category_id'   => 'required',
            'technician_id' => 'required'
        ], [
            'name.unique' => "This sub category already exists in $category->name category"
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // update sub category
        $category = Category::where('id', $request->category_id)->first();
        $subcategory->name = $request->name;
        $subcategory->category()->associate($category);
        $subcategory->user()->associate($technician);
        $subcategory->save();

        return response()->json([
            'success'   => true,
            'message'   => 'The sub category has been updated',
            'data'      => $subcategory
        ]);
    }

    public function destroy(SubCategory $subcategory)
    {
        $subcategory->delete();

        return response()->json([
            'success' => true,
            'message' => "The sub category has been deleted",
            'data'    => $subcategory
        ]);
    }
}
