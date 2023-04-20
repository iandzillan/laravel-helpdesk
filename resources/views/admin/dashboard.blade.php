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

    <div class="col-md-12 col-lg-12">
        <div class="row">
            <div class="col-md-3 mb-4">
                <button class="btn btn-primary btn-sm" data-aos="fade-up" data-aos-delay="800" id="generate-report">
                    <i class="fa-solid fa-download"></i>
                    Generate SLA Report
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-lg-12">
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
    </div>

    <div class="col-md-12 col-lg-12">
        <div class="row">
            <div class="col-md-6">
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

            <div class="col-md-6">
                <div class="card" data-aos="fade-up" data-aos-delay="800">
                    <div class="flex-wrap card-header d-flex justify-content-between align-items-center">
                        <div class="header-title d-flex justify-content-between align-items-center">
                            <span class="fa-stack fa-2x">
                                <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                                <i class="fa-solid fa-check fa-stack-1x fa-inverse"></i>
                            </span>
                            <h4 class="card-title">Success Rate</h4>
                        </div>
                        <div class="d-flex align-items-center align-self-center">
                            <div class="dropdown">
                                <select id="filter-percentage" name="filter-percentage" class="form-select mb-3">
                                    <option value="year" selected>This Year</option>
                                    <option value="month">This Month</option>
                                    <option value="week">This Week</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="success-rate" class="d-main"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-lg-12">
        <div class="row">
            <div class="col-md-12 col-lg-6">
                <div class="card" data-aos="fade-up" data-aos-delay="800">
                    <div class="flex-wrap card-header d-flex align-items-center">
                        <span class="fa-stack fa-2x">
                            <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                            <i class="fa-solid fa-list fa-stack-1x fa-inverse"></i>
                        </span>
                        <div class="header-title">
                            <h4 class="card-title">Unassigned</h4>
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
                                    @forelse ($ticket['unassigned'] as $unassigned)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <a href="{{ route('admin.entry.tickets.show', $unassigned->ticket_number) }}">
                                                    {{$unassigned->ticket_number}}
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

            <div class="col-md-12 col-lg-6">
                <div class="card" data-aos="fade-up" data-aos-delay="800">
                    <div class="flex-wrap card-header d-flex align-items-center">
                        <span class="fa-stack fa-2x">
                            <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                            <i class="fa-solid fa-list fa-stack-1x fa-inverse"></i>
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
                                        <th>Technician</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ticket['onwork'] as $onwork)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <a href="{{ route('admin.tickets.onwork.show', $onwork->ticket_number) }}">
                                                    {{$onwork->ticket_number}}
                                                </a>
                                            </td>
                                            <td>{{ $onwork->Technician->employee->name }}</td>
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

    {{-- Modal --}}
    <div class="modal fade" id="modal-report" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Generate SLA Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form-date">
                    <div class="modal-body">
                        <div class="row d-flex justify-content-center">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="from" class="form-label">From</label>
                                    <input type="date" name="from" id="from" class="form-control">
                                    <div class="invalid-feedback d-none" role="alert" id="alert-form"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="to" class="form-label">To</label>
                                    <input type="date" name="to" id="to" class="form-control">
                                    <div class="invalid-feedback d-none" role="alert" id="alert-to"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="generate">Generate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.dashboard-chart')
@endsection