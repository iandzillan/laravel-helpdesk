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
                                    <select id="employee-position" name="position_id" class="selectpicker form-control basic-usage"></select>
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
            let position_id = "{{ $employee->position_id }}";

            // get all position
            $.ajax({
                url: "{{ route('subdept.employees.positions') }}",
                type: 'get',
                cache: false,
                success:function(response){
                    if (response) {
                        // fill dept select option
                        $('#employee-position').append('<option selected> -- Choose -- </option>');
                        $.each(response, function(code, position){
                            $('#employee-position').append('<option value="'+position.id+'">'+position.name+'</option>');
                            $('#employee-position option[value='+position_id+']').attr('selected', 'selected');
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
                let formData = this;

                // ajax update
                $.ajax({
                    url: "{{ route('subdept.employees.update') }}",
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
                            $('#employee-image').addClass('is-invalid');
                            $('#alert-employee-image').removeClass('d-none');
                            $('#alert-employee-image').addClass('d-block');

                            // show message
                            $('#alert-employee-image').html(error.responseJSON.image);
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