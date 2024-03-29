<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        // get role
        $role = Auth::user()->role;

        switch ($role) {
            case 'Admin':
                $view  = 'admin.dashboard';
                $title = 'Admin Dashboard - Helpdesk Ticketing System';
                $total_ticket      = Ticket::all();
                $myticket          = null;
                $approval_tl       = Ticket::where('status', 1)->get();
                $approval_manager  = Ticket::where('status', 2)->get();
                $unassigned_ticket = Ticket::where('status', 3)->get();
                $onwork_ticket     = Ticket::where('status', 4)->get();
                $pending_ticket    = Ticket::where('status', 5)->get();
                $closed_ticket     = Ticket::where('status', 6)->get();
                $rejected_ticket   = Ticket::where('status', 7)->get();
                break;

            case 'Approver1':
                $view  = 'approver1.dashboard';
                $title = 'Manager Dashboard - Helpdesk Ticketing System';
                $total_ticket      = Ticket::whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $myticket          = Ticket::where('user_id', Auth::user()->id)->latest()->get();
                $approval_tl       = Ticket::where('status', 1)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $approval_manager  = Ticket::where('status', 2)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $unassigned_ticket = Ticket::where('status', 3)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $onwork_ticket     = Ticket::where('status', 4)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $pending_ticket    = Ticket::where('status', 5)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $closed_ticket     = Ticket::where('status', 6)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                $rejected_ticket   = Ticket::where('status', 7)->whereHas('user.employee', function ($q) {
                    $q->where('department_id', Auth::user()->employee->department_id);
                })->get();
                break;

            case 'Approver2':
                $view  = 'approver2.dashboard';
                $title = 'Sub Dept Head Dashboard - Helpdesk Ticketing System';
                $total_ticket      = Ticket::whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $myticket          = Ticket::where('user_id', Auth::user()->id)->latest()->get();
                $approval_tl       = Ticket::where('status', 1)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $approval_manager  = Ticket::where('status', 2)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $unassigned_ticket = Ticket::where('status', 3)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $onwork_ticket     = Ticket::where('status', 4)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $pending_ticket    = Ticket::where('status', 5)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $closed_ticket     = Ticket::where('status', 6)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                $rejected_ticket   = Ticket::where('status', 7)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                })->get();
                break;

            case 'User':
                $view  = 'user.dashboard';
                $title = 'User Dashboard - Helpdesk Ticketing System';
                $total_ticket      = null;
                $myticket          = Ticket::where('user_id', Auth::user()->id)->latest()->get();
                $approval_tl       = Ticket::where('status', 1)->where('user_id', Auth::user()->id)->latest()->get();
                $approval_manager  = Ticket::where('status', 2)->where('user_id', Auth::user()->id)->latest()->get();
                $unassigned_ticket = Ticket::where('status', 3)->where('user_id', Auth::user()->id)->latest()->get();
                $onwork_ticket     = Ticket::where('status', 4)->where('user_id', Auth::user()->id)->latest()->get();
                $pending_ticket    = Ticket::where('status', 5)->where('user_id', Auth::user()->id)->latest()->get();
                $closed_ticket     = Ticket::where('status', 6)->where('user_id', Auth::user()->id)->latest()->get();
                $rejected_ticket   = Ticket::where('status', 7)->where('user_id', Auth::user()->id)->latest()->get();
                break;

            case 'Technician':
                $view  = 'technician.dashboard';
                $title = 'Technician Dashboard - Helpdesk Ticketing System';
                $total_ticket      = Ticket::where('technician_id', Auth::user()->id)->get();
                $myticket          = null;
                $approval_tl       = null;
                $approval_manager  = null;
                $unassigned_ticket = null;
                $onwork_ticket     = Ticket::join('urgencies', 'urgencies.id', '=', 'tickets.urgency_id')->where('technician_id', Auth::user()->id)->where('status', 4)->orderBy('urgencies.hours', 'asc')->get();
                $pending_ticket    = Ticket::join('urgencies', 'urgencies.id', '=', 'tickets.urgency_id')->where('technician_id', Auth::user()->id)->where('status', 5)->orderBy('urgencies.hours', 'asc')->get();
                $closed_ticket     = Ticket::where('technician_id', Auth::user()->id)->where('status', 6)->latest()->get();
                $rejected_ticket   = null;
                break;

            default:
                abort(404);
                break;
        }

        return view($view, [
            'title'  => $title,
            'name'   => Auth::user()->employee->name,
            'ticket' => [
                'total'            => $total_ticket,
                'myticket'         => $myticket,
                'new'              => $approval_tl,
                'approval_manager' => $approval_manager,
                'unassigned'       => $unassigned_ticket,
                'onwork'           => $onwork_ticket,
                'pending'          => $pending_ticket,
                'closed'           => $closed_ticket,
                'rejected'         => $rejected_ticket
            ]
        ]);
    }

    public function getCategoryYear()
    {
        switch (Auth::user()->role) {
            case 'Admin':
                $months = Ticket::select(DB::raw('MONTHNAME(created_at) AS month'))
                    ->where(DB::raw('YEAR(created_at)'), date('Y'))
                    ->groupBy(DB::raw('month'))
                    ->get();

                $month  = $months->pluck('month');

                foreach ($months as $item) {
                    $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                        $q->where(DB::raw('MONTHNAME(tickets.created_at)'), $item->month)->where(DB::raw('YEAR(tickets.created_at)'), date('Y'));
                    }])->get();

                    foreach ($categories as $category) {
                        $datas[] = [
                            'name'  => $category->name,
                            'count' => $category->tickets_count,
                        ];
                    }

                    $data = collect($datas);
                }

                $map = $data->mapToGroups(function ($item) {
                    return [$item['name'] => $item['count']];
                });
                break;

            case 'Approver1':
                $months = Ticket::select(DB::raw('MONTHNAME(created_at) AS month'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('department_id', Auth::user()->employee->department_id);
                    })
                    ->where(DB::raw('YEAR(created_at)'), date('Y'))
                    ->groupBy(DB::raw('month'))
                    ->get();

                $month  = $months->pluck('month');

                foreach ($months as $item) {
                    $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                        $q->where(DB::raw('MONTHNAME(tickets.created_at)'), $item->month)
                            ->whereHas('user.employee', function ($q) {
                                $q->where('department_id', Auth::user()->employee->department_id);
                            })
                            ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'));
                    }])->get();

                    foreach ($categories as $category) {
                        $datas[] = [
                            'name'  => $category->name,
                            'count' => $category->tickets_count,
                        ];
                    }

                    $data = collect($datas);
                }

                $map = $data->mapToGroups(function ($item) {
                    return [$item['name'] => $item['count']];
                });
                break;

            case 'Approver2':
                $months = Ticket::select(DB::raw('MONTHNAME(created_at) AS month'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                    })
                    ->where(DB::raw('YEAR(created_at)'), date('Y'))
                    ->groupBy(DB::raw('month'))
                    ->get();

                $month  = $months->pluck('month');

                foreach ($months as $item) {
                    $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                        $q->where(DB::raw('MONTHNAME(tickets.created_at)'), $item->month)
                            ->whereHas('user.employee', function ($q) {
                                $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                            })
                            ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'));
                    }])->get();

                    foreach ($categories as $category) {
                        $datas[] = [
                            'name'  => $category->name,
                            'count' => $category->tickets_count,
                        ];
                    }

                    $data = collect($datas);
                }

                $map = $data->mapToGroups(function ($item) {
                    return [$item['name'] => $item['count']];
                });
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            'name'  => $map->keys(),
            'data'  => $map->values(),
            'month' => $month
        ]);
    }

    public function getCategoryMonth()
    {
        switch (Auth::user()->role) {
            case 'Admin':
                $months = Ticket::select(DB::raw('MONTHNAME(created_at) AS month'))
                    ->where(DB::raw('MONTHNAME(created_at)'), date('F'))
                    ->groupBy(DB::raw('month'))
                    ->get();

                $month  = $months->pluck('month');

                foreach ($months as $item) {
                    $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                        $q->where(DB::raw('MONTHNAME(tickets.created_at)'), $item->month);
                    }])->get();

                    foreach ($categories as $category) {
                        $datas[] = [
                            'name'  => $category->name,
                            'count' => $category->tickets_count,
                        ];
                    }

                    $data = collect($datas);
                }

                $map = $data->mapToGroups(function ($item) {
                    return [$item['name'] => $item['count']];
                });
                break;

            case 'Approver1':
                $months = Ticket::select(DB::raw('MONTHNAME(created_at) AS month'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('department_id', Auth::user()->employee->department_id);
                    })
                    ->where(DB::raw('MONTHNAME(created_at)'), date('F'))
                    ->groupBy(DB::raw('month'))
                    ->get();

                $month  = $months->pluck('month');

                foreach ($months as $item) {
                    $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                        $q->where(DB::raw('MONTHNAME(tickets.created_at)'), $item->month)->whereHas('user.employee', function ($q) {
                            $q->where('department_id', Auth::user()->employee->department_id);
                        });
                    }])->get();

                    foreach ($categories as $category) {
                        $datas[] = [
                            'name'  => $category->name,
                            'count' => $category->tickets_count,
                        ];
                    }

                    $data = collect($datas);
                }

                $map = $data->mapToGroups(function ($item) {
                    return [$item['name'] => $item['count']];
                });
                break;

            case 'Approver2':
                $months = Ticket::select(DB::raw('MONTHNAME(created_at) AS month'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                    })
                    ->where(DB::raw('MONTHNAME(created_at)'), date('F'))
                    ->groupBy(DB::raw('month'))
                    ->get();

                $month  = $months->pluck('month');

                foreach ($months as $item) {
                    $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                        $q->where(DB::raw('MONTHNAME(tickets.created_at)'), $item->month)->whereHas('user.employee', function ($q) {
                            $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                        });
                    }])->get();

                    foreach ($categories as $category) {
                        $datas[] = [
                            'name'  => $category->name,
                            'count' => $category->tickets_count,
                        ];
                    }

                    $data = collect($datas);
                }

                $map = $data->mapToGroups(function ($item) {
                    return [$item['name'] => $item['count']];
                });
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            'name'  => $map->keys(),
            'data'  => $map->values(),
            'month' => $month
        ]);
    }

    public function getCategoryWeek()
    {
        switch (Auth::user()->role) {
            case 'Admin':
                $dates = Ticket::select(DB::raw('DATE(created_at) AS date'))
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy('date')
                    ->get();

                $date = $dates->pluck('date');

                foreach ($dates as $item) {
                    $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                        $q->where(DB::raw('DATE(tickets.created_at)'), $item->date)
                            ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'));
                    }])->get();

                    foreach ($categories as $category) {
                        $datas[] = [
                            'name'  => $category->name,
                            'count' => $category->tickets_count,
                        ];
                    }

                    $data = collect($datas);
                }

                $map = $data->mapToGroups(function ($item) {
                    return [$item['name'] => $item['count']];
                });
                break;

            case 'Approver1':
                $dates = Ticket::select(DB::raw('DATE(created_at) AS date'))
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereHas('user.employee', function ($q) {
                        $q->where('department_id', Auth::user()->employee->department_id);
                    })
                    ->groupBy('date')
                    ->get();

                $date = $dates->pluck('date');

                foreach ($dates as $item) {
                    $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                        $q->where(DB::raw('DATE(tickets.created_at)'), $item->date)
                            ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'))
                            ->whereHas('user.employee', function ($q) {
                                $q->where('department_id', Auth::user()->employee->department_id);
                            });
                    }])->get();

                    foreach ($categories as $category) {
                        $datas[] = [
                            'name'  => $category->name,
                            'count' => $category->tickets_count,
                        ];
                    }

                    $data = collect($datas);
                }
                $map = $data->mapToGroups(function ($item) {
                    return [$item['name'] => $item['count']];
                });
                break;

            case 'Approver2':
                $dates = Ticket::select(DB::raw('DATE(created_at) AS date'))
                    ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->whereHas('user.employee', function ($q) {
                        $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                    })
                    ->groupBy('date')
                    ->get();

                $date = $dates->pluck('date');

                foreach ($dates as $item) {
                    $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                        $q->where(DB::raw('DATE(tickets.created_at)'), $item->date)
                            ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'))
                            ->whereHas('user.employee', function ($q) {
                                $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                            });
                    }])->get();

                    foreach ($categories as $category) {
                        $datas[] = [
                            'name'  => $category->name,
                            'count' => $category->tickets_count,
                        ];
                    }

                    $data = collect($datas);
                }

                $map = $data->mapToGroups(function ($item) {
                    return [$item['name'] => $item['count']];
                });
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            'name' => $map->keys(),
            'data' => $map->values(),
            'week' => $date
        ]);
    }

    public function getSubCategoryYear()
    {
        switch (Auth::user()->role) {
            case 'Admin':
                $tickets = Ticket::join('sub_categories', 'Sub_categories.id', '=', 'tickets.sub_category_id')
                    ->select(Db::raw('count(tickets.id) as count, sub_categories.name as name'))
                    ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'))
                    ->groupBy(DB::raw('sub_categories.name'))
                    ->get();

                $name = [];
                $data = [];
                foreach ($tickets as $ticket) {
                    $name[] = $ticket->name;
                    $data[] = $ticket->count;
                }
                break;

            case 'Approver1':
                $tickets = Ticket::join('sub_categories', 'Sub_categories.id', '=', 'tickets.sub_category_id')
                    ->select(Db::raw('count(tickets.id) as count, sub_categories.name as name'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('department_id', Auth::user()->employee->department_id);
                    })
                    ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'))
                    ->groupBy(DB::raw('sub_categories.name'))
                    ->get();

                $name = [];
                $data = [];
                foreach ($tickets as $ticket) {
                    $name[] = $ticket->name;
                    $data[] = $ticket->count;
                }
                break;

            case 'Approver2':
                $tickets = Ticket::join('sub_categories', 'Sub_categories.id', '=', 'tickets.sub_category_id')
                    ->select(Db::raw('count(tickets.id) as count, sub_categories.name as name'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                    })
                    ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'))
                    ->groupBy(DB::raw('sub_categories.name'))
                    ->get();

                $name = [];
                $data = [];
                foreach ($tickets as $ticket) {
                    $name[] = $ticket->name;
                    $data[] = $ticket->count;
                }
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            'name' => $name,
            'data' => $data,
        ]);
    }

    public function getSubCategoryMonth()
    {
        switch (Auth::user()->role) {
            case 'Admin':
                $tickets = Ticket::join('sub_categories', 'Sub_categories.id', '=', 'tickets.sub_category_id')
                    ->select(Db::raw('count(tickets.id) as count, sub_categories.name as name'))
                    ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'))
                    ->where(DB::raw('MONTHNAME(tickets.created_at)'), date('F'))
                    ->groupBy(DB::raw('sub_categories.name'))
                    ->get();

                $name = [];
                $data = [];
                foreach ($tickets as $ticket) {
                    $name[] = $ticket->name;
                    $data[] = $ticket->count;
                }
                break;

            case 'Approver1':
                $tickets = Ticket::join('sub_categories', 'Sub_categories.id', '=', 'tickets.sub_category_id')
                    ->select(Db::raw('count(tickets.id) as count, sub_categories.name as name'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('department_id', Auth::user()->employee->department_id);
                    })
                    ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'))
                    ->where(DB::raw('MONTHNAME(tickets.created_at)'), date('F'))
                    ->groupBy(DB::raw('sub_categories.name'))
                    ->get();

                $name = [];
                $data = [];
                foreach ($tickets as $ticket) {
                    $name[] = $ticket->name;
                    $data[] = $ticket->count;
                }
                break;

            case 'Approver2':
                $tickets = Ticket::join('sub_categories', 'Sub_categories.id', '=', 'tickets.sub_category_id')
                    ->select(Db::raw('count(tickets.id) as count, sub_categories.name as name'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                    })
                    ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'))
                    ->where(DB::raw('MONTHNAME(tickets.created_at)'), date('F'))
                    ->groupBy(DB::raw('sub_categories.name'))
                    ->get();

                $name = [];
                $data = [];
                foreach ($tickets as $ticket) {
                    $name[] = $ticket->name;
                    $data[] = $ticket->count;
                }
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            'name' => $name,
            'data' => $data,
        ]);
    }

    public function getSubCategoryWeek()
    {
        switch (Auth::user()->role) {
            case 'Admin':
                $tickets = Ticket::join('sub_categories', 'Sub_categories.id', '=', 'tickets.sub_category_id')
                    ->select(Db::raw('count(tickets.id) as count, sub_categories.name as name'))
                    ->whereBetween('tickets.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy(DB::raw('sub_categories.name'))
                    ->get();

                $name = [];
                $data = [];
                foreach ($tickets as $ticket) {
                    $name[] = $ticket->name;
                    $data[] = $ticket->count;
                }
                break;

            case 'Approver1':
                $tickets = Ticket::join('sub_categories', 'Sub_categories.id', '=', 'tickets.sub_category_id')
                    ->select(Db::raw('count(tickets.id) as count, sub_categories.name as name'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('department_id', Auth::user()->employee->department_id);
                    })
                    ->whereBetween('tickets.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy(DB::raw('sub_categories.name'))
                    ->get();

                $name = [];
                $data = [];
                foreach ($tickets as $ticket) {
                    $name[] = $ticket->name;
                    $data[] = $ticket->count;
                }
                break;

            case 'Approver2':
                $tickets = Ticket::join('sub_categories', 'Sub_categories.id', '=', 'tickets.sub_category_id')
                    ->select(Db::raw('count(tickets.id) as count, sub_categories.name as name'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                    })
                    ->whereBetween('tickets.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                    ->groupBy(DB::raw('sub_categories.name'))
                    ->get();

                $name = [];
                $data = [];
                foreach ($tickets as $ticket) {
                    $name[] = $ticket->name;
                    $data[] = $ticket->count;
                }
                break;

            default:
                # code...
                break;
        }

        return response()->json([
            'name' => $name,
            'data' => $data,
        ]);
    }

    public function solvePercentageYear()
    {
        // count total ticket
        $total = Ticket::select(DB::raw('count(id) as count'))
            ->where(DB::raw('YEAR(created_at)'), date('Y'))
            ->where('status', 6)
            ->groupBy('isUnderSla')
            ->get();

        $sum = [];
        foreach ($total as $row) {
            $sum[] = $row->count;
        }

        return response()->json([
            'data' => $sum
        ]);
    }

    public function solvePercentageMonth()
    {
        // count total ticket
        $total = Ticket::select(DB::raw('count(id) as count'))
            ->where(DB::raw('YEAR(created_at)'), date('Y'))
            ->where(DB::raw('MONTHNAME(created_at)'), date('F'))
            ->where('status', 6)
            ->groupBy('isUnderSla')
            ->get();

        $sum = [];
        foreach ($total as $row) {
            $sum[] = $row->count;
        }

        return response()->json([
            'data' => $sum
        ]);
    }

    public function solvePercentageWeek()
    {
        // count total ticket
        $total = Ticket::select(DB::raw('count(id) as count'))
            ->whereBetween(DB::raw('created_at'), [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->where('status', 6)
            ->groupBy('isUnderSla')
            ->get();

        $sum = [];
        foreach ($total as $row) {
            $sum[] = $row->count;
        }

        return response()->json([
            'data' => $sum
        ]);
    }

    public function testing()
    {
        $months = Ticket::select(DB::raw('MONTHNAME(created_at) AS month'))->where(DB::raw('MONTHNAME(created_at)'), date('F'))->groupBy(DB::raw('month'))->get();
        $month  = $months->pluck('month');
        foreach ($months as $item) {
            $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                $q->where(DB::raw('MONTHNAME(tickets.created_at)'), $item->month)->whereHas('user.employee', function ($q) {
                    $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                });
            }])->get();
            foreach ($categories as $category) {
                $datas[] = [
                    'name'  => $category->name,
                    'count' => $category->tickets_count,
                ];
            }
            $data = collect($datas);
        }
        $result = $data->mapToGroups(function ($item) {
            return [$item['name'] => $item['count']];
        });

        return response()->json([
            'month' => $month,
            'data'  => $result->keys(),
            'count' => $result->values(),
        ]);
    }

    public function testing2()
    {
        $dates = Ticket::select(DB::raw('DATE(created_at) AS date'))
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->whereHas('user.employee', function ($q) {
                $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
            })
            ->groupBy('date')
            ->get();
        $date = $dates->pluck('date');

        foreach ($dates as $item) {
            $categories = Category::withCount(['tickets' => function ($q) use ($item) {
                $q->where(DB::raw('DATE(tickets.created_at)'), $item->date)
                    ->where(DB::raw('YEAR(tickets.created_at)'), date('Y'))
                    ->whereHas('user.employee', function ($q) {
                        $q->where('sub_department_id', Auth::user()->employee->sub_department_id);
                    });
            }])->get();

            foreach ($categories as $category) {
                $datas[] = [
                    'name'  => $category->name,
                    'count' => $category->tickets_count,
                ];
            }
            $data = collect($datas);
        }
        $result = $data->mapToGroups(function ($item) {
            return [$item['name'] => $item['count']];
        });

        return response()->json([
            'date'  => $date,
            'data'  => $result->keys(),
            'count' => $result->values(),
        ]);
    }
}
