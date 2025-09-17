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
$total_pages = ceil($total_rows / $limit);

// Ambil data materi dengan batasan halaman
$sql_materi = "SELECT * FROM materi ORDER BY id_materi DESC LIMIT :limit OFFSET :offset";
$stmt_materi = $pdo->prepare($sql_materi);
$stmt_materi->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt_materi->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt_materi->execute();
$materi = $stmt_materi->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
<main class="content">
    <div class="content-container">
        <div class="content-header">
            <h1 class="content-title">Daftar Materi</h1>
            <div class="content-actions">
                <a href="tambah.php" class="btn btn-tambah">Tambah Materi</a>
                <a href="logout.php" class="btn btn-hapus">Logout</a>
            </div>
        </div>

        <!-- Tampilkan pesan session -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success_message'] ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Materi</th>
                        <th>Deskripsi</th>
                        <th>Link Materi</th>
                        <th>Status</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = $offset + 1; foreach ($materi as $m): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($m['judul_materi']) ?></td>
                        <td class="deskripsi-cell">
                            <?php 
                            $fullDeskripsi = $m['deskripsi'];
                            $escapedFull = htmlspecialchars($fullDeskripsi, ENT_QUOTES, 'UTF-8');
                            $words = str_word_count(strip_tags($fullDeskripsi), 2);
                            $word_limit = 10;
                            
                            if (count($words) > $word_limit) {
                                $word_keys = array_keys($words);
                                $truncated = substr($fullDeskripsi, 0, $word_keys[$word_limit]) . '...';
                                echo '<span class="short-description">' . htmlspecialchars($truncated) . '</span>';
                                echo ' <a href="#" class="view-more" data-full="' . $escapedFull . '">[Lihat Selengkapnya]</a>';
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
                        <td>
                            <span class="status-badge status-<?= $m['status'] ?>">
                                <?= ucfirst($m['status']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($m['gambar']): ?>
                                <a href="<?= htmlspecialchars($m['gambar']) ?>" target="_blank" class="gambar-preview">
                                    <i class="fas fa-eye"></i> Preview
                                </a>
                            <?php else: ?>
                                <span class="no-image">Tidak ada gambar</span>
                            <?php endif; ?>
                        </td>
                        <td class="action-buttons">
                            <a href="edit.php?id=<?= $m['id_materi'] ?>" class="btn btn-edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="hapus.php?id=<?= $m['id_materi'] ?>" class="btn btn-hapus" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn btn-prev">
                    <i class="fas fa-chevron-left"></i> Sebelumnya
                </a>
            <?php endif; ?>

            <span class="page-info">Halaman <?= $page ?> dari <?= $total_pages ?></span>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn btn-next">
                    Selanjutnya <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle deskripsi lengkap/pendek
    document.querySelectorAll('.view-more').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const shortDesc = this.previousElementSibling;
            const fullText = this.getAttribute('data-full');
            
            if (this.textContent === '[Lihat Selengkapnya]') {
                shortDesc.textContent = fullText;
                this.textContent = '[Sembunyikan]';
            } else {
                const words = fullText.split(/\s+/);
                if (words.length > 10) {
                    shortDesc.textContent = words.slice(0, 10).join(' ') + '...';
                } else {
                    shortDesc.textContent = fullText;
                }
                this.textContent = '[Lihat Selengkapnya]';
            }
        });
    });
});
</script>

<style>
/* General Styles */
.content-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.content-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 8px 15px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.btn-tambah {
    background-color: #28a745;
    color: white;
}

.btn-tambah:hover {
    background-color: #218838;
    transform: translateY(-2px);
}

.btn-hapus {
    background-color: #dc3545;
    color: white;
}

.btn-hapus:hover {
    background-color: #c82333;
    transform: translateY(-2px);
}

/* Table Styles */
.table-responsive {
    overflow-x: auto;
    margin-bottom: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
}

.data-table th, .data-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.data-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.data-table tr:hover {
    background-color: #f5f5f5;
}

/* Deskripsi Cell */
.deskripsi-cell {
    max-width: 250px;
}

.short-description {
    display: inline;
}

.view-more {
    color: #007bff;
    text-decoration: none;
    font-size: 0.9em;
    cursor: pointer;
    white-space: nowrap;
}

.view-more:hover {
    text-decoration: underline;
}

/* Status Badges */
.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: 500;
}

.status-published {
    background-color: #d4edda;
    color: #155724;
}

.status-draft {
    background-color: #fff3cd;
    color: #856404;
}

.status-archived {
    background-color: #f8d7da;
    color: #721c24;
}

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

.no-link, .no-image {
    color: #6c757d;
    font-style: italic;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-edit {
    background-color: #17a2b8;
    color: white;
    padding: 6px 10px;
    font-size: 0.9em;
}

.btn-edit:hover {
    background-color: #138496;
    transform: translateY(-2px);
}

.btn-hapus {
    background-color: #dc3545;
    color: white;
    padding: 6px 10px;
    font-size: 0.9em;
}

.btn-hapus:hover {
    background-color: #c82333;
    transform: translateY(-2px);
}

/* Pagination Styles */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 20px;
    flex-wrap: wrap;
}

.page-info {
    font-weight: 500;
    color: #495057;
}

.btn-prev, .btn-next {
    padding: 8px 15px;
    background-color: #6c757d;
    color: white;
}

.btn-prev:hover, .btn-next:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
}

/* Responsive Styles */
@media (max-width: 768px) {
    .content-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .content-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .data-table th, .data-table td {
        padding: 8px 10px;
        font-size: 0.9em;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 5px;
    }
    
    .btn {
        padding: 6px 10px;
        font-size: 0.9em;
    }
}
</style>

<?php include_once('../components/footer.php') ?>