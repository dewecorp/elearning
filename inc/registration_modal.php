<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="registrationModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Daftar Akun E-Learning
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <form id="registrationForm" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nis" class="form-label fw-bold">NIS *</label>
                                    <input type="text" name="nis" id="nis" class="form-control" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nama_lengkap" class="form-label fw-bold">Nama Lengkap *</label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required />
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tempat_lahir" class="form-label fw-bold">Tempat Lahir *</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tgl_lahir" class="form-label fw-bold">Tanggal Lahir *</label>
                                    <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control" required />
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_kelamin" class="form-label fw-bold">Jenis Kelamin *</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                                        <option value="">- Pilih -</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="agama" class="form-label fw-bold">Agama *</label>
                                    <select name="agama" id="agama" class="form-select" required>
                                        <option value="">- Pilih -</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katholik">Katholik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Budha">Budha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nama_ayah" class="form-label fw-bold">Nama Ayah *</label>
                                <input type="text" name="nama_ayah" id="nama_ayah" class="form-control" required />
                            </div>
                            
                            <div class="mb-3">
                                <label for="nama_ibu" class="form-label fw-bold">Nama Ibu *</label>
                                <input type="text" name="nama_ibu" id="nama_ibu" class="form-control" required />
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_telp" class="form-label fw-bold">Nomor Telepon</label>
                                    <input type="text" name="no_telp" id="no_telp" class="form-control" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" />
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-bold">Alamat *</label>
                                <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col-md-6">
                        <form id="registrationForm2" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="kelas" class="form-label fw-bold">Kelas *</label>
                                <select name="kelas" id="kelas" class="form-select" required>
                                    <option value="">- Pilih -</option>
                                    <?php
                                    $sql_kelas = mysqli_query($db, "SELECT * from tb_kelas") or die ($db->error);
                                    while($data_kelas = mysqli_fetch_array($sql_kelas)) {
                                        echo '<option value="'.$data_kelas['id_kelas'].'">'.$data_kelas['nama_kelas'].'</option>';
                                    } ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="thn_masuk" class="form-label fw-bold">Tahun Masuk *</label>
                                <select name="thn_masuk" id="thn_masuk" class="form-select" required>
                                    <option value="">- Pilih -</option>
                                    <?php
                                    for ($i = 2021; $i >= 2000; $i--) { 
                                        echo '<option value="'.$i.'">'.$i.'</option>';
                                    } ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="gambar" class="form-label fw-bold">Foto</label>
                                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*" />
                                <div class="form-text">Format: JPG, PNG, maksimal 2MB</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="user" class="form-label fw-bold">Username *</label>
                                <input type="text" name="user" id="user" class="form-control" required />
                                <div class="form-text">Gunakan username yang belum digunakan</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="pass" class="form-label fw-bold">Password *</label>
                                <input type="password" name="pass" id="pass" class="form-control" required />
                                <div class="form-text">Minimal 6 karakter</div>
                            </div>
                            
                            <div class="alert alert-info mt-4">
                                <h6><i class="fas fa-info-circle me-2"></i>Catatan:</h6>
                                <p class="mb-0">Tanda <strong>*</strong> wajib diisi</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Batal
                </button>
                <button type="button" class="btn btn-primary" id="registerBtn">
                    <i class="fas fa-check me-1"></i>Daftar Sekarang
                </button>
            </div>
        </div>
    </div>
</div>