<?php
@session_start();
include "koneksi.php";

if(!@$_SESSION['admin'] && !@$_SESSION['pengajar']) {
    echo "<script>window.location='index.php';</script>";
} else {
    // Load pengaturan
    include "pengaturan_sweet.php";
}
?>