@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Sub Departments</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-subdept">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add Sub Department
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Total Employee</th>
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

    @include('approver1.subdepartment.modal-create')
    @include('approver1.subdepartment.modal-edit')

    <script>
        $(document).ready(function(){
            // draw table
            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $(".data-table").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('dept.subdepartments') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'total', name: 'total'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // add button event
            $('#btn-create-subdept').click(function(){
                // show modal
                $('#modal-create').modal('show');
            });

            //store button event
            $('#store-subdept').click(function(e){
                e.preventDefault();

                // show loading
                Swal.fire({
                    title: 'Please wait',
                    text: 'Sending request...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false, 
                    allowEnterKey: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });

                // define variable
                let name    = $('#subdept-name').val();
                let token   = $('meta[name="csrf-token"]').attr('content');

                // ajax create
                $.ajax({
                    url: "{{ route('dept.subdepartments.store') }}",
                    type: "post",
                    cache: false,
                    data: {
                        "name": name,
                        "_token": token
                    },
                    success:function(response){
                        // show message
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // clear form
                        $('#form-create-subdept').trigger('reset');

                        // clear alert
                        $('input').removeClass('is-invalid');
                        $('.invalid-feedback').removeClass('d-block');
                        $('.invalid-feedback').addClass('d-none');

                        // modal close
                        $('#modal-create').modal('hide');

                        // draw table
                        table.draw();
                    },
                    error:function(error){
                        // show message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Please check again',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // check if name field error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#alert-subdept-name').removeClass('d-none');
                            $('#alert-subdept-name').addClass('d-block');
                            $('#subdept-name').addClass('is-invalid');

                            // show message
                            $('#alert-subdept-name').html(error.responseJSON.name);
                        } else {
                            // remove alert
                            $('#alert-subdept-name').removeClass('d-block');
                            $('#alert-subdept-name').addClass('d-none');
                            $('#subdept-name').removeClass('is-invalid');
                        }
                    }
                });
            });

            // edit button event
            $('body').on('click', '#btn-edit-subdept', function(){
                // get id
                let id = $(this).data('id');

                // fecth detail department to modal
                $.ajax({
                    url: `sub-departments/${id}/edit`,
                    type: "get",
                    cache: false,
                    success:function(response){
                        if (response) {
                            // fill form
                            $('#subdept-id').val(response.data.id);
                            $('#subdept-name-edit').val(response.data.name);
                        }
                    }
                });

                // show modal edit
                $('#modal-edit').modal('show');
            });

            // update button event
            $('#update-subdept').click(function(e){
                e.preventDefault();

                // show loading
                Swal.fire({
                    title: 'Please wait',
                    text: 'Sending request...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false, 
                    allowEnterKey: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });

                // define variable
                let id      = $('#subdept-id').val();
                let name    = $('#subdept-name-edit').val();
                let token   = $('meta[name="csrf-token"]').attr('content');

                // ajax update
                $.ajax({
                    url: `sub-departments/${id}`,
                    type: 'patch',
                    cache: false,
                    data: {
                        'name': name,
                        '_token': token
                    }, 
                    success:function(response){
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // clear form 
                        $('#form-edit-subdept').trigger('reset');

                        // clear alert
                        $('input').removeClass('is-invalid');
                        $('.invalid-feedback').removeClass('d-block');
                        $('.invalid-feedback').addClass('d-none');

                        // close modal
                        $('#modal-edit').modal('hide');

                        // draw table
                        table.draw();
                    }, 
                    error:function(error){
                        Swal.fire({
                            icon: 'warning',
                            title: 'Please check again',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // check if name field has error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#subdept-name-edit').addClass('is-invalid');
                            $('#alert-subdept-name-edit').removeClass('d-none');
                            $('#alert-subdept-name-edit').addClass('d-block');

                            // show message
                            $('#alert-subdept-name-edit').html(error.responseJSON.name);
                        } else {
                            // remove alert
                            $('#subdept-name-edit').removeClass('is-invalid');
                            $('#alert-subdept-name-edit').removeClass('d-block');
                            $('#alert-subdept-name-edit').addClass('d-none');
                        }
                    }
                });
            });

            // delete button event
            $('body').on('click', '#btn-delete-subdept', function(){
                // get id
                let id    = $(this).data('id');
                let token = $('meta[name="csrf-token"]').attr('content');

                // show confirmation
                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: 'All data related to this sub department will be deleted as well.',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // show loading
                        Swal.fire({
                            title: 'Please wait',
                            text: 'Sending request...',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false, 
                            allowEnterKey: false,
                            didOpen: ()=>{
                                Swal.showLoading();
                            }
                        });

                        // ajax delete
                        $.ajax({
                            url: `sub-departments/${id}`,
                            type: 'delete',
                            cache: false,
                            data: {
                                '_token': token
                            }, 
                            success:function(response){
                                // show message
                                Swal.fire({
                                    icon: 'success',
                                    title: `${response.message}`,
                                    showConfirmButton: false,
                                    timer: 2000
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