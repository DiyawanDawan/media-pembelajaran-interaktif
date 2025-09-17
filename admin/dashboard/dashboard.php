
<?php
session_start();
// Jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../../includes/db.php';
// Tentukan jumlah data per halaman
$limit = 10;

// Ambil halaman saat ini dari URL, default ke halaman 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Pastikan halaman tidak kurang dari 1

// Hitung offset
$offset = ($page - 1) * $limit;

// Ambil total data untuk menghitung jumlah halaman
$total_sql = "SELECT COUNT(*) FROM materi";
$total_stmt = $pdo->query($total_sql);
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit)
;
// Ambil data materi dan audiobook dengan batasan halaman
$sql_materi = "SELECT materi.*, audiobook.file_audio 
               FROM materi 
               LEFT JOIN audiobook ON materi.id_materi = audiobook.id_materi
               LIMIT :limit OFFSET :offset";

$stmt_materi = $pdo->prepare($sql_materi);
$stmt_materi->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt_materi->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt_materi->execute();
$materi = $stmt_materi->fetchAll(PDO::FETCH_ASSOC);


?>

<style>
    /* Link and Image Styles */
.link-materi, .gambar-preview {
    color: #007bff;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.link-materi:hover, .gambar-preview:hover {
    text-decoration: underline;
}

</style>
<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
<main class="content">
   <div class=" content-container">
    <h1 class="content-title">Dashboard Admin</h1>

    <li class="btn"><a href="../materi/tambah.php" class="btn btn-tambah">Tambah Materi dan Audiobook</a></li>

    

    <h2 class="content-subtitle">Daftar Materi dan Audiobook</h2>
    <!-- todo tampillkan psesnya -->
    <?php 
        // Cek jika ada alert di session
        if (isset($_SESSION['alert'])) {
            echo $_SESSION['alert'];
            unset($_SESSION['alert']); // Hapus alert setelah ditampilkan
        }
        
    ?>
    <table class="data-table">
        <thead>
            <tr class="table-header-row">
                <th class="table-header">No</th>
                <th class="table-header">Judul Materi</th>
                <th class="table-header">Deskripsi</th>
                <th class="table-header">Link Materi</th>
                <th class="table-header">Status</th>
                <th class="table-header">Gambar Materi</th>
                <th class="table-header">File Audiobook</th>
                <th class="table-header">Aksi</th>
            </tr>
        </thead>
      <tbody>
    <?php $no = $offset + 1; foreach ($materi as $m): ?>
        <tr class="table-row">
            <td class="table-data"><?= $no++ ?></td>
            <td class="table-data"><?= htmlspecialchars($m['judul_materi']) ?></td>
             <td class="table-data">
                <?php 
                $fullDeskripsi = $m['deskripsi']; // Ambil teks asli
                $escapedFull = htmlspecialchars($fullDeskripsi, ENT_QUOTES, 'UTF-8'); // Escape kutipan agar aman di atribut HTML
                $words = str_word_count(strip_tags($fullDeskripsi), 2);
                $word_limit = 10;
                
                if (count($words) > $word_limit) {
                    $word_keys = array_keys($words);
                    $truncated = substr($fullDeskripsi, 0, $word_keys[$word_limit]) . '...';
                    echo '<span class="short-description">' . htmlspecialchars($truncated) . '</span>';
                    echo ' <a href="#" class="view-description" data-full="' . $escapedFull . '">View</a>';
                } else {
                    echo htmlspecialchars($fullDeskripsi);
                }
                ?>

            </td>
             <td>
                            <?php if ($m['link_materi']): ?>
                                <a href="<?= htmlspecialchars($m['link_materi']) ?>" target="_blank" class="link-materi">
                                    <i class="fas fa-external-link-alt"></i> Buka Link
                                </a>
                            <?php else: ?>
                                <span class="no-link">Tidak ada link</span>
                            <?php endif; ?>
                        </td>
            </td>
            <td class="table-data"><?= htmlspecialchars($m['status']) ?></td>
            <td class="table-data">
                <?php if ($m['gambar']): ?>
                    <a href="../../assets/uploads/gambar/<?= htmlspecialchars(basename($m['gambar'])) ?>" target="_blank" class="view-gambar-link">
                       Lihat
                    </a>
                <?php else: ?>
                    <span class="table-no-image">Tidak ada gambar</span>
                <?php endif; ?>
            </td>
            
            <td class="table-data">
                <?php if ($m['file_audio']): ?>
                    <audio controls class="table-audio">
                        <source src="../../assets/uploads/audio/<?= htmlspecialchars(basename($m['file_audio'])) ?>" type="audio/mpeg">
                        Browser Anda tidak mendukung tag audio.
                    </audio>
                <?php else: ?>
                    <span class="table-no-audio">Tidak ada audiobook</span>
                <?php endif; ?>
            </td>
            <td class="table-data table-actions">
                <a href="../materi/edit.php?id=<?= $m['id_materi'] ?>" class="btn btn-edit">Edit</a>
                <a href="../materi/hapus.php?id=<?= $m['id_materi'] ?>" class="btn btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data materi dan audiobook ini?')">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

    </table>
</div>
    <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" class="btn btn-prev">« Previous</a>
    <?php endif; ?>

    <span>Halaman <?= $page ?> dari <?= $total_pages ?></span>

    <?php if ($page < $total_pages): ?>
        <a href="?page=<?= $page + 1 ?>" class="btn btn-next">Next »</a>
    <?php endif; ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const viewLinks = document.querySelectorAll('.view-description');
    
    viewLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const shortDesc = this.previousElementSibling;
            const fullText = this.getAttribute('data-full');
            
            // Jika teks link adalah "hide", kembalikan ke tampilan singkat
            if (this.textContent.trim().toLowerCase() === 'hide') {
                let words = fullText.split(/\s+/);
                if (words.length > 10) {
                    shortDesc.textContent = words.slice(0, 10).join(' ') + '...';
                } else {
                    shortDesc.textContent = fullText;
                }
                this.textContent = 'View';
            } else {
                // Tampilkan deskripsi lengkap
                shortDesc.textContent = fullText;
                this.textContent = 'Hide';
            }
        });
    });
});
</script>
</main

<?php include_once('../components/footer.php') ?>


<style>
.view-description {
    font-size: 0.9rem;
    color: #007bff;
    text-decoration: underline;
    cursor: pointer;
    transition: color 0.3s ease, transform 0.3s ease;
}

.view-description:hover {
    color: #0056b3;
    transform: scale(1.05);
}

    .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    gap: 10px;
}

.pagination a {
    text-decoration: none;
    padding: 8px 15px;
    border-radius: 5px;
    background-color: #007bff;
    color: white;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.2s;
}

.pagination a:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.pagination span {
    font-size: 16px;
    font-weight: bold;
    color: #333;
}

.btn-prev,
.btn-next {
    padding: 8px 12px;
    border-radius: 5px;
    background-color: #28a745;
    color: white;
    font-weight: bold;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-prev:hover,
.btn-next:hover {
    background-color: #218838;
    transform: scale(1.05);
}

/* Responsif */
@media (max-width: 600px) {
    .pagination {
        flex-direction: column;
        gap: 5px;
    }
    
    .pagination a,
    .btn-prev,
    .btn-next {
        width: 100%;
        text-align: center;
    }
}

</style>