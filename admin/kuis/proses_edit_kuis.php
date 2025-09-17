<?php
session_start();
// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../../includes/db.php';

// Ambil data dari form
$id_kuis = $_POST['id_kuis'];
$pertanyaan = $_POST['pertanyaan'];
$pilihan_a = $_POST['pilihan_a'];
$pilihan_b = $_POST['pilihan_b'];
$pilihan_c = $_POST['pilihan_c'];
$pilihan_d = $_POST['pilihan_d'];
$jawaban_benar = $_POST['jawaban_benar'];
$id_materi = $_POST['id_materi'];

// Query untuk memperbarui data kuis
$sql = "UPDATE kuis 
        SET pertanyaan = :pertanyaan, 
            pilihan_a = :pilihan_a, 
            pilihan_b = :pilihan_b, 
            pilihan_c = :pilihan_c, 
            pilihan_d = :pilihan_d, 
            jawaban_benar = :jawaban_benar, 
            id_materi = :id_materi 
        WHERE id_kuis = :id_kuis";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':pertanyaan' => $pertanyaan,
    ':pilihan_a' => $pilihan_a,
    ':pilihan_b' => $pilihan_b,
    ':pilihan_c' => $pilihan_c,
    ':pilihan_d' => $pilihan_d,
    ':jawaban_benar' => $jawaban_benar,
    ':id_materi' => $id_materi ?: null, // Jika id_materi kosong, set null
    ':id_kuis' => $id_kuis,
]);

// Redirect ke halaman list kuis dengan pesan sukses
header("Location: list_kuis.php?status=success");
exit;
?>