<?php
@session_start();
include "koneksi.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>E-Learning Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        .btn-student {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-student:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .admin-link {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h4><i class="fas fa-graduation-cap"></i> Portal Siswa</h4>
            <p class="mb-0">Masuk untuk mengakses pembelajaran</p>
        </div>
        <div class="card-body p-4">
            <?php if(isset($_GET['error'])) { ?>
                <div class="alert alert-danger py-2">
                    <i class="fas fa-exclamation-triangle"></i> Login gagal! Username atau password salah.
                </div>
            <?php } ?>
            
            <form method="POST" action="inc/proses_login.php">
                <input type="hidden" name="tipe" value="siswa">
                
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-user"></i> Username</label>
                    <input type="text" name="user" class="form-control form-control-sm" placeholder="Masukkan username siswa" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="pass" class="form-control form-control-sm" placeholder="Masukkan password" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><i class="fas fa-school"></i> Kelas</label>
                    <select name="kelas" class="form-control form-control-sm" required>
                        <option value="">-- Pilih Kelas --</option>
                        <?php
                        $kelas = mysqli_query($db, "SELECT * FROM tb_kelas ORDER BY nama_kelas");
                        while($data_kelas = mysqli_fetch_array($kelas)) {
                            echo "<option value='$data_kelas[id_kelas]'>$data_kelas[nama_kelas]</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-student btn-sm w-100">
                    <i class="fas fa-sign-in-alt"></i> Login Siswa
                </button>
            </form>
            
            <div class="text-center mt-3">
                <small>Belum punya akun? <a href="?hal=daftar" class="text-decoration-none">Daftar sekarang</a></small>
            </div>
        </div>
    </div>
</div>

<div class="admin-link">
    <a href="admin/" class="btn btn-dark btn-sm" target="_blank" title="Buka Panel Admin">
        <i class="fas fa-cog"></i> Admin
    </a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>