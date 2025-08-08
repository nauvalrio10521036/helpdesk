<!DOCTYPE html>
<html lang="en">
@include('partials.header')
<head>
    <style>
        .status-select {
            border: none;
            background: transparent;
            font-weight: bold;
            cursor: pointer;
        }
        .status-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        .loading-indicator {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 10;
            margin-top: 2px;
        }
        .loading-indicator small {
            font-size: 0.75rem;
            font-style: italic;
        }
        .table td {
            vertical-align: middle;
        }
        .input-group-sm .btn {
            padding: 0.25rem 0.5rem;
        }
        
        /* Attachment preview styles */
        .attachment-preview {
            max-width: 80px;
            margin: 0 auto;
        }
        .image-preview img {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .image-preview img:hover {
            transform: scale(1.05);
        }
        .file-preview .file-icon {
            transition: background-color 0.2s;
        }
        .file-preview .file-icon:hover {
            background-color: #e9ecef !important;
        }
        .btn-xs {
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
        }
        .no-attachment {
            opacity: 0.6;
        }
        
        /* Modal styles for image preview */
        .modal-backdrop {
            background-color: rgba(0,0,0,0.8);
        }
        .modal-content img {
            max-width: 100%;
            height: auto;
        }
        
        /* Filter form styles */
        .filter-form {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
        }
        .filter-form .form-label {
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
        }
        .filter-form .form-control, .filter-form .form-select {
            border: 1px solid #ced4da;
            border-radius: 6px;
        }
        .filter-form .form-control:focus, .filter-form .form-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        }
        .filter-info .badge {
            margin-right: 0.5rem;
            margin-bottom: 0.25rem;
        }
        
        /* Pagination styles */
        .pagination {
            margin-bottom: 0;
        }
        .pagination .page-link {
            color: #007bff;
            border-color: #dee2e6;
        }
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        .pagination .page-link:hover {
            color: #0056b3;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
    </style>
</head>
<body class="sidebar-dark sidebar-expand navbar-brand-dark header-light">
    <div id="wrapper" class="wrapper">
        @include('partials.nav')
        @include('partials.sidebar')
        <main class="main-wrapper clearfix">
            <div class="row page-title clearfix">
                <div class="page-title-left">
                    <h6 class="page-title-heading mr-0 mr-r-5">Laporan Gangguan Jaringan</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Kelola dan pantau laporan gangguan IT secara real-time</p>
                </div>
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                    </ol>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="row mb-4">
                @php
                    $totalReports = $allReports->count();
                    $waitingReports = $allReports->where('status', 'waiting')->count();
                    $processReports = $allReports->where('status', 'process')->count();
                    $finishedReports = $allReports->where('status', 'finished')->count();
                    $highPriorityReports = $allReports->where('prioritas', 'high')->count();
                @endphp
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $totalReports }}</h4>
                                    <p class="mb-0">Total Laporan</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons" style="font-size: 40px;">assessment</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $waitingReports }}</h4>
                                    <p class="mb-0">Menunggu</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons" style="font-size: 40px;">schedule</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $processReports }}</h4>
                                    <p class="mb-0">Diproses</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons" style="font-size: 40px;">build</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">{{ $finishedReports }}</h4>
                                    <p class="mb-0">Selesai</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="material-icons" style="font-size: 40px;">check_circle</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">filter_list</i> Filter & Pencarian
                    </h5>
                </div>
                <div class="card-body filter-form">
                    <form method="GET" action="{{ route('dashboard_it_manager.view-report') }}" id="filterForm">
                        <div class="row">
                            <!-- Search -->
                            <div class="col-md-3 mb-3">
                                <label for="search" class="form-label">
                                    <i class="material-icons">search</i> Cari Laporan
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="material-icons">search</i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Judul, deskripsi, atau lokasi...">
                                    @if(request('search'))
                                        <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()">
                                            <i class="material-icons">clear</i>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="col-md-2 mb-3">
                                <label for="status" class="form-label">
                                    <i class="material-icons">list</i> Status
                                </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="process" {{ request('status') == 'process' ? 'selected' : '' }}>Diproses</option>
                                    <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>

                            <!-- Priority Filter -->
                            <div class="col-md-2 mb-3">
                                <label for="prioritas" class="form-label">
                                    <i class="material-icons">priority_high</i> Prioritas
                                </label>
                                <select class="form-select" id="prioritas" name="prioritas">
                                    <option value="">Semua Prioritas</option>
                                    <option value="high" {{ request('prioritas') == 'high' ? 'selected' : '' }}>Tinggi</option>
                                    <option value="medium" {{ request('prioritas') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                    <option value="low" {{ request('prioritas') == 'low' ? 'selected' : '' }}>Rendah</option>
                                </select>
                            </div>

                            <!-- Date From -->
                            <div class="col-md-2 mb-3">
                                <label for="tanggal_dari" class="form-label">
                                    <i class="material-icons">date_range</i> Dari Tanggal
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="tanggal_dari" 
                                       name="tanggal_dari" 
                                       value="{{ request('tanggal_dari') }}">
                            </div>

                            <!-- Date To -->
                            <div class="col-md-2 mb-3">
                                <label for="tanggal_sampai" class="form-label">
                                    <i class="material-icons">date_range</i> Sampai Tanggal
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="tanggal_sampai" 
                                       name="tanggal_sampai" 
                                       value="{{ request('tanggal_sampai') }}">
                            </div>

                            <!-- Filter Buttons -->
                            <div class="col-md-1 mb-3 d-flex align-items-end">
                                <div class="btn-group-vertical" role="group">
                                    <button type="submit" class="btn btn-primary mb-1">
                                        <i class="material-icons">search</i> Filter
                                    </button>
                                    <a href="{{ route('dashboard_it_manager.view-report') }}" class="btn btn-secondary">
                                        <i class="material-icons">refresh</i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Info -->
                        @if(request()->hasAny(['search', 'status', 'prioritas', 'tanggal_dari', 'tanggal_sampai']))
                            <div class="alert alert-info filter-info">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <i class="material-icons">info</i>
                                        <strong>Filter aktif:</strong>
                                        <div class="mt-2">
                                            @if(request('search'))
                                                <span class="badge bg-primary">Pencarian: "{{ request('search') }}"</span>
                                            @endif
                                            @if(request('status'))
                                                <span class="badge bg-info">Status: {{ ucfirst(request('status')) }}</span>
                                            @endif
                                            @if(request('prioritas'))
                                                <span class="badge bg-warning">Prioritas: {{ ucfirst(request('prioritas')) }}</span>
                                            @endif
                                            @if(request('tanggal_dari'))
                                                <span class="badge bg-success">Dari: {{ request('tanggal_dari') }}</span>
                                            @endif
                                            @if(request('tanggal_sampai'))
                                                <span class="badge bg-danger">Sampai: {{ request('tanggal_sampai') }}</span>
                                            @endif
                                        </div>
                                        <small class="text-muted mt-1 d-block">
                                            Menampilkan {{ $reports->count() }} dari {{ $reports->total() }} laporan
                                        </small>
                                    </div>
                                    <a href="{{ route('dashboard_it_manager.view-report') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="material-icons">clear</i> Hapus Semua Filter
                                    </a>
                                </div>
                            </div>
                        @else
                            <!-- Quick Filter Shortcuts -->
                            <div class="alert alert-light">
                                <strong><i class="material-icons">speed</i> Filter Cepat:</strong>
                                <div class="mt-2">
                                    <a href="{{ route('dashboard_it_manager.view-report', ['status' => 'waiting']) }}" class="btn btn-sm btn-outline-info me-1">
                                        <i class="material-icons">schedule</i> Menunggu ({{ $waitingReports }})
                                    </a>
                                    <a href="{{ route('dashboard_it_manager.view-report', ['status' => 'process']) }}" class="btn btn-sm btn-outline-info me-1">
                                        <i class="material-icons">build</i> Diproses ({{ $processReports }})
                                    </a>
                                    <a href="{{ route('dashboard_it_manager.view-report', ['prioritas' => 'high']) }}" class="btn btn-sm btn-outline-danger me-1">
                                        <i class="material-icons">priority_high</i> Prioritas Tinggi ({{ $highPriorityReports }})
                                    </a>
                                    <a href="{{ route('dashboard_it_manager.view-report', ['tanggal_dari' => now()->format('Y-m-d')]) }}" class="btn btn-sm btn-outline-success">
                                        <i class="material-icons">today</i> Hari Ini
                                    </a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="widget-list">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">
                                    <i class="material-icons">list_alt</i> Data Laporan Gangguan IT
                                    @if(request()->hasAny(['search', 'status', 'prioritas', 'tanggal_dari', 'tanggal_sampai']))
                                        <span class="badge bg-info">{{ $reports->total() }} hasil filter</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $reports->total() }} total</span>
                                    @endif
                                </h5>
                                <small class="text-muted">Kelola status dan monitor progress laporan (Maks. 10 per halaman)</small>
                            </div>
                            <a href="{{ route('dashboard_it_manager.create-report') }}" class="btn btn-primary">
                                <i class="material-icons">add_box</i> Tambah Laporan
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12 widget-holder">
                        <div class="widget-bg">
                            <div class="widget-body clearfix">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 60px;">ID</th>
                                                <th style="min-width: 200px;">Laporan</th>
                                                <th style="min-width: 120px;">Lokasi & User</th>
                                                <th style="width: 140px;">Status</th>
                                                <th style="width: 100px;">Prioritas</th>
                                                <th style="width: 120px;">Lampiran</th>
                                                <th style="min-width: 200px;">Timeline & Waktu Selesai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @forelse ($reports as $report)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary">#{{ $report->report_id }}</span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <h6 class="mb-1">{{ Str::limit($report->title, 50) }}</h6>
                                                        <small class="text-muted">{{ Str::limit($report->description, 80) }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <i class="material-icons text-info" style="font-size: 16px;">location_on</i>
                                                        <strong>{{ $report->lokasi }}</strong>
                                                        <br>
                                                        <i class="material-icons text-success" style="font-size: 16px;">person</i>
                                                        <small>{{ $report->user->name ?? '-' }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <form method="POST" action="{{ route('dashboard_it_manager.update-status', $report->report_id) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="status" class="form-select form-select-sm status-select" 
                                                                onchange="this.form.submit()" 
                                                                data-report-id="{{ $report->report_id }}">
                                                            <option value="waiting" {{ $report->status == 'waiting' ? 'selected' : '' }} 
                                                                    class="text-info">
                                                                🕐 Menunggu
                                                            </option>
                                                            <option value="process" {{ $report->status == 'process' ? 'selected' : '' }} 
                                                                    class="text-info">
                                                                🔧 Diproses
                                                            </option>
                                                            <option value="finished" {{ $report->status == 'finished' ? 'selected' : '' }} 
                                                                    class="text-success">
                                                                ✅ Selesai
                                                            </option>
                                                        </select>
                                                    </form>
                                                    <!-- Status badge for visual reference -->
                                                    <div class="mt-1">
                                                        @if($report->status == 'waiting')
                                                            <small class="badge bg-info text-dark">
                                                                <i class="material-icons" style="font-size: 12px;">schedule</i> Menunggu
                                                            </small>
                                                        @elseif($report->status == 'process')
                                                            <small class="badge bg-info">
                                                                <i class="material-icons" style="font-size: 12px;">build</i> Diproses
                                                            </small>
                                                        @else
                                                            <small class="badge bg-success">
                                                                <i class="material-icons" style="font-size: 12px;">check_circle</i> Selesai
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($report->prioritas == 'high')
                                                        <span class="badge bg-danger">
                                                            <i class="material-icons" style="font-size: 14px;">priority_high</i> Tinggi
                                                        </span>
                                                    @elseif($report->prioritas == 'medium')
                                                        <span class="badge bg-info text-dark">
                                                            <i class="material-icons" style="font-size: 14px;">remove</i> Sedang
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="material-icons" style="font-size: 14px;">low_priority</i> Rendah
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if($report->attachment)
                                                        @php
                                                            $ext = pathinfo($report->attachment, PATHINFO_EXTENSION);
                                                            $isImage = in_array(strtolower($ext), ['jpg','jpeg','png','gif','bmp','webp']);
                                                            $fileName = basename($report->attachment);
                                                        @endphp
                                                        
                                                        <div class="attachment-preview">
                                                            @if($isImage)
                                                                <!-- Image Preview -->
                                                                <div class="image-preview mb-1">
                                                                    <a href="{{ asset('storage/' . $report->attachment) }}" 
                                                                       target="_blank" 
                                                                       class="d-block"
                                                                       title="Klik untuk melihat gambar penuh">
                                                                        <img src="{{ asset('storage/' . $report->attachment) }}" 
                                                                             alt="Preview" 
                                                                             style="max-width:60px; max-height:60px; object-fit:cover; border-radius:4px; border:1px solid #ddd;">
                                                                    </a>
                                                                </div>
                                                                <small class="text-muted d-block">{{ Str::limit($fileName, 15) }}</small>
                                                                <a href="{{ asset('storage/' . $report->attachment) }}" 
                                                                   target="_blank" 
                                                                   class="btn btn-xs btn-outline-primary mt-1">
                                                                    <i class="material-icons" style="font-size: 12px;">zoom_in</i> Lihat
                                                                </a>
                                                            @else
                                                                <!-- File Icon -->
                                                                <div class="file-preview mb-1">
                                                                    <div class="file-icon p-2 mb-1" style="background: #f8f9fa; border-radius: 4px; border: 1px solid #ddd;">
                                                                        @if(in_array(strtolower($ext), ['pdf']))
                                                                            <i class="material-icons text-danger" style="font-size: 32px;">picture_as_pdf</i>
                                                                        @elseif(in_array(strtolower($ext), ['doc', 'docx']))
                                                                            <i class="material-icons text-primary" style="font-size: 32px;">description</i>
                                                                        @elseif(in_array(strtolower($ext), ['xls', 'xlsx']))
                                                                            <i class="material-icons text-success" style="font-size: 32px;">table_chart</i>
                                                                        @elseif(in_array(strtolower($ext), ['zip', 'rar']))
                                                                            <i class="material-icons text-warning" style="font-size: 32px;">archive</i>
                                                                        @else
                                                                            <i class="material-icons text-secondary" style="font-size: 32px;">insert_drive_file</i>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <small class="text-muted d-block">{{ Str::limit($fileName, 15) }}</small>
                                                                <small class="badge bg-light text-dark">{{ strtoupper($ext) }}</small>
                                                                <a href="{{ asset('storage/' . $report->attachment) }}" 
                                                                   target="_blank" 
                                                                   class="btn btn-xs btn-outline-primary mt-1 d-block">
                                                                    <i class="material-icons" style="font-size: 12px;">download</i> Unduh
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="no-attachment text-muted">
                                                            <i class="material-icons" style="font-size: 24px;">attach_file</i>
                                                            <small class="d-block">Tidak ada lampiran</small>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        <small class="text-muted">
                                                            <i class="material-icons" style="font-size: 16px;">event</i> 
                                                            Dilaporkan:
                                                        </small>
                                                        <br>
                                                        <strong>{{ \Carbon\Carbon::parse($report->time_report)->timezone('Asia/Jakarta')->format('d/m/Y') }}</strong>
                                                        
                                                        @if($report->time_finished)
                                                            <br>
                                                            <small class="text-success">
                                                                <i class="material-icons" style="font-size: 16px;">done</i> 
                                                                Selesai:
                                                            </small>
                                                            <br>
                                                            <strong class="text-success">{{ \Carbon\Carbon::parse($report->time_finished)->timezone('Asia/Jakarta')->format('d/m/Y') }}</strong>
                                                            
                                                            @php
                                                                $startTime = \Carbon\Carbon::parse($report->time_report)->timezone('Asia/Jakarta');
                                                                $endTime = \Carbon\Carbon::parse($report->time_finished)->timezone('Asia/Jakarta');
                                                                $duration = $endTime->diff($startTime);
                                                            @endphp
                                                            <br>
                                                            <small class="badge bg-light text-dark">
                                                                <i class="material-icons" style="font-size: 12px;">timer</i>
                                                                @if($duration->days > 0)
                                                                    {{ $duration->days }}h 
                                                                @endif
                                                                {{ $duration->h }}j {{ $duration->i }}m
                                                            </small>
                                                        @else
                                                            <br>
                                                            <small class="text-info mb-2 d-block">
                                                                <i class="material-icons" style="font-size: 16px;">hourglass_empty</i> 
                                                                Belum selesai
                                                            </small>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="material-icons" style="font-size: 48px;">inbox</i>
                                                        <h5 class="mt-2">Belum ada laporan</h5>
                                                        <p>Klik tombol "Tambah Laporan" untuk membuat laporan baru</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination Section -->
                            @if($reports->hasPages())
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div>
                                        <p class="text-muted mb-0">
                                            Menampilkan {{ $reports->firstItem() }} - {{ $reports->lastItem() }} 
                                            dari {{ $reports->total() }} laporan
                                        </p>
                                    </div>
                                    <div>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                {{-- Previous Page Link --}}
                                                @if ($reports->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <span class="page-link">Previous</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $reports->appends(request()->query())->previousPageUrl() }}">Previous</a>
                                                    </li>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @foreach ($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                                                    @if ($page == $reports->currentPage())
                                                        <li class="page-item active">
                                                            <span class="page-link">{{ $page }}</span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link" href="{{ $reports->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($reports->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $reports->appends(request()->query())->nextPageUrl() }}">Next</a>
                                                    </li>
                                                @else
                                                    <li class="page-item disabled">
                                                        <span class="page-link">Next</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            @else
                                <div class="mt-4">
                                    <p class="text-muted text-center">
                                        @if(request()->hasAny(['search', 'status', 'prioritas', 'tanggal_dari', 'tanggal_sampai']))
                                            Tidak ada laporan yang sesuai dengan filter yang dipilih.
                                        @else
                                            Menampilkan semua {{ $reports->count() }} laporan.
                                        @endif
                                    </p>
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

<!-- Image Modal for Full View -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Preview Lampiran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Lampiran" class="img-fluid">
            </div>
            <div class="modal-footer">
                <a id="downloadLink" href="" target="_blank" class="btn btn-primary">
                    <i class="material-icons">download</i> Download Asli
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>

// Add loading state to status selects
document.addEventListener('DOMContentLoaded', function() {
    // Initialize image modal functionality
    const imageLinks = document.querySelectorAll('.image-preview a');
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const downloadLink = document.getElementById('downloadLink');
    
    imageLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const imageSrc = this.href;
            modalImage.src = imageSrc;
            downloadLink.href = imageSrc;
            
            // Show modal (Bootstrap 5 syntax)
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = new bootstrap.Modal(imageModal);
                modal.show();
            } else {
                // Fallback for jQuery/Bootstrap 4
                $('#imageModal').modal('show');
            }
        });
    });
    
    const statusSelects = document.querySelectorAll('.status-select');
    
    statusSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            // Add loading state
            this.disabled = true;
            this.style.opacity = '0.7';
            
            // Create loading indicator
            const loadingSpinner = document.createElement('div');
            loadingSpinner.innerHTML = '<small class="text-muted">Mengupdate...</small>';
            loadingSpinner.className = 'loading-indicator';
            this.parentElement.appendChild(loadingSpinner);
            
            // Show additional message when setting to finished
            if (this.value === 'finished') {
                // Add info message that completion date will be set automatically
                const infoSpinner = document.createElement('div');
                infoSpinner.innerHTML = '<small class="text-info">Tanggal selesai akan diisi otomatis...</small>';
                infoSpinner.className = 'loading-indicator';
                this.parentElement.appendChild(infoSpinner);
            }
        });
    });
    
    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});

    });
});

