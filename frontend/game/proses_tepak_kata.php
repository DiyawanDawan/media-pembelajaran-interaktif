<?php
session_start();
require_once '../../includes/db.php'; // Pastikan file koneksi sudah benar

// Inisialisasi session untuk menyimpan poin
if (!isset($_SESSION['poin'])) {
    $_SESSION['poin'] = 0; // Poin awal
}

// Ambil data organ dari database
$query = "SELECT * FROM organ_data";
$stmt = $pdo->query($query);
$organ_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi session untuk menyimpan indeks soal
if (!isset($_SESSION['current_index'])) {
    $_SESSION['current_index'] = 0;
}

// Jika form dikirim (tebakan dikirim)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $tebakan = strtolower(trim($_POST['tebakan'])); // Normalisasi tebakan

    // Cari data organ berdasarkan ID
    $organ = null;
    foreach ($organ_data as $data) {
        if ($data['id'] == $id) {
            $organ = $data;
            break;
        }
    }

    if ($organ) {
        $jawaban_benar = strtolower(trim($organ['correct_word'])); // Normalisasi jawaban benar
        if ($tebakan === $jawaban_benar) {
            $pesan = "Selamat! Jawaban Anda benar. (+50 poin)";
            $_SESSION['poin'] += 50; // Tambah 50 poin
        } else {
            $pesan = "Maaf, jawaban Anda salah. Jawaban yang benar adalah: " . htmlspecialchars($organ['correct_word']) . " (-25 poin)";
            $_SESSION['poin'] -= 25; // Kurangi 25 poin
        }

        // Pindah ke soal berikutnya
        $_SESSION['current_index'] = ($_SESSION['current_index'] + 1) % count($organ_data);
    } else {
        $pesan = "Data organ tidak ditemukan.";
    }
}

// Ambil organ yang sedang ditampilkan
$current_index = $_SESSION['current_index'];
$organ = $organ_data[$current_index];
$image_path = "../../assets/uploads/gambar/" . $organ['image']; // Sesuaikan path gambar
$scrambled_word = $organ['scrambled_word'];
$scrambled_letters = str_split($scrambled_word); // Pisahkan kata acak menjadi array huruf

// Include file HTML
include 'tebak_kata_view.php';
?>