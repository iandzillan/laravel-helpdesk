@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Sub Categories</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-subcategory">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add Sub Category
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Technician</th>
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

    @include('admin.subcategory.modal-create')
    @include('admin.subcategory.modal-edit')

    <script>
        $('body').ready(function(){

            // draw table
            let table = $('.data-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.subcategories') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'category', name: 'category'},
                    {data: 'technician', name: 'technician'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // add sub category button event
            $('body').on('click', '#btn-create-subcategory', function(){
                // fetch category to select option
                $.ajax({
                    url:"{{ route('admin.subcategories.categories') }}",
                    type:"get",
                    cache:false,
                    success:function(response){
                        if(response){
                            // fill select options with categories
                            $('#category-id').empty();
                            $('#category-id').append(`
                                <option disabled selected> -- Choose -- </option>
                            `);
                            $.each(response, function(code, category){
                                $('#category-id').append('<option value="'+category.id+'">'+category.name+'</option>'); 
                            });
                        } else {
                            $('#category-id').empty();
                        }
                    }
                });

                // fetch technician to select option
                $.ajax({
                    url: "{{ route('admin.subcategories.technicians') }}",
                    type: "get",
                    cache: false,
                    success:function(response){
                        if (response) {
                            $('#technician-id').empty();
                            $('#technician-id').append(`
                                <option disabled selected> -- Choose -- </option>
                            `);
                            $.each(response, function(code, technician){
                                $('#technician-id').append('<option value="'+technician.id+'">'+technician.name+'</option>'); 
                            });
                        } else {
                            $('#technician-id').empty();
                        }
                    }
                });

                // show modal
                $('#modal-create').modal('show');
            });

            // store sub category button event
            $('#store-subcategory').click(function(e){
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
                let name          = $('#name').val();
                let category_id   = $('#category-id').val();
                let technician_id = $('#technician-id').val();
                let token         = $('meta[name="csrf-token"]').attr('content');

                // ajax
                $.ajax({
                    url: "{{ route('admin.subcategories.store') }}",
                    type: "post",
                    cache: false,
                    data: {
                        "name": name,
                        "category_id": category_id,
                        "technician_id": technician_id,
                        "_token": token
                    },
                    success:function(response){
                        // show success message
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // clear form
                        $('#form-create-subcategory').trigger('reset');

                        // clear alert
                        $('input').removeClass('is-invalid');
                        $('select').removeClass('is-invalid');
                        $('.invalid-feedback').removeClass('d-block');

                        // close modal
                        $('#modal-create').modal('hide');

                        // draw table
                        table.draw();
                    },
                    error:function(error){
                        // show success message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check again',
                            showConfirmButton:false,
                            timer:1000
                        });

                        // check if sub category name error
                        if(error.responseJSON.name){
                            // show alert
                            $('#name').addClass('is-invalid');
                            $('#alert-name').removeClass('d-none');
                            $('#alert-name').addClass('d-block');

                            // add message to alert
                            $('#alert-name').html(error.responseJSON.name);
                        } else {
                            // remove alert
                            $('#name').removeClass('is-invalid');
                            $('#alert-name').addClass('d-none');
                            $('#alert-name').removeClass('d-block');
                        }

                        // check if category option error
                        if (error.responseJSON.category_id){
                            // show alert
                            $('#category-id').addClass('is-invalid');
                            $('#category-id').removeClass('d-none');
                            $('#category-id').addClass('d-block');

                            // add message to alert
                            $('#alert-categoryid').html(error.responseJSON.category_id);
                        } else {
                            // remove alert
                            $('#category-id').removeClass('is-invalid');
                            $('#category-id').addClass('d-none');
                            $('#category-id').removeClass('d-block');
                        }

                        // check if technician option error
                        if (error.responseJSON.technician_id){
                            // show alert
                            $('#technician-id').addClass('is-invalid');
                            $('#technician-id').removeClass('d-none');
                            $('#technician-id').addClass('d-block');

                            // add message to alert
                            $('#alert-technicianid').html(error.responseJSON.technician_id);
                        } else {
                            // remove alert
                            $('#technician-id').removeClass('is-invalid');
                            $('#technician-id').addClass('d-none');
                            $('#technician-id').removeClass('d-block');
                        }
                    }
                });
            });

            // edit sub category button event
            $('body').on('click', '#btn-edit-subcategory', function(){
                // define variable
                let id = $(this).data('id');

                // fetch detail sub category to modal
                $.ajax({
                    url: `sub-categories/${id}/edit`,
                    type: "get",
                    cache: false,
                    success:function(response){
                        if(response){
                            // fill form
                            $('#subcategory-categoryid-edit').empty();
                            $('#subcategory-categoryid-edit').append('<option disabled selected> -- Choose -- </option>');
                            $('#technician-id-edit').empty();
                            $('#technician-id-edit').append('<option disabled selected> -- Choose -- </option>');
                            $('#subcategory-id').val(response.data.id);
                            $('#subcategory-name-edit').val(response.data.name);
                            $.each(response.categories, function(code, category){
                                $('#subcategory-categoryid-edit').append('<option value="'+category.id+'">'+category.name+'</option>');
                                $(`#subcategory-categoryid-edit option[value=${response.data.category_id}]`).attr('selected', 'selected');
                            });
                            $.each(response.technicians, function(code, technician){
                                $('#technician-id-edit').append('<option value="'+technician.id+'">'+technician.name+'</option>');
                                $(`#technician-id-edit option[value=${response.technician.id}]`).attr('selected', 'selected');
                            });
                        } else {
                            $('#subcategory-categoryid-edit').empty();
                            $('#technician-id-edit').empty();
                        }
                    }
                });

                // show modal
                $('#modal-edit').modal('show');
            });

            // update sub category button event
            $('#update-subcategory').click(function(e){
                e.preventDefault();

                // define variable
                let id            = $('#subcategory-id').val();
                let name          = $('#subcategory-name-edit').val();
                let category_id   = $('#subcategory-categoryid-edit').val();
                let technician_id = $('#technician-id-edit').val();
                let token         = $('meta[name="csrf-token"]').attr('content');

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

                // ajax update
                $.ajax({
                    url: `sub-categories/${id}`,
                    type: "patch",
                    cache: false,
                    data:{
                        'name': name,
                        'category_id': category_id,
                        'technician_id': technician_id,
                        '_token': token
                    }, 
                    success:function(response){
                        // show message success
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 2000
                        });

                        // clear form
                        $('#form-edit-subcategory').trigger('reset');

                        // close modal
                        $('#modal-edit').modal('hide');

                        // draw table
                        table.draw();
                    },
                    error:function(error){
                        // show success message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Something wrong',
                            text: 'Please check again',
                            showConfirmButton:false,
                            timer:1000
                        });

                        // check if sub category name error
                        if(error.responseJSON.name){
                            // show alert
                            $('#subcategory-name-edit').addClass('is-invalid');
                            $('#alert-subcategory-name-edit').removeClass('d-none');
                            $('#alert-subcategory-name-edit').addClass('d-block');

                            // add message to alert
                            $('#alert-subcategory-name-edit').html(error.responseJSON.name);
                        } else {
                            // remove alert
                            $('#subcategory-name-edit').removeClass('is-invalid');
                            $('#alert-subcategory-name-edit').addClass('d-none');
                            $('#alert-subcategory-name-edit').removeClass('d-block');
                        }

                        // check if category option error
                        if (error.responseJSON.category_id){
                            // show alert
                            $('#subcategory-categoryid-edit').addClass('is-invalid');
                            $('#subcategory-categoryid-edit').removeClass('d-none');
                            $('#subcategory-categoryid-edit').addClass('d-block');

                            // add message to alert
                            $('#alert-subcategory-categoryid-edit').html(error.responeJSON.category_id);
                        } else {
                            // remove alert
                            $('#subcategory-categoryid-edit').removeClass('is-invalid');
                            $('#subcategory-categoryid-edit').addClass('d-none');
                            $('#subcategory-categoryid-edit').removeClass('d-block');
                        }

                        // check if technician option error
                        if (error.responseJSON.technician_id){
                            // show alert
                            $('#technician-id-edit').addClass('is-invalid');
                            $('#technician-id-edit').removeClass('d-none');
                            $('#technician-id-edit').addClass('d-block');

                            // add message to alert
                            $('#alert-technician-id-edit').html(error.responeJSON.technician_id);
                        } else {
                            // remove alert
                            $('#technician-id-edit').removeClass('is-invalid');
                            $('#technician-id-edit').addClass('d-none');
                            $('#technician-id-edit').removeClass('d-block');
                        }
                    }
                });
            })

            // delete sub category button event
            $('body').on('click', '#btn-delete-subcategory', function(){
                let subcategory_id  = $(this).data('id');
                let token           = $('meta[name="csrf-token"]').attr('content');

                // confirmation
                Swal.fire({
                    title: "Are you sure?",
                    text: "All tickets that related to this urgency will be deleted as well.",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    confirmButtonText: "Yes"
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
                            url: `sub-categories/${subcategory_id}`,
                            type: "delete",
                            cache: false,
                            data:{
                                "_token": token
                            },
                            success:function(response){
                                // show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: `${response.message}`,
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                                // draw table
                                table.draw();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection