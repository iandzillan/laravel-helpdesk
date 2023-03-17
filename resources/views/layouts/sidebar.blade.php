<aside class="sidebar sidebar-default navs-rounded-all">

    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="#" class="navbar-brand">
            <i class="fa-solid fa-screwdriver-wrench"></i>

            <h4 class="logo-title">Helpdesk</h4>
        </a>

        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>

    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list">
            {{-- Sidebar menu start --}}
            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#" tabindex="-1">
                        <span class="default-icon">Home</span>
                        <span class="mini-icon">-</span>
                    </a>
                </li>

                <li class="nav-item">
                @if (Auth::user()->role == 'Admin')
                    <a class="nav-link {{(request()->segment(2) == 'dashboard') ? 'active' : ''}}" aria-current="page" href="{{route('admin.dashboard')}}">
                @endif
                @if (Auth::user()->role == 'Approver1')
                    <a class="nav-link {{(request()->segment(2) == 'dashboard') ? 'active' : ''}}" aria-current="page" href="{{route('dept.dashboard')}}">
                @endif
                @if (Auth::user()->role == 'Approver2')
                    <a class="nav-link {{(request()->segment(2) == 'dashboard') ? 'active' : ''}}" aria-current="page" href="{{route('subdept.dashboard')}}">
                @endif
                @if (Auth::user()->role == 'User')
                    <a class="nav-link {{(request()->segment(2) == 'dashboard') ? 'active' : ''}}" aria-current="page" href="{{route('user.dashboard')}}">
                @endif
                @if (Auth::user()->role == 'Technician')
                    <a class="nav-link {{(request()->segment(2) == 'dashboard') ? 'active' : ''}}" aria-current="page" href="{{route('technician.dashboard')}}">
                @endif
                        <i class="icon">
                            <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.4" d="M16.0756 2H19.4616C20.8639 2 22.0001 3.14585 22.0001 4.55996V7.97452C22.0001 9.38864 20.8639 10.5345 19.4616 10.5345H16.0756C14.6734 10.5345 13.5371 9.38864 13.5371 7.97452V4.55996C13.5371 3.14585 14.6734 2 16.0756 2Z" fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.53852 2H7.92449C9.32676 2 10.463 3.14585 10.463 4.55996V7.97452C10.463 9.38864 9.32676 10.5345 7.92449 10.5345H4.53852C3.13626 10.5345 2 9.38864 2 7.97452V4.55996C2 3.14585 3.13626 2 4.53852 2ZM4.53852 13.4655H7.92449C9.32676 13.4655 10.463 14.6114 10.463 16.0255V19.44C10.463 20.8532 9.32676 22 7.92449 22H4.53852C3.13626 22 2 20.8532 2 19.44V16.0255C2 14.6114 3.13626 13.4655 4.53852 13.4655ZM19.4615 13.4655H16.0755C14.6732 13.4655 13.537 14.6114 13.537 16.0255V19.44C13.537 20.8532 14.6732 22 16.0755 22H19.4615C20.8637 22 22 20.8532 22 19.44V16.0255C22 14.6114 20.8637 13.4655 19.4615 13.4655Z" fill="currentColor"></path>
                            </svg>
                        </i>
                        <span class="item-name">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                @if (Auth::user()->role == 'Admin')
                    <a class="nav-link {{(request()->segment(2) == 'all-tickets') ? 'active' : ''}}" aria-current="page" href="#">
                @endif
                @if (Auth::user()->role == 'Approver1')
                    <a class="nav-link {{(request()->segment(2) == 'all-tickets') ? 'active' : ''}}" aria-current="page" href="#">
                @endif
                @if (Auth::user()->role == 'Approver2')
                    <a class="nav-link {{(request()->segment(2) == 'all-tickets') ? 'active' : ''}}" aria-current="page" href="#">
                @endif
                @if (Auth::user()->role == 'User')
                    <a class="nav-link {{(request()->segment(2) == 'all-tickets') ? 'active' : ''}}" aria-current="page" href="#">
                @endif
                @if (Auth::user()->role == 'Technician')
                    <a class="nav-link {{(request()->segment(2) == 'all-tickets') ? 'active' : ''}}" aria-current="page" href="#">
                @endif
                        <i class="icon">
                            <i class="fa-solid fa-ticket"></i>
                        </i>
                        <span class="item-name">All Tickets</span>
                    </a>
                </li>

                <li class="nav-item">
                @if (Auth::user()->role == 'Admin')
                    <a class="nav-link {{(request()->segment(2) == 'new-tickets') ? 'active' : ''}}" aria-current="page" href="#">
                @endif
                @if (Auth::user()->role == 'Approver1')
                    <a class="nav-link {{(request()->segment(2) == 'new-tickets') ? 'active' : ''}}" aria-current="page" href="#">
                @endif
                @if (Auth::user()->role == 'Approver2')
                    <a class="nav-link {{(request()->segment(2) == 'new-tickets') ? 'active' : ''}}" aria-current="page" href="#">
                @endif
                @if (Auth::user()->role == 'User')
                    <a class="nav-link {{(request()->segment(2) == 'new-tickets') ? 'active' : ''}}" aria-current="page" href="#">
                @endif
                @if (Auth::user()->role == 'Technician')
                    <a class="nav-link {{(request()->segment(2) == 'new-tickets') ? 'active' : ''}}" aria-current="page" href="#">
                @endif
                        <i class="icon">
                            <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        </i>
                        <span class="item-name">New Tickets</span>
                    </a>
                </li>

                @if (Auth::user()->role == 'Admin')
                <li class="nav-item">
                    <a class="nav-link {{(request()->segment(2) == 'unassigned-tickets') ? 'active' : ''}}" aria-current="page" href="#" title="Unassigned Ticket">
                        <i class="icon">
                            <i class="fa-solid fa-list"></i>
                        </i>
                        <span class="item-name">Unassigned Tickets</span>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                @if (Auth::user()->role == 'Admin')
                    <a class="nav-link {{(request()->segment(2) == 'onwork-tickets') ? 'active' : ''}}" aria-current="page" href="#" title="On Work Ticket">
                @endif
                @if (Auth::user()->role == 'Approver1')
                    <a class="nav-link {{(request()->segment(2) == 'onwork-tickets') ? 'active' : ''}}" aria-current="page" href="#" title="On Work Ticket">
                @endif
                @if (Auth::user()->role == 'Approver2')
                    <a class="nav-link {{(request()->segment(2) == 'onwork-tickets') ? 'active' : ''}}" aria-current="page" href="#" title="On Work Ticket">
                @endif
                        <i class="icon">
                            <i class="fa-solid fa-hammer"></i>
                        </i>
                        <span class="item-name">On Work Tickets</span>
                    </a>
                </li>

                <li class="nav-item">
                @if (Auth::user()->role == 'Admin')
                    <a class="nav-link {{(request()->segment(2) == 'complete-tickets') ? 'active' : ''}}" aria-current="page" href="#" title="Completed Ticket">
                @endif
                @if (Auth::user()->role == 'Approver1')
                    <a class="nav-link {{(request()->segment(2) == 'complete-tickets') ? 'active' : ''}}" aria-current="page" href="#" title="Completed Ticket">
                @endif
                @if (Auth::user()->role == 'Approver2')
                    <a class="nav-link {{(request()->segment(2) == 'complete-tickets') ? 'active' : ''}}" aria-current="page" href="#" title="Completed Ticket">
                @endif
                        <i class="icon">
                            <i class="fa-solid fa-circle-check"></i>
                        </i>
                        <span class="item-name">Completed Tickets</span>
                    </a>
                </li>

                <li class="nav-item">
                @if (Auth::user()->role == 'Admin')
                    <a class="nav-link {{(request()->segment(2) == 'reject-tickets') ? 'active' : ''}}" aria-current="page" href="#" title="Rejected Ticket">
                @endif
                @if (Auth::user()->role == 'Approver1')
                    <a class="nav-link {{(request()->segment(2) == 'reject-tickets') ? 'active' : ''}}" aria-current="page" href="#" title="Rejected Ticket">
                @endif
                @if (Auth::user()->role == 'Approver2')
                    <a class="nav-link {{(request()->segment(2) == 'reject-tickets') ? 'active' : ''}}" aria-current="page" href="#" title="Rejected Ticket">
                @endif
                        <i class="icon">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </i>
                        <span class="item-name">Rejected Tickets</span>
                    </a>
                </li>
                
                @if (Auth::user()->role == 'Admin')
                    <li><hr class="hr-horizontal"></li>
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">Ticket Management</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'categories') ? 'active' : ''}}" aria-current="page" href="{{route('admin.categories')}}">
                            <i class="icon">
                                <i class="fa-solid fa-folder"></i>
                            </i>
                            <span class="item-name">Categories</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'sub-categories') ? 'active' : ''}}" aria-current="page" href="{{route('admin.subcategories')}}">
                            <i class="icon">
                                <i class="fa-solid fa-folder-tree"></i>
                            </i>
                            <span class="item-name">Sub Categories</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'urgencies') ? 'active' : ''}}" aria-current="page" href="{{route('admin.urgencies')}}">
                            <i class="icon">
                                <i class="fa-solid fa-layer-group"></i>
                            </i>
                            <span class="item-name">Urgencies</span>
                        </a>
                    </li>

                    <li><hr class="hr-horizontal" /></li>
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">Personel Management</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'users') ? 'active' : ''}}" aria-current="page" href="{{route('admin.users')}}">
                            <i class="icon">
                                <i class="fa-solid fa-user"></i>
                            </i>
                            <span class="item-name">Users</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'departments') ? 'active' : ''}}" aria-current="page" href="{{route('admin.departments')}}">
                            <i class="icon">
                                <i class="fa-solid fa-building"></i>
                            </i>
                            <span class="item-name">Departments</span>
                        </a>
                    </li>
                    <br>
                @endif

                @if (Auth::user()->role == 'Approver1')
                    <li><hr class="hr-horizontal"></li>
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">Personel Management</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'employee') ? 'active' : ''}}" aria-current="page" href="{{ route('dept.employees.new') }}">
                            <i class="icon">
                                <i class="fa-solid fa-address-card"></i>
                            </i>
                            <span class="item-name">Add employee</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'employees') ? 'active' : ''}}" aria-current="page" href="{{ route('dept.employees.list') }}">
                            <i class="icon">
                                <i class="fa-solid fa-id-card-clip"></i>
                            </i>
                            <span class="item-name">Employees List</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'user-request') ? 'active' : ''}}" aria-current="page" href="{{ route('dept.userrequestlist') }}">
                            <i class="icon">
                                <i class="fa-solid fa-chalkboard-user"></i>
                            </i>
                            <span class="item-name">Account Request</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'sub-departments') ? 'active' : ''}}" aria-current="page" href="{{route('dept.subdepartments')}}">
                            <i class="icon">
                                <i class="fa-solid fa-code-fork"></i>
                            </i>
                            <span class="item-name">Sub Departments</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'positions') ? 'active' : ''}}" aria-current="page" href="{{ route('dept.positions') }}">
                            <i class="icon">
                                <i class="fa-solid fa-sitemap"></i>
                            </i>
                            <span class="item-name">Positions</span>
                        </a>
                    </li>
                    <br>
                @endif

                @if (Auth::user()->role == 'Approver2')
                    <li><hr class="hr-horizontal" /></li>
                    <li class="nav-item static-item">
                        <a class="nav-link static-item disabled" href="#" tabindex="-1">
                            <span class="default-icon">Employee Master Data</span>
                            <span class="mini-icon">-</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'new-employee') ? 'active' : ''}}" aria-current="page" href="{{ route('subdept.employees.index') }}">
                            <i class="icon">
                                <i class="fa-solid fa-address-card"></i>
                            </i>
                            <span class="item-name">Add employees</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'employees') ? 'active' : ''}}" aria-current="page" href="{{ route('subdept.employees.list') }}">
                            <i class="icon">
                                <i class="fa-solid fa-id-card-clip"></i>
                            </i>
                            <span class="item-name">Employees List</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'user-request') ? 'active' : ''}}" aria-current="page" href="{{ route('subdept.userrequestlist') }}">
                            <i class="icon">
                                <i class="fa-solid fa-chalkboard-user"></i>
                            </i>
                            <span class="item-name">Account Request</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{(request()->segment(2) == 'positions') ? 'active' : ''}}" aria-current="page" href="{{ route('subdept.positions') }}">
                            <i class="icon">
                                <i class="fa-solid fa-sitemap"></i>
                            </i>
                            <span class="item-name">Positions</span>
                        </a>
                    </li>
                    <br>
                @endif
            </ul>
            {{-- Sidebar menu end --}}
        </div>
    </div>
</aside>