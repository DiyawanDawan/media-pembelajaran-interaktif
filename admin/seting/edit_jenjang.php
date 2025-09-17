<?php
session_start();

// Pastikan admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require '../../includes/db.php';

// Cek apakah ID jenjang diberikan
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: list_jenjang.php?status=error");
    exit;
}

$id_jenjang = $_GET['id'];

// Ambil data jenjang dari database berdasarkan ID
$sql = "SELECT * FROM jenjang_pendidikan WHERE id_jenjang = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_jenjang]);
$jenjang = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika data tidak ditemukan
if (!$jenjang) {
    header("Location: list_jenjang.php?status=error");
    exit;
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenjang_baru = $_POST['jenjang'];
    $institusi = $_POST['institusi'];
    $jurusan = $_POST['jurusan'];
    $tahun_lulus = $_POST['tahun_lulus'];
    $nim = $_POST['nim'] ?? null; // NIM hanya diisi jika ada

    // Update data ke database
    $sql_update = "UPDATE jenjang_pendidikan 
                   SET jenjang = :jenjang, institusi = :institusi, jurusan = :jurusan, 
                       tahun_lulus = :tahun_lulus, nim = :nim 
                   WHERE id_jenjang = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $update = $stmt_update->execute([
        'jenjang' => $jenjang_baru,
        'institusi' => $institusi,
        'jurusan' => $jurusan,
        'tahun_lulus' => $tahun_lulus,
        'nim' => ($jenjang_baru === 'S1') ? $nim : null, // NIM hanya untuk S1
        'id' => $id_jenjang
    ]);

    if ($update) {
        header("Location: list_jenjang.php?status=success");
        exit;
    } else {
        $error_message = "Gagal memperbarui data!";
    }
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <div class="content-container">
        <h1 class="content-title">Edit Jenjang Pendidikan</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="form">
            <div class="form-group">
                <label class="form-label">Jenjang</label>
                <select name="jenjang" id="jenjang" class="form-input" required onchange="toggleNIM()">
                    <option value="SD" <?= ($jenjang['jenjang'] == 'SD') ? 'selected' : '' ?>>SD</option>
                    <option value="SMP" <?= ($jenjang['jenjang'] == 'SMP') ? 'selected' : '' ?>>SMP</option>
                    <option value="SMA" <?= ($jenjang['jenjang'] == 'SMA') ? 'selected' : '' ?>>SMA</option>
                    <option value="SMK" <?= ($jenjang['jenjang'] == 'SMK') ? 'selected' : '' ?>>SMK</option>
                    <option value="D3" <?= ($jenjang['jenjang'] == 'D3') ? 'selected' : '' ?>>D3</option>
                    <option value="S1" <?= ($jenjang['jenjang'] == 'S1') ? 'selected' : '' ?>>S1</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Institusi</label>
                <input type="text" name="institusi" class="form-input" value="<?php echo htmlspecialchars($jenjang['institusi']); ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Jurusan</label>
                <input type="text" name="jurusan" class="form-input" value="<?php echo htmlspecialchars($jenjang['jurusan']); ?>" required>
            </div>
            <div class="form-group">
    <label class="form-label">Tahun Lulus:</label>
    <select name="tahun_lulus" class="form-select" required>
        <option value="">Pilih Tahun</option>
        <?php
        $tahun_sekarang = date('Y');
        for ($tahun = $tahun_sekarang; $tahun >= 1900; $tahun--) {
            echo "<option value='$tahun'>$tahun</option>";
        }
        ?>
    </select>
</div>
            <div class="form-group" id="nim-group" style="display: <?= ($jenjang['jenjang'] == 'S1') ? 'block' : 'none' ?>;">
                <label class="form-label">NIM</label>
                <input type="text" name="nim" id="nim" class="form-input" value="<?php echo htmlspecialchars($jenjang['nim'] ?? ''); ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Simpan</button>
                <a href="list_jenjang.php" class="form-button form-button-cancel">Batal</a>
            </div>
        </form>
    </div>
</main>

<script>
    function toggleNIM() {
        let jenjang = document.getElementById("jenjang").value;
        let nimGroup = document.getElementById("nim-group");
        let nimInput = document.getElementById("nim");

        if (jenjang === "S1") {
            nimGroup.style.display = "block";
            nimInput.setAttribute("required", "required");
        } else {
            nimGroup.style.display = "none";
            nimInput.removeAttribute("required");
            nimInput.value = ""; // Hapus nilai NIM jika bukan S1
        }
    }
</script>

<?php include_once('../components/footer.php'); ?>
