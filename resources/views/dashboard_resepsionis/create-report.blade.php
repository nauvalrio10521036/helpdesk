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
                    <h6 class="page-title-heading mr-0 mr-r-5">Buat Laporan Baru</h6>
                    <p class="page-title-description mr-0 d-none d-md-inline-block">Form untuk melaporkan masalah IT</p>
                </div>
                <!-- /.page-title-left -->
                <div class="page-title-right d-none d-sm-inline-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard_resepsionis.dashboard') }}">Dashboard</a></li>
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
                                <i class="material-icons">assignment</i> Form Laporan Masalah IT
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

                            <form action="{{ route('dashboard_resepsionis.store-report') }}" method="POST" enctype="multipart/form-data">
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
                                               placeholder="Contoh: Internet tidak bisa diakses di kamar 101"
                                               required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Tuliskan judul yang jelas dan spesifik mengenai masalah yang dihadapi</small>
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
                                               placeholder="Contoh: Kamar 101, Lobby, Restaurant"
                                               required>
                                        @error('lokasi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Sebutkan lokasi yang spesifik di mana masalah terjadi</small>
                                    </div>

                                    <!-- Prioritas -->
                                    <div class="col-md-6 mb-3">
                                        <label for="prioritas" class="form-label fw-bold">
                                            Tingkat Prioritas <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('prioritas') is-invalid @enderror" 
                                                id="prioritas" 
                                                name="prioritas" 
                                                required>
                                            <option value="">Pilih Prioritas</option>
                                            <option value="low" {{ old('prioritas') == 'low' ? 'selected' : '' }}>
                                                Rendah - Tidak mengganggu operasional
                                            </option>
                                            <option value="medium" {{ old('prioritas') == 'medium' ? 'selected' : '' }}>
                                                Sedang - Sedikit mengganggu operasional
                                            </option>
                                            <option value="high" {{ old('prioritas') == 'high' ? 'selected' : '' }}>
                                                Tinggi - Sangat mengganggu operasional
                                            </option>
                                        </select>
                                        @error('prioritas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Device (Opsional) -->
                                    <div class="col-md-6 mb-3">
                                        <label for="device_id" class="form-label fw-bold">
                                            Perangkat Terkait (Opsional)
                                        </label>
                                        <select class="form-select @error('device_id') is-invalid @enderror" 
                                                id="device_id" 
                                                name="device_id">
                                            <option value="">Pilih Perangkat (jika terkait)</option>
                                            @foreach($devices as $device)
                                                <option value="{{ $device->device_id }}" {{ old('device_id') == $device->device_id ? 'selected' : '' }}>
                                                    {{ $device->name }} ({{ $device->lokasi }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('device_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Pilih perangkat jika masalah terkait dengan perangkat tertentu</small>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="col-12 mb-3">
                                        <label for="description" class="form-label fw-bold">
                                            Deskripsi Masalah <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="5" 
                                                  placeholder="Jelaskan masalah secara detail..."
                                                  required>{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            Jelaskan masalah secara detail, kapan terjadi, dampak yang dirasakan, dan langkah yang sudah dicoba
                                        </small>
                                    </div>

                                    <!-- File Attachment -->
                                    <div class="col-12 mb-4">
                                        <label for="attachment" class="form-label fw-bold">
                                            Lampiran (Opsional)
                                        </label>
                                        <input type="file" 
                                               class="form-control @error('attachment') is-invalid @enderror" 
                                               id="attachment" 
                                               name="attachment"
                                               accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                        @error('attachment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="material-icons" style="font-size: 16px;">info</i>
                                            Anda dapat melampirkan foto, screenshot, atau dokumen pendukung. 
                                            Format yang didukung: JPG, JPEG, PNG, PDF, DOC, DOCX (Maksimal 2MB)
                                        </small>
                                    </div>
                                </div>

                                <!-- Preview Area for Image -->
                                <div id="imagePreview" class="mb-3" style="display: none;">
                                    <label class="form-label fw-bold">Preview Gambar:</label>
                                    <div class="border rounded p-2">
                                        <img id="previewImg" src="" alt="Preview" style="max-width: 100%; max-height: 300px;">
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('dashboard_resepsionis.dashboard') }}" class="btn btn-secondary">
                                                <i class="material-icons">arrow_back</i> Kembali
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="material-icons">send</i> Kirim Laporan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="card mt-4 border-info">
                        <div class="card-header bg-info text-white">
                            <h6 class="m-0"><i class="material-icons">info</i> Informasi Penting</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="material-icons text-warning">schedule</i> Waktu Respon:</h6>
                                    <ul class="small">
                                        <li><strong>Prioritas Tinggi:</strong> Maksimal 2 jam</li>
                                        <li><strong>Prioritas Sedang:</strong> Maksimal 4 jam</li>
                                        <li><strong>Prioritas Rendah:</strong> Maksimal 24 jam</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="material-icons text-success">phone</i> Kontak Darurat:</h6>
                                    <p class="small mb-0">
                                        Untuk masalah kritis yang membutuhkan penanganan segera, 
                                        hubungi IT Support: <strong>ext. 111</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <!-- /.main-wrapper -->
    </div>
    <!-- /.wrapper -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview image when file is selected
    const fileInput = document.getElementById('attachment');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const fileType = file.type;
            
            // Check if it's an image
            if (fileType.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        } else {
            imagePreview.style.display = 'none';
        }
    });

    // Form validation enhancement
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const lokasi = document.getElementById('lokasi').value.trim();
        const prioritas = document.getElementById('prioritas').value;

        if (!title || !description || !lokasi || !prioritas) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi!');
            return false;
        }

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="material-icons">hourglass_empty</i> Mengirim...';
        submitBtn.disabled = true;
    });
});
</script>

@include('partials.footer')
</body>
</html>
