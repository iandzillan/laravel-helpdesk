<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
    <div class="container-fluid navbar-inner">
        <a href="#" class="navbar-brand">
            <i class="fa-solid fa-screwdriver-wrench text-primary"></i>

            <h2 class="logo-title">Helpdesk</h2>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20px" height="20px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                </svg>
            </i>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <span class="mt-2 navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded">
                        <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-purple-img img-fluid avatar avatar-50 avatar-rounded">
                        <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-blue-img img-fluid avatar avatar-50 avatar-rounded">
                        <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-green-img img-fluid avatar avatar-50 avatar-rounded">
                        <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-yellow-img img-fluid avatar avatar-50 avatar-rounded">
                        <img src="{{ asset('storage/uploads/photo-profile/'. Auth::user()->employee->image) }}" alt="User-Profile" class="theme-color-pink-img img-fluid avatar avatar-50 avatar-rounded">
                        <div class="caption ms-3 d-none d-md-block ">
                            <h6 class="mb-0 caption-title">{{Auth::user()->employee->name}}</h6>
                            <p class="mb-0 caption-sub-title text-sm">{{Auth::user()->employee->position}} of 
                                @if (Auth::user()->employee->position == 'Manager')
                                    {{Auth::user()->employee->department->name}}
                                @else
                                    {{Auth::user()->employee->subDepartment->name}}
                                @endif
                            </p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('accountProfile') }}">Account Profile</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{route('logout')}}">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="iq-navbar-header" style="height: 215px">
    <div class="container-fluid iq-container">
        <div class="row">
            <div class="col-md-12">
                <div class="flex-wrap d-flex justify-content-between align-items-center">
                    <div>
                        <h1>Hello {{$name}}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="iq-header-img">
        <img src="{{ asset('assets/images/dashboard/top-header.png') }}" alt="header" class="theme-color-default-img img-fluid w-100 h-100 animated-scaleX">
        <img src="{{ asset('assets/images/dashboard/top-header1.png') }}" alt="header" class="theme-color-purple-img img-fluid w-100 h-100 animated-scaleX">
        <img src="{{ asset('assets/images/dashboard/top-header2.png') }}" alt="header" class="theme-color-blue-img img-fluid w-100 h-100 animated-scaleX">
        <img src="{{ asset('assets/images/dashboard/top-header3.png') }}" alt="header" class="theme-color-green-img img-fluid w-100 h-100 animated-scaleX">
        <img src="{{ asset('assets/images/dashboard/top-header4.png') }}" alt="header" class="theme-color-yellow-img img-fluid w-100 h-100 animated-scaleX">
        <img src="{{ asset('assets/images/dashboard/top-header5.png') }}" alt="header" class="theme-color-pink-img img-fluid w-100 h-100 animated-scaleX">
    </div>
</div>