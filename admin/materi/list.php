<?php
session_start();

// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Include koneksi database
require '../../includes/db.php';

// Ambil data materi dan audiobook dari database
$sql_materi = "SELECT MATERI.*, AUDIOBOOK.file_audio 
               FROM MATERI 
               LEFT JOIN AUDIOBOOK ON MATERI.id_materi = AUDIOBOOK.id_materi";
$stmt_materi = $pdo->query($sql_materi);
$materi = $stmt_materi->fetchAll(PDO::FETCH_ASSOC);


?>
<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
<main class="content">
    <h1 class="content-title">Dashboard Admin</h1>

    <li class="btn"><a href="tambah.php" class="btn btn-tambah">Tambah Materi dan Audiobook</a></li>
    <li class="btn"><a href="logout.php" class="btn btn-hapus">Logout</a></li>


    </div>

    <h2 class="content-subtitle">Daftar Materi dan Audiobook</h2>
    <table class="data-table">
        <thead>
            <tr class="table-header-row">
                <th class="table-header">ID</th>
                <th class="table-header">Judul Materi</th>
                <th class="table-header">Deskripsi</th>
                <th class="table-header">Status</th>
                <th class="table-header">Gambar Materi</th>
                <th class="table-header">File Audiobook</th>
                <th class="table-header">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materi as $m): ?>
                <tr class="table-row">
                    <td class="table-data"><?= htmlspecialchars($m['id_materi']) ?></td>
                    <td class="table-data"><?= htmlspecialchars($m['judul_materi']) ?></td>
                    <td class="table-data"><?= htmlspecialchars($m['deskripsi']) ?></td>
                    <td class="table-data"><?= htmlspecialchars($m['status']) ?></td>
                    <td class="table-data">
                        <?php if ($m['gambar']): ?>
                            <!-- Tambahkan teks "View Gambar" yang dapat diklik -->
                            <a href="../../assets/uploads/gambar/<?= htmlspecialchars(basename($m['gambar'])) ?>" target="_blank" class="view-gambar-link">
                                <i class="bi bi-eye"></i> Lihat
                            </a>
                        <?php else: ?>
                            <span class="table-no-image">Tidak ada gambar</span>
                        <?php endif; ?>
                    </td>
                    <td class="table-data">
                        <?php if ($m['file_audio']): ?>
                            <audio controls class="table-audio">
                                <source src="../../assets/uploads/audio/<?= htmlspecialchars(basename($m['file_audio'])) ?>" type="audio/mpeg">
                                Browser Anda tidak mendukung tag audio.
                            </audio>
                        <?php else: ?>
                            <span class="table-no-audio">Tidak ada audiobook</span>
                        <?php endif; ?>
                    </td>
                    <td class="table-data table-actions">
                        <a href="edit.php?id=<?= $m['id_materi'] ?>" class="btn btn-edit">Edit</a>
                        <a href="hapus.php?id=<?= $m['id_materi'] ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data materi dan audiobook ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include_once('../components/footer.php') ?>