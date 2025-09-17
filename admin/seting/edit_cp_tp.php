<?php
session_start();

// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Include koneksi database
require '../../includes/db.php';

// Ambil ID CP/TP dari parameter URL
if (!isset($_GET['id'])) {
    header("Location: list_cp_tp.php");
    exit;
}
$id_pembelajaran = $_GET['id'];

// Ambil data CP/TP dari database
$sql_pembelajaran = "SELECT pembelajaran.*, materi.judul_materi 
                     FROM pembelajaran 
                     LEFT JOIN materi ON pembelajaran.id_materi = materi.id_materi 
                     WHERE pembelajaran.id_pembelajaran = :id_pembelajaran";
$stmt_pembelajaran = $pdo->prepare($sql_pembelajaran);
$stmt_pembelajaran->execute([':id_pembelajaran' => $id_pembelajaran]);
$pembelajaran = $stmt_pembelajaran->fetch(PDO::FETCH_ASSOC);

// Jika CP/TP tidak ditemukan, redirect ke halaman list CP/TP
if (!$pembelajaran) {
    header("Location: list_cp_tp.php");
    exit;
}

// Ambil data materi untuk dropdown
$sql_materi = "SELECT id_materi, judul_materi FROM materi";
$stmt_materi = $pdo->query($sql_materi);
$materi = $stmt_materi->fetchAll(PDO::FETCH_ASSOC);

// Pesan status (jika ada)
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = '<div class="alert alert-success">Data CP/TP berhasil diperbarui!</div>';
    } elseif ($_GET['status'] === 'error') {
        $message = '<div class="alert alert-error">Gagal memperbarui data CP/TP.</div>';
    }
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <div class="content-container">
        <h1 class="content-title">Edit CP/TP</h1>

        <?php echo $message; ?>

        <form class="content-form" method="POST" action="proses_edit_cp_tp.php">
            <!-- Input ID CP/TP (Hidden) -->
            <input type="hidden" name="id_pembelajaran" value="<?php echo $pembelajaran['id_pembelajaran']; ?>">

            <!-- Input Materi -->
            <div class="form-group">
                <label class="form-label">Materi:</label>
                <select name="id_materi" class="form-select" required>
                    <option value="">Pilih Materi</option>
                    <?php foreach ($materi as $m): ?>
                        <option value="<?php echo $m['id_materi']; ?>" <?php echo $pembelajaran['id_materi'] == $m['id_materi'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($m['judul_materi']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Input Tujuan Pembelajaran (TP) -->
            <div class="form-group">
                <label class="form-label">Tujuan Pembelajaran (TP):</label>
                <textarea name="poin_tujuan" class="form-textarea" required><?php echo htmlspecialchars($pembelajaran['poin_tujuan']); ?></textarea>
            </div>

            <!-- Input Capaian Pembelajaran (CP) -->
            <div class="form-group">
                <label class="form-label">Capaian Pembelajaran (CP):</label>
                <textarea name="poin_capaian" class="form-textarea" required><?php echo htmlspecialchars($pembelajaran['poin_capaian']); ?></textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Simpan Perubahan</button>
                <a href="list_cp_tp.php" class="form-button form-button-cancel">Kembali</a>
            </div>
        </form>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>