<?php
session_start();

// Pastikan admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../../includes/db.php';

// Cek apakah ID CP/TP tersedia dari parameter GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Siapkan query hapus
    $sql = "DELETE FROM pembelajaran WHERE id_pembelajaran = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // Eksekusi query dan berikan feedback
    if ($stmt->execute()) {
        $_SESSION['alert'] = '<div class="alert alert-success">Data CP/TP berhasil dihapus!</div>';
        header("Location: list_cp_tp.php?status=success");
        exit;
    } else {
        $_SESSION['alert'] = '<div class="alert alert-error">Gagal menghapus data CP/TP!</div>';
        header("Location: list_cp_tp.php?status=error");
        exit;
    }
} else {
    $_SESSION['alert'] = '<div class="alert alert-error">ID tidak ditemukan!</div>';
    header("Location: list_cp_tp.php?status=error");
    exit;
}
?>
