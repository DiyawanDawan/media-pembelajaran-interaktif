<?php
session_start();
require '../../includes/db.php';
// require_once '../includes/db.php';

function fetchMateri($pdo) {
    try {
        $stmt_materi = $pdo->query("SELECT * FROM materi");
        return $stmt_materi->fetch(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        die("Error fetching materi: " . $e->getMessage());
    }
}

function fetchKuis($pdo) {
    try {
        $stmt_kuis = $pdo->query("SELECT * FROM Kuis");
        return $stmt_kuis->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        die("Error fetching kuis: " . $e->getMessage());
    }
}

function processSubmission($kuis, &$jawaban_siswa) {
    $score = 0;
    foreach ($kuis as $index => $q) {
        if (isset($jawaban_siswa[$index]) && $jawaban_siswa[$index] === $q['jawaban_benar']) {
            $score++;
        }
    }
    return $score;
}

$materi = fetchMateri($pdo);
$kuis = fetchKuis($pdo);
$feedback = '';
$current_question = 0;

if (!isset($_SESSION['jawaban_siswa'])) {
    $_SESSION['jawaban_siswa'] = [];
}
$jawaban_siswa = &$_SESSION['jawaban_siswa'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['jawaban'])) {
        foreach ($_POST['jawaban'] as $index => $jawaban) {
            $jawaban_siswa[$index] = $jawaban;
        }
    }

    if (isset($_POST['current_question'])) {
        $current_question = (int)$_POST['current_question'];
    }

    if (isset($_POST['next'])) {
        $current_question = min($current_question + 1, count($kuis) - 1);
    } elseif (isset($_POST['prev'])) {
        $current_question = max($current_question - 1, 0);
    } elseif (isset($_POST['submit'])) {
        $score = processSubmission($kuis, $jawaban_siswa);
        $total_soal = count($kuis);
        $feedback = "Skor Anda: $score dari $total_soal soal.";
        unset($_SESSION['jawaban_siswa']);
    }
}

// Inisialisasi variabel dengan nilai default
$materi = $materi ?? ['judul_materi' => 'Judul Materi Default'];
$kuis = $kuis ?? [];
$feedback = $feedback ?? '';
$current_question = $current_question ?? 0;
$jawaban_siswa = $jawaban_siswa ?? [];

include 'kuis_view.php';
?>