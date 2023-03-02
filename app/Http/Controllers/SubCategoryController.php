<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $sub_categories = SubCategory::with('category')->orderBy('name', 'asc')->get();

        // Draw Data Table
        if ($request->ajax()) {
            return datatables()->eloquent($sub_categories)
                ->addIndexColumn()
                ->addColumn(
                    'action', function($row){
                    $btn = '<a href="javascript:void(0)" id="btn-edit-subcategory" data-id="'.$row->id.'" class="btn btn-primary btn-sm" title="Edit this sub category"> <i class="fa-solid fa-pen-to-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-delete-subcategory" data-id="'.$row->id.'" class="btn btn-danger btn-sm" title="Delete this sub category"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['category', 'action'])
                ->make(true);
        }

        return view('admin.subcategory.index', [
            'title'             => 'Sub Categories - Helpdesk Ticketing System',
            'sub_categories'    => $sub_categories
        ]);
    }
}
