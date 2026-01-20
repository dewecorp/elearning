<!-- Modal Template -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="modalFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFormLabel">
                    <i class="fas fa-plus"></i> <span id="modalTitle">Tambah Data</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formModal" method="post" enctype="multipart/form-data">
                    <div id="formContent">
                        <!-- Dynamic form content -->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" onclick="submitModalForm()">
                    <i class="fas fa-save"></i> <span id="submitButtonText">Simpan</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Registration Modal -->
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

<script>
// Global variables for modal
let currentAction = '';
let currentEditId = '';
let modalForm;

// Initialize modal when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap !== 'undefined') {
        modalForm = new bootstrap.Modal(document.getElementById('modalForm'));
    }
});

// Fallback modal initialization
function initializeModal() {
    if (!modalForm && typeof bootstrap !== 'undefined') {
        modalForm = new bootstrap.Modal(document.getElementById('modalForm'));
    }
}

// Function to open modal with form
function openModal(action, id = '') {
    console.log('openModal called with:', action, id);
    currentAction = action;
    currentEditId = id;
    
    // Initialize modal if needed
    initializeModal();
    
    // Reset form
    document.getElementById('formContent').innerHTML = '';
    document.getElementById('formModal').reset();
    
    // Set modal title and button text
    if(action === 'tambah') {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Tambah Data';
        document.getElementById('submitButtonText').textContent = 'Simpan';
    } else if(action === 'edit') {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Data';
        document.getElementById('submitButtonText').textContent = 'Update';
    }
    
    // Load form content
    console.log('Calling loadFormContent');
    loadFormContent(action, id);
    
    // Show modal with delay to ensure content is loaded
    setTimeout(function() {
        console.log('Showing modal, modalForm exists:', !!modalForm);
        if (modalForm) {
            modalForm.show();
        } else {
            console.error('Modal form not initialized!');
        }
    }, 100);
}

// Function to submit form via AJAX
function submitModalForm() {
    showLoading('Memproses data...');
    
    const formData = new FormData(document.getElementById('formModal'));
    
    // Determine the process file to use - defaults to the currentAction if entityProcessFile is not set
    const processFile = typeof entityProcessFile !== 'undefined' ? entityProcessFile : currentAction;
    
    // Log for debugging
    console.log('Submitting form to:', 'inc/process_' + processFile + '.php');
    console.log('Current action:', currentAction);
    console.log('Entity process file:', processFile);
    console.log('Available entityProcessFile:', typeof entityProcessFile !== 'undefined' ? entityProcessFile : 'NOT SET');
    console.log('Current page path:', window.location.pathname);
    
    // Universal URL construction that works from any admin location
    // Get the base admin URL
    let adminBaseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + '/';
    
    // If we're in a subdirectory, adjust accordingly
    if (window.location.pathname.includes('/admin/inc/')) {
        adminBaseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -2).join('/') + '/';
    } else if (window.location.pathname.includes('/admin/')) {
        // Already in admin directory
        adminBaseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + '/';
    }
    
    const processUrl = adminBaseUrl + 'inc/process_' + processFile + '.php';
    console.log('Admin Base URL:', adminBaseUrl);
    console.log('Final process URL:', processUrl);
    
    fetch(processUrl, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
        hideLoading();
        if(data.success) {
            autoSuccess(data.message);
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            autoError(data.message);
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Submit error:', error);
        console.error('Error stack:', error.stack);
        autoError('Terjadi kesalahan koneksi atau server error: ' + error.message);
    });
}

// Function to close modal
function closeModal() {
    modalForm.hide();
}

// Load form content dynamically
function loadFormContent(action, id) {
    // This will be overridden by specific pages
}

// Registration modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle registration form submission
    const registerBtn = document.getElementById('registerBtn');
    if(registerBtn) {
        registerBtn.addEventListener('click', function() {
            const form1 = document.getElementById('registrationForm');
            const form2 = document.getElementById('registrationForm2');
            
            // Combine form data from both forms
            const formData = new FormData();
            
            // Add data from first form
            const inputs1 = form1.querySelectorAll('input, select, textarea');
            inputs1.forEach(input => {
                if(input.name && input.value) {
                    formData.append(input.name, input.value);
                }
            });
            
            // Add data from second form
            const inputs2 = form2.querySelectorAll('input, select, textarea');
            inputs2.forEach(input => {
                if(input.name && input.value) {
                    formData.append(input.name, input.value);
                }
            });
            
            // Add action for processing
            formData.append('action', 'register');
            
            // Validate required fields
            if(!validateRegistrationForm()) {
                return;
            }

            // Show loading indicator
            const registerBtn = document.getElementById('registerBtn');
            const originalText = registerBtn.innerHTML;
            registerBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
            registerBtn.disabled = true;

            // Submit form via AJAX
            fetch('inc/process_registration.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Restore button
                registerBtn.innerHTML = originalText;
                registerBtn.disabled = false;
                
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Close modal and reset form
                        bootstrap.Modal.getInstance(document.getElementById('registrationModal')).hide();
                        form1.reset();
                        form2.reset();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Restore button
                registerBtn.innerHTML = originalText;
                registerBtn.disabled = false;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mengirim data.',
                    confirmButtonText: 'OK'
                });
            });
        });
    }
});

