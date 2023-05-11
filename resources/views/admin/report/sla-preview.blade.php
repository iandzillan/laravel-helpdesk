@extends('layouts.app')

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <div class="card-title">
                        <h4>SLA Report</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="form-report" action="{{ route('admin.sla.create') }}" method="post">
                    <div class="row d-flex justify-content-start">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="from" class="form-lable">From</label>
                                <input type="date" name="from" id="from" class="form-control">
                                <div class="invalid-feedback" role="alert" id="alert-from"></div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="to" class="form-lable">To</label>
                                <input type="date" name="to" id="to" class="form-control">
                                <div class="invalid-feedback" role="alert" id="alert-to"></div>
                            </div>
                        </div>
                        <div class="col-4 d-flex align-items-center">
                            <button type="submit" id="sla-submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <div class="card-title">
                        <h4>Ticket</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-ticket">
                        <thead>
                            <tr>
                                <th>Created At</th>
                                <th>Ticket Number</th>
                                <th>User</th>
                                <th>Subject</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Technician</th>
                                <th>Status</th>
                                <th>Progress At</th>
                                <th>Finish At</th>
                                <th>SLA Duration</th>
                                <th>On work Duration</th>
                                <th>Pending Duration</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <div class="card-title">
                        <h4>Ticket based on status</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-status" width="100%">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Total Ticket</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td class="text-bold">Total</td>
                                <td id="statusSum" class="text-bold"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){

            $('#sla-submit').click(function(e){
                e.preventDefault();

                let from  = $('#from').val();
                let to    = $('#to').val();
                let token = $('meta[name="csrf-token"]').attr('content');
                
                $.ajax({
                    url: "{{ route('admin.sla.create') }}",
                    type: 'post',
                    cache: false,
                    data: {
                        'from': from, 
                        'to': to,
                        '_token': token
                    },
                    success:function(response){
                        // Ticket table
                        $('.data-ticket').DataTable({
                            destroy: true, 
                            initComplete: function (settings, json) {  
                                $(".data-ticket").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                            },
                            data: response.ticketReport,
                            columns: [
                                {data: 'created_at'},
                                {data: 'ticket_number'},
                                {data: 'user'},
                                {data: 'subject'},
                                {data: 'category'},
                                {data: 'subcategory'},
                                {data: 'technician'},
                                {data: 'status'},
                                {data: 'progress_at'},
                                {data: 'finish_at'},
                                {data: 'urgency'},
                                {data: 'onwork'},
                                {data: 'pending'},
                            ]
                        });

                        // Status table
                        $('.data-status').DataTable({
                            destroy: true,
                            searching: false,
                            paging: false,
                            info: false,
                            data: response.statusReport,
                            columns: [
                                {data: 'status'},
                                {data: 'count'},
                            ]
                        });
                        let statusSum = 0
                        $.each(response.statusReport, function(code, status){
                            statusSum += status.count || 0 
                        });
                        $('.data-status tfoot #statusSum').html(statusSum);
                    }
                })
            });
        });
    </script>
@endsection