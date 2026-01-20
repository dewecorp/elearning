<?php
@session_start();
include "koneksi.php";

if(!@$_SESSION['admin'] && !@$_SESSION['pengajar']) {
    echo "<script>window.location='index.php';</script>";
} else {
    // Load quiz modal
    include "inc/quiz_modal.php";
}
?>