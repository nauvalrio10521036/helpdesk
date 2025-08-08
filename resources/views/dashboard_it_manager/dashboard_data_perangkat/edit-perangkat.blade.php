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
                    <h6 class="page-title-heading mr-0 mr-r-5">Edit Perangkat Jaringan</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Form untuk mengedit perangkat jaringan</p>
                </div>
                <!-- /.page-title-left -->
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_it_manager.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_it_manager.view-data-perangkat') }}">Data Perangkat</a></li>
                        <li class="breadcrumb-item active">Edit Perangkat</li>
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
                                <i class="material-icons">edit</i> Form Edit Data Perangkat
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

                            <form action="{{ route('dashboard_it_manager.update-perangkat', $device->device_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <!-- Nama Perangkat -->
                                    <div class="col-12 mb-3">
                                        <label for="name" class="form-label fw-bold">
                                            Nama Perangkat <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $device->name) }}" 
                                               placeholder="Contoh: AP-Lantai-2-001"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Berikan nama yang jelas dan mudah dikenali</small>
                                    </div>

                                    <!-- Tipe -->
                                    <div class="col-md-6 mb-3">
                                        <label for="tipe" class="form-label fw-bold">
                                            Tipe Perangkat <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('tipe') is-invalid @enderror" 
                                                id="tipe" 
                                                name="tipe" 
                                                required>
                                            <option value="">Pilih Tipe</option>
                                            <option value="access_point" {{ old('tipe', $device->tipe) == 'access_point' ? 'selected' : '' }}>Access Point</option>
                                            <option value="switch_poe" {{ old('tipe', $device->tipe) == 'switch_poe' ? 'selected' : '' }}>Switch POE</option>
                                            <option value="mikrotik" {{ old('tipe', $device->tipe) == 'mikrotik' ? 'selected' : '' }}>Mikrotik</option>
                                            <option value="switch_hub" {{ old('tipe', $device->tipe) == 'switch_hub' ? 'selected' : '' }}>Switch Hub</option>
                                        </select>
                                        @error('tipe')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label fw-bold">
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" 
                                                name="status" 
                                                required>
                                            <option value="aktif" {{ old('status', $device->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="tidak_aktif" {{ old('status', $device->status) == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- IP Address -->
                                    <div class="col-md-6 mb-3">
                                        <label for="ip_address" class="form-label fw-bold">
                                            IP Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('ip_address') is-invalid @enderror" 
                                               id="ip_address" 
                                               name="ip_address" 
                                               value="{{ old('ip_address', $device->ip_address) }}" 
                                               placeholder="Contoh: 192.168.1.100"
                                               required>
                                        @error('ip_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- MAC Address -->
                                    <div class="col-md-6 mb-3">
                                        <label for="mac_address" class="form-label fw-bold">
                                            MAC Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('mac_address') is-invalid @enderror" 
                                               id="mac_address" 
                                               name="mac_address" 
                                               value="{{ old('mac_address', $device->mac_address) }}" 
                                               placeholder="Contoh: AA:BB:CC:DD:EE:FF"
                                               required>
                                        @error('mac_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- VLAN -->
                                    <div class="col-md-6 mb-3">
                                        <label for="vlan_id" class="form-label fw-bold">
                                            VLAN <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('vlan_id') is-invalid @enderror" 
                                                id="vlan_id" 
                                                name="vlan_id" 
                                                required>
                                            <option value="">Pilih VLAN</option>
                                            @foreach($vlans as $vlan)
                                                <option value="{{ $vlan->vlan_id }}" {{ old('vlan_id', $device->vlan_id) == $vlan->vlan_id ? 'selected' : '' }}>
                                                    {{ $vlan->name_vlan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('vlan_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Lokasi -->
                                    <div class="col-md-6 mb-3">
                                        <label for="lokasi" class="form-label fw-bold">
                                            Lokasi <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('lokasi') is-invalid @enderror" 
                                               id="lokasi" 
                                               name="lokasi" 
                                               value="{{ old('lokasi', $device->lokasi) }}" 
                                               placeholder="Contoh: Ruang Server, Lantai 2"
                                               required>
                                        @error('lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Uptime -->
                                    <div class="col-md-6 mb-3">
                                        <label for="uptime" class="form-label fw-bold">
                                            Uptime <small class="text-muted">(opsional)</small>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('uptime') is-invalid @enderror" 
                                               id="uptime" 
                                               name="uptime" 
                                               value="{{ old('uptime', $device->uptime) }}" 
                                               placeholder="Contoh: 30 hari 5 jam">
                                        @error('uptime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Durasi perangkat sudah berjalan</small>
                                    </div>

                                    <!-- Keterangan -->
                                    <div class="col-12 mb-4">
                                        <label for="keterangan" class="form-label fw-bold">
                                            Keterangan <small class="text-muted">(opsional)</small>
                                        </label>
                                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                                  id="keterangan" 
                                                  name="keterangan" 
                                                  rows="3" 
                                                  placeholder="Catatan tambahan mengenai perangkat...">{{ old('keterangan', $device->keterangan) }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Informasi tambahan atau catatan khusus tentang perangkat</small>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('dashboard_it_manager.view-data-perangkat') }}" class="btn btn-secondary">
                                                <i class="material-icons">arrow_back</i> Kembali
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="material-icons">update</i> Update Perangkat
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
