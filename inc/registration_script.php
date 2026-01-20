<script>
// Registration modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Open registration modal when "Daftar" button is clicked
    const registerLinks = document.querySelectorAll('[data-bs-toggle="modal"][href="#registrationModal"]');
    registerLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('registrationModal'));
            modal.show();
        });
    });

    // Handle registration form submission
    document.getElementById('registerBtn').addEventListener('click', function() {
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
                    // Close modal and redirect or reset form
                    bootstrap.Modal.getInstance(document.getElementById('registrationModal')).hide();
                    location.reload();
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
</script>