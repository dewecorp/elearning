<?php
@session_start();
require_once '../koneksi.php';

// Ambil data pengaturan sekolah
$pengaturan = [];
$sql = mysqli_query($db, "SELECT * FROM tb_pengaturan WHERE id_pengaturan = 1");
if(mysqli_num_rows($sql) > 0) {
    $pengaturan = mysqli_fetch_assoc($sql);
}

// HTML content dengan mode print
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data</title>
    <style>
        @media screen {
            body { background: #f5f5f5; padding: 20px; }
            .print-container { 
                background: white; 
                padding: 20px; 
                border-radius: 8px; 
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .print-info { 
                background: #e3f2fd; 
                padding: 15px; 
                border-radius: 5px; 
                margin-bottom: 20px;
                border-left: 4px solid #2196f3;
            }
        }
        
        @media print {
            body { background: white; padding: 0; }
            .print-container { box-shadow: none; border-radius: 0; }
            .print-info { display: none; }
            .no-print { display: none !important; }
        }
        
        @page {
            size: A4 landscape;
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 20px;
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
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn-print {
            background: #2196f3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12pt;
            margin: 10px;
        }
        .btn-print:hover {
            background: #1976d2;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="print-info no-print">
            <h3>🖨️ Mode Cetak Data</h3>
            <p>Dokumen siap untuk dicetak. Klik tombol di bawah atau gunakan Ctrl+P.</p>
            <button class="btn-print" onclick="window.print()">🖨️ Cetak Sekarang</button>
            <button class="btn-print" onclick="window.close()">❌ Tutup</button>
        </div>
        
        <div class="header">
            <?php if(!empty($pengaturan['logo_sekolah'])): ?>
            <div style="text-align: center; margin-bottom: 10px;">
                <img src="../style/assets/img/<?php echo $pengaturan['logo_sekolah']; ?>" 
                     style="max-height: 60px; max-width: 80px;">
            </div>
            <?php endif; ?>
            <h1><?php echo $pengaturan['nama_sekolah'] ?: 'SMK TI Muhammadiyah Cikampek'; ?></h1>
            <h2>Alamat : <?php echo $pengaturan['alamat_sekolah'] ?: 'Cikampek Karawang'; ?></h2>
            <?php if(!empty($pengaturan['tahun_ajaran'])): ?>
            <h3>Tahun Ajaran: <?php echo $pengaturan['tahun_ajaran']; ?></h3>
            <?php endif; ?>
        </div>
        
        <div class="title">
            LAPORAN DATA <?php echo strtoupper(@$_GET['data']); ?><br>
            <?php if(!empty($pengaturan['tahun_ajaran'])): ?>
            <small style="font-size: 10pt; font-weight: normal;">Tahun Ajaran <?php echo $pengaturan['tahun_ajaran']; ?></small>
            <?php endif; ?>
        </div>
        
        <table class="table">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th width="200">Nama</th>
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
                else {
                    echo "<tr><td colspan='3'>Data tidak tersedia</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="footer">
            <table width="100%" style="margin-top: 30px;">
                <tr>
                    <td width="50%">
                        <small>Dicetak pada: <?php echo date('d-m-Y H:i:s'); ?></small><br>
                        <small>Operator: <?php echo @$_SESSION['admin'] ? 'Admin' : 'Pengajar'; ?></small>
                    </td>
                    <td width="50%" style="text-align: right;">
                        <?php if(!empty($pengaturan['nama_kepala_sekolah'])): ?>
                        <div style="margin-top: 40px;">
                            <small style="text-decoration: underline;">Mengetahui,</small><br>
                            <small style="text-decoration: underline;">Kepala Sekolah</small><br><br><br><br>
                            <small><strong><?php echo $pengaturan['nama_kepala_sekolah']; ?></strong></small><br>
                            <small>NIP: <?php echo $pengaturan['nip_kepala'] ?: '-'; ?></small>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <script>
    // Auto print dialog setelah 2 detik
    setTimeout(function() {
        window.print();
    }, 2000);
    
    // Close window setelah print (opsional)
    window.onafterprint = function() {
        setTimeout(function() {
            window.close();
        }, 1000);
    };
    </script>
</body>
</html>
<?php
$html = ob_get_clean();

// Tampilkan HTML dengan auto print
echo $html;
?>