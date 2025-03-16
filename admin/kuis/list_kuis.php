<?php
session_start();

// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Include koneksi database
require '../../includes/db.php';

// Ambil data kuis dari database
$sql_kuis = "SELECT kuis.*, MATERI.judul_materi 
             FROM kuis 
             LEFT JOIN MATERI ON kuis.id_materi = MATERI.id_materi";
$stmt_kuis = $pdo->query($sql_kuis);
$kuis = $stmt_kuis->fetchAll(PDO::FETCH_ASSOC);

// Pesan status (jika ada)
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = '<div class="alert alert-success">Kuis berhasil ditambahkan/diubah/dihapus!</div>';
    } elseif ($_GET['status'] === 'error') {
        $message = '<div class="alert alert-error">Gagal melakukan operasi.</div>';
    }
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <div class="content-container">
        <h1 class="content-title">Daftar Kuis</h1>

        <?php echo $message; ?>

        <!-- Tombol Tambah Kuis -->
        <div class="form-actions">
            <a href="tambah_kuis.php" class="form-button form-button-submit">Tambah Kuis</a>
        </div>

        <!-- Tabel Daftar Kuis -->
        <table class="data-table">
            <thead>
                <tr class="table-header-row">
                    <th class="table-header">ID</th>
                    <th class="table-header">Pertanyaan</th>
                    <th class="table-header">Pilihan A</th>
                    <th class="table-header">Pilihan B</th>
                    <th class="table-header">Pilihan C</th>
                    <th class="table-header">Jawaban Benar</th>
                    <th class="table-header">Materi</th>
                    <th class="table-header">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kuis as $k): ?>
                    <tr class="table-row">
                        <td class="table-data"><?php echo htmlspecialchars($k['id_kuis']); ?></td>
                        <td class="table-data"><?php echo htmlspecialchars($k['pertanyaan']); ?></td>
                        <td class="table-data"><?php echo htmlspecialchars($k['pilihan_a']); ?></td>
                        <td class="table-data"><?php echo htmlspecialchars($k['pilihan_b']); ?></td>
                        <td class="table-data"><?php echo htmlspecialchars($k['pilihan_c']); ?></td>
                        <td class="table-data"><?php echo strtoupper(htmlspecialchars($k['jawaban_benar'])); ?></td>
                        <td class="table-data"><?php echo htmlspecialchars($k['judul_materi'] ?? 'Tidak Terkait'); ?></td>
                        <td class="table-data table-actions">
                            <a href="edit_kuis.php?id=<?php echo $k['id_kuis']; ?>" class="btn btn-edit">Edit</a>
                            <a href="hapus_kuis.php?id=<?php echo $k['id_kuis']; ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>