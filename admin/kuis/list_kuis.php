<?php
session_start();
// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../../includes/db.php';

// Pagination
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Hitung total data
$total_query = "SELECT COUNT(*) FROM kuis";
$total_result = $pdo->query($total_query)->fetchColumn();
$total_pages = ceil($total_result / $limit);

// Ambil data kuis dengan pagination
$sql_kuis = "SELECT kuis.*, materi.judul_materi 
             FROM kuis 
             LEFT JOIN materi ON kuis.id_materi = materi.id_materi 
             LIMIT :limit OFFSET :offset";
$stmt_kuis = $pdo->prepare($sql_kuis);
$stmt_kuis->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt_kuis->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt_kuis->execute();
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
                    <th class="table-header">No</th>
                    <th class="table-header">Pertanyaan</th>
                    <th class="table-header">Pilihan A</th>
                    <th class="table-header">Pilihan B</th>
                    <th class="table-header">Pilihan C</th>
                    <th class="table-header">Pilihan D</th>
                    <th class="table-header">Jawaban Benar</th>
                    <th class="table-header">Materi</th>
                    <th class="table-header">Judul Materi</th>
                    <th class="table-header">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($kuis) > 0): ?>
                    <?php foreach ($kuis as $index => $k): ?>
                        <tr class="table-row">
                            <td class="table-data"><?php echo ($offset + $index + 1); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($k['pertanyaan']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($k['pilihan_a']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($k['pilihan_b']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($k['pilihan_c']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($k['pilihan_d']); ?></td>
                            <td class="table-data"><?php echo strtoupper(htmlspecialchars($k['jawaban_benar'])); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($k['judul_materi'] ?? 'Tidak Terkait'); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($k['judul_materi']); ?></td>
                            <td class="table-data table-actions">
                                <a href="edit_kuis.php?id=<?php echo $k['id_kuis']; ?>" class="btn btn-edit">Edit</a>
                                <a href="hapus_kuis.php?id=<?php echo $k['id_kuis']; ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="table-row">
                        <td colspan="9" class="table-data table-no-data">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="pagination-button">Previous</a>
            <?php endif; ?>

            <span class="pagination-info">Halaman <?php echo $page; ?> dari <?php echo $total_pages; ?></span>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="pagination-button">Next</a>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>

<!-- CSS untuk desain modern dan responsif -->
<style>
    .pagination {
        text-align: center;
        margin: 20px 0;
    }
    .pagination-button {
        display: inline-block;
        padding: 8px 12px;
        margin: 5px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 5px;
    }
    .pagination-button:hover {
        background-color: #0056b3;
    }
    .pagination-info {
        display: inline-block;
        color: black;
        margin: 5px;
        font-weight: bold;
    }
</style>
