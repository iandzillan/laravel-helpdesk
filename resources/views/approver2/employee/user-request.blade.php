@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">User Account Request</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIK</th>
                                <th>Name</th>
                                <th>Position</th>
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

    @include('approver2.employee.modal-user-request')

    <script>
        $(document).ready(function(){
            // draw table
            let table = $('.data-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('subdept.userrequestlist') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'nik', name: 'nik'},
                    {data: 'name', name: 'name'},
                    {data: 'position', name: 'position'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // request user account button event
            $('body').on('click', '#user-request-button', function(){
                // define variable
                let nik = $(this).data('id');

                // clear form
                $('#form-user-request').trigger('reset');

                // get employee data
                $.ajax({
                    url: `user-request/${nik}`,
                    type: 'get',
                    cache: false,
                    success:function(response){
                        // fill nik and name
                        $('#nik').val(response.data.nik);
                        $('#name').val(response.data.name);
                    }
                });

                // open modal user request form
                $('#modal-user-request').modal('show');
            });

            // request button event
            $('#request').click(function(e){
                e.preventDefault();

                // show loader
                // $('#myloader').show();
                Swal.fire({
                    title: "Please wait",
                    text: "Sending request...",
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowEnterKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // define variable
                let nik = $('#nik').val();
                let name = $('#name').val();
                let email = $('#email').val() + '@example.com';
                let username = $('#username').val();
                let password = $('#password').val();
                let confirm = $('#confirm').val();
                let role = $('#role').val();
                let token = $('meta[name="csrf-token"]').attr('content');

                // ajax update isRequest
                $.ajax({
                    url: "{{ route('subdept.isRequest') }}",
                    type: 'patch',
                    cache: false,
                    data: {
                        'nik': nik,
                        'name': name,
                        'email': email,
                        'username': username,
                        'password': password,
                        'password_confirmation': confirm,
                        'role': role,
                        '_token': token
                    },
                    success:function(response){
                        // ajax send email
                        $.ajax({
                            url: "{{route('subdept.sendRequest')}}",
                            type: "get",
                            cache: false,
                            data:{
                                'nik': nik,
                                'name': name,
                                'email': email,
                                'username': username,
                                'password': password,
                                'password_confirmation': confirm,
                                'role': role,
                            },
                            success:function(response1){
                                console.log(response1.message);

                                // hide loader
                                // $('#myloader').hide();

                                // show message
                                Swal.fire({
                                    icon: 'success',
                                    title: `${response1.message}`,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            },
                            error:function(error1){
                                console.log(error1.responseJSON.message);
                                // hide loader
                                // $('#myloader').hide();

                                // show message
                                Swal.fire({
                                    icon: 'warning',
                                    title: "Can't send user account email request",
                                    text: `${error1.responseJSON.message}`,
                                    showConfirmButton: false,
                                });
                            }
                        });

                        // close modal
                        $('#modal-user-request').modal('hide');

                        // draw table
                        table.draw();
                    }, 
                    error:function(error){
                        // hide loader
                        // $('#myloader').hide();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Please check again', 
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // check if email field has error
                        if (error.responseJSON.email) {
                            // show alert
                            $('#email').addClass('is-invalid');
                            $('#alert-email').removeClass('d-none');
                            $('#alert-email').addClass('d-block');

                            // show message
                            $('#alert-email').html(error.responseJSON.email);
                        }

                        // check if username field has error
                        if (error.responseJSON.username) {
                            // show alert
                            $('#username').addClass('is-invalid');
                            $('#alert-username').removeClass('d-none');
                            $('#alert-username').addClass('d-block');

                            // show message
                            $('#alert-username').html(error.responseJSON.username);
                        }

                        // check if password field has error
                        if (error.responseJSON.password) {
                            // show alert
                            $('#password').addClass('is-invalid');
                            $('#alert-password').removeClass('d-none');
                            $('#alert-password').addClass('d-block');

                            // show message
                            $('#alert-password').html(error.responseJSON.password);
                        }

                        // check if role field has error
                        if (error.responseJSON.role) {
                            // show alert
                            $('#role').addClass('is-invalid');
                            $('#alert-role').removeClass('d-none');
                            $('#alert-role').addClass('d-block');

                            // show message
                            $('#alert-role').html(error.responseJSON.role);
                        }
                    }
                });
            });
        });
    </script>

@endsection