@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title text-bold">Edit {{ $employee->name }}'s Information</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form-update-employee" enctype="multipart/form-data" method="post">
                        @method('put')
                        @csrf
                        <div class="new-user info">
                            <input type="hidden" id="employee-id" name="id" value="{{ $employee->id }}">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6">
                                    <div class="form-group">
                                        <div class="profile-img-edit position relative mb-3">
                                            <img src="{{ asset('storage/uploads/photo-profile/'.$employee->image) }}" id="employee-image-preview" alt="profile-pic" class="theme-color-default-img profile-pic rounded avatar-100">
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
                                        <input type="text" class="form-control" id="employee-nik" name="nik" placeholder="Employee's nik" value="{{ $employee->nik }}" readonly>
                                        <div class="invalid-feedback d-none" role="alert" id="alert-employee-nik"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="employee-name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="employee-name" name="name" placeholder="Employee's name" value="{{ $employee->name }}">
                                        <div class="invalid-feedback d-none" role="alert" id="alert-employee-name"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="employee-position" class="form-label">Position</label>
                                        <input type="text" class="form-control" id="position" name="position" value="{{ $employee->position }}" readonly>
                                    <div class="invalid-feedback d-none" role="alert" id="alert-employee-position"></div>
                                </div>
                                </div>
                            </div>
                            <button type="subtmit" class="btn btn-primary mt-3" id="update-employee">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            // define varibale
            let subdept_id = "{{ $employee->sub_department_id }}";

            // preview image
            $('#employee-image').change(function(){
                let input = $(this);
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#employee-image-preview').attr("src", e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });


            // update button event
            $('#form-update-employee').on('submit', function(e){
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

                // define form variable
                let formData = new FormData(this);
                let nik      = formData.get('nik');
                let url      = "{{route('subdept.employees.update', ":nik")}}";
                url          = url.replace(':nik', nik);

                // ajax update
                $.ajax({
                    url: url,
                    type: "post",
                    cache: false,
                    data: formData, 
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
                        // show message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check again',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // check if photo profile field error
                        if (error.responseJSON.image) {
                            $('#employee-image').addClass('is-invalid');
                            $('#alert-employee-image').removeClass('d-none');
                            $('#alert-employee-image').addClass('d-block');

                            // show message
                            $('#alert-employee-image').html(error.responseJSON.image);
                        } else {
                            $('#employee-image').removeClass('is-invalid');
                            $('#alert-employee-image').removeClass('d-block');
                            $('#alert-employee-image').addClass('d-none');
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
                        if (error.responseJSON.position) {
                            // show alert
                            $('#employee-position').addClass('is-invalid');
                            $('#alert-employee-position').removeClass('d-none');
                            $('#alert-employee-position').addClass('d-block');

                            // show message
                            $('#alert-employee-position').html(error.responseJSON.position);
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