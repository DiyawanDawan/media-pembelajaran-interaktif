<?php
session_start();

// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Include koneksi database
require '../../includes/db.php';

// Ambil data materi untuk dropdown
$sql_materi = "SELECT id_materi, judul_materi FROM MATERI";
$stmt_materi = $pdo->query($sql_materi);
$materi = $stmt_materi->fetchAll(PDO::FETCH_ASSOC);

// Pesan status (jika ada)
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = '<div class="alert alert-success">Kuis berhasil ditambahkan!</div>';
    } elseif ($_GET['status'] === 'error') {
        $message = '<div class="alert alert-error">Gagal menambahkan kuis.</div>';
    }
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <div class="content-container">
        <h1 class="content-title">Tambah Kuis</h1>

        <?php echo $message; ?>

        <form class="content-form" method="POST" action="proses_tambah_kuis.php">
            <!-- Input Pertanyaan -->
            <div class="form-group">
                <label class="form-label">Pertanyaan:</label>
                <textarea name="pertanyaan" class="form-textarea" required></textarea>
            </div>

            <!-- Input Pilihan A -->
            <div class="form-group">
                <label class="form-label">Pilihan A:</label>
                <input type="text" name="pilihan_a" class="form-input" required>
            </div>

            <!-- Input Pilihan B -->
            <div class="form-group">
                <label class="form-label">Pilihan B:</label>
                <input type="text" name="pilihan_b" class="form-input" required>
            </div>

            <!-- Input Pilihan C -->
            <div class="form-group">
                <label class="form-label">Pilihan C:</label>
                <input type="text" name="pilihan_c" class="form-input" required>
            </div>

            <!-- Input Jawaban Benar -->
            <div class="form-group">
                <label class="form-label">Jawaban Benar:</label>
                <select name="jawaban_benar" class="form-select" required>
                    <option value="a">Pilihan A</option>
                    <option value="b">Pilihan B</option>
                    <option value="c">Pilihan C</option>
                </select>
            </div>

            <!-- Input Materi -->
            <div class="form-group">
                <label class="form-label">Materi:</label>
                <select name="id_materi" class="form-select">
                    <option value="">Pilih Materi</option>
                    <?php foreach ($materi as $m): ?>
                        <option value="<?php echo $m['id_materi']; ?>"><?php echo htmlspecialchars($m['judul_materi']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Simpan</button>
                <a href="list_kuis.php" class="form-button form-button-cancel">Kembali</a>
            </div>
        </form>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>