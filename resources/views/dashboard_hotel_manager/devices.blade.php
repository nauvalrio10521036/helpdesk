@extends('dashboard_hotel_manager.layout')

@section('page_title', 'Monitoring Perangkat Jaringan')
@section('page_description', 'Monitoring dan Analisis Perangkat Jaringan Hotel')
@section('breadcrumb_active', 'Perangkat')

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <!-- Active Devices -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Perangkat Aktif</h5>
                            <h2 class="text-black">{{ $devices->where('status', 'active')->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">check_circle</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inactive Devices -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Perangkat Tidak Aktif</h5>
                            <h2 class="text-black">{{ $devices->where('status', 'inactive')->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">cancel</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Access Points -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Access Point</h5>
                            <h2 class="text-black">{{ $devices->where('tipe', 'Access Point')->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">wifi</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Devices -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card text-black bg-gradient-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Perangkat</h5>
                            <h2 class="text-black">{{ $devices->count() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="material-icons md-48">devices</i>
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
                        Status Perangkat (30 Hari Terakhir)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="devicesChart" height="400"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">pie_chart</i>
                        Distribusi Tipe Perangkat
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="typeChart" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Devices Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title">
                <i class="material-icons">list</i>
                Daftar Perangkat Jaringan
            </h5>
            <a href="{{ route('dashboard_hotel_manager.download.devices') }}" class="btn btn-success">
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
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>IP Address</th>
                            <th>MAC Address</th>
                            <th>VLAN</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Uptime</th>
                            <th>Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($devices as $device)
                        <tr>
                            <td><span class="badge badge-secondary">#{{ $device->device_id }}</span></td>
                            <td><strong>{{ $device->name }}</strong></td>
                            <td>
                                <span class="badge badge-info">{{ $device->tipe }}</span>
                            </td>
                            <td>
                                <code>{{ $device->ip_address }}</code>
                            </td>
                            <td>
                                <small class="text-muted">{{ $device->mac_address }}</small>
                            </td>
                            <td>{{ $device->vlan ? $device->vlan->name : 'N/A' }}</td>
                            <td><i class="material-icons text-muted">location_on</i> {{ $device->lokasi }}</td>
                            <td>
                                @if($device->status == 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $device->uptime ?? '-' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $device->created_at ? \Carbon\Carbon::parse($device->created_at)->format('d/m/Y H:i') : '-' }}
                                </small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                <i class="material-icons">inbox</i>
                                <br>Tidak ada data perangkat
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
                        <i class="material-icons">category</i>
                        Perangkat Berdasarkan Tipe
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $typeCounts = $devices->groupBy('tipe')->map->count()->sortDesc();
                    @endphp
                    @foreach($typeCounts as $type => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="material-icons text-muted">device_hub</i> {{ $type }}</span>
                        <span class="badge badge-primary">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">
                        <i class="material-icons">location_on</i>
                        Perangkat per Lokasi
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $locationCounts = $devices->groupBy('lokasi')->map->count()->sortDesc()->take(5);
                    @endphp
                    @foreach($locationCounts as $location => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="material-icons text-muted">place</i> {{ $location }}</span>
                        <span class="badge badge-success">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Health Status -->
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">
                <i class="material-icons">favorite</i>
                Network Health Overview
            </h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-lg-3 col-md-6">
                    <div class="p-3">
                        <div class="h2 text-success">
                            {{ number_format(($devices->where('status', 'active')->count() / max($devices->count(), 1)) * 100, 1) }}%
                        </div>
                        <small class="text-muted">Network Uptime</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="p-3">
                        <div class="h2 text-info">
                            {{ $devices->where('tipe', 'Access Point')->where('status', 'active')->count() }}
                        </div>
                        <small class="text-muted">Access Point Aktif</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="p-3">
                        <div class="h2 text-warning">
                            {{ $devices->where('status', 'inactive')->count() }}
                        </div>
                        <small class="text-muted">Perlu Perhatian</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="p-3">
                        <div class="h2 text-primary">
                            {{ $devices->unique('vlan_id')->count() }}
                        </div>
                        <small class="text-muted">VLAN Terkonfigurasi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Devices Status Chart
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

    // Device Type Distribution Chart
    var typeCtx = document.getElementById('typeChart').getContext('2d');
    
    @php
        $typeCounts = $devices->groupBy('tipe')->map->count();
        $typeLabels = $typeCounts->keys()->toArray();
        $typeData = $typeCounts->values()->toArray();
        $colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
    @endphp
    
    var typeChart = new Chart(typeCtx, {
        type: 'doughnut',
        data: {
            labels: @json($typeLabels),
            datasets: [{
                data: @json($typeData),
                backgroundColor: @json(array_slice($colors, 0, count($typeLabels))),
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
