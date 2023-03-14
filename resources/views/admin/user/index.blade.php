@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Users</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-user">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add User
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nik</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
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

    @include('admin.user.modal-create')
    @include('admin.user.modal-edit')

    <script>
        $(document).ready(function(){
            // select2 modal 
            $('.select2').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                dropdownParent: $('#modal-create-user')
            });

            // draw table
            let table = $('.data-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'nik', name: 'nik'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'role', name: 'role'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            // add button action
            $('#btn-create-user').click(function(){
                // reset form
                $('#form-create-user').trigger('reset');

                // get all employee
                $.ajax({
                    url: "{{ route('admin.users.getEmployee') }}",
                    type: "get",
                    cache: false,
                    success:function(response){
                        // fill employee select
                        $('#employee').empty();
                        $('#employee').append('<option selected> -- Choose -- </option>');
                        $.each(response, function(code, employee){
                            $('#employee').append('<option value="'+employee.nik+'">'+employee.nik+' - '+employee.name+'</option>')
                        });
                    }
                });

                // append role
                $('#role').empty();
                $('#role').append('<option selected> -- Choose -- </option>')
                $('#role').append('<option value="Approver1"> Approver Lv-1 (Dept Head) </option>')
                $('#role').append('<option value="Approver2"> Approver Lv-2 (Sub Dept Head) </option>')
                $('#role').append('<option value="User"> User </option>')
                $('#role').append('<option value="Technician"> Technician </option>')

                // show modal
                $('#modal-create-user').modal('show');
            });

            // store button action
            $('body').on('click', '#store-user', function(e){
                e.preventDefault();

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

                // define variable
                let employee_nik          = $('#employee').val();
                let role                  = $('#role').val();
                let email                 = $('#email').val() + '@example.com';
                let username              = $('#username').val();
                let password              = $('#password').val();
                let password_confirmation = $('#password-confirm').val();
                let token                 = $('meta[name="csrf-token"]').attr('content');

                // ajax create
                $.ajax({
                    url: "{{ route('admin.users.store') }}",
                    type: "post",
                    cache: false,
                    data: {
                        'employee': employee_nik,
                        'role': role,
                        'email': email,
                        'username': username,
                        'password': password,
                        'password_confirmation': password_confirmation,
                        '_token': token
                    },
                    success:function(response){
                        // ajax send email
                        $.ajax({
                            url: "{{ route('admin.users.accountActive') }}",
                            type: "get",
                            cache: false,
                            data: {
                                'employee': employee_nik,
                                'email': email,
                                'username': username,
                                'password': password,
                                'role': role
                            },
                            success:function(response1){
                                // show message
                                Swal.fire({
                                    icon: 'success',
                                    title: `${response1.message}`,
                                    text: `${response1.text}`,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            },
                            error:function(error1){
                                console.log(error1.responseJSON.message);
                                // show message
                                Swal.fire({
                                    icon: 'warning',
                                    title: "User has been created but email notification failed to be sent to user",
                                    text: `${error1.responseJSON.message}`,
                                    showConfirmButton: false,
                                });
                            }
                        });

                        // clode modal
                        $('#modal-create-user').modal('hide');

                        // draw table
                        table.draw();
                    }, 
                    error:function(error){
                        console.log(error.responseJSON.message);
                        // show message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Please check again',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // check if employee field has error
                        if (error.responseJSON.employee) {
                            // show alert
                            $('#employee').addClass('is-invalid');
                            $('#alert-employee').removeClass('d-none');
                            $('#alert-employee').addClass('d-block');
                            // show message
                            $('#alert-employee').html(error.responseJSON.employee);
                        } else {
                            // remove alert
                            $('#employee').removeClass('is-invalid');
                            $('#alert-employee').removeClass('d-block');
                            $('#alert-employee').addClass('d-none');
                        }

                        // check if role field has error
                        if (error.responseJSON.role) {
                            // show alert
                            $('#role').addClass('is-invalid');
                            $('#alert-role').removeClass('d-none');
                            $('#alert-role').addClass('d-block');
                            // show message
                            $('#alert-role').html(error.responseJSON.role);
                        } else {
                            // remove alert
                            $('#role').removeClass('is-invalid');
                            $('#alert-role').removeClass('d-block');
                            $('#alert-role').addClass('d-none');
                        }

                        // check if email field has error
                        if (error.responseJSON.email) {
                            // show alert
                            $('#email').addClass('is-invalid');
                            $('#alert-email').removeClass('d-none');
                            $('#alert-email').addClass('d-block');
                            // show message
                            $('#alert-email').html(error.responseJSON.email);
                        } else {
                            // remove alert
                            $('#email').removeClass('is-invalid');
                            $('#alert-email').removeClass('d-block');
                            $('#alert-email').addClass('d-none');
                        }

                        // check if username field has error
                        if (error.responseJSON.username) {
                            // show alert
                            $('#username').addClass('is-invalid');
                            $('#alert-username').removeClass('d-none');
                            $('#alert-username').addClass('d-block');
                            // show message
                            $('#alert-username').html(error.responseJSON.username);
                        } else {
                            // remove alert
                            $('#username').removeClass('is-invalid');
                            $('#alert-username').removeClass('d-block');
                            $('#alert-username').addClass('d-none');
                        }

                        // check if password field has error
                        if (error.responseJSON.password) {
                            // show alert
                            $('#password').addClass('is-invalid');
                            $('#alert-password').removeClass('d-none');
                            $('#alert-password').addClass('d-block');
                            // show message
                            $('#alert-password').html(error.responseJSON.password);
                        } else {
                            // remove alert
                            $('#password').removeClass('is-invalid');
                            $('#alert-password').removeClass('d-block');
                            $('#alert-password').addClass('d-none');
                        }

                        // check if password-confirm field has error
                        if (error.responseJSON.password_confirmation) {
                            // show alert
                            $('#password-confirm').addClass('is-invalid');
                            $('#alert-password-confirm').removeClass('d-none');
                            $('#alert-password-confirm').addClass('d-block');
                            // show message
                            $('#alert-password-confirm').html(error.responseJSON.password);
                        } else {
                            // remove alert
                            $('#password-confirm').removeClass('is-invalid');
                            $('#alert-password-confirm').removeClass('d-block');
                            $('#alert-password-confirm').addClass('d-none');
                        }
                    }
                });
            });

            // delete button action
            $('body').on('click', '#btn-delete-user', function(){
                // define variable
                let id = $(this).data('id');
                let token = $('meta[name="csrf-token"]').attr('content');

                // show confirmation
                Swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: 'This user will be deleted',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes'
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
                            url: `users/${id}`,
                            type: "delete",
                            cache: false,
                            data:{
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
                            }, 
                            error:function(error){
                                console.log(error.responseJSON.message);
                            }
                        });
                    }
                });
            });
        });
    </script>
    
@endsection