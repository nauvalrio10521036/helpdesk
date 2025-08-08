<!DOCTYPE html>
<html lang="en">
@include('partials.header')

<body class="sidebar-dark sidebar-expand navbar-brand-dark header-light">
    <div id="wrapper" class="wrapper">
@include('partials.nav')
@include('partials.sidebar-resepsionis')
        <main class="main-wrapper clearfix">
            <!-- Page Title Area -->
            <div class="row page-title clearfix">
                <div class="page-title-left">
                    <h6 class="page-title-heading mr-0 mr-r-5">Dashboard Resepsionis</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Selamat datang, {{ session('name') }}</p>
                </div>
                <!-- /.page-title-left -->
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_resepsionis.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
                <!-- /.page-title-right -->
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row">
                <!-- Total Laporan -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card text-black bg-gradient-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Total Laporan</h5>
                                    <h2 class="text-black">{{ $totalLaporan }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons md-48">assignment</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laporan Menunggu -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card text-black bg-gradient-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Menunggu</h5>
                                    <h2 class="text-black">{{ $laporanWaiting }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons md-48">schedule</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laporan Diproses -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card text-black bg-gradient-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Diproses</h5>
                                    <h2 class="text-black">{{ $laporanProcess }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons md-48">settings</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Laporan Selesai -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="card text-black bg-gradient-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title">Selesai</h5>
                                    <h2 class="text-black">{{ $laporanFinished }}</h2>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons md-48">check_circle</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="row mt-4">
                <div class="col-lg-6 col-md-6">
                    <div class="card border-left-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Aksi Cepat</div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">Buat Laporan Baru</div>
                                    <small class="text-muted">Laporkan masalah IT yang ditemukan</small>
                                </div>
                                <div class="align-self-center">
                                    <a href="{{ route('dashboard_resepsionis.create-report') }}" class="btn btn-primary">
                                        <i class="material-icons">add</i> Buat Laporan
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <div class="card border-left-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Riwayat</div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">Lihat Semua Laporan</div>
                                    <small class="text-muted">Pantau status dan riwayat laporan</small>
                                </div>
                                <div class="align-self-center">
                                    <a href="{{ route('dashboard_resepsionis.riwayat-laporan') }}" class="btn btn-info">
                                        <i class="material-icons">history</i> Riwayat
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reports Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="material-icons">assignment</i> Laporan Terbaru
                                <a href="{{ route('dashboard_resepsionis.riwayat-laporan') }}" class="btn btn-sm btn-outline-primary float-right">
                                    Lihat Semua
                                </a>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($recentReports->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="25%">Judul</th>
                                                <th width="20%">Lokasi</th>
                                                <th width="15%">Prioritas</th>
                                                <th width="15%">Status</th>
                                                <th width="20%">Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentReports as $index => $report)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <a href="{{ route('dashboard_resepsionis.detail-laporan', $report->report_id) }}" 
                                                           class="text-decoration-none font-weight-bold">
                                                            {{ $report->title }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <i class="material-icons text-primary" style="font-size: 16px;">location_on</i>
                                                        {{ $report->lokasi }}
                                                    </td>
                                                    <td>
                                                        @if($report->prioritas == 'high')
                                                            <span class="badge badge-danger">
                                                                <i class="material-icons" style="font-size: 12px;">priority_high</i> Tinggi
                                                            </span>
                                                        @elseif($report->prioritas == 'medium')
                                                            <span class="badge badge-warning">
                                                                <i class="material-icons" style="font-size: 12px;">remove</i> Sedang
                                                            </span>
                                                        @else
                                                            <span class="badge badge-success">
                                                                <i class="material-icons" style="font-size: 12px;">low_priority</i> Rendah
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($report->status == 'waiting')
                                                            <span class="badge badge-warning">
                                                                <i class="material-icons" style="font-size: 12px;">schedule</i> Menunggu
                                                            </span>
                                                        @elseif($report->status == 'process')
                                                            <span class="badge badge-info">
                                                                <i class="material-icons" style="font-size: 12px;">settings</i> Diproses
                                                            </span>
                                                        @else
                                                            <span class="badge badge-success">
                                                                <i class="material-icons" style="font-size: 12px;">check_circle</i> Selesai
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($report->time_report)->format('d/m/Y H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="material-icons md-48 text-muted">assignment</i>
                                    <h5 class="text-muted mt-3">Belum ada laporan yang dibuat</h5>
                                    <p class="text-muted">Mulai buat laporan pertama Anda sekarang</p>
                                    <a href="{{ route('dashboard_resepsionis.create-report') }}" class="btn btn-primary">
                                        <i class="material-icons">add</i> Buat Laporan Pertama
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tips & Info -->
            <div class="row mt-4">
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="material-icons">lightbulb_outline</i> Tips & Panduan
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex align-items-center">
                                    <i class="material-icons text-warning mr-3">lightbulb_outline</i>
                                    <div>
                                        <strong>Tip 1:</strong> Sertakan detail yang jelas saat membuat laporan
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <i class="material-icons text-primary mr-3">camera_alt</i>
                                    <div>
                                        <strong>Tip 2:</strong> Lampirkan foto/screenshot jika memungkinkan
                                    </div>
                                </div>
                                <div class="list-group-item d-flex align-items-center">
                                    <i class="material-icons text-success mr-3">location_on</i>
                                    <div>
                                        <strong>Tip 3:</strong> Cantumkan lokasi yang spesifik
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12">
                    <div class="card border-left-info">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="material-icons">schedule</i> Waktu Respon Target
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="text-danger">
                                        <i class="material-icons md-24">priority_high</i>
                                        <h6 class="mt-1">Tinggi</h6>
                                        <small>Max 2 jam</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-warning">
                                        <i class="material-icons md-24">remove</i>
                                        <h6 class="mt-1">Sedang</h6>
                                        <small>Max 4 jam</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-success">
                                        <i class="material-icons md-24">low_priority</i>
                                        <h6 class="mt-1">Rendah</h6>
                                        <small>Max 24 jam</small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="text-center">
                                <p class="mb-2"><strong>Kontak Darurat IT Support:</strong></p>
                                <h4 class="text-primary">ext. 111</h4>
                                <small class="text-muted">24/7 untuk masalah kritis</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <!-- /.main-wrapper -->
    </div>
    <!-- /.wrapper -->
@include('partials.footer')
</body>
</html>
