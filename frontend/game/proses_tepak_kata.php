<?php
session_start();
require_once '../../includes/db.php';

// Set header agar selalu mengembalikan JSON
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ambil data organ
$query = "SELECT * FROM organ_data";
$stmt = $pdo->query($query);
$organ_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cek apakah data tersedia
if (!$organ_data) {
    echo json_encode(["error" => "Data organ tidak tersedia"]);
    exit;
}

// Inisialisasi session jika belum ada
if (!isset($_SESSION['poin'])) {
    $_SESSION['poin'] = 0;
}
if (!isset($_SESSION['current_index'])) {
    $_SESSION['current_index'] = 0;
}

$current_index = $_SESSION['current_index'];

// Pastikan indeks tidak di luar batas array
if (!isset($organ_data[$current_index])) {
    echo json_encode(["error" => "Indeks tidak valid"]);
    exit;
}

$organ = $organ_data[$current_index];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $tebakan = strtolower(trim($_POST['tebakan'] ?? ''));

    // Pastikan ID valid
    if ($id == $organ['id']) {
        $jawaban_benar = strtolower(trim($organ['correct_word']));

        if ($tebakan === $jawaban_benar) {
            $_SESSION['poin'] += 50;
            $status = "benar";
        } else {
            $_SESSION['poin'] -= 25;
            $status = "salah";
        }

        // Pindah ke soal berikutnya
        $_SESSION['current_index'] = ($_SESSION['current_index'] + 1) % count($organ_data);
        $next_organ = $organ_data[$_SESSION['current_index']];

        echo json_encode([
            "status" => $status,
            "poin" => $_SESSION['poin'],
            "scrambled_word" => str_split($next_organ['scrambled_word']),
            "image_path" => "../../assets/uploads/gambar/" . $next_organ['image'],
            "description" => $next_organ['description']
        ]);
    } else {
        echo json_encode(["error" => "ID tidak cocok"]);
    }
} else {
    echo json_encode(["error" => "Metode request tidak valid"]);
}
?>
