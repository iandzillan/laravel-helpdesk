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
                                <th>Department</th>
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

    @include('admin.subdepartment.modal-create')
    @include('admin.subdepartment.modal-edit')

    <script>
        $(function(){
            // draw table
            let table = $('.data-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.subdepartments') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'department', name: 'department'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // add button event
            $('#btn-create-subdept').click(function(){
                // fetch departments to select option
                $.ajax({
                    url: "{{ route('admin.subdepartments.getDepts') }}",
                    type: "get",
                    cache: false,
                    success:function(response){
                        if (response) {
                            // fill select option with departments
                            $('#dept-id').empty();
                            $('#dept-id').append(`
                                <option selected> -- Choose Department -- </option>
                            `);
                            $.each(response, function(code, dept){
                                $('#dept-id').append('<option value="'+dept.id+'">'+dept.name+'</option>');
                            });
                        } else {
                            $('#dept-id').empty();
                        }
                    }
                });

                // show modal
                $('#modal-create-subdept').modal('show');
            });

            //store button event
            $('#store-subdept').click(function(e){
                e.preventDefault();

                // define variable
                let dept_id = $('#dept-id').val();
                let name    = $('#subdept-name').val();
                let token   = $('meta[name="csrf-token"]').attr('content');

                // ajax create
                $.ajax({
                    url: "{{ route('admin.subdepartments.store') }}",
                    type: "post",
                    cache: false,
                    data: {
                        "department_id": dept_id,
                        "name": name,
                        "_token": token
                    },
                    success:function(response){
                        // show message
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // clear form
                        $('#form-create-subdept').trigger('reset');

                        // modal close
                        $('#modal-create-subdept').modal('hide');

                        // draw table
                        table.draw();
                    },
                    error:function(error){
                        // check if name field error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#alert-subdept-name').removeClass('d-none');
                            $('#alert-subdept-name').addClass('d-block');
                            $('#subdept-name').addClass('is-invalid');

                            // show message
                            $('#alert-subdept-name').html(error.responseJSON.name);
                        }

                        // check if dept field error
                        if (error.responseJSON.department_id) {
                            // show alert
                            $('#alert-dept-id').removeClass('d-none');
                            $('#alert-dept-id').addClass('d-block');
                            $('#dept-id').addClass('is-invalid');

                            // show message
                            $('#alert-dept-id').html(error.responseJSON.department_id);
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
                            $('#dept-id-edit').empty();
                            $('#subdept-id').val(response.data.id);
                            $('#subdept-name-edit').val(response.data.name);
                            $.each(response.depts, function(code, dept){
                                $('#dept-id-edit').append('<option value="'+dept.id+'">'+dept.name+'</option>');
                                $(`#dept-id-edit option[value="${response.data.department_id}"]`).attr('selected', 'selected');
                            });
                        } else {
                            $('#dept-id-edit').empty();
                        }
                    }
                });

                // show modal edit
                $('#modal-edit-subdept').modal('show');
            });

            // update button event
            $('#update-subdept').click(function(e){
                e.preventDefault();

                // define variable
                let id      = $('#subdept-id').val();
                let name    = $('#subdept-name-edit').val();
                let dept_id = $('#dept-id-edit').val();
                let token   = $('meta[name="csrf-token"]').attr('content');

                // ajax update
                $.ajax({
                    url: `sub-departments/${id}`,
                    type: 'patch',
                    cache: false,
                    data: {
                        'name': name,
                        'department_id': dept_id,
                        '_token': token
                    }, 
                    success:function(response){
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // clear form 
                        $('#form-edit-subdept').trigger('reset');

                        // close modal
                        $('#modal-edit-subdept').modal('hide');

                        // draw table
                        table.draw();
                    }, 
                    error:function(error){
                        // check if name field has error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#subdept-name-edit').addClass('is-invalid');
                            $('#alert-subdept-name-edit').removeClass('d-none');
                            $('#alert-subdept-name-edit').addClass('d-block');

                            // show message
                            $('#alert-subdept-name-edit').html(error.responseJSON.name);
                        }

                        // check if dept select option has error
                        if (error.responseJSON.department_id) {
                            // show alert
                            $('#alert-dept-id-edit').removeClass('d-none');
                            $('#alert-dept-id-edit').addClass('d-block');
                            $('#dept-id-edit').addClass('is-invalid');

                            // show message
                            $('#alert-dept-id-edit').html(error.responseJSON.department_id);
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
                    text: 'This sub department will be deleted',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'yes'
                }).then((result) => {
                    if (result.isConfirmed) {
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