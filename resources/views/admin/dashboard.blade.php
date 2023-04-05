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
                                    <h4 class="counter">{{ $ticket['total'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa-solid fa-list fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                    <p  class="mb-2">Unassigned</p>
                                    <h4 class="counter">{{ $ticket['new'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa-solid fa-hammer fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                    <p  class="mb-2">On Work</p>
                                    <h4 class="counter">{{ $ticket['new'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa-solid fa-circle-pause fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                    <p  class="mb-2">Pending</p>
                                    <h4 class="counter">{{ $ticket['pending'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa-solid fa-circle-check fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                    <p  class="mb-2">Closed</p>
                                    <h4 class="counter">{{ $ticket['closed'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                        <div class="card-body">
                            <div class="progress-widget">
                                <span class="fa-stack fa-2x">
                                    <i class="fa-solid fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa-solid fa-circle-xmark fa-stack-1x fa-inverse"></i>
                                </span>
                                <div class="progress-detail">
                                    <p  class="mb-2">Rejected</p>
                                    <h4 class="counter">{{ $ticket['rejected'] }}</h4>
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
@endsection