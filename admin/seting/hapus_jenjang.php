<?php
session_start();

// Pastikan admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require '../../includes/db.php';

// Cek apakah ID jenjang diberikan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list_jenjang.php?status=error");
    exit;
}

$id_jenjang = $_GET['id'];

// Hapus data dari database
$sql = "DELETE FROM jenjang_pendidikan WHERE id_jenjang = :id";
$stmt = $pdo->prepare($sql);
$delete = $stmt->execute(['id' => $id_jenjang]);

if ($delete) {
    header("Location: list_jenjang.php?status=success");
    exit;
} else {
    header("Location: list_jenjang.php?status=error");
    exit;
}
?>
