<?php 
require_once 'config/database.php';

// Pagination
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Jika ada ID berita, tampilkan detail
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $query = query("SELECT * FROM berita WHERE id = $id");
    $berita = fetch($query);
    
    if (!$berita) {
        header('Location: berita.php');
        exit;
    }
} else {
    // Ambil semua berita dengan pagination
    $query = query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT $start, $limit");
    $berita_list = fetchAll($query);
    
    // Hitung total berita untuk pagination
    $total_query = query("SELECT COUNT(*) as total FROM berita");
    $total_data = fetch($total_query);
    $total_pages = ceil($total_data['total'] / $limit);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($berita) ? htmlspecialchars($berita['judul']) . ' - ' : '' ?>Berita - SMK Assyafiiyah</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="page-header">
        <div class="container">
            <h1><?= isset($berita) ? 'Detail Berita' : 'Berita & Informasi' ?></h1>
            <p><?= isset($berita) ? '' : 'Update Terbaru Seputar SMK Assyafiiyah' ?></p>
        </div>
    </div>

    <section class="content-section">
        <div class="container">
            <?php if (isset($berita)): ?>
                <!-- Detail Berita -->
                <div class="berita-detail">
                    <div class="berita-detail-header">
                        <h1><?= htmlspecialchars($berita['judul']) ?></h1>
                        <div class="berita-meta">
                            <span>📅 <?= date('d F Y', strtotime($berita['tanggal'])) ?></span>
                            <span>✍️ <?= htmlspecialchars($berita['penulis']) ?></span>
                        </div>
                    </div>
                    <div class="berita-detail-image">
                        <img src="assets/images/berita/<?= $berita['gambar'] ?? 'default.jpg' ?>" 
                             alt="<?= htmlspecialchars($berita['judul']) ?>"
                             onerror="this.src='https://via.placeholder.com/800x400'">
                    </div>
                    <div class="berita-detail-content">
                        <?= nl2br(htmlspecialchars($berita['konten'])) ?>
                    </div>
                    <div class="berita-detail-footer">
                        <a href="berita.php" class="btn-secondary">← Kembali ke Daftar Berita</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- List Berita -->
                <div class="berita-grid">
                    <?php if (empty($berita_list)): ?>
                        <p>Belum ada berita tersedia.</p>
                    <?php else: ?>
                        <?php foreach ($berita_list as $item): ?>
                        <div class="berita-card">
                            <img src="assets/images/berita/<?= $item['gambar'] ?? 'default.jpg' ?>" 
                                 alt="<?= htmlspecialchars($item['judul']) ?>"
                                 onerror="this.src='https://via.placeholder.com/400x250'">
                            <div class="berita-content">
                                <span class="berita-date"><?= date('d F Y', strtotime($item['tanggal'])) ?></span>
                                <h3><?= htmlspecialchars($item['judul']) ?></h3>
                                <p><?= substr(strip_tags($item['konten']), 0, 150) ?>...</p>
                                <a href="berita.php?id=<?= $item['id'] ?>">Baca Selengkapnya →</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>" class="pagination-btn">← Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="pagination-btn <?= $i == $page ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?>" class="pagination-btn">Next →</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>