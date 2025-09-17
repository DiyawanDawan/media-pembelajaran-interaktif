<?php
session_start();
require_once '../../includes/db.php';

// Ambil data organ dari database
$query = "SELECT * FROM organ_data";
$stmt = $pdo->query($query);
$organ_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ubah array ke format JSON untuk digunakan di JavaScript
$organ_data_json = json_encode($organ_data, JSON_UNESCAPED_UNICODE);
?>

<?php include_once('../components/header.php') ?>
<?php include_once('../components/nav.php') ?>

<!-- Layar Awal dengan Tombol Play -->
<div id="start-screen">
    <button id="play-button" class="play-button">Play Game</button>
</div>

<!-- Efek Suara -->
<audio id="bg-music" loop>
    <source src="../../assets/sounds/background.mp3" type="audio/mp3">
</audio>

<audio id="click-sound">
    <source src="../../assets/sounds/click.mp3" type="audio/mp3">
</audio>

<audio id="correct-sound">
    <source src="../../assets/sounds/correct.mp3" type="audio/mp3">
</audio>

<audio id="wrong-sound">
    <source src="../../assets/sounds/wrong.mp3" type="audio/mp3">
</audio>

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



    /* Reset dasar dan font playful */
    html,
    body {
        margin: 0;
        padding: 0;
        
        font-size: 16px;
        height: 100%;
        box-sizing: border-box;
    }

  
    /* Mode Gelap bisa ditambahkan jika diperlukan */
    /* [data-theme="dark"] { ... } */

    /* Start Screen: tampilan awal dengan background gradient yang ceria */
    #start-screen {
        margin-top: 3.75rem;
        /* Misalnya tinggi navbar 60px */
        /* margin-bottom: 3.75rem; Misalnya tinggi footer 60px */
        height: calc(100vh - 7.5rem);
        display: flex;
        justify-content: center;
        align-items: center;
         background: 
        linear-gradient(
            135deg,
            #d0fefe,  /* Biru muda kehijauan */
            #d1ffff,  /* Cyan sangat terang */
            #fefadd,  /* Kuning pastel */
            #e5fce8,  /* Hijau pastel pucat */
            #fefadf,  /* Krem kekuningan */
            #ddfdf0   /* Hijau mint pucat */
        ),
            url('../../assets/bg.png');
        background-blend-mode: overlay;
        /* Ubah ke multiply, screen, atau overlay sesuai keinginan */
        background-size: cover;
        /* Gambar memenuhi seluruh kontainer */
        background-repeat: no-repeat;
        /* Jangan ulang gambar */
        background-position: center 20%;
        /* Geser gambar ke bawah sedikit */
        color: var(--content-text);
        position: relative;
        z-index: 1;
    }
    
