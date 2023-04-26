@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Feedback</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ticket Number</th>
                                <th>Technician</th>
                                <th>Progress At</th>
                                <th>Finished At</th>
                                <th>SLA Duration</th>
                                <th>Solve Duration</th>
                                <th>Pending Duration</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal add --}}
    <div class="modal fade" id="modal-feedback" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-feedback">
                    <div class="modal-body">
                        <input type="text" name="id" id="id">
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 mb-3">
                                <p class="h3 text-center mb-3">How was your support experience?</p>
                            </div>

                            <div class="col-12 mb-3">
                                <p class="h5 text-center mb-3">We'll appreciate your honest feedback</p>
                            </div>

                            <div class="col-12 d-flex justify-content-center mb-4">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label h1" for="rating3">
                                        <input class="form-check-input" type="radio" name="rating" id="rating3" value="3">
                                        <span class="border rounded px-3 py-2">
                                            <i class="fa-solid fa-face-smile text-success"></i>
                                        </span>
                                    </label>
                                </div>

                                
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label h1" for="rating2">
                                        <input class="form-check-input" type="radio" name="rating" id="rating2" value="2">
                                        <span class="border rounded px-3 py-2">
                                            <i class="fa-regular fa-face-meh text-secondary"></i>
                                        </span>
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <label class="form-check-label h1" for="rating1">
                                        <input class="form-check-input" type="radio" name="rating" id="rating1" value="1">
                                        <span class="border rounded px-3 py-2">
                                            <i class="fa-regular fa-face-frown text-danger"></i>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="note" class="form-label">Would you like to add comment?</label>
                                <textarea class="form-control" id="note" name="note" rows="3" placeholder="Your Comment"></textarea>
                                <div class="invalid-feedback d-none" role="alert" id="alert-note"></div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="store-urgency">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            let table = $('.data-table').DataTable({
                scrollX: true,
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.feedback') }}",
                columns: [
                    {data:'DT_RowIndex', name: 'DT_RowIndex'},
                    {data:'ticket_number', name: 'ticket_number'},
                    {data:'technician', name: 'technician'},
                    {data:'progress_at', name: 'progress_at'},
                    {data:'finish_at', name: 'finish_at'},
                    {data:'sla', name: 'sla'},
                    {data:'duration', name: 'duration'},
                    {data:'pending', name: 'pending'},
                    {data:'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('body').on('click', '#btn-feedback', function(){
                $('#form-feedback').trigger('reset');
                $('#modal-feedback').modal('show');
            });
        });
    </script>
@endsection