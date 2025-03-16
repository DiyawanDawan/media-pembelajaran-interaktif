<?php
require_once '../includes/db.php';

$id_materi = $_GET['id'] ?? null;
if (!$id_materi) {
    header('Location: index.php');
    exit;
}

// Ambil data materi
$stmt_materi = $pdo->prepare("SELECT * FROM Materi WHERE id_materi = ?");
$stmt_materi->execute([$id_materi]);
$materi = $stmt_materi->fetch();

if (!$materi) {
    echo "Materi tidak ditemukan!";
    exit;
}

// Ambil data game terkait materi
$stmt_game = $pdo->prepare("SELECT * FROM Game WHERE id_materi = ?");
$stmt_game->execute([$id_materi]);
$game = $stmt_game->fetch();

if (!$game) {
    echo "Game tidak ditemukan untuk materi ini!";
    exit;
}

// Proses jawaban game
$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jawaban_siswa = $_POST['jawaban'] ?? '';
    if ($jawaban_siswa === $game['jawaban_benar']) {
        $feedback = "Selamat! Jawaban Anda benar. 🎉";
    } else {
        $feedback = "Maaf, jawaban Anda salah. Jawaban yang benar adalah: " . htmlspecialchars($game['jawaban_benar']);
    }
}
?>

<?php include_once('components/header.php') ?>
<?php include_once('components/nav.php') ?>
    <main class="content">
    <h1>Game: <?= htmlspecialchars($materi['judul_materi']) ?></h1>
    <?php if ($feedback): ?>
        <p style="color: <?= strpos($feedback, 'Selamat') !== false ? 'green' : 'red' ?>;"><?= $feedback ?></p>
    <?php endif; ?>

    <div class="game">
        <p><strong>Deskripsi Game:</strong> <?= htmlspecialchars($game['deskripsi_game']) ?></p>
        <img src="/assets/images/<?= htmlspecialchars($game['gambar_game']) ?>" alt="Gambar Game" width="300">
        <form method="POST">
            <label for="jawaban">Tebak jawaban:</label>
            <input type="text" id="jawaban" name="jawaban" required>
            <button type="submit">Submit</button>
        </form>
    </div>
    </main>
<!-- Navigasi -->
<a href="materi.php?id=<?= $materi['id_materi'] ?>">Kembali ke Materi</a> |
<a href="cp_tp.php?id=<?= $materi['id_materi'] ?>">Lihat Evaluasi (CP & TP)</a> |
<a href="evaluasi.php?id=<?= $materi['id_materi'] ?>">Kerjakan Kuis</a>
<script src="script/script.js"></script>
</body>
</html>