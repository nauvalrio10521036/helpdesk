<!DOCTYPE html>
<html lang="en">
@include('partials.header')
<body class="sidebar-dark sidebar-expand navbar-brand-dark header-light">
    <div id="wrapper" class="wrapper">
        @include('partials.nav')
        @include('partials.sidebar')

        <main class="main-wrapper clearfix">
            <!-- Page Title Area -->
            <div class="row page-title clearfix">
                <div class="page-title-left">
                    <h6 class="page-title-heading mr-0 mr-r-5">Edit VLAN</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Form untuk mengedit data VLAN jaringan</p>
                </div>
                <!-- /.page-title-left -->
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_it_manager.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_it_manager.view-data-perangkat') }}">Data Perangkat</a></li>
                        <li class="breadcrumb-item active">Edit VLAN</li>
                    </ol>
                </div>
                <!-- /.page-title-right -->
            </div>

            <!-- Form -->
            <div class="row">
                <div class="col-xl-8 col-lg-10 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="material-icons">edit</i> Form Edit Data VLAN
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Alert Errors -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <h6><i class="material-icons">error</i> Terdapat kesalahan:</h6>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('dashboard_it_manager.update-vlan', $vlan->vlan_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <!-- Nama VLAN -->
                                    <div class="col-12 mb-3">
                                        <label for="name_vlan" class="form-label fw-bold">
                                            Nama VLAN <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name_vlan') is-invalid @enderror" 
                                               id="name_vlan" 
                                               name="name_vlan" 
                                               value="{{ old('name_vlan', $vlan->name_vlan) }}" 
                                               placeholder="Contoh: VLAN_GUEST, VLAN_ADMIN"
                                               required>
                                        @error('name_vlan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Berikan nama VLAN yang jelas dan mudah dikenali</small>
                                    </div>

                                    <!-- Subnet -->
                                    <div class="col-md-6 mb-3">
                                        <label for="subnet" class="form-label fw-bold">
                                            Subnet <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('subnet') is-invalid @enderror" 
                                               id="subnet" 
                                               name="subnet" 
                                               value="{{ old('subnet', $vlan->subnet) }}" 
                                               placeholder="Contoh: 192.168.1.0/24"
                                               required>
                                        @error('subnet')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Format: IP_Address/CIDR</small>
                                    </div>

                                    <!-- Port -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name_port" class="form-label fw-bold">
                                            Nama Port <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name_port') is-invalid @enderror" 
                                               id="name_port" 
                                               name="name_port" 
                                               value="{{ old('name_port', $vlan->name_port) }}" 
                                               placeholder="Contoh: eth0/1, GigabitEthernet0/1"
                                               required>
                                        @error('name_port')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Nama interface atau port yang digunakan</small>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="col-12 mb-4">
                                        <label for="deskripsi" class="form-label fw-bold">
                                            Deskripsi <small class="text-muted">(opsional)</small>
                                        </label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                  id="deskripsi" 
                                                  name="deskripsi" 
                                                  rows="3" 
                                                  placeholder="Deskripsi atau catatan mengenai VLAN ini...">{{ old('deskripsi', $vlan->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Penjelasan fungsi atau tujuan VLAN ini</small>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('dashboard_it_manager.view-data-perangkat') }}" class="btn btn-secondary">
                                                <i class="material-icons">arrow_back</i> Kembali
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="material-icons">update</i> Update VLAN
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main><!-- /.main-wrapper -->
    </div><!-- /.wrapper -->

    @include('partials.footer')
</body>
</html>
