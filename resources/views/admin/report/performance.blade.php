@extends('layouts.app')

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Success Rate</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div id="success-rate" class="d-main"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Feedback Rate</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div id="feedback-rate" class="d-main"></div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Technician</th>
                                    <th>Success Rate</th>
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
    </div>

    <script>
        $(document).ready(function(){
            // data table
            let table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                initComplete: function (settings, json) {  
                    $(".data-table").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                },
                ajax: "{{ route('admin.performance') }}",
                columns: [
                    {data:'DT_RowIndex', name: 'DT_RowIndex'},
                    {data:'name', name: 'name'},
                    {data:'success', name: 'success'},
                    {data:'action', name: 'action', orderable: false, searchable: false},
                ],
                columnDefs: [
                    {targets: [2],
                    className: 'text-center'}
                ]
            });

            // rate percentage chart
            let donutSuccessRate = {
                series: [],
                labels: ['Above SLA Time', 'Under SLA Time'],
                colors: ['#D4526E', '#33B2DF'],
                chart: {
                    type: 'donut',
                },
                responsive: [{
                    options: {
                        chart: {
                            width: 200,
                        }
                    }
                }],
                legend: {
                    show: false,
                },
                noData: {
                    text: 'No data...'
                }
            };
            let donutSuccessChart = new ApexCharts(document.querySelector('#success-rate'), donutSuccessRate);
            donutSuccessChart.render();

            let url = "{{ route('admin.solvePercentage.year') }}";
            $.getJSON(url, function(response){
                donutSuccessChart.updateOptions({
                    series: response.data
                });
            });

            // feedback rate
            let donutFeedbackRate = {
                series: [],
                labels: [],
                colors: [],
                chart: {
                    type: 'donut',
                },
                responsive: [{
                    options: {
                        chart: {
                            width: 200
                        }
                    }
                }],
                legend: {
                    show: false,
                },
                noData: {
                    text: 'No data...'
                }
            };
            let donutFeedbackChart = new ApexCharts(document.querySelector('#feedback-rate'), donutFeedbackRate);
            donutFeedbackChart.render();

            let url1 = "{{ route('admin.feedback.rate') }}";
            $.getJSON(url1, function(response){
                donutFeedbackChart.updateOptions({
                    series: response.data,
                    labels: response.name,
                    colors: response.color
                });
            });
        });
    </script>
@endsection