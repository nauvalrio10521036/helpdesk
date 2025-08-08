@extends('dashboard_hotel_manager.layout')

@section('page_title', 'Dashboard Hotel Manager')
@section('page_description', 'Statistics, Charts and Reports')
@section('breadcrumb_active', 'Home')

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Reports -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Laporan</h5>
                            <h2 class="text-black">{{ $totalReports }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">assignment</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Devices -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Perangkat</h5>
                            <h2 class="text-black">{{ $totalDevices }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">devices</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Alerts -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Security Alerts</h5>
                            <h2 class="text-black">{{ $totalAlerts }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">security</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Overview Cards -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">assignment_turned_in</i>
                        Status Laporan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-3">
                                <h3 class="text-warning">{{ $pendingReports }}</h3>
                                <p class="text-muted mb-0">Pending</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3">
                                <h3 class="text-info">{{ $inProgressReports }}</h3>
                                <p class="text-muted mb-0">In Progress</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3">
                                <h3 class="text-success">{{ $completedReports }}</h3>
                                <p class="text-muted mb-0">Completed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">trending_up</i>
                        Trend Laporan (30 Hari)
                    </h5>
                    <div class="card-actions">
                        <a href="{{ route('dashboard_hotel_manager.reports') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="reportsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">security</i>
                        Security Alerts (30 Hari)
                    </h5>
                    <div class="card-actions">
                        <a href="{{ route('dashboard_hotel_manager.alerts') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="alertsChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">get_app</i>
                        Download Laporan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('dashboard_hotel_manager.download.reports') }}" class="btn btn-success btn-block mb-3">
                                <i class="material-icons">file_download</i>
                                Download Data Laporan
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('dashboard_hotel_manager.download.alerts') }}" class="btn btn-info btn-block mb-3">
                                <i class="material-icons">file_download</i>
                                Download Security Alerts
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('dashboard_hotel_manager.download.comprehensive') }}" class="btn btn-primary btn-block mb-3">
                                <i class="material-icons">assessment</i>
                                Laporan Komprehensif
                            </a>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="material-icons">info</i>
                        Laporan dalam format CSV yang dapat dibuka dengan Excel.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Reports Chart
    var reportsCtx = document.getElementById('reportsChart').getContext('2d');
    var reportsChart = new Chart(reportsCtx, {
        type: 'line',
        data: @json($reportsChartData),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });

    // Devices Chart
    var devicesCtx = document.getElementById('devicesChart').getContext('2d');
    var devicesChart = new Chart(devicesCtx, {
        type: 'bar',
        data: @json($devicesChartData),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });

    // Alerts Chart
    var alertsCtx = document.getElementById('alertsChart').getContext('2d');
    var alertsChart = new Chart(alertsCtx, {
        type: 'line',
        data: @json($alertsChartData),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }]
            }
        }
    });
</script>
@endpush
