<?php
session_start();
require_once '../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_guru = $_POST['id_guru'];
    $jenjang = $_POST['jenjang'];
    $institusi = $_POST['institusi'];
    $jurusan = $_POST['jurusan'];
    $tahun_lulus = $_POST['tahun_lulus'];
    $nim = $_POST['nim'];

    // Validasi data (nim hanya wajib jika jenjangnya S1 atau lebih tinggi)
    if (empty($id_guru) || empty($jenjang) || empty($institusi) || empty($jurusan) || empty($tahun_lulus) || ($jenjang == "S1" && empty($nim))) {
        header("Location: tambah_jenjang_pendidikan.php?status=error");
        exit;
    }

    // Insert ke database
    $sql = "INSERT INTO jenjang_pendidikan (id_guru, jenjang, institusi, jurusan, tahun_lulus, nim) 
            VALUES (:id_guru, :jenjang, :institusi, :jurusan, :tahun_lulus, :nim)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_guru', $id_guru);
    $stmt->bindParam(':jenjang', $jenjang);
    $stmt->bindParam(':institusi', $institusi);
    $stmt->bindParam(':jurusan', $jurusan);
    $stmt->bindParam(':tahun_lulus', $tahun_lulus);
    $stmt->bindParam(':nim', $nim);

    if ($stmt->execute()) {
        header("Location: list_jenjang.php?status=success");
        exit;
    } else {
        header("Location: tambah_jenjang_pendidikan.php?status=error");
        exit;
    }
} else {
    header("Location: tambah_jenjang_pendidikan.php");
    exit;
}
