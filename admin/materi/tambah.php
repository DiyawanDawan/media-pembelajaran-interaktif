<?php
session_start();

// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

// Include koneksi database
require '../../includes/db.php';

// Ambil id_guru dari session
$id_guru = $_SESSION['id_guru'];

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
    $link_materi = $_POST['link_materi']; // Ambil link materi dari form

    // Upload gambar materi (jika ada)
    $gambar_path = null;
    if (!empty($_FILES['gambar']['name'])) {
        $gambar_path = uploadFile($_FILES['gambar'], "../../assets/uploads/gambar/");
    }

    // Query untuk insert materi
    $sql_materi = "INSERT INTO materi (judul_materi, deskripsi, id_guru, status, gambar, link_materi) 
                  VALUES (:judul_materi, :deskripsi, :id_guru, :status, :gambar, :link_materi)";
    $stmt_materi = $pdo->prepare($sql_materi);
    $stmt_materi->execute([
        ':judul_materi' => $judul_materi,
        ':deskripsi' => $deskripsi,
        ':id_guru' => $id_guru,
        ':status' => $status,
        ':gambar' => $gambar_path,
        ':link_materi' => $link_materi
    ]);

    echo "<script>alert('Materi berhasil ditambahkan!'); window.location.href='../dashboard/dashboard.php';</script>";
}
?>
<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
<main class="content">
    <div class="content-container">
    <h1 class="content-title">Tambah Materi</h1>
    <form class="content-form" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="judul_materi" class="form-label">Judul Materi:</label>
            <input type="text" id="judul_materi" name="judul_materi" class="form-input" required>
        </div>

        <div class="form-group">
            <label for="deskripsi" class="form-label">Deskripsi:</label>
            <textarea id="deskripsi" name="deskripsi" class="form-textarea" required></textarea>
        </div>

        <div class="form-group">
            <label for="link_materi" class="form-label">Link Materi:</label>
            <input type="url" id="link_materi" name="link_materi" class="form-input" placeholder="https://example.com">
        </div>

        <div class="form-group">
            <label for="status" class="form-label">Status:</label>
            <select id="status" name="status" class="form-select" required>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
                <option value="archived">Archived</option>
            </select>
        </div>

        <div class="form-group">
            <label for="gambar" class="form-label">Gambar Materi (Opsional):</label>
            <input type="file" id="gambar" name="gambar" class="form-file" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit" class="form-button form-button-submit">Tambah</button>
            <button type="button" class="form-button form-button-cancel" onclick="window.location.href='list.php'">Kembali ke List</button>
        </div>
    </form>
    </div>
</main>
</body>
</html>