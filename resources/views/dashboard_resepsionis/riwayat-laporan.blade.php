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
                    <h6 class="page-title-heading mr-0 mr-r-5">Riwayat Laporan</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Daftar semua laporan yang telah dibuat</p>
                </div>
                <!-- /.page-title-left -->
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_resepsionis.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Laporan</li>
                    </ol>
                </div>
                <!-- /.page-title-right -->
            </div>

            <!-- Action Bar -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('dashboard_resepsionis.create-report') }}" class="btn btn-primary">
                            <i class="material-icons">add</i> Laporan Baru
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">filter_list</i> Filter & Pencarian
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard_resepsionis.riwayat-laporan') }}" id="filterForm">
                        <div class="row">
                            <!-- Search -->
                            <div class="col-md-3 mb-3">
                                <label for="search" class="form-label">Pencarian</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="search" 
                                       name="search" 
                                       value="{{ request('search') }}" 
                                       placeholder="Cari judul atau deskripsi...">
                            </div>

                            <!-- Status Filter -->
                            <div class="col-md-2 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>
                                        Menunggu
                                    </option>
                                    <option value="process" {{ request('status') == 'process' ? 'selected' : '' }}>
                                Diproses
                            </option>
                            <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>
                                Selesai
                            </option>
                        </select>
                    </div>

                    <!-- Priority Filter -->
                    <div class="col-md-2 mb-3">
                        <label for="prioritas" class="form-label">Prioritas</label>
                        <select class="form-select" id="prioritas" name="prioritas">
                            <option value="">Semua Prioritas</option>
                            <option value="high" {{ request('prioritas') == 'high' ? 'selected' : '' }}>
                                Tinggi
                            </option>
                            <option value="medium" {{ request('prioritas') == 'medium' ? 'selected' : '' }}>
                                Sedang
                            </option>
                            <option value="low" {{ request('prioritas') == 'low' ? 'selected' : '' }}>
                                Rendah
                            </option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div class="col-md-2 mb-3">
                        <label for="tanggal_dari" class="form-label">Tanggal Dari</label>
                        <input type="date" 
                               class="form-control" 
                               id="tanggal_dari" 
                               name="tanggal_dari" 
                               value="{{ request('tanggal_dari') }}">
                    </div>

                    <!-- Date To -->
                    <div class="col-md-2 mb-3">
                        <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
                        <input type="date" 
                               class="form-control" 
                               id="tanggal_sampai" 
                               name="tanggal_sampai" 
                               value="{{ request('tanggal_sampai') }}">
                    </div>

                    <!-- Filter Actions -->
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="material-icons">search</i>
                            </button>
                            <a href="{{ route('dashboard_resepsionis.riwayat-laporan') }}" class="btn btn-secondary btn-sm">
                                <i class="material-icons">refresh</i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Summary -->
    @if(request()->hasAny(['search', 'status', 'prioritas', 'tanggal_dari', 'tanggal_sampai']))
        <div class="alert alert-info">
            <i class="material-icons">info</i> 
            Menampilkan {{ $reports->total() }} hasil dari pencarian/filter yang diterapkan.
            <a href="{{ route('dashboard_resepsionis.riwayat-laporan') }}" class="alert-link">Reset filter</a>
        </div>
    @endif

    <!-- Reports Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="material-icons">list</i> Daftar Laporan ({{ $reports->total() }} total)
            </h5>
        </div>
        <div class="card-body">
            @if($reports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%" class="text-white">No</th>
                                <th width="25%" class="text-white">Judul & Deskripsi</th>
                                <th width="15%" class="text-white">Lokasi</th>
                                <th width="10%" class="text-white">Prioritas</th>
                                <th width="10%" class="text-white">Status</th>
                                <th width="15%" class="text-white">Tanggal Laporan</th>
                                <th width="10%" class="text-white">Lampiran</th>
                                <th width="10%" class="text-white">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $index => $report)
                                <tr class="table-row">
                                    <td class="text-dark">{{ $reports->firstItem() + $index }}</td>
                                    <td>
                                        <div>
                                            <strong class="text-dark">{{ $report->title }}</strong>
                                            <br>
                                            <small class="text-secondary">
                                                {{ Str::limit($report->description, 100) }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-dark">
                                        <i class="material-icons text-primary">location_on</i>
                                        {{ $report->lokasi }}
                                    </td>
                                    <td>
                                        @if($report->prioritas == 'high')
                                            <span class="badge bg-danger">
                                                <i class="material-icons">priority_high</i> Tinggi
                                            </span>
                                        @elseif($report->prioritas == 'medium')
                                            <span class="badge bg-warning">
                                                <i class="material-icons">error</i> Sedang
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="material-icons">check_circle</i> Rendah
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($report->status == 'waiting')
                                            <span class="badge bg-info">
                                                <i class="material-icons">schedule</i> Menunggu
                                            </span>
                                        @elseif($report->status == 'process')
                                            <span class="badge bg-info">
                                                <i class="material-icons">settings</i> Diproses
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="material-icons">check</i> Selesai
                                            </span>
                                            @if($report->time_finished)
                                                <br><small class="text-secondary">
                                                    {{ \Carbon\Carbon::parse($report->time_finished)->format('d/m/Y H:i') }}
                                                </small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <span class="text-dark">{{ \Carbon\Carbon::parse($report->time_report)->format('d/m/Y') }}</span>
                                            <br>
                                            <small class="text-secondary">
                                                {{ \Carbon\Carbon::parse($report->time_report)->format('H:i') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($report->attachment)
                                            <a href="{{ route('dashboard_resepsionis.download-attachment', $report->report_id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Download Lampiran">
                                                <i class="material-icons">download</i>
                                            </a>
                                        @else
                                            <span class="text-secondary">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('dashboard_resepsionis.detail-laporan', $report->report_id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Lihat Detail">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Menampilkan {{ $reports->firstItem() }} sampai {{ $reports->lastItem() }} 
                            dari {{ $reports->total() }} laporan
                        </small>
                    </div>
                    <div>
                        {{ $reports->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="material-icons text-muted" style="font-size: 72px;">assignment</i>
                    <h5 class="text-muted mt-3">
                        @if(request()->hasAny(['search', 'status', 'prioritas', 'tanggal_dari', 'tanggal_sampai']))
                            Tidak ada laporan yang sesuai dengan filter
                        @else
                            Belum ada laporan yang dibuat
                        @endif
                    </h5>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'status', 'prioritas', 'tanggal_dari', 'tanggal_sampai']))
                            Coba ubah kriteria pencarian atau filter Anda
                        @else
                            Mulai buat laporan pertama Anda sekarang
                        @endif
                    </p>
                    <div class="mt-3">
                        @if(request()->hasAny(['search', 'status', 'prioritas', 'tanggal_dari', 'tanggal_sampai']))
                            <a href="{{ route('dashboard_resepsionis.riwayat-laporan') }}" class="btn btn-secondary me-2">
                                <i class="material-icons">refresh</i> Reset Filter
                            </a>
                        @endif
                        <a href="{{ route('dashboard_resepsionis.create-report') }}" class="btn btn-primary">
                            <i class="material-icons">add</i> Buat Laporan Baru
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Card -->
    @if($reports->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-info">
                    <div class="card-header bg-light">
                        <h6 class="m-0 text-primary">
                            <i class="material-icons">pie_chart</i> Statistik Laporan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h4 class="text-primary">{{ $reports->total() }}</h4>
                                    <small class="text-muted">Total Laporan</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h4 class="text-warning">
                                        {{ $reports->where('status', 'waiting')->count() }}
                                    </h4>
                                    <small class="text-muted">Menunggu</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border-end">
                                    <h4 class="text-info">
                                        {{ $reports->where('status', 'process')->count() }}
                                    </h4>
                                    <small class="text-muted">Diproses</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-success">
                                    {{ $reports->where('status', 'finished')->count() }}
                                </h4>
                                <small class="text-muted">Selesai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

        </main>
        <!-- /.main-wrapper -->
    </div>
    <!-- /.wrapper -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto submit form when filter changes
    const filterSelects = document.querySelectorAll('#status, #prioritas');
    const dateInputs = document.querySelectorAll('#tanggal_dari, #tanggal_sampai');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });

    // Search on Enter key
    document.getElementById('search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('filterForm').submit();
        }
    });
});
</script>

@include('partials.footer')
</body>
</html>
