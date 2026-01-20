<?php
@session_start();
require_once('koneksi.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Daftar E-Learning - Modern Design</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="style/assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="style/assets/css/style.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .registration-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
        }
        
        .registration-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .registration-form {
            padding: 2rem;
        }
        
        .form-floating > label {
            padding: 0.5rem 0.75rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .feature-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
        }
        
        .sticky-top {
            top: 0;
            z-index: 1020;
        }
        
        .navbar-sticky {
            position: sticky;
            top: 0;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <!-- Sticky Header -->
    <header class="sticky-top">
        <div class="container-fluid bg-white shadow-sm">
            <div class="container">
                <div class="row py-3">
                    <div class="col-md-12 text-center">
                        Sudah punya akun? <a href="./" class="btn btn-sm btn-outline-primary ms-2">Login Disini</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sticky Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary navbar-sticky shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="./">
                <img src="style/assets/img/logo.png" alt="Logo" height="40" class="me-2">
                <span class="fw-bold">E-Learning</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="?hal=daftar">
                            <i class="fas fa-user-plus me-1"></i>Registrasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?hal=daftar&page=berita">
                            <i class="fas fa-newspaper me-1"></i>Berita
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12">
                    <?php 
                    if(@$_GET['page'] == 'berita' && @$_GET['action'] == 'detail' && @$_GET['id_berita']) { 
                        // News Detail View
                    ?>
                    <!-- News Detail Content -->
                    <div class="registration-container">
                        <div class="registration-header">
                            <h1 class="display-5 fw-bold mb-3">
                                <i class="fas fa-newspaper me-3"></i>Detail Berita
                            </h1>
                            <p class="lead mb-0">
                                <a href="?hal=daftar&page=berita" class="btn btn-sm btn-outline-light">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Berita
                                </a>
                            </p>
                        </div>
                        
                        <div class="p-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    $sql_berita_detail = mysqli_query($db, "SELECT * FROM tb_berita WHERE id_berita = '$_GET[id_berita]' AND status='aktif'") or die($db->error);
                                    if(mysqli_num_rows($sql_berita_detail) > 0) {
                                        $data_berita_detail = mysqli_fetch_array($sql_berita_detail);
                                    ?>
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title text-primary"><?php echo $data_berita_detail['judul']; ?></h3>
                                            <hr>
                                            <div class="mb-3">
                                                <span class="me-3">
                                                    <i class="fas fa-user text-info"></i> 
                                                    <?php
                                                    if($data_berita_detail['penerbit'] == 'admin') {
                                                      echo "Admin";
                                                    } else {
                                                      $sql_pengajar = mysqli_query($db, "SELECT * FROM tb_pengajar WHERE id_pengajar = '$data_berita_detail[penerbit]' AND status='aktif'") or die($db->error);
                                                      $data_pengajar = mysqli_fetch_array($sql_pengajar);
                                                      echo $data_pengajar['nama_lengkap'];
                                                    } 
                                                    ?>
                                                </span>
                                                <span>
                                                    <i class="fas fa-calendar text-success"></i> <?php echo tgl_indo($data_berita_detail['tgl_posting']); ?>
                                                </span>
                                            </div>
                                            <div class="mt-4">
                                                <?php echo nl2br($data_berita_detail['isi']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } else { ?> 
                                    <div class="alert alert-warning text-center">
                                        <i class="fas fa-exclamation-triangle"></i> Berita tidak ditemukan atau telah diarsipkan.
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                    } else if(@$_GET['page'] == 'berita') { 
                        // News List View
                    ?>
                    <!-- News Content -->
                    <div class="registration-container">
                        <div class="registration-header">
                            <h1 class="display-5 fw-bold mb-3">
                                <i class="fas fa-newspaper me-3"></i>Berita & Informasi
                            </h1>
                            <p class="lead mb-0">Berita dan informasi terbaru dari sistem e-learning</p>
                        </div>
                        
                        <div class="p-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Berita</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <?php
                                                $sql_berita = mysqli_query($db, "SELECT * FROM tb_berita WHERE status = 'aktif' ORDER BY tgl_posting DESC") or die($db->error);
                                                if(mysqli_num_rows($sql_berita) > 0) {
                                                    while($data_berita = mysqli_fetch_array($sql_berita)) { 
                                                        $isi_berita = strlen($data_berita['isi']) > 200 ? substr($data_berita['isi'], 0, 200) . '...' : $data_berita['isi'];
                                                ?>  
                                                <div class="col-md-6 mb-4">
                                                    <div class="card h-100">
                                                        <div class="card-body">
                                                            <h5 class="card-title">
                                                                <a href="?hal=daftar&page=berita&action=detail&id_berita=<?php echo $data_berita['id_berita']; ?>" class="text-decoration-none text-dark">
                                                                    <i class="fas fa-file-alt me-2"></i><?php echo $data_berita['judul']; ?>
                                                                </a>
                                                            </h5>
                                                            <p class="card-text text-muted small">
                                                                <i class="fas fa-calendar me-1"></i><?php echo tgl_indo($data_berita['tgl_posting']); ?> | 
                                                                <i class="fas fa-user me-1"></i>
                                                                <?php
                                                                if($data_berita['penerbit'] == 'admin') {
                                                                  echo "Admin";
                                                                } else {
                                                                  $sql_pengajar = mysqli_query($db, "SELECT * FROM tb_pengajar WHERE id_pengajar = '$data_berita[penerbit]' AND status='aktif'") or die($db->error);
                                                                  $data_pengajar = mysqli_fetch_array($sql_pengajar);
                                                                  echo $data_pengajar['nama_lengkap'];
                                                                } 
                                                                ?>
                                                            </p>
                                                            <p class="card-text"><?php echo $isi_berita; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php } } else { ?> 
                                                <div class="col-md-12">
                                                    <div class="alert alert-info text-center">
                                                        <i class="fas fa-info-circle"></i> Belum ada berita yang dipublikasikan.
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <!-- Registration Content -->
                    <div class="registration-container">
                        <div class="registration-header">
                            <h1 class="display-5 fw-bold mb-3">
                                <i class="fas fa-user-plus me-3"></i>Daftar Akun E-Learning
                            </h1>
                            <p class="lead mb-0">Bergabunglah bersama ribuan pelajar untuk belajar secara online</p>
                        </div>
                        
                        <div class="row g-0">
                            <div class="col-lg-8">
                                <div class="registration-form">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="nis" placeholder="NIS *" required>
                                                <label for="nis">NIS *</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="nama_lengkap" placeholder="Nama Lengkap *" required>
                                                <label for="nama_lengkap">Nama Lengkap *</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="tempat_lahir" placeholder="Tempat Lahir *" required>
                                                <label for="tempat_lahir">Tempat Lahir *</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="date" class="form-control" id="tgl_lahir" required>
                                                <label for="tgl_lahir">Tanggal Lahir *</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <select class="form-select" id="jenis_kelamin" required>
                                                    <option value="">Pilih Jenis Kelamin</option>
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                                <label for="jenis_kelamin">Jenis Kelamin *</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <select class="form-select" id="agama" required>
                                                    <option value="">Pilih Agama</option>
                                                    <option value="Islam">Islam</option>
                                                    <option value="Kristen">Kristen</option>
                                                    <option value="Katholik">Katholik</option>
                                                    <option value="Hindu">Hindu</option>
                                                    <option value="Budha">Budha</option>
                                                    <option value="Konghucu">Konghucu</option>
                                                </select>
                                                <label for="agama">Agama *</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="nama_ayah" placeholder="Nama Ayah *" required>
                                                <label for="nama_ayah">Nama Ayah *</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="nama_ibu" placeholder="Nama Ibu *" required>
                                                <label for="nama_ibu">Nama Ibu *</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="tel" class="form-control" id="no_telp" placeholder="Nomor Telepon">
                                                <label for="no_telp">Nomor Telepon</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="email" class="form-control" id="email" placeholder="Email">
                                                <label for="email">Email</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <textarea class="form-control" id="alamat" placeholder="Alamat *" style="height: 100px;" required></textarea>
                                            <label for="alamat">Alamat *</label>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <select class="form-select" id="kelas" required>
                                                    <option value="">Pilih Kelas</option>
                                                    <?php
                                                    $sql_kelas = mysqli_query($db, "SELECT * from tb_kelas") or die ($db->error);
                                                    while($data_kelas = mysqli_fetch_array($sql_kelas)) {
                                                        echo '<option value="'.$data_kelas['id_kelas'].'">'.$data_kelas['nama_kelas'].'</option>';
                                                    } ?>
                                                </select>
                                                <label for="kelas">Kelas *</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="number" class="form-control" id="thn_masuk" placeholder="Tahun Masuk *" min="1950" max="2030" required>
                                                <label for="thn_masuk">Tahun Masuk *</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="gambar" class="form-label fw-bold">Foto (Opsional)</label>
                                        <input type="file" class="form-control" id="gambar" accept="image/*">
                                        <div class="form-text">Format: JPG, PNG, maksimal 2MB</div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="text" class="form-control" id="user" placeholder="Username *" required>
                                                <label for="user">Username *</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="password" class="form-control" id="pass" placeholder="Password *" required>
                                                <label for="pass">Password *</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-register btn-lg" onclick="submitRegistration()">
                                            <i class="fas fa-paper-plane me-2"></i>Daftar Sekarang
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 bg-light p-4">
                                <h5 class="mb-4 text-primary">
                                    <i class="fas fa-shield-alt me-2"></i>Keamanan & Kenyamanan
                                </h5>
                                
                                <div class="feature-box">
                                    <h6><i class="fas fa-lock text-success me-2"></i>Data Aman</h6>
                                    <small>Data pribadi Anda sepenuhnya aman dan tidak akan disebarluaskan</small>
                                </div>
                                
                                <div class="feature-box">
                                    <h6><i class="fas fa-sync-alt text-info me-2"></i>Persetujuan Admin</h6>
                                    <small>Akun Anda akan diverifikasi oleh admin sebelum aktif</small>
                                </div>
                                
                                <div class="feature-box">
                                    <h6><i class="fas fa-book-open text-warning me-2"></i>Akses Materi</h6>
                                    <small>Dapatkan akses ke ribuan materi pembelajaran berkualitas</small>
                                </div>
                                
                                <div class="feature-box">
                                    <h6><i class="fas fa-tasks text-danger me-2"></i>Tugas & Ujian</h6>
                                    <small>Kerjakan tugas dan ujian secara online dengan mudah</small>
                                </div>
                                
                                <div class="alert alert-info mt-4">
                                    <h6><i class="fas fa-info-circle me-2"></i>Catatan:</h6>
                                    <p class="mb-0 small">Tanda <strong>*</strong> menandakan field wajib diisi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?> <!-- Close the main else condition -->

    <!-- Footer -->
    <footer class="bg-dark text-white py-3 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <small class="d-block mb-1">E-Learning System - Sistem Pembelajaran Elektronik</small>
                    <small>&copy; <?php echo date('Y')?> E-Learning System. Hak Cipta Dilindungi.</small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function submitRegistration() {
            // Get form values
            const nis = document.getElementById('nis').value;
            const nama_lengkap = document.getElementById('nama_lengkap').value;
            const tempat_lahir = document.getElementById('tempat_lahir').value;
            const tgl_lahir = document.getElementById('tgl_lahir').value;
            const jenis_kelamin = document.getElementById('jenis_kelamin').value;
            const agama = document.getElementById('agama').value;
            const nama_ayah = document.getElementById('nama_ayah').value;
            const nama_ibu = document.getElementById('nama_ibu').value;
            const alamat = document.getElementById('alamat').value;
            const kelas = document.getElementById('kelas').value;
            const thn_masuk = document.getElementById('thn_masuk').value;
            const user = document.getElementById('user').value;
            const pass = document.getElementById('pass').value;
            const no_telp = document.getElementById('no_telp').value;
            const email = document.getElementById('email').value;

            // Validate required fields
            if (!nis || !nama_lengkap || !tempat_lahir || !tgl_lahir || !jenis_kelamin || 
                !agama || !nama_ayah || !nama_ibu || !alamat || !kelas || !thn_masuk || !user || !pass) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Harap isi semua field yang wajib diisi (bertanda *)',
                    confirmButtonText: 'OK'
                });
                return;
            }
            
            // Validate tahun masuk is a valid year
            const tahun = parseInt(thn_masuk);
            if (isNaN(tahun) || tahun < 1950 || tahun > 2030) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Tahun masuk harus berupa angka antara 1950 dan 2030',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Validate email if provided
            if (email && !isValidEmail(email)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Format email tidak valid',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Validate password length
            if (pass.length < 6) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Password minimal 6 karakter',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Prepare form data
            const formData = new FormData();
            formData.append('action', 'register');
            formData.append('nis', nis);
            formData.append('nama_lengkap', nama_lengkap);
            formData.append('tempat_lahir', tempat_lahir);
            formData.append('tgl_lahir', tgl_lahir);
            formData.append('jenis_kelamin', jenis_kelamin);
            formData.append('agama', agama);
            formData.append('nama_ayah', nama_ayah);
            formData.append('nama_ibu', nama_ibu);
            formData.append('no_telp', no_telp);
            formData.append('email', email);
            formData.append('alamat', alamat);
            formData.append('kelas', kelas);
            formData.append('thn_masuk', thn_masuk);
            formData.append('user', user);
            formData.append('pass', pass);

            // Handle file upload if provided
            const fileInput = document.getElementById('gambar');
            if (fileInput.files.length > 0) {
                formData.append('gambar', fileInput.files[0]);
            }

            // Show loading indicator
            const submitBtn = document.querySelector('.btn-register');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
            submitBtn.disabled = true;

            // Submit form via AJAX
            fetch('inc/process_registration.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Restore button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Reset form
                        document.getElementById('nis').value = '';
                        document.getElementById('nama_lengkap').value = '';
                        document.getElementById('tempat_lahir').value = '';
                        document.getElementById('tgl_lahir').value = '';
                        document.getElementById('jenis_kelamin').value = '';
                        document.getElementById('agama').value = '';
                        document.getElementById('nama_ayah').value = '';
                        document.getElementById('nama_ibu').value = '';
                        document.getElementById('no_telp').value = '';
                        document.getElementById('email').value = '';
                        document.getElementById('alamat').value = '';
                        document.getElementById('kelas').value = '';
                        document.getElementById('thn_masuk').value = '';
                        document.getElementById('user').value = '';
                        document.getElementById('pass').value = '';
                        document.getElementById('gambar').value = '';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restore button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mengirim data.',
                    confirmButtonText: 'OK'
                });
            });
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
</body>
</html>