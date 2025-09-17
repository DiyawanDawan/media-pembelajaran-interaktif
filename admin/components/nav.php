   <!-- Sidebar -->
   <div class="sidebar" id="sidebar">
        <!-- Tombol toggle di dalam sidebar -->
        <!-- Konten sidebar -->
       
        <h2 class="sidebar__title">

     <!-- <button  class="theme-toggle"  id="toggle-theme">Toggle Dark Mode</button> -->
        <button id="toggle-theme" class="theme-toggle" onclick="toggleTheme()">Toggle Dark Mode</button>

            <div> <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i> <!-- Ikon hamburger -->
                </button></div>
        </h2>
        <ul class="sidebar__list">
        <li class="sidebar__item">
                <a href="../materi/list.php" class="sidebar__link">
                <i class="bi bi-book"></i>
                    <span class="sidebar__text">List Materi</span>
                </a>
            </li>
            <li class="sidebar__item">
                <a href="../game/list.php" class="sidebar__link">
                <i class="bi bi-controller"></i>
                    <span class="sidebar__text">List Game </span>
                </a>
            </li>
           
            <li class="sidebar__item">
                <a href="../kuis/list_kuis.php" class="sidebar__link">
                <i class="bi bi-list-check"></i>
                    <span class="sidebar__text">Kuis</span>
                </a>
            </li>
           
            <li class="sidebar__item">
                <a href="../seting/list_cp_tp.php" class="sidebar__link">
                <i class="bi bi-journal-bookmark"></i>
                    <span class="sidebar__text">CP & TP</span>
                </a>
            </li>
            <li class="sidebar__item">
                    <a href="../seting/list_jenjang.php" class="sidebar__link">
                        <i class="bi bi-gear"></i>
                        <span class="sidebar__text">Setting</span>
                    </a>
                </li>
            <li class="nav-item sidebar__item">
                <a href="../logout.php" class="nav-link"><i class="bi bi-box-arrow-right"></i><span  class="sidebar__text">Logout</span>
            </a></li>
        </ul>
    </div>
