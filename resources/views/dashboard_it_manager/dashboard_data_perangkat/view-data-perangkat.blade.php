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
                    <h6 class="page-title-heading mr-0 mr-r-5">Data Perangkat Jaringan</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Kelola dan pantau perangkat jaringan</p>
                </div>
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_it_manager.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Perangkat Jaringan</li>
                    </ol>
                </div>
            </div>
            <div class="widget-list">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <a href="{{ route('dashboard_it_manager.dashboard') }}" 
                                    class="btn btn-secondary">
                                    <i class="material-icons">arrow_back</i> Kembali ke Dashboard
                                </a>
                                <a href="{{ route('dashboard_it_manager.create-perangkat') }}"
                                    class="btn btn-primary btn-xl px-4 fs-16">
                                    <span><i class="material-icons">add_box</i> Tambah Perangkat</span>
                                </a>
                            </div>
                            <a href="{{ route('dashboard_it_manager.view-vlan') }}" 
                                class="btn btn-secondary">
                                <i class="material-icons">settings_ethernet</i> Daftar VLAN
                            </a>
                        </div>
                    </div>

                    <div class="col-md-12 widget-holder">
                        <div class="widget-bg">
                            <div class="widget-heading clearfix">
                                <h5><i class="material-icons">router</i> Data Perangkat Jaringan</h5>
                            </div>
                            <div class="widget-body clearfix">
                                <table class="table table-editable table-responsive">
                                    <thead>
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
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($devices as $device)
                                            <tr>
                                                <td>{{ $device->device_id }}</td>
                                                <td>{{ $device->name }}</td>
                                                <td>{{ $device->tipe }}</td>
                                                <td>{{ $device->ip_address }}</td>
                                                <td>{{ $device->mac_address }}</td>
                                                <td>{{ $device->vlan->name_vlan ?? '-' }}</td>
                                                <td>{{ $device->lokasi }}</td>
                                                <td>{{ $device->status }}</td>
                                                <td>{{ $device->uptime }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('dashboard_it_manager.edit-perangkat', $device->device_id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="material-icons">create</i> Edit
                                                    </a>
                                                    <form
                                                        action="{{ route('dashboard_it_manager.destroy-perangkat', $device->device_id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Hapus perangkat ini?')">
                                                            <i class="material-icons">delete_sweep</i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">Tidak ada perangkat ditemukan.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div><!-- /.widget-body -->
                        </div><!-- /.widget-bg -->
                    </div><!-- /.widget-holder -->
                </div><!-- /.row -->
            </div><!-- /.widget-list -->
        </main><!-- /.main-wrapper -->
        @include('partials.footer')
    </div><!-- /.wrapper -->
</body>
</html>
