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

// Ambil ID data yang akan diedit
if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit();
}

$id = $_GET['id'];

// Ambil data dari database berdasarkan ID
try {
    $query = "SELECT * FROM organ_data WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        header("Location: list.php");
        exit();
    }
} catch (PDOException $e) {
    $message = "Error: " . $e->getMessage();
}

// Proses Form Edit Data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $organ_name = $_POST['organ_name'];
    $description = $_POST['description'];
    $scrambled_word = $_POST['scrambled_word'];
    $correct_word = $_POST['correct_word'];

    try {
        // Handle Upload Gambar
        $image = $data['image']; // Gunakan gambar lama jika tidak diubah
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

        // Update Data di Database
        $query = "UPDATE organ_data 
                  SET organ_name = :organ_name, description = :description, image = :image, 
                      scrambled_word = :scrambled_word, correct_word = :correct_word 
                  WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':organ_name' => $organ_name,
            ':description' => $description,
            ':image' => $image,
            ':scrambled_word' => $scrambled_word,
            ':correct_word' => $correct_word,
            ':id' => $id
        ]);

        $message = "Data berhasil diupdate!";
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
        <h1 class="content-title">Edit Data Organ Pernapasan</h1>
        <?php if (!empty($message)): ?>
            <div class="content-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form class="content-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Nama Organ:</label>
                <input type="text" name="organ_name" class="form-input" value="<?php echo $data['organ_name']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi:</label>
                <textarea name="description" class="form-textarea" required><?php echo $data['description']; ?></textarea>
            </div>

            <!-- Input Gambar Organ -->
            <div class="form-group">
                <label class="form-label">Gambar Organ:</label>
                <input type="file" name="image" class="form-file">
                <?php if (!empty($data['image'])): ?>
                    <div class="form-image-preview">
                        <p class="form-info">Gambar saat ini:</p>
                        <a href="../../assets/uploads/gambar/<?php echo $data['image']; ?>" target="_blank" class="-gambar-link">
                            <i class="bi bi-eye"></i> Lihat Gambar
                        </a>
                    </div>
                <?php else: ?>
                    <p class="form-no-image">Tidak Ada Gambar</p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Kata Acak:</label>
                <input type="text" name="scrambled_word" class="form-input" value="<?php echo $data['scrambled_word']; ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Kata Benar:</label>
                <input type="text" name="correct_word" class="form-input" value="<?php echo $data['correct_word']; ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Simpan Perubahan</button>
                <a href="list.php" class="form-button form-button-cancel">Kembali</a>
            </div>
        </form>
    </div>
</main>
<?php include_once('../components/footer.php'); ?>
