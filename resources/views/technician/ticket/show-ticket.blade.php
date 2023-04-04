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
                        @if ($ticket->status == 'On work')    
                            <a href="javascript:void(0)" class="btn btn-primary" id="btn-update" data-id="{{ $ticket->ticket_number }}">
                                <i class="fa-solid fa-wrench"></i>
                                Update Progress
                            </a>
                            <a href="#" class="btn btn-warning">
                                <i class="fa-regular fa-circle-pause"></i>
                                Hold
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

    {{-- Modal --}}
    <div class="modal fade" id="modal-update" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <label class="form-label" for="note">Note*</label>
                            <textarea class="form-control" id="note" rows="5"></textarea>
                            <div class="invalid-feedback d-none" role="alert" id="alert-note"></div>
                        </div>
                        <div class="form-group">
                            <label for="progress" class="form-label">Progress</label>
                            <select id="progress" name="progress" class="selectpicker form-control"></select>
                            <div class="invalid-feedback d-none" role="alert" id="alert-progress"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="update">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // btn_assign button action
            $('body').on('click', '#btn-update', function(){
                // get variable
                let ticket = $(this).data('id');
                let url    = "{{ route('technician.tickets.onwork.progress', ":ticket") }}";
                url        = url.replace(':ticket', ticket);

                // get ticket
                $.ajax({
                    url: url,
                    type: 'get',
                    cache: false,
                    success:function(response){
                        $('#ticket').val(response.ticket_number);
                        $('#progress').attr('disabled', true);
                        $('#progress').empty();
                        for (let i = response.progress; i <= 100; i+=10) {
                            $('#progress').append('<option value="'+i+'">'+i+'%</option>')
                        }
                        $('#note').on('keyup', function(){
                            if ($(this).val().length > 0) {
                                $('#progress').attr('disabled', false);
                            } else {
                                $('#progress').attr('disabled', true);
                            }
                        });
                    }
                });

                // open modal
                $('#modal-update').modal('show');
            });

            // store button action
            $('body').on('click', '#update', function(e){
                // define variable
                let ticket   = $("#ticket").val();
                let note     = $("#note").val();
                let progress = $("#progress").val();
                let token    = $('meta[name="csrf-token"]').attr('content');
                let url      = "{{ route('technician.tickets.onwork.update', ":ticket") }}";
                url          = url.replace(':ticket', ticket);

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

                // ajax update 
                $.ajax({
                    url: url,
                    type: 'patch',
                    cache: false,
                    data: {
                        'note': note,
                        'progress': progress,
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
                            location.reload();
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

                        // if note field has error
                        if (error.responseJSON.note) {
                            // show alert and message
                            $('#note').addClass('is-invalid');
                            $('#alert-note').addClass('d-block');
                            $('#alert-note').removeClass('d-none');
                            $('#alert-note').html(error.responseJSON.note);
                        }

                        // if progress field has error
                        if (error.responseJSON.progress) {
                            // show alert and message
                            $('#progress').addClass('is-invalid');
                            $('#alert-progress').addClass('d-block');
                            $('#alert-progress').removeClass('d-none');
                            $('#alert-progress').html(error.responseJSON.progress);
                        }
                    }
                });
            })
        });
    </script>
@endsection