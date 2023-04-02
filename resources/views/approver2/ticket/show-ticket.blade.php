@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title text-bold">{{ $ticket->ticket_number }}</h4>
                    </div>

                    @switch($ticket->status)
                        @case('Open')
                            <div>
                                <a href="javascript:void(0)" id="approve" data-id="{{ $ticket->ticket_number }}" class="btn btn-primary">Approve</a>
                                <a href="#" class="btn btn-danger">Reject</a>
                            </div>
                            @break
                        @case('Waiting to be assigned')
                            <div>
                                <a href="#" class="btn btn-primary">Choose Technician</a>
                            </div>
                            @break
                        @default
                            
                    @endswitch
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
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{ $ticket->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $ticket->progress }}">{{ $ticket->progress }}%</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">Created At</h5>
                                    <p class="card-text">{{ $ticket->created_at }}</p>
                                    <hr>

                                    <h5 class="card-title">User</h5>
                                    <p class="card-text">{{ $ticket->user->employee->name }} ({{$ticket->user->employee->nik}})</p>
                                    <hr>

                                    <h5 class="card-title">Subject</h5>
                                    <p class="card-text">{{ $ticket->subject }}</p>
                                    <hr>

                                    <h5 class="card-title">Category</h5>
                                    <p class="card-text">{{ $ticket->subCategory->category->name }}</p>
                                    <hr>
        
                                    <h5 class="card-title">Sub Category</h5>
                                    <p class="card-text">{{ $ticket->subCategory->name }}</p>
                                    <hr>

                                </div>
                                <div class="col-md-6">
                                    <h5 class="card-title">Last Update</h5>
                                    <p class="card-text">{{ $ticket->updated_at }}</p>
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

                                    <h5 class="card-title">Progress at</h5>
                                    <p class="card-text">{{ $ticket->progress_at }}</p>
                                    <hr>

                                    <h5 class="card-title">Urgency</h5>
                                    @if ($ticket->urgency == null)
                                        <p class="card-text">--</p>
                                    @else
                                        <p class="card-text">{{ $ticket->urgency->name }}</p>
                                    @endif
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
                let id    = $(this).data('id');
                let token = $('meta[name="csrf-token"]').attr('content');
                let url   = "{{ route('subdept.entry.tickets.approve', ':id') }}";
                url       = url.replace(':id', id);

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

                                // reload page
                                setTimeout(() => {
                                    location.reload();
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