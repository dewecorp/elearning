<?php
// Script reset password admin
$db = mysqli_connect("localhost", "root", "", "e-learning");

if (!$db) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Reset password admin menjadi "admin"
$new_password = "admin";
$hashed_password = md5($new_password);

$sql = "UPDATE tb_admin SET password = '$hashed_password', pass = '$new_password' WHERE username = 'admin'";

if (mysqli_query($db, $sql)) {
    echo "Password admin berhasil direset!<br>";
    echo "Username: admin<br>";
    echo "Password: admin<br>";
    echo "Silakan login ke: <a href='admin/login.php'>admin/login.php</a>";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($db);
}

mysqli_close($db);
?>