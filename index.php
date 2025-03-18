<?php
require_once 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Media Pembelajaran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="sidebar" id="sidebar">
    <!-- Tombol toggle di dalam sidebar -->
    <!-- Konten sidebar -->
    <button class="theme-toggle" onclick="toggleTheme()">Toggle Dark Mode</button>
    <h2 class="sidebar__title">
        <div><span class="sidebar__title-text">Pembelajaran</span> <!-- Teks judul --></div>
    <div>    <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i> <!-- Ikon hamburger -->
        </button></div>
    </h2>
    <ul class="sidebar__list">
        <li class="sidebar__item">
            <a href="frontend/materi/materi_view.php" class="sidebar__link">
                <i class="bi bi-book-half"></i>
                <span class="sidebar__text">Materi </span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="frontend/cp_tp/cp_tp_view.php" class="sidebar__link">
                <i class="bi bi-clipboard2"></i>
                <span class="sidebar__text">CP & TP</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="frontend/kuis/kuis_view.php" class="sidebar__link">
                <i class="bi bi-pencil-square"></i>
                <span class="sidebar__text">Kuis</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="frontend/game/game_view.php" class="sidebar__link">
                <i class="bi bi-controller"></i>
                <span class="sidebar__text">Game</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="frontend/materi/biodata.php" class="sidebar__link">
                <i class="bi bi-person-circle"></i>
                <span class="sidebar__text">Biodata</span>
            </a>
        </li>
    </ul>
</div>

<div id="overlay" onclick="toggleSidebar()"></div>

<div class="content">
    <h1 class="content-title">Selamat Datang di Media Pembelajaran</h1>
    <p class="content-text">Silakan pilih menu di samping untuk mengakses materi.</p>
</div>
</script>

<script src="frontend/script/script.js"></script>
</body>
</html>

