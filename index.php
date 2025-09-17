<?php
session_start();
require 'includes/db.php';

$stmt_guru = $pdo->query("SELECT * FROM guru");
$gurus = $stmt_guru->fetchAll();

$stmt_jenjang = $pdo->query("SELECT * FROM jenjang_pendidikan");
$pendidikan = $stmt_jenjang->fetchAll();
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Pembelajaran</title>
<!--   <link rel="preconnect" href="https://fonts.googleapis.com">-->
<!--<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>-->
<!--<link href="https://fonts.googleapis.com/css2?family=Patrick+Hand+SC&display=swap" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <!-- Judul navbar -->

        <div class="navbar__title">

            <a href="" class="navbar__link">
                <img width="50" height="50" src="https://img.icons8.com/3d-fluency/50/books.png" alt="books" />
                <span>AudioBooks</span>
            </a>

        </div>

        <!-- Daftar menu navbar -->
        <ul class="navbar__list" id="navbarList">
            <!-- Menu Home -->
            <li class="navbar__item">
                <a href="/" class="navbar__link">
                    <!-- <i class="bi bi-house-door"></i> Ikon rumah untuk Home -->
                    <img width="50" height="50" src="https://img.icons8.com/3d-fluency/50/home.png" alt="home" />
                    <span>Home</span>
                </a>
            </li>
             <li class="navbar__item">
                <a href="frontend/cp_tp/cp_tp_view.php" class="navbar__link">
                    <!-- <i class="bi bi-clipboard2"></i> -->
                    <img width="50" height="50" src="https://img.icons8.com/3d-fluency/50/hand-with-pen--v3.png" alt="hand-with-pen--v3" />
                    <span>CP & TP</span>
                </a>
            </li>
            <li class="navbar__item">
                <a href="frontend/materi/materi_view.php" class="navbar__link">
                    <!-- <i class="bi bi-book-half"></i> -->
                    <img width="50" height="50" src="https://img.icons8.com/3d-fluency/50/knowledge-sharing.png" alt="knowledge-sharing" />
                    <span>Materi</span>
                </a>
            </li>
           
            <li class="navbar__item">
                <a href="frontend/kuis/kuis_view.php" class="navbar__link">
                    <!-- <i class="bi bi-pencil-square"></i> -->
                    <img width="50" height="50" src="https://img.icons8.com/3d-fluency/50/test-passed.png" alt="test-passed" />
                    <span>Latihan Siswa</span>
                </a>
            </li>
            <li class="navbar__item">
                <a href="frontend/game/game_view.php" class="navbar__link">
                    <!-- <i class="bi bi-controller"></i> -->
                    <img width="50" height="50" src="https://img.icons8.com/3d-fluency/50/controller.png" alt="controller" />
                    <span>Game</span>
                </a>
            </li>
            <!--<li class="navbar__item">-->
            <!--    <a href="frontend/materi/biodata.php" class="navbar__link">-->
                    <!-- <i class="bi bi-person-circle"></i> -->
            <!--        <img width="50" height="50" src="https://img.icons8.com/3d-fluency/50/guest-male--v2.png" alt="guest-male--v2" />-->
            <!--        <span>Biodata</span>-->
            <!--    </a>-->
            <!--</li>-->
        </ul>

        <!-- Tombol toggle theme -->
        <!-- <button  onclick="toggleTheme()">Dark Mode</button> -->
        <!-- <button  class="theme-toggle"  id="toggle-theme">Toggle Dark Mode</button> -->
        <!--<button id="toggle-theme" class="theme-toggle" onclick="toggleTheme()">Toggle Dark Mode</button>-->

        <!-- Tombol toggle menu untuk layar kecil -->
        <button class="navbar-toggle"  id="menuToggle">
            <i class="bi bi-list"></i>
        </button>
    </nav>

    <div id="overlay" onclick="toggleSidebar()"></div>
    <main class="main-contents">
        <div class="container">
            <section class="hero">
                <div class="hero__content">
                    <h1>Selamat Datang di Media Pembelajaran Interaktif!</h1>
                    <p>Belajar jadi lebih mudah dan menyenangkan dengan materi lengkap dan interaktif.</p>
                    <a href="frontend/materi/materi_view.php" class="btn mulai-belajar">Mulai Belajar</a>
                </div>
                <!--<div class="hero__image">-->
                <!--    <img src="assets/fikri.png" alt="Belajar Interaktif">-->
                <!--</div>-->
            </section>



   <div class="containerds">

