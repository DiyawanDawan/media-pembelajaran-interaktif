<?php
session_start();

// Hapus session admin
unset($_SESSION['admin_logged_in']);

// Redirect ke halaman login
header("Location: index.php");
exit;
?>