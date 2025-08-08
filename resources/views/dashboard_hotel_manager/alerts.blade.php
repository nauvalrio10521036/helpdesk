@extends('dashboard_hotel_manager.layout')

@section('page_title', 'Security Alerts & Monitoring')
@section('page_description', 'Real-time Security Monitoring & Threat Analysis')
@section('breadcrumb_active', 'Security Alerts')

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <!-- High Priority Alerts -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">High Priority</h5>
                            <h2 class="text-black">{{ $alerts->where('priority', 1)->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">warning</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medium Priority Alerts -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Medium Priority</h5>
                            <h2 class="text-black">{{ $alerts->where('priority', 2)->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">error_outline</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Priority Alerts -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Low Priority</h5>
                            <h2 class="text-black">{{ $alerts->where('priority', 3)->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">info</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Alerts -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Alerts</h5>
                            <h2 class="text-black">{{ $alerts->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">security</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">timeline</i>
                        Trend Security Alerts (30 Hari Terakhir)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="alertsChart" height="400"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">pie_chart</i>
                        Priority Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Alerts -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">
                <i class="material-icons">notifications_active</i>
                Alerts Terbaru (10 Terakhir)
            </h5>
            <div>
                <span class="badge badge-danger me-2">{{ $alerts->where('priority', 1)->count() }} High</span>
                <span class="badge badge-warning me-2">{{ $alerts->where('priority', 2)->count() }} Medium</span>
                <span class="badge badge-success">{{ $alerts->where('priority', 3)->count() }} Low</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Priority</th>
                            <th>Source IP</th>
                            <th>Destination IP</th>
                            <th>Protocol</th>
                            <th>Message</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alerts->take(10) as $alert)
                        <tr>
                            <td>
                                @if($alert->priority == 1)
                                    <span class="badge badge-danger">High</span>
                                @elseif($alert->priority == 2)
                                    <span class="badge badge-warning">Medium</span>
                                @elseif($alert->priority == 3)
                                    <span class="badge badge-success">Low</span>
                                @else
                                    <span class="badge badge-secondary">Unknown</span>
                                @endif
                            </td>
                            <td><code>{{ $alert->src_ip }}</code></td>
                            <td><code>{{ $alert->dest_ip }}</code></td>
                            <td><span class="badge badge-info">{{ strtoupper($alert->protocol) }}</span></td>
                            <td>{{ Str::limit($alert->message, 60) }}</td>
                            <td>
                                <small class="text-muted">
                                    {{ $alert->created_at ? \Carbon\Carbon::parse($alert->created_at)->format('d/m/Y H:i:s') : '-' }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="material-icons">inbox</i>
                                <br>Tidak ada alerts
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Full Alerts Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">
                <i class="material-icons">list</i>
                Semua Security Alerts
            </h5>
            <a href="{{ route('dashboard_hotel_manager.download.alerts') }}" class="btn btn-success">
                <i class="material-icons">file_download</i>
                Download Excel
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark sticky-top">
                        <tr>
                            <th>ID</th>
                            <th>Priority</th>
                            <th>Source IP</th>
                            <th>Destination IP</th>
                            <th>Protocol</th>
                            <th>Message</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alerts as $alert)
                        <tr>
                            <td><span class="badge badge-secondary">#{{ $alert->id }}</span></td>
                            <td>
                                @if($alert->priority == 1)
                                    <span class="badge badge-danger">High</span>
                                @elseif($alert->priority == 2)
                                    <span class="badge badge-warning">Medium</span>
                                @elseif($alert->priority == 3)
                                    <span class="badge badge-success">Low</span>
                                @else
                                    <span class="badge badge-secondary">Unknown</span>
                                @endif
                            </td>
                            <td><code>{{ $alert->src_ip }}</code></td>
                            <td><code>{{ $alert->dest_ip }}</code></td>
                            <td><span class="badge badge-info">{{ strtoupper($alert->protocol) }}</span></td>
                            <td title="{{ $alert->message }}">{{ Str::limit($alert->message, 80) }}</td>
                            <td>
                                <small class="text-muted">
                                    {{ $alert->created_at ? \Carbon\Carbon::parse($alert->created_at)->format('d/m/Y H:i:s') : '-' }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <i class="material-icons">inbox</i>
                                <br>Tidak ada data alerts
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">
                        <i class="material-icons">public</i>
                        Top Source IPs
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $sourceIPs = $alerts->groupBy('src_ip')->map->count()->sortDesc()->take(5);
                    @endphp
                    @foreach($sourceIPs as $ip => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <code>{{ $ip }}</code>
                        <span class="badge badge-danger">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">
                        <i class="material-icons">router</i>
                        Top Protocols
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $protocols = $alerts->groupBy('protocol')->map->count()->sortDesc()->take(5);
                    @endphp
                    @foreach($protocols as $protocol => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge badge-info">{{ strtoupper($protocol) }}</span>
                        <span class="badge badge-primary">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Security Status Overview -->
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">
                <i class="material-icons">security</i>
                Security Status Overview
            </h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-lg-3 col-md-6">
                    <div class="p-3">
                        <div class="h2 {{ $alerts->where('priority', 1)->count() > 0 ? 'text-danger' : 'text-success' }}">
                            @if($alerts->where('priority', 1)->count() > 0)
                                <i class="material-icons">warning</i>
                            @else
                                <i class="material-icons">security</i>
                            @endif
                        </div>
                        <small class="text-muted">Security Status</small>
                        <div class="mt-2">
                            <small class="{{ $alerts->where('priority', 1)->count() > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $alerts->where('priority', 1)->count() > 0 ? 'Needs Attention' : 'Secure' }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="p-3">
                        <div class="h2 text-info">
                            {{ $alerts->where('created_at', '>=', \Carbon\Carbon::today())->count() }}
                        </div>
                        <small class="text-muted">Alerts Hari Ini</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="p-3">
                        <div class="h2 text-warning">
                            {{ $alerts->where('created_at', '>=', \Carbon\Carbon::now()->subHour())->count() }}
                        </div>
                        <small class="text-muted">Alerts 1 Jam Terakhir</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="p-3">
                        <div class="h2 text-primary">
                            {{ $alerts->unique('src_ip')->count() }}
                        </div>
                        <small class="text-muted">Unique Source IPs</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Alerts Trend Chart
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

    // Priority Distribution Chart
    var priorityCtx = document.getElementById('priorityChart').getContext('2d');
    var priorityChart = new Chart(priorityCtx, {
        type: 'doughnut',
        data: {
            labels: ['High Priority', 'Medium Priority', 'Low Priority'],
            datasets: [{
                data: [
                    {{ $alerts->where('priority', 1)->count() }},
                    {{ $alerts->where('priority', 2)->count() }},
                    {{ $alerts->where('priority', 3)->count() }}
                ],
                backgroundColor: ['#dc3545', '#ffc107', '#28a745'],
                borderWidth: 0
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

    // Auto refresh every 30 seconds for real-time monitoring
    setInterval(function() {
        location.reload();
    }, 30000);
</script>
@endpush
