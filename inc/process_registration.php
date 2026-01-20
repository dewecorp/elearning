<?php
@session_start();
include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    // Sanitize and validate input data
    $nis = mysqli_real_escape_string($db, trim($_POST['nis']));
    $nama_lengkap = mysqli_real_escape_string($db, trim($_POST['nama_lengkap']));
    $tempat_lahir = mysqli_real_escape_string($db, trim($_POST['tempat_lahir']));
    $tgl_lahir = mysqli_real_escape_string($db, trim($_POST['tgl_lahir']));
    $jenis_kelamin = mysqli_real_escape_string($db, trim($_POST['jenis_kelamin']));
    $agama = mysqli_real_escape_string($db, trim($_POST['agama']));
    $nama_ayah = mysqli_real_escape_string($db, trim($_POST['nama_ayah']));
    $nama_ibu = mysqli_real_escape_string($db, trim($_POST['nama_ibu']));
    $no_telp = mysqli_real_escape_string($db, trim($_POST['no_telp']));
    $email = mysqli_real_escape_string($db, trim($_POST['email']));
    $alamat = mysqli_real_escape_string($db, trim($_POST['alamat']));
    $kelas = mysqli_real_escape_string($db, trim($_POST['kelas']));
    $thn_masuk = mysqli_real_escape_string($db, trim($_POST['thn_masuk']));
    $user = mysqli_real_escape_string($db, trim($_POST['user']));
    $pass = mysqli_real_escape_string($db, trim($_POST['pass']));

    // Validate required fields
    if (empty($nis) || empty($nama_lengkap) || empty($tempat_lahir) || empty($tgl_lahir) ||
        empty($jenis_kelamin) || empty($agama) || empty($nama_ayah) || empty($nama_ibu) ||
        empty($alamat) || empty($kelas) || empty($thn_masuk) || empty($user) || empty($pass)) {
        echo json_encode(['success' => false, 'message' => 'Harap isi semua field yang wajib diisi']);
        exit;
    }

    // Check if username already exists
    $sql_cek_user = mysqli_query($db, "SELECT * FROM tb_siswa WHERE username = '$user'") or die ($db->error);
    if (mysqli_num_rows($sql_cek_user) > 0) {
        echo json_encode(['success' => false, 'message' => 'Username yang Anda pilih sudah ada, silahkan ganti yang lain']);
        exit;
    }

    // Handle file upload if provided
    $foto = 'anonim.png'; // Default image
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_extensions)) {
            if ($_FILES['gambar']['size'] <= 2000000) { // Max 2MB
                $foto = time() . '_' . basename($_FILES['gambar']['name']);
                $target_path = '../img/foto_siswa/' . $foto;
                
                if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_path)) {
                    // File uploaded successfully
                } else {
                    $foto = 'anonim.png'; // Revert to default if upload fails
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Ukuran file terlalu besar. Maksimal 2MB']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF']);
            exit;
        }
    }

    // Insert new user into database
    $sql_insert = "INSERT INTO tb_siswa (
        nis, nama_lengkap, tempat_lahir, tgl_lahir, jenis_kelamin, agama, 
        nama_ayah, nama_ibu, no_telp, email, alamat, id_kelas, thn_masuk, 
        foto, username, password, password_original, status
    ) VALUES (
        '$nis', '$nama_lengkap', '$tempat_lahir', '$tgl_lahir', '$jenis_kelamin', '$agama',
        '$nama_ayah', '$nama_ibu', '$no_telp', '$email', '$alamat', '$kelas', '$thn_masuk',
        '$foto', '$user', MD5('$pass'), '$pass', 'tidak aktif'
    )";

    $result = mysqli_query($db, $sql_insert);

    if ($result) {
        echo json_encode([
            'success' => true, 
            'message' => 'Pendaftaran berhasil! Tunggu akun Anda diaktifkan oleh administrator, lalu silahkan login.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal mendaftar. Silakan coba lagi.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Akses tidak sah']);
}
?>