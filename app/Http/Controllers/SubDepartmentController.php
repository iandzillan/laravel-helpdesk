<?php

namespace App\Http\Controllers;

use App\Models\SubDepartment;
use Illuminate\Http\Request;

class SubDepartmentController extends Controller
{
    public function index()
    {
        $sub_departments = SubDepartment::orderBy('name', 'asc')->get();

        return view('admin.subdepartment.index', [
            'title'             => 'Sub Departments - Helpdesk Ticketing System',
            'sub_departments'   => $sub_departments
        ]);
    }
}
