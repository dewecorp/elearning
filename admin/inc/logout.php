<?php
@session_start();
session_destroy();

// Clear session cookies
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Direct redirect to main admin login
header("Location: ../index.php");
exit();
?>