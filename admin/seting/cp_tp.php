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
$sql_materi = "SELECT id_materi, judul_materi FROM materi";
$stmt_materi = $pdo->query($sql_materi);
$materi = $stmt_materi->fetchAll(PDO::FETCH_ASSOC);

// Pesan status (jika ada)
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = '<div class="alert alert-success">Data CP/TP berhasil ditambahkan!</div>';
    } elseif ($_GET['status'] === 'error') {
        $message = '<div class="alert alert-error">Gagal menambahkan data CP/TP.</div>';
    }
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <div class="content-container">
        <h1 class="content-title">Tambah CP/TP</h1>

        <?php echo $message; ?>

        <form class="content-form" method="POST" action="proses_tambah_cp_tp.php">
            <!-- Input Materi -->
            <div class="form-group">
                <label class="form-label">Materi:</label>
                <select name="id_materi" class="form-select" required>
                    <option value="">Pilih Materi</option>
                    <?php foreach ($materi as $m): ?>
                        <option value="<?php echo $m['id_materi']; ?>"><?php echo htmlspecialchars($m['judul_materi']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Input Tujuan Pembelajaran (TP) -->
            <div class="form-group">
                <label class="form-label">Tujuan Pembelajaran (TP):</label>
                <textarea name="poin_tujuan" class="form-textarea" required></textarea>
            </div>

            <!-- Input Capaian Pembelajaran (CP) -->
            <div class="form-group">
                <label class="form-label">Capaian Pembelajaran (CP):</label>
                <textarea name="poin_capaian" class="form-textarea" required></textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Simpan</button>
                <a href="list_cp_tp.php" class="form-button form-button-cancel">Kembali</a>
            </div>
        </form>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>