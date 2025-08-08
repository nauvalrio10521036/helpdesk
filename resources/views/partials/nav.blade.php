<!-- HEADER & TOP NAVIGATION -->
<nav class="navbar">
    <!-- Logo Area -->
    <div class="navbar-header">
        <a class="navbar-brand" href="#">
            <img class="logo-expand" alt="" src="{{ asset('assets/img/logo-dark.png') }}">
            <img class="logo-collapse" alt="" src="{{ asset('assets/img/logo-collapse.png') }}">
        </a>
    </div>
    <!-- /.navbar-header -->

    <!-- Left Menu & Sidebar Toggle -->
    <ul class="nav navbar-nav">
        <li class="sidebar-toggle dropdown">
            <a href="javascript:void(0)" class="ripple">
                <i class="feather feather-menu list-icon fs-20"></i>
            </a>
        </li>
    </ul>

    <div class="spacer"></div>

    <!-- Right Menu -->
    <ul class="nav navbar-nav d-none d-lg-flex ml-2 ml-0-rtl">
        <!-- User Info Display -->
        <li class="nav-item d-flex align-items-center">
            <span class="text-dark mr-3">
                <strong>{{ Auth::user()->username }}</strong>
                <small class="d-block text-muted">
                    @php
                        $userRole = Auth::user()->role;
                        switch($userRole) {
                            case 'resepsionis':
                                echo 'Resepsionis';
                                break;
                            case 'it_manager':
                                echo 'IT Manager';
                                break;
                            case 'hotel_manager':
                                echo 'Hotel Manager';
                                break;
                            default:
                                echo 'Unknown Role';
                        }
                    @endphp
                </small>
            </span>
        </li>
    </ul>

    <!-- User Dropdown -->
    <ul class="nav navbar-nav">
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle dropdown-toggle-user ripple" data-toggle="dropdown">
                <span class="avatar thumb-xs2">
                    <img src="{{ asset('assets/demo/users/user-image.png') }}" class="rounded-circle" alt="User Avatar">
                    <i class="feather feather-chevron-down list-icon"></i>
                </span>
            </a>
            <div class="dropdown-menu dropdown-left dropdown-card dropdown-card-profile animated flipInY">
                <div class="card">
                    <header class="card-header text-center mb-0">
                        <h6 class="mb-0">{{ Auth::user()->username }}</h6>
                        {{-- <h6 class="mb-0">{{ session('username') ?? 'Guest' }}</h6> --}}
                        <small class="text-muted">
                            @php
                                $userRole = Auth::user()->role;
                                switch($userRole) {
                                    case 'resepsionis':
                                        echo 'Resepsionis';
                                        break;
                                    case 'it_manager':
                                        echo 'IT Manager';
                                        break;
                                    case 'hotel_manager':
                                        echo 'Hotel Manager';
                                        break;
                                    default:
                                        echo 'Unknown Role';
                                }
                            @endphp
                        </small>
                    </header>
                    <ul class="list-unstyled card-body">
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-link text-left w-100 p-0" style="text-decoration: none;">
                                    <i class="feather feather-power align-middle mr-2"></i>
                                    <span class="align-middle">Sign Out</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</nav>
