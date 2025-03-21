<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Tombol toggle di dalam sidebar -->
    <!-- Konten sidebar -->
    <button class="theme-toggle" onclick="toggleTheme()">Dark Mode</button>
    <h2 class="sidebar__title">
        <div><span class="sidebar__title-text">Pembelajaran</span> <!-- Teks judul --></div>
    <div>    <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i> <!-- Ikon hamburger -->
        </button></div>
    </h2>
    <ul class="sidebar__list">
        <li class="sidebar__item">
            <a href="../materi/materi_view.php" class="sidebar__link">
                <i class="bi bi-book-half"></i>
                <span class="sidebar__text">Materi </span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="../cp_tp/cp_tp_view.php" class="sidebar__link">
                <i class="bi bi-clipboard2"></i>
                <span class="sidebar__text">CP & TP</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="../kuis/kuis_view.php" class="sidebar__link">
                <i class="bi bi-pencil-square"></i>
                <span class="sidebar__text">Kuis</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="../game/game_view.php" class="sidebar__link">
                <i class="bi bi-controller"></i>
                <span class="sidebar__text">Game</span>
            </a>
        </li>
        <li class="sidebar__item">
            <a href="../materi/biodata.php" class="sidebar__link">
                <i class="bi bi-person-circle"></i>
                <span class="sidebar__text">Biodata</span>
            </a>
        </li>
    </ul>
</div>

<div id="overlay" onclick="toggleSidebar()"></div>