<?php foreach ($gurus as $guru): ?>
    <?php
    // Get S1 education data for this teacher
    $s1_data = null;
    foreach ($pendidikan as $p) {
        if ($p['id_guru'] == $guru['id_guru'] && $p['jenjang'] == 'S1') {
            $s1_data = $p;
            break;
        }
    }
    ?>
    <div class="biodata-card">
   <h1 class="biodata-title">Biodata Guru</h1>
        <div class="biodata-header">
            <img src="../../assets/uploads/gambar/<?= htmlspecialchars($guru['image'] ?: 'default-profile.png') ?>" alt="Profile Image" class="biodata-image">
            <div class="biodata-info">
                <!-- Fixed: Show teacher name here -->
                <h2 class="biodata-name">Nama : <?= htmlspecialchars($guru['nama_guru']) ?></h2>
                <p class="biodata-email"><strong>Email:</strong> <?= htmlspecialchars($guru['email_guru']) ?></p>
            </div>
        </div>

        <div class="biodata-section">
            <h3 class="biodata-subtitle">Jenjang Pendidikan</h3>
          <table class="biodata-table">
            <thead>
                <tr class="biodata-table-header">
                    <th class="biodata-table-th">Jenjang</th>
                    <th class="biodata-table-th">Institusi</th>
                    <th class="biodata-table-th">Jurusan</th>
                    <th class="biodata-table-th">Tahun Lulus</th>
                    <th class="biodata-table-th">NIM</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendidikan as $p): ?>
                    <?php if ($p['id_guru'] == $guru['id_guru']): ?>
                        <tr class="biodata-table-row">
                            <td class="biodata-table-td" data-label="Jenjang"><?= htmlspecialchars($p['jenjang']) ?></td>
                            <td class="biodata-table-td" data-label="Institusi"><?= htmlspecialchars($p['institusi']) ?></td>
                            <td class="biodata-table-td" data-label="Jurusan"><?= htmlspecialchars($p['jurusan']) ?></td>
                            <td class="biodata-table-td" data-label="Tahun Lulus"><?= htmlspecialchars($p['tahun_lulus']) ?></td>
                            <td class="biodata-table-td" data-label="NIM"><?= htmlspecialchars($p['nim'] ?: '-') ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <div class="biodata-footer">
            <!-- Show university logo and name from S1 data -->
            <?php if ($s1_data): ?>
                <img src="../../assets/uploads/gambar/<?= htmlspecialchars($guru['logo_universitas'] ?: 'default-university.png') ?>" alt="University Logo" class="biodata-university-logo">
                <span class="biodata-university"><?= htmlspecialchars($s1_data['institusi']) ?></span>
            <?php else: ?>
                <img src="../../assets/uploads/gambar/default-university.png" alt="University Logo" class="biodata-university-logo">
                <span class="biodata-university">Data S1 Tidak Ditemukan</span>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
   </div>







            <!-- Fitur Unggulan -->
            <!--<section class="features">-->
            <!--    <h2 class="features__title">Fitur Unggulan</h2>-->
            <!--    <div class="features__list">-->
            <!--        <div class="feature__item">-->
            <!--            <a href="/frontend/materi/materi_view.php" class="feature__icon-link">-->
            <!--                <img width="100" height="100" src="https://img.icons8.com/3d-fluency/100/books.png" alt="books" />-->

            <!--            </a>-->
            <!--            <h3 class="feature__title">Audio Book</h3>-->
            <!--            <p class="feature__description">Akses materi pembelajaran yang terstruktur dan mudah dipahami.</p>-->
            <!--        </div>-->
            <!--        <div class="feature__item">-->
            <!--            <a href="/frontend/kuis/kuis_view.php" class="feature__icon-link">-->
            <!--                <img width="100" height="100" src="https://img.icons8.com/3d-fluency/100/test-passed.png" alt="test-passed" />-->
            <!--            </a>-->
            <!--            <h3 class="feature__title">Kuis Interaktif</h3>-->
            <!--            <p class="feature__description">Uji pemahaman Anda dengan kuis yang menarik.</p>-->
            <!--        </div>-->
            <!--        <div class="feature__item">-->
            <!--            <a href="/game/game_view.php" class="feature__icon-link">-->
            <!--                <img width="100" height="100" src="https://img.icons8.com/3d-fluency/100/controller.png" alt="controller" />-->
            <!--            </a>-->
            <!--            <h3 class="feature__title">Game Interaktif</h3>-->
            <!--            <p class="feature__description">Uji pemahaman Anda dengan game yang seru.</p>-->
            <!--        </div>-->
            <!--        <div class="feature__item">-->
            <!--            <a href="/frontend/cp_tp/cp_tp_view.php" class="feature__icon-link">-->
            <!--                <img width="100" height="100" src="https://img.icons8.com/3d-fluency/100/hand-with-pen--v3.png" alt="hand-with-pen--v3" />-->

            <!--            </a>-->
            <!--            <h3 class="feature__title">Capaian Pembelajaran</h3>-->
            <!--            <p class="feature__description">Belajar melalui video yang mendalam dan jelas.</p>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</section>-->


            <!-- Call to Action -->
           <!--<section class="cta">-->
           <!--     <h2 class="cta__title">Ayo Mulai Belajar Sekarang!</h2>-->
           <!--     <p class="cta__description">Belajar dan nikmati semua fitur yang tersedia.</p>-->
           <!--     <a href="frontend/materi/materi_view.php" class="cta__button btn">-->
           <!--         Belajar Sekarang-->
           <!--         <i class="fas fa-arrow-right"></i>-->
           <!--     </a>-->
           <!-- </section>-->
        </div>
    </main>

   
    <!--<script>-->
    <!--    let currentIndex = 0;-->

    <!--    function moveCarousel(direction) {-->
    <!--        const container = document.querySelector('.carousel-container');-->
    <!--        const items = document.querySelectorAll('.materi-item');-->
            <!--const itemWidth = items[0].offsetWidth + 20; // Lebar satu item + margin-->

    <!--        currentIndex += direction;-->

            <!--// Batasi currentIndex agar tidak melebihi jumlah item-->
    <!--        if (currentIndex < 0) {-->
    <!--            currentIndex = 0;-->
    <!--        } else if (currentIndex >= items.length) {-->
    <!--            currentIndex = items.length - 1;-->
    <!--        }-->

            <!--// Geser carousel-->
    <!--        const offset = -currentIndex * itemWidth;-->
    <!--        container.style.transform = `translateX(${offset}px)`;-->
    <!--    }-->
    <!--</script>-->

    <script src="frontend/script/script.js"></script>