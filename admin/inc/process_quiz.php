<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors to user, log them instead

@session_start();

// Check if session exists
if(!isset($_SESSION['admin']) && !isset($_SESSION['pengajar'])) {
    error_log("NO ACTIVE SESSION - Session data: " . print_r($_SESSION, true));
    echo json_encode(['success' => false, 'message' => 'Sesi tidak valid. Silakan login kembali.']);
    exit;
}

// Include required files with proper path
$konek_file = __DIR__ . '/../koneksi.php';
if(file_exists($konek_file)) {
    include $konek_file;
} else {
    // Fallback to root koneksi.php
    include __DIR__ . '/../../koneksi.php';
}

// Check database connection
if(!$db) {
    error_log("DATABASE CONNECTION FAILED: " . mysqli_connect_error());
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal: ' . mysqli_connect_error()]);
    exit;
}

$sweetalert_file = __DIR__ . '/sweetalert_helper.php';
if(file_exists($sweetalert_file)) {
    require_once $sweetalert_file;
} else {
    require_once __DIR__ . '/sweetalert_helper.php';
}

// Log incoming request
error_log("=== PROCESS_QUIZ REQUEST START ===");
error_log("Session: admin=" . (isset($_SESSION['admin']) ? $_SESSION['admin'] : 'NOT SET') . ", pengajar=" . (isset($_SESSION['pengajar']) ? $_SESSION['pengajar'] : 'NOT SET'));
error_log("POST data: " . print_r($_POST, true));

