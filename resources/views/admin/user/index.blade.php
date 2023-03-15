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

            // role-edit select
            $('.select2-edit').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                placeholder: $(this).data('placeholder'),
                dropdownParent: $('#modal-edit-user')
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
                let email                 = $('#email').val() + $('#domain-name').text();
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
                        // show message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check carefully',
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

            // edit button action
            $('body').on('click', '#btn-edit-user', function(){
                // get id
                let id = $(this).data('id');

                // ajax show
                $.ajax({
                    url: `users/${id}/edit`,
                    type: 'get',
                    cache: false,
                    success:function(response){
                        // fill form
                        $('#id').val(id);
                        $('#employee-edit').val(response.dataEmployee.nik + ' - ' + response.dataEmployee.name);
                        $(`#role-edit option[value=${response.dataUser.role}]`).attr('selected', 'selected').change();
                        $('#email-edit').val(response.dataUser.email.split('@')[0]);
                        $('#username-edit').val(response.dataUser.username);
                    }
                });

                // clear form
                $('#form-edit-user').trigger('reset');

                // show modal
                $('#modal-edit-user').modal('show');
            });

            // update button action
            $('body').on('click', '#update-user', function(e){
                e.preventDefault();

                // define variable
                let id = $('#id').val();
                let role = $('#role-edit').val();
                let email = $('#email-edit').val() + $('#domain-name-edit').text();
                let username = $('#username-edit').val();
                let password = $('#password-edit').val();
                let password_confirm = $('#password-confirm-edit').val();
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

                // Ajax update
                $.ajax({
                    url: `users/${id}`,
                    type: 'patch',
                    cache: false,
                    data: {
                        'role': role,
                        'email': email,
                        'username': username,
                        'password': password,
                        'password_confirmation': password_confirm,
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
                        $('#form-edit-user').trigger('reset');

                        // close modal
                        $('#modal-edit-user').modal('hide');

                        // draw table
                        table.draw();
                    },
                    error:function(error){
                        console.log(error.responseJSON.message);
                        // show message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check carefully',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // check if employee field has error
                        if (error.responseJSON.employee) {
                            // show alert
                            $('#employee-edit').addClass('is-invalid');
                            $('#alert-employee-edit').removeClass('d-none');
                            $('#alert-employee-edit').addClass('d-block');
                            // show message
                            $('#alert-employee-edit').html(error.responseJSON.employee);
                        } else {
                            // remove alert
                            $('#employee-edit').removeClass('is-invalid');
                            $('#alert-employee-edit').removeClass('d-block');
                            $('#alert-employee-edit').addClass('d-none');
                        }

                        // check if role field has error
                        if (error.responseJSON.role) {
                            // show alert
                            $('#role-edit').addClass('is-invalid');
                            $('#alert-role-edit').removeClass('d-none');
                            $('#alert-role-edit').addClass('d-block');
                            // show message
                            $('#alert-role-edit').html(error.responseJSON.role);
                        } else {
                            // remove alert
                            $('#role-edit').removeClass('is-invalid');
                            $('#alert-role-edit').removeClass('d-block');
                            $('#alert-role-edit').addClass('d-none');
                        }

                        // check if email field has error
                        if (error.responseJSON.email) {
                            // show alert
                            $('#email-edit').addClass('is-invalid');
                            $('#alert-email-edit').removeClass('d-none');
                            $('#alert-email-edit').addClass('d-block');
                            // show message
                            $('#alert-email-edit').html(error.responseJSON.email);
                        } else {
                            // remove alert
                            $('#email-edit').removeClass('is-invalid');
                            $('#alert-email-edit').removeClass('d-block');
                            $('#alert-email-edit').addClass('d-none');
                        }

                        // check if username field has error
                        if (error.responseJSON.username) {
                            // show alert
                            $('#username-edit').addClass('is-invalid');
                            $('#alert-username-edit').removeClass('d-none');
                            $('#alert-username-edit').addClass('d-block');
                            // show message
                            $('#alert-username-edit').html(error.responseJSON.username);
                        } else {
                            // remove alert
                            $('#username-edit').removeClass('is-invalid');
                            $('#alert-username-edit').removeClass('d-block');
                            $('#alert-username-edit').addClass('d-none');
                        }

                        // check if password field has error
                        if (error.responseJSON.password) {
                            // show alert
                            $('#password-edit').addClass('is-invalid');
                            $('#alert-password-edit').removeClass('d-none');
                            $('#alert-password-edit').addClass('d-block');
                            // show message
                            $('#alert-password-edit').html(error.responseJSON.password);
                        } else {
                            // remove alert
                            $('#password-edit').removeClass('is-invalid');
                            $('#alert-password-edit').removeClass('d-block');
                            $('#alert-password-edit').addClass('d-none');
                        }

                        // check if password-confirm field has error
                        if (error.responseJSON.password_confirmation) {
                            // show alert
                            $('#password-confirm-edit').addClass('is-invalid');
                            $('#alert-password-confirm-edit').removeClass('d-none');
                            $('#alert-password-confirm-edit').addClass('d-block');
                            // show message
                            $('#alert-password-confirm-edit').html(error.responseJSON.password);
                        } else {
                            // remove alert
                            $('#password-confirm-edit').removeClass('is-invalid');
                            $('#alert-password-confirm-edit').removeClass('d-block');
                            $('#alert-password-confirm-edit').addClass('d-none');
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