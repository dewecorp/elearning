<?php
@session_start();
require_once 'sweetalert_helper.php';
if(@$_SESSION['admin']) { ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h4 class="mb-0">
            <i class="fas fa-book text-primary"></i> Manajemen Mata Pelajaran
        </h4>
        <p class="text-muted mb-0">Kelola data mata pelajaran untuk pembelajaran</p>
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
        <a href="./laporan/cetak_print.php?data=mapel" target="_blank" class="btn btn-outline-secondary">
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
                    <table class="table table-hover align-middle" id="datamapel" data-export="true">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Mata Pelajaran</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_mapel = mysqli_query($db, "SELECT * FROM tb_mapel ORDER BY mapel");
                            $no = 1;
                            while($data_mapel = mysqli_fetch_array($sql_mapel)) {
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $data_mapel['kode_mapel']; ?></td>
                                <td><?php echo $data_mapel['mapel']; ?></td>
                                <td width="200px">
                                    <button class="btn btn-warning btn-sm me-1" onclick="openModal('edit', '<?php echo $data_mapel['id']; ?>')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete('Yakin akan menghapus data mapel <?php echo $data_mapel['mapel']; ?>?', '?page=mapel&action=hapus&id=<?php echo $data_mapel['id']; ?>')">
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
var entityProcessFile = 'mapel';

// Override form content loader for mapel
function loadFormContent(action, id) {
    let content = '';
    
    if(action === 'tambah') {
        content = '<input type="hidden" name="action" value="tambah">' +
                '<div class="row g-3">' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Kode Mata Pelajaran *</label>' +
                            '<input type="text" name="kode_mapel" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Mata Pelajaran *</label>' +
                            '<input type="text" name="mapel" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                '</div>';
    } else if(action === 'edit') {
        // Fetch existing data for edit mode
        fetch('./inc/get_mapel_data.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                autoError(data.error);
                return;
            }
            
            content = '<input type="hidden" name="action" value="edit">' +
                    '<input type="hidden" name="id" value="' + id + '">' +
                    '<div class="row g-3">' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Kode Mata Pelajaran *</label>' +
                                '<input type="text" name="kode_mapel" class="form-control" value="' + (data.kode_mapel || '') + '" required>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Mata Pelajaran *</label>' +
                                '<input type="text" name="mapel" class="form-control" value="' + (data.mapel || '') + '" required>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
            document.getElementById('formContent').innerHTML = content;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            autoError('Gagal memuat data untuk diedit');
        });
        return; // Exit early since we're handling async operation
    }
    
    document.getElementById('formContent').innerHTML = content;
}
</script>

<?php } else { ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i>
        Akses ditolak! Hanya admin yang dapat mengakses halaman ini.
    </div>
<?php } ?>