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

// Proses Form Tambah Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_guru = $_SESSION['id_guru'];
    $organ_name = $_POST['organ_name'];
    $description = $_POST['description'];
    $scrambled_word = $_POST['scrambled_word'];
    $correct_word = $_POST['correct_word'];

    try {
        // Handle Upload Gambar
        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($file_ext, $allowed_ext)) {
                $new_file_name = uniqid('organ_', true) . '.' . $file_ext;
                $upload_path = '../../assets/uploads/gambar/' . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $image = $new_file_name;
                } else {
                    $message = "Gagal mengunggah gambar.";
                }
            } else {
                $message = "Format file tidak didukung.";
            }
        }

        // Simpan Data ke Database
        $query = "INSERT INTO organ_data (id_guru, organ_name, description, image, scrambled_word, correct_word) 
                  VALUES (:id_guru, :organ_name, :description, :image, :scrambled_word, :correct_word)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':id_guru' => $id_guru,
            ':organ_name' => $organ_name,
            ':description' => $description,
            ':image' => $image,
            ':scrambled_word' => $scrambled_word,
            ':correct_word' => $correct_word
        ]);

        $message = "Data berhasil ditambahkan!";
        header("Location: list.php");
        exit();
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
<main class="content">
    <div class="content-container">
        <h1 class="content-title">Tambah Data Organ Pernapasan</h1>
        <?php if (!empty($message)): ?>
            <div class="content-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form class="content-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Nama Organ:</label>
                <input type="text" name="organ_name" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi:</label>
                <textarea name="description" class="form-textarea" required></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Gambar Organ:</label>
                <input type="file" name="image" class="form-file">
            </div>

            <div class="form-group">
                <label class="form-label">Kata Acak:</label>
                <input type="text" name="scrambled_word" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kata Benar:</label>
                <input type="text" name="correct_word" class="form-input" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Simpan</button>
                <a href="list.php" class="form-button form-button-cancel">Kembali</a>
            </div>
        </form>
    </div>
</main>
</body>

</html>