<!-- /.navbar -->
<div class="content-wrapper">
    <!-- SIDEBAR -->
    <aside class="site-sidebar scrollbar-enabled" data-suppress-scroll-x="true">
        <!-- User Details -->
        <div class="side-user d-none">
            <div class="col-sm-12 text-center p-0 clearfix">
                <div class="d-inline-block pos-relative mr-b-10">
                    <figure class="thumb-sm mr-b-0 user--online">
                        <img src="{{ asset('assets/demo/users/user1.jpg') }}" class="rounded-circle" alt="">
                    </figure>
                    <a href="{{ url('page-profile') }}" class="text-muted side-user-link">
                        <i class="feather feather-settings list-icon"></i>
                    </a>
                </div>
                <div class="lh-14 mr-t-5">
                    <a href="{{ url('page-profile') }}" class="hide-menu mt-3 mb-0 side-user-heading fw-500">
                        {{ session('username', 'Hotel Manager') }}
                    </a>
                    <br><small class="hide-menu">Hotel Manager</small>
                </div>
            </div>
        </div>
        <!-- /.side-user -->

        <!-- Sidebar Menu -->
        <nav class="sidebar-nav">
            <ul class="nav in side-menu">
                <li class="current-page menu-item-has-children">
                    <a href="#"><i class="list-icon feather feather-home"></i> <span class="hide-menu">Dashboard</span></a>
                    <ul class="list-unstyled sub-menu">
                        <li><a href="{{ route('dashboard_hotel_manager.dashboard') }}">Dashboard Utama</a></li>
                    </ul>
                </li>

                <li class="menu-item-has-children">
                    <a href="#"><i class="list-icon feather feather-file-text"></i> <span class="hide-menu">Data Laporan</span></a>
                    <ul class="list-unstyled sub-menu">
                        <li><a href="{{ route('dashboard_hotel_manager.reports') }}">Laporan IT </a></li>
                    </ul>
                </li>

                <li class="menu-item-has-children">
                    <a href="#"><i class="list-icon feather feather-shield"></i> <span class="hide-menu">Keamanan</span></a>
                    <ul class="list-unstyled sub-menu">
                        <li><a href="{{ route('dashboard_hotel_manager.alerts') }}">Security Alerts</a></li>
                    </ul>
                </li>

                <li class="menu-item-has-children">
                    <a href="#"><i class="list-icon feather feather-download"></i> <span class="hide-menu">Download</span></a>
                    <ul class="list-unstyled sub-menu">
                        <li><a href="{{ route('dashboard_hotel_manager.download.reports') }}">Download Data Laporan</a></li>
                        <li><a href="{{ route('dashboard_hotel_manager.download.alerts') }}">Download Data Alerts</a></li>
                        <li><a href="{{ route('dashboard_hotel_manager.download.comprehensive') }}">Laporan Komprehensif</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-nav -->
    </aside>
    <!-- /.site-sidebar -->
