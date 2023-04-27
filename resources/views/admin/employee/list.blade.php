@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Managers List</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Department</th>
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

    <script>
        $(document).ready(function(){
            // draw table
            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $(".data-table").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('admin.managers.list') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'dept', name: 'dept'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });

            // destroy button action
            $('body').on('click', '#btn-delete-employee', function(){
                // define variable
                let id    = $(this).data('id');
                let token = $('meta[name="csrf-token"]').attr('content');
                let url   = "{{ route('admin.managers.destroy', ":id") }}";
                url       = url.replace(':id', id);

                // show confirmation
                swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    text: 'All data related to this manager will be deleted',
                    showCancelButton: true,
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                }).then((result) => {
                    if (result.isConfirmed) {
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

                        // ajax delete
                        $.ajax({
                            url: url,
                            type: 'delete',
                            cache: false,
                            data: {
                                '_token': token
                            },
                            success:function(response){
                                // show message
                                swal.fire({
                                    icon: 'success',
                                    title: `${response.message}`,
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                                // table draw
                                table.draw();
                            }, 
                            error:function(error){
                                console.log(error.responseJSON.message);
                            }
                        });
                    }
                });

            });
        });
    </script>
@endsection