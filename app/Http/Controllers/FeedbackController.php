<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'Admin':
                # code...
                break;

            case 'User':
                $view    = 'user.ticket.feedback';
                $tickets = Ticket::where('user_id', Auth::user()->id)->where('feedback_status', 1)->orderBy('finish_at')->get();
                break;

            case 'Technician':
                # code...
                break;

            default:
                # code...
                break;
        }

        // draw table
        if ($request->ajax()) {
            return DataTables::of($tickets)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    return $row->user->employee->name;
                })
                ->addColumn('technician', function ($row) {
                    return $row->technician->employee->name;
                })
                ->addColumn('sla', function ($row) {
                    return gmdate('H:i:s', $row->urgency->hours * 3600);
                })
                ->addColumn('duration', function ($row) {
                    return gmdate('H:i:s', $row->trackings->where('status', '!=', 'Ticket Continued')->sum('duration'));
                })
                ->addColumn('pending', function ($row) {
                    return gmdate('H:i:s', $row->trackings->where('status', 'Ticket Continued')->sum('duration'));
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" id="btn-feedback" data-id="' . $row->id . '" class="btn btn-primary btn-sm" title="Give Feedback">
                        <i class="fa-regular fa-comment"></i>
                    </a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view($view, [
            'title' => 'Feedback - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function store(Request $request)
    {
    }
}
