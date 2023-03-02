<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Make table
        $categories = Category::latest()->get();

        if ($request->ajax()) {
            return datatables()->of($categories)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" id="btn-edit-category" data-id="'.$row->id.'" class="btn btn-primary btn-sm" title="Edit this category"> <i class="fa-solid fa-pen-to-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-category" data-id="'.$row->id.'" class="btn btn-danger btn-sm" title="Delete this category"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.category.index', [
            'title'         => 'Categories - Helpdesk Ticketing System',
            'categories'    => $categories
        ]);
    }

    public function store(Request $request)
    {
        // set validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories'
        ]);

        // check if validation fails 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // create category
        $category = Category::create([
            'name' => $request->name
        ]);

        // return response
        return response()->json([
            'success'   => true, 
            'message'   => 'New category has been added',
            'data'      => $category
        ]);
    }

    public function show(Category $category)
    {
        // return response
        return response()->json([
            'success' => true,
            'message' => 'Detail category',
            'data'    => $category  
        ]);
    }

    public function edit(Request $request, Category $category)
    {
        // Set validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,'.$category->id
        ]);

        // check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // update category
        $category->update([
            'name' => $request->name
        ]);

        // return response
        return response()->json([
            'success' => true, 
            'message' => 'The category has been updated',
            'data'    => $category
        ]);
    }

    public function destroy($category)
    {
        $category = Category::where('id', $category)->delete();

        return response()->json([
            'success' => true,
            'message' => "The category has been deleted"
        ]);
    }
}
