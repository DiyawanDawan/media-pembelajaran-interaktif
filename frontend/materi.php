<?php 
require_once '../includes/db.php';  // Ambil semua data materi dari database

try {
    $stmt = $pdo->query("SELECT * FROM Materi ORDER BY id_materi DESC"); // Urutkan berdasarkan id_materi
    $materi = $stmt->fetchAll();
} catch (PDOException $e) {
    // Jika ada error dalam query
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<?php include_once('components/header.php') ?>
<?php include_once('components/nav.php') ?>   

    <main class="content">
        <!-- Daftar Materi -->
        <h1>Daftar Materi Pembelajaran</h1>
    <div class="materi-list">
        <?php if (count($materi) > 0): ?>
            <?php foreach ($materi as $m): ?>
                <div class="materi-item">
                    <h2><?= htmlspecialchars($m['judul_materi']) ?></h2>
                    <p><?= htmlspecialchars($m['deskripsi']) ?></p>
                    <a href="detail_materi.php?id=<?= $m['id_materi'] ?>">Baca Selengkapnya</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Tidak ada materi yang tersedia.</p>
        <?php endif; ?>
    </div>
    </main>
    <footer>
        <p>&copy; 2023 Media Pembelajaran. All rights reserved.</p>
    </footer>
    <script src="script/script.js"></script>
</body>
</html>
