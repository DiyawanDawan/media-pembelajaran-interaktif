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

$message = "";

// Proses Hapus Data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    try {
        // Hapus Data dari Database
        $query = "DELETE FROM organ_data WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $id]);

        $message = "Data berhasil dihapus!";
        header("Location: list.php");
        exit();
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Ambil Data dari Database
try {
    $query = "SELECT * FROM organ_data";
    $stmt = $pdo->query($query);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = "Error: " . $e->getMessage();
}
?>
<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
<main class="content">
    <div class="content-container">
        <h1 class="content-title">List Data Organ Pernapasan</h1>
        <?php if (!empty($message)): ?>
            <div class="content-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Tombol Tambah Data -->
        <a href="add.php" class="btn btn-tambah">Tambah Data</a>

        <!-- Tabel Data -->
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
                            <td class="table-data"><?php echo $index + 1; ?></td>
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
                                <!-- Tombol Edit -->
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                                <!-- Tombol Hapus -->
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
    </div>
</main>
</html>