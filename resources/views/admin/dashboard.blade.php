@extends('layouts.app')

@section('content')
    <div class="col-md-12 col-lg-12">
        <div class="row row-cols-1">
            <div class="overflow-hidden d-slider1 ">
                <ul  class="p-0 m-0 mb-2 swiper-wrapper list-inline">
                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa-solid fa-ticket fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                <p  class="mb-2">Total Tickets</p>
                                <h4 class="counter">{{ $ticket['total']->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-dark"></i>
                                    <i class="fa-solid fa-code-fork fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                <p  class="mb-2">Approver Lv.2</p>
                                <h4 class="counter">{{ $ticket['new']->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-secondary"></i>
                                    <i class="fa-solid fa-building fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                <p  class="mb-2">Approver Lv.1</p>
                                <h4 class="counter">{{ $ticket['approval_manager']->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-success"></i>
                                    <i class="fa-solid fa-list fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                <p  class="mb-2">Unassigned</p>
                                <h4 class="counter">{{ $ticket['unassigned']->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-info"></i>
                                    <i class="fa-solid fa-hammer fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                <p  class="mb-2">On Work</p>
                                <h4 class="counter">{{ $ticket['onwork']->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-warning"></i>
                                    <i class="fa-solid fa-pause fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                <p  class="mb-2">Pending</p>
                                <h4 class="counter">{{ $ticket['pending']->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa-solid fa-check fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                <p  class="mb-2">Closed</p>
                                <h4 class="counter">{{ $ticket['closed']->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-danger"></i>
                                    <i class="fa-solid fa-xmark fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                <p  class="mb-2">Rejected</p>
                                <h4 class="counter">{{ $ticket['rejected']->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="swiper-button swiper-button-next"></div>
                <div class="swiper-button swiper-button-prev"></div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-lg-7">
        <div class="row">
            <div class="col-md-12">
                <div class="card" data-aos="fade-up" data-aos-delay="800">
                    <div class="flex-wrap card-header d-flex justify-content-between align-items-center">
                        <div class="header-title d-flex justify-content-between align-items-center">
                            <span class="fa-stack fa-2x">
                                <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                                <i class="fa-solid fa-folder fa-stack-1x fa-inverse"></i>
                            </span>
                            <h4 class="card-title">Category</h4>
                        </div>
                        <div class="d-flex align-items-center align-self-center">
                            <div class="dropdown">
                                <select id="filter" name="filter" class="form-select mb-3">
                                    <option value="year" selected>This Year</option>
                                    <option value="month">This Month</option>
                                    <option value="week">This Week</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="category" class="d-main"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card" data-aos="fade-up" data-aos-delay="800">
                    <div class="flex-wrap card-header d-flex justify-content-between align-items-center">
                        <div class="header-title d-flex justify-content-between align-items-center">
                            <span class="fa-stack fa-2x">
                                <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                                <i class="fa-solid fa-folder-tree fa-stack-1x fa-inverse"></i>
                            </span>
                            <h4 class="card-title">Sub Category</h4>
                        </div>
                        <div class="d-flex align-items-center align-self-center">
                            <div class="dropdown">
                                <select id="filter2" name="filter2" class="form-select mb-3">
                                    <option value="year" selected>This Year</option>
                                    <option value="month">This Month</option>
                                    <option value="week">This Week</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="sub-category" class="d-main"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-lg-5">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card" data-aos="fade-up" data-aos-delay="900">
                    <div class="flex-wrap card-header d-flex align-items-center">
                        <span class="fa-stack fa-2x">
                            <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                            <i class="fa-solid fa-code-fork fa-stack-1x fa-inverse"></i>
                        </span>
                        <div class="header-title">
                            <h4 class="card-title">Need your approval</h4>
                        </div>
                    </div>
                    <div class="card-body" style="height: 293px; overflow: auto">
                        <div class="table-responsive">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ticket</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ticket['unassigned'] as $ticket)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$ticket->ticket_number}}</td>
                                            <td>
                                                <a href="{{ route('subdept.entry.tickets.show', $ticket->ticket_number) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-magnifying-glass"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" align="center">No Ticket...</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            let options = {
                series: [], 
                chart: {
                    type: 'bar',
                    height: 350
                }, 
                plotOptions: {
                    bar: {
                        horizontal: false, 
                        columnWidth: '55%',
                        endingShape: 'rounded',
                        borderRadius: 5,
                    }
                }, 
                dataLabels: {
                    enabled: false
                }, 
                stroke: {
                    show: true, 
                    width: 2,
                    curve: 'smooth',
                    colors: ['transparent']
                }, 
                xaxis: {
                    type: 'category',
                    categories: [],
                }, 
                yaxis: {
                    title: {
                        text: 'total'
                    }
                }, 
                legend: {
                    show: false,
                },
                fill: {
                    opacity: 1
                }, 
                tooltip: {
                    y: {
                        formatter: function(val){
                            return val; 
                        }
                    }
                }, 
                noData: {
                    text: 'No data...'
                }
            };
            let barchart = new ApexCharts(document.querySelector('#category'), options);
            barchart.render();

            let url = "{{ route('admin.chart.year') }}";
            $.getJSON(url, function(response){
                let series     = [];
                for (let i = 0; i < response.name.length; i++) {
                    series.push({
                        name: response.name[i],
                        data: response.data[i]
                    });
                }
                barchart.updateOptions({
                    xaxis: {
                        categories: response.month
                    },
                    series: series
                })
            })

            $('#filter').on('change', function(){
                let value = $('#filter').val();
                switch (value) {
                    case 'year':
                        let url1 = "{{ route('admin.chart.year') }}";
                        $.getJSON(url1, function(response){
                            let series = [];
                            for (let i = 0; i < response.name.length; i++) {
                                series.push({
                                    name: response.name[i],
                                    data: response.data[i]
                                });
                            }
                            barchart.updateOptions({
                                xaxis: {
                                    categories: response.month
                                },
                                series: series
                            })
                        })
                        break;

                        case 'month':
                            let url2 = "{{ route('admin.chart.month') }}";
                            $.getJSON(url2, function(response){
                                let series2 = [];
                                for (let i = 0; i < response.name.length; i++) {
                                    series2.push({
                                        name: response.name[i],
                                        data: response.data[i]
                                    });
                                }
                                barchart.updateOptions({
                                    xaxis:{
                                        categories: response.month
                                    },
                                    series: series2
                                });
                            })
                        break;

                    case 'week':
                        let url3 = "{{ route('admin.chart.week') }}";
                            $.getJSON(url3, function(response){
                                let series2 = [];
                                for (let i = 0; i < response.name.length; i++) {
                                    series2.push({
                                        name: response.name[i],
                                        data: response.data[i]
                                    });
                                }
                                barchart.updateOptions({
                                    xaxis:{
                                        categories: response.week
                                    },
                                    series: series2
                                });
                            })
                        break;
                
                    default:
                        break;
                }
            })

        });
    </script>
@endsection