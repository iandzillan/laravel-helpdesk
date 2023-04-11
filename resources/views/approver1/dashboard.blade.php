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
                                    <i class="fa-solid fa-user fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                <p  class="mb-2">My Tickets</p>
                                <h4 class="counter">{{ $ticket['myticket']->count() }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-secondary"></i>
                                    <i class="fa-solid fa-inbox fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                <p  class="mb-2">Need Approval</p>
                                <h4 class="counter">{{ $ticket['approval_manager']->count() }}</h4>
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
                                <select id="filter-bar" name="filter-bar" class="form-select mb-3">
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
            <div class="col-md-12 col-lg-12">
                <div class="card" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex-wrap card-header d-flex align-items-center">
                        <span class="fa-stack fa-2x">
                            <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                            <i class="fa-solid fa-hammer fa-stack-1x fa-inverse"></i>
                        </span>
                        <div class="header-title">
                            <h4 class="card-title">On Work</h4>
                        </div>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow: auto">
                        <div class="table-responsive">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ticket</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ticket['onwork'] as $onwork)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <a href="{{ route('dept.tickets.onwork.show', $onwork->ticket_number) }}">
                                                    {{$onwork->ticket_number}}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $onwork->progress }}%;" aria-valuenow="{{ $onwork->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $onwork->progress }}%</div>
                                                </div>
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

    <div class="col-md-12 col-lg-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card" data-aos="fade-up" data-aos-delay="900">
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
                                <select id="filter-donut" name="filter-donut" class="form-select mb-3">
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

        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex-wrap card-header d-flex align-items-center">
                        <span class="fa-stack fa-2x">
                            <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                            <i class="fa-solid fa-inbox fa-stack-1x fa-inverse"></i>
                        </span>
                        <div class="header-title">
                            <h4 class="card-title">Need your approval</h4>
                        </div>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow: auto">
                        <div class="table-responsive">
                            <table id="basic-table" class="table table-striped mb-0" role="grid">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ticket</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ticket['approval_manager'] as $approval)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <a href="{{ route('dept.entry.tickets.show', $approval->ticket_number) }}">
                                                    {{$approval->ticket_number}}
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

    @include('approver1.dashboard-chart')
@endsection