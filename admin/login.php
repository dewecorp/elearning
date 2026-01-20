<?php
@session_start();
include "koneksi.php";

if(@$_SESSION['admin']) {
    echo "<script>window.location='dashboard_new.php';</script>";
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin E-Learning</title>
    <link href="style/assets/css/bootstrap.css" rel="stylesheet" />
    <link href="style/assets/css/font-awesome.css" rel="stylesheet" />
    <link href="style/assets/css/custom-styles.css" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div id="wrapper">
    <nav class="navbar navbar-default top-navbar" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./">Administrator</a>
        </div>
    </nav>

    <!-- Page Content -->
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Login Administrator</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" id="loginForm">
                                <div class="form-group">
                                    <label>Username</label>
                                    <input class="form-control" name="user" type="text" placeholder="Enter username" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="form-control" name="pass" type="password" placeholder="Enter password" required>
                                </div>
                                <button type="submit" class="btn btn-lg btn-success btn-block">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="style/assets/js/jquery-1.10.2.js"></script>
<script src="style/assets/js/bootstrap.min.js"></script>
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
    $("#loginForm").on("submit", function(e) {
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