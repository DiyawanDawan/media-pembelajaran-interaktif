<?php
session_start();

// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../../includes/db.php';

// Ambil ID materi dari parameter URL
if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}
$id_materi = $_GET['id'];

// Ambil data materi dari database
$sql_materi = "SELECT * FROM materi WHERE id_materi = :id_materi";
$stmt_materi = $pdo->prepare($sql_materi);
$stmt_materi->execute([':id_materi' => $id_materi]);
$materi = $stmt_materi->fetch(PDO::FETCH_ASSOC);

// Jika data materi tidak ditemukan, redirect ke list.php
if (!$materi) {
    header("Location: list.php");
    exit;
}

// Fungsi untuk upload file
function uploadFile($file, $target_dir) {
    $target_file = $target_dir . basename($file["name"]);
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $file_size = $file["size"];

    // Validasi file
    if ($file_size > 5000000) { // Maksimal 5MB
        die("File terlalu besar. Maksimal 5MB.");
    }

    // Upload file
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        die("Gagal mengupload file.");
    }
}

// Proses form jika ada data yang dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $judul_materi = $_POST['judul_materi'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];
    $link_materi = $_POST['link_materi'];

    // Update data materi
    $sql_update_materi = "UPDATE materi 
                         SET judul_materi = :judul_materi, 
                             deskripsi = :deskripsi, 
                             status = :status,
                             link_materi = :link_materi
                         WHERE id_materi = :id_materi";
    $stmt_update_materi = $pdo->prepare($sql_update_materi);
    $stmt_update_materi->execute([
        ':judul_materi' => $judul_materi,
        ':deskripsi' => $deskripsi,
        ':status' => $status,
        ':link_materi' => $link_materi,
        ':id_materi' => $id_materi
    ]);

    // Jika ada file gambar baru diupload
    if ($_FILES['gambar']['size'] > 0) {
        $gambar_path = uploadFile($_FILES['gambar'], "../../assets/uploads/gambar/");
        
        // Hapus gambar lama jika ada
        if ($materi['gambar'] && file_exists($materi['gambar'])) {
            unlink($materi['gambar']);
        }
        
        $sql_update_gambar = "UPDATE materi SET gambar = :gambar WHERE id_materi = :id_materi";
        $stmt_update_gambar = $pdo->prepare($sql_update_gambar);
        $stmt_update_gambar->execute([
            ':gambar' => $gambar_path,
            ':id_materi' => $id_materi
        ]);
    }

    // Set session pesan sukses
    $_SESSION['success_message'] = "Materi berhasil diperbarui!";

    // Redirect ke halaman list.php
    header("Location: list.php");
    exit;
}
?>
<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
<main class="content">
    <div class="content-container">
        <h1 class="content-title">Edit Materi</h1>
        <form class="content-form" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul_materi" class="form-label">Judul Materi:</label>
                <input type="text" id="judul_materi" name="judul_materi" class="form-input" 
                       value="<?= htmlspecialchars($materi['judul_materi']) ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi" class="form-label">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" class="form-textarea" required><?= 
                    htmlspecialchars($materi['deskripsi']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="link_materi" class="form-label">Link Materi:</label>
                <input type="url" id="link_materi" name="link_materi" class="form-input" 
                       value="<?= htmlspecialchars($materi['link_materi']) ?>" 
                       placeholder="https://example.com">
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status:</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="draft" <?= $materi['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="published" <?= $materi['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                    <option value="archived" <?= $materi['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                </select>
            </div>

            <div class="form-group">
                <label for="gambar" class="form-label">Gambar Materi (Opsional):</label>
                <input type="file" id="gambar" name="gambar" class="form-file" accept="image/*">
                <?php if ($materi['gambar']): ?>
                    <p class="form-info">Gambar saat ini: 
                        <a href="<?= htmlspecialchars($materi['gambar']) ?>" target="_blank" class="form-link">
                            Lihat Gambar
                        </a>
                        <br>
                        <img src="<?= htmlspecialchars($materi['gambar']) ?>" alt="Gambar Materi" class="form-preview">
                    </p>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Simpan Perubahan</button>
                <button type="button" class="form-button form-button-cancel" 
                        onclick="window.location.href='list.php'">Kembali ke List</button>
            </div>
        </form>
    </div>
</main>
<?php include_once('../components/footer.php')?>