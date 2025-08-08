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
                    <a href="{{ url('page-profile') }}" class="hide-menu mt-3 mb-0 side-user-heading fw-500">Scott Adams</a>
                    <br><small class="hide-menu">Developer</small>
                </div>
            </div>
        </div>
        <!-- /.side-user -->

        <!-- Sidebar Menu -->
        <nav class="sidebar-nav">
            <ul class="nav in side-menu">
                <li class="current-page menu-item-has-children">
                    <a href="#"><i class="list-icon feather feather-command"></i> <span class="hide-menu">Dashboard</span></a>
                    <ul class="list-unstyled sub-menu">
                        <li><a href="{{ route('dashboard_it_manager.dashboard') }}">Statistics, Charts and Events</a></li>
                    </ul>
                </li>

                <li class="current-page menu-item-has-children">
                    <a href="#"><i class="list-icon feather feather-command"></i> <span class="hide-menu">Pemantauan</span></a>
                    <ul class="list-unstyled sub-menu">
                        <li><a href="{{ route('dashboard_it_manager.view-monitoring-suricata') }}">Monitoring Keamanan</a></li>
                    </ul>
                </li>

                <li class="current-page menu-item-has-children">
                    <a href="#"><i class="list-icon feather feather-command"></i> <span class="hide-menu">Laporan</span></a>
                    <ul class="list-unstyled sub-menu">
                        <li><a href="{{ route('dashboard_it_manager.view-report') }}">Laporan Gangguan Jaringan</a></li>
                    </ul>
                </li>
{{-- 
                <li class="current-page menu-item-has-children">
                    <a href="#"><i class="list-icon feather feather-command"></i> <span class="hide-menu">Data Perangkat</span></a>
                    <ul class="list-unstyled sub-menu">
                        <li><a href="{{ route('dashboard_it_manager.view-data-perangkat') }}">Perangkat Jaringan</a></li>
                        <li><a href="{{ route('dashboard_it_manager.view-vlan') }}">Vlan</a></li>
                    </ul>
                </li> --}}

                <li class="current-page menu-item-has-children">
                    <a href="#"><i class="list-icon feather feather-command"></i> <span class="hide-menu">Pengguna</span></a>
                    <ul class="list-unstyled sub-menu">
                        <li><a href="{{ route('dashboard_it_manager.view-user') }}">Daftar Pengguna</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-nav -->
    </aside>
    <!-- /.site-sidebar -->
