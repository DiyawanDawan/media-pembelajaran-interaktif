<?php
session_start();

// Jika admin belum login, redirect ke halaman login
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
// header("Location: index.php");
// exit;
// }

// Include koneksi database
require '../../includes/db.php';

// Ambil data guru dari database
$stmt_guru = $pdo->query("SELECT * FROM Guru");
$guru = $stmt_guru->fetchAll();
?>

<?php include_once('../components/header.php') ?>
<?php include_once('../components/nav.php') ?>

<main class="content">
    <h1>Biodata Guru</h1>
    <div class="card-container">
        <?php foreach ($guru as $g): ?>
            <div class="card">
                <div class="card-header">
                    <img src="https://images.unsplash.com/photo-1734630378523-c6735d798820?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwzfHx8ZW58MHx8fHx8" alt="Profile Image" class="profile-img">
                    <h2><?= htmlspecialchars($g['nama_guru']) ?></h2>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= htmlspecialchars($g['email_guru']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Alamat</span>
                        <span class="info-value">Jl. Pendidikan No. 123, Kota Pendidikan</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Mata Pelajaran</span>
                        <span class="info-value">Matematika, Fisika</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Materi</span>
                        <span class="info-value">Aljabar, Trigonometri, Mekanika</span>
                    </div>
                </div>
                <div class="card-footer">
                    <img src="https://images.unsplash.com/photo-1734630378523-c6735d798820?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxmZWF0dXJlZC1waG90b3MtZmVlZHwzfHx8ZW58MHx8fHx8" alt="University Logo" class="university-logo">
                    <span>Universitas Pendidikan Indonesia</span>
                </div>
            </div>
              <!-- Corak Pinggiran -->
              <div class="card-border"></div>
        <?php endforeach; ?>
    </div>
</main>

<script src="script/script.js"></script>
</body>
</html>