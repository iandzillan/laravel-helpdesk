@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title text-bold">{{ $ticket->ticket_number }}</h4>
                    </div>
                    <div>
                        @if ($ticket->status == 'Approved by Team Leader' && request()->segment(2) != 'all-tickets')    
                            <a href="{{ route('dept.entry.tickets.approve', $ticket->ticket_number) }}" id="approve" data-id="{{ $ticket->ticket_number }}" data-redirect="{{ route('dept.entry.tickets') }}" class="btn btn-primary">Approve</a>
                            <a href="#" class="btn btn-danger">Reject</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <img src="{{ asset('storage/uploads/tickets/'.$ticket->image) }}" class="img-fluid rounded-start mb-3" alt="...">
                            <h5 class="card-title">Description</h5>
                            <p class="card-text">{{ $ticket->description }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title">Progress</h5>
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{ $ticket->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $ticket->progress }}%">{{ $ticket->progress }}%</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="card-title">Created At</h5>
                                            <p class="card-text">{{ $ticket->created_at }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="card-title">Last Update</h5>
                                            <p class="card-text">{{ $ticket->updated_at }}</p>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="card-title">User</h5>
                                            <p class="card-text">{{ $ticket->user->employee->name }} ({{$ticket->user->employee->nik}})</p>
                                            @if ($ticket->user->employee->sub_department_id == null)
                                                <p class="card-text">{{ $ticket->user->employee->department->name }}'s {{$ticket->user->employee->position}}</p>
                                            @else
                                                <p class="card-text">{{ $ticket->user->employee->subDepartment->name }}'s {{$ticket->user->employee->position}}</p>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="card-title">Subject</h5>
                                            <p class="card-text">{{ $ticket->subject }}</p>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="card-title">Category</h5>
                                            <p class="card-text">{{ $ticket->subCategory->category->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="card-title">Sub Category</h5>
                                            <p class="card-text">{{ $ticket->subCategory->name }}</p>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="card-title">Status</h5>
                                            @switch($ticket->status)
                                                @case('Open')
                                                    <p class="card-text">
                                                        <span class="badge bg-light text-dark">{{ $ticket->status }}</span>
                                                    </p>
                                                    @break
                                                @case('Approved by Team Leader')
                                                    <p class="card-text">
                                                        <span class="badge bg-secondary">{{ $ticket->status }}</span>
                                                    </p>
                                                    @break
                                                @case('Approved by Manager')
                                                    <p class="card-text">
                                                        <span class="badge bg-success">{{ $ticket->status }}</span>
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
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="card-title">Technician</h5>
                                            @if ($ticket->technician == null)
                                                <p class="card-text">--</p>
                                            @else
                                                <p class="card-text">{{ $ticket->technician->employee->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="card-title">Progress at</h5>
                                            <p class="card-text">{{ $ticket->progress_at }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="card-title">Urgency</h5>
                                            @if ($ticket->urgency == null)
                                                <p class="card-text">--</p>
                                            @else
                                                <p class="card-text">{{ $ticket->urgency->name }} ({{ $ticket->urgency->hours }} hours)</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="header-title">
                            <h4 class="card-title text-bold">Tracking</h4>
                            <div class="table-responsive-md">
                                <table class="table table-borderless">
                                    @forelse ($trackings as $tracking)
                                        <tr>
                                            <th>{{ $loop->iteration }}</th>
                                            <th>{{ $tracking->created_at }}</th>
                                            <th>{{ $tracking->status }}</th>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td colspan="2">
                                                Note : 
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

    <script>
        $(document).ready(function(){
            // approve button action
            $('body').on('click', '#approve', function(e){
                e.preventDefault();

                // define variable
                let id       = $(this).data('id');
                let redirect = $(this).data('redirect');
                let token    = $('meta[name="csrf-token"]').attr('content');
                let url      = $(this).attr('href');

                // show confirmation
                swal.fire({
                    title: 'Approve?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // show showLoading
                        swal.fire({
                            title: 'Please wait',
                            text: 'Sending request...',
                            showConfirmButton: false, 
                            allowOutsideClick: false,
                            allowEnterKey: false, 
                            allowEscapeKey: false,
                            didOpen: () => {
                                swal.showLoading();
                            }
                        });

                        // ajax approve
                        $.ajax({
                            url: url,
                            type: 'patch',
                            cache: false,
                            data: {
                                '_token': token
                            }, 
                            success:function(response){
                                // show success message
                                swal.fire({
                                    icon: 'success',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                                // rederict to new entry ticket page
                                setTimeout(() => {
                                    window.location.href = redirect;
                                }, 2000);
                            }, 
                            error:function(error){
                                console.log(error.responseJSON.message);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection