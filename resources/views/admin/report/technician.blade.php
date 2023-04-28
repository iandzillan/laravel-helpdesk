@extends('layouts.app')

@section('content')
    <div class="row d-flex justify-content-start">
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column text-center align-items-center justify-content-between ">
                            <div class="fs-italic">
                                <h5> {{ $technician->employee->name }}</h5>
                            </div>
                            <div class="text-black-50 text-warning">
                                <div class="card-profile-progress mb-3">
                                    <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-100 avatar-rounded">
                                    <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-purple-img img-fluid avatar avatar-100 avatar-rounded">
                                    <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-blue-img img-fluid avatar avatar-100 avatar-rounded">
                                    <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-green-img img-fluid avatar avatar-100 avatar-rounded">
                                    <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-yellow-img img-fluid avatar avatar-100 avatar-rounded">
                                    <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-pink-img img-fluid avatar avatar-100 avatar-rounded">
                                </div>
                            </div>
                            <div>
                                <table class="text-start">
                                    <tr>
                                        <th>Position</th>
                                        <td>:</td>
                                        <td>{{ $technician->employee->position }}</td>
                                    </tr>
                                    <tr>
                                        <th>Team</th>
                                        <td>:</td>
                                        <td>{{ $technician->employee->subDepartment->name }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="row d-flex justify-content-start">
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="bg-primary rounded p-3">
                                    <i class="fa-solid fa-ticket text-white"></i>
                                </div>
                                <div class="text-end">
                                    <h2 class="counter">{{ $technician->technician_tickets->count() }}</h2>
                                    Total Ticket
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="bg-info rounded p-3">
                                    <i class="fa-solid fa-hammer text-white"></i>
                                </div>
                                <div class="text-end">
                                    <h2 class="counter">{{ $technician->technician_tickets->where('status', 4)->count() }}</h2>
                                    On Work
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="bg-success rounded p-3">
                                    <i class="fa-regular fa-circle-check text-white"></i>
                                </div>
                                <div class="text-end">
                                    <h2 class="counter">{{ ($technician->technician_tickets->where('isUnderSla', 1)->count() / $technician->technician_tickets->count()) * 100 }} %</h2>
                                    SLA Success
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="bg-danger rounded p-3">
                                    <i class="fa-solid fa-circle-xmark text-white"></i>
                                </div>
                                <div class="text-end">
                                    <h2 class="counter">{{ ($technician->technician_tickets->where('isUnderSla', 0)->count() / $technician->technician_tickets->count()) * 100 }} %</h2>
                                    SLA Failed
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row d-flex justify-content center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Tickets</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" width="100%">
                            <thead>
                                <tr>
                                    <th>Ticket Number</th>
                                    <th>Status</th>
                                    <th>Progress</th>
                                    <th>SLA Duration</th>
                                    <th>Solve Duration</th>
                                    <th>Pending Duration</th>
                                    <th>SLA Success</th>
                                    <th>Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($technician->technician_tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->ticket_number }}</td>
                                        <td>{{ $ticket->status }}</td>
                                        <td>{{ $ticket->progress }} %</td>
                                        <td>{{ gmdate('H:i:s', $ticket->urgency->hours * 3600) }}</td>
                                        <td>{{ gmdate('H:i:s', $ticket->trackings->where('status', '!=', 'Ticket Continued')->sum('duration')) }}</td>
                                        <td>{{ gmdate('H:i:s', $ticket->trackings->where('status', 'Ticket Continued')->sum('duration')) }}</td>
                                        <td class="text-center">
                                            @if ($ticket->isUnderSla == 1)
                                                <i class="fa-regular fa-circle-check text-success"></i>
                                            @else
                                                <i class="fa-regular fa-circle-xmark text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($ticket->feedback_status == 1)
                                                <i class="text-info">No feedback yet</i>
                                            @else
                                                @switch($ticket->feedback->rating)
                                                    @case(1)
                                                        <i class="text-danger">Bad</i>
                                                        @break
                                                    @case(2)
                                                        <i class="text-secondary">Neutral / Okay</i>
                                                        @break
                                                    @case(3)
                                                        <i class="text-success">Excellent</i>
                                                        @break
                                                    @default
                                                        <i class="text-info">Not Rated</i>
                                                @endswitch
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.data-table').DataTable({
                initComplete: function (settings, json) {  
                    $(".data-table").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
            });
        });
    </script>
@endsection