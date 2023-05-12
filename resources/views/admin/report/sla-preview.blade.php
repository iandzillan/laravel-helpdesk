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

    <div class="col-sm-12 d-none" id="data-available">
        <div class="col mb-3">
            <form action="{{ route('admin.sla.report') }}" method="post">
                @method('post')
                @csrf
                <input type="hidden" name="from" id="export-from">
                <input type="hidden" name="to" id="export-to">
                <button type="submit" class="btn btn-primary" id="btn-export">
                    <i class="fa-regular fa-file-excel"></i> 
                    Export
                </button>
            </form>
        </div>

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

        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <div class="card-title">
                        <h4>Status</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive mb-3">
                    <table class="table table-bordered data-status" width="100%">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Total Ticket</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <h5 id="status-sum"></h5>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <div class="card-title">
                        <h4>Category</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-category" width="100%">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Total ticket</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <h5 id="category-sum"></h5>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <div class="card-title">
                        <h4>Sub Category</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-subcategory" width="100%">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Total ticket</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <h5 id="subcategory-sum"></h5>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <div class="card-title">
                        <h4>Department</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-dept" width="100%">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Total ticket</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <h5 id="dept-sum"></h5>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <div class="card-title">
                        <h4>Sub Department</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-subdept" width="100%">
                        <thead>
                            <tr>
                                <th colspan="3">Sub Department</th>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <th>Sub Department</th>
                                <th>Total ticket</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="2"><b>Total</b></td>
                                <td id="subdept-sum"><b></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered data-manager" width="100%">
                        <thead>
                            <tr>
                                <th colspan="2">Manager</th>
                            </tr>
                            <tr>
                                <th>Department Manager</th>
                                <th>Total ticket</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td><b>Total</b></td>
                                <td id="manager-sum"><b></b></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <h5 id="submanag-sum"></h5>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="header-title">
                    <div class="card-title">
                        <h4>SLA</h4>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered data-sla" width="100%">
                        <thead>
                            <tr>
                                <th>SLA</th>
                                <th>Total ticket</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <h5 id="sla-sum"></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12 d-none" id="data-empty">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <h4></h4>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){

            $('#sla-submit').click(function(e){
                e.preventDefault();

                // show loading
                Swal.fire({
                    title: 'Please wait',
                    text: 'Get the data...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEnterKey: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

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
                        // show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            showConfirmButton: false,
                            timer: 1000
                        });
                        
                        if (response.ticketReport.length == 0) {
                            $('#data-available').addClass('d-none');
                            $('#data-empty').removeClass('d-none').addClass('d-block').find('.text-center h4').html('No data available between ' + response.from + ' - ' + response.to);
                        } else {
                            $('#data-available').removeClass('d-none').addClass('d-block');
                            $('#data-empty').addClass('d-none').removeClass('d-block');
                            
                            // fill hidden form
                            $('#export-from').val(from);
                            $('#export-to').val(to);

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
                            let statusSum = 0;
                            $.each(response.statusReport, function(code, status){
                                statusSum += status.count || 0 
                            });
                            $('#status-sum').html('Total: ' + statusSum);
    
                            // Category table
                            $('.data-category').DataTable({
                                destroy: true,
                                searching: false,
                                paging: false,
                                info: false,
                                data: response.categoryReport,
                                columns: [
                                    {data: 'category'},
                                    {data: 'count'}
                                ]
                            });
                            let categorySum = 0;
                            $.each(response.categoryReport, function(code, category){
                                categorySum += category.count || 0
                            });
                            $('#category-sum').html('Total: ' + categorySum);

                            // Sub Category table
                            $('.data-subcategory').DataTable({
                                destroy: true,
                                searching: false,
                                paging: false,
                                info: false,
                                data: response.subcategoryReport,
                                columns: [
                                    {data: 'category'},
                                    {data: 'subcategory'},
                                    {data: 'count'},
                                ]
                            });
                            let subcategorySum = 0;
                            $.each(response.subcategoryReport, function(code, subcategory){
                                subcategorySum += subcategory.count || 0;
                            });
                            $('#subcategory-sum').html('Total: ' + subcategorySum);

                            // Department table
                            $('.data-dept').DataTable({
                                destroy: true,
                                searching: false,
                                paging: false, 
                                info: false,
                                data: response.deptReport,
                                columns: [
                                    {data: 'dept'},
                                    {data: 'count'},
                                ]
                            });
                            let deptSum = 0;
                            $.each(response.deptReport, function(code, dept){
                                deptSum += dept.count || 0;
                            });
                            $('#dept-sum').html('Total: ' + deptSum);

                            // Sub Department table
                            $('.data-subdept').DataTable({
                                destroy: true,
                                searching: false,
                                paging: false, 
                                info: false,
                                data: response.subdeptReport, 
                                columns: [
                                    {data: 'dept'},
                                    {data: 'subdept'},
                                    {data: 'count'},
                                ]
                            });
                            let subdeptSum = 0;
                            $.each(response.subdeptReport, function(code, subdept){
                                subdeptSum += subdept.count || 0;
                            });
                            $('#subdept-sum b').html(subdeptSum);

                            // Manager table
                            $('.data-manager').DataTable({
                                destroy: true, 
                                searching: false,
                                paging: false,
                                info: false,
                                data: response.managerReport,
                                columns: [
                                    {data: 'manager'},
                                    {data: 'count'},
                                ]
                            });
                            let managerSum = 0;
                            $.each(response.managerReport, function(code, manager){
                                managerSum += manager.count || 0;
                            });
                            $('#manager-sum b').html(managerSum);

                            // total subdept + manager
                            $('#submanag-sum').html('Total: ' + (subdeptSum + managerSum));

                            // SLA table
                            $('.data-sla').DataTable({
                                destroy: true, 
                                searching: false, 
                                paging: false, 
                                info: false,
                                data: response.slaCount,
                                columns: [
                                    {data: 'name'},
                                    {data: 'count'},
                                ]
                            });
                            let slaSum = 0;
                            $.each(response.slaCount, function(code, sla){
                                slaSum += sla.count || 0;
                            });
                            $('#sla-sum').html('Total: ' + slaSum);
                        }
                    },
                    error:function(error){
                        console.log(error.responseJSON.message);
                    }
                })
            });
        });
    </script>
@endsection