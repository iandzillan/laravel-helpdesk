@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">{{ Auth::user()->userable->department->name }}'s Position List</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-position">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add Position
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Position</th>
                                <th>Sub Department</th>
                                <th>Total Employees</th>
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

    @include('approver1.position.modal-create')
    @include('approver1.position.modal-edit')

    <script>
        $(document).ready(function(){
            // draw table
            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $(".data-table").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('dept.positions') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'subdept', name: 'subdept'},
                    {data: 'total', name: 'total'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // add button action
            $('body').on('click', '#btn-create-position',function(){
                // ajax getSubdept
                $.ajax({
                    url: "{{ route('dept.positions.getSubdept') }}",
                    type: "get",
                    cache: false,
                    success:function(response){
                        // fill subdept select option 
                        $('#subdept').empty();
                        $('#subdept').append('<option disabled selected> -- Choose -- </option>');
                        $.each(response, function(code, subdept){
                            $('#subdept').append('<option value="'+subdept.id+'">'+subdept.name+'</option>');
                        });
                    }, 
                    error:function(error){
                        console.log(error.responseJSON.message);
                    }
                });
                // show modal create
                $('#modal-create').modal('show');
            });

            // store button action
            $('body').on('click', '#store', function(e){
                e.preventDefault();

                // show loading
                Swal.fire({
                    title: "Please wait",
                    text: "Sending request...",
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false, 
                    allowEnterKey: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });

                // define variable
                let name    = $('#name').val();
                let subdept = $('#subdept').val();
                let token   = $('meta[name="csrf-token"]').attr('content');

                // run ajax create
                $.ajax({
                    url: "{{ route('dept.positions.store') }}",
                    type: "post",
                    cache: false,
                    data: {
                        'name': name,
                        'sub_department_id': subdept,
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

                        // clear form
                        $('#form-create-position').trigger('reset');

                        // clear alert
                        $('input').removeClass('is-invalid');
                        $('.invalid-feedback').addClass('d-none');
                        $('.invalid-feedback').removeClass('d-block');

                        // close modal
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

                        // check if name field which has error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#name').addClass('is-invalid');
                            $('#alert-name').removeClass('d-none');
                            $('#alert-name').addClass('d-block');

                            // show message
                            $('#alert-name').html(error.responseJSON.name);
                        } else {
                            // remove alert
                            $('#name').removeClass('is-invalid');
                            $('#alert-name').removeClass('d-block');
                            $('#alert-name').addClass('d-none');
                        }

                        // check if subdept field which has error
                        if (error.responseJSON.sub_department_id) {
                            // show alert
                            $('#subdept').addClass('is-invalid');
                            $('#alert-subdept').removeClass('d-none');
                            $('#alert-subdept').addClass('d-block');

                            // show message
                            $('#alert-subdept').html(error.responseJSON.sub_department_id);
                        } else {
                            // remove alert
                            $('#subdept').removeClass('is-invalid');
                            $('#alert-subdept').removeClass('d-block');
                            $('#alert-subdept').addClass('d-none');
                        }
                    }
                });
            });

            // edit button action
            $('body').on('click', '#btn-edit-position', function(){
                // define variable
                let id  = $(this).data('id');
                let url = "{{ route('dept.positions.show', ":id") }}";
                url     = url.replace(':id', id);

                // ajax show
                $.ajax({
                    url: url,
                    type: "get",
                    cache: false,
                    success:function(response){
                        // clear alert
                        $('input').removeClass('is-invalid');
                        $('.invalid-feedback').addClass('d-none');
                        $('.invalid-feedback').removeClass('d-block');

                        // get subdept id
                        let subdept_id = response.data.sub_department_id;

                        // ajax get subdept
                        $.ajax({
                            url: "{{ route('dept.positions.getSubdept') }}",
                            type: "get",
                            cache: false,
                            success:function(response1){
                                $('#subdept-edit').empty();
                                $('#subdept-edit').append('<option disabled selected> -- Choose -- </option>');
                                $.each(response1, function(code, subdept){
                                    $('#subdept-edit').append('<option value="'+subdept.id+'">'+subdept.name+'</option>');
                                    $('#subdept-edit option[value="'+subdept_id+'"]').attr('selected', 'selected');
                                });
                            }
                        });

                        // fill form
                        $('#id').val(id);
                        $('#name-edit').val(response.data.name);
                    }, 
                    error:function(error){
                        console.log(error.responseJSON.message);
                    }
                });

                // show modal
                $('#modal-edit').modal('show');
            });

            // update button action
            $('body').on('click', '#update', function(e){
                e.preventDefault();

                // define variable
                let id      = $('#id').val();
                let name    = $('#name-edit').val();
                let subdept = $('#subdept-edit').val();
                let token   = $('meta[name="csrf-token"]').attr('content');
                let url     = "{{ route('dept.positions.update', ":id") }}";
                url         = url.replace(':id', id);

                // show loading
                Swal.fire({
                    title: "Please wait",
                    text: "Sending request...",
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    didOpen: ()=>{
                        Swal.showLoading();
                    }
                });

                // ajax update
                $.ajax({
                    url: url,
                    type: "patch",
                    cache: false,
                    data: {
                        'name': name,
                        'sub_department_id': subdept,
                        '_token': token
                    },
                    success:function(response){
                        // show message
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: false
                        });

                        // clear form
                        $('#form-edit-position').trigger('reset');

                        // close modal
                        $('#modal-edit').modal('hide');

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

                        // check if name field has error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#name-edit').addClass('is-invalid');
                            $('#alert-name-edit').removeClass('d-none');
                            $('#alert-name-edit').addClass('d-block');

                            // show message
                            $('#alert-name-edit').html(error.responseJSON.name);
                        } else {
                            // remove alert
                            $('#name-edit').removeClass('is-invalid');
                            $('#alert-name-edit').removeClass('d-block');
                            $('#alert-name-edit').addClass('d-none');
                        }

                        // check if subdept field has error
                        if (error.responseJSON.sub_department_id) {
                            // show alert
                            $('#subdept-edit').addClass('is-invalid');
                            $('#alert-subdept-edit').removeClass('d-none');
                            $('#alert-subdept-edit').addClass('d-block');

                            // show message
                            $('#alert-subdept-edit').html(error.responseJSON.sub_department_id);
                        } else {
                            // remove alert
                            $('#subdept-edit').removeClass('is-invalid');
                            $('#alert-subdept-edit').removeClass('d-block');
                            $('#alert-subdept-edit').addClass('d-none');
                        }
                    }
                });
            });

            // delete button action
            $('body').on('click', '#btn-delete-position', function(){
                // define variable
                let id = $(this).data('id');
                let token = $('meta[name="csrf-token"]').attr('content');

                // show confirmation
                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: 'All employees in this position will be deleted as well.',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result)=>{
                    if (result.isConfirmed) {
                        // show loading
                        Swal.fire({
                            title: 'Please wait',
                            text: 'Sending request...',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // ajax delete
                        $.ajax({
                            url: `positions/${id}`,
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