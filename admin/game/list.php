<?php
session_start();
// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../../includes/db.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['id_guru'])) {
    header("Location: ../index.php");
    exit();
}

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    try {
        // Hapus data dari database
        $query = "DELETE FROM organ_data WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);

        // Set pesan alert untuk sukses
        $_SESSION['alert'] = '<div class="alert alert-success">Data berhasil dihapus!</div>';
        header("Location: list.php");
        exit();
    } catch (PDOException $e) {
        // Set pesan alert untuk error
        $_SESSION['alert'] = '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        header("Location: list.php");
        exit();
    }
}

// Pagination Setup
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Ambil total data untuk pagination
$total_query = "SELECT COUNT(*) FROM organ_data";
$total_stmt = $pdo->query($total_query);
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Ambil Data dengan Limit
try {
    $query = "SELECT * FROM organ_data LIMIT :start, :limit";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Error: " . $e->getMessage();
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
<main class="content">
    <div class="content-container">
        <h1 class="content-title">List Game Organ Pernapasan</h1>
        <?php if (!empty($message)): ?>
            <div class="content-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <a href="add.php" class="btn btn-tambah">Tambah Data</a>
        <?php
        session_start();
        if (isset($_SESSION['alert'])) {
            echo $_SESSION['alert']; // Tampilkan alert
            unset($_SESSION['alert']); // Hapus setelah ditampilkan
        }
        ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th class="table-header">No</th>
                    <th class="table-header">Nama Organ</th>
                    <th class="table-header">Deskripsi</th>
                    <th class="table-header">Gambar</th>
                    <th class="table-header">Kata Acak</th>
                    <th class="table-header">Kata Benar</th>
                    <th class="table-header">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $index => $row): ?>
                        <tr class="table-row">
                            <td class="table-data"><?php echo $start + $index + 1; ?></td>
                            <td class="table-data"><?php echo $row['organ_name']; ?></td>
                            <td class="table-data"><?php echo $row['description']; ?></td>
                            <td class="table-data">
                                <?php if (!empty($row['image'])): ?>
                                    <a href="../../assets/uploads/gambar/<?php echo $row['image']; ?>" target="_blank" class="view-gambar-link">
                                        <i class="bi bi-eye"></i> Lihat Gambar
                                    </a>
                                <?php else: ?>
                                    <span class="table-no-image">Tidak Ada Gambar</span>
                                <?php endif; ?>
                            </td>
                            <td class="table-data"><?php echo $row['scrambled_word']; ?></td>
                            <td class="table-data"><?php echo $row['correct_word']; ?></td>
                            <td class="table-data table-actions">
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                                <a href="list.php?hapus=<?php echo $row['id']; ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="table-row">
                        <td colspan="7" class="table-data table-no-data">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn-prev">« Previous</a>
            <?php endif; ?>

            <span>Halaman <?= $page ?> dari <?= $total_pages ?></span>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn-next">Next »</a>
            <?php endif; ?>
        </div>
    </div>
</main>
</html>

<style>
    .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    gap: 10px;
}

.pagination a {
    text-decoration: none;
    padding: 8px 15px;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.2s;
}

.pagination a:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.pagination span {
    font-size: 16px;
    font-weight: bold;
    color: #333;
}

.btn-prev,
.btn-next {
    padding: 8px 12px;
    border-radius: 5px;
    background-color: #28a745;
    color: white;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-prev:hover,
.btn-next:hover {
    background-color: #218838;
    transform: scale(1.05);
}

/* Responsif */
@media (max-width: 600px) {
    .pagination {
        flex-direction: column;
        gap: 5px;
    }
    
    .pagination a,
    .btn-prev,
    .btn-next {
        width: 100%;
        text-align: center;
    }
}

</style>
