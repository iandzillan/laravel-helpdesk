@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Urgencies</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-urgency">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add Urgency
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Duration (Hours)</th>
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

    @include('admin.urgency.modal-create')
    @include('admin.urgency.modal-edit')

    <script>
        $(function(){

            // draw table
            let table = $('.data-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.urgencies') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'hours', name: 'hours'},
                    {data: 'action', name: 'action', orderable:false, searchable: false},
                ]
            });

            // add urgency button event
            $('body').on('click', '#btn-create-urgency', function(){
                // show modal create
                $('#modal-create-urgency').modal('show');
            })

            // store urgency button event
            $('#store-urgency').click(function(e){
                e.preventDefault();

                // define variable
                let name        = $('#urgency-name').val();
                let duration    = $('#urgency-hours').val();
                let token       = $('meta[name="csrf-token"]').attr('content');

                // ajax create
                $.ajax({
                    url: "{{ route('admin.urgencies.store') }}",
                    type: "post",
                    cache: false,
                    data: {
                        "name": name,
                        "hours": duration,
                        "_token": token
                    }, 
                    success:function(response){
                        // show success message
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // close modal
                        $('#modal-create-urgency').modal('hide');

                        // draw table
                        table.draw();
                    }, 
                    error:function(error){
                        // check if urgency name error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#urgency-name').addClass('is-invalid');
                            $('#alert-urgency-name').removeClass('d-none');
                            $('#alert-urgency-name').addClass('d-block');

                            // show message alert
                            $('#alert-urgency-name').html(error.responseJSON.name);
                        }

                        // check if urgency duration error
                        if (error.responseJSON.hours) {
                            // show alert
                            $('#urgency-hours').addClass('is-invalid');
                            $('#alert-urgency-hours').removeClass('d-none');
                            $('#alert-urgency-hours').addClass('d-block');

                            // show message alert
                            $('#alert-urgency-hours').html(error.responseJSON.hours);
                        }
                    }
                });
            });

            // edit urgency button event
            $('body').on('click', '#btn-edit-urgency', function(){
                // define variable
                let id = $(this).data('id');

                // fetch detail urgency to modal
                $.ajax({
                    url: `urgency/${id}/edit`,
                    type: "get",
                    cache: false,
                    success:function(response){
                        if(response){
                            // fill form
                            $('#urgency-id').val(response.data.id);
                            $('#urgency-name-edit').val(response.data.name);
                            $('#urgency-hours-edit').val(response.data.hours);
                        } else {
                            $('#form-edit-urgency')[0].reset();
                        }
                    }
                });

                // show modal
                $('#modal-edit-urgency').modal('show');
            });

            // delete urgency button event
            $('body').on('click', '#btn-delete-urgency', function(){
                let id      = $(this).data('id');
                let token   = $('meta[name="csrf-token"]').attr('content');

                // confirmation
                Swal.fire({
                    title: "Are you sure?",
                    text: "This urgency will be deleted",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    confirmButtonText:"Yes"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax delete
                        $.ajax({
                            url: `urgencies/${id}`,
                            type: "delete",
                            cache: false,
                            data: {
                                "_token": token
                            }, 
                            success:function(response){
                                // show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: `${response.message}`,
                                    showConfirmButton: false,
                                    timer: 3000
                                });

                                // draw table
                                table.draw();
                            }
                        });
                    }
                });
            });
        });
    </script>

@endsection