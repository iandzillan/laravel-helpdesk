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
    {{-- @include('admin.subcategory.modal-edit') --}}

    <script>
        $(function(){

            // draw table
            var table = $('.data-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.subcategories') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'category', name: 'category'},
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
                            $('#subcategory-categoryid').empty();
                            $('#subcategory-categoryid').append(`
                                <option selected>-- Choose Category --</option>
                            `);
                            $.each(response, function(code, category){
                                $('#subcategory-categoryid').append('<option value="'+category.id+'">'+category.name+'</option>'); 
                            });
                        } else {
                            $('#subcategory-categoryid').empty();
                        }
                    }
                });
                // show modal
                $('#modal-create-subcategory').modal('show');
            });

            // store sub category button event
            $('#store-subcategory').click(function(e){
                e.preventDefault();

                // define variable
                let name        = $('#subcategory-name').val();
                let category_id = $('#subcategory-categoryid').val();
                let token       = $('meta[name="csrf-token"]').attr('content');

                // ajax
                $.ajax({
                    url: "{{ route('admin.subcategories.store') }}",
                    type: "post",
                    cache: false,
                    data: {
                        "name": name,
                        "category_id": category_id,
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

                        // clear form
                        $('#form-create-subcategory')[0].reset();

                        // close modal
                        $('#modal-create-subcategory').modal('hide');

                        // draw table
                        table.draw();
                    },
                    error:function(error){
                        // check if sub category name error
                        if(error.responseJSON.name){
                            // show alert
                            $('#subcategory-name').addClass('is-invalid');
                            $('#alert-subcategory-name').removeClass('d-none');
                            $('#alert-subcategory-name').addClass('d-block');

                            // add message to alert
                            $('#alert-subcategory-name').html(error.responseJSON.name);
                        }

                        // check if category option error
                        if (error.responseJSON.category_id){
                            // show alert
                            $('#subcategory-categoryid').addClass('is-invalid');
                            $('#subcategory-categoryid').removeClass('d-none');
                            $('#subcategory-categoryid').addClass('d-block');

                            // add message to alert
                            $('#alert-subcategory-categoryid').html(error.responeJSON.category_id);
                        }
                    }
                });
            });

            // edit sub category button event
            $('body').on('click', '#btn-edit-subcategory', function(){
                // define variable
                let subcategory_id = $(this).data('id');

                // fetch detail sub category to modal
                $.ajax({
                    url: `sub-categories/${subcategory_id}/edit`,
                    type: "get",
                    cache: false,
                    success:function(respone){
                        // fill form 
                        $('#subcategory-id').val(response.data.id);
                        $('#subcategory-name-edit').val(response.data.name);
                        $('#subcategory-categoryid').val(response.data.category_id);
                    }
                });

                // show modal
                $('#modal-edit-subcategory').modal('show');
            });

            // delete sub category button event
            $('body').on('click', '#btn-delete-subcategory', function(){
                let subcategory_id  = $(this).data('id');
                let token           = $('meta[name="csrf-token"]').attr('content');

                // confirmation
                Swal.fire({
                    title: "Are you sure?",
                    text: "This sub category will be deleted",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.isConfirmed) {
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