/* Tombol Play Game */
.play-button {
    padding: 1rem 2rem;
    font-size: 1.8rem;
    font-weight: bold;
    background: linear-gradient(45deg, #FFCC00, #FF6600); /* Warna cerah untuk anak-anak */
    color: white;
    border: 4px solid #FF4500; /* Warna border kontras */
    border-radius: 50px; /* Bentuk bulat untuk tampilan lebih friendly */
    cursor: pointer;
    text-transform: uppercase;
    letter-spacing: 2px;
    transition: transform 0.3s ease, background 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 8px 15px rgba(255, 69, 0, 0.4); /* Shadow untuk efek timbul */
    display: inline-block;
    text-align: center;
    position: relative;
    overflow: hidden;
}

/* Efek hover */
.play-button:hover {
    background: linear-gradient(45deg, #FF6600, #FFCC00);
    transform: scale(1.1); /* Sedikit membesar saat dihover */
    box-shadow: 0 10px 20px rgba(255, 69, 0, 0.6);
}

/* Efek klik */
.play-button:active {
    transform: scale(0.9); /* Efek mengecil saat ditekan */
    box-shadow: 0 5px 10px rgba(255, 69, 0, 0.3);
}


/* Efek animasi glow */
@keyframes glowing {
    0% { box-shadow: 0 0 5px #FF4500; }
    50% { box-shadow: 0 0 20px #FF4500; }
    100% { box-shadow: 0 0 5px #FF4500; }
}
.play-button {
    animation: glowing 1.5s infinite alternate;
}

/* Efek animasi bintang untuk daya tarik anak-anak */
.play-button::before {
    content: "⭐";
    position: absolute;
    left: -20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 2rem;
    opacity: 0;
    transition: left 0.3s ease, opacity 0.3s ease;
}

.play-button:hover::before {
    left: 15px;
    opacity: 1;
}

/* Responsif */
@media screen and (max-width: 768px) { /* Tablet */
    .play-button {
        font-size: 1.5rem;
        padding: 0.8rem 1.5rem;
        border-radius: 40px;
    }
}

@media screen and (max-width: 480px) { /* Mobile */
    .play-button {
        font-size: 1.3rem;
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
    }
    .play-button::before {
        font-size: 1.5rem;
    }
}


    /* Game Container: area game dengan background ringan agar teks mudah dibaca */
    #game-container {
        display: none;
        padding: 2rem 1rem;
        /* background: #fdfdfd; */
        position: relative;
        z-index: 1;
    }

    /* Kontainer utama game */
    .content {
        max-width: 60rem;
        margin: 0 auto;
        padding: 1rem;
    }
/* Judul dan teks konten */
.title {
    margin-top: 3.5rem;
   font-size: 1.5rem;
    letter-spacing: 4px; /* Jarak antar huruf diperbesar */
    text-align: center; /* Rata tengah */
    display: block;
    width: 100%;

    /* Warna teks sangat kontras */
    color: #FFF700; /* Neon Yellow */

 

    /* Efek 3D & Animasi */
    transform: perspective(600px) rotateX(8deg); /* Efek timbul */
    animation: fadeIn 1.5s ease-in-out;
}

.content-text {
    text-align: center;
    font-size: 2rem; /* Ukuran font lebih besar */
    font-weight: 700; /* Lebih tebal agar efek lebih terasa */
    text-transform: uppercase; /* Semua huruf kapital */
    letter-spacing: 3px; /* Jarak antar huruf lebih luas */
    margin-bottom: 2rem;
    display: block;
    width: 100%;

    /* Warna teks utama */
    color: #FF4500; /* Oranye terang */

    /* Efek Bayangan 3D */
    text-shadow: 
        1px 1px 0px #000000,  /* Lapisan dasar hitam */
        2px 2px 0px #650000,  /* Lapisan merah gelap */
        4px 4px 0px #9B0000,  /* Lapisan merah tua */
        6px 6px 5px rgba(0, 0, 0, 0.6); /* Shadow luar agar lebih dalam */

    /* Efek 3D Melengkung */
    transform: perspective(500px) rotateX(15deg); /* Membuat efek cekung */
    text-align: center;
    animation: fadeIn 1.2s ease-in-out, pulseGlow 2s infinite alternate;
}

/* Animasi glow agar lebih hidup */
@keyframes pulseGlow {
    0% {
        text-shadow: 
            1px 1px 0px #000000,
            2px 2px 0px #650000,
            4px 4px 0px #9B0000,
            6px 6px 10px rgba(255, 69, 0, 0.6); /* Glow oranye terang */
    }
    100% {
        text-shadow: 
            1px 1px 0px #000000,
            2px 2px 0px #650000,
            4px 4px 0px #9B0000,
            6px 6px 20px rgba(255, 69, 0, 0.9); /* Glow lebih kuat */
    }
}

/* Animasi fade-in */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Responsif untuk berbagai perangkat */
@media screen and (max-width: 1024px) { /* Tablet */
    .content-title {
        font-size: 2.5rem;
        letter-spacing: 3px;
    }
    .content-text {
        font-size: 1.8rem;
        letter-spacing: 2px;
    }
}

@media screen and (max-width: 768px) { /* Tablet kecil dan iPad */
    .content-title {
        font-size: 2rem;
        letter-spacing: 2px;
    }
    .content-text {
        font-size: 1.6rem;
        letter-spacing: 1.5px;
    }
}

@media screen and (max-width: 480px) { /* Mobile */
    .content-title {
        font-size: 1.8rem;
        letter-spacing: 1px;
       
    }
    .content-text {
        font-size: 1.4rem;
        letter-spacing: 1px;
       
    }
}

    /* Card Game: area interaktif untuk bermain */
    .card-game {
        background: rgba(255,255,255, 0.12);
        padding: 1.4rem;
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        color: var(--text-color);
    }

    /* Tampilan Poin */
    .poin-container {
        margin: 1rem 0;
    }

    .poin-box {
        background: var(--primary-color);
        color: var(--text-color);
        padding: 0.75rem;
        border-radius: 0.75rem;
        font-size: 1.125rem;
        text-align: center;
    }

    /* Pesan Notifikasi */
    .pesan-container {
        display: none;
        position: fixed;
        top: 10%;
        left: 50tebak-kata
        transform: translateX(-50%);
        padding: 0.9375rem;
        border-radius: 0.5rem;
        font-size: 1rem;
        font-weight: bold;
        text-align: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .pesan.success {
        background: #4CAF50;
        color: #fff;
    }

    .pesan.error {
        background: #FF3B30;
        color: #fff;
    }

    /* Gambar dan Deskripsi Organ */
    .organ-image {
        width: 12.5rem;
        height: auto;
        border-radius: 0.625rem;
        margin-bottom: 0.625rem;
        border: 3px solid var(--accent-color);
    }

    .organ-description {
        font-size: 1.125rem;
        color: #000000;
        margin-bottom: 1rem;
        background: rgba(255, 255, 255, 0.14);
        padding: 0.5rem;
        border-radius: 0.5rem;
        color: #000000;
    }

    /* Grid untuk kata yang diacak */
    .scrambled-word-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(3.125rem, 1fr));
        gap: 0.625rem;
        justify-content: center;
        margin: 1.25rem 0;
    }
/* Huruf Acak dengan Efek 3D Nyata */
.scrambled-letter {
    font-size: 3.5rem;
    font-weight: bold;
    text-transform: uppercase;
    padding: 1rem;
    border-radius: 0.5rem;
    text-align: center;
    transition: transform 0.3s ease-in-out;

    /* Gradient Latar Belakang */
    background: linear-gradient(135deg, #6A0572, #9B5DE5);

    /* Warna teks lebih cerah agar kontras */
    color: #FFD166;

    /* Border tegas agar lebih nyata */
    border: 3px solid #F13B2A;

    /* Efek Shadow 3D Lebih Dalam */
    text-shadow: 
        2px 2px 0px #000, 
        4px 4px 0px #333, 
        6px 6px 0px #555, 
        8px 8px 10px rgba(0, 0, 0, 0.7); /* Shadow luar lebih lembut */

    /* Efek transformasi untuk 3D lebih kuat */
    transform: perspective(500px) rotateX(10deg) rotateY(10deg);

    /* Efek animasi glow */
    animation: glowEffect 2s infinite alternate;
}

/* Hover untuk efek depth */
.scrambled-letter:hover {
    transform: perspective(500px) rotateX(0deg) rotateY(0deg) scale(1.1);
    box-shadow: 0 10px 30px rgba(255, 69, 0, 0.8);
}

/* Animasi Glow */
@keyframes glowEffect {
    0% {
        text-shadow: 
            2px 2px 0px #000, 
            4px 4px 0px #333, 
            6px 6px 0px #555, 
            8px 8px 15px rgba(255, 214, 102, 0.6);
    }
    100% {
        text-shadow: 
            2px 2px 0px #000, 
            4px 4px 0px #333, 
            6px 6px 0px #555, 
            8px 8px 25px rgba(255, 214, 102, 1);
    }
}


    .scrambled-letter:hover {
        transform: scale(1.1);
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 1rem;
    }

    .label-form {
        margin-bottom: 0.5rem;
        font-size: 2rem;
        margin: 1rem;
        text-align: center;
        color: #000000;
        /* atau gunakan warna lain sesuai tema */
    }

    .hint-text {
        text-align: center;
        padding: 1rem;
        background: #ffff;
        font-size: 1.5rem;
        color: var(--content-text);
    }

    .tebakan-input {
        padding: 1.5rem;
        font-size: 2.7rem;
        border: 1px solid var(--primary-color);
        border-radius: 0.25rem;
        width: 100%;
        box-sizing: border-box;
        text-align: center;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .tebakan-input:focus {
        outline: none;
        border-color: var(--sidebar-hover);
        box-shadow: 0 0 0.5rem var(--sidebar-hover);
    }

    .tebakan-submit {
        display: block;
        width: 100%;
        padding: 0.625rem 0;
        /* Atur padding vertikal sesuai kebutuhan */
        font-size: 2.125rem;
         background: linear-gradient(45deg, var(--sidebar-bg), var(--tertiary-color));
        padding: 20px 50px;
        color: var(--sidebar-text);
        box-shadow: 0 8px 20px rgba(255, 75, 43, 0.4);
        border-radius: 50px;
        border: none;
        border-radius: 0.3125rem;
        cursor: pointer;
        transition: transform 0.2s ease-in-out, background 0.2s ease-in-out;
    }

    .tebakan-submit:hover {
        background: var(--sidebar-bg);
        transform: scale(1.05);
    }


    /* Tombol Mute */
    #mute-button {
        position: fixed;
        bottom: 1.25rem;
        right: 1.25rem;
        background-color: var(--sidebar-bg);
        color: var(--text-color);
        border: none;
        border-radius: 50%;
        padding: 0.9375rem;
        font-size: 1.5rem;
        cursor: pointer;
        z-index: 1000;
        box-shadow: var(--card-shadow);
    }

    /* Responsive Design: Gunakan satuan rem dan atur ulang untuk tampilan kecil */
    @media (max-width: 48rem) {
        .scrambled-letter {
            font-size: 1.875rem;
            padding: 0.625rem;
        }

        .tebakan-input {
            font-size: 2rem;
        }

        .tebakan-submit {
            font-size: 2rem;
        }

        .tebak-kata-container {
            width: 90%;
            padding: 1rem;
        }
    }

    .main-contents {
        display: flex;
        justify-content: center;
        align-items: center;
        padding-top: 70px;
        /* Sesuaikan dengan tinggi navbar */
        padding-left: 15px;
        padding-right: 15px;
        min-height: 100vh;
        /* Menggabungkan linear gradient dan background image */
        background:
            linear-gradient(135deg,
                #F15BB5,
                /* Bright Pink */
                #6A0572,
                /* Bright Purple */
                #9B5DE5,
                /* Softer Purple */
                #FFD166,
                /* Bright Yellow */
                #FF9A8B,
                /* Light Pink */
                #F13B2A
                /* Bright Red-Pink */
            ),
            url('../../assets/bg.png');
        background-blend-mode: overlay;
        /* Ubah ke multiply, screen, atau overlay sesuai keinginan */
        background-size: cover;
        /* Gambar memenuhi seluruh kontainer */
        background-repeat: no-repeat;
        /* Jangan ulang gambar */
        background-position: center 20%;
        /* Geser gambar ke bawah sedikit */
        color: var(--content-text);
    }

    /* Penyesuaian untuk mobile */
    @media (max-width: 600px) {
        .main-contents {
            padding-top: 70px;
            padding-left: 10px;
            padding-right: 10px;
            background-position: center 70%;
        }
    }
    .tebak-kata-title {
        color: #000000;
    }
</style>

<div  id="game-container" style="display: none;">
    <div class="container">
    <h1 class="title">Geme ini merupakan menyusun nama orang pernapasan.</h1>
        <div class="card-game">
            <button id="mute-button" class="mute-button">🔊</button>

            <div class="poin-container">
                <div class="poin-box">
                    <div class="running-text" id="poin-display">Poin Anda: 0</div>
                </div>
            </div>

            <div class="pesan-container">
                <div id="pesan" class="pesan"></div>
            </div>

            <div class="tebak-kata-container">
           
                <h2 class="tebak-kata-title">Tebak Nama Organ</h2>
                <img src="" alt="Organ Image" class="organ-image">
                <p class="organ-description"></p>

                <div class="scrambled-word-grid"></div>
                <div class="hint-container">
                    <p class="hint-text">Bantuan: <span id="hint-pattern"></span></p>
                </div>
                <form class="tebakan-form">
                    <div class="form-group">
                        <label for="tebakan-input" class="label-form">Masukkan Jawaban:</label>
                        <input type="text" id="tebakan-input" name="tebakan" class="tebakan-input" placeholder="[------]" required>
                    </div>

                    <div class="form-group">
                        <input type="submit" value="Tebak" class="tebakan-submit">
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

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
    document.addEventListener("DOMContentLoaded", function() {
        let organData = <?= $organ_data_json ?>;
        let currentIndex = 0;
        let poin = 0;

        let bgMusic = document.getElementById("bg-music");
        let muteButton = document.getElementById("mute-button");
        let clickSound = document.getElementById("click-sound");
        let correctSound = document.getElementById("correct-sound");
        let wrongSound = document.getElementById("wrong-sound");

        const tebakButton = document.querySelector(".tebakan-submit");

        if (tebakButton) {
            tebakButton.addEventListener("click", function() {
                clickSound.play();
            });
        }

        // Cek apakah pengguna sebelumnya mute atau tidak
        let isMuted = localStorage.getItem("bgMusicMuted") === "true";
        bgMusic.muted = isMuted;
        muteButton.textContent = isMuted ? "🔇" : "🔊";

        document.getElementById("play-button").addEventListener("click", function() {
            // Sembunyikan start-screen
            console.log("berhasil klik");
            document.getElementById("start-screen").style.display = "none";

            // Tampilkan game-container
let gameContainer = document.getElementById("game-container");
gameContainer.style.display = "block";
            gameContainer.classList.add("main-contents");
            console.log("Class ditambahkan setelah delay:", gameContainer.classList);
            // Mulai musik (jika ada)
            bgMusic.volume = 0.5;
            bgMusic.play();

            // Muat game
            loadGame();
        });

        // Tombol Mute/Unmute
        muteButton.addEventListener("click", function() {
            bgMusic.muted = !bgMusic.muted;
            muteButton.textContent = bgMusic.muted ? "🔇" : "🔊";
            localStorage.setItem("bgMusicMuted", bgMusic.muted);
        });

        // Form Tebak Kata
        document.querySelector(".tebakan-form").addEventListener("submit", function(event) {
            event.preventDefault();

            let inputJawaban = document.querySelector(".tebakan-input").value.trim().toLowerCase();
            let jawabanBenar = organData[currentIndex].correct_word.toLowerCase();

            if (inputJawaban === jawabanBenar) {
                poin += 50;
                correctSound.play();
                showMessage("Selamat! Jawaban Anda benar. (+50 poin)", "success");
            } else {
                poin -= 25;
                wrongSound.play();
                showMessage("Maaf, jawaban Anda salah. Jawaban yang benar adalah: " + jawabanBenar + " (-25 poin)", "error");
            }

            document.getElementById("poin-display").textContent = "Poin Anda: " + poin;

            // Pindah ke soal berikutnya setelah 2 detik
           currentIndex++;
    if (currentIndex >= organData.length) {
        // Jika sudah sampai akhir, tampilkan pesan selesai atau kembali ke awal
        currentIndex = 0;
        showMessage("Selamat! Anda telah menyelesaikan semua soal. Mulai dari awal lagi.", "success");
    }
            setTimeout(loadGame, 2000);
        });

        function generateHintPattern(correctWord) {
            // Pisahkan kata menjadi array huruf
            let letters = correctWord.split('');

            // Buat pola bantuan
            let hintPattern = letters.map((letter, index) => {
                // Tampilkan huruf pertama dan huruf tengah
                if (index === 0 || index === Math.floor(letters.length / 2)) {
                    return letter; // Tampilkan huruf asli
                } else {
                    return "_"; // Ganti huruf lain dengan underscore
                }
            }).join(' '); // Gabungkan dengan spasi

            return hintPattern;
        }

        function loadGame() {
            // Cek apakah data tersedia
            if (!organData || organData.length === 0) {
                // Sembunyikan elemen game container
                document.getElementById("game-container").innerHTML = `
            <div class="main-contents">
                <div class="container">
                <h1 class="title">Game Tidak Tersedia</h1>
        
                </div>
            </div>
        `;
                return;
            }

            if (currentIndex >= organData.length) {
                currentIndex = 0; // Reset ke awal jika melebihi panjang array
            }

            const organ = organData[currentIndex];
            if (!organ || !organ.image) {
                console.error("Data organ tidak valid:", organ);
                return;
            }

            // Tampilkan gambar dan deskripsi
            document.querySelector(".organ-image").src = "../../assets/uploads/gambar/" + organ.image;
            document.querySelector(".organ-description").textContent = organ.description;

            // Tampilkan kata acak
            document.querySelector(".scrambled-word-grid").innerHTML = organ.scrambled_word
                .split('')
                .map(letter => `<span class="scrambled-letter">${letter}</span>`)
                .join('');
            document.querySelector(".tebakan-input").value = "";

            // Tampilkan pola bantuan
            const hintPattern = generateHintPattern(organ.correct_word);
            document.getElementById("hint-pattern").textContent = hintPattern;
        }


        function showMessage(message, status) {
            let pesanDiv = document.getElementById("pesan");
            let pesanContainer = document.querySelector(".pesan-container");

            pesanDiv.textContent = message;
            pesanDiv.className = "pesan " + status;

            pesanContainer.style.display = "block";
            pesanContainer.style.opacity = "1";

            setTimeout(() => {
                pesanContainer.style.opacity = "0";
                setTimeout(() => {
                    pesanContainer.style.display = "none";
                }, 300);
            }, 2000);
        }
    });
</script>

<?php include_once('../components/footer.php') ?>