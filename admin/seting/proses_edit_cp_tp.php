<?php
session_start();

// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Include koneksi database
require '../../includes/db.php';

// Ambil data dari form
$id_pembelajaran = $_POST['id_pembelajaran'];
$id_materi = $_POST['id_materi'];
$poin_tujuan = $_POST['poin_tujuan'];
$poin_capaian = $_POST['poin_capaian'];

// Query untuk memperbarui data CP/TP
$sql = "UPDATE pembelajaran 
        SET id_materi = :id_materi, 
            poin_tujuan = :poin_tujuan, 
            poin_capaian = :poin_capaian 
        WHERE id_pembelajaran = :id_pembelajaran";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_materi' => $id_materi,
    ':poin_tujuan' => $poin_tujuan,
    ':poin_capaian' => $poin_capaian,
    ':id_pembelajaran' => $id_pembelajaran,
]);

// Redirect ke halaman list CP/TP dengan pesan sukses
header("Location: list_cp_tp.php?status=success");
exit;
?>