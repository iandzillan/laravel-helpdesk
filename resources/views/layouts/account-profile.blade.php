@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title text-bold">Account Profile</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form id="update-profile" enctype="multipart/form-data">
                        @method('patch')
                        @csrf
                        <div class="card mb-3">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="{{ asset('storage/uploads/photo-profile/'.$employee->image) }}" id="image-preview" alt="profile-pic" class="img-fluid rounded-start">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body" style="max-height: 369px; overflow-y: auto">
                                        <div class="form-group">
                                            <label for="image" class="form-label">Photo Profile</label>
                                            <input type="file" class="form-control" id="image" name="image">
                                            <div class="invalid-feedback d-none" role="alert" id="alert-image"></div>
                                            <div class="img-extension mt-3">
                                                <div class="d-inline-block align-items-center text-sm">
                                                    Only
                                                    <span class="text-primary"> .jpg .jpeg .png </span>
                                                    allowed
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="nik" class="form-label">NIK</label>
                                            <input type="text" class="form-control" id="nik" name="nik" value="{{ $employee->nik }}" readonly>
                                            <div class="invalid-feedback d-none" role="alert" id="alert-nik"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $employee->name }}">
                                            <div class="invalid-feedback d-none" role="alert" id="alert-name"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="old-passowrd" class="form-label">Old Password</label>
                                            <input type="password" class="form-control" id="old-password" name="oldpassword">
                                            <div class="invalid-feedback d-none" role="alert" id="alert-old-password"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="new-password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="new-password" name="password">
                                            <div class="invalid-feedback d-none" role="alert" id="alert-new-password"></div>
                                        </div>

                                        <div class="form-group">
                                            <label for="confirm" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirm" name="password_confirmation">
                                            <div class="invalid-feedback d-none" role="alert" id="alert-confirm"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="subtmit" class="btn btn-primary mt-3" id="update">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // preview image
            $('#image').change(function(){
                let input = $(this);
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#image-preview').attr("src", e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            // form update submit action
            $('#update-profile').on('submit', function(e){
                e.preventDefault();

                // define variable
                let formData = new FormData(this);
                let nik      = formData.get('nik');
                let url      = "{{ route('profile.update', ":nik") }}";
                url          = url.replace(':nik', nik);

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

                // ajax update
                $.ajax({
                    url: url, 
                    type: "post",
                    cache: false,
                    data: formData,
                    processData:false,
                    dataType:"json",
                    contentType:false,
                    success:function(response){
                        if (response.success == true) {
                            // show message
                            swal.fire({
                                icon: 'success',
                                title: `${response.message}`,
                                showConfirmButton: false,
                                timer: 2000
                            });
    
                            // reload page
                            setTimeout(() => {
                                location.reload()
                            }, 2000);
                        } else {
                            // show message
                            swal.fire({
                                icon: 'error',
                                title: `${response.message}`,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
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
                            $('#image').addClass('is-invalid');
                            $('#alert-image').addClass('d-block');
                            $('#alert-image').removeClass('d-none');
                            $('#alert-image').html(error.responseJSON.image);
                        } else {
                            // remove alert and message
                            $('#image').removeClass('is-invalid');
                            $('#alert-image').removeClass('d-block');
                            $('#alert-image').addClass('d-none');
                        }

                        // check if nik has error
                        if (error.responseJSON.nik) {
                            // show alert and message
                            $('#nik').addClass('is-invalid');
                            $('#alert-nik').addClass('d-block');
                            $('#alert-nik').removeClass('d-none');
                            $('#alert-nik').html(error.responseJSON.nik);
                        } else {
                            // remove alert and message
                            $('#nik').removeClass('is-invalid');
                            $('#alert-nik').removeClass('d-block');
                            $('#alert-nik').addClass('d-none');
                        }

                        // check if image has error
                        if (error.responseJSON.name) {
                            // show alert and message
                            $('#name').addClass('is-invalid');
                            $('#alert-name').addClass('d-block');
                            $('#alert-name').removeClass('d-none');
                            $('#alert-name').html(error.responseJSON.name);
                        } else {
                            // remove alert and message
                            $('#name').removeClass('is-invalid');
                            $('#alert-name').removeClass('d-block');
                            $('#alert-name').addClass('d-none');
                        }

                        // check if old password has error
                        if (error.responseJSON.oldpassword) {
                            // show alert and message
                            $('#old-password').addClass('is-invalid');
                            $('#alert-old-password').addClass('d-block');
                            $('#alert-old-password').removeClass('d-none');
                            $('#alert-old-password').html(error.responseJSON.oldpassword);
                        } else {
                            // remove alert and message
                            $('#old-password').removeClass('is-invalid');
                            $('#alert-old-password').removeClass('d-block');
                            $('#alert-old-password').addClass('d-none');
                        }

                        // check if new password has error
                        if (error.responseJSON.password) {
                            // show alert and message
                            $('#new-password').addClass('is-invalid');
                            $('#alert-new-password').addClass('d-block');
                            $('#alert-new-password').removeClass('d-none');
                            $('#alert-new-password').html(error.responseJSON.password);
                        } else {
                            // remove alert and message
                            $('#new-password').removeClass('is-invalid');
                            $('#alert-new-password').removeClass('d-block');
                            $('#alert-new-password').addClass('d-none');
                        }

                        // check if confirm password has error
                        if (error.responseJSON.password_confirmation) {
                            // show alert and message
                            $('#confirm').addClass('is-invalid');
                            $('#alert-confirm').addClass('d-block');
                            $('#alert-confirm').removeClass('d-none');
                            $('#alert-confirm').html(error.responseJSON.password_confirmation);
                        } else {
                            // remove alert and message
                            $('#confirm').removeClass('is-invalid');
                            $('#alert-confirm').removeClass('d-block');
                            $('#alert-confirm').addClass('d-none');
                        }
                    }
                });
            });
        });
    </script>
@endsection