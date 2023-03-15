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
                $('#modal-create').modal('show');
            })

            // store urgency button event
            $('#store-urgency').click(function(e){
                e.preventDefault();

                // define variable
                let name        = $('#urgency-name').val();
                let duration    = $('#urgency-hours').val();
                let token       = $('meta[name="csrf-token"]').attr('content');

                // show loading
                Swal.fire({
                    title: 'Please wait',
                    text: 'Sending request...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEnterKey: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

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
                            timer: 2000
                        });

                        // clear form
                        $('#form-create-urgency').trigger('reset');

                        // clear alert
                        $('#urgency-name').removeClass('is-invalid');
                        $('#alert-urgency-name').addClass('d-none');
                        $('#alert-urgency-name').removeClass('d-block');
                        $('#urgency-hours').removeClass('is-invalid');
                        $('#alert-urgency-hours').addClass('d-none');
                        $('#alert-urgency-hours').removeClass('d-block');

                        // close modal
                        $('#modal-create').modal('hide');

                        // draw table
                        table.draw();
                    }, 
                    error:function(error){
                        // show success message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check again',
                            showConfirmButton:false,
                            timer:1000
                        });

                        // check if urgency name error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#urgency-name').addClass('is-invalid');
                            $('#alert-urgency-name').removeClass('d-none');
                            $('#alert-urgency-name').addClass('d-block');

                            // show message alert
                            $('#alert-urgency-name').html(error.responseJSON.name);
                        } else {
                            // remove alert
                            $('#urgency-name').removeClass('is-invalid');
                            $('#alert-urgency-name').addClass('d-none');
                            $('#alert-urgency-name').removeClass('d-block');
                        }

                        // check if urgency duration error
                        if (error.responseJSON.hours) {
                            // show alert
                            $('#urgency-hours').addClass('is-invalid');
                            $('#alert-urgency-hours').removeClass('d-none');
                            $('#alert-urgency-hours').addClass('d-block');

                            // show message alert
                            $('#alert-urgency-hours').html(error.responseJSON.hours);
                        } else {
                            // remove alert
                            $('#urgency-hours').removeClass('is-invalid');
                            $('#alert-urgency-hours').addClass('d-none');
                            $('#alert-urgency-hours').removeClass('d-block');
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
                    url: `urgencies/${id}/edit`,
                    type: "get",
                    cache: false,
                    success:function(response){
                        if(response){
                            // fill form
                            $('#urgency-id').val(response.data.id);
                            $('#urgency-name-edit').val(response.data.name);
                            $('#urgency-hours-edit').val(response.data.hours);
                        } else {
                            $('#form-edit-urgency').trigger('reset');
                        }
                    }
                });

                // show modal
                $('#modal-edit').modal('show');
            });

            // update urgency button event
            $('#update-urgency').click(function(e){
                e.preventDefault();

                // define variable
                let id    = $('#urgency-id').val();
                let name  = $('#urgency-name-edit').val();
                let hours = $('#urgency-hours-edit').val();
                let token = $('meta[name="csrf-token"]').attr('content');

                // show loading
                Swal.fire({
                    title: 'Please wait',
                    text: 'Sending request...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEnterKey: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // ajax update 
                $.ajax({
                    url: `urgencies/${id}`,
                    type: "patch",
                    cache: false,
                    data: {
                        "name": name,
                        "hours": hours,
                        "_token": token
                    },
                    success:function(response){
                        Swal.fire({
                            icon: "success", 
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // clear form
                        $('#form-edit-urgency').trigger('reset');

                        // clear alert
                        $('#alert-urgency-name-edit').addClass('d-none');
                        $('#alert-urgency-name-edit').removeClass('d-block');
                        $('#urgency-name-edit').removeClass('is-invalid');
                        $('#alert-urgency-hours-edit').addClass('d-none');
                        $('#alert-urgency-hours-edit').removeClass('d-block');
                        $('#urgency-hours-edit').removeClass('is-invalid');

                        // modal edit hide
                        $('#modal-edit').modal('hide');

                        // draw table
                        table.draw();
                    }, 
                    error:function(error){
                        // show success message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check again',
                            showConfirmButton:false,
                            timer:1000
                        });

                        // check if name field error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#alert-urgency-name-edit').removeClass('d-none');
                            $('#alert-urgency-name-edit').addClass('d-block');
                            $('#urgency-name-edit').addClass('is-invalid');

                            // show message
                            $('#alert-urgency-name-edit').html(error.responseJSON.name)
                        } else {
                            // remove alert
                            $('#alert-urgency-name-edit').addClass('d-none');
                            $('#alert-urgency-name-edit').removeClass('d-block');
                            $('#urgency-name-edit').removeClass('is-invalid');
                        }

                        // check if duration field error
                        if (error.responseJSON.hours) {
                            // show alert
                            $('#alert-urgency-hours-edit').removeClass('d-none');
                            $('#alert-urgency-hours-edit').addClass('d-block');
                            $('#urgency-hours-edit').addClass('is-invalid');

                            // show message
                            $('#alert-urgency-hours-edit').html(error.responseJSON.hours);
                        } else {
                            // remove alert
                            $('#alert-urgency-hours-edit').addClass('d-none');
                            $('#alert-urgency-hours-edit').removeClass('d-block');
                            $('#urgency-hours-edit').removeClass('is-invalid');
                        }
                    }
                });
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
                        // show loading
                        Swal.fire({
                            title: 'Please wait',
                            text: 'Sending request...',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEnterKey: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
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