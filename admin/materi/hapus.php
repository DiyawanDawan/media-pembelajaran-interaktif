<?php
session_start();
// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../../includes/db.php';

// Ambil ID materi dari parameter URL
if (isset($_GET['id'])) {
    $id_materi = $_GET['id'];

    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Hapus data audiobook terkait (jika ada)
        $sql_delete_audiobook = "DELETE FROM audiobook WHERE id_materi = :id_materi";
        $stmt_delete_audiobook = $pdo->prepare($sql_delete_audiobook);
        $stmt_delete_audiobook->execute([':id_materi' => $id_materi]);

        // Hapus data materi
        $sql_delete_materi = "DELETE FROM materi WHERE id_materi = :id_materi";
        $stmt_delete_materi = $pdo->prepare($sql_delete_materi);
        $stmt_delete_materi->execute([':id_materi' => $id_materi]);

        // Commit transaksi
        $pdo->commit();

        // Redirect ke halaman list materi dengan pesan sukses
        header("Location: list.php?status=deleted");
        exit;
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $pdo->rollBack();

        // Redirect ke halaman list materi dengan pesan error
        header("Location: list.php?status=error");
        exit;
    }
} else {
    // Jika tidak ada ID, redirect ke halaman list materi
    header("Location: list.php");
    exit;
}
?>