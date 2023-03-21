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
                                            <img src="{{ asset('assets/images/avatars/avtar_1.png') }}" id="employee-image-preview" alt="profile-pic" class="theme-color-default-img profile-pic rounded avatar-100">
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
                                    <div class="form-group">
                                        <label for="employee-position" class="form-label">Position</label>
                                        <select id="employee-position" name="position_id" class="form-control form-select2"></select>
                                        <div class="invalid-feedback d-none" role="alert" id="alert-employee-position"></div>
                                    </div>
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
            // get sub dept id employee
            let id = "{{ Auth::user()->userable->position->sub_department_id }}";

            // get position
            $.ajax({
                url: "{{ route('subdept.employees.positions') }}",
                type: "get",
                cache: false,
                data:{
                    'id': id
                },
                success:function(response){
                    if (response) {
                        // empty select option
                        $('#employee-position').empty();
                        $('#employee-position').append('<option disabled selected> -- Choose -- </option>');
                        // fill position select option
                        $.each(response, function(code, position){
                            $('#employee-position').append('<option value="'+position.id+'">'+position.name+'</option>');
                        });
                    } else {
                        $('#employee-position').empty();
                    }
                }
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

                // define variable form
                let formData = this;

                // ajax create
                $.ajax({
                    url: "{{ route('subdept.employees.store') }}",
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
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    },
                    error:function(error){
                        // show message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Please check again',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // check if photo profile field error
                        if (error.responseJSON.image) {
                            // show alert
                            $('#employee-image').addClass('is-invalid');
                            $('#alert-employee-image').removeClass('d-none');
                            $('#alert-employee-image').addClass('d-block');

                            // show message
                            $('#alert-employee-image').html(error.responseJSON.image);
                        } else {
                            // remove alert
                            $('#employee-image').removeClass('is-invalid');
                            $('#alert-employee-image').removeClass('d-block');
                            $('#alert-employee-image').addClass('d-none');
                        }

                        // check if nik field error
                        if (error.responseJSON.nik) {
                            // show alert
                            $('#employee-nik').addClass('is-invalid');
                            $('#alert-employee-nik').removeClass('d-none');
                            $('#alert-employee-nik').addClass('d-block');

                            // show message
                            $('#alert-employee-nik').html(error.responseJSON.nik);
                        } else {
                            // remove alert
                            $('#employee-nik').removeClass('is-invalid');
                            $('#alert-employee-nik').removeClass('d-block');
                            $('#alert-employee-nik').addClass('d-none');
                        }

                        // check if name field error
                        if (error.responseJSON.name) {
                            // show alert
                            $('#employee-name').addClass('is-invalid');
                            $('#alert-employee-name').removeClass('d-none');
                            $('#alert-employee-name').addClass('d-block');

                            // show message
                            $('#alert-employee-name').html(error.responseJSON.name);
                        } else {
                            // remove alert
                            $('#employee-name').removeClass('is-invalid');
                            $('#alert-employee-name').removeClass('d-block');
                            $('#alert-employee-name').addClass('d-none');
                        }

                        // check if position field error
                        if (error.responseJSON.position_id) {
                            // show alert
                            $('#employee-position').addClass('is-invalid');
                            $('#alert-employee-position').removeClass('d-none');
                            $('#alert-employee-position').addClass('d-block');

                            // show message
                            $('#alert-employee-position').html(error.responseJSON.position_id);
                        } else {
                            // remove alert
                            $('#employee-position').removeClass('is-invalid');
                            $('#alert-employee-position').removeClass('d-block');
                            $('#alert-employee-position').addClass('d-none');
                        }
                    }
                });
            });
        });
    </script>

@endsection