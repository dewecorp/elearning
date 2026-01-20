<?php
@session_start();
include "koneksi.php";

if(@$_SESSION['admin']) {
    $sesi_id = @$_SESSION['admin'];
    $level = "admin";
    $sql_terlogin = mysqli_query($db, "SELECT * FROM tb_admin WHERE id_admin = '$sesi_id'") or die ($db->error);
} else if(@$_SESSION['pengajar']) {
    $sesi_id = @$_SESSION['pengajar'];
    $level = "pengajar";
    $sql_terlogin = mysqli_query($db, "SELECT * FROM tb_pengajar WHERE id_pengajar = '$sesi_id'") or die ($db->error);
} else {
    echo "<script>window.location='index.php';</script>";
}

$data_terlogin = mysqli_fetch_array($sql_terlogin);

// Define page titles
$page_titles = [
    '' => 'Dashboard',
    'pengajar' => 'Manajemen Pengajar',
    'siswaregistrasi' => 'Registrasi Siswa',
    'siswa' => 'Manajemen Siswa',
    'kelas' => 'Manajemen Kelas',
    'mapel' => 'Manajemen Mata Pelajaran',
    'materi' => 'Manajemen Materi',
    'quiz' => 'Manajemen Tugas/Quiz',
    'berita' => 'Manajemen Berita',
    'pengaturan' => 'Pengaturan'
];

// Handle special cases with sub-actions
$sub_action_titles = [
    'buatsoal' => 'Buat Soal',
    'daftarsoal' => 'Daftar Soal',
    'lihatsoal' => 'Lihat Soal',
    'tambahpilgan' => 'Tambah Soal Pilihan Ganda',
    'tambahessay' => 'Tambah Soal Essay',
    'pesertakoreksi' => 'Peserta Koreksi',
    'koreksi' => 'Koreksi Jawaban'
];

// Get current page
$current_page = isset($_GET['page']) ? $_GET['page'] : '';

// Set page title
$page_title = isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Halaman Tidak Ditemukan';

