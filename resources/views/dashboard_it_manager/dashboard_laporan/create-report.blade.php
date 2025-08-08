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
                    <h6 class="page-title-heading mr-0 mr-r-5">Buat Laporan Baru</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Form untuk membuat laporan gangguan IT</p>
                </div>
                <!-- /.page-title-left -->
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_it_manager.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Buat Laporan</li>
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
                                <i class="material-icons">assignment</i> Form Laporan Gangguan IT
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

                            <form action="{{ route('dashboard_it_manager.store-report') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <!-- Judul Laporan -->
                                    <div class="col-12 mb-3">
                                        <label for="title" class="form-label fw-bold">
                                            Judul Laporan <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('title') is-invalid @enderror" 
                                               id="title" 
                                               name="title" 
                                               value="{{ old('title') }}" 
                                               placeholder="Contoh: Gangguan koneksi internet lantai 2"
                                               required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Tuliskan judul yang jelas dan spesifik mengenai masalah yang dihadapi</small>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="col-12 mb-3">
                                        <label for="description" class="form-label fw-bold">
                                            Deskripsi Masalah <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="4" 
                                                  placeholder="Jelaskan detail masalah yang terjadi..."
                                                  required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Berikan penjelasan detail mengenai masalah yang terjadi</small>
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
                                               value="{{ old('lokasi') }}" 
                                               placeholder="Contoh: Ruang Server, Lantai 2"
                                               required>
                                        @error('lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- User -->
                                    <div class="col-md-6 mb-3">
                                        <label for="user_id" class="form-label fw-bold">
                                            User Pelapor <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('user_id') is-invalid @enderror" 
                                                id="user_id" 
                                                name="user_id" 
                                                required>
                                            <option value="">Pilih User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->user_id }}" {{ old('user_id') == $user->user_id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Device -->
                                    <div class="col-md-6 mb-3">
                                        <label for="device_id" class="form-label fw-bold">
                                            Perangkat Network <small class="text-muted">(opsional)</small>
                                        </label>
                                        <select class="form-select @error('device_id') is-invalid @enderror" 
                                                id="device_id" 
                                                name="device_id">
                                            <option value="">Pilih Perangkat</option>
                                            @foreach($devices as $device)
                                                <option value="{{ $device->device_id }}" {{ old('device_id') == $device->device_id ? 'selected' : '' }}>
                                                    {{ $device->name }} - {{ $device->lokasi }} ({{ ucfirst(str_replace('_', ' ', $device->tipe)) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('device_id')
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
                                            <option value="waiting" {{ old('status') == 'waiting' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="process" {{ old('status') == 'process' ? 'selected' : '' }}>Sedang Diproses</option>
                                            <option value="finished" {{ old('status') == 'finished' ? 'selected' : '' }}>Selesai</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Prioritas -->
                                    <div class="col-md-6 mb-3">
                                        <label for="prioritas" class="form-label fw-bold">
                                            Prioritas <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('prioritas') is-invalid @enderror" 
                                                id="prioritas" 
                                                name="prioritas" 
                                                required>
                                            <option value="low" {{ old('prioritas') == 'low' ? 'selected' : '' }}>Rendah</option>
                                            <option value="medium" {{ old('prioritas') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                            <option value="high" {{ old('prioritas') == 'high' ? 'selected' : '' }}>Tinggi</option>
                                        </select>
                                        @error('prioritas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Lampiran -->
                                    <div class="col-12 mb-3">
                                        <label for="attachment" class="form-label fw-bold">
                                            Lampiran <small class="text-muted">(opsional)</small>
                                        </label>
                                        <input type="file" 
                                               class="form-control @error('attachment') is-invalid @enderror" 
                                               id="attachment" 
                                               name="attachment" 
                                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                        @error('attachment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Format yang didukung: JPG, PNG, PDF, DOC, DOCX (Maksimal 5MB)</small>
                                    </div>

                                    <!-- Waktu Selesai -->
                                    <div class="col-12 mb-4">
                                        <label for="time_finished" class="form-label fw-bold">
                                            Waktu Selesai <small class="text-muted">(opsional)</small>
                                        </label>
                                        <div class="input-group">
                                            <input type="datetime-local" 
                                                   id="time_finished" 
                                                   name="time_finished" 
                                                   class="form-control @error('time_finished') is-invalid @enderror" 
                                                   value="{{ old('time_finished') }}">
                                            <button type="button" class="btn btn-outline-info" onclick="setNow()">
                                                <i class="material-icons">schedule</i> Sekarang
                                            </button>
                                        </div>
                                        @error('time_finished')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            Isi jika laporan sudah selesai. Klik "Sekarang" untuk mengisi waktu saat ini.
                                        </small>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('dashboard_it_manager.view-report') }}" class="btn btn-secondary">
                                                <i class="material-icons">arrow_back</i> Kembali
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="material-icons">save</i> Simpan Laporan
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

<script>
function setNow() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hour = String(now.getHours()).padStart(2, '0');
    const minute = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('time_finished').value = `${year}-${month}-${day}T${hour}:${minute}`;
}
</script>
</body>
</html>