<script>
    $(document).ready(function(){
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

        // add user employee button action
        $('#add-user-employee').click(function(){
            // get all employee
            $.ajax({
                url: "{{ route('admin.users.getEmployee') }}",
                type: "get",
                cache: false,
                success:function(response){
                    // fill user select
                    $('#user').empty();
                    $('#user').append('<option selected> -- Choose -- </option>');
                    $.each(response, function(code, employee){
                        $('#user').append('<option value="'+employee.nik+'">'+employee.nik+' - '+employee.name+'</option>')
                    });
                }
            });

            // append role
            $('#role').empty();
            $('#role').append('<option selected> -- Choose -- </option>')
            $('#role').append('<option value="Approver2"> Approver Lv.2 (Sub Dept Head) </option>')
            $('#role').append('<option value="User"> User </option>')
            $('#role').append('<option value="Technician"> Technician </option>')

            // show modal
            $('#modal-create').modal('show');
        });

        // add user manager button action
        $('#add-user-manager').click(function(){
            // get all Manager
            $.ajax({
                url: "{{ route('admin.users.getManagers') }}",
                type: "get",
                cache: false,
                success:function(response){
                    // fill user select
                    $('#user').empty();
                    $('#user').append('<option selected> -- Choose -- </option>');
                    $.each(response, function(code, managers){
                        $('#user').append('<option value="'+managers.nik+'">'+managers.nik+' - '+managers.name+'</option>')
                    });
                }
            });

            // append role
            $('#role').empty();
            $('#role').append('<option selected readonly value="Approver1"> Approver Lv.1 (Manager) </option>');

            // show modal
            $('#modal-create').modal('show');
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
            let user                  = $('#user').val();
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
                    'user': user,
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
                            'user': user,
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
                    // reset form
                    $('#form-create-user').trigger('reset');

                    // remove alert
                    $('input').removeClass('is-invalid');
                    $('select').removeClass('is-invalid');
                    $('.invalid-feedback').removeClass('d-block');
                    $('.invalid-feedback').removeClass('d-none');

                    // close modal
                    $('#modal-create').modal('hide');

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
                    if (error.responseJSON.user) {
                        // show alert
                        $('#user').addClass('is-invalid');
                        $('#alert-user').removeClass('d-none');
                        $('#alert-user').addClass('d-block');
                        // show message
                        $('#alert-user').html(error.responseJSON.user);
                    } else {
                        // remove alert
                        $('#user').removeClass('is-invalid');
                        $('#alert-user').removeClass('d-block');
                        $('#alert-user').addClass('d-none');
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

            // 

            // ajax show
            $.ajax({
                url: `users/${id}/edit`,
                type: 'get',
                cache: false,
                success:function(response){
                    // fill form
                    $('#id').val(id);
                    $('#user-edit').val(response.dataRelation.nik + ' - ' + response.dataRelation.name);
                    $('#role-edit').empty();
                    $('#role-edit').append('<option disabled selected> -- Choose -- </option>');
                    if (response.data.role == 'Approver1') {
                        // append role
                        $('#role-edit').append('<option value="Approver1"> Approver Lv.1 (Manager) </option>');
                    } else {
                        // append role
                        $('#role-edit').append('<option value="Approver2"> Approver Lv.2 (Sub Dept Head) </option>');
                        $('#role-edit').append('<option value="User"> User </option>');
                        $('#role-edit').append('<option value="Technician"> Technician </option>');
                    }
                    $(`#role-edit option[value=${response.data.role}]`).attr('selected', 'selected').change();
                    $('#email-edit').val(response.data.email.split('@')[0]);
                    $('#username-edit').val(response.data.username);
                }
            });

            // clear form
            $('#form-edit-user').trigger('reset');

            // show modal
            $('#modal-edit').modal('show');
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

                    // clear alert
                    $('input').removeClass('is-invalid');
                    $('select').removeClass('is-invalid');
                    $('.invalid-feedback').removeClass('d-block');
                    $('.invalid-feedback').removeClass('d-none');

                    // close modal
                    $('#modal-edit').modal('hide');

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
                text: 'This employee will not be able to access this system.',
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