<?php
@session_start();
include "koneksi.php";

if(!@$_SESSION['admin'] && !@$_SESSION['pengajar']) {
    echo "<script>window.location='index.php';</script>";
} else {
    // Load mapel modal
    include "inc/mapel_modal.php";
}
?>