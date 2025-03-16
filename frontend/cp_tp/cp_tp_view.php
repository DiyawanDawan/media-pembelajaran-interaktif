<?php
require_once '../../includes/db.php';

// Mengambil data dari tabel pembelajaran
$sql = "SELECT * FROM pembelajaran";
$stmt = $pdo->query($sql);
?>

<?php include_once('../components/header.php'); ?>
<?php include_once('../components/nav.php'); ?>
<main class="content">
    <div class="learning-goald">
        <h1 class="learning-title">Tujuan & Capaian Pembelajaran</h1>
        <div class="table-container">
            <?php if ($stmt->rowCount() > 0): ?>
                <table class="learning-table">
                    <tr class="table-header">
                        <!-- <th class="header-id">ID Pembelajaran</th> -->
                        <th class="header-materi">ID Materi</th>
                        <th class="header-tujuan">Tujuan Pembelajaran</th>
                        <th class="header-capaian">Capaian Pembelajaran</th>
                    </tr>
                    <?php while ($row = $stmt->fetch()): ?>
                        <tr class="table-row">
                            <!-- <td class="cell-id"><?php echo $row['id_pembelajaran']; ?></td> -->
                            <td class="cell-materi"><?php echo $row['id_materi']; ?></td>
                            <td class="cell-tujuan"><?php echo nl2br($row['poin_tujuan']); ?></td>
                            <td class="cell-capaian"><?php echo nl2br($row['poin_capaian']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p class="no-data-message">Data pembelajaran tidak ditemukan.</p>
            <?php endif; ?>
            <img src="../assets/Boy.svg" alt="ilustrasi">
        </div>
    </div>
</main>
<script src="../script/script.js"></script>
</body>

</html>