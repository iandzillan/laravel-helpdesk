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
                                <p  class="mb-2">Assigned Tickets</p>
                                <h4 class="counter">{{ $ticket['onwork']->count() }}</h4>
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
                            <i class="fa-solid fa-hammer fa-stack-1x fa-inverse"></i>
                        </span>
                        <div class="header-title">
                            <h4 class="card-title">On work</h4>
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
                                    @forelse ($ticket['onwork'] as $onwork)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <a href="{{ route('technician.tickets.onwork.show', $onwork->ticket_number) }}">
                                                    {{$onwork->ticket_number}}
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
                                                <a href="{{ route('technician.tickets.onwork.show', $pending->ticket_number) }}">
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