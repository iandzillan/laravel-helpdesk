<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // get all user
        $users = User::orderBy('role', 'ASC')->get();

        // draw table
        if ($request->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('nik', function ($row) {
                    return $row->employee->nik;
                })
                ->addColumn('name', function ($row) {
                    return $row->employee->name;
                })
                ->editColumn('role', function ($row) {
                    if ($row->role == 'Admin') {
                        $role = '<span class="badge bg-primary">' . $row->role . '</span>';
                    }
                    if ($row->role == 'Approver1') {
                        $role = '<span class="badge bg-info">' . $row->role . '</span>';
                    }
                    if ($row->role == 'Approver2') {
                        $role = '<span class="badge bg-success">' . $row->role . '</span>';
                    }
                    if ($row->role == 'User') {
                        $role = '<span class="badge bg-secondary">' . $row->role . '</span>';
                    }
                    if ($row->role == 'Technician') {
                        $role = '<span class="badge bg-warning">' . $row->role . '</span>';
                    }

                    return $role;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btn-edit-user" data-id="' . $row->id . '" class="btn btn-primary btn-sm" title="Edit this user"> <i class="fa-solid fa-pen-square"></i> </a>';
                    $btn = $btn . ' ' . '<a href="javascript:void(0)" id="btn-edit-user" data-id="' . $row->id . '" class="btn btn-danger btn-sm" title="Edit this user"> <i class="fa-solid fa-eraser"></i> </a>';
                    return $btn;
                })
                ->rawColumns(['action', 'role'])
                ->make(true);
        }

        return view('admin.user.index', [
            'title' => 'Users - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name,
        ]);
    }
}
