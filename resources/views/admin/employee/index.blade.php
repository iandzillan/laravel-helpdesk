@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title text-bold">New Manager Information</h4>
                    </div>
                    <a href="{{ route('admin.managers.list') }}" class="btn btn-primary">
                        <i class="fa-solid fa-table-list"></i> List Managers
                    </a>
                </div>
                <div class="card-body">
                    <form id="form-create-manager" enctype="multipart/form-data">
                        @csrf
                        <div class="new-user info">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6">
                                    <div class="form-group">
                                        <div class="profile-img-edit position relative mb-3">
                                            <img src="{{ asset('assets/images/avatars/avtar_1.png') }}" id="manager-image-preview" alt="profile-pic" class="theme-color-default-img profile-pic rounded avatar-100">
                                        </div>
                                        <input type="file" class="form-control" id="manager-image" name="image">
                                        <div class="invalid-feedback d-none" role="alert" id="alert-manager-image"></div>
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
                                        <label for="manager-nik" class="form-label">NIK</label>
                                        <input type="text" class="form-control" id="manager-nik" name="nik" placeholder="Manager's nik">
                                        <div class="invalid-feedback d-none" role="alert" id="alert-manager-nik"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="manager-name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="manager-name" name="name" placeholder="Manager's name">
                                        <div class="invalid-feedback d-none" role="alert" id="alert-manager-name"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-felx justify-content-end">
                                <div class="col-xl-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="manager-department" class="form-label">Department</label>
                                        <select id="manager-department" name="department_id" class="form-control form-select2"></select>
                                        <div class="invalid-feedback d-none" role="alert" id="alert-manager-department"></div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6">
                                    <div class="form-group">
                                        <label for="manager-position" class="form-label">Position</label>
                                        <input type="text" class="form-control" id="manager-position" name="position" placeholder="Manager's position" readonly value="Manager">
                                        <div class="invalid-feedback d-none" role="alert" id="alert-manager-position"></div>
                                    </div>
                                </div>
                            </div>
                            <button type="subtmit" class="btn btn-primary mt-3" id="store">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // ajax get all dept
            $.ajax({
                url: "{{ route('admin.managers.depts') }}",
                type: "get",
                cache: false,
                success:function(response){
                    $('#manager-department').empty();
                    $('#manager-department').append('<option disabled selected> -- Choose -- </option>');
                    $.each(response, function(code, dept){
                        $('#manager-department').append(`<option value="${dept.id}">${dept.name}</option>`);
                    });
                },
                error:function(error){
                    console.log(error.responseJSON.message);
                }
            });

            // when dept change
            // $('#manager-department').change(function(){
            //     $('#manager-position').val('');
            //     $('#manager-position').val('Head of ' + $('#manager-department').find('option:selected').text());
            // });

            // preview image
            $('#manager-image').change(function(){
                let input = $(this);
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#manager-image-preview').attr("src", e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            // store button action 
            $('#form-create-manager').on('submit', function(e){
                e.preventDefault();

                // define variabel
                let formData = new FormData(this);

                // show loading
                swal.fire({
                    title: 'Please wait',
                    text: 'Sending request...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEnterKey: false,
                    allowEscapeKey: false, 
                    didOpen: () => {
                        swal.showLoading();
                    }
                });

                // ajax store
                $.ajax({
                    url: "{{ route('admin.managers.store') }}",
                    type: "post",
                    cache: false,
                    data: formData,
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    success:function(response){
                        // show message
                        swal.fire({
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
                        swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check again',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // check if image has error
                        if (error.responseJSON.image) {
                            // show alert and message
                            $('#manager-image').addClass('is-invalid');
                            $('#alert-manager-image').addClass('d-block');
                            $('#alert-manager-image').removeClass('d-none');
                            $('#alert-manager-image').html(error.responseJSON.image);
                        } else {
                            // remove alert and message
                            $('#manager-image').removeClass('is-invalid');
                            $('#alert-manager-image').removeClass('d-block');
                            $('#alert-manager-image').addClass('d-none');
                        }

                        // check if nik has error
                        if (error.responseJSON.nik) {
                            // show alert and message
                            $('#manager-nik').addClass('is-invalid');
                            $('#alert-manager-nik').addClass('d-block');
                            $('#alert-manager-nik').removeClass('d-none');
                            $('#alert-manager-nik').html(error.responseJSON.nik);
                        } else {
                            // remove alert and message
                            $('#manager-nik').removeClass('is-invalid');
                            $('#alert-manager-nik').removeClass('d-block');
                            $('#alert-manager-nik').addClass('d-none');
                        }

                        // check if image has error
                        if (error.responseJSON.name) {
                            // show alert and message
                            $('#manager-name').addClass('is-invalid');
                            $('#alert-manager-name').addClass('d-block');
                            $('#alert-manager-name').removeClass('d-none');
                            $('#alert-manager-name').html(error.responseJSON.name);
                        } else {
                            // remove alert and message
                            $('#manager-name').removeClass('is-invalid');
                            $('#alert-manager-name').removeClass('d-block');
                            $('#alert-manager-name').addClass('d-none');
                        }

                        // check if image has error
                        if (error.responseJSON.position) {
                            // show alert and message
                            $('#manager-position').addClass('is-invalid');
                            $('#alert-manager-position').addClass('d-block');
                            $('#alert-manager-position').removeClass('d-none');
                            $('#alert-manager-position').html(error.responseJSON.position);
                        } else {
                            // remove alert and message
                            $('#manager-position').removeClass('is-invalid');
                            $('#alert-manager-position').removeClass('d-block');
                            $('#alert-manager-position').addClass('d-none');
                        }

                        // check if image has error
                        if (error.responseJSON.department_id) {
                            // show alert and message
                            $('#manager-department').addClass('is-invalid');
                            $('#alert-manager-department').addClass('d-block');
                            $('#alert-manager-department').removeClass('d-none');
                            $('#alert-manager-department').html(error.responseJSON.department_id);
                        } else {
                            // remove alert and message
                            $('#manager-department').removeClass('is-invalid');
                            $('#alert-manager-department').removeClass('d-block');
                            $('#alert-manager-department').addClass('d-none');
                        }
                    }
                });
            });
        });
    </script>
@endsection