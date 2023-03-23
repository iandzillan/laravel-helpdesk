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

                    <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <img src="{{ asset('storage/uploads/tickets/'.$ticket->image) }}" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-6">
                            <div class="card-body">
                                <h5 class="card-title">Subject</h5>
                                <p class="card-text">{{ $ticket->subject }}</p>

                                <h5 class="card-title">Category</h5>
                                <p class="card-text">{{ $ticket->subCategory->category->name }}</p>

                                <h5 class="card-title">Sub Category</h5>
                                <p class="card-text">{{ $ticket->subCategory->name }}</p>

                                <h5 class="card-title">Description</h5>
                                <p class="card-text">{{ $ticket->description }}</p>

                                <h5 class="card-title">Progress</h5>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{ $ticket->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $ticket->progress }}">{{ $ticket->progress }}%</div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection