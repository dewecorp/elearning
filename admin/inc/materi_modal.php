<?php
@session_start();
require_once 'sweetalert_helper.php';
if(@$_SESSION['admin'] || @$_SESSION['pengajar']) { 

// Get available mapel for dropdown
$sql_mapel = mysqli_query($db, "SELECT * FROM tb_mapel ORDER BY mapel ASC");
$mapel_options = '';
while($data_mapel = mysqli_fetch_array($sql_mapel)) {
    $mapel_options .= '<option value="'.$data_mapel['id'].'">'.$data_mapel['mapel'].'</option>';
}

?>

<div class="row mb-4">
    <div class="col-md-12">
        <h4 class="mb-0">
            <i class="fas fa-file-alt text-primary"></i> Manajemen Materi
        </h4>
        <p class="text-muted mb-0">Kelola materi pembelajaran</p>
    </div>
</div>

<!-- Include modal template -->
<?php include 'modal_template.php'; ?>

<!-- Action buttons -->
<div class="row mb-3">
    <div class="col-md-12">
        <button class="btn btn-primary" onclick="openModal('tambah')">
            <i class="fas fa-plus"></i> Tambah Data
        </button>
        <a href="./laporan/cetak_print.php?data=materi" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-print"></i> Cetak
        </a>
    </div>
</div>

<!-- Data table -->
<div class="row">
    <div class="col-md-12">
        <div class="modern-card shadow-sm">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="datamateri" data-export="true">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Mata Pelajaran</th>
                                <th>File</th>
                                <th>Status File</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_materi = mysqli_query($db, "SELECT * FROM tb_file_materi ORDER BY id_materi DESC");
                            $no = 1;
                            while($data_materi = mysqli_fetch_array($sql_materi)) {
                                // Get mapel name
                                $sql_get_mapel = mysqli_query($db, "SELECT mapel FROM tb_mapel WHERE id = '{$data_materi['id_mapel']}'");
                                $mapel_nama = '-';
                                if(mysqli_num_rows($sql_get_mapel) > 0) {
                                    $mapel_data = mysqli_fetch_array($sql_get_mapel);
                                    $mapel_nama = $mapel_data['mapel'];
                                }
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $data_materi['judul']; ?></td>
                                <td><?php echo $mapel_nama; ?></td>
                                <td>
                                    <?php 
                                    if(!empty($data_materi['nama_file'])) {
                                        $fileExtension = pathinfo($data_materi['nama_file'], PATHINFO_EXTENSION);
                                        $icon = '';
                                        switch(strtolower($fileExtension)) {
                                            case 'pdf':
                                                $icon = 'fa-file-pdf text-danger';
                                                break;
                                            case 'doc':
                                            case 'docx':
                                                $icon = 'fa-file-word text-primary';
                                                break;
                                            case 'xls':
                                            case 'xlsx':
                                                $icon = 'fa-file-excel text-success';
                                                break;
                                            case 'ppt':
                                            case 'pptx':
                                                $icon = 'fa-file-powerpoint text-warning';
                                                break;
                                            case 'jpg':
                                            case 'jpeg':
                                            case 'png':
                                            case 'gif':
                                                $icon = 'fa-file-image text-info';
                                                break;
                                            default:
                                                $icon = 'fa-file text-secondary';
                                        }
                                        echo '<div class="d-flex align-items-center">';
                                        echo '<i class="fas ' . $icon . ' me-2"></i>';
                                        $downloadUrl = 'inc/download_materi.php?file=' . rawurlencode($data_materi['nama_file']);
                                        echo '<a href="' . $downloadUrl . '" target="_blank" class="text-decoration-none" download="' . htmlspecialchars($data_materi['nama_file']) . '">' . htmlspecialchars(substr($data_materi['nama_file'], 0, 20)) . (strlen($data_materi['nama_file']) > 20 ? '...' : '') . '</a>';
                                        echo '</div>';
                                    } else {
                                        echo '<span class="badge bg-warning">Tidak Ada File</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if(!empty($data_materi['nama_file'])) {
                                        echo '<span class="badge bg-success">Tersedia</span>';
                                    } else {
                                        echo '<span class="badge bg-warning">Belum Tersedia</span>';
                                    }
                                    ?>
                                </td>
                                <td width="200px">
                                    <button class="btn btn-warning btn-sm me-1" onclick="openModal('edit', '<?php echo $data_materi['id_materi']; ?>')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete('Yakin akan menghapus materi <?php echo $data_materi['judul']; ?>?', 'materi', <?php echo $data_materi['id_materi']; ?>)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set the entity process file for the modal
var entityProcessFile = 'materi';

// Override form content loader for materi
function loadFormContent(action, id) {
    let content = '';
    
    if(action === 'tambah') {
        // Fetch mapel options dynamically
        fetch('./inc/get_available_mapel.php')
        .then(response => response.json())
        .then(mapelOptions => {
            let mapelSelect = '<select name="id_mapel" class="form-control" required>' +
                             '<option value="">-- Pilih --</option>';
            
            mapelOptions.forEach(mapel => {
                mapelSelect += '<option value="' + mapel.id + '">' + mapel.mapel + '</option>';
            });
            
            mapelSelect += '</select>';
            
            // Fetch kelas options
            fetch('./inc/get_available_kelas.php')
            .then(response => response.json())
            .then(kelasOptions => {
                let kelasSelect = '<select name="id_kelas" class="form-control" required>' +
                                 '<option value="">-- Pilih --</option>';
                                
                kelasOptions.forEach(kelas => {
                    kelasSelect += '<option value="' + kelas.id_kelas + '">' + kelas.nama_kelas + '</option>';
                });
                                
                kelasSelect += '</select>';
                                
                content = '<input type="hidden" name="action" value="tambah">' +
                        '<div class="row g-3">' +
                            '<div class="col-md-12">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Judul Materi *</label>' +
                                    '<input type="text" name="judul" class="form-control" required>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row g-3">' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Mata Pelajaran *</label>' +
                                    mapelSelect +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Kelas *</label>' +
                                    kelasSelect +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row g-3">' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">File Materi</label>' +
                                    '<input type="file" name="file" class="form-control">' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Status</label>' +
                                    '<select name="status" class="form-control">' +
                                        '<option value="aktif">Aktif</option>' +
                                        '<option value="tidak">Tidak Aktif</option>' +
                                    '</select>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                                
                document.getElementById('formContent').innerHTML = content;
            })
            .catch(error => {
                console.error('Error fetching kelas options:', error);
                // Fallback to basic options if AJAX fails
                content = '<input type="hidden" name="action" value="tambah">' +
                        '<div class="row g-3">' +
                            '<div class="col-md-12">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Judul Materi *</label>' +
                                    '<input type="text" name="judul" class="form-control" required>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row g-3">' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Mata Pelajaran *</label>' +
                                    mapelSelect +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Kelas *</label>' +
                                    '<select name="id_kelas" class="form-control" required>' +
                                        '<option value="">-- Pilih --</option>' +
                                        '<option value="1">Contoh Kelas 1</option>' +
                                        '<option value="2">Contoh Kelas 2</option>' +
                                    '</select>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row g-3">' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">File Materi</label>' +
                                    '<input type="file" name="file" class="form-control">' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Status</label>' +
                                    '<select name="status" class="form-control">' +
                                        '<option value="aktif">Aktif</option>' +
                                        '<option value="tidak">Tidak Aktif</option>' +
                                    '</select>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                                
                document.getElementById('formContent').innerHTML = content;
            });
            
            document.getElementById('formContent').innerHTML = content;
        })
        .catch(error => {
            console.error('Error fetching mapel options:', error);
            // Fallback to basic options if AJAX fails
            content = '<input type="hidden" name="action" value="tambah">' +
                    '<div class="row g-3">' +
                        '<div class="col-md-12">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Judul Materi *</label>' +
                                '<input type="text" name="judul" class="form-control" required>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row g-3">' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Mata Pelajaran *</label>' +
                                '<select name="id_mapel" class="form-control" required>' +
                                    '<option value="">-- Pilih --</option>' +
                                    '<option value="1">Matematika</option>' +
                                    '<option value="2">Bahasa Indonesia</option>' +
                                    '<option value="3">Bahasa Inggris</option>' +
                                    '<option value="4">IPA</option>' +
                                    '<option value="5">IPS</option>' +
                                '</select>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Kelas *</label>' +
                                '<select name="id_kelas" class="form-control" required>' +
                                    '<option value="">-- Pilih --</option>' +
                                    '<option value="1">Kelas 1</option>' +
                                    '<option value="2">Kelas 2</option>' +
                                '</select>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row g-3">' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">File Materi</label>' +
                                '<input type="file" name="file" class="form-control">' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Status</label>' +
                                '<select name="status" class="form-control">' +
                                    '<option value="aktif">Aktif</option>' +
                                    '<option value="tidak">Tidak Aktif</option>' +
                                '</select>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
            
            // Fetch kelas options for fallback
            fetch('./inc/get_available_kelas.php')
            .then(response => response.json())
            .then(kelasOptions => {
                let kelasSelect = '<select name="id_kelas" class="form-control" required>' +
                                 '<option value="">-- Pilih --</option>';
                            
                kelasOptions.forEach(kelas => {
                    kelasSelect += '<option value="' + kelas.id_kelas + '">' + kelas.nama_kelas + '</option>';
                });
                            
                kelasSelect += '</select>';
                            
                let updatedContent = '<input type="hidden" name="action" value="tambah">' +
                        '<div class="row g-3">' +
                            '<div class="col-md-12">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Judul Materi *</label>' +
                                    '<input type="text" name="judul" class="form-control" required>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row g-3">' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Mata Pelajaran *</label>' +
                                    '<select name="id_mapel" class="form-control" required>' +
                                        '<option value="">-- Pilih --</option>' +
                                        '<option value="1">Matematika</option>' +
                                        '<option value="2">Bahasa Indonesia</option>' +
                                        '<option value="3">Bahasa Inggris</option>' +
                                        '<option value="4">IPA</option>' +
                                        '<option value="5">IPS</option>' +
                                    '</select>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Kelas *</label>' +
                                    kelasSelect +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row g-3">' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">File Materi</label>' +
                                    '<input type="file" name="file" class="form-control">' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Status</label>' +
                                    '<select name="status" class="form-control">' +
                                        '<option value="aktif">Aktif</option>' +
                                        '<option value="tidak">Tidak Aktif</option>' +
                                    '</select>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                            
                document.getElementById('formContent').innerHTML = updatedContent;
            })
            .catch(error => {
                // If both AJAX calls fail, use basic options
                document.getElementById('formContent').innerHTML = content;
            });
        });
    } else if(action === 'edit') {
        // Fetch existing data for edit mode
        fetch('./inc/get_materi_data.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                autoError(data.error);
                return;
            }
            
            // Fetch mapel options dynamically
            fetch('./inc/get_available_mapel.php')
            .then(response => response.json())
            .then(mapelOptions => {
                let mapelSelect = '<select name="id_mapel" class="form-control" required>' +
                                 '<option value="">-- Pilih --</option>';
                
                mapelOptions.forEach(mapel => {
                    let selected = (mapel.id == data.id_mapel) ? 'selected' : '';
                    mapelSelect += '<option value="' + mapel.id + '" ' + selected + '>' + mapel.mapel + '</option>';
                });
                
                mapelSelect += '</select>';
                
                // Fetch kelas options
                fetch('./inc/get_available_kelas.php')
                .then(response => response.json())
                .then(kelasOptions => {
                    let kelasSelect = '<select name="id_kelas" class="form-control" required>' +
                                     '<option value="">-- Pilih --</option>';
                    
                    kelasOptions.forEach(kelas => {
                        let selected = (kelas.id_kelas == data.id_kelas) ? 'selected' : '';
                        kelasSelect += '<option value="' + kelas.id_kelas + '" ' + selected + '>' + kelas.nama_kelas + '</option>';
                    });
                    
                    kelasSelect += '</select>';
                    
                    content = '<input type="hidden" name="action" value="edit">' +
                            '<input type="hidden" name="id" value="' + id + '">' +
                            '<div class="row g-3">' +
                                '<div class="col-md-12">' +
                                    '<div class="mb-3">' +
                                        '<label class="form-label">Judul Materi *</label>' +
                                        '<input type="text" name="judul" class="form-control" value="' + (data.judul || '') + '" required>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="row g-3">' +
                                '<div class="col-md-6">' +
                                    '<div class="mb-3">' +
                                        '<label class="form-label">Mata Pelajaran *</label>' +
                                        mapelSelect +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                    '<div class="mb-3">' +
                                        '<label class="form-label">Kelas *</label>' +
                                        kelasSelect +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="row g-3">' +
                                '<div class="col-md-6">' +
                                    '<div class="mb-3">' +
                                        '<label class="form-label">File Materi (kosongkan jika tidak ingin mengganti)</label>' +
                                        '<input type="file" name="file" class="form-control">' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                    '<div class="mb-3">' +
                                        '<label class="form-label">Status</label>' +
                                        '<select name="status" class="form-control">' +
                                            '<option value="aktif" ' + (data.status == 'aktif' || data.status == undefined ? 'selected' : '') + '>Aktif</option>' +
                                            '<option value="tidak" ' + (data.status == 'tidak' ? 'selected' : '') + '>Tidak Aktif</option>' +
                                        '</select>' +
                                    '</div>' +
                                '</div>' +
                            '</div>';
                    
                    document.getElementById('formContent').innerHTML = content;
                })
                .catch(error => {
                    console.error('Error fetching kelas options:', error);
                    // Fallback to basic options if AJAX fails
                    content = '<input type="hidden" name="action" value="edit">' +
                            '<input type="hidden" name="id" value="' + id + '">' +
                            '<div class="row g-3">' +
                                '<div class="col-md-12">' +
                                    '<div class="mb-3">' +
                                        '<label class="form-label">Judul Materi *</label>' +
                                        '<input type="text" name="judul" class="form-control" value="' + (data.judul || '') + '" required>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="row g-3">' +
                                '<div class="col-md-6">' +
                                    '<div class="mb-3">' +
                                        '<label class="form-label">Mata Pelajaran *</label>' +
                                        mapelSelect +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                    '<div class="mb-3">' +
                                        '<label class="form-label">Kelas *</label>' +
                                        '<select name="id_kelas" class="form-control" required>' +
                                            '<option value="">-- Pilih --</option>' +
                                            '<option value="1">Contoh Kelas 1</option>' +
                                            '<option value="2">Contoh Kelas 2</option>' +
                                        '</select>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="row g-3">' +
                                '<div class="col-md-6">' +
                                    '<div class="mb-3">' +
                                        '<label class="form-label">File Materi (kosongkan jika tidak ingin mengganti)</label>' +
                                        '<input type="file" name="file" class="form-control">' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-md-6">' +
                                    '<div class="mb-3">' +
                                        '<label class="form-label">Status</label>' +
                                        '<select name="status" class="form-control">' +
                                            '<option value="aktif" ' + (data.status == 'aktif' || data.status == undefined ? 'selected' : '') + '>Aktif</option>' +
                                            '<option value="tidak" ' + (data.status == 'tidak' ? 'selected' : '') + '>Tidak Aktif</option>' +
                                        '</select>' +
                                    '</div>' +
                                '</div>' +
                            '</div>';
                    
                    document.getElementById('formContent').innerHTML = content;
                });
                
                document.getElementById('formContent').innerHTML = content;
            })
            .catch(error => {
                console.error('Error fetching mapel options:', error);
                // Fallback to basic options if AJAX fails
                content = '<input type="hidden" name="action" value="edit">' +
                        '<input type="hidden" name="id" value="' + id + '">' +
                        '<div class="row g-3">' +
                            '<div class="col-md-12">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Judul Materi *</label>' +
                                    '<input type="text" name="judul" class="form-control" value="' + (data.judul || '') + '" required>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row g-3">' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Mata Pelajaran *</label>' +
                                    '<select name="id_mapel" class="form-control" required>' +
                                        '<option value="">-- Pilih --</option>' +
                                        '<option value="1" ' + (data.id_mapel == '1' ? 'selected' : '') + '>Matematika</option>' +
                                        '<option value="2" ' + (data.id_mapel == '2' ? 'selected' : '') + '>Bahasa Indonesia</option>' +
                                        '<option value="3" ' + (data.id_mapel == '3' ? 'selected' : '') + '>Bahasa Inggris</option>' +
                                        '<option value="4" ' + (data.id_mapel == '4' ? 'selected' : '') + '>IPA</option>' +
                                        '<option value="5" ' + (data.id_mapel == '5' ? 'selected' : '') + '>IPS</option>' +
                                    '</select>' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Kelas *</label>' +
                                    '<select name="id_kelas" class="form-control" required>' +
                                        '<option value="">-- Pilih --</option>' +
                                        '<option value="1" ' + (data.id_kelas == '1' ? 'selected' : '') + '>Kelas 1</option>' +
                                        '<option value="2" ' + (data.id_kelas == '2' ? 'selected' : '') + '>Kelas 2</option>' +
                                    '</select>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="row g-3">' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">File Materi (kosongkan jika tidak ingin mengganti)</label>' +
                                    '<input type="file" name="file" class="form-control">' +
                                '</div>' +
                            '</div>' +
                            '<div class="col-md-6">' +
                                '<div class="mb-3">' +
                                    '<label class="form-label">Status</label>' +
                                    '<select name="status" class="form-control">' +
                                        '<option value="aktif" ' + (data.status == 'aktif' || data.status == undefined ? 'selected' : '') + '>Aktif</option>' +
                                        '<option value="tidak" ' + (data.status == 'tidak' ? 'selected' : '') + '>Tidak Aktif</option>' +
                                    '</select>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                
                document.getElementById('formContent').innerHTML = content;
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            autoError('Gagal memuat data untuk diedit');
        });
        return; // Exit early since we're handling async operation
    }
}
</script>

<?php } else { ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        Akses ditolak! Hanya admin atau pengajar yang dapat mengakses halaman ini.
    </div>
<?php } ?>