function validateRegistrationForm() {
    // Get values from both forms
    const nis = document.getElementById('nis').value;
    const nama_lengkap = document.getElementById('nama_lengkap').value;
    const tempat_lahir = document.getElementById('tempat_lahir').value;
    const tgl_lahir = document.getElementById('tgl_lahir').value;
    const jenis_kelamin = document.getElementById('jenis_kelamin').value;
    const agama = document.getElementById('agama').value;
    const nama_ayah = document.getElementById('nama_ayah').value;
    const nama_ibu = document.getElementById('nama_ibu').value;
    const alamat = document.getElementById('alamat').value;
    const kelas = document.getElementById('kelas').value;
    const thn_masuk = document.getElementById('thn_masuk').value;
    const user = document.getElementById('user').value;
    const pass = document.getElementById('pass').value;

    // Validate required fields
    if(!nis || !nama_lengkap || !tempat_lahir || !tgl_lahir || !jenis_kelamin || 
       !agama || !nama_ayah || !nama_ibu || !alamat || !kelas || !thn_masuk || !user || !pass) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Harap isi semua field yang wajib diisi (bertanda *)',
            confirmButtonText: 'OK'
        });
        return false;
    }

    // Validate email if provided
    const email = document.getElementById('email').value;
    if(email && !isValidEmail(email)) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Format email tidak valid',
            confirmButtonText: 'OK'
        });
        return false;
    }

    // Validate password length
    if(pass.length < 6) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Password minimal 6 karakter',
            confirmButtonText: 'OK'
        });
        return false;
    }

    return true;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// SweetAlert confirmations
function confirmDelete(message, deleteUrl, id = null) {
    // Check if deleteUrl is an entity name (string without ? or /) for AJAX deletion
    if(typeof deleteUrl === 'string' && !deleteUrl.includes('?') && !deleteUrl.includes('/') && id !== null) {
        // AJAX deletion approach
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading('Menghapus data...');
                
                // Construct the process URL
                let adminBaseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + '/';
                if (window.location.pathname.includes('/admin/inc/')) {
                    adminBaseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -2).join('/') + '/';
                }
                
                const processUrl = adminBaseUrl + 'inc/process_' + deleteUrl + '.php';
                
                // Send AJAX request to delete
                fetch(processUrl + '?action=hapus&id=' + encodeURIComponent(id), {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if(data.success) {
                        autoSuccess(data.message);
                        setTimeout(() => {
                            if(data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                location.reload();
                            }
                        }, 1500);
                    } else {
                        autoError(data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Delete error:', error);
                    autoError('Terjadi kesalahan saat menghapus data: ' + error.message);
                });
            }
        });
    } else {
        // Traditional URL redirect approach
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }
        });
    }
}

// Helper functions
function showLoading(title) {
    Swal.fire({
        title: title || 'Memproses...',
        html: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        didOpen: function() {
            Swal.showLoading();
        }
    });
}

function hideLoading() {
    Swal.close();
}

function autoSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        timer: 1500,
        showConfirmButton: false,
        position: 'top-end',
        toast: true
    });
}

function autoError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: message,
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK'
    });
}

// Function to handle async form loading properly
function loadFormContentWithCallback(action, id, callback) {
    if(action === 'edit') {
        // Specific page will handle async operation
        loadFormContent(action, id);
    } else {
        // For non-edit actions, use the default behavior
        loadFormContent(action, id);
        if(callback) callback();
    }
}

// Updated openModal function to handle async properly
function openModal(action, id = '') {
    console.log('openModal called with:', action, id);
    currentAction = action;
    currentEditId = id;
    
    // Initialize modal if needed
    initializeModal();
    
    // Reset form
    document.getElementById('formContent').innerHTML = '';
    document.getElementById('formModal').reset();
    
    // Set modal title and button text
    if(action === 'tambah') {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Tambah Data';
        document.getElementById('submitButtonText').textContent = 'Simpan';
    } else if(action === 'edit') {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Data';
        document.getElementById('submitButtonText').textContent = 'Update';
    }
    
    // Load form content
    console.log('Calling loadFormContent');
    if(action === 'edit') {
        loadFormContent(action, id);
    } else {
        loadFormContent(action, id);
    }
    
    // Show modal with delay to ensure content is loaded
    setTimeout(function() {
        console.log('Showing modal, modalForm exists:', !!modalForm);
        if (modalForm) {
            modalForm.show();
        } else {
            console.error('Modal form not initialized!');
        }
    }, 100);
}</script>