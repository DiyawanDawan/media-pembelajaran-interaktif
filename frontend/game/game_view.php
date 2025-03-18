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
    /* TODO css game */
:root {
    --sidebar-bg: #6A0572; /* Ungu cerah */
    --sidebar-text: #ffffff; /* Teks putih */
    --sidebar-hover: #9B5DE5; /* Ungu lebih lembut */
    --content-bg: #F15BB5; /* Pink cerah */
    --content-text: #ffffff; /* Teks putih */
    --card-bg: #9f4eaf;
    --card-shadow: 0 4px 8px rgba(0, 0, 0, 0.3); /* Shadow card */
}

/* Variabel CSS untuk mode gelap */
[data-theme="dark"] {
    --sidebar-bg: #f13b2a; /* Merah muda cerah */
    --sidebar-text: #ffffff; /* Teks putih */
    --sidebar-hover: #FF9A8B; /* Merah muda lebih terang */
    --content-bg: #FFD166; /* Kuning cerah */
    --content-text: #333333; /* Teks hitam agar kontras */
    --hover: #ffd700;
    --card-bg: #e2a9a9; 
    --card-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Shadow card */
}

/* Styling untuk layar awal */
#start-screen {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: var(--content-bg);
}

.play-button {
    padding: 15px 30px;
    font-size: 24px;
    background: var(--sidebar-bg);
    color: var(--sidebar-text);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: background 0.3s;
}

.play-button:hover {
    background: var(--sidebar-hover);
}

/* Game Container */
/* #game-container {
    display: none;
    padding: 20px;
    background: var(--content-bg);
    color: var(--content-text);
    text-align: center;
}

.card-game {
    background: var(--card-bg);
    padding: 20px;
    border-radius: 15px;
    box-shadow: var(--card-shadow);
} */

/* Tampilan poin */
.poin-container {
    margin: 10px 0;
}

.poin-box {
    background: var(--sidebar-bg);
    color: var(--sidebar-text);
    padding: 10px;
    border-radius: 10px;
    font-size: 18px;
}

.running-text {
    font-weight: bold;
}

/* Pesan notifikasi */
.pesan-container {
    display: none;
    position: fixed;
    top: 10%;
    left: 50%;
    transform: translateX(-50%);
    padding: 15px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.pesan.success {
    background: #4CAF50;
    color: white;
}

.pesan.error {
    background: #FF3B30;
    color: white;
}

/* Gambar dan deskripsi organ */
.organ-image {
    width: 200px;
    height: auto;
    border-radius: 10px;
    margin-bottom: 10px;
}

.organ-description {
    font-size: 18px;
    margin-bottom: 10px;
}
/* Grid untuk kata yang diacak */
.scrambled-word-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
    gap: 10px;
    justify-content: center;
    margin: 20px 0;
}

/* Huruf besar, efek shadow, dan warna gradien */
.scrambled-letter {
    font-size: 40px;
    font-weight: bold;
    text-transform: uppercase;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    transition: transform 0.3s ease-in-out;
    
    /* Gradient warna */
    background: linear-gradient(45deg, #FF5733, #FFC300);
    /* -webkit-background-clip: text;
    -webkit-text-fill-color: transparent; */
    
    /* Shadow */
    text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.3);
}

/* Efek hover untuk animasi */
.scrambled-letter:hover {
    transform: scale(1.1);
}


/* Form input tebakan */
.tebakan-form {
    margin-top: 10px;
}

.tebakan-input {
    padding: 8px;
    font-size: 18px;
    border-radius: 5px;
    border: 2px solid var(--sidebar-bg);
    text-align: center;
}

.tebakan-submit {
    padding: 10px 15px;
    font-size: 18px;
    background: var(--sidebar-bg);
    color: var(--sidebar-text);
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

.tebakan-submit:hover {
    background: var(--sidebar-hover);
}

/* Tombol mute */
.mute-button {
    background: transparent;
    border: none;
    font-size: 24px;
    cursor: pointer;
}

.tebak-kata-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    width: 100%;
    /* max-width: 600px; */
    margin: 0 auto;
    padding: 20px;
    background: rgba(0, 0, 0, 0.8);
    border-radius: 15px;
    box-shadow: 0px 4px 15px rgba(255, 255, 255, 0.2);
 /* Efek 3D */
 
}


