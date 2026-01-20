<?php
@session_start();
require_once '../koneksi.php';

// Load MPDF with correct path
$vendorAutoload = __DIR__ . '/../../vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
} else {
    // Fallback path
    $fallbackPath = dirname(__DIR__, 2) . '/vendor/autoload.php';
    if (file_exists($fallbackPath)) {
        require_once $fallbackPath;
    } else {
        die("MPDF library not found. Please run: composer install");
    }
}

use Mpdf\Mpdf;

// Create new PDF
$mpdf = new Mpdf([
    'orientation' => 'L',
    'format' => 'A4',
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 20,
    'margin_bottom' => 20
]);

// HTML content
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            font-weight: bold;
        }
        .header h2 {
            font-size: 8px;
            margin: 5px 0;
            font-style: italic;
        }
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .table td {
            vertical-align: top;
        }
    </style>
</head>
<body>';

// Header
$html .= '
<div class="header">
    <h1>SMK TI Muhammadiyah Cikampek</h1>
    <h2>Alamat : Cikampek Karawang</h2>
</div>
<div class="title">
    LAPORAN DATA ' . strtoupper(@$_GET['data']) . '
</div>';

// Data table
$data = @$_GET['data'];
$html .= '<table class="table">';

if ($data == 'kelas') {
    $html .= '
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kelas</th>
            <th>Ruang</th>
            <th>Wali Kelas</th>
            <th>Ketua Kelas</th>
        </tr>
    </thead>
    <tbody>';
    
    $sql = mysqli_query($db, "SELECT k.*, 
        (SELECT nama_lengkap FROM tb_pengajar WHERE id_pengajar = k.wali_kelas) as wali_kelas_nama,
        (SELECT nama_lengkap FROM tb_siswa WHERE id_siswa = k.ketua_kelas) as ketua_kelas_nama
        FROM tb_kelas k ORDER BY nama_kelas");
    
    $no = 1;
    while($d = mysqli_fetch_array($sql)) {
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $d['nama_kelas'] . '</td>
            <td>' . $d['ruang'] . '</td>
            <td>' . ($d['wali_kelas_nama'] ?: 'Belum diatur') . '</td>
            <td>' . ($d['ketua_kelas_nama'] ?: 'Belum diatur') . '</td>
        </tr>';
    }
} 
elseif ($data == 'siswa') {
    $html .= '
    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Lengkap</th>
            <th>Kelas</th>
            <th>Jenis Kelamin</th>
            <th>No Telp</th>
        </tr>
    </thead>
    <tbody>';
    
    $sql = mysqli_query($db, "SELECT s.*, k.nama_kelas 
        FROM tb_siswa s 
        LEFT JOIN tb_kelas k ON s.id_kelas = k.id_kelas 
        ORDER BY s.nama_lengkap");
    
    $no = 1;
    while($d = mysqli_fetch_array($sql)) {
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $d['nis'] . '</td>
            <td>' . $d['nama_lengkap'] . '</td>
            <td>' . ($d['nama_kelas'] ?: '-') . '</td>
            <td>' . $d['jenis_kelamin'] . '</td>
            <td>' . $d['no_telp'] . '</td>
        </tr>';
    }
}
elseif ($data == 'mapel') {
    $html .= '
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Mapel</th>
            <th>Mata Pelajaran</th>
        </tr>
    </thead>
    <tbody>';
    
    $sql = mysqli_query($db, "SELECT * FROM tb_mapel ORDER BY mapel");
    
    $no = 1;
    while($d = mysqli_fetch_array($sql)) {
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $d['kode_mapel'] . '</td>
            <td>' . $d['mapel'] . '</td>
        </tr>';
    }
}
elseif ($data == 'pengajar') {
    $html .= '
    <thead>
        <tr>
            <th>No</th>
            <th>NIP</th>
            <th>Nama Lengkap</th>
            <th>Tempat, Tgl Lahir</th>
            <th>Jenis Kelamin</th>
            <th>Agama</th>
            <th>No Telp</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';
    
    $sql = mysqli_query($db, "SELECT * FROM tb_pengajar ORDER BY nama_lengkap");
    
    $no = 1;
    while($d = mysqli_fetch_array($sql)) {
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $d['nip'] . '</td>
            <td>' . $d['nama_lengkap'] . '</td>
            <td>' . $d['tempat_lahir'] . ', ' . $d['tgl_lahir'] . '</td>
            <td>' . $d['jenis_kelamin'] . '</td>
            <td>' . $d['agama'] . '</td>
            <td>' . $d['no_telp'] . '</td>
            <td>' . $d['email'] . '</td>
            <td>' . $d['status'] . '</td>
        </tr>';
    }
}
elseif ($data == 'materi') {
    $html .= '
    <thead>
        <tr>
            <th>No</th>
            <th>Judul Materi</th>
            <th>Kelas</th>
            <th>Mapel</th>
            <th>Pembuat</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>';
    
    $sql = mysqli_query($db, "SELECT m.*, k.nama_kelas, mp.mapel FROM tb_file_materi m 
        LEFT JOIN tb_kelas k ON m.id_kelas = k.id_kelas 
        LEFT JOIN tb_mapel mp ON m.id_mapel = mp.id 
        ORDER BY m.tgl_posting DESC");
    
    $no = 1;
    while($d = mysqli_fetch_array($sql)) {
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $d['judul'] . '</td>
            <td>' . ($d['nama_kelas'] ?: '-') . '</td>
            <td>' . ($d['mapel'] ?: '-') . '</td>
            <td>' . $d['pembuat'] . '</td>
            <td>' . $d['tgl_posting'] . '</td>
        </tr>';
    }
}
elseif ($data == 'berita') {
    $html .= '
    <thead>
        <tr>
            <th>No</th>
            <th>Judul Berita</th>
            <th>Tanggal Posting</th>
            <th>Penerbit</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';
    
    $sql = mysqli_query($db, "SELECT * FROM tb_berita ORDER BY tgl_posting DESC");
    
    $no = 1;
    while($d = mysqli_fetch_array($sql)) {
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $d['judul'] . '</td>
            <td>' . $d['tgl_posting'] . '</td>
            <td>' . $d['penerbit'] . '</td>
            <td>' . $d['status'] . '</td>
        </tr>';
    }
}
elseif ($data == 'topikquiz') {
    $html .= '
    <thead>
        <tr>
            <th>No</th>
            <th>Judul Quiz</th>
            <th>Kelas</th>
            <th>Mapel</th>
            <th>Pembuat</th>
            <th>Waktu</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';
    
    $sql = mysqli_query($db, "SELECT tq.*, k.nama_kelas, mp.mapel FROM tb_topik_quiz tq
        LEFT JOIN tb_kelas k ON tq.id_kelas = k.id_kelas 
        LEFT JOIN tb_mapel mp ON tq.id_mapel = mp.id 
        ORDER BY tq.tgl_buat DESC");
    
    $no = 1;
    while($d = mysqli_fetch_array($sql)) {
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $d['judul'] . '</td>
            <td>' . ($d['nama_kelas'] ?: '-') . '</td>
            <td>' . ($d['mapel'] ?: '-') . '</td>
            <td>' . $d['pembuat'] . '</td>
            <td>' . $d['waktu_soal'] . ' detik</td>
            <td>' . $d['status'] . '</td>
        </tr>';
    }
}
elseif ($data == 'quiz' && isset($_GET['id_tq'])) {
    $id_tq = $_GET['id_tq'];
    $html .= '
    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Nilai</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>';
    
    $sql = mysqli_query($db, "SELECT s.*, k.nama_kelas FROM tb_siswa s 
        LEFT JOIN tb_kelas k ON s.id_kelas = k.id_kelas 
        WHERE s.status = 'aktif' 
        ORDER BY s.nama_lengkap");
    
    $no = 1;
    while($d = mysqli_fetch_array($sql)) {
        // Check if student has quiz result
        $check_nilai = mysqli_query($db, "SELECT * FROM tb_nilai_pilgan WHERE id_siswa = '$d[id_siswa]' AND id_tq = '$id_tq'");
        $nilai_data = mysqli_fetch_assoc($check_nilai);
        
        $html .= '
        <tr>
            <td>' . $no++ . '</td>
            <td>' . $d['nis'] . '</td>
            <td>' . $d['nama_lengkap'] . '</td>
            <td>' . ($d['nama_kelas'] ?: '-') . '</td>
            <td>' . ($nilai_data ? $nilai_data['presentase'] . '%' : 'Belum mengerjakan') . '</td>
            <td>' . ($nilai_data ? 'Selesai' : 'Pending') . '</td>
        </tr>';
    }
}
else {
    $html .= '
    <thead>
        <tr>
            <th colspan="2">Data Tidak Tersedia</th>
        </tr>
    </thead>';
}

$html .= '
    </tbody>
</table>
</body>
</html>';

// Write HTML to PDF
$mpdf->WriteHTML($html);

// Output PDF
$mpdf->Output('laporan_' . $data . '.pdf', 'I');
?>