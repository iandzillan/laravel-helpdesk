@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Employess</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table display responsive nowrap" width=100%>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIK</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Sub Department</th>
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
        $(function(){
            // draw table
            let table = $('.data-table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('approver.employees.list') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'nik', name: 'nik'},
                    {data: 'name', name: 'name'},
                    {data: 'position', name: 'position'},
                    {data: 'subdept', name: 'subdept'},
                    {data: 'dept', name: 'dept'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // delete  button event
            $('#btn-delete-employee').click(function(){
                // define variable
                
            });
        });
    </script>
@endsection