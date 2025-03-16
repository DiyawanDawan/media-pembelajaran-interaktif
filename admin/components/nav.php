   <!-- Sidebar -->
   <div class="sidebar" id="sidebar">
        <!-- Tombol toggle di dalam sidebar -->
        <!-- Konten sidebar -->
        <button class="theme-toggle" onclick="toggleTheme()">Toggle Dark Mode</button>
        <h2 class="sidebar__title">

            <div> <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i> <!-- Ikon hamburger -->
                </button></div>
        </h2>
        <ul class="sidebar__list">
        <li class="sidebar__item">
                <a href="../materi/list.php" class="sidebar__link">
                    <i class="bi bi-clipboard2"></i>
                    <span class="sidebar__text">List Materi</span>
                </a>
            </li>
            <li class="sidebar__item">
                <a href="../game/list.php" class="sidebar__link">
                    <i class="bi bi-book-half"></i>
                    <span class="sidebar__text">List Game </span>
                </a>
            </li>
           
            <li class="sidebar__item">
                <a href="../kuis/list_kuis.php" class="sidebar__link">
                    <i class="bi bi-pencil-square"></i>
                    <span class="sidebar__text">Kuis</span>
                </a>
            </li>
           
            <li class="sidebar__item">
                <a href="../seting/list_cp_tp.php" class="sidebar__link">
                    <i class="bi bi-person-circle"></i>
                    <span class="sidebar__text">CP & TP</span>
                </a>
            </li>
            <li class="nav-item"><a href="../logout.php" class="nav-link">Logout</a></li>
        </ul>
    </div>
