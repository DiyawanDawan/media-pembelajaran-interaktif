<!-- TODO frontend/index.php (Halaman Utama untuk Siswa)-->

<?php
require_once '../includes/db.php';

// Ambil data materi dari database
$stmt = $pdo->query("SELECT * FROM Materi");
$materi = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Media Pembelajaran</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <h1>Daftar Materi</h1>
    <ul>
        <?php foreach ($materi as $m): ?>
            <li>
                <a href="materi.php?id=<?= $m['id_materi'] ?>">
                    <?= htmlspecialchars($m['judul_materi']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>