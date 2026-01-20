<?php
@session_start();
if(!isset($db)) {
    include dirname(__DIR__) . "/koneksi.php";
}
require_once __DIR__ . '/sweetalert_helper.php';

// Proses backup database
if(isset($_POST['backup_db'])) {
    // Pastikan koneksi database tersedia
    if(!isset($db)) {
        include dirname(__DIR__) . "/koneksi.php";
    }
    
    $tanggal = date('Y-m-d_H-i-s');
    $filename = 'backup_elearning_' . $tanggal . '.sql';
    // Gunakan absolute path agar aman
    $backupDir = dirname(dirname(__DIR__)) . '/backup';
    $filepath = $backupDir . '/' . $filename;
    
    // Buat folder backup jika belum ada
    if (!file_exists($backupDir)) {
        mkdir($backupDir, 0777, true);
    }
    
    // Eksekusi perintah mysqldump
    $host = 'localhost';
    $dbname = 'e-learning';
    $username = 'root';
    $password = '';
    
    // Gunakan full path jika perlu, tapi coba default dulu
    // Tambahkan --column-statistics=0 jika error di MySQL 8
    $command = "mysqldump --host=$host --user=$username --password=$password --add-drop-table --add-locks --extended-insert --lock-tables --single-transaction --column-statistics=0 $dbname > \"$filepath\" 2>&1";
    
    exec($command, $output, $return_var);
    
    if($return_var === 0) {
        // Hitung ukuran file
        $filesize = filesize($filepath);
        
        // Simpan informasi backup ke database
        $sql = "INSERT INTO tb_backup (nama_file, ukuran) VALUES ('$filename', '$filesize')";
        mysqli_query($db, $sql);
        
        echo "<script>autoSuccess('Backup database berhasil! File: $filename'); setTimeout(function(){ window.location='?page=backup&success=backup'; }, 1500);</script>";
    } else {
        // Tampilkan error detail
        $error_msg = implode("\\n", $output);
        echo "<script>autoError('Gagal melakukan backup database. Error: $error_msg');</script>";
    }
}

// Proses restore database
if(isset($_POST['restore_db'])) {
    // Pastikan koneksi database tersedia
    if(!isset($db)) {
        include dirname(__DIR__) . "/koneksi.php";
    }
    
    if(isset($_FILES['restore_file']) && $_FILES['restore_file']['error'] == 0) {
        $allowed = ['sql'];
        $filename = $_FILES['restore_file']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $temppath = $_FILES['restore_file']['tmp_name'];
            
            // Eksekusi perintah mysql untuk restore
            $host = 'localhost';
            $dbname = 'e-learning';
            $username = 'root';
            $password = '';
            
            $command = "mysql --host=$host --user=$username --password=$password $dbname < \"$temppath\" 2>&1";
            
            exec($command, $output, $return_var);
            
            if($return_var === 0) {
                echo "<script>autoSuccess('Restore database berhasil!');</script>";
            } else {
                 $error_msg = implode("\\n", $output);
                echo "<script>autoError('Gagal melakukan restore database. Error: $error_msg');</script>";
            }
        } else {
            echo "<script>autoError('Format file harus .sql');</script>";
        }
    } else {
        echo "<script>autoError('Silakan pilih file backup terlebih dahulu');</script>";
    }
}

// Proses hapus file backup
if(isset($_GET['hapus_backup'])) {
    // Pastikan koneksi database tersedia
    if(!isset($db)) {
        include dirname(__DIR__) . "/koneksi.php";
    }
    
    $id_backup = $_GET['hapus_backup'];
    $sql = "SELECT nama_file FROM tb_backup WHERE id_backup = '$id_backup'";
    $result = mysqli_query($db, $sql);
    $backup = mysqli_fetch_assoc($result);
    
    if($backup) {
        $backupDir = dirname(dirname(__DIR__)) . '/backup';
        $filepath = $backupDir . '/' . $backup['nama_file'];
        
        if(file_exists($filepath)) {
            unlink($filepath);
        }
        mysqli_query($db, "DELETE FROM tb_backup WHERE id_backup = '$id_backup'");
        echo "<script>autoSuccess('File backup berhasil dihapus'); setTimeout(function(){ window.location='?page=backup'; }, 1500);</script>";
    }
}

