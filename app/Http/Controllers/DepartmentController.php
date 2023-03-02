<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('name', 'asc')->get();

        return view('admin.department.index', [
            'title'         => 'Departments - Helpdesk Ticketing System',
            'departments'   => $departments
        ]);
    }
}
