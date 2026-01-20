<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
@session_start();
include "koneksi.php";

// If already logged in, redirect to dashboard
if(@$_SESSION['admin'] || @$_SESSION['pengajar']) {
	echo "<script>window.location='dashboard_new.php';</script>";
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin E-Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: Arial, sans-serif;
            padding: 40px 0;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .login-body {
            padding: 30px;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            width: 100%;
            padding: 12px;
            border-radius: 5px;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
    </style>
</head>

<body>
<div class="login-container">
    <div class="login-header">
        <h3><i class="fas fa-user-shield"></i> Login Administrator</h3>
        <p style="margin: 0;">Masukkan credentials Anda</p>
    </div>
    <div class="login-body">
        <form id="adminLoginForm">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="user" class="form-control" placeholder="Username admin" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="pass" class="form-control" placeholder="Password admin" required>
            </div>
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login Administrator
            </button>
        </form>
    </div>
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-3 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <small class="d-block mb-1">E-Learning System - Sistem Pembelajaran Elektronik</small>
                <small>&copy; <?php echo date('Y')?> E-Learning System. Hak Cipta Dilindungi.</small>
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function showSuccess(title, message, autoClose) {
    Swal.fire({
        title: title,
        text: message,
        icon: "success",
        timer: autoClose || 2000,
        timerProgressBar: true,
        showConfirmButton: false,
        position: "top-end",
        toast: true
    });
}

function showError(title, message) {
    Swal.fire({
        title: title,
        text: message,
        icon: "error",
        confirmButtonColor: "#d33",
        confirmButtonText: "OK"
    });
}

function autoSuccess(message) {
    showSuccess("Berhasil!", message, 1500);
}

function autoError(message) {
    showError("Gagal!", message);
}

function showLoading(title) {
    Swal.fire({
        title: title || "Memproses...",
        html: "Mohon tunggu sebentar...",
        allowOutsideClick: false,
        didOpen: function() {
            Swal.showLoading();
        }
    });
}

function hideLoading() {
    Swal.close();
}

$(document).ready(function() {
    $("#adminLoginForm").on("submit", function(e) {
        e.preventDefault();
        
        var username = $("input[name=user]").val();
        var password = $("input[name=pass]").val();
        
        if(username === "") {
            showError("Error", "Username tidak boleh kosong");
            return;
        }
        
        if(password === "") {
            showError("Error", "Password tidak boleh kosong");
            return;
        }
        
        showLoading("Login...");
        
        $.ajax({
            url: "inc/proses_login.php",
            type: "POST",
            data: {
                user: username,
                pass: password
            },
            dataType: "json",
            success: function(response) {
                hideLoading();
                if(response.status === "sukses") {
                    autoSuccess(response.message);
                    setTimeout(function() {
                        window.location = "dashboard_new.php";
                    }, 1500);
                } else {
                    autoError(response.message);
                }
            },
            error: function() {
                hideLoading();
                autoError("Terjadi kesalahan koneksi");
            }
        });
    });
});
</script>
</body>
</html>
<?php
}
?>