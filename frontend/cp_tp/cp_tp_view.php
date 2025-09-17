<?php
require_once '../../includes/db.php';

// Mengambil data dari tabel pembelajaran
// $sql = "SELECT * FROM pembelajaran";
$sql = "SELECT m.judul_materi, p.poin_tujuan, p.poin_capaian 
        FROM pembelajaran p
        JOIN materi m ON p.id_materi = m.id_materi";

$stmt = $pdo->query($sql);
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="main-contents">
    <div class="container">
       <div class="learning-goal">
    <h1 class="learning-title">Tujuan & Capaian Pembelajaran</h1>
    <div class="table-container">
        <?php if ($stmt->rowCount() > 0): ?>
            <table class="learning-table">
                <thead>
                    <tr class="table-header">
                        <th class="header-materi">Materi</th>
                        <th class="header-tujuan">Tujuan Pembelajaran</th>
                        <th class="header-capaian">Capaian Pembelajaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr class="table-row">
                            <td class="cell-materi" data-label="Materi"><?= htmlspecialchars($row['judul_materi']) ?></td>
                            <td class="cell-tujuan" data-label="Tujuan"><?= nl2br(htmlspecialchars($row['poin_tujuan'])) ?></td>
                            <td class="cell-capaian" data-label="Capaian"><?= nl2br(htmlspecialchars($row['poin_capaian'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data-message">Tidak ada pembelajaran.</p>
        <?php endif; ?>
    </div>
</div>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    // Toggle Tema
    function toggleTheme() {
        const root = document.documentElement;
        const currentTheme = root.getAttribute("data-theme") || "light";
        const newTheme = currentTheme === "dark" ? "light" : "dark";

        root.setAttribute("data-theme", newTheme);
        localStorage.setItem("theme", newTheme);
        updateButtonText(newTheme);
    }

    function updateButtonText(theme) {
        const toggleThemeBtn = document.getElementById("toggle-theme");
        if (toggleThemeBtn) {
            toggleThemeBtn.innerHTML = theme === "dark" ? "☀️ Light Mode" : "🌙 Dark Mode";
        }
    }

    // Mengecek tema saat halaman dimuat
    const savedTheme = localStorage.getItem("theme") || "light";
    document.documentElement.setAttribute("data-theme", savedTheme);
    updateButtonText(savedTheme);

    // Event listener untuk tombol toggle tema
    document.getElementById("toggle-theme")?.addEventListener("click", toggleTheme);

   
        const navbarList = document.getElementById("navbarList");
        const menuToggle = document.getElementById("menuToggle");
    
        // Fungsi untuk toggle navbar
        function toggleNavbar() {
            navbarList.classList.toggle("open");
        }
    
        // Event listener untuk tombol toggle
        menuToggle.addEventListener("click", (e) => {
            e.stopPropagation(); // Mencegah event bubbling
            toggleNavbar();
        });
    
        // Tutup navbar jika klik di luar menu
        document.addEventListener("click", (event) => {
            if (!navbarList.contains(event.target) && !menuToggle.contains(event.target)) {
                navbarList.classList.remove("open");
            }
        });
    

    // Scroll halus untuk semua link
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            target?.scrollIntoView({ behavior: 'smooth' });

            // Tutup menu setelah klik
            navbarList.classList.remove("open");
        });
    });
});
</script>