<?php
session_start();

// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Include koneksi database
require '../../includes/db.php';

// Ambil data CP/TP dari database
$sql_pembelajaran = "SELECT pembelajaran.*, materi.judul_materi 
                     FROM pembelajaran 
                     LEFT JOIN materi ON pembelajaran.id_materi = materi.id_materi";
$stmt_pembelajaran = $pdo->query($sql_pembelajaran);
$pembelajaran = $stmt_pembelajaran->fetchAll(PDO::FETCH_ASSOC);

// Pesan status (jika ada)
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = '<div class="alert alert-success">Data CP/TP berhasil ditambahkan/diubah/dihapus!</div>';
    } elseif ($_GET['status'] === 'error') {
        $message = '<div class="alert alert-error">Gagal melakukan operasi.</div>';
    }
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <div class="content-container">
        <h1 class="content-title">Daftar CP/TP</h1>

        <?php echo $message; ?>

        <!-- Tombol Tambah CP/TP -->
        <div class="form-actions">
            <a href="cp_tp.php" class="form-button form-button-submit">Tambah CP/TP</a>
        </div>

        <!-- Tabel Daftar CP/TP -->
        <table class="data-table">
            <thead>
                <tr class="table-header-row">
                    <th class="table-header">Nomor</th> <!-- Perubahan dari "No" ke "Nomor" -->
                    <th class="table-header">Materi</th>
                    <th class="table-header">Tujuan Pembelajaran (TP)</th>
                    <th class="table-header">Capaian Pembelajaran (CP)</th>
                    <th class="table-header">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $nomor = 1; // Mulai nomor urut dari 1
                foreach ($pembelajaran as $p): ?>
                    <tr class="table-row">
                        <td class="table-data"><?php echo $nomor++; ?></td> <!-- Menampilkan nomor urut -->
                        <td class="table-data"><?php echo htmlspecialchars($p['judul_materi'] ?? 'Tidak Terkait'); ?></td>
                        <td class="table-data"><?php echo htmlspecialchars($p['poin_tujuan']); ?></td>
                        <td class="table-data"><?php echo htmlspecialchars($p['poin_capaian']); ?></td>
                        <td class="table-data table-actions">
                            <a href="edit_cp_tp.php?id=<?php echo $p['id_pembelajaran']; ?>" class="btn btn-edit">Edit</a>
                            <a href="hapus_cp_tp.php?id=<?php echo $p['id_pembelajaran']; ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>
