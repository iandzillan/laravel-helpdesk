@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">Feedback</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-table" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ticket Number</th>
                                <th>User</th>
                                <th>Technician</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Date</th>
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
            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $(".data-table").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('admin.feedback') }}",
                columns: [
                    {data:'DT_RowIndex', name: 'DT_RowIndex'},
                    {data:'ticket_number', name: 'ticket_number'},
                    {data:'user', name: 'user'},
                    {data:'technician', name: 'technician'},
                    {data:'rating', name: 'rating'},
                    {data:'note', name: 'note'},
                    {data:'created_at', name: 'created_at'},
                ],
                columnDefs: [
                    {targets: [4],
                    className: 'text-center'}
                ]
            });
        });
    </script>
@endsection