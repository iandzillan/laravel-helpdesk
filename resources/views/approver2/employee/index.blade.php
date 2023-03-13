@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title text-bold">New Employee Information</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form-create-employee" enctype="multipart/form-data">
                        @csrf
                        <div class="new-user info">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6">
                                    <div class="form-group">
                                        <div class="profile-img-edit position relative mb-3">
                                            <img src="{{ asset('storage/uploads/photo-profile/avtar_1.png') }}" id="employee-image-preview" alt="profile-pic" class="theme-color-default-img profile-pic rounded avatar-100">
                                        </div>
                                        <input type="file" class="form-control" id="employee-image" name="image">
                                        <div class="invalid-feedback d-none" role="alert" id="alert-employee-image"></div>
                                        <div class="img-extension mt-3">
                                            <div class="d-inline-block align-items-center">
                                                Only
                                                <span class="text-primary"> .jpg .jpeg .png </span>
                                                allowed
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="employee-nik" class="form-label">NIK</label>
                                        <input type="text" class="form-control" id="employee-nik" name="nik" placeholder="Employee's nik">
                                        <div class="invalid-feedback d-none" role="alert" id="alert-employee-nik"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="employee-name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="employee-name" name="name" placeholder="Employee's name">
                                        <div class="invalid-feedback d-none" role="alert" id="alert-employee-name"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="employee-dept" class="form-label">Department</label>
                                    <select id="employee-dept" name="dept" class="selectpicker form-control basic-usage"></select>
                                    <div class="invalid-feedback d-none" role="alert" id="alert-employee-dept"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="employee-subdept" class="form-label">Sub Department</label>
                                    <select id="employee-subdept" name="subdept" class="selectpicker form-control basic-usage"></select>
                                    <div class="invalid-feedback d-none" role="alert" id="alert-employee-subdept"></div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="employee-position" class="form-label">Position</label>
                                    <select id="employee-position" name="position_id" class="selectpicker form-control basic-usage"></select>
                                    <div class="invalid-feedback d-none" role="alert" id="alert-employee-position"></div>
                                </div>
                            </div>
                            <button type="subtmit" class="btn btn-primary mt-3" id="store-employee">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // get all dept data
            $.ajax({
                url: "{{ route('approver.employees.depts') }}",
                type: "get",
                cache: false,
                success:function(response){
                    if (response) {
                        // fill dept select option
                        $('#employee-dept').empty();
                        $('#employee-dept').append('<option selected> -- Choose -- </option>');
                        $('#employee-subdept').append('<option selected> -- Choose -- </option>');
                        $('#employee-position').append('<option selected> -- Choose -- </option>');
                        $.each(response, function(code, dept){
                            $('#employee-dept').append('<option value="'+dept.id+'">'+dept.name+'</option>');
                        });
                    } else {
                        $('#employee-dept').empty();
                    }
                }
            });

            // get subdepts data when dept change
            $('#employee-dept').change(function(){
                // empty subdept and position select option
                $('#employee-subdept').empty();
                $('#employee-subdept').append('<option selected> -- Choose -- </option>');
                $('#employee-position').empty();
                $('#employee-position').append('<option selected> -- Choose -- </option>');

                // get dept id
                let dept_id = $('#employee-dept').val();

                // fetch subdepts data based on dept_id
                $.ajax({
                    url: "{{ route('approver.employees.subdepts') }}",
                    type: "get",
                    cache: false,
                    data: {
                        'id': dept_id
                    },
                    success:function(response){
                        if (response) {
                            // fill subdept select option
                            $.each(response, function(code, subdept){
                                $('#employee-subdept').append('<option value="'+subdept.id+'">'+subdept.name+'</option>');
                            });
                        } else {
                            $('#employee-subdept').empty();
                            $('#employee-position').empty();
                        }
                    }
                });
            });

            // get positions data when subdept change
            $('#employee-subdept').change(function(){
                // empty position select option
                $('#employee-position').empty();
                $('#employee-position').append('<option selected> -- Choose -- </option>');

                // get subdept id
                let subdept_id = $('#employee-subdept').val();

                // fetch positions data when subdept change
                $.ajax({
                    url: "{{ route('approver.employees.positions') }}",
                    type: "get",
                    cache: false,
                    data: {
                        'id': subdept_id
                    },
                    success:function(response){
                        if (response) {
                            // fill position select option
                            $.each(response, function(code, position){
                                $('#employee-position').append('<option value="'+position.id+'">'+position.name+'</option>');
                            });
                        } else {
                            $('#employee-position').empty();
                        }
                    }
                });
            });

            // preview image
            $('#employee-image').change(function(){
                let input = $(this);
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#employee-image-preview').attr("src", e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            // store button event
            $('#form-create-employee').on('submit', function(e){
                e.preventDefault();

                // define variable form
                let formData = this;

                // ajax create
                $.ajax({
                    url: "{{ route('approver.employees.store') }}",
                    type: "post",
                    cache: false,
                    data: new FormData(formData), 
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    success:function(response){
                        // show message
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // reload page
                        setTimeout(function(){
                            location.reload();
                        }, 2000);
                    },
                    error:function(error){
                        // check if photo profile field error
                        if (error.responseJSON.image) {
                            // show alert
                            $('#employee-image').addClass('is-invalid');
                            $('#alert-employee-image').removeClass('d-none');
                            $('#alert-employee-image').addClass('d-block');

                            // show message
                            $('#alert-employee-image').html(error.responseJSON.image);
                        }

                        // check if nik field error
                        if (error.responseJSON.nik) {
                            // show alert
                            $('#employee-nik').addClass('is-invalid');
                            $('#alert-employee-nik').removeClass('d-none');
                            $('#alert-employee-nik').addClass('d-block');

                            // show message
                            $('#alert-employee-nik').html(error.responseJSON.nik);
                        }

                        // check if name field error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#employee-name').addClass('is-invalid');
                            $('#alert-employee-name').removeClass('d-none');
                            $('#alert-employee-name').addClass('d-block');

                            // show message
                            $('#alert-employee-name').html(error.responseJSON.name);
                        }

                        // check if dept field error
                        if (error.responseJSON.dept) {
                            // show alert
                            $('#employee-dept').addClass('is-invalid');
                            $('#alert-employee-dept').removeClass('d-none');
                            $('#alert-employee-dept').addClass('d-block');

                            // show message
                            $('#alert-employee-dept').html(error.responseJSON.dept);
                        }

                        // check if subdept field error
                        if (error.responseJSON.subdept) {
                            // show alert
                            $('#employee-subdept').addClass('is-invalid');
                            $('#alert-employee-subdept').removeClass('d-none');
                            $('#alert-employee-subdept').addClass('d-block');

                            // show message
                            $('#alert-employee-subdept').html(error.responseJSON.subdept);
                        }

                        // check if position field error
                        if (error.responseJSON.position_id) {
                            // show alert
                            $('#employee-position').addClass('is-invalid');
                            $('#alert-employee-position').removeClass('d-none');
                            $('#alert-employee-position').addClass('d-block');

                            // show message
                            $('#alert-employee-position').html(error.responseJSON.position_id);
                        }
                    }
                });
            });
        });
    </script>

@endsection