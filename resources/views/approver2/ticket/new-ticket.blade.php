@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title text-bold">Create New Ticket</h4>
                    </div>
                </div>

                <div class="card-body">
                    <form id="form-create-ticket" enctype="multipart/form-data">
                        @method('post')
                        @csrf
                        <div class="row">
                            <div class="col-xl-6 col-lg-6">
                                <div class="form-group">
                                    <label for="nik" class="form-label">NIK</label>
                                    <input type="text" class="form-control" id="nik" name="nik" value="{{ Auth::user()->employee->nik }}" readonly>
                                    <div class="invalid-feedback d-none" role="alert" id="alert-nik"></div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->employee->name }}" readonly>
                                    <div class="invalid-feedback d-none" role="alert" id="alert-name"></div>
                                </div>
                                <div class="form-group">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject">
                                    <div class="invalid-feedback d-none" role="alert" id="alert-subject"></div>
                                </div>
                                <div class="form-group">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select id="category_id" name="category_id" class="form-control form-select2"></select>
                                    <div class="invalid-feedback d-none" role="alert" id="alert-category"></div>
                                </div>
                                <div class="form-group">
                                    <label for="sub_category_id" class="form-label">Sub Category</label>
                                    <select id="sub_category_id" name="sub_category_id" class="form-control form-select2"></select>
                                    <div class="invalid-feedback d-none" role="alert" id="alert-sub-category"></div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="form-label">Describe the problem</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    <div class="invalid-feedback d-none" role="alert" id="alert-description"></div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="form-group">
                                    <label for="image" class="form-label">Attachment</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <div class="invalid-feedback d-none" role="alert" id="alert-image"></div>
                                    <div class="img-extension">
                                        <div class="d-inline-block align-items-center">
                                            Only
                                            <span class="text-primary"> .jpg .jpeg .png </span>
                                            allowed
                                        </div>
                                    </div>
                                </div>
                                <img src="{{ asset('assets/images/noimage_preview.jpg') }}" class="img-thumbnail rounded mb-3" alt="image-preview" id="image-preview">
                            </div>
                        </div>
                        <button type="subtmit" class="btn btn-primary mt-3" id="store-employee">Save</button>
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

            // get all category
            $.ajax({
                url: "{{ route('subdept.get.category') }}",
                type: "get",
                cache: false,
                success:function(response){
                    $('#category_id').empty();
                    $('#category_id').append('<option disabled selected> -- Choose -- </option>');
                    $('#sub_category_id').append('<option disabled selected> -- Choose -- </option>');
                    $.each(response, function(code, category){
                        $('#category_id').append(`<option value="${category.id}">${category.name}</option>`);
                    });
                }, 
                error:function(error){
                    console.log(error.responseJSON.message);
                }
            });

            // get all sub category when category change
            $('#category_id').change(function(){
                // define varible
                let category_id = $('#category_id').val();
                let url = "{{ route('subdept.get.subCategory', ":id") }}";
                url = url.replace(':id', category_id);

                // get sub category
                $.ajax({
                    url: url,
                    type: "get",
                    cache: false,
                    data: {'category': category_id},
                    success:function(response){
                        $('#sub_category_id').empty();
                        $('#sub_category_id').append('<option disabled selected> -- Choose -- </option>');
                        $.each(response, function(code, subcategory){
                            $('#sub_category_id').append(`<option value="${subcategory.id}">${subcategory.name}</option>`)
                        });
                    }
                });
            });

            // form submit action
            $('#form-create-ticket').on('submit', function(e){
                e.preventDefault();

                // define variable
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
                    url: "{{ route('subdept.ticket.store') }}",
                    type: "post",
                    cache: false,
                    data: formData,
                    processData: false,
                    dataType: "json",
                    contentType: false,
                    success:function(response){
                        // show message
                        swal.fire({
                            icon: "success",
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
                        console.log(error.responseJSON.message);
                        // show message
                        swal.fire({
                            icon: "warning",
                            title: "Something wrong",
                            text: "Please check again",
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // check if subject has error
                        if (error.responseJSON.subject) {
                            // show alert & message
                            $('#subject').addClass('is-invalid');
                            $('#alert-subject').addClass('d-block');
                            $('#alert-subject').removeClass('d-none');
                            $('#alert-subject').html(error.responseJSON.subject);
                        } else {
                            $('#subject').removeClass('is-invalid');
                            $('#alert-subject').removeClass('d-block');
                            $('#alert-subject').addClass('d-none');
                        }

                        // check if category has error
                        if (error.responseJSON.category_id) {
                            // show alert & message
                            $('#category_id').addClass('is-invalid');
                            $('#alert-category').addClass('d-block');
                            $('#alert-category').removeClass('d-none');
                            $('#alert-category').html(error.responseJSON.category_id);
                        } else {
                            $('#category').removeClass('is-invalid');
                            $('#alert-category_id').removeClass('d-block');
                            $('#alert-category_id').addClass('d-none');
                        }

                        // check if sub category has error
                        if (error.responseJSON.sub_category_id) {
                            // show alert & message
                            $('#sub_category_id').addClass('is-invalid');
                            $('#alert-sub-category').addClass('d-block');
                            $('#alert-sub-category').removeClass('d-none');
                            $('#alert-sub-category').html(error.responseJSON.sub_category_id);
                        } else {
                            $('#sub_category_id').removeClass('is-invalid');
                            $('#alert-sub-category').removeClass('d-block');
                            $('#alert-sub-category').addClass('d-none');
                        }

                        // check if description has error
                        if (error.responseJSON.description) {
                            // show alert & message
                            $('#description').addClass('is-invalid');
                            $('#alert-description').addClass('d-block');
                            $('#alert-description').removeClass('d-none');
                            $('#alert-description').html(error.responseJSON.description);
                        } else {
                            $('#description').removeClass('is-invalid');
                            $('#alert-description').removeClass('d-block');
                            $('#alert-description').addClass('d-none');
                        }

                        // check if image has error
                        if (error.responseJSON.image) {
                            // show alert & message
                            $('#image').addClass('is-invalid');
                            $('#alert-image').addClass('d-block');
                            $('#alert-image').removeClass('d-none');
                            $('#alert-image').html(error.responseJSON.image);
                        } else {
                            $('#image').removeClass('is-invalid');
                            $('#alert-image').removeClass('d-block');
                            $('#alert-image').addClass('d-none');
                        }
                    }
                });
            });
        });
    </script>
@endsection