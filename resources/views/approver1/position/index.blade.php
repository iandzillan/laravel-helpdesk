@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">{{ Auth::user()->employee->position->subDepartment->name }}'s Position List</h4>
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
                                <th>Sub Department</th>
                                <th>Position</th>
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
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('dept.positions') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'subdept', name: 'subdept'},
                    {data: 'name', name: 'name'},
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
                let name  = $('#name').val();
                let token = $('meta[name="csrf-token"]').attr('content');

                // run ajax create
                $.ajax({
                    url: "{{ route('subdept.positions.store') }}",
                    type: "post",
                    cache: false,
                    data: {
                        'name': name,
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
                    }
                });
            });

            // edit button action
            $('body').on('click', '#btn-edit-position', function(){
                // define variable
                let id = $(this).data('id');

                // ajax show
                $.ajax({
                    url: `positions/${id}/edit`,
                    type: "get",
                    cache: false,
                    success:function(response){
                        // reset form
                        $('body').trigger('reset', '#form-edit-position');

                        // fill form
                        if (response) {
                            $('#id').val(id);
                            $('#name-edit').val(response.data.name);
                        }
                    }
                });

                // show modal
                $('#modal-edit').modal('show');
            });

            // update button action
            $('body').on('click', '#update', function(e){
                e.preventDefault();

                // define variable
                let id    = $('#id').val();
                let name  = $('#name-edit').val();
                let token = $('meta[name="csrf-token"]').attr('content');

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
                    url: `positions/${id}`,
                    type: "patch",
                    cache: false,
                    data: {
                        'name': name,
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

                        // clear alert
                        $('input').removeClass('is-invalid');
                        $('.invalid-feedback').addClass('d-none');
                        $('.invalid-feedback').removeClass('d-block');

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