<!DOCTYPE html>
<html lang="en">
@include('partials.header')

<body class="sidebar-dark sidebar-expand navbar-brand-dark header-light">
    <div id="wrapper" class="wrapper">
        @include('partials.nav')
        @include('partials.sidebar-hotel-manager')
        
        <main class="main-wrapper clearfix">
            <!-- Page Title Area -->
            <div class="row page-title clearfix">
                <div class="page-title-left">
                    <h6 class="page-title-heading mr-0 mr-r-5">@yield('page_title', 'Hotel Manager Dashboard')</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">@yield('page_description', 'Monitoring & Reporting Dashboard')</p>
                </div>
                <!-- /.page-title-left -->
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_hotel_manager.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">@yield('breadcrumb_active', 'Home')</li>
                    </ol>
                </div>
                <!-- /.page-title-right -->
            </div>

            @yield('content')
        </main>
        <!-- /.main-wrapper -->
    </div>
    <!-- /.wrapper -->

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    @include('partials.footer')
</body>
</html>
