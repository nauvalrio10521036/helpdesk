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
                    <h6 class="page-title-heading mr-0 mr-r-5">Sistem Informasi Monitoring Keamanan Jaringan</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">
                        Analisis Komprehensif Data Suricata IDS
                    </p>
                </div>
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Monitoring Keamanan</li>
                    </ol>
                </div>
            </div>

            <!-- Security Summary Cards -->
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="widget-bg card border-left-danger shadow">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Total Alerts
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $securityAnalysis['summary']['total_alerts'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="widget-bg card border-left-warning shadow">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        High Priority
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $securityAnalysis['summary']['high_priority'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shield-alt fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="widget-bg card border-left-info shadow">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Risk Level
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-{{ $securityAnalysis['risk_assessment']['risk_color'] ?? 'success' }}">
                                        {{ $securityAnalysis['risk_assessment']['risk_level'] ?? 'Low' }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5>Distribusi Tingkat Ancaman</h5>
                        </div>
                        <div class="widget-body">
                            <canvas id="severityChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline and Pattern Analysis -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5>Timeline Serangan (24 Jam Terakhir)</h5>
                        </div>
                        <div class="widget-body">
                            <canvas id="hourlyPatternChart" width="400" height="150"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="widget-bg">
                        <div class="widget-heading clearfix">
                            <h5>Top 5 Penyerang</h5>
                        </div>
                        <div class="widget-body">
                            @if(!empty($securityAnalysis['threat_analysis']['top_attackers']))
                                @foreach(collect($securityAnalysis['threat_analysis']['top_attackers'])->take(5) as $attacker)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-sm">{{ $attacker['ip'] }}</span>
                                        <span class="badge badge-danger">{{ $attacker['count'] }} attacks</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Tidak ada data penyerang</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="widget-list">
                <div class="row">
                    <div class="col-md-12 widget-holder">
                        <div class="widget-bg">
                            <div class="widget-heading clearfix">
                                <h5>Data Real-time Monitoring Suricata</h5>
                                <div class="widget-actions">
                                    <a href="{{ route('dashboard_it_manager.view-data-perangkat') }}"
                                       class="btn btn-primary btn-sm">Daftar Perangkat</a>
                                    <button id="refreshData" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                            </div>
                            <div class="widget-body clearfix">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Data di bawah ini merupakan hasil analisis real-time dari Suricata IDS untuk monitoring
                                    keamanan jaringan hotel. Sistem secara otomatis mengkategorikan 
                                    <br><small><strong>Last Update:</strong> {{ $lastUpdate ?? 'Unknown' }}</small>
                                </div>

                                <!-- Filtering Controls -->
                                <div class="row mb-3">
                                    <div class="col-md-2">
                                        <label for="severityFilter" class="form-label">Filter by Severity:</label>
                                        <select id="severityFilter" class="form-control form-control-sm">
                                            <option value="">All Severity</option>
                                            <option value="1">High Priority</option>
                                            <option value="2">Medium Priority</option>
                                            <option value="3">Low Priority</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="protocolFilter" class="form-label">Filter by Protocol:</label>
                                        <select id="protocolFilter" class="form-control form-control-sm">
                                            <option value="">All Protocols</option>
                                            <option value="TCP">TCP</option>
                                            <option value="UDP">UDP</option>
                                            <option value="HTTP">HTTP</option>
                                            <option value="HTTPS">HTTPS</option>
                                            <option value="DNS">DNS</option>
                                            <option value="SSH">SSH</option>
                                            <option value="ICMP">ICMP</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="ipFilter" class="form-label">Filter by IP:</label>
                                        <input type="text" id="ipFilter" class="form-control form-control-sm" placeholder="Enter IP address">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="messageFilter" class="form-label">Search Message:</label>
                                        <input type="text" id="messageFilter" class="form-control form-control-sm" placeholder="Search in messages">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="dateFilter" class="form-label">Filter by Date:</label>
                                        <select id="dateFilter" class="form-control form-control-sm">
                                            <option value="">All Dates</option>
                                            <option value="today">Today</option>
                                            <option value="yesterday">Yesterday</option>
                                            <option value="last7days">Last 7 Days</option>
                                            <option value="thisweek">This Week</option>
                                            <option value="lastweek">Last Week</option>
                                            <option value="thismonth">This Month</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <div>
                                            <button id="clearFilters" class="btn btn-outline-secondary btn-sm d-block mb-1">
                                                <i class="fas fa-times"></i> Clear Filters
                                            </button>
                                            <small id="visibleCounter" class="text-muted">All alerts</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Custom Date Range -->
                                <div class="row mb-3" id="customDateRange" style="display: none;">
                                    <div class="col-md-3">
                                        <label for="startDate" class="form-label">Start Date:</label>
                                        <input type="date" id="startDate" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="endDate" class="form-label">End Date:</label>
                                        <input type="date" id="endDate" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div>
                                            <button id="applyDateRange" class="btn btn-primary btn-sm">
                                                <i class="fas fa-calendar-check"></i> Apply Range
                                            </button>
                                            <button id="showCustomRange" class="btn btn-outline-info btn-sm ml-1">
                                                <i class="fas fa-calendar-alt"></i> Custom Range
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Source IP</th>
                                                <th>Destination IP</th>
                                                <th>Protocol</th>
                                                <th>Severity</th>
                                                <th>Message</th>
                                                <th>Timestamp</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="alertsTableBody">
                                            @forelse($monitoringData as $data)
                                                <tr>
                                                    <td>
                                                        <span class="badge badge-secondary">{{ $data['src_ip'] }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-info">{{ $data['dest_ip'] }}</span>
                                                    </td>
                                                    <td>{{ $data['protocol'] }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $data['severity'] == 1 ? 'danger' : ($data['severity'] == 2 ? 'warning' : 'success') }}">
                                                            {{ $data['severity'] == 1 ? 'High' : ($data['severity'] == 2 ? 'Medium' : 'Low') }}
                                                        </span>
                                                    </td>
                                                    <td class="text-truncate" style="max-width: 300px;" title="{{ $data['message'] }}">
                                                        {{ Str::limit($data['message'], 50) }}
                                                    </td>
                                                    <td>{{ $data['timestamp'] }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="showAlertDetails('{{ $data['src_ip'] }}', '{{ $data['dest_ip'] }}', '{{ $data['message'] }}')">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">
                                                        <i class="fas fa-shield-alt fa-2x mb-2"></i>
                                                        <br>Tidak ada alert keamanan saat ini
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if(isset($logs))
                                    <div class="d-flex justify-content-center">
                                        {{ $logs->links() }}
                                    </div>
                                @endif
                            </div><!-- /.widget-body -->
                        </div><!-- /.widget-bg -->
                    </div><!-- /.widget-holder -->
                </div><!-- /.row -->
            </div><!-- /.widget-list -->
        </main><!-- /.main-wrapper -->
        @include('partials.footer')
    </div>

    <!-- Alert Details Modal -->
    <div class="modal fade" id="alertDetailsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alert Details</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="alertDetailsBody">
                    <!-- Details will be populated by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Security Charts
        const chartsData = @json($chartsData ?? []);
        
        // Severity Distribution Chart
        if (document.getElementById('severityChart') && chartsData.severityChart) {
            new Chart(document.getElementById('severityChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['High Priority', 'Medium Priority', 'Low Priority'],
                    datasets: [{
                        label: 'Security Alerts by Severity',
                        data: [
                            chartsData.severityChart.datasets[0].data[0] || 0, // High Priority
                            chartsData.severityChart.datasets[0].data[1] || 0, // Medium Priority
                            chartsData.severityChart.datasets[0].data[2] || 0  // Low Priority
                        ],
                        backgroundColor: [
                            '#dc3545', // Red for High Priority
                            '#ffc107', // Yellow for Medium Priority
                            '#28a745'  // Green for Low Priority
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                    return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Hourly Pattern Chart
        if (document.getElementById('hourlyPatternChart') && chartsData.hourlyPatternChart) {
            new Chart(document.getElementById('hourlyPatternChart').getContext('2d'), {
                type: 'line',
                data: chartsData.hourlyPatternChart,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Table Filtering Functionality
        function initializeTableFilters() {
            const severityFilter = document.getElementById('severityFilter');
            const protocolFilter = document.getElementById('protocolFilter');
            const ipFilter = document.getElementById('ipFilter');
            const messageFilter = document.getElementById('messageFilter');
            const dateFilter = document.getElementById('dateFilter');
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            const tableBody = document.getElementById('alertsTableBody');

            // Helper function to parse date from timestamp
            function parseAlertDate(timestampText) {
                // Extract date from "2025-08-08 10:30:00 WIB" format
                const dateMatch = timestampText.match(/(\d{4}-\d{2}-\d{2})/);
                return dateMatch ? new Date(dateMatch[1]) : null;
            }

            // Helper function to check if date falls within range
            function isDateInRange(alertDate, filterType, customStart = null, customEnd = null) {
                if (!alertDate) return true;
                
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);
                
                switch (filterType) {
                    case 'today':
                        return alertDate.toDateString() === today.toDateString();
                    
                    case 'yesterday':
                        return alertDate.toDateString() === yesterday.toDateString();
                    
                    case 'last7days':
                        const last7Days = new Date(today);
                        last7Days.setDate(last7Days.getDate() - 7);
                        return alertDate >= last7Days && alertDate <= today;
                    
                    case 'thisweek':
                        const startOfWeek = new Date(today);
                        const day = startOfWeek.getDay();
                        const diff = startOfWeek.getDate() - day + (day === 0 ? -6 : 1); // Monday
                        startOfWeek.setDate(diff);
                        return alertDate >= startOfWeek && alertDate <= today;
                    
                    case 'lastweek':
                        const lastWeekStart = new Date(today);
                        const lastWeekDay = lastWeekStart.getDay();
                        const lastWeekDiff = lastWeekStart.getDate() - lastWeekDay - 6; // Previous Monday
                        lastWeekStart.setDate(lastWeekDiff);
                        
                        const lastWeekEnd = new Date(lastWeekStart);
                        lastWeekEnd.setDate(lastWeekEnd.getDate() + 6); // Sunday
                        
                        return alertDate >= lastWeekStart && alertDate <= lastWeekEnd;
                    
                    case 'thismonth':
                        const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                        return alertDate >= startOfMonth && alertDate <= today;
                    
                    case 'custom':
                        if (customStart && customEnd) {
                            const start = new Date(customStart);
                            const end = new Date(customEnd);
                            end.setHours(23, 59, 59, 999); // Include the entire end date
                            return alertDate >= start && alertDate <= end;
                        }
                        return true;
                    
                    default:
                        return true;
                }
            }

            function filterTable() {
                const rows = tableBody.getElementsByTagName('tr');
                
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    
                    // Skip empty state row
                    if (row.cells.length < 7) continue;
                    
                    let showRow = true;
                    
                    // Get row data
                    const srcIp = row.cells[0].textContent.trim();
                    const destIp = row.cells[1].textContent.trim();
                    const protocol = row.cells[2].textContent.trim();
                    const severityText = row.cells[3].textContent.trim();
                    const message = row.cells[4].textContent.trim();
                    const timestamp = row.cells[5].textContent.trim();
                    
                    // Convert severity text to number
                    let severityValue = '';
                    if (severityText.includes('High')) severityValue = '1';
                    else if (severityText.includes('Medium')) severityValue = '2';
                    else if (severityText.includes('Low')) severityValue = '3';
                    
                    // Parse alert date
                    const alertDate = parseAlertDate(timestamp);
                    
                    // Apply filters
                    if (severityFilter.value && severityValue !== severityFilter.value) {
                        showRow = false;
                    }
                    
                    if (protocolFilter.value && !protocol.toLowerCase().includes(protocolFilter.value.toLowerCase())) {
                        showRow = false;
                    }
                    
                    if (ipFilter.value && !srcIp.includes(ipFilter.value) && !destIp.includes(ipFilter.value)) {
                        showRow = false;
                    }
                    
                    if (messageFilter.value && !message.toLowerCase().includes(messageFilter.value.toLowerCase())) {
                        showRow = false;
                    }
                    
                    // Date filter
                    if (dateFilter.value) {
                        const customStart = startDate.value;
                        const customEnd = endDate.value;
                        
                        if (dateFilter.value === 'custom') {
                            if (!isDateInRange(alertDate, 'custom', customStart, customEnd)) {
                                showRow = false;
                            }
                        } else {
                            if (!isDateInRange(alertDate, dateFilter.value)) {
                                showRow = false;
                            }
                        }
                    }
                    
                    // Show/hide row
                    row.style.display = showRow ? '' : 'none';
                }
                
                // Update visible count
                updateVisibleCount();
            }
            
            function updateVisibleCount() {
                const rows = tableBody.getElementsByTagName('tr');
                let visibleCount = 0;
                
                for (let i = 0; i < rows.length; i++) {
                    if (rows[i].style.display !== 'none' && rows[i].cells.length >= 7) {
                        visibleCount++;
                    }
                }
                
                // Update counter if exists
                const counter = document.getElementById('visibleCounter');
                if (counter) {
                    counter.textContent = `Showing ${visibleCount} alerts`;
                }
            }
            
            // Add event listeners
            severityFilter?.addEventListener('change', filterTable);
            protocolFilter?.addEventListener('change', filterTable);
            ipFilter?.addEventListener('input', filterTable);
            messageFilter?.addEventListener('input', filterTable);
            dateFilter?.addEventListener('change', function() {
                if (this.value === 'custom') {
                    document.getElementById('customDateRange').style.display = 'block';
                } else {
                    document.getElementById('customDateRange').style.display = 'none';
                    filterTable();
                }
            });
            startDate?.addEventListener('change', filterTable);
            endDate?.addEventListener('change', filterTable);
            
            // Custom date range buttons
            document.getElementById('showCustomRange')?.addEventListener('click', function() {
                const customRange = document.getElementById('customDateRange');
                if (customRange.style.display === 'none') {
                    customRange.style.display = 'block';
                    dateFilter.value = 'custom';
                } else {
                    customRange.style.display = 'none';
                    dateFilter.value = '';
                    filterTable();
                }
            });
            
            document.getElementById('applyDateRange')?.addEventListener('click', function() {
                if (startDate.value && endDate.value) {
                    dateFilter.value = 'custom';
                    filterTable();
                } else {
                    alert('Please select both start and end dates');
                }
            });
            
            // Clear filters button
            document.getElementById('clearFilters')?.addEventListener('click', function() {
                severityFilter.value = '';
                protocolFilter.value = '';
                ipFilter.value = '';
                messageFilter.value = '';
                dateFilter.value = '';
                startDate.value = '';
                endDate.value = '';
                document.getElementById('customDateRange').style.display = 'none';
                filterTable();
            });
        }

        // Show alert details function
        function showAlertDetails(srcIp, destIp, message) {
            const details = `
                <div class="alert alert-warning">
                    <h6><i class="fas fa-exclamation-triangle"></i> Security Alert Details</h6>
                </div>
                <table class="table table-sm">
                    <tr><td><strong>Source IP:</strong></td><td>${srcIp}</td></tr>
                    <tr><td><strong>Destination IP:</strong></td><td>${destIp}</td></tr>
                    <tr><td><strong>Message:</strong></td><td>${message}</td></tr>
                    <tr><td><strong>Timestamp:</strong></td><td>${new Date().toLocaleString()}</td></tr>
                </table>
                <div class="alert alert-info">
                    <strong>Recommended Actions:</strong>
                    <ul class="mb-0">
                        <li>Investigate source IP for suspicious activity</li>
                        <li>Check if destination IP is a critical system</li>
                        <li>Review firewall rules and consider blocking if necessary</li>
                    </ul>
                </div>
            `;
            
            document.getElementById('alertDetailsBody').innerHTML = details;
            $('#alertDetailsModal').modal('show');
        }

        // Auto-refresh functionality
        document.getElementById('refreshData')?.addEventListener('click', function() {
            location.reload();
        });

        // Initialize filters when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeTableFilters();
        });

        // Auto-refresh every 30 seconds
        setInterval(function() {
            // Optionally implement AJAX refresh here
            console.log('Auto-refresh check...');
        }, 30000);
    </script>
</body>
</html>
