<?php
@session_start();
require_once '../koneksi.php';

// Mode window print - tidak download langsung

// HTML content untuk PDF
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
        }
        .header h2 {
            font-size: 8pt;
            font-style: italic;
            margin: 3px 0;
        }
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }
        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .no-print {
            display: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SMK TI Muhammadiyah Cikampek</h1>
        <h2>Alamat : Cikampek Karawang</h2>
    </div>
    <div class="title">
        LAPORAN DATA <?php echo strtoupper(@$_GET['data']); ?>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data = @$_GET['data'];
            $no = 1;
            
            if ($data == 'kelas') {
                $sql = mysqli_query($db, "SELECT * FROM tb_kelas ORDER BY nama_kelas");
                while($d = mysqli_fetch_array($sql)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $d['nama_kelas'] . "</td>";
                    echo "<td>Ruang: " . $d['ruang'] . "</td>";
                    echo "</tr>";
                }
            }
            elseif ($data == 'siswa') {
                $sql = mysqli_query($db, "SELECT * FROM tb_siswa ORDER BY nama_lengkap LIMIT 50");
                while($d = mysqli_fetch_array($sql)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $d['nama_lengkap'] . "</td>";
                    echo "<td>NIS: " . $d['nis'] . "<br>Kelas: " . $d['id_kelas'] . "</td>";
                    echo "</tr>";
                }
            }
            elseif ($data == 'materi') {
                $sql = mysqli_query($db, "SELECT m.*, mp.mapel, k.nama_kelas FROM tb_file_materi m LEFT JOIN tb_mapel mp ON m.id_mapel = mp.id LEFT JOIN tb_kelas k ON m.id_kelas = k.id_kelas ORDER BY m.tgl_posting DESC");
                while($d = mysqli_fetch_array($sql)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $d['judul'] . "</td>";
                    echo "<td>Mapel: " . ($d['mapel'] ?: '-') . "<br>Kelas: " . ($d['nama_kelas'] ?: '-') . "<br>File: " . ($d['nama_file'] ?: '-') . "</td>";
                    echo "</tr>";
                }
            }
            elseif ($data == 'berita') {
                $sql = mysqli_query($db, "SELECT * FROM tb_berita ORDER BY tgl_posting DESC");
                while($d = mysqli_fetch_array($sql)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $d['judul'] . "</td>";
                    echo "<td>Tanggal: " . $d['tgl_posting'] . "<br>Status: " . $d['status'] . "</td>";
                    echo "</tr>";
                }
            }
            elseif ($data == 'topikquiz') {
                $sql = mysqli_query($db, "SELECT tq.*, mp.mapel, k.nama_kelas FROM tb_topik_quiz tq LEFT JOIN tb_mapel mp ON tq.id_mapel = mp.id LEFT JOIN tb_kelas k ON tq.id_kelas = k.id_kelas ORDER BY tq.tgl_buat DESC");
                while($d = mysqli_fetch_array($sql)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $d['judul'] . "</td>";
                    echo "<td>Mapel: " . ($d['mapel'] ?: '-') . "<br>Kelas: " . ($d['nama_kelas'] ?: '-') . "<br>Tgl: " . $d['tgl_buat'] . "</td>";
                    echo "</tr>";
                }
            }
            elseif ($data == 'siswaregistrasi') {
                $sql = mysqli_query($db, "SELECT s.*, k.nama_kelas FROM tb_siswa s LEFT JOIN tb_kelas k ON s.id_kelas = k.id_kelas WHERE s.status = 'tidak aktif' ORDER BY s.nis");
                while($d = mysqli_fetch_array($sql)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $d['nama_lengkap'] . "</td>";
                    echo "<td>NIS: " . $d['nis'] . "<br>Kelas: " . ($d['nama_kelas'] ?: $d['id_kelas']) . "<br>Alamat: " . $d['alamat'] . "</td>";
                    echo "</tr>";
                }
            }
            elseif ($data == 'mapel') {
                $sql = mysqli_query($db, "SELECT * FROM tb_mapel ORDER BY mapel");
                while($d = mysqli_fetch_array($sql)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $d['mapel'] . "</td>";
                    echo "<td>Kode: " . $d['kode_mapel'] . "</td>";
                    echo "</tr>";
                }
            }
            elseif ($data == 'pengajar') {
                $sql = mysqli_query($db, "SELECT * FROM tb_pengajar ORDER BY nama_lengkap");
                while($d = mysqli_fetch_array($sql)) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $d['nama_lengkap'] . "</td>";
                    echo "<td>NIP: " . $d['nip'] . "<br>Email: " . $d['email'] . "</td>";
                    echo "</tr>";
                }
            }
            else {
                echo "<tr><td colspan='3'>Data tidak tersedia</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <small>Cetak tanggal: <?php echo date('d-m-Y H:i:s'); ?></small>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

// Generate PDF tanpa external library
echo $html;
?>