// Clear search function
function clearSearch() {
    document.getElementById('search').value = '';
    document.getElementById('filterForm').submit();
}

// Show success message for status updates
@if(session('success'))
    // Show alert notification
    document.addEventListener('DOMContentLoaded', function() {
        showSuccessAlert('{{ session('success') }}');
    });
@endif

// Function to show success alert
function showSuccessAlert(message) {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
    alert.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    alert.innerHTML = `
        <strong><i class="material-icons" style="font-size: 16px;">check_circle</i> Berhasil!</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Add to body
    document.body.appendChild(alert);
    
    // Auto remove after 3 seconds
    setTimeout(function() {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 3000);
}

// Auto-submit filter when select changes (optional UX improvement)
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('#status, #prioritas');
    const dateInputs = document.querySelectorAll('#tanggal_dari, #tanggal_sampai');
    
    // Auto-submit on select change
    filterSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    
    // Auto-submit on date change
    dateInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
    
    // Search input with delay
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (this.value.length > 2 || this.value.length === 0) {
                document.getElementById('filterForm').submit();
            }
        }, 1000); // 1 second delay
    });
    
    // Add loading state to form submission
    document.getElementById('filterForm').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="material-icons">hourglass_empty</i> Filtering...';
            submitBtn.disabled = true;
        }
    });
});
</script>
</body>
</html>
