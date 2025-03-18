<?php
session_start();
// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../../includes/db.php';

// Ambil ID kuis dari parameter URL
if (!isset($_GET['id'])) {
    header("Location: list_kuis.php");
    exit;
}
$id_kuis = $_GET['id'];

// Query untuk menghapus data kuis
try {
    $sql = "DELETE FROM kuis WHERE id_kuis = :id_kuis";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_kuis' => $id_kuis]);

    // Redirect ke halaman list kuis dengan pesan sukses
    header("Location: list_kuis.php?status=success");
    exit;
} catch (Exception $e) {
    // Redirect ke halaman list kuis dengan pesan error
    header("Location: list_kuis.php?status=error");
    exit;
}
?>