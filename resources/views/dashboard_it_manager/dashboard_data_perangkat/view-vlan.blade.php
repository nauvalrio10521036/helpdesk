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
                    <h6 class="page-title-heading mr-0 mr-r-5">Data VLAN</h6>
                </div>
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">VLAN</li>
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
                                <a href="{{ route('dashboard_it_manager.create-vlan') }}" 
                                    class="btn btn-primary btn-xl px-4 fs-16">
                                    <span><i class="material-icons">add_box</i> Tambah VLAN</span>
                                </a>
                            </div>
                            <a href="{{ route('dashboard_it_manager.view-data-perangkat') }}" 
                                class="btn btn-secondary">
                                <i class="material-icons">router</i> Data Perangkat
                            </a>
                        </div>
                    </div>
                    <div class="col-md-12 widget-holder">
                        <div class="widget-bg">
                            <div class="widget-heading clearfix">
                                <h5><i class="material-icons">settings_ethernet</i> Data VLAN</h5>
                            </div>
                            <div class="widget-body clearfix">
                                <table class="table table-editable table-responsive">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama VLAN</th>
                                            <th>Subnet</th>
                                            <th>Port</th>
                                            <th>Deskripsi</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($vlans as $vlan)
                                            <tr>
                                                <td>{{ $vlan->vlan_id }}</td>
                                                <td>{{ $vlan->name_vlan }}</td>
                                                <td>{{ $vlan->subnet }}</td>
                                                <td>{{ $vlan->name_port }}</td>
                                                <td>{{ $vlan->deskripsi }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('dashboard_it_manager.edit-vlan', $vlan->vlan_id) }}" class="btn btn-sm btn-info">
                                                        <i class="material-icons">create</i> Edit
                                                    </a>
                                                    <form action="{{ route('dashboard_it_manager.destroy-vlan', $vlan->vlan_id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus VLAN ini?')">
                                                            <i class="material-icons">delete_sweep</i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6">Tidak ada VLAN ditemukan.</td>
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
    </div>
</body>
</html>
