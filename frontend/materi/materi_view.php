<?php
session_start();

// Include koneksi database
require '../../includes/db.php';

// Get only published materials
$sql_materi = "SELECT * FROM materi WHERE status = 'published' ORDER BY id_materi DESC";
$stmt_materi = $pdo->query($sql_materi);
$materi = $stmt_materi->fetchAll(PDO::FETCH_ASSOC);


// Ambil materi unggulan (materi pertama)
$materi_unggulan = $materi[0] ?? null;

?>
<?php include_once('../components/header.php'); ?>
<style>
:root {
    --sidebar-bg: #d0fefe;          /* Biru muda kehijauan */
    --sidebar-text: #333333;        /* Teks abu gelap */
    --sidebar-hover: #d1ffff;       /* Cyan sangat terang */
    
    --content-bg: #fefadd;          /* Kuning pastel */
    --content-text: #333333;        /* Teks abu gelap */
    
    --card-bg: #e5fce8;             /* Hijau pastel pucat */
    --primary-color: #fefbdc;       /* Kuning muda hampir putih */
    --secondary-color: #ddfdf0;     /* Hijau mint pucat */
    --tertiary-color: #d4fdff;      /* Putih kebiruan */
    
    --accent-color: #ffc400;        /* Tetap: Kuning emas */
    --hover-color: #fefadf;         /* Krem kekuningan */
    
    --background-color: #ffffff;    /* Tetap putih */
    --text-color: #333333;          /* Teks abu gelap */
    
    --shadow-color: rgba(0, 102, 204, 0.2); /* Bayangan lembut */
    --card-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Shadow card ringan */
}


/* Animasi Background */
@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Main Content */
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 40px 20px;
    position: relative;
}

/* Container tombol utama */
.center-container {
    text-align: center;
    max-width: 800px;
    margin-top: 3.5rem;
    width: 100%;
    padding: 40px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
}

/* Efek rotasi latar belakang */
.center-container::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
    z-index: -1;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Judul */
.center-container h2 {
    color: var(--sidebar-bg);
    font-size: 2.2rem;
    margin-bottom: 30px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

/* Container tombol */
.btn-container {
    display: flex;
    color: var(--sidebar-text);
    justify-content: center;
    margin: 0px 0;
}

/* Tombol utama */
.btn-baca {
    background: linear-gradient(45deg, var(--sidebar-bg), var(--tertiary-color));
    color: var(--sidebar-text);
    padding: 20px 50px;
    text-decoration: none;
    border-radius: 50px;
    font-weight: bold;
    font-size: 2.5rem;
    transition: all 0.4s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    box-shadow: 0 8px 20px rgba(255, 75, 43, 0.4);
    position: relative;
    overflow: hidden;
    border: none;
    cursor: pointer;
    transform: translateY(0);
    animation: pulse 2s infinite;
}

.btn-baca i {
    font-size: 1.8rem;
}

.btn-baca:hover {
    transform: translateY(-8px) scale(1.05);
    box-shadow: 0 15px 25px rgba(255, 75, 43, 0.6);
    background: linear-gradient(45deg, #ff4b2b, #ff416c);
}

.btn-baca:active {
    transform: translateY(-4px) scale(0.98);
}

.btn-baca::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: 0.5s;
}

.btn-baca:hover::before {
    left: 100%;
}

.othe-materi {
color: var(--sidebar-text);
font-size: 2rem;
padding: 3rem;
border-bottom: 2px solid var(--sidebar-bg);
};

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(255, 75, 43, 0.7); }
    70% { box-shadow: 0 0 0 15px rgba(255, 75, 43, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 75, 43, 0); }
}

/* Daftar materi */
.materi-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 25px;
    margin-top: 40px;
}

.materi-card {
    background: rgba(255, 255, 255, 0.12);
    border-radius: 15px;
    padding: 25px;
    width: 250px;
    text-align: center;
    transition: all 0.3s ease;
    backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.materi-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.2);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.materi-card h3 {
    color: var(--sidebar-hover);
    margin-bottom: 15px;
    font-size: 1.2rem;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.materi-card .btn-baca-small {
    background: rgba(255, 255, 255, 0.15);
    color: var(--accent-color);
    padding: 10px 25px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: inline-block;
}

.materi-card .btn-baca-small:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Partikel animasi */
.particles {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
}

.particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.5);
    border-radius: 50%;
    animation: float 15s infinite linear;
}

@keyframes float {
    0% { transform: translateY(100vh) translateX(0); }
    100% { transform: translateY(-100px) translateX(calc(100vw * var(--x))); }
}

/* Responsif */
@media (max-width: 768px) {
    .center-container {
        padding: 20px;
    }
    
    .btn-baca {
        padding: 15px 35px;
        font-size: 1.2rem;
    }
    
    .materi-list {
        flex-direction: column;
        align-items: center;
    }
    
    .materi-card {
        width: 90%;
        max-width: 350px;
    }
}
.no-materi {
    color: var(--sidebar-text);
}
</style>

<!-- Partikel animasi -->
<div class="particles" id="particles"></div>

<?php include_once('../components/nav.php'); ?>

<main class="main-content">
    <div class="center-container">
        <h2><i class="fas fa-star"></i> Materi unggulan Hari Ini <i class="fas fa-star"></i></h2>
        
         <?php if ($materi_unggulan): ?>
        <div class="btn-container">
            <a target="_blank" href="<?= htmlspecialchars($materi_unggulan['link_materi']) ?>" class="btn-baca">
                <i class="fas fa-book-reader"></i> Baca Sekarang
            </a>
        </div>
        <?php else: ?>
        <div class="btn-container">
            <p class="no-materi">Belum ada materi unggulan</p>
        </div>
        <?php endif; ?>
        
        <h3 class="othe-materi">Materi Lainnya:</h3>
        <div class="materi-list">
            <?php foreach ($materi as $index => $m): ?>
                <?php if ($index > 0): ?>
                    <div class="materi-card">
                        <h3><?= htmlspecialchars($m['judul_materi']) ?></h3>
                        <a target="_blank" href="<?= htmlspecialchars($m['link_materi']) ?>" class="btn-baca-small">Baca</a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</main>




<script>
// Fungsi untuk membuat partikel animasi
document.addEventListener("DOMContentLoaded", () => {
    

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
function createParticles() {
    const particlesContainer = document.getElementById('particles');
    const particleCount = 30;
    
    for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Ukuran acak
        const size = Math.random() * 10 + 5;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        
        // Posisi acak
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.bottom = '0px';
        
        // Delay animasi acak
        particle.style.animationDelay = `${Math.random() * 15}s`;
        
        // Durasi animasi acak
        const duration = Math.random() * 10 + 15;
        particle.style.animationDuration = `${duration}s`;
        
        // Gerakan horizontal acak
        particle.style.setProperty('--x', Math.random() * 0.5 - 0.25);
        
        // Opasitas acak
        particle.style.opacity = Math.random() * 0.5 + 0.3;
        
        particlesContainer.appendChild(particle);
    }
}

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', createParticles);
</script>