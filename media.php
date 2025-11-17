<?php 
require_once 'config/database.php';

// Filter berdasarkan tipe
$tipe = isset($_GET['tipe']) ? $_GET['tipe'] : 'all';
$where = $tipe != 'all' ? "WHERE tipe = '$tipe'" : '';

$query = query("SELECT * FROM media $where ORDER BY created_at DESC");
$media_list = fetchAll($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media & Galeri - SMK Assyafiiyah</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="page-header">
        <div class="container">
            <h1>Media & Galeri</h1>
            <p>Dokumentasi Kegiatan SMK Assyafiiyah</p>
        </div>
    </div>

    <section class="content-section">
        <div class="container">
            <!-- Filter -->
            <div class="media-filter">
                <a href="media.php?tipe=all" class="filter-btn <?= $tipe == 'all' ? 'active' : '' ?>">Semua</a>
                <a href="media.php?tipe=foto" class="filter-btn <?= $tipe == 'foto' ? 'active' : '' ?>">📷 Foto</a>
                <a href="media.php?tipe=video" class="filter-btn <?= $tipe == 'video' ? 'active' : '' ?>">🎥 Video</a>
            </div>

            <!-- Gallery Grid -->
            <div class="gallery-grid">
                <?php if (empty($media_list)): ?>
                    <p>Belum ada media tersedia.</p>
                <?php else: ?>
                    <?php foreach ($media_list as $media): ?>
                    <div class="gallery-item" onclick="openModal('<?= $media['id'] ?>')">
                        <?php if ($media['tipe'] == 'foto'): ?>
                            <img src="assets/images/media/<?= $media['file'] ?>" 
                                 alt="<?= htmlspecialchars($media['judul']) ?>"
                                 onerror="this.src='https://via.placeholder.com/400x300'">
                        <?php else: ?>
                            <div class="video-thumbnail">
                                <img src="https://via.placeholder.com/400x300?text=Video" 
                                     alt="<?= htmlspecialchars($media['judul']) ?>">
                                <div class="play-icon">▶</div>
                            </div>
                        <?php endif; ?>
                        <div class="gallery-overlay">
                            <h3><?= htmlspecialchars($media['judul']) ?></h3>
                            <p><?= htmlspecialchars($media['deskripsi']) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Modal untuk preview -->
    <div id="mediaModal" class="modal">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <div id="modalBody"></div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
    <script>
        const mediaData = <?= json_encode($media_list) ?>;
        
        function openModal(id) {
            const media = mediaData.find(m => m.id == id);
            if (!media) return;
            
            const modal = document.getElementById('mediaModal');
            const modalBody = document.getElementById('modalBody');
            
            let content = '';
            if (media.tipe === 'foto') {
                content = `
                    <img src="assets/images/media/${media.file}" 
                         alt="${media.judul}"
                         onerror="this.src='https://via.placeholder.com/800x600'">
                `;
            } else {
                content = `
                    <video controls width="100%">
                        <source src="assets/images/media/${media.file}" type="video/mp4">
                        Browser Anda tidak mendukung video.
                    </video>
                `;
            }
            
            content += `
                <div class="modal-caption">
                    <h3>${media.judul}</h3>
                    <p>${media.deskripsi || ''}</p>
                </div>
            `;
            
            modalBody.innerHTML = content;
            modal.style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('mediaModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('mediaModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>