// Fungsi untuk format ukuran file
function formatBytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    
    if ($i > 0) {
        $size = round($size, $precision);
    }
    
    return $size . ' ' . $units[$i];
}
?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Backup & Restore Database</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-download-alt"></i> Backup Database
            </div>
            <div class="panel-body">
                <form method="post" action="" id="backup-form">
                    <div class="form-group">
                        <label>Backup Database Saat Ini</label>
                        <p class="help-block">Klik tombol dibawah untuk membuat cadangan database saat ini.</p>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="backup-btn" onclick="backupDatabase();">
                            <i class="glyphicon glyphicon-download-alt"></i> Backup Sekarang
                        </button>
                    </div>
                    <?php if(isset($_GET['success']) && $_GET['success'] == 'backup'): ?>
                    <script>
                        window.addEventListener('load', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses!',
                                text: 'Backup database berhasil.',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                        });
                    </script>
                    <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-upload"></i> Restore Database
            </div>
            <div class="panel-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Pilih File Backup</label>
                        <input type="file" name="restore_file" class="form-control" accept=".sql" required>
                        <p class="help-block">Pilih file .sql hasil backup sebelumnya untuk merestore database.</p>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="restore_db" class="btn btn-warning">
                            <i class="glyphicon glyphicon-upload"></i> Restore Database
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Daftar Backup -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-list"></i> Daftar File Backup
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="table-backup" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Tanggal Backup</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            // Pastikan koneksi database tersedia
                            if(!isset($db)) {
                                include dirname(__DIR__) . "/koneksi.php";
                            }
                            $sql_backup = mysqli_query($db, "SELECT * FROM tb_backup ORDER BY tanggal_backup DESC");
                            if(mysqli_num_rows($sql_backup) > 0) {
                                while($backup = mysqli_fetch_assoc($sql_backup)) {
                                    $ukuran = formatBytes($backup['ukuran']);
                                    $tanggal = date('d M Y H:i:s', strtotime($backup['tanggal_backup']));
                                    echo "<tr>";
                                    echo "<td>" . $no++ . "</td>";
                                    echo "<td>" . $backup['nama_file'] . "</td>";
                                    echo "<td>" . $ukuran . "</td>";
                                    echo "<td>" . $tanggal . "</td>";
                                    echo "<td>";
                                    echo "<a href='../backup/" . $backup['nama_file'] . "' class='btn btn-success btn-xs' title='Unduh' target='_blank'><i class='glyphicon glyphicon-download-alt'></i> Unduh</a> ";
                                    echo "<button type='button' class='btn btn-danger btn-xs' title='Hapus' onclick='hapusBackup(\"" . $backup['id_backup'] . "\");'><i class='glyphicon glyphicon-trash'></i> Hapus</button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>Tidak ada file backup</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-xs .glyphicon {
    top: 0;
    margin-right: 0;
}
.table .btn-xs {
    padding: 2px 6px;
    font-size: 12px;
}
/* Memastikan ikon terlihat */
.glyphicon {
    display: inline-block;
    font-style: normal;
    font-weight: normal;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
/* Gaya tombol aksi */
.action-buttons .btn {
    margin: 0 2px;
}

/* Memastikan ikon terlihat */
i.glyphicon {
    display: inline-block;
    font-style: normal;
    font-weight: normal;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
</style>

<script>
// Pindahkan fungsi ke global scope agar bisa dipanggil oleh onclick
function backupDatabase() {
    Swal.fire({
        title: 'Backup Database?',
        text: "Apakah Anda yakin ingin membuat backup database saat ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Backup Sekarang!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            var form = document.getElementById('backup-form');
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'backup_db';
            input.value = '1';
            form.appendChild(input);
            form.submit();
            
            // Tampilkan loading setelah submit
            Swal.fire({
                title: 'Memproses Backup...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });
        }
    });
}

function hapusBackup(id) {
    Swal.fire({
        title: 'Hapus Backup?',
        text: "File backup yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?page=backup&hapus_backup=' + id;
        }
    });
}

// Gunakan window load agar jQuery sudah dimuat dari footer
window.addEventListener('load', function() {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery belum dimuat!');
        return;
    }
    var $ = jQuery;

    // Auto-close alert sukses backup
    setTimeout(function() {
        $('.alert-success:not(.alert-dismissible)').fadeOut('slow');
    }, 3000); // 3 detik
    
    // Pastikan DataTables berfungsi
    // Cek apakah elemen tabel ada dan library DataTables tersedia
    if($('#table-backup').length > 0) {
        if(typeof $.fn.DataTable === 'undefined') {
            console.error('DataTable library not loaded');
        } else {
            // Destroy dulu jika sudah pernah diinisialisasi
            if ($.fn.DataTable.isDataTable('#table-backup')) {
                $('#table-backup').DataTable().destroy();
            }
            
            // Baru inisialisasi DataTable
            $('#table-backup').DataTable({
                "responsive": true,
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "pageLength": 10,
                "language": {
                    "processing": "Sedang memproses...",
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ entri",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                    "infoFiltered": "(difilter dari _MAX_ total entri)",
                    "loadingRecords": "Sedang memuat...",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
                    "paginate": {
                        "first": "Pertama",
                        "previous": "Sebelumnya",
                        "next": "Selanjutnya",
                        "last": "Terakhir"
                    }
                }
            });
        }
    }
});
</script>