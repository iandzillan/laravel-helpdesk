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

    <div class="col-md-12 col-lg-6">
        <div class="row">
            <div class="col-md-12">
                <div class="card" data-aos="fade-up" data-aos-delay="800">
                    <div class="flex-wrap card-header d-flex align-items-center">
                        <span class="fa-stack fa-2x">
                            <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                            <i class="fa-solid fa-code-fork fa-stack-1x fa-inverse"></i>
                        </span>
                        <div class="header-title">
                            <h4 class="card-title">On Approval Lv. 2</h4>
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
                                    @forelse ($ticket['new'] as $new)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <a href="{{ route('user.my.tickets.show', $new->ticket_number) }}">
                                                    {{$new->ticket_number}}
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

        <div class="row">
            <div class="col-md-12">
                <div class="card" data-aos="fade-up" data-aos-delay="800">
                    <div class="flex-wrap card-header d-flex align-items-center">
                        <span class="fa-stack fa-2x">
                            <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                            <i class="fa-solid fa-building fa-stack-1x fa-inverse"></i>
                        </span>
                        <div class="header-title">
                            <h4 class="card-title">On Approval Lv. 1</h4>
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
                                    @forelse ($ticket['approval_manager'] as $approval_manager)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <a href="{{ route('user.my.tickets.show', $approval_manager->ticket_number) }}">
                                                    {{$approval_manager->ticket_number}}
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

    <div class="col-md-12 col-lg-6">
        <div class="row">
            <div class="col-md-12">
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
                                                <a href="{{ route('user.my.tickets.show', $unassigned->ticket_number) }}">
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
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card" data-aos="fade-up" data-aos-delay="800">
                    <div class="flex-wrap card-header d-flex align-items-center">
                        <span class="fa-stack fa-2x">
                            <i class="fa-solid fa-square fa-stack-2x text-primary"></i>
                            <i class="fa-solid fa-pause fa-stack-1x fa-inverse"></i>
                        </span>
                        <div class="header-title">
                            <h4 class="card-title">Pending</h4>
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
                                    @forelse ($ticket['pending'] as $pending)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <a href="{{ route('user.my.tickets.show', $pending->ticket_number) }}">
                                                    {{$pending->ticket_number}}
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
@endsection