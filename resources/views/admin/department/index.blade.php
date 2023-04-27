@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Departments</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-department">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add Department
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
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

    @include('admin.department.modal-create')
    @include('admin.department.modal-edit')

    <script>
        $(function(){
            // draw table
            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $(".data-table").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('admin.departments') }}", 
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // add button event
            $('body').on('click', '#btn-create-department', function(){
                // show modal
                $('#modal-create').modal('show');
            });

            // store button event
            $('#store-department').click(function(e){
                e.preventDefault();

                // define variable
                let name  = $('#department-name').val();
                let token = $('meta[name=csrf-token]').attr('content');

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
                    url: "{{ route('admin.departments.store') }}",
                    type: "post",
                    cache: false,
                    data: {
                        'name': name,
                        '_token': token
                    }, 
                    success:function(response){
                        // Show message
                        Swal.fire({
                            icon: "success",
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // form reset
                        $('#form-create-department').trigger('reset');

                        // clear alert
                        $('input').removeClass('is-invalid');
                        $('.invalid-feedback').removeClass('d-block');
                        $('.invalid-feedback').addClass('d-none');

                        // close modal
                        $('#modal-create').modal('hide');

                        // drar table
                        table.draw();
                    }, 
                    error:function(error){
                        // show success message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check again',
                            showConfirmButton:false,
                            timer: 1000
                        });
                        
                        // if error on name field
                        if (error.responseJSON.name) {
                            // show alert
                            $('#alert-department-name').removeClass('d-none');
                            $('#alert-department-name').addClass('d-block');
                            $('#department-name').addClass('is-invalid');

                            // show message 
                            $('#alert-department-name').html(error.responseJSON.name);
                        } else {
                            // remove alert
                            $('#alert-department-name').removeClass('d-block');
                            $('#alert-department-name').addClass('d-none');
                            $('#department-name').removeClass('is-invalid');
                        }
                    }
                });
            });

            // edit button event
            $('body').on('click', '#btn-edit-department', function(){
                // define variable
                let id = $(this).data('id');

                // fetch data to modal
                $.ajax({
                    url: `departments/${id}/edit`,
                    type: "get",
                    cache: false,
                    success:function(response){
                        // fill form
                        $('#department-id').val(response.data.id)
                        $('#department-name-edit').val(response.data.name);
                    }
                });

                // show edit modal
                $('#modal-edit').modal('show');
            });

            // update button event
            $('#update-department').click(function(e){
                e.preventDefault();

                // define variable
                let id    = $('#department-id').val();
                let name  = $('#department-name-edit').val();
                let token = $('meta[name="csrf-token"]').attr('content');

                // ajax update
                $.ajax({
                    url: `departments/${id}`,
                    type: "patch",
                    cache: false,
                    data: {
                        'name': name,
                        '_token': token
                    },
                    success:function(response){
                        // show message
                        Swal.fire({
                            icon: "success",
                            title: "The department has been updated",
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // reset form
                        $('#form-edit-department').trigger('reset');

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
                        // show message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check again',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // if error on name field
                        if (error.responseJSON.name) {
                            // show alert
                            $('#alert-department-name-edit').removeClass('d-none');
                            $('#alert-department-name-edit').addClass('d-block');
                            $('#department-name-edit').addClass('is-invalid');

                            // show message 
                            $('#alert-department-name-edit').html(error.responseJSON.name);
                        } else {
                            $('#alert-department-name-edit').removeClass('d-block');
                            $('#alert-department-name-edit').addClass('d-none');
                            $('#department-name-edit').removeClass('is-invalid');
                        }
                    }
                });
            });

            // delete button event
            $('body').on('click', '#btn-delete-department', function(){
                // define variable
                let id    = $(this).data('id');
                let token = $('meta[name="csrf-token"]').attr('content');

                // show confirmation
                Swal.fire({
                    icon: 'warning', 
                    title: 'Are you sure?',
                    text: 'All data related to this department will be deleted',
                    showCancelButton: true,
                    cancelButtonText: "No", 
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if(result.isConfirmed){
                        // ajax delete
                        $.ajax({
                            url: `departments/${id}`,
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