// Process actions
if(isset($_POST['action'])) {
    $action = $_POST['action'];
    
    if($action === 'tambah') {
        // Handle insert for quiz
        $judul = mysqli_real_escape_string($db, $_POST['judul']);
        $mapel = mysqli_real_escape_string($db, $_POST['mapel']);
        $tgl_buat = mysqli_real_escape_string($db, $_POST['tgl_buat']);
        $waktu_soal = mysqli_real_escape_string($db, $_POST['waktu_soal']) * 60; // Convert to seconds
        $info = mysqli_real_escape_string($db, $_POST['info']);
        $status = mysqli_real_escape_string($db, $_POST['status']);
        
        // Determine pembuat based on session
        if(@$_SESSION['admin']) {
            $pembuat = "admin";
        } else if(@$_SESSION['pengajar']) {
            $pembuat = @$_SESSION['pengajar'];
        } else {
            echo json_encode(['success' => false, 'message' => 'Tidak ada sesi aktif']);
            exit;
        }
        
        // Handle multiple classes
        if(isset($_POST['kelas']) && is_array($_POST['kelas'])) {
            $success_count = 0;
            foreach($_POST['kelas'] as $kelas) {
                $kelas_clean = mysqli_real_escape_string($db, $kelas);
                if(!empty($kelas_clean)) {
                    $insert = mysqli_query($db, "INSERT INTO tb_topik_quiz (judul, id_kelas, id_mapel, tgl_buat, pembuat, waktu_soal, info, status) VALUES ('$judul', '$kelas_clean', '$mapel', '$tgl_buat', '$pembuat', '$waktu_soal', '$info', '$status')");
                    if($insert) {
                        $success_count++;
                    }
                }
            }
            
            if($success_count > 0) {
                echo json_encode(['success' => true, 'message' => "Quiz berhasil ditambahkan untuk $success_count kelas!"]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan quiz']);
            }
        } else {
            // Single class
            $kelas = mysqli_real_escape_string($db, $_POST['kelas']);
            $insert = mysqli_query($db, "INSERT INTO tb_topik_quiz (judul, id_kelas, id_mapel, tgl_buat, pembuat, waktu_soal, info, status) VALUES ('$judul', '$kelas', '$mapel', '$tgl_buat', '$pembuat', '$waktu_soal', '$info', '$status')");
            
            if($insert) {
                echo json_encode(['success' => true, 'message' => 'Quiz berhasil ditambahkan!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menambahkan quiz']);
            }
        }
    }
    elseif($action === 'edit') {
        // Handle update for quiz
        $id = mysqli_real_escape_string($db, $_POST['id']);
        $judul = mysqli_real_escape_string($db, $_POST['judul']);
        $kelas = mysqli_real_escape_string($db, $_POST['kelas']);
        $mapel = mysqli_real_escape_string($db, $_POST['mapel']);
        $tgl_buat = mysqli_real_escape_string($db, $_POST['tgl_buat']);
        $waktu_soal = mysqli_real_escape_string($db, $_POST['waktu_soal']) * 60; // Convert to seconds
        $info = mysqli_real_escape_string($db, $_POST['info']);
        $status = mysqli_real_escape_string($db, $_POST['status']);
        
        // Determine pembuat based on session
        if(@$_SESSION['admin']) {
            $pembuat = "admin";
        } else if(@$_SESSION['pengajar']) {
            $pembuat = @$_SESSION['pengajar'];
        }
        
        $update = mysqli_query($db, "UPDATE tb_topik_quiz SET judul = '$judul', id_kelas = '$kelas', id_mapel = '$mapel', tgl_buat = '$tgl_buat', pembuat = '$pembuat', waktu_soal = '$waktu_soal', info = '$info', status = '$status' WHERE id_tq = '$id'");
        
        if($update) {
            echo json_encode(['success' => true, 'message' => 'Quiz berhasil diperbarui!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui quiz']);
        }
    }
    elseif($action === 'tambahpilgan') {
        // Handle insert for multiple choice question
        $id_tq = mysqli_real_escape_string($db, $_POST['id_tq']);
        $pertanyaan = mysqli_real_escape_string($db, $_POST['pertanyaan']);
        $gambar = mysqli_real_escape_string($db, $_POST['gambar']);
        $pil_a = mysqli_real_escape_string($db, $_POST['pil_a']);
        $pil_b = mysqli_real_escape_string($db, $_POST['pil_b']);
        $pil_c = mysqli_real_escape_string($db, $_POST['pil_c']);
        $pil_d = mysqli_real_escape_string($db, $_POST['pil_d']);
        $pil_e = mysqli_real_escape_string($db, $_POST['pil_e']);
        $kunci = mysqli_real_escape_string($db, $_POST['kunci']);
        $tgl_buat = date('Y-m-d');
        
        $insert = mysqli_query($db, "INSERT INTO tb_soal_pilgan (id_tq, pertanyaan, gambar, pil_a, pil_b, pil_c, pil_d, pil_e, kunci, tgl_buat) VALUES ('$id_tq', '$pertanyaan', '$gambar', '$pil_a', '$pil_b', '$pil_c', '$pil_d', '$pil_e', '$kunci', '$tgl_buat')");
        
        if($insert) {
            echo json_encode(['success' => true, 'message' => 'Soal pilihan ganda berhasil ditambahkan!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan soal pilihan ganda']);
        }
    }
    elseif($action === 'tambahessay') {
        // Handle insert for essay question
        $id_tq = mysqli_real_escape_string($db, $_POST['id_tq']);
        $pertanyaan = mysqli_real_escape_string($db, $_POST['pertanyaan']);
        $gambar = mysqli_real_escape_string($db, $_POST['gambar']);
        $petunjuk = mysqli_real_escape_string($db, $_POST['petunjuk']);
        $contoh_jawaban = mysqli_real_escape_string($db, $_POST['contoh_jawaban']);
        $tgl_buat = date('Y-m-d');
        
        // Combine petunjuk and contoh jawaban into the pertanyaan field
        if(!empty($petunjuk) || !empty($contoh_jawaban)) {
            $pertanyaan .= "\n\n---\n\n";
            if(!empty($petunjuk)) {
                $pertanyaan .= "Petunjuk Penilaian: " . $petunjuk . "\n";
            }
            if(!empty($contoh_jawaban)) {
                $pertanyaan .= "Contoh Jawaban: " . $contoh_jawaban;
            }
        }
        
        $insert = mysqli_query($db, "INSERT INTO tb_soal_essay (id_tq, pertanyaan, gambar, tgl_buat) VALUES ('$id_tq', '$pertanyaan', '$gambar', '$tgl_buat')");
        
        if($insert) {
            echo json_encode(['success' => true, 'message' => 'Soal essay berhasil ditambahkan!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menambahkan soal essay']);
        }
    }
    
    elseif($action === 'simpanquiz') {
        // New quiz topic creation from enhanced form
        $judul = mysqli_real_escape_string($db, $_POST['judul']);
        $id_kelas = mysqli_real_escape_string($db, $_POST['id_kelas']);
        $id_mapel = mysqli_real_escape_string($db, $_POST['id_mapel']);
        $deskripsi = mysqli_real_escape_string($db, $_POST['deskripsi']);
        $tgl_buat = date('Y-m-d');
        $pembuat = $_SESSION['admin'];
        
        $insert = mysqli_query($db, "INSERT INTO tb_topik_quiz (judul, id_kelas, id_mapel, tgl_buat, pembuat, info, status) VALUES ('$judul', '$id_kelas', '$id_mapel', '$tgl_buat', '$pembuat', '$deskripsi', 'aktif')");
        
        if($insert) {
            echo json_encode(['success' => true, 'message' => 'Topik quiz berhasil dibuat! Anda dapat menambahkan soal-soal di dalamnya.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal membuat topik quiz']);
        }
    }
    
    elseif($action === 'simpansoalpg') {
        // Save multiple choice question directly
        $id_kelas = mysqli_real_escape_string($db, $_POST['id_kelas']);
        $id_mapel = mysqli_real_escape_string($db, $_POST['id_mapel']);
        $pertanyaan = mysqli_real_escape_string($db, $_POST['pertanyaan_pilgan']);
        $pil_a = mysqli_real_escape_string($db, $_POST['pil_a']);
        $pil_b = mysqli_real_escape_string($db, $_POST['pil_b']);
        $pil_c = mysqli_real_escape_string($db, $_POST['pil_c']);
        $pil_d = mysqli_real_escape_string($db, $_POST['pil_d']);
        $pil_e = mysqli_real_escape_string($db, $_POST['pil_e']);
        $kunci = mysqli_real_escape_string($db, $_POST['kunci']);
        $tgl_buat = date('Y-m-d');
        $pembuat = $_SESSION['admin'];
        
        // First create a quiz topic
        $judul = "Soal PG Langsung - " . date('d-m-Y H:i');
        $insert_topic = mysqli_query($db, "INSERT INTO tb_topik_quiz (judul, id_kelas, id_mapel, tgl_buat, pembuat, info, status) VALUES ('$judul', '$id_kelas', '$id_mapel', '$tgl_buat', '$pembuat', 'Soal pilihan ganda dibuat langsung', 'aktif')");
        
        if($insert_topic) {
            $id_tq = mysqli_insert_id($db);
            
            // Then insert the multiple choice question
            $insert_question = mysqli_query($db, "INSERT INTO tb_soal_pilgan (id_tq, pertanyaan, pil_a, pil_b, pil_c, pil_d, pil_e, kunci, tgl_buat) VALUES ('$id_tq', '$pertanyaan', '$pil_a', '$pil_b', '$pil_c', '$pil_d', '$pil_e', '$kunci', '$tgl_buat')");
            
            if($insert_question) {
                echo json_encode(['success' => true, 'message' => 'Soal pilihan ganda berhasil disimpan!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan soal pilihan ganda']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal membuat topik untuk soal']);
        }
    }
    
    elseif($action === 'simpansoalesay') {
        // Save essay question directly
        $id_kelas = mysqli_real_escape_string($db, $_POST['id_kelas']);
        $id_mapel = mysqli_real_escape_string($db, $_POST['id_mapel']);
        $pertanyaan = mysqli_real_escape_string($db, $_POST['pertanyaan_essay']);
        $petunjuk = mysqli_real_escape_string($db, $_POST['petunjuk_penilaian']);
        $tgl_buat = date('Y-m-d');
        $pembuat = $_SESSION['admin'];
        
        // First create a quiz topic
        $judul = "Soal Essay Langsung - " . date('d-m-Y H:i');
        $insert_topic = mysqli_query($db, "INSERT INTO tb_topik_quiz (judul, id_kelas, id_mapel, tgl_buat, pembuat, info, status) VALUES ('$judul', '$id_kelas', '$id_mapel', '$tgl_buat', '$pembuat', 'Soal essay dibuat langsung', 'aktif')");
        
        if($insert_topic) {
            $id_tq = mysqli_insert_id($db);
            
            // Then insert the essay question
            $insert_question = mysqli_query($db, "INSERT INTO tb_soal_essay (id_tq, pertanyaan, tgl_buat) VALUES ('$id_tq', '$pertanyaan', '$tgl_buat')");
            
            if($insert_question) {
                echo json_encode(['success' => true, 'message' => 'Soal essay berhasil disimpan!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan soal essay']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal membuat topik untuk soal']);
        }
    }
    
    elseif($action === 'hapusquiz') {
        // Handle delete quiz
        $id = mysqli_real_escape_string($db, $_POST['id']);
        
        // Delete related records first
        mysqli_query($db, "DELETE FROM tb_soal_pilgan WHERE id_tq='$id'");
        mysqli_query($db, "DELETE FROM tb_soal_essay WHERE id_tq='$id'");
        mysqli_query($db, "DELETE FROM tb_nilai_pilgan WHERE id_tq='$id'");
        mysqli_query($db, "DELETE FROM tb_nilai_essay WHERE id_tq='$id'");
        mysqli_query($db, "DELETE FROM tb_jawaban WHERE id_tq='$id'");
        
        // Then delete the quiz topic
        $delete = mysqli_query($db, "DELETE FROM tb_topik_quiz WHERE id_tq='$id'");
        
        if($delete) {
            echo json_encode(['success' => true, 'message' => 'Quiz berhasil dihapus!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus quiz']);
        }
    }
    
    elseif($action === 'hapuspilgan') {
        // Handle delete multiple choice question
        $id = mysqli_real_escape_string($db, $_POST['id']);
        $delete = mysqli_query($db, "DELETE FROM tb_soal_pilgan WHERE id_pilgan='$id'");
        
        if($delete) {
            echo json_encode(['success' => true, 'message' => 'Soal pilihan ganda berhasil dihapus!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus soal pilihan ganda']);
        }
    }
    
    elseif($action === 'hapusessay') {
        // Handle delete essay question
        $id = mysqli_real_escape_string($db, $_POST['id']);
        $delete = mysqli_query($db, "DELETE FROM tb_soal_essay WHERE id_essay='$id'");
        
        if($delete) {
            echo json_encode(['success' => true, 'message' => 'Soal essay berhasil dihapus!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus soal essay']);
        }
    }
    
    elseif($action === 'koreksiessay') {
        try {
            // Debug logging
            error_log("=== KOREKSI ESSAY DEBUG START ===");
            error_log("Koreksi essay action triggered");
            error_log("POST data: " . print_r($_POST, true));
            error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
            error_log("CONTENT_TYPE: " . ($_SERVER['CONTENT_TYPE'] ?? 'NOT SET'));
            
            // Check if required fields exist
            if(!isset($_POST['id_tq']) || !isset($_POST['id_siswa']) || !isset($_POST['nilai'])) {
                error_log("MISSING REQUIRED FIELDS");
                error_log("id_tq: " . (isset($_POST['id_tq']) ? $_POST['id_tq'] : 'NOT SET'));
                error_log("id_siswa: " . (isset($_POST['id_siswa']) ? $_POST['id_siswa'] : 'NOT SET'));
                error_log("nilai: " . (isset($_POST['nilai']) ? $_POST['nilai'] : 'NOT SET'));
                echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
                exit;
            }
            
            // Handle essay correction
            $id_tq = mysqli_real_escape_string($db, $_POST['id_tq']);
            $id_siswa = mysqli_real_escape_string($db, $_POST['id_siswa']);
            $nilai = mysqli_real_escape_string($db, $_POST['nilai']);
            
            error_log("Processing: id_tq=$id_tq, id_siswa=$id_siswa, nilai=$nilai");
            
            // Validate nilai range
            if(!is_numeric($nilai) || $nilai < 0 || $nilai > 100) {
                error_log("INVALID NILAI: $nilai");
                echo json_encode(['success' => false, 'message' => 'Nilai harus antara 0-100']);
                exit;
            }
            
            // Check if nilai already exists
            $check = mysqli_query($db, "SELECT * FROM tb_nilai_essay WHERE id_tq='$id_tq' AND id_siswa='$id_siswa'");
            
            if(!$check) {
                error_log("Database query failed: " . mysqli_error($db));
                echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($db)]);
                exit;
            }
            
            $success = false;
            if(mysqli_num_rows($check) > 0) {
                // Update existing nilai
                error_log("UPDATING existing record");
                $update = mysqli_query($db, "UPDATE tb_nilai_essay SET nilai='$nilai' WHERE id_tq='$id_tq' AND id_siswa='$id_siswa'");
                error_log("Update query result: " . ($update ? "SUCCESS" : "FAILED"));
                if($update) {
                    error_log("Rows affected: " . mysqli_affected_rows($db));
                }
                $success = $update;
            } else {
                // Insert new nilai
                error_log("INSERTING new record");
                $insert = mysqli_query($db, "INSERT INTO tb_nilai_essay (id_tq, id_siswa, nilai) VALUES ('$id_tq', '$id_siswa', '$nilai')");
                error_log("Insert query result: " . ($insert ? "SUCCESS" : "FAILED"));
                if(!$insert) {
                    error_log("Insert failed: " . mysqli_error($db));
                } else {
                    error_log("Inserted ID: " . mysqli_insert_id($db));
                }
                $success = $insert;
            }
            
            if($success) {
                error_log("Successfully saved nilai");
                echo json_encode(['success' => true, 'message' => 'Nilai essay berhasil disimpan!']);
            } else {
                error_log("Failed to save nilai");
                $db_error = mysqli_error($db);
                error_log("Database error: " . $db_error);
                echo json_encode(['success' => false, 'message' => 'Gagal menyimpan nilai essay: ' . $db_error]);
            }
            
            error_log("=== KOREKSI ESSAY DEBUG END ===");
        } catch(Exception $e) {
            error_log("EXCEPTION in koreksiessay: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()]);
        }
    }
}
?>