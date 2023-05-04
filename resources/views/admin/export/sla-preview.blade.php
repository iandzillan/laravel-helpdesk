<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SLA Report Preview - Helpdesk Ticketing System</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}" />
    
    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{asset('assets/css/core/libs.min.css')}}" />
    
    <!-- Aos Animation Css -->
    <link rel="stylesheet" href="{{asset('assets/vendor/aos/dist/aos.css')}}" />
    
    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{asset('assets/css/hope-ui.min.css?v=1.2.0')}}" />
    
    <!-- Custom Css -->
    <link rel="stylesheet" href="{{asset('assets/css/custom.min.css?v=1.2.0')}}" />
    
    <!-- Dark Css -->
    <link rel="stylesheet" href="{{asset('assets/css/dark.min.css')}}"/>
    
    <!-- Customizer Css -->
    <link rel="stylesheet" href="{{asset('assets/css/customizer.min.css')}}" />
    
    <!-- RTL Css -->
    <link rel="stylesheet" href="{{asset('assets/css/rtl.min.css')}}"/>

    <!-- Library Bundle Script -->
    <script src="{{asset('assets/js/core/libs.min.js')}}"></script>
</head>
<body>
    @if ($tickets->count() == 0)
        <div style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%)">
            <h2 class="text-center">No data between {{ $validate['from'] }} - {{ $validate['to'] }}</h2>
        </div>
    @else    
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.sla.report') }}" method="post">
                    @method('post')
                    @csrf
                    <input type="hidden" name="from" value="{{ $validate['from'] }}">
                    <input type="hidden" name="to" value="{{ $validate['to'] }}">
                    
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-download"></i>
                        Export
                    </button>
                </form>
                <br>

                <div class="table-responsive">
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th colspan="14" align="center">
                                    <b>
                                        Service-Level Agreement Report From {{ $validate['from'] }} - {{ $validate['to'] }} 
                                    </b>
                                </th>
                            </tr>
                            <tr>
                                <th>No</th>
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
                        <tbody>
                            @forelse ($tickets as $ticket)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $ticket->created_at }}</td>
                                    <td>{{ $ticket->ticket_number }}</td>
                                    <td>{{ $ticket->user->employee->name }}</td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>{{ $ticket->subCategory->category->name }}</td>
                                    <td>{{ $ticket->subCategory->name }}</td>
                                    <td>{{ ($ticket->technician == null) ? "--" : $ticket->technician->employee->name }}</td>
                                    <td>{{ $ticket->status }}</td>
                                    <td>{{ $ticket->progress_at }}</td>
                                    <td>{{ $ticket->finish_at }}</td>
                                    <td>{{ ($ticket->urgency == null) ? "--" : gmdate('H:i:s', $ticket->urgency->hours * 3600) }}</td>
                                    <td>{{ gmdate('H:i:s', $ticket->trackings->where('status', '!=', 'Ticket Continued')->sum('duration')) }}</td>
                                    <td>{{ gmdate('H:i:s', $ticket->trackings->where('status', 'Ticket Continued')->sum('duration')) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12">No data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="12" align="center">
                                    <b>
                                        Total ticket based on status 
                                    </b>
                                </th>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <th>Total Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $status = $tickets->groupBy('status');
                            @endphp
                            <tr>
                                <td>Open</td>
                                <td>{{ ($status->get('Open') == null) ? 0 : $status->get('Open')->count() }}</td>
                            </tr>
                            <tr>
                                <td>Approved by Team Leader</td>
                                <td>{{ ($status->get('Approved by Team Leader') == null) ? 0 : $status->get('Approved by Team Leader')->count() }}</td>
                            </tr>
                            <tr>
                                <td>Approved by Manager</td>
                                <td>{{ ($status->get('Approved by Manager') == null) ? 0 : $status->get('Approved by Manager')->count() }}</td>
                            </tr>
                            <tr>
                                <td>On work</td>
                                <td>{{ ($status->get('On work') == null) ? 0 : $status->get('On work')->count() }}</td>
                            </tr>
                            <tr>
                                <td>Pending</td>
                                <td>{{ ($status->get('Pending') == null) ? 0 : $status->get('Pending')->count() }}</td>
                            </tr>
                            <tr>
                                <td>Closed</td>
                                <td>{{ ($status->get('Closed') == null) ? 0 : $status->get('Closed')->count() }}</td>
                            </tr>
                            <tr>
                                <td>Rejected</td>
                                <td>{{ ($status->get('Rejected') == null) ? 0 : $status->get('Rejected')->count() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2">
                                    <b>Total Ticket Based on Category</b>
                                </th>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <th>Total Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->tickets_count }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td align="center">
                                    <b>Total</b>
                                </td>
                                <td>
                                    <b>{{ $categories->sum('tickets_count') }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <b>Total Ticket Based on Sub Category</b>
                                </th>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Total Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                @foreach ($category->subCategories as $subcategory)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $subcategory->name }}</td>
                                        <td>{{ $subcategory->tickets->whereBetween('created_at', [$validate['from'], $validate['to']])->count() }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <td colspan="2" align="center">
                                    <b>Total</b>
                                </td>
                                <td>
                                    <b>{{ $categories->sum('tickets_count') }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2">
                                    <b>Total ticket in each department</b>
                                </th>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <th>Total Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sum = 0;
                            @endphp
                            @foreach ($data_dept as $row)
                                <tr>
                                    <td>{{ $row['department'] }}</td>
                                    <td>{{ $row['tickets_count'] }}</td>
                                </tr>
                                @php
                                    $sum += $row['tickets_count'];
                                @endphp
                            @endforeach
                            <tr>
                                <td align="center"><b>Total</b></td>
                                <td><b>{{ $sum }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    <b>Total Ticket Based on Sub Department</b>
                                </th>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <th>Sub Department</th>
                                <th>Total Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sum1 = 0 @endphp
                            @foreach ($data_subdept as $row)
                                <tr>
                                    <td>{{ $row['dept'] }}</td>
                                    <td>{{ $row['subdept'] }}</td>
                                    <td>{{ $row['count'] }}</td>
                                </tr>
                                @php
                                    $sum1 += $row['count']
                                @endphp
                            @endforeach
                            <tr>
                                <td colspan="2" align="center">
                                    <b>Total</b>
                                </td>
                                <td>
                                    <b>{{ $sum1 }}</b>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2">Total tickets based on SLA</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Total Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sla = $tickets->groupBy('isUnderSla');
                            @endphp
                            <tr>
                                <td>Within SLA</td>
                                <td>{{ ($sla->get('1') == null) ? 0 : $sla->get('1')->count() }}</td>
                            </tr>
                            <tr>
                                <td>Out of SLA</td>
                                <td>{{ ($sla->get('0') == null) ? 0 : $sla->get('0')->count() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- External Library Bundle Script -->
    <script src="{{asset('assets/js/core/external.min.js')}}"></script>
    
    <!-- Widgetchart Script -->
    <script src="{{asset('assets/js/charts/widgetcharts.js')}}"></script>
    
    <!-- mapchart Script -->
    <script src="{{asset('assets/js/charts/vectore-chart.js')}}"></script>
    <script src="{{asset('assets/js/charts/dashboard.js')}}" ></script>
    
    <!-- fslightbox Script -->
    <script src="{{asset('assets/js/plugins/fslightbox.js')}}"></script>
    
    <!-- Settings Script -->
    <script src="{{asset('assets/js/plugins/setting.js')}}"></script>
    
    <!-- Slider-tab Script -->
    <script src="{{asset('assets/js/plugins/slider-tabs.js')}}"></script>
    
    <!-- Form Wizard Script -->
    <script src="{{asset('assets/js/plugins/form-wizard.js')}}"></script>
    
    <!-- AOS Animation Plugin-->
    <script src="{{asset('assets/vendor/aos/dist/aos.js')}}"></script>
    
    <!-- App Script -->
    <script src="{{asset('assets/js/hope-ui.js')}}" defer></script>

    <script>
        $(document).ready(function(){
            $('.data-table').DataTable({
                initComplete: function (settings, json) {  
                    $(".data-table").wrap("<div style='overflow:auto; width:100%; position:relative;'></div>");            
                }
            });
        });
    </script>
</body>
</html>