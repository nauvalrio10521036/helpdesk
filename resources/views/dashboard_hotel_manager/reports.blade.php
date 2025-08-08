@extends('dashboard_hotel_manager.layout')

@section('page_title', 'Data Laporan Hotel')
@section('page_description', 'Monitoring dan Analisis Laporan IT & Fasilitas')
@section('breadcrumb_active', 'Laporan')

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <!-- Pending Reports -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Menunggu</h5>
                            <h2 class="text-black">{{ $reports->where('status', 'waiting')->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">schedule</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress Reports -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Diproses</h5>
                            <h2 class="text-black">{{ $reports->where('status', 'process')->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">autorenew</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Reports -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Selesai</h5>
                            <h2 class="text-black">{{ $reports->where('status', 'finished')->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">check_circle</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Reports -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Laporan</h5>
                            <h2 class="text-black">{{ $reports->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">assignment</i>
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
                        <i class="material-icons">trending_up</i>
                        Trend Laporan (30 Hari Terakhir)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="reportsChart" height="400"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">pie_chart</i>
                        Distribusi Status
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">
                <i class="material-icons">list</i>
                Daftar Laporan
            </h5>
            <a href="{{ route('dashboard_hotel_manager.download.reports') }}" class="btn btn-success">
                <i class="material-icons">file_download</i>
                Download Excel
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Lokasi</th>
                            <th>Pelapor</th>
                            <th>Status</th>
                            <th>Prioritas</th>
                            <th>Waktu Laporan</th>
                            <th>Waktu Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td><span class="badge badge-secondary">#{{ $report->report_id }}</span></td>
                            <td><strong>{{ $report->title }}</strong></td>
                            <td>{{ Str::limit($report->description, 50) }}</td>
                            <td><i class="material-icons text-muted">location_on</i> {{ $report->lokasi }}</td>
                            <td>{{ $report->user ? $report->user->nama : 'N/A' }}</td>
                            <td>
                                @if($report->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($report->status == 'in progress')
                                    <span class="badge badge-info">In Progress</span>
                                @elseif($report->status == 'completed')
                                    <span class="badge badge-success">Completed</span>
                                @endif
                            </td>
                            <td>
                                @if($report->prioritas == 'high')
                                    <span class="badge badge-danger">High</span>
                                @elseif($report->prioritas == 'medium')
                                    <span class="badge badge-warning">Medium</span>
                                @elseif($report->prioritas == 'low')
                                    <span class="badge badge-success">Low</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $report->time_report ? \Carbon\Carbon::parse($report->time_report)->format('d/m/Y H:i') : '-' }}
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $report->time_finished ? \Carbon\Carbon::parse($report->time_finished)->format('d/m/Y H:i') : '-' }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                <i class="material-icons">inbox</i>
                                <br>Tidak ada data laporan
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
                        <i class="material-icons">priority_high</i>
                        Laporan Berdasarkan Prioritas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-2">
                                <h4 class="text-danger">{{ $reports->where('prioritas', 'high')->count() }}</h4>
                                <small class="text-muted">High Priority</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2">
                                <h4 class="text-warning">{{ $reports->where('prioritas', 'medium')->count() }}</h4>
                                <small class="text-muted">Medium Priority</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2">
                                <h4 class="text-success">{{ $reports->where('prioritas', 'low')->count() }}</h4>
                                <small class="text-muted">Low Priority</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">
                        <i class="material-icons">location_on</i>
                        Laporan Terbanyak per Lokasi
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $locationCounts = $reports->groupBy('lokasi')->map->count()->sortDesc()->take(5);
                    @endphp
                    @foreach($locationCounts as $location => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="material-icons text-muted">place</i> {{ $location }}</span>
                        <span class="badge badge-primary">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Reports Trend Chart
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

    // Status Distribution Chart
    var statusCtx = document.getElementById('statusChart').getContext('2d');
    var statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'In Progress', 'Completed'],
            datasets: [{
                data: [
                    {{ $reports->where('status', 'pending')->count() }},
                    {{ $reports->where('status', 'in progress')->count() }},
                    {{ $reports->where('status', 'completed')->count() }}
                ],
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745'],
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
</script>
@endpush
