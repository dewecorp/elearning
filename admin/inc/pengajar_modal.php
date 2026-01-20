<?php
@session_start();

if(@$_SESSION['admin']) { ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="page-header">Manajemen Pengajar</h1>
    </div>
</div>

<!-- Include modal template -->
<?php include 'modal_template.php'; ?>

<!-- Action buttons -->
<div class="row mb-4">
    <div class="col-md-12 d-flex flex-wrap gap-2">
        <button class="btn btn-primary btn-sm" onclick="openModal('tambah')">
            <i class="fas fa-plus"></i> Tambah Data
        </button>
        <a href="./laporan/cetak_print.php?data=pengajar" target="_blank" class="btn btn-outline-secondary btn-sm">Cetak Data Pengajar</a>
    </div>
</div>

<!-- Data table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="datapengajar" data-export="true">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>NIP</th>
                                <th>Nama Lengkap</th>
                                <th>Jenis Kelamin</th>
                                <th>Status</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 1;
                        $sql_pengajar = mysqli_query($db, "SELECT * FROM tb_pengajar") or die ($db->error);
                        if(mysqli_num_rows($sql_pengajar) > 0) {
	                        while($data_pengajar = mysqli_fetch_array($sql_pengajar)) {
	                        ?>
	                            <tr>
	                                <td><?php echo $no++; ?></td>
	                                <td><?php echo $data_pengajar['nip']; ?></td>
	                                <td><?php echo $data_pengajar['nama_lengkap']; ?></td>
	                                <td><?php echo $data_pengajar['jenis_kelamin']; ?></td>
	                                <td><?php echo ucfirst($data_pengajar['status']); ?></td>
	                                <td align="center" width="170px">
	                                    <button class="badge btn btn btn-warning btn-xs" onclick="openModal('edit', '<?php echo $data_pengajar['id_pengajar']; ?>')">
	                                        <i class="fas fa-edit"></i> Edit
	                                    </button>
	                                    <button class="badge btn btn btn-danger btn-xs" onclick="confirmDelete('Yakin akan menghapus data?', '?page=pengajar&action=hapus&id=<?php echo $data_pengajar['id_pengajar']; ?>')">
	                                        <i class="fas fa-trash"></i> Hapus
	                                    </button>
	                                    <a href="?page=pengajar&action=detail&id=<?php echo $data_pengajar['id_pengajar']; ?>" class="badge">Detail</a>
	                                </td>
	                            </tr>
	                        <?php
		                    }
		                } else {
		                	?>
							<tr>
                                <td colspan="6" align="center">Data tidak ditemukan</td>
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

<?php 
} else if(@$_GET['action'] == 'detail') {
    $id = @$_GET['id'];
    $sql_per_id = mysqli_query($db, "SELECT * FROM tb_pengajar WHERE id_pengajar = '$id'") or die ($db->error);
    $data = mysqli_fetch_array($sql_per_id);
    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Detail Data Pengajar &nbsp; <a href="?page=pengajar" class="btn btn btn-warning btn-sm">Kembali</a></div>
                <div class="panel-body">
                	<div class="table-responsive">
                        <table width="100%">
                        	<tr>
                        		<td align="right" width="46%"><b>NIP</b></td>
                        		<td align="center">:</td>
                        		<td width="46%"><?php echo $data['nip']; ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Nama Lengkap</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo $data['nama_lengkap']; ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Tempat Tanggal Lahir</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo $data['tempat_lahir'].", ".tgl_indo($data['tgl_lahir']); ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Jenis Kelamin</b></td>
                        		<td align="center">:</td>
                        		<td><?php if($data['jenis_kelamin'] == 'L') { echo "Laki-laki"; } else { echo "Perempuan"; } ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Agama</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo $data['agama']; ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Nomor Telepon</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo $data['no_telp']; ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Email</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo $data['email']; ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Alamat</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo $data['alamat']; ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Jabatan</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo $data['jabatan']; ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right" valign="top"><b>Foto</b></td>
                        		<td align="center" valign="top">:</td>
                        		<td>
                        			<div style="padding:10px 0;"><img width="250px" src="../admin/img/foto_pengajar/<?php echo $data['foto']; ?>" /></div>
                        		</td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Website</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo $data['web']; ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Username</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo $data['username']; ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Password</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo $data['pass']; ?></td>
                        	</tr>
                        	<tr>
                        		<td align="right"><b>Status</b></td>
                        		<td align="center">:</td>
                        		<td><?php echo ucfirst($data['status']); ?></td>
                        	</tr>
                        </table>
                    </div>
                </div>
		    </div>
		</div>
	</div>
    <?php
} else if(@$_GET['action'] == 'hapus') {
    $id = @$_GET['id'];
    mysqli_query($db, "DELETE FROM tb_pengajar WHERE id_pengajar = '$id'") or die ($db->error);
    echo '<script>autoSuccess("Data berhasil dihapus!"); setTimeout(function(){ window.location="?page=pengajar"; }, 1500);</script>';
} ?>

<script>
// Set the entity process file for the modal
var entityProcessFile = 'pengajar';

// Override form content loader for pengajar
function loadFormContent(action, id) {
    console.log('pengajar loadFormContent called with:', action, id);
    let content = '';
    
    if(action === 'tambah') {
        content = `<input type="hidden" name="action" value="tambah">` +
                '<div class="row g-3">' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">NIP *</label>' +
                            '<input type="text" name="nip" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">No Telepon *</label>' +
                            '<input type="text" name="no_telp" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Nama Lengkap *</label>' +
                            '<input type="text" name="nama_lengkap" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Email</label>' +
                            '<input type="email" name="email" class="form-control">' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Tempat Lahir *</label>' +
                            '<input type="text" name="tempat_lahir" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Tanggal Lahir *</label>' +
                            '<input type="date" name="tgl_lahir" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Jenis Kelamin *</label>' +
                            '<select name="jenis_kelamin" class="form-control" required>' +
                                '<option value="">-- Pilih --</option>' +
                                '<option value="L">Laki-laki</option>' +
                                '<option value="P">Perempuan</option>' +
                            '</select>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Agama</label>' +
                            '<select name="agama" class="form-control" required>' +
                                '<option value="">-- Pilih --</option>' +
                                '<option value="Islam">Islam</option>' +
                                '<option value="Kristen">Kristen</option>' +
                                '<option value="Katolik">Katolik</option>' +
                                '<option value="Hindu">Hindu</option>' +
                                '<option value="Buddha">Buddha</option>' +
                                '<option value="Konghucu">Konghucu</option>' +
                            '</select>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-12">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Alamat</label>' +
                            '<textarea name="alamat" class="form-control" rows="3"></textarea>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Username *</label>' +
                            '<input type="text" name="username" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Password *</label>' +
                            '<input type="password" name="password" class="form-control" required>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-md-12">' +
                        '<div class="mb-3">' +
                            '<label class="form-label">Status *</label>' +
                            '<select name="status" class="form-control" required>' +
                                '<option value="">-- Pilih --</option>' +
                                '<option value="aktif">Aktif</option>' +
                                '<option value="tidak">Tidak Aktif</option>' +
                            '</select>' +
                        '</div>' +
                    '</div>' +
                '</div>';
    } else if(action === 'edit') {
        // Fetch existing data for edit mode
        fetch('./inc/get_pengajar_data.php?id=' + id)
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
                                '<label class="form-label">NIP *</label>' +
                                '<input type="text" name="nip" class="form-control" value="' + (data.nip || '') + '" required>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">No Telepon *</label>' +
                                '<input type="text" name="no_telp" class="form-control" value="' + (data.no_telp || '') + '" required>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row g-3">' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Nama Lengkap *</label>' +
                                '<input type="text" name="nama_lengkap" class="form-control" value="' + (data.nama_lengkap || '') + '" required>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Email</label>' +
                                '<input type="email" name="email" class="form-control" value="' + (data.email || '') + '">' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row g-3">' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Tempat Lahir *</label>' +
                                '<input type="text" name="tempat_lahir" class="form-control" value="' + (data.tempat_lahir || '') + '" required>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Tanggal Lahir *</label>' +
                                '<input type="date" name="tgl_lahir" class="form-control" value="' + (data.tgl_lahir || '') + '" required>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row g-3">' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Jenis Kelamin *</label>' +
                                '<select name="jenis_kelamin" class="form-control" required>' +
                                    '<option value="">-- Pilih --</option>' +
                                    '<option value="L" ' + (data.jenis_kelamin === 'L' ? 'selected' : '') + '>Laki-laki</option>' +
                                    '<option value="P" ' + (data.jenis_kelamin === 'P' ? 'selected' : '') + '>Perempuan</option>' +
                                '</select>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Agama</label>' +
                                '<select name="agama" class="form-control" required>' +
                                    '<option value="">-- Pilih --</option>' +
                                    '<option value="Islam" ' + (data.agama === 'Islam' ? 'selected' : '') + '>Islam</option>' +
                                    '<option value="Kristen" ' + (data.agama === 'Kristen' ? 'selected' : '') + '>Kristen</option>' +
                                    '<option value="Katolik" ' + (data.agama === 'Katolik' ? 'selected' : '') + '>Katolik</option>' +
                                    '<option value="Hindu" ' + (data.agama === 'Hindu' ? 'selected' : '') + '>Hindu</option>' +
                                    '<option value="Buddha" ' + (data.agama === 'Buddha' ? 'selected' : '') + '>Buddha</option>' +
                                    '<option value="Konghucu" ' + (data.agama === 'Konghucu' ? 'selected' : '') + '>Konghucu</option>' +
                                '</select>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row g-3">' +
                        '<div class="col-md-12">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Alamat</label>' +
                                '<textarea name="alamat" class="form-control" rows="3">' + (data.alamat || '') + '</textarea>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row g-3">' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Username *</label>' +
                                '<input type="text" name="username" class="form-control" value="' + (data.username || '') + '" required>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-6">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Password</label>' +
                                '<input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah">' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="row g-3">' +
                        '<div class="col-md-12">' +
                            '<div class="mb-3">' +
                                '<label class="form-label">Status *</label>' +
                                '<select name="status" class="form-control" required>' +
                                    '<option value="">-- Pilih --</option>' +
                                    '<option value="aktif" ' + (data.status === 'aktif' ? 'selected' : '') + '>Aktif</option>' +
                                    '<option value="tidak" ' + (data.status === 'tidak' ? 'selected' : '') + '>Tidak Aktif</option>' +
                                '</select>' +
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
    
    console.log('Setting form content, length:', content.length);
    document.getElementById('formContent').innerHTML = content;
}
</script>