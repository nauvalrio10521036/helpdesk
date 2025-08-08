<!DOCTYPE html>
<html lang="en">
@include('partials.header')

<body class="sidebar-dark sidebar-expand navbar-brand-dark header-light">
    <div id="wrapper" class="wrapper">
        @include('partials.nav')
        @include('partials.sidebar')
        <main class="main-wrapper clearfix">
            <div class="row page-title clearfix">
                <div class="page-title-left">
                    <h6 class="page-title-heading mr-0 mr-r-5">Laporan Keamanan Jaringan</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">
                        {{ ucfirst($report['report_type']) }} Security Report - {{ $report['period'] ?? $report['date'] }}
                    </p>
                </div>
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('suricata.index') }}">Monitoring</a></li>
                        <li class="breadcrumb-item active">Laporan Keamanan</li>
                    </ol>
                </div>
            </div>

            <!-- Report Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="widget-bg">
                        <div class="widget-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h4 class="mb-1">Laporan {{ ucfirst($report['report_type']) }} Keamanan Jaringan</h4>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-calendar"></i> Generated: {{ $report['generated_at']->format('d M Y, H:i:s') }} WIB
                                        <br>
                                        <i class="fas fa-clock"></i> Period: {{ $report['period'] ?? $report['date'] }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button class="btn btn-primary" onclick="window.print()">
                                        <i class="fas fa-print"></i> Print Report
                                    </button>
                                    <button class="btn btn-success" onclick="exportToPDF()">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Executive Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5><i class="fas fa-chart-pie"></i> Executive Summary</h5>
                        </div>
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="alert alert-{{ $report['executive_summary']['status_color'] }}">
                                        <h6><i class="fas fa-shield-alt"></i> Security Status: {{ ucfirst($report['executive_summary']['status']) }}</h6>
                                        <p class="mb-2">{{ $report['executive_summary']['status_message'] }}</p>
                                        
                                        @if(!empty($report['executive_summary']['key_points']))
                                            <strong>Key Points:</strong>
                                            <ul class="mb-0">
                                                @foreach($report['executive_summary']['key_points'] as $point)
                                                    <li>{{ $point }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border-right">
                                                <h3 class="text-danger">{{ $report['executive_summary']['total_alerts'] }}</h3>
                                                <small class="text-muted">Total Alerts</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h3 class="text-{{ $report['executive_summary']['risk_level'] === 'Critical' ? 'danger' : ($report['executive_summary']['risk_level'] === 'High' ? 'warning' : 'success') }}">
                                                {{ $report['executive_summary']['risk_level'] }}
                                            </h3>
                                            <small class="text-muted">Risk Level</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Metrics -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="widget-bg card border-left-danger">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">High Priority</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $report['detailed_analysis']['summary']['high_priority'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="widget-bg card border-left-warning">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Medium Priority</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $report['detailed_analysis']['summary']['medium_priority'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-circle fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="widget-bg card border-left-info">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Low Priority</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $report['detailed_analysis']['summary']['low_priority'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-info-circle fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="widget-bg card border-left-success">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Avg Per Day</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $report['detailed_analysis']['summary']['average_per_day'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5>Threat Categories Analysis</h5>
                        </div>
                        <div class="widget-body">
                            <canvas id="threatCategoriesChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5>Protocol Distribution</h5>
                        </div>
                        <div class="widget-body">
                            <canvas id="protocolChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Attackers and Targets -->
            <div class="row mb-4">
                <div class="col-lg-6">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5>Top 10 Attacking IPs</h5>
                        </div>
                        <div class="widget-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>IP Address</th>
                                            <th>Attack Count</th>
                                            <th>Avg Severity</th>
                                            <th>Duration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report['detailed_analysis']['threat_analysis']['top_attackers'] as $attacker)
                                            <tr>
                                                <td><code>{{ $attacker['ip'] }}</code></td>
                                                <td><span class="badge badge-danger">{{ $attacker['count'] }}</span></td>
                                                <td>{{ $attacker['severity_avg'] }}</td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($attacker['first_seen'])->diffForHumans(\Carbon\Carbon::parse($attacker['last_seen'])) }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No attacking IPs detected</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5>Top 10 Targeted IPs</h5>
                        </div>
                        <div class="widget-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>IP Address</th>
                                            <th>Attack Count</th>
                                            <th>Avg Severity</th>
                                            <th>Attack Types</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($report['detailed_analysis']['threat_analysis']['top_targets'] as $target)
                                            <tr>
                                                <td><code>{{ $target['ip'] }}</code></td>
                                                <td><span class="badge badge-warning">{{ $target['count'] }}</span></td>
                                                <td>{{ $target['severity_avg'] }}</td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ count($target['attack_types']) }} types
                                                    </small>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No targeted IPs detected</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Risk Assessment -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5><i class="fas fa-shield-alt"></i> Risk Assessment</h5>
                        </div>
                        <div class="widget-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="risk-score-circle mb-3">
                                            <h2 class="text-{{ $report['detailed_analysis']['risk_assessment']['risk_color'] }}">
                                                {{ $report['detailed_analysis']['risk_assessment']['risk_score'] }}/100
                                            </h2>
                                            <small class="text-muted">Risk Score</small>
                                        </div>
                                        <h5 class="text-{{ $report['detailed_analysis']['risk_assessment']['risk_color'] }}">
                                            {{ $report['detailed_analysis']['risk_assessment']['risk_level'] }} Risk
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h6>Risk Factors:</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <small class="text-muted">High Severity Alerts (24h)</small>
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-danger" style="width: {{ min(100, ($report['detailed_analysis']['risk_assessment']['factors']['high_severity_alerts'] / 20) * 100) }}%"></div>
                                                </div>
                                                <small>{{ $report['detailed_analysis']['risk_assessment']['factors']['high_severity_alerts'] }}</small>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Total Recent Alerts</small>
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-warning" style="width: {{ min(100, ($report['detailed_analysis']['risk_assessment']['factors']['total_recent_alerts'] / 100) * 100) }}%"></div>
                                                </div>
                                                <small>{{ $report['detailed_analysis']['risk_assessment']['factors']['total_recent_alerts'] }}</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-2">
                                                <small class="text-muted">Unique Attackers</small>
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-info" style="width: {{ min(100, ($report['detailed_analysis']['risk_assessment']['factors']['unique_attackers'] / 20) * 100) }}%"></div>
                                                </div>
                                                <small>{{ $report['detailed_analysis']['risk_assessment']['factors']['unique_attackers'] }}</small>
                                            </div>
                                            <div class="mb-2">
                                                <small class="text-muted">Unique Targets</small>
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-success" style="width: {{ min(100, ($report['detailed_analysis']['risk_assessment']['factors']['unique_targets'] / 10) * 100) }}%"></div>
                                                </div>
                                                <small>{{ $report['detailed_analysis']['risk_assessment']['factors']['unique_targets'] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Items -->
            @if(!empty($report['action_items']))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5><i class="fas fa-tasks"></i> Action Items</h5>
                        </div>
                        <div class="widget-body">
                            @foreach($report['action_items'] as $action)
                                <div class="alert alert-{{ $action['priority'] === 'critical' ? 'danger' : ($action['priority'] === 'high' ? 'warning' : 'info') }}">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6>
                                                <span class="badge badge-{{ $action['priority'] === 'critical' ? 'danger' : ($action['priority'] === 'high' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($action['priority']) }}
                                                </span>
                                                {{ $action['title'] }}
                                            </h6>
                                            <p class="mb-1">{{ $action['description'] }}</p>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <small class="text-muted">
                                                <strong>Deadline:</strong> {{ $action['deadline']->format('d M Y, H:i') }}<br>
                                                <strong>Assigned:</strong> {{ $action['assigned_to'] }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recommendations -->
            @if(!empty($report['detailed_analysis']['recommendations']))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5><i class="fas fa-lightbulb"></i> Security Recommendations</h5>
                        </div>
                        <div class="widget-body">
                            <div class="row">
                                @foreach($report['detailed_analysis']['recommendations'] as $recommendation)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border-left-primary h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $recommendation['title'] }}</h6>
                                                <p class="card-text text-sm">{{ $recommendation['description'] }}</p>
                                                <div class="mt-auto">
                                                    <span class="badge badge-primary">{{ ucfirst($recommendation['type']) }}</span>
                                                    <small class="text-muted d-block mt-1">
                                                        <strong>Action:</strong> {{ $recommendation['action'] }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </main>
        @include('partials.footer')
    </div>

    <!-- Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Threat Categories Chart
        const threatCtx = document.getElementById('threatCategoriesChart').getContext('2d');
        new Chart(threatCtx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($report['detailed_analysis']['threat_analysis']['threat_categories'])),
                datasets: [{
                    label: 'Threat Count',
                    data: @json(array_values($report['detailed_analysis']['threat_analysis']['threat_categories'])),
                    backgroundColor: [
                        '#dc3545', '#fd7e14', '#ffc107', '#28a745', '#17a2b8'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Protocol Chart
        const protocolCtx = document.getElementById('protocolChart').getContext('2d');
        new Chart(protocolCtx, {
            type: 'doughnut',
            data: {
                labels: @json(array_column($report['detailed_analysis']['threat_analysis']['protocol_distribution'], 'protocol')),
                datasets: [{
                    data: @json(array_column($report['detailed_analysis']['threat_analysis']['protocol_distribution'], 'count')),
                    backgroundColor: [
                        '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        function exportToPDF() {
            alert('PDF export functionality would be implemented here');
        }
    </script>

    <style>
        .risk-score-circle {
            width: 120px;
            height: 120px;
            border: 4px solid #e9ecef;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        @media print {
            .widget-actions,
            .breadcrumb,
            .sidebar,
            .nav {
                display: none !important;
            }
        }
    </style>
</body>
</html>
