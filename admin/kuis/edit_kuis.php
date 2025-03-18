<?php
session_start();
// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../../includes/db.php';

// Ambil ID kuis dari parameter URL
if (!isset($_GET['id'])) {
    header("Location: list_kuis.php");
    exit;
}
$id_kuis = $_GET['id'];

// Ambil data kuis dari database
$sql_kuis = "SELECT kuis.*, MATERI.judul_materi 
             FROM kuis 
             LEFT JOIN MATERI ON kuis.id_materi = MATERI.id_materi 
             WHERE kuis.id_kuis = :id_kuis";
$stmt_kuis = $pdo->prepare($sql_kuis);
$stmt_kuis->execute([':id_kuis' => $id_kuis]);
$kuis = $stmt_kuis->fetch(PDO::FETCH_ASSOC);

// Jika kuis tidak ditemukan, redirect ke halaman list kuis
if (!$kuis) {
    header("Location: list_kuis.php");
    exit;
}

// Ambil data materi untuk dropdown
$sql_materi = "SELECT id_materi, judul_materi FROM MATERI";
$stmt_materi = $pdo->query($sql_materi);
$materi = $stmt_materi->fetchAll(PDO::FETCH_ASSOC);

// Pesan status (jika ada)
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = '<div class="alert alert-success">Kuis berhasil diperbarui!</div>';
    } elseif ($_GET['status'] === 'error') {
        $message = '<div class="alert alert-error">Gagal memperbarui kuis.</div>';
    }
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <div class="content-container">
        <h1 class="content-title">Edit Kuis</h1>

        <?php echo $message; ?>

        <form class="content-form" method="POST" action="proses_edit_kuis.php">
            <!-- Input ID Kuis (Hidden) -->
            <input type="hidden" name="id_kuis" value="<?php echo $kuis['id_kuis']; ?>">

            <!-- Input Pertanyaan -->
            <div class="form-group">
                <label class="form-label">Pertanyaan:</label>
                <textarea name="pertanyaan" class="form-textarea" required><?php echo htmlspecialchars($kuis['pertanyaan']); ?></textarea>
            </div>

            <!-- Input Pilihan A -->
            <div class="form-group">
                <label class="form-label">Pilihan A:</label>
                <input type="text" name="pilihan_a" class="form-input" value="<?php echo htmlspecialchars($kuis['pilihan_a']); ?>" required>
            </div>

            <!-- Input Pilihan B -->
            <div class="form-group">
                <label class="form-label">Pilihan B:</label>
                <input type="text" name="pilihan_b" class="form-input" value="<?php echo htmlspecialchars($kuis['pilihan_b']); ?>" required>
            </div>

            <!-- Input Pilihan C -->
            <div class="form-group">
                <label class="form-label">Pilihan C:</label>
                <input type="text" name="pilihan_c" class="form-input" value="<?php echo htmlspecialchars($kuis['pilihan_c']); ?>" required>
            </div>

            <!-- Input Jawaban Benar -->
            <div class="form-group">
                <label class="form-label">Jawaban Benar:</label>
                <select name="jawaban_benar" class="form-select" required>
                    <option value="a" <?php echo $kuis['jawaban_benar'] === 'a' ? 'selected' : ''; ?>>Pilihan A</option>
                    <option value="b" <?php echo $kuis['jawaban_benar'] === 'b' ? 'selected' : ''; ?>>Pilihan B</option>
                    <option value="c" <?php echo $kuis['jawaban_benar'] === 'c' ? 'selected' : ''; ?>>Pilihan C</option>
                </select>
            </div>

            <!-- Input Materi -->
            <div class="form-group">
                <label class="form-label">Materi:</label>
                <select name="id_materi" class="form-select">
                    <option value="">Pilih Materi</option>
                    <?php foreach ($materi as $m): ?>
                        <option value="<?php echo $m['id_materi']; ?>" <?php echo $kuis['id_materi'] == $m['id_materi'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($m['judul_materi']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Simpan Perubahan</button>
                <a href="list_kuis.php" class="form-button form-button-cancel">Kembali</a>
            </div>
        </form>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>