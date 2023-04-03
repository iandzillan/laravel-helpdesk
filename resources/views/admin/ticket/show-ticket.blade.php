@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title text-bold">{{ $ticket->ticket_number }}</h4>
                        <input type="hidden" name="ticket_number" id="ticket" value="{{ $ticket->ticket_number }}">
                    </div>
                    <div>
                        @if ($ticket->status == 'Approved by Manager' && request()->segment(2) != "all-tickets")    
                            <a href="javascript:void(0)" class="btn btn-primary" id="btn-assign" data-url="{{ route('admin.entry.tickets.technicians', $ticket->sub_category_id) }}">
                                <i class="fa-solid fa-user-gear"></i>
                                Assign Technician
                            </a>
                            <a href="#" class="btn btn-danger">
                                <i class="fa-solid fa-file-circle-xmark"></i>
                                Reject
                            </a>
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
                            <div class="progress mb-3">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{{ $ticket->progress }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $ticket->progress }}">{{ $ticket->progress }}%</div>
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
                                            <p class="card-text">{{ $ticket->user->employee->subDepartment->name }}'s {{$ticket->user->employee->position}}</p>
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

    <script>
        $(document).ready(function(){
            // btn_assign button action
            $('body').on('click', '#btn-assign', function(){
                // get variable
                let ticket = $("#ticket").val();
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
                        // show response
                        swal.fire({
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false, 
                            timer: 2000
                        });

                        // redirect to unassigned ticket page
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.entry.tickets') }}";
                        }, 2000);
                    }, 
                    error:function(error){
                        console.log(error.responseJSON.message);
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
            })
        });
    </script>
@endsection