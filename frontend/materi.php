<?php
require_once '../includes/db.php';

$id_materi = $_GET['id'] ?? null;
if (!$id_materi) {
    header('Location: index.php');
    exit;
}

// Ambil data materi
$stmt = $pdo->prepare("SELECT * FROM Materi WHERE id_materi = ?");
$stmt->execute([$id_materi]);
$materi = $stmt->fetch();

if (!$materi) {
    echo "Materi tidak ditemukan!";
    exit;
}

// Ambil data audio book terkait materi
$stmt_audio = $pdo->prepare("SELECT * FROM AudioBook WHERE id_materi = ?");
$stmt_audio->execute([$id_materi]);
$audio_books = $stmt_audio->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($materi['judul_materi']) ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <h1><?= htmlspecialchars($materi['judul_materi']) ?></h1>
    <p><?= htmlspecialchars($materi['deskripsi']) ?></p>

    <!-- Tampilkan Audio Book -->
    <h2>Audio Book</h2>
    <?php if (count($audio_books) > 0): ?>
        <ul>
            <?php foreach ($audio_books as $audio): ?>
                <li>
                    <audio controls>
                        <source src="/assets/audio/<?= htmlspecialchars($audio['file_audio']) ?>" type="audio/mpeg">
                        Browser Anda tidak mendukung pemutaran audio.
                    </audio>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Tidak ada audio book untuk materi ini.</p>
    <?php endif; ?>

    <!-- Navigasi -->
    <a href="kuis.php?id=<?= $materi['id_materi'] ?>">Kerjakan Kuis</a> |
    <a href="game.php?id=<?= $materi['id_materi'] ?>">Mainkan Game</a>
</body>
</html>