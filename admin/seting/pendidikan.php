<?php
session_start();
// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../../includes/db.php';

// Ambil daftar guru untuk dropdown
$sql_guru = "SELECT id_guru, nama_guru FROM guru";
$stmt_guru = $pdo->query($sql_guru);
$guru_list = $stmt_guru->fetchAll(PDO::FETCH_ASSOC);

// Pesan status jika ada
$message = '';
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = '<div class="alert alert-success">Data jenjang pendidikan berhasil ditambahkan!</div>';
    } elseif ($_GET['status'] === 'error') {
        $message = '<div class="alert alert-danger">Gagal menambahkan data jenjang pendidikan.</div>';
    }
}
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>

<main class="content">
    <div class="content-container">
        <h1 class="content-title">Tambah Jenjang Pendidikan</h1>

        <?php echo $message; ?>

        <form class="content-form" method="POST" action="proses_tambah_jenjang.php">
            <!-- Pilih Guru -->
            <div class="form-group">
                <label class="form-label">Nama Guru:</label>
                <select name="id_guru" class="form-select" required>
                    <option value="">Pilih Guru</option>
                    <?php foreach ($guru_list as $guru): ?>
                        <option value="<?php echo $guru['id_guru']; ?>"><?php echo htmlspecialchars($guru['nama_guru']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Jenjang (Dropdown) -->
            <div class="form-group">
                <label class="form-label">Jenjang:</label>
                <select name="jenjang" id="jenjang" class="form-select" required>
                    <option value="">Pilih Jenjang</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="SMA">SMA</option>
                    <option value="SMK">SMK</option>
                    <option value="D3">D3</option>
                    <option value="S1">S1</option>
                    <option value="S2">S2</option>
                    <option value="S3">S3</option>
                </select>
            </div>

            <!-- Institusi -->
            <div class="form-group">
                <label class="form-label">Institusi:</label>
                <input type="text" name="institusi" class="form-input" required>
            </div>

            <!-- Jurusan -->
            <div class="form-group">
                <label class="form-label">Jurusan:</label>
                <input type="text" name="jurusan" class="form-input" required>
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



            <!-- NIM (Hanya untuk S1, disembunyikan default) -->
            <div class="form-group" id="nim-group" style="display: none;">
                <label class="form-label">NIM:</label>
                <input type="text" name="nim" id="nim" class="form-input">
            </div>

            <!-- Tombol Aksi -->
            <div class="form-actions">
                <button type="submit" class="form-button form-button-submit">Simpan</button>
                <a href="list_jenjang.php" class="form-button form-button-cancel">Kembali</a>
            </div>
        </form>
    </div>
</main>

<?php include_once('../components/footer.php'); ?>

<!-- JavaScript untuk Menangani Tampilan NIM -->
<script>
    document.getElementById("jenjang").addEventListener("change", function() {
        var nimGroup = document.getElementById("nim-group");
        var jenjangValue = this.value;

        if (jenjangValue === "S1") {
            nimGroup.style.display = "block";
            document.getElementById("nim").setAttribute("required", "true");
        } else {
            nimGroup.style.display = "none";
            document.getElementById("nim").removeAttribute("required");
        }
    });
</script>