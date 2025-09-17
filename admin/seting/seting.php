<?php
session_start();
require_once '../../includes/db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Ambil data guru berdasarkan sesi login
$id_guru = $_SESSION['id_guru'];
$sql = "SELECT * FROM guru WHERE id_guru = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_guru]);
$guru = $stmt->fetch(PDO::FETCH_ASSOC);

// Proses Update Profil
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_guru = $_POST['nama_guru'];
    $email_guru = $_POST['email_guru'];
    $password_guru = $_POST['password_guru'];
    $image = $_FILES['image']['name'];
    $logo_universitas = $_FILES['logo_universitas']['name'];

    // Cek apakah email sudah digunakan oleh guru lain
    $stmt = $pdo->prepare("SELECT id_guru FROM guru WHERE email_guru = ? AND id_guru != ?");
    $stmt->execute([$email_guru, $id_guru]);
    if ($stmt->rowCount() > 0) {
        $message = '<div class="alert alert-danger">Email sudah digunakan!</div>';
    } else {
        // Gunakan password lama jika tidak diisi
        if (empty($password_guru)) {
            $password_guru = $guru['password_guru'];
        }

        // Upload gambar profil jika ada
        if (!empty($image)) {
            $target_dir = "../../assets/uploads/gambar/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        } else {
            $image = $guru['image'];
        }

        // Upload logo universitas jika ada
        if (!empty($logo_universitas)) {
            $target_dir = "../../assets/uploads/gambar/";
            $target_file = $target_dir . basename($_FILES["logo_universitas"]["name"]);
            move_uploaded_file($_FILES["logo_universitas"]["tmp_name"], $target_file);
        } else {
            $logo_universitas = $guru['logo_universitas'];
        }

        // Update data guru tanpa hashing password
        $sql_update = "UPDATE guru SET nama_guru = ?, email_guru = ?, password_guru = ?, image = ?, logo_universitas = ? WHERE id_guru = ?";
        $stmt = $pdo->prepare($sql_update);
        $result = $stmt->execute([$nama_guru, $email_guru, $password_guru, $image, $logo_universitas, $id_guru]);

        if ($result) {
            $message = '<div class="alert alert-success">Profil berhasil diperbarui!</div>';
            $_SESSION['nama_guru'] = $nama_guru; // Perbarui sesi

            // Redirect ke list_jenjang.php setelah sukses
            header("Location: list_jenjang.php");
            exit(); // Hentikan eksekusi agar tidak ada output lain sebelum redirect
        } else {
            $message = '<div class="alert alert-danger">Gagal memperbarui profil!</div>';
        }
    }
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <div class="content-container">
        <h1 class="content-title">Update Profil</h1>

        <form class="content-form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Nama Guru:</label>
                <input type="text" name="nama_guru" class="form-input" value="<?php echo htmlspecialchars($guru['nama_guru']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email:</label>
                <input type="email" name="email_guru" class="form-input" value="<?php echo htmlspecialchars($guru['email_guru']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah):</label>
                <input type="password" name="password_guru" class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Foto Profil:</label>
                <input type="file" name="image" class="form-input">
                <?php if ($guru['image']) : ?>
                    <br><img src="../../assets/uploads/gambar/<?php echo $guru['image']; ?>" alt="Foto Profil" width="100">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label class="form-label">Logo Universitas:</label>
                <input type="file" name="logo_universitas" class="form-input">
                <?php if ($guru['logo_universitas']) : ?>
                    <br><img src="../../assets/uploads/gambar/<?php echo $guru['logo_universitas']; ?>" alt="Logo Universitas" width="100">
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Update Profil</button>
                <a href="list_jenjang.php" class="form-button form-button-cancel">Batal</a>
            </div>
        </form>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>
