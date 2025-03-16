<?php
session_start();

// Jika admin belum login, redirect ke halaman login
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
// header("Location: index.php");
// exit;
// }

// Include koneksi database
require '../../includes/db.php';

// Ambil data materi dan audiobook dari database
$sql_materi = "SELECT MATERI.*, AUDIOBOOK.file_audio 
               FROM MATERI 
               LEFT JOIN AUDIOBOOK ON MATERI.id_materi = AUDIOBOOK.id_materi
               WHERE MATERI.status = 'published'";
$stmt_materi = $pdo->query($sql_materi);
$materi = $stmt_materi->fetchAll(PDO::FETCH_ASSOC);


?>
<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <h1 class="content-title">Daftar Materi dan Audiobook</h1>

    <div class="carousel">
        <button class="carousel-button prev" onclick="moveCarousel(-1)">&#10094;</button>
        <div class="carousel-container">
    <?php foreach ($materi as $m): ?>
        <div class="materi-item"> <!-- Satu materi per slide -->
            <!-- Header Card -->
            <div class="materi-item-header">
                <h3><?= htmlspecialchars($m['judul_materi']) ?></h3>
            </div>

            <!-- Body Card -->
            <div class="materi-item-body">
                <!-- Gambar di Kiri -->
                <div class="materi-item-left">
                    <?php if ($m['gambar']): ?>
                        <div class="materi-item-image">
                            <a href="../../assets/uploads/gambar/<?= htmlspecialchars(basename($m['gambar'])) ?>" target="_blank">
                                <img src="../../assets/uploads/gambar/<?= htmlspecialchars(basename($m['gambar'])) ?>" alt="<?= htmlspecialchars($m['judul_materi']) ?>" class="materi-item-img">
                            </a>
                        </div>
                    <?php else: ?>
                        <span class="no-image">Tidak ada gambar</span>
                    <?php endif; ?>
                </div>

                <!-- Audio dan Deskripsi di Kanan -->
                <div class="materi-item-right">
                    <?php if ($m['file_audio']): ?>
                        <div class="materi-item-audio">
                            <audio controls>
                                <source src="../../assets/uploads/audio/<?= htmlspecialchars(basename($m['file_audio'])) ?>" type="audio/mpeg">
                                Browser Anda tidak mendukung tag audio.
                            </audio>
                        </div>
                    <?php else: ?>
                        <span class="no-audio">Tidak ada audiobook</span>
                    <?php endif; ?>
                    <div class="materi-item-description">
                        <p><?= htmlspecialchars($m['deskripsi']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Footer Card -->
            <div class="materi-item-footer">
                <span class="tanggal"><?= htmlspecialchars($m['tanggal_upload']) ?></span> <!-- Tanggal -->
            </div>
        </div>
    <?php endforeach; ?>
</div>
          <button class="carousel-button next" onclick="moveCarousel(1)">&#10095;</button>
    </div>
</main>

<script>
    let currentIndex = 0;

    function moveCarousel(direction) {
        const container = document.querySelector('.carousel-container');
        const items = document.querySelectorAll('.materi-item');
        const itemWidth = items[0].offsetWidth + 20; // Lebar satu item + margin

        currentIndex += direction;

        // Batasi currentIndex agar tidak melebihi jumlah item
        if (currentIndex < 0) {
            currentIndex = 0;
        } else if (currentIndex >= items.length) {
            currentIndex = items.length - 1;
        }

        // Geser carousel
        const offset = -currentIndex * itemWidth;
        container.style.transform = `translateX(${offset}px)`;
    }
</script>

<?php include_once('../components/footer.php') ?>