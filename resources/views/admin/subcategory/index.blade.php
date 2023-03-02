@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Sub Categories</h4>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary mb-2" id="btn-create-category">
                    <i class="fa-solid fa-folder-plus"></i>
                    Add Sub Category
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width="100%">
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

    {{-- @include('admin.subcategory.modal-create') --}}
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
                    {data: 'category', name: 'name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endsection