// Check if current page has sub-actions
if($current_page == 'quiz' && isset($_GET['action'])) {
    $sub_action = $_GET['action'];
    if(isset($sub_action_titles[$sub_action])) {
        $page_title = $sub_action_titles[$sub_action];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php if($current_page == ''): ?><title>Admin E-Learning | Dashboard</title><?php else: ?><title>Admin E-Learning | <?php echo $page_title; ?></title><?php endif; ?>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #1d4ed8;
            --primary-light: #93c5fd;
            --secondary-color: #0ea5e9;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f1f5f9;
            --sidebar-width: 280px;
            --navbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #0ea5e9 100%);
            min-height: 100vh;
        }

        /* Modern Sidebar */
        .modern-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #1e3a8a 0%, #3b82f6 100%);
            z-index: 1040;
            transition: transform 0.3s ease;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0,0,0,0.2);
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-logo {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar-user {
            padding: 15px;
            margin: 15px;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.05) 100%);
            border-radius: 12px;
            color: white;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            margin: 5px 15px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.2);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, #ffffff 0%, #dbeafe 100%);
            color: #1e3a8a;
            box-shadow: 0 4px 15px rgba(255,255,255,0.3);
            font-weight: 600;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        .nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 20px 15px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            background: #f8fafc;
            transition: margin-left 0.3s ease;
            padding-top: calc(var(--navbar-height) + 10px);
        }

        /* Modern Navbar */
        .modern-navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 0 30px;
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            z-index: 1030;
            transition: all 0.3s ease;
        }
        
        .modern-navbar.scrolled {
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            background: white;
            min-width: 200px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--dark-color);
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px 8px;
        }

        .dropdown-item:hover {
            background: var(--light-color);
            color: var(--dark-color);
        }

        .dropdown-divider {
            margin: 8px 0;
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        /* Modern Cards */
        .modern-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .modern-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .stat-card {
            padding: 25px;
            text-align: center;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            color: white;
        }

        .stat-icon.primary { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .stat-icon.success { background: linear-gradient(135deg, var(--success-color), #059669); }
        .stat-icon.warning { background: linear-gradient(135deg, var(--warning-color), #d97706); }
        .stat-icon.danger { background: linear-gradient(135deg, var(--danger-color), #dc2626); }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6b7280;
            font-weight: 500;
        }

        /* Responsive */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--primary-color);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .mobile-toggle:hover {
            background: rgba(79,70,229,0.1);
        }

        @media (max-width: 768px) {
            .modern-sidebar {
                transform: translateX(-100%);
            }

            .modern-sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .modern-navbar {
                left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .content-area {
                padding: 20px;
            }

            .stat-card {
                padding: 20px;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .stat-number {
                font-size: 24px;
            }
        }

        /* Button Styles */
        .btn-modern {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            border: none;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
        }

        /* Badge styling */
        .badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 500;
            border-radius: 0.375rem;
        }

        .bg-primary { background-color: var(--primary-color) !important; }
        .bg-success { background-color: var(--success-color) !important; }
        .bg-warning { background-color: var(--warning-color) !important; }
        .bg-danger { background-color: var(--danger-color) !important; }
        .bg-info { background-color: var(--secondary-color) !important; }

        .badge.bg-warning { color: #000 !important; }
        .badge.bg-light { color: #000 !important; }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="modern-sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-graduation-cap"></i>
                <span>E-Learning</span>
            </div>
        </div>

        

        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="./" class="nav-link <?php if(@$_GET['page'] == '') echo 'active'; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <?php if(@$_SESSION['admin']) { ?>
            <div class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#manajemenMenu" role="button" aria-expanded="false" aria-controls="manajemenMenu">
                    <i class="fas fa-users-cog"></i>
                    <span>Manajemen</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="manajemenMenu">
                    <div class="nav-item ms-3">
                        <a href="?page=pengajar" class="nav-link <?php if(@$_GET['page'] == 'pengajar') echo 'active'; ?>">
                            <i class="fas fa-user-tie"></i>
                            <span>Pengajar</span>
                        </a>
                    </div>
                    <div class="nav-item ms-3">
                        <a href="?page=siswa" class="nav-link <?php if(@$_GET['page'] == 'siswa') echo 'active'; ?>">
                            <i class="fas fa-user-graduate"></i>
                            <span>Siswa</span>
                        </a>
                    </div>
                    <div class="nav-item ms-3">
                        <a href="?page=siswaregistrasi" class="nav-link <?php if(@$_GET['page'] == 'siswaregistrasi') echo 'active'; ?>">
                            <i class="fas fa-user-plus"></i>
                            <span>Registrasi Siswa</span>
                        </a>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="nav-item">
                <a href="?page=kelas" class="nav-link <?php if(@$_GET['page'] == 'kelas') echo 'active'; ?>">
                    <i class="fas fa-chalkboard"></i>
                    <span>Kelas</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=mapel" class="nav-link <?php if(@$_GET['page'] == 'mapel') echo 'active'; ?>">
                    <i class="fas fa-book"></i>
                    <span>Mata Pelajaran</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=materi" class="nav-link <?php if(@$_GET['page'] == 'materi') echo 'active'; ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>Materi</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=quiz" class="nav-link <?php if(@$_GET['page'] == 'quiz') echo 'active'; ?>">
                    <i class="fas fa-tasks"></i>
                    <span>Tugas/Quiz</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=berita" class="nav-link <?php if(@$_GET['page'] == 'berita') echo 'active'; ?>">
                    <i class="fas fa-newspaper"></i>
                    <span>Berita</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="?page=pengaturan" class="nav-link <?php if(@$_GET['page'] == 'pengaturan') echo 'active'; ?>">
                    <i class="fas fa-cog"></i>
                    <span>Pengaturan</span>
                </a>
            </div>

            <div class="nav-divider"></div>

            <div class="nav-item">
                <a href="./inc/logout.php" class="nav-link text-danger" onclick="return confirmLogout();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <nav class="modern-navbar">
            <button class="mobile-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="navbar-brand">
                <?php echo $page_title; ?>
            </div>

            <div class="navbar-user">
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none" type="button" id="userDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr($data_terlogin['username'], 0, 1)); ?>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdownBtn">
                        <li>
                            <a class="dropdown-item" href="?hal=editprofil">
                                <i class="fas fa-user"></i>
                                <span>Edit Profil</span>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="./inc/logout.php" onclick="return confirmLogout();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Modal Template - Include on all pages -->
        <?php include "inc/modal_template.php"; ?>

        <!-- Page Content -->
        <div class="content-area">
            <?php
            if(@$_GET['page'] == '') {
                include "inc/dashboard.php";
            } else if(@$_GET['page'] == 'pengajar') {
                include "inc/pengajar_modal.php";
            } else if(@$_GET['page'] == 'siswaregistrasi') {
                include "inc/siswaregistrasi.php";
            } else if(@$_GET['page'] == 'siswa') {
                include "inc/siswa_modal.php";
            } else if(@$_GET['page'] == 'kelas') {
                include "inc/kelas_modal.php";
            } else if(@$_GET['page'] == 'mapel') {
                include "inc/mapel_modal.php";
            } else if(@$_GET['page'] == 'quiz') {
                if(@$_GET['action'] == 'buatsoal') {
                    include "inc/buat_soal.php";
                } else if(@$_GET['action'] == 'daftarsoal') {
                    include "inc/daftar_soal.php";
                } else if(@$_GET['action'] == 'lihatsoal') {
                    include "inc/lihat_soal.php";
                } else if(@$_GET['action'] == 'tambahpilgan') {
                    include "inc/tambah_pilgan.php";
                } else if(@$_GET['action'] == 'tambahessay') {
                    include "inc/tambah_essay.php";
                } else if(@$_GET['action'] == 'pesertakoreksi') {
                    include "inc/peserta_koreksi.php";
                } else if(@$_GET['action'] == 'koreksi') {
                    include "inc/koreksi.php";
                } else {
                    include "inc/quiz_modal.php";
                }
            } else if(@$_GET['page'] == 'materi') {
                include "inc/materi_modal.php";
            } else if(@$_GET['page'] == 'berita') {
                include "inc/berita_modal.php";
            } else if(@$_GET['page'] == 'pengaturan') {
                include "inc/pengaturan_sweet.php";
            } else {
                echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Halaman tidak ditemukan!</div>';
            }
            ?>
        </div>
    </main>

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
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="./style/assets/js/dataTables/export-extension.js"></script>

    <script>
        function showSuccess(title, message, autoClose) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'success',
                timer: autoClose || 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-end',
                toast: true
            });
        }

        function showError(title, message) {
            Swal.fire({
                title: title,
                text: message,
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        }

        function showLoading(title) {
            Swal.fire({
                title: title || 'Memproses...',
                html: 'Mohon tunggu sebentar...',
                allowOutsideClick: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });
        }

        function hideLoading() {
            Swal.close();
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = './inc/logout.php?' + new Date().getTime();
                }
            });
            return false;
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const navbar = document.querySelector('.modern-navbar');
            sidebar.classList.toggle('show');
            
            // Toggle navbar position based on sidebar
            if (sidebar.classList.contains('show')) {
                navbar.style.left = '0';
            } else {
                // Get the computed value of --sidebar-width from the root
                const sidebarWidth = getComputedStyle(document.documentElement).getPropertyValue('--sidebar-width');
                navbar.style.left = sidebarWidth.trim();
            }
        }

        // Close mobile sidebar when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggle.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const navbar = document.querySelector('.modern-navbar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                // Reset navbar position for desktop view
                const sidebarWidth = getComputedStyle(document.documentElement).getPropertyValue('--sidebar-width');
                navbar.style.left = sidebarWidth.trim();
            } else {
                // Adjust navbar for mobile view
                navbar.style.left = '0';
            }
        });
        
        // Initialize navbar position on load
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.modern-navbar');
            const sidebar = document.getElementById('sidebar');
            
            // Set initial position based on screen size
            if (window.innerWidth <= 768) {
                navbar.style.left = '0';
            } else {
                // Get the computed value of --sidebar-width from the root
                const sidebarWidth = getComputedStyle(document.documentElement).getPropertyValue('--sidebar-width');
                navbar.style.left = sidebarWidth.trim();
            }
        });
        
        // Sticky navbar enhancement
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.modern-navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

    </script>
</body>
</html>