/* Grid untuk kata yang diacak */
.scrambled-word-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(50px, 1fr));
    gap: 10px;
    justify-content: center;
    align-items: center;
    margin: 20px 0;
    width: 100%;
    max-width: 400px;
}

/* Huruf besar, efek shadow, dan warna gradien */
.scrambled-letter {
    font-size: 40px;
    font-weight: bold;
    text-transform: uppercase;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    transition: transform 0.3s ease-in-out;
     /* Warna teks dengan efek 3D */
     background: linear-gradient(45deg, #FF5733, #FFC300);
    color: black;
    border: 3px solid white;
    /* text-shadow: 8px 8px 4px rgba(0, 0, 0, 0.3); */
    text-shadow: 
        1px 1px 0px #000,  
        2px 2px 0px #222,  
        3px 3px 0px #444,  
        4px 4px 0px #666,  
        5px 5px 0px #888;
}

/* Efek hover */
.scrambled-letter:hover {
    transform: scale(1.1) translateY(-5px);
}

/* Gambar organ */
.organ-image {
    width: 100%;
    max-width: 300px;
    height: auto;
    margin: 10px 0;
    border-radius: 10px;
    box-shadow: 0px 4px 15px rgba(255, 255, 255, 0.3);
}

/* Deskripsi organ */
.organ-description {
    font-size: 18px;
    font-weight: 500;
    color: white;
    margin-bottom: 15px;
    background: rgba(255, 255, 255, 0.2);
    padding: 10px;
    border-radius: 5px;
}

/* Input form */
.tebakan-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

.tebakan-input {
    width: 80%;
    max-width: 300px;
    font-size: 20px;
    text-align: center;
    padding: 10px;
    border: 2px solid #FFC300;
    border-radius: 5px;
    outline: none;
    margin-bottom: 10px;
    /* background-color: white; */
}

/* Tombol submit */
.tebakan-submit {
    background: linear-gradient(45deg, #FF5733, #FFC300);
    color: black;
    font-size: 18px;
    font-weight: bold;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: transform 0.2s ease-in-out;
}

.tebakan-submit:hover {
    transform: scale(1.05);
}

/* Responsif untuk mobile */
@media (max-width: 600px) {
    .scrambled-letter {
        font-size: 30px;
        padding: 10px;
    }

    .tebakan-input {
        font-size: 18px;
    }

    .tebakan-submit {
        font-size: 16px;
    }

    .tebak-kata-container {
        width: 90%;
        padding: 15px;
    }
}

</style>

<script>
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
        tebakButton.addEventListener("click", function () {
            clickSound.play();
        });
    }

        // Cek apakah pengguna sebelumnya mute atau tidak
        let isMuted = localStorage.getItem("bgMusicMuted") === "true";
        bgMusic.muted = isMuted;
        muteButton.textContent = isMuted ? "🔇" : "🔊";

        // Tombol Play Game
        document.getElementById("play-button").addEventListener("click", function() {
            clickSound.currentTime = 0;
            clickSound.play().catch(e => console.log("Gagal memutar suara:", e));

            document.getElementById("start-screen").style.display = "none";
            document.getElementById("game-container").style.display = "block";

            bgMusic.volume = 0.5;
            bgMusic.play();
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
            currentIndex = (currentIndex + 1) % organData.length;
            setTimeout(loadGame, 2000);
        });

        function loadGame() {
            let organ = organData[currentIndex];

            document.querySelector(".organ-image").src = "../../assets/uploads/gambar/" + organ.image;
            document.querySelector(".organ-description").textContent = organ.description;
            document.querySelector(".scrambled-word-grid").innerHTML = organ.scrambled_word.split('').map(letter => `<span class="scrambled-letter">${letter}</span>`).join('');
            document.querySelector(".tebakan-input").value = "";
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

<!-- Game Container -->
<div id="game-container">
    <div class="content">
        <h1 class="content-title">Selamat Datang di Media Pembelajaran</h1>
        <p class="content-text">Silakan pilih menu di samping untuk mengakses materi.</p>

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
                <form class="tebakan-form">
    <div class="form-group">
        <label for="tebakan-input">Masukkan Jawaban:</label>
        <input type="text" id="tebakan-input" name="tebakan" class="tebakan-input" placeholder="[----]" required>
    </div>
    <div class="form-group">
        <input type="submit" value="Tebak" class="tebakan-submit">
    </div>
</form>

            </div>
        </div>
    </div>
</div>

<?php include_once('../components/footer.php') ?>
