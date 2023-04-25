@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row d-flex justify-content-between">
                        <div class="col-md-6 mb-3">
                            <div class="header-title">
                                <h4 class="card-title text-bold">{{ $ticket->ticket_number }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            @if ($ticket->status == 'Approved by Manager' && request()->segment(2) != "all-tickets")
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary">Action</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" class="btn btn-primary" id="btn-assign" data-url="{{ route('admin.entry.tickets.technicians', $ticket->sub_category_id) }}" data-id="{{ $ticket->ticket_number }}">
                                                <i class="fa-solid fa-user-gear"></i>
                                                Assign Technician
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" id="reject" data-id="{{ $ticket->ticket_number }}">
                                                <i class="fa-solid fa-file-circle-xmark"></i>
                                                Reject
                                            </a>
                                        </li>
                                    </ul>
                                </div>    
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mt-3">
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
                                        <div class="col-md-6 mb-3">
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
                                        <div class="col-md-6 mb-3">
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
                                        <div class="col-md-6 mb-3">
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
                                        <div class="col-md-6 mb-3">
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
                                        <div class="col-md-6 mb-3">
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
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <h5 class="card-title">Expected Solve at</h5>
                                            <p class="card-text">{{ $ticket->expected_finish_at }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="card-title">Solved at</h5>
                                            <p class="card-text">{{ $ticket->finish_at }}</p>
                                            <p class="card-text">Duration: {{ gmdate('H:i:s', $ticket->trackings->where('status', '!=', 'Ticket Continued')->sum('duration')) }}</p>
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

    {{-- Modal --}}
    <div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Technician</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-assign">
                    <div class="modal-body">
                        <input type="hidden" id="ticket">
                        <div class="form-group">
                            <label for="technician" class="form-label">Technician</label>
                            <select id="technician" name="technician_id" class="selectpicker form-control select2"></select>
                            <div class="invalid-feedback d-none" role="alert" id="alert-technician"></div>
                        </div>
                        <div class="form-group">
                            <label for="urgency" class="form-label">Urgency</label>
                            <select id="urgency" name="urgency_id" class="selectpicker form-control select2"></select>
                            <div class="invalid-feedback d-none" role="alert" id="alert-urgency"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="store">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modal-update" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Please fill the reason</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-reject">
                    <div class="modal-body">
                        <input type="hidden" id="ticket">
                        <div class="form-group">
                            <label class="form-label" for="note">Reason*</label>
                            <textarea class="form-control" id="note" rows="5"></textarea>
                            <div class="invalid-feedback d-none" role="alert" id="alert-note"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="update">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // btn_assign button action
            $('body').on('click', '#btn-assign', function(){
                // get variable
                let ticket = $(this).data('id');
                let url    = $(this).data('url');
                let url2   = "{{ route('admin.entry.tickets.urgencies', ":ticket") }}";
                url2       = url2.replace(':ticket', ticket);

                // get technician
                $.ajax({
                    url: url,
                    type: 'get',
                    cache: false,
                    success:function(response){
                        $('#technician').empty();
                        $('#technician').append('<option disabled selected> -- Choose -- </option>');
                        $.each(response, function(code, technician){
                            $('#technician').append('<option value="'+technician.id+'">'+technician.name+'</option>');
                        });
                    }
                });

                // get urgency
                $.ajax({
                    url: url2,
                    type: 'get',
                    cache: false,
                    success:function(response){
                        $('#urgency').empty();
                        $('#urgency').append('<option disabled selected> -- Choose -- </option>');
                        $.each(response, function (code, urgency){
                            $('#urgency').append('<option value="'+urgency.id+'">'+urgency.name+' ('+urgency.hours+' hours)</option>');
                        });
                    }, 
                    error:function(error){
                        console.log(error.responseJSON.message);
                    }
                });

                // open modal
                $('#ticket').val(ticket);
                $('#modal-create').modal('show');
            });

            // store button action
            $('body').on('click', '#store', function(e){
                // define variable
                let technician_id = $("#technician").val();
                let urgency_id    = $("#urgency").val();
                let ticket        = $("#ticket").val();
                let token         = $('meta[name="csrf-token"]').attr('content');
                let url           = "{{ route('admin.entry.tickets.assign', ":ticket") }}";
                url               = url.replace(':ticket', ticket);

                // show loading
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

                // ajax assign
                $.ajax({
                    url: url,
                    type: 'patch',
                    cache: false,
                    data: {
                        'technician_id': technician_id,
                        'urgency_id': urgency_id,
                        '_token': token
                    }, 
                    success:function(response){
                        // defince variable
                        let ticket    = response.data.ticket_number;
                        let url_email = "{{ route('notification', ":ticket") }}";
                        url_email     = url_email.replace(':ticket', ticket);

                        // ajax email
                        // $.ajax({
                        //     url: url_email,
                        //     type: 'get',
                        //     cache: false,
                        //     success: function(response1){
                        //         swal.fire({
                        //             icon: 'success',
                        //             title: 'Ticket has been assigned to technician',
                        //             text: 'Notification has been sended to the technician',
                        //             showConfirmButton: false,
                        //             timer: 2000
                        //         });
                        //     },
                        //     error: function(error1){
                        //         swal.fire({
                        //             icon: 'warning',
                        //             text: error1.responseJSON.message,
                        //             showConfirmButton: false
                        //         });
                        //     }
                        // });

                        // redirect to unassigned ticket page
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.entry.tickets') }}";
                        }, 2000);
                    }, 
                    error:function(error){
                        // show response
                        swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check again',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // if technician field has error
                        if (error.responseJSON.technician_id) {
                            // show alert and message
                            $('#technician').addClass('is-invalid');
                            $('#alert-technician').addClass('d-block');
                            $('#alert-technician').removeClass('d-none');
                            $('#alert-technician').html(error.responseJSON.technician_id);
                        }

                        // if urgency field has error
                        if (error.responseJSON.urgency_id) {
                            // show alert and message
                            $('#urgency').addClass('is-invalid');
                            $('#alert-urgency').addClass('d-block');
                            $('#alert-urgency').removeClass('d-none');
                            $('#alert-urgency').html(error.responseJSON.urgency_id);
                        }
                    }
                });
            });

            // reject button action
            $('body').on('click', '#reject', function(){
                // define variable
                let ticket = $(this).data('id');
                // fill id to ticket field
                $('#ticket').val(ticket);
                $('#modal-update').modal('show');
            });

            // update button action
            $('body').on('click', '#update', function(e){
                e.preventDefault();

                // define variable
                let ticket   = $('#ticket').val();
                let note     = $('#note').val();
                let token    = $('meta[name="csrf-token"]').attr('content');
                let url      = "{{ route('admin.entry.tickets.reject', ":ticket") }}";
                url          = url.replace(':ticket', ticket);

                // show confirmation
                swal.fire({
                    title: 'Are you sure?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // show loading
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

                        // ajax reject
                        $.ajax({
                            url: url,
                            type: 'patch',
                            cache: false,
                            data: {
                                'note': note,
                                '_token': token
                            }, 
                            success:function(response){
                                // show message
                                swal.fire({
                                    title: response.message,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                                // return redirect
                                setTimeout(() => {
                                    window.location.href = "{{ route('admin.entry.tickets') }}";
                                }, 2000);
                            }, 
                            error:function(error){
                                // show message 
                                swal.fire({
                                    title: 'Something wrong',
                                    icon: 'warning',
                                    text: 'Please check again',
                                    showConfirmButton: false,
                                    timer: 1000
                                });

                                // check if note field error
                                if (error.responseJSON.note) {
                                    // show alert & message
                                    $('#note').addClass('is-invalid');
                                    $('#alert-note').addClass('d-block');
                                    $('#alert-note').removeClass('d-none');
                                    $('#alert-note').html(error.responseJSON.note);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection