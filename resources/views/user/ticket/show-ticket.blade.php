@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title text-bold">{{ $ticket->ticket_number }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <img src="{{ asset('storage/uploads/tickets/'.$ticket->image) }}" class="img-fluid rounded-start mb-3" alt="...">
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title">Progress</h5>
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{ $ticket->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $ticket->progress }}">{{ $ticket->progress }}%</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">Created At</h5>
                                    <p class="card-text">{{ $ticket->created_at }}</p>
                                    <hr>

                                    <h5 class="card-title">Subject</h5>
                                    <p class="card-text">{{ $ticket->subject }}</p>
                                    <hr>

                                    <h5 class="card-title">Urgency</h5>
                                    @if ($ticket->urgency == null)
                                        <p class="card-text">--</p>
                                    @else
                                        <p class="card-text">{{ $ticket->urgency->name }}</p>
                                    @endif
                                    <hr>
                                    <h5 class="card-title">Status</h5>
                                    @switch($ticket->status)
                                        @case('Open')
                                            <p class="card-text">
                                                <span class="badge bg-light text-dark">{{ $ticket->status }}</span>
                                            </p>
                                            @break
                                        @case('Approved by supervisor')
                                            <p class="card-text">
                                                <span class="badge bg-secondary">{{ $ticket->status }}</span>
                                            </p>
                                            @break
                                        @case('Approved by Manager')
                                            <p class="card-text">
                                                <span class="badge bg-success">{{ $ticket->status }}</span>
                                            </p>
                                            @break
                                        @case('Waiting to be assigned')
                                            <p class="card-text">
                                                <span class="badge bg-dark">{{ $ticket->status }}</span>
                                            </p>
                                            @break
                                        @case('On work')
                                            <p class="card-text">
                                                <span class="badge bg-info">{{ $ticket->status }}</span>
                                            </p>
                                            @break
                                        @case('Pending')
                                            <p class="card-text">
                                                <span class="badge bg-warning">{{ $ticket->status }}</span>
                                            </p>
                                            @break
                                        @case('Closed')
                                            <p class="card-text">
                                                <span class="badge bg-primary">{{ $ticket->status }}</span>
                                            </p>
                                            @break
                                        @case('Rejected')
                                            <p class="card-text">
                                                <span class="badge bg-danger">{{ $ticket->status }}</span>
                                            </p>
                                            @break
                                        @default
                                            <p class="card-text">
                                                <span class="badge bg-danger">Undefined</span>
                                            </p>
                                    @endswitch
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="card-title">Last Update</h5>
                                    <p class="card-text">{{ $ticket->updated_at }}</p>
                                    <hr>

                                    <h5 class="card-title">Category</h5>
                                    <p class="card-text">{{ $ticket->subCategory->category->name }}</p>
                                    <hr>
        
                                    <h5 class="card-title">Sub Category</h5>
                                    <p class="card-text">{{ $ticket->subCategory->name }}</p>
                                    <hr>

                                    <h5 class="card-title">Technician</h5>
                                    @if ($ticket->technician == null)
                                        <p class="card-text">--</p>
                                    @else
                                        <p class="card-text">{{ $ticket->user->userable->name }}</p>
                                    @endif
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h5 class="card-title">Description</h5>
                    <p class="card-text">{{ $ticket->description }}</p>
                    <hr>
                    <div class="row mt-3">
                        <div class="header-title">
                            <h4 class="card-title text-bold">Tracking</h4>
                            <div class="table-responsive-md">
                                <table class="table table-borderless">
                                    @forelse ($trackings as $tracking)
                                        <tr>
                                            <th>{{ $loop->iteration }}</th>
                                            <th>{{ $tracking->created_at }}</th>
                                            <th>{{ $tracking->status }}</th>
                                            <th>Note:</th>
                                        </tr>
                                        <tr>
                                            <td colspan="3"></td>
                                            <td>
                                                @if ($tracking->note == null)
                                                    --
                                                @else
                                                    {{ $tracking->note }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        
                                    @endforelse
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection