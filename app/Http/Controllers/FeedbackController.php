<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'Admin':
                $view    = 'admin.ticket.feedback';
                $tickets = Ticket::where('feedback_status', 2)->get();

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
                        ->addColumn('rating', function ($row) {
                            $rating = $row->feedback->rating;
                            switch ($rating) {
                                case 1:
                                    return '<i class="text-danger">Bad</i>';
                                    break;

                                case 2:
                                    return '<i class="text-secondary">Neutral / Okay</i>';
                                    break;

                                case 3:
                                    return '<i class="text-success">Excellent</i>';
                                    break;

                                default:
                                    return 'Undefined';
                                    break;
                            }
                        })
                        ->addColumn('note', function ($row) {
                            if ($row->feedback->note == null) {
                                return '<i>No Comment<i>';
                            } else {
                                return $row->feedback->note;
                            }
                        })
                        ->rawColumns(['note', 'rating'])
                        ->make(true);
                }
                break;

            case 'User':
                $view    = 'user.ticket.feedback';
                $tickets = Ticket::where('user_id', Auth::user()->id)->where('feedback_status', 1)->orderBy('finish_at')->get();

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
                break;

            case 'Technician':
                # code...
                break;

            default:
                # code...
                break;
        }

        return view($view, [
            'title' => 'Feedback - Helpdesk Ticketing System',
            'name'  => Auth::user()->employee->name
        ]);
    }

    public function create(Request $request, $ticket)
    {
        $ticket = Ticket::where('id', $ticket)->where('user_id', Auth::user()->id)->first();

        return response()->json([
            'success' => true,
            'message' => 'Detail ticket',
            'data'    => $ticket
        ]);
    }

    public function store(Request $request, $ticket)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
        ], [
            'rating.required' => 'Please choose you honest feedback from one of them'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $ticket = Ticket::where('id', $ticket)->where('user_id', Auth::user()->id)->first();
        $ticket->feedback_status = 2;
        $ticket->save();

        $feedback                = new Feedback;
        $feedback->rating        = $request->rating;
        $feedback->note          = $request->note;
        $feedback->user_id       = $ticket->user_id;
        $feedback->technician_id = $ticket->technician_id;
        $ticket->feedback()->save($feedback);

        return response()->json([
            'success' => true,
            'data'    => $ticket
        ]);
    }

    public function performance()
    {
        $technicians = User::with('feedbacks')->where('role', 'technician')->get();

        foreach ($technicians as $technician) {
            echo $technician->employee->name . ' | ';
            echo 'count ticket: ' . $technician->tickets->count() . ' | ';
            echo 'success rate: ' . ($technician->tickets->where('isUnderSla', 1)->count() / $technician->tickets->count()) * 100 . '% | ';
            echo '<br>';
            // echo 'Excellent feedback: ' . $technician->feedbacks->where('rating', 3)->count() . ' | ';
            // echo 'Neutral feedback: ' . $technician->feedbacks->where('rating', 2)->count() . ' | ';
            // echo 'Bad feedback: ' . $technician->feedbacks->where('rating', 1)->count() . ' | ';
            $feedbacks = $technician->feedbacks->groupBy('rating')->all();
            dump($feedbacks);
            $rating = [];
            foreach ($feedbacks as $feedback) {
                $rating[] = $feedback->count();
            }
            dump($rating);
            echo '<br>';
            foreach ($technician->tickets as $ticket) {
                echo $ticket->ticket_number . '<br>';
            }
            echo '<hr>';
        }
    }

    public function testing()
    {
        $technicians = User::where('role', 'technician')->get();

        $rating = [];
        foreach ($technicians as $technician) {
            $feedbacks = $technician->feedbacks->groupBy('technician_id');
            foreach ($feedbacks as $feedback) {
                $row = $feedback->pluck('rating');
                $rating[] = $row;
            }
        }

        return response()->json([
            'data' => $rating
        ]);
    }
}
