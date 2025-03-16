<?php
session_start();
// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Cek apakah admin sudah login
if (!isset($_SESSION['id_guru'])) {
    header("Location: list.php");
    exit();
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
 <main class="content">
 <h1 class="dashboard-title">Dashboard</h1>

 </main>
 <?php include('../components/footer.php'); ?>
