<!DOCTYPE html>
<html lang="en">
@include('partials.header')

<body class="sidebar-dark sidebar-expand navbar-brand-dark header-light">
    <div id="wrapper" class="wrapper">
@include('partials.nav')
@include('partials.sidebar')
        <main class="main-wrapper clearfix">
            <!-- Page Title Area -->
            <div class="row page-title clearfix">
                <div class="page-title-left">
                    <h6 class="page-title-heading mr-0 mr-r-5">Dashboard IT Manager</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Statistics, Charts and Events</p>
                </div>
                <!-- /.page-title-left -->
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_it_manager.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
                <!-- /.page-title-right -->
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                

                <!-- Total Devices -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card text-white bg-gradient-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Total Devices</h5>
                                    <h2 class="text-white">{{ $totalDevices }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons md-48">devices</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Reports -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card text-white bg-gradient-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Reports</h5>
                                    <h2 class="text-white">{{ $totalReports }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons md-48">report_problem</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Alerts -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card text-white bg-gradient-danger">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Security Alerts</h5>
                                    <h2 class="text-white">{{ $totalAlerts }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons md-48">security</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Statistics Row -->
            <div class="row mt-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card border-left-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Users</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons text-info">people</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card border-left-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total VLANs</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalVlans }}</div>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons text-success">settings_ethernet</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Charts Section -->
            <div class="row mt-4">
                <!-- Device Status Chart -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Device Status Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="deviceStatusChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Device Types Chart -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Device Types Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="deviceTypesChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Reports Status Chart -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Reports by Status</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="reportsStatusChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Monthly Trends Chart -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Monthly Trends ({{ date('Y') }})</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="monthlyTrendsChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <!-- Security Alerts by Priority -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Security Alerts by Priority</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="alertsPriorityChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- VLAN Utilization -->
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">VLAN Utilization</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="vlanUtilizationChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Events Section -->
            <div class="row mt-4">
                <!-- Recent Reports -->
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Recent Reports</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            @forelse($recentReports as $report)
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="mr-3">
                                    <span class="badge 
                                        @if($report->prioritas == 'high') badge-danger
                                        @elseif($report->prioritas == 'medium') badge-warning
                                        @else badge-info
                                        @endif">
                                        {{ ucfirst($report->prioritas) }}
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($report->title, 30) }}</h6>
                                    <p class="text-muted mb-1">{{ Str::limit($report->description, 50) }}</p>
                                    <small class="text-muted">
                                        by {{ $report->user->name ?? 'Unknown' }} - 
                                        {{ Carbon\Carbon::parse($report->time_report)->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted text-center">No recent reports</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Security Alerts -->
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Recent Security Alerts</h5>
                        </div>
                        <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                            @forelse($recentAlerts as $alert)
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="mr-3">
                                    <span class="badge 
                                        @if($alert->priority == 'high') badge-danger
                                        @elseif($alert->priority == 'medium') badge-warning
                                        @else badge-info
                                        @endif">
                                        {{ ucfirst($alert->priority) }}
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $alert->protocol }} Alert</h6>
                                    <p class="text-muted mb-1">{{ Str::limit($alert->message, 50) }}</p>
                                    <small class="text-muted">
                                        {{ $alert->src_ip }} → {{ $alert->dest_ip }} - 
                                        {{ $alert->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted text-center">No recent security alerts</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </main>
    <!-- /.content-wrapper -->
    
</body>
@include('partials.footer')

<!-- Custom Dashboard Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Device Status Chart
    const deviceStatusCtx = document.getElementById('deviceStatusChart').getContext('2d');
    new Chart(deviceStatusCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($devicesByStatus->pluck('status')) !!},
            datasets: [{
                data: {!! json_encode($devicesByStatus->pluck('total')) !!},
                backgroundColor: ['#28a745', '#dc3545', '#ffc107', '#6c757d'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });

    // Device Types Chart
    const deviceTypesCtx = document.getElementById('deviceTypesChart').getContext('2d');
    new Chart(deviceTypesCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($devicesByType->pluck('tipe')) !!},
            datasets: [{
                data: {!! json_encode($devicesByType->pluck('total')) !!},
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6610f2', '#fd7e14'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });

    // Reports Status Chart
    const reportsStatusCtx = document.getElementById('reportsStatusChart').getContext('2d');
    new Chart(reportsStatusCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($reportsByStatus->pluck('status')) !!},
            datasets: [{
                label: 'Reports',
                data: {!! json_encode($reportsByStatus->pluck('total')) !!},
                backgroundColor: ['#17a2b8', '#ffc107', '#28a745', '#dc3545'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Monthly Trends Chart
    const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    // Prepare monthly data
    const reportsData = Array(12).fill(0);
    const alertsData = Array(12).fill(0);
    
    @foreach($monthlyReports as $report)
        reportsData[{{ $report->month - 1 }}] = {{ $report->total }};
    @endforeach
    
    @foreach($monthlyAlerts as $alert)
        alertsData[{{ $alert->month - 1 }}] = {{ $alert->total }};
    @endforeach

    new Chart(monthlyTrendsCtx, {
        type: 'line',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Reports',
                data: reportsData,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: false
            }, {
                label: 'Security Alerts',
                data: alertsData,
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // Security Alerts by Priority Chart
    const alertsPriorityCtx = document.getElementById('alertsPriorityChart').getContext('2d');
    new Chart(alertsPriorityCtx, {
        type: 'horizontalBar',
        data: {
            labels: {!! json_encode($alertsByPriority->pluck('priority')) !!},
            datasets: [{
                label: 'Alerts',
                data: {!! json_encode($alertsByPriority->pluck('total')) !!},
                backgroundColor: ['#dc3545', '#ffc107', '#28a745'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    // VLAN Utilization Chart
    const vlanUtilizationCtx = document.getElementById('vlanUtilizationChart').getContext('2d');
    new Chart(vlanUtilizationCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($vlanUtilization->pluck('name_vlan')) !!},
            datasets: [{
                label: 'Devices',
                data: {!! json_encode($vlanUtilization->pluck('devices_count')) !!},
                backgroundColor: '#17a2b8',
                borderColor: '#138496',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
});
</script>

<style>
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.bg-gradient-primary {
    background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(87deg, #fb6340 0, #fbb140 100%) !important;
}

.bg-gradient-danger {
    background: linear-gradient(87deg, #f5365c 0, #f56036 100%) !important;
}

.material-icons.md-48 {
    font-size: 48px;
}

.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    border: 1px solid #e3e6f0 !important;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}
</style>
</html>