<?php
session_start();

// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Include koneksi database
require '../../includes/db.php';

// Ambil data jenjang pendidikan dari database
$sql_jenjang = "SELECT jp.id_jenjang, g.nama_guru, jp.jenjang, jp.institusi, jp.jurusan, jp.tahun_lulus, jp.nim 
                FROM jenjang_pendidikan jp
                LEFT JOIN guru g ON jp.id_guru = g.id_guru
                ORDER BY jp.tahun_lulus DESC";
$stmt_jenjang = $pdo->query($sql_jenjang);
$jenjang_list = $stmt_jenjang->fetchAll(PDO::FETCH_ASSOC);

// Pesan status (jika ada)
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = '<div class="alert alert-success">Data jenjang berhasil ditambahkan/diubah/dihapus!</div>';
    } elseif ($_GET['status'] === 'error') {
        $message = '<div class="alert alert-error">Gagal melakukan operasi.</div>';
    }
}
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Hapus pesan agar hanya tampil sekali
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <div class="content-container">
        <h1 class="content-title">Daftar Jenjang Pendidikan</h1>

        <?php echo $message; ?>

        <!-- Tombol Tambah Jenjang -->
        <div class="form-actions">
            <a href="pendidikan.php" class="form-button form-button-submit">Tambah Jenjang</a>
            <a href="seting.php" class="form-button form-button-submit">Perbarui Profile</a>
        </div>

        <!-- Tabel Daftar Jenjang Pendidikan -->
        <table class="data-table">
            <thead>
                <tr class="table-header-row">
                    <th class="table-header">No</th>
                    <th class="table-header">Nama Guru</th>
                    <th class="table-header">Jenjang</th>
                    <th class="table-header">Institusi</th>
                    <th class="table-header">Jurusan</th>
                    <th class="table-header">Tahun Lulus</th>
                    <th class="table-header">NIM</th>
                    <th class="table-header">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($jenjang_list) > 0): ?>
                    <?php $no = 1; foreach ($jenjang_list as $jenjang): ?>
                        <tr class="table-row">
                            <td class="table-data"><?php echo $no++; ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($jenjang['nama_guru']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($jenjang['jenjang']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($jenjang['institusi']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($jenjang['jurusan']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($jenjang['tahun_lulus']); ?></td>
                            <td class="table-data"><?php echo htmlspecialchars($jenjang['nim']); ?></td>
                            <td class="table-data table-actions">
                                <a href="edit_jenjang.php?id=<?php echo $jenjang['id_jenjang']; ?>" class="btn btn-edit">Edit</a>
                                <a href="hapus_jenjang.php?id=<?php echo $jenjang['id_jenjang']; ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr class="table-row">
                        <td class="table-data" colspan="8" class="text-center">Tidak ada data.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>
