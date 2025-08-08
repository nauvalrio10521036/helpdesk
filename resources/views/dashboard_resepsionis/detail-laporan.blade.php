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
                    <h6 class="page-title-heading mr-0 mr-r-5">Detail Laporan</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Informasi lengkap laporan IT</p>
                </div>
                <!-- /.page-title-left -->
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_resepsionis.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_resepsionis.riwayat-laporan') }}">Riwayat Laporan</a></li>
                        <li class="breadcrumb-item active">Detail</li>
                    </ol>
                </div>
                <!-- /.page-title-right -->
            </div>

            <!-- Action Bar -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('dashboard_resepsionis.riwayat-laporan') }}" class="btn btn-secondary">
                            <i class="material-icons">arrow_back</i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Report Details Card -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">
                                <i class="material-icons">assignment</i> Informasi Laporan
                            </h5>
                            <div>
                                <!-- Status Badge -->
                                @if($report->status == 'waiting')
                                    <span class="badge bg-info fs-6">
                                        <i class="material-icons">schedule</i> Menunggu
                                    </span>
                                @elseif($report->status == 'process')
                                    <span class="badge bg-info fs-6">
                                        <i class="material-icons">settings</i> Sedang Diproses
                                    </span>
                                @else
                                    <span class="badge bg-success fs-6">
                                        <i class="material-icons">check</i> Selesai
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                    <!-- Title -->
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="material-icons text-primary">title</i> Judul:</strong>
                        </div>
                        <div class="col-sm-9">
                            <h5 class="mb-0">{{ $report->title }}</h5>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="material-icons text-primary">description</i> Deskripsi:</strong>
                        </div>
                        <div class="col-sm-9">
                            <div class="border rounded p-3 bg-light">
                                {{ $report->description }}
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="material-icons text-primary">location_on</i> Lokasi:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="badge bg-secondary fs-6">{{ $report->lokasi }}</span>
                        </div>
                    </div>

                    <!-- Priority -->
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="material-icons text-primary">priority_high</i> Prioritas:</strong>
                        </div>
                        <div class="col-sm-9">
                            @if($report->prioritas == 'high')
                                <span class="badge bg-danger fs-6">
                                    <i class="material-icons">priority_high</i> Tinggi
                                </span>
                            @elseif($report->prioritas == 'medium')
                                <span class="badge bg-info fs-6">
                                    <i class="material-icons">error</i> Sedang
                                </span>
                            @else
                                <span class="badge bg-success fs-6">
                                    <i class="material-icons">check_circle</i> Rendah
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <strong><i class="material-icons text-primary">event</i> Tanggal Laporan:</strong>
                        </div>
                        <div class="col-sm-9">
                            <span class="text-muted">
                                {{ \Carbon\Carbon::parse($report->time_report)->format('d F Y, H:i') }} WIB
                                <small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($report->time_report)->diffForHumans() }})
                                </small>
                            </span>
                        </div>
                    </div>

                    @if($report->time_finished)
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="material-icons text-success">event_available</i> Tanggal Selesai:</strong>
                            </div>
                            <div class="col-sm-9">
                                <span class="text-success">
                                    {{ \Carbon\Carbon::parse($report->time_finished)->format('d F Y, H:i') }} WIB
                                    <small class="text-muted">
                                        ({{ \Carbon\Carbon::parse($report->time_finished)->diffForHumans() }})
                                    </small>
                                </span>
                            </div>
                        </div>

                        <!-- Duration -->
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong><i class="material-icons text-info">hourglass_empty</i> Durasi Penanganan:</strong>
                            </div>
                            <div class="col-sm-9">
                                @php
                                    $start = \Carbon\Carbon::parse($report->time_report);
                                    $end = \Carbon\Carbon::parse($report->time_finished);
                                    $duration = $start->diff($end);
                                @endphp
                                <span class="text-info">
                                    @if($duration->days > 0)
                                        {{ $duration->days }} hari,
                                    @endif
                                    {{ $duration->h }} jam, {{ $duration->i }} menit
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Attachment Card -->
            @if($report->attachment)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="material-icons">attach_file</i> Lampiran
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                @php
                                    $extension = pathinfo($report->attachment, PATHINFO_EXTENSION);
                                    $filename = basename($report->attachment);
                                @endphp
                                
                                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                    <i class="material-icons text-success me-3" style="font-size: 32px;">image</i>
                                @elseif(in_array(strtolower($extension), ['pdf']))
                                    <i class="material-icons text-danger me-3" style="font-size: 32px;">picture_as_pdf</i>
                                @elseif(in_array(strtolower($extension), ['doc', 'docx']))
                                    <i class="material-icons text-primary me-3" style="font-size: 32px;">description</i>
                                @else
                                    <i class="material-icons text-secondary me-3" style="font-size: 32px;">insert_drive_file</i>
                                @endif
                                
                                <div>
                                    <strong>{{ $filename }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        Type: {{ strtoupper($extension) }}
                                    </small>
                                </div>
                            </div>
                            
                            <div>
                                <a href="{{ route('dashboard_resepsionis.download-attachment', $report->report_id) }}" 
                                   class="btn btn-primary">
                                    <i class="material-icons">download</i> Download
                                </a>
                            </div>
                        </div>

                        <!-- Image Preview -->
                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                            <hr>
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $report->attachment) }}" 
                                     alt="Attachment Preview" 
                                     class="img-fluid rounded shadow"
                                     style="max-height: 400px; cursor: pointer;"
                                     onclick="showImageModal(this.src)">
                                <br>
                                <small class="text-muted">Klik gambar untuk memperbesar</small>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Status Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">timeline</i> Timeline Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Laporan Dibuat -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Laporan Dibuat</h6>
                                <p class="text-muted mb-0 small">
                                    {{ \Carbon\Carbon::parse($report->time_report)->format('d M Y, H:i') }}
                                </p>
                                <small class="text-success">
                                    <i class="material-icons">check</i> Selesai
                                </small>
                            </div>
                        </div>

                        <!-- Status Waiting -->
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $report->status == 'waiting' ? 'bg-info' : 'bg-success' }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Menunggu Penanganan</h6>
                                <p class="text-muted mb-0 small">
                                    Laporan masuk ke antrian IT Support
                                </p>
                                <small class="{{ $report->status == 'waiting' ? 'text-info' : 'text-success' }}">
                                    <i class="material-icons">{{ $report->status == 'waiting' ? 'schedule' : 'check' }}</i> 
                                    {{ $report->status == 'waiting' ? 'Sedang Berlangsung' : 'Selesai' }}
                                </small>
                            </div>
                        </div>

                        <!-- Status Process -->
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $report->status == 'process' ? 'bg-info' : ($report->status == 'finished' ? 'bg-success' : 'bg-light') }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Sedang Diproses</h6>
                                <p class="text-muted mb-0 small">
                                    Tim IT sedang menangani masalah
                                </p>
                                <small class="{{ $report->status == 'process' ? 'text-info' : ($report->status == 'finished' ? 'text-success' : 'text-muted') }}">
                                    <i class="material-icons">{{ $report->status == 'process' ? 'settings' : ($report->status == 'finished' ? 'check' : 'radio_button_unchecked') }}</i> 
                                    {{ $report->status == 'process' ? 'Sedang Berlangsung' : ($report->status == 'finished' ? 'Selesai' : 'Menunggu') }}
                                </small>
                            </div>
                        </div>

                        <!-- Status Finished -->
                        <div class="timeline-item">
                            <div class="timeline-marker {{ $report->status == 'finished' ? 'bg-success' : 'bg-light' }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Selesai</h6>
                                <p class="text-muted mb-0 small">
                                    @if($report->time_finished)
                                        {{ \Carbon\Carbon::parse($report->time_finished)->format('d M Y, H:i') }}
                                    @else
                                        Masalah telah selesai ditangani
                                    @endif
                                </p>
                                <small class="{{ $report->status == 'finished' ? 'text-success' : 'text-muted' }}">
                                    <i class="material-icons">{{ $report->status == 'finished' ? 'check' : 'radio_button_unchecked' }}</i> 
                                    {{ $report->status == 'finished' ? 'Selesai' : 'Menunggu' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">flash_on</i> Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('dashboard_resepsionis.create-report') }}" class="btn btn-primary">
                            <i class="material-icons">add</i> Buat Laporan Baru
                        </a>
                        <a href="{{ route('dashboard_resepsionis.riwayat-laporan') }}" class="btn btn-outline-secondary">
                            <i class="material-icons">list</i> Lihat Semua Laporan
                        </a>
                        @if($report->attachment)
                            <a href="{{ route('dashboard_resepsionis.download-attachment', $report->report_id) }}" 
                               class="btn btn-outline-info">
                                <i class="material-icons">download</i> Download Lampiran
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card border-info">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title text-white m-0">
                        <i class="material-icons">phone</i> Kontak IT Support
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <p class="mb-2">Butuh bantuan segera?</p>
                        <h4 class="text-primary">ext. 111</h4>
                        <small class="text-muted">
                            Jam kerja: 24/7 untuk emergency
                        </small>
                    </div>
                </div>
            </div>
        </div>
            </div>

        </main>
        <!-- /.main-wrapper -->
    </div>
    <!-- /.wrapper -->

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Lampiran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Preview" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e3e6f0;
}

.timeline-content {
    background: #f8f9fc;
    border: 1px solid #e3e6f0;
    border-radius: 5px;
    padding: 15px;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
</style>

<script>
function showImageModal(src) {
    document.getElementById('modalImage').src = src;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

// Auto refresh status setiap 30 detik jika status belum finished
@if($report->status != 'finished')
setInterval(function() {
    location.reload();
}, 30000);
@endif
</script>

@include('partials.footer')
</body>
</html>
