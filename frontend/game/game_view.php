<?php
session_start();
require_once '../../includes/db.php'; // Pastikan file koneksi sudah benar

// Inisialisasi session untuk menyimpan poin
if (!isset($_SESSION['poin'])) {
    $_SESSION['poin'] = 0; // Poin awal
}

// Ambil data organ dari database
$query = "SELECT * FROM organ_data";
$stmt = $pdo->query($query);
$organ_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inisialisasi session untuk menyimpan indeks soal
if (!isset($_SESSION['current_index'])) {
    $_SESSION['current_index'] = 0;
}

// Jika form dikirim (tebakan dikirim)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $tebakan = strtolower(trim($_POST['tebakan'])); // Normalisasi tebakan

    // Cari data organ berdasarkan ID
    $organ = null;
    foreach ($organ_data as $data) {
        if ($data['id'] == $id) {
            $organ = $data;
            break;
        }
    }

    if ($organ) {
        $jawaban_benar = strtolower(trim($organ['correct_word'])); // Normalisasi jawaban benar
        if ($tebakan === $jawaban_benar) {
            $pesan = "Selamat! Jawaban Anda benar. (+50 poin)";
            $_SESSION['poin'] += 50; // Tambah 50 poin
        } else {
            $pesan = "Maaf, jawaban Anda salah. Jawaban yang benar adalah: " . htmlspecialchars($organ['correct_word']) . " (-25 poin)";
            $_SESSION['poin'] -= 25; // Kurangi 25 poin
        }

        // Pindah ke soal berikutnya
        $_SESSION['current_index'] = ($_SESSION['current_index'] + 1) % count($organ_data);
    } else {
        $pesan = "Data organ tidak ditemukan.";
    }
}

// Ambil organ yang sedang ditampilkan
$current_index = $_SESSION['current_index'];
$organ = $organ_data[$current_index];
$image_path = "../../assets/uploads/gambar/" . $organ['image']; // Sesuaikan path gambar
$scrambled_word = $organ['scrambled_word'];
$scrambled_letters = str_split($scrambled_word); // Pisahkan kata acak menjadi array huruf
?>

<?php include_once('../components/header.php') ?>
<?php include_once('../components/nav.php') ?>

<div class="content">
    <h1 class="content-title">Selamat Datang di Media Pembelajaran</h1>
    <p class="content-text">Silakan pilih menu di samping untuk mengakses materi.</p>

    <!-- Bungkus semua elemen ke dalam card -->
    <div class="card">
     <!-- Container untuk Poin dengan Efek Running Text -->
     <div class="poin-container">
        <div class="poin-box">
            <div class="running-text">
                Poin Anda: <?= $_SESSION['poin'] ?>
            </div>
        </div>
    </div>
    <?php if (isset($pesan)): ?>
                <div class="pesan <?= strpos($pesan, 'salah') !== false ? 'error' : '' ?>">
                    <?= htmlspecialchars($pesan) ?>
                </div>
            <?php endif; ?>
    <!-- Tambahkan Fitur Tebak Kata di Sini -->
    <div class="tebak-kata-container">
        <h2 class="tebak-kata-title">Tebak Nama Organ</h2>
        <?php if (!empty($organ_data)): ?>
            <img src="<?= htmlspecialchars($image_path) ?>" alt="<?= htmlspecialchars($organ['organ_name']) ?>" class="organ-image">
            <p class="organ-description"><?= htmlspecialchars($organ['description']) ?></p>

            <!-- Card Container untuk Kata Acak -->
            <div class="card-container">
                <div class="scrambled-word-grid">
                    <?php foreach ($scrambled_letters as $letter): ?>
                        <span class="scrambled-letter"><?= htmlspecialchars($letter) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Form Tebakan dengan Input Kotak -->
            <form method="POST" class="tebakan-form">
                <input type="hidden" name="id" value="<?= htmlspecialchars($organ['id']) ?>">
                <div class="tebakan-input-container">
                    <input type="text" name="tebakan" placeholder="[----]" class="tebakan-input" required>
                </div>
                <input type="submit" value="Tebak" class="tebakan-submit">
            </form>
           
        <?php else: ?>
            <p class="no-data-message">Tidak ada data organ yang tersedia.</p>
        <?php endif; ?>
    </div>
</div>
</div>
<?php include_once('../components/footer.php') ?>
