<?php
// Modern SweetAlert2 Helper Functions

function autoSuccess($message) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '$message',
            timer: 1500,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    </script>";
}

function autoError($message) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '$message',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    </script>";
}

function showSuccess($title, $message, $autoClose = 2000) {
    echo "<script>
        Swal.fire({
            title: '$title',
            text: '$message',
            icon: 'success',
            timer: $autoClose,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    </script>";
}

function showError($title, $message) {
    echo "<script>
        Swal.fire({
            title: '$title',
            text: '$message',
            icon: 'error',
            confirmButtonColor: '#d33',
            confirmButtonText: 'OK'
        });
    </script>";
}

function showLoading($title = 'Memproses...') {
    echo "<script>
        Swal.fire({
            title: '$title',
            html: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            didOpen: function() {
                Swal.showLoading();
            }
        });
    </script>";
}

function hideLoading() {
    echo "<script>Swal.close();</script>";
}

function showConfirm($title, $message, $callback) {
    echo "<script>
        Swal.fire({
            title: '$title',
            text: '$message',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $callback
            }
        });
    </script>";
}
?>