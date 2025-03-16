<?php
session_start();

// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../../index.php");
    exit;
}

// Include koneksi database
require '../../includes/db.php';

// Ambil ID materi dari parameter URL
if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}
$id_materi = $_GET['id'];

// Ambil data materi dan audiobook dari database
$sql_materi = "SELECT MATERI.*, AUDIOBOOK.file_audio 
               FROM MATERI 
               LEFT JOIN AUDIOBOOK ON MATERI.id_materi = AUDIOBOOK.id_materi 
               WHERE MATERI.id_materi = :id_materi";
$stmt_materi = $pdo->prepare($sql_materi);
$stmt_materi->execute([':id_materi' => $id_materi]);
$materi = $stmt_materi->fetch(PDO::FETCH_ASSOC);

// Jika data materi tidak ditemukan, redirect ke list.php
if (!$materi) {
    header("Location: list.php");
    exit;
}

// Fungsi untuk upload file
function uploadFile($file, $target_dir)
{
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

    // Update data materi
    $sql_update_materi = "UPDATE MATERI 
                          SET judul_materi = :judul_materi, 
                              deskripsi = :deskripsi, 
                              status = :status 
                          WHERE id_materi = :id_materi";
    $stmt_update_materi = $pdo->prepare($sql_update_materi);
    $stmt_update_materi->execute([
        ':judul_materi' => $judul_materi,
        ':deskripsi' => $deskripsi,
        ':status' => $status,
        ':id_materi' => $id_materi
    ]);

    // Jika ada file gambar baru diupload
    if ($_FILES['gambar']['size'] > 0) {
        $gambar_path = uploadFile($_FILES['gambar'], "../../assets/uploads/gambar/");
        $sql_update_gambar = "UPDATE MATERI SET gambar = :gambar WHERE id_materi = :id_materi";
        $stmt_update_gambar = $pdo->prepare($sql_update_gambar);
        $stmt_update_gambar->execute([
            ':gambar' => $gambar_path,
            ':id_materi' => $id_materi
        ]);
    }

    // Jika ada file audio baru diupload
    if ($_FILES['file_audio']['size'] > 0) {
        $audio_path = uploadFile($_FILES['file_audio'], "../../assets/uploads/audio/");
        // Cek apakah audiobook sudah ada
        $sql_check_audio = "SELECT * FROM AUDIOBOOK WHERE id_materi = :id_materi";
        $stmt_check_audio = $pdo->prepare($sql_check_audio);
        $stmt_check_audio->execute([':id_materi' => $id_materi]);
        $audiobook = $stmt_check_audio->fetch(PDO::FETCH_ASSOC);

        if ($audiobook) {
            // Update file audio yang sudah ada
            $sql_update_audio = "UPDATE AUDIOBOOK SET file_audio = :file_audio WHERE id_materi = :id_materi";
            $stmt_update_audio = $pdo->prepare($sql_update_audio);
            $stmt_update_audio->execute([
                ':file_audio' => $audio_path,
                ':id_materi' => $id_materi
            ]);
        } else {
            // Tambahkan file audio baru
            $sql_insert_audio = "INSERT INTO AUDIOBOOK (file_audio, id_materi) VALUES (:file_audio, :id_materi)";
            $stmt_insert_audio = $pdo->prepare($sql_insert_audio);
            $stmt_insert_audio->execute([
                ':file_audio' => $audio_path,
                ':id_materi' => $id_materi
            ]);
        }
    }

    // Set session pesan sukses
    $_SESSION['success_message'] = "Materi dan Audiobook berhasil diperbarui!";

    // Redirect ke halaman list.php
    header("Location: list.php");
    exit;
}
?>
<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
<main class="content">
    <div class="content-container">
        <h1 class="content-title">Edit Materi dan Audiobook</h1>
        <form class="content-form" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judul_materi" class="form-label">Judul Materi:</label>
                <input type="text" id="judul_materi" name="judul_materi" class="form-input" value="<?= htmlspecialchars($materi['judul_materi']) ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi" class="form-label">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" class="form-textarea" required><?= htmlspecialchars($materi['deskripsi']) ?></textarea>
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
                <label for="gambar" class="form-label">Gambar Materi:</label>
                <input type="file" id="gambar" name="gambar" class="form-file" accept="image/*">
                <?php if ($materi['gambar']): ?>
                    <p class="form-info">Gambar saat ini: <a href="../../assets/uploads/gambar/<?= htmlspecialchars(basename($materi['gambar'])) ?>" target="_blank" class="form-link">Lihat Gambar</a></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="file_audio" class="form-label">File Audiobook:</label>
                <input type="file" id="file_audio" name="file_audio" class="form-file" accept="audio/*">
                <?php if ($materi['file_audio']): ?>
                    <p class="form-info">File audio saat ini:
                        <audio controls class="form-audio">
                            <source src="../../assets/uploads/audio/<?= htmlspecialchars(basename($materi['file_audio'])) ?>" type="audio/mpeg">
                            Browser Anda tidak mendukung tag audio.
                        </audio>
                    </p>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Simpan Perubahan</button>
                <button type="button" class="form-button form-button-cancel" onclick="window.location.href='list.php'">Kembali ke List</button>
            </div>
        </form>
    </div>
</main>
<?php include_once('../components/footer.php')?>