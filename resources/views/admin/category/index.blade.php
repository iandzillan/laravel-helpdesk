@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Categories</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-category">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add Category
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
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

    @include('admin.category.modal-create')
    @include('admin.category.modal-edit')
    
    <script>
        $(function(){  
            
            // Draw table
            let table = $('.data-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.categories') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // button create category event
            $('body').on('click', '#btn-create-category', function(){
                // show modal
                $('#modal-create-category').modal('show');
            });

            // action create category
            $('#store-category').click(function(e){
                e.preventDefault();

                // define variable
                let name    = $('#category-name').val();
                let token   = $('meta[name="csrf-token"]').attr('content');

                // ajax
                $.ajax({
                    url : "{{route('admin.categories.store')}}",
                    type: "post", 
                    cache: false,
                    data: {
                        "name": name,
                        "_token": token
                    },

                    success:function(response){
                        // show success message
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton:false,
                            timer:3000
                        });
                        
                        // clear form
                        $("#form-create-category")[0].reset();
                        
                        // close modal
                        $('#modal-create-category').modal('hide');
                        
                        // draw table
                        table.draw();
                    },

                    error:function(error){
                        if(error.responseJSON.name){
                            // show alert
                            $('#alert-category-name').removeClass('d-none');
                            $('#alert-category-name').addClass('d-block');
                            $('#category-name').addClass('is-invalid');

                            // add message to alert
                            $('#alert-category-name').html(error.responseJSON.name);
                        }
                    }
                });
            });

            // button edit category event
            $('body').on('click', '#btn-edit-category', function(){
                // define variable 
                let category_id = $(this).data('id');

                // fetch detail category to modal
                $.ajax({
                    url: `categories/${category_id}/edit`,
                    type: "get",
                    cache: false,
                    success:function(response){
                        // fill form
                        $('#category-id').val(response.data.id);
                        $('#category-name-edit').val(response.data.name);
                    }
                });

                // show modal
                $('#modal-edit-category').modal('show');
            });

            // action update category button
            $('#update-category').click(function(e){
                e.preventDefault();

                // define variable
                let category_id     = $('#category-id').val();
                let category_name   = $('#category-name-edit').val();
                let token           = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: `categories/${category_id}`,
                    type: "patch",
                    cache: false,
                    data:{
                        "name": category_name,
                        "_token": token
                    }, 
                    success:function(response){
                        // show success message
                        Swal.fire({
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 3000
                        });

                        // draw table
                        table.draw();
                        
                        // clear alert
                        $('#alert-category-name-edit').removeClass('d-block');
                        $('#alert-category-name-edit').addClass('d-none');
                        $('#category-name-edit').removeClass('is-invalid');

                        // close modal
                        $('#modal-edit-category').modal('hide');
                    }, 
                    error:function(error){
                        if(error.responseJSON.name){
                            // show alert
                            $('#alert-category-name-edit').removeClass('d-none');
                            $('#alert-category-name-edit').addClass('d-block');
                            $('#category-name-edit').addClass('is-invalid');

                            // add message to alert
                            $('#alert-category-name-edit').html(error.responseJSON.name);
                        }
                    }
                });
            });

            // action destroy category
            $('body').on('click', '#btn-delete-category', function(){
                let category_id = $(this).data('id');
                let token       = $('meta[name="csrf-token"]').attr('content');

                // Confirmation 
                Swal.fire({
                    title: "Are you sure?",
                    text: "This category will be deleted",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ajax delete
                        $.ajax({
                            url: `categories/${category_id}`,
                            type: "delete",
                            cache: false,
                            data:{
                                "_token" : token
                            }, 
                            success:function(response){
                                // show success message
                                Swal.fire({
                                    icon: 'success',
                                    title: `${response.message}`,
                                    showConfirmButton: false,
                                    timer: 3000
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