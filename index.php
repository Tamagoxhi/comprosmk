<?php
require_once 'config/database.php';

// Ambil berita terbaru
$berita_query = query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3");
$berita_terbaru = fetchAll($berita_query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMK Assyafiiyah - Berkarya dengan Iman dan Ilmu</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Selamat Datang di SMK Assyafiiyah</h1>
            <p>Membangun Generasi Berakhlak Mulia dan Berkompetensi Tinggi</p>
            <a href="profil.php" class="btn-primary">Selengkapnya</a>
        </div>
    </section>

    <!-- Sambutan Kepala Sekolah -->
    <section class="sambutan">
        <div class="container">
            <div class="sambutan-content">
                <div class="sambutan-img">
                    <img src="assets/images/kepala-sekolah.jpg" alt="Kepala Sekolah" onerror="this.src='https://via.placeholder.com/300x400'">
                </div>
                <div class="sambutan-text">
                    <h2>Sambutan Kepala Sekolah</h2>
                    <p>Assalamu'alaikum Warahmatullahi Wabarakatuh</p>
                    <p>Puji syukur kehadirat Allah SWT atas segala rahmat dan karunia-Nya. SMK Assyafiiyah hadir sebagai lembaga pendidikan kejuruan yang berkomitmen mencetak lulusan yang tidak hanya kompeten dalam bidangnya, tetapi juga berakhlak mulia.</p>
                    <p>Kami mengintegrasikan nilai-nilai keislaman dalam setiap aspek pembelajaran, sehingga siswa-siswi kami siap menghadapi tantangan dunia kerja dengan iman yang kuat dan keterampilan yang mumpuni.</p>
                    <p><strong>Endah Kusuma D.A, S.Pd., M.Pd.</strong><br>Kepala SMK Assyafiiyah</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Jurusan -->
    <section class="jurusan-preview">
        <div class="container">
            <h2 class="section-title">Jurusan yang Tersedia</h2>
            <div class="jurusan-grid">
                <div class="jurusan-card">
                    <div class="jurusan-icon">📱</div>
                    <h3>Rekayasa Perangkat Lunak</h3>
                    <p>Fokus pada pengembangan aplikasi dan sistem perangkat lunak</p>
                    <a href="jurusan.php#rpl" class="btn-secondary">Detail</a>
                </div>
                <div class="jurusan-card">
                    <div class="jurusan-icon">💼</div>
                    <h3>Akuntansi dan Keuangan</h3>
                    <p>Mempelajari pembukuan, perpajakan, dan manajemen keuangan</p>
                    <a href="jurusan.php#akl" class="btn-secondary">Detail</a>
                </div>
                <div class="jurusan-card">
                    <div class="jurusan-icon">🏢</div>
                    <h3>Manajemen Perkantoran</h3>
                    <p>Menguasai administrasi perkantoran dan manajemen bisnis</p>
                    <a href="jurusan.php#mp" class="btn-secondary">Detail</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita Terbaru -->
    <section class="berita-terbaru">
        <div class="container">
            <h2 class="section-title">Berita Terbaru</h2>
            <div class="berita-grid">
                <?php foreach ($berita_terbaru as $berita): ?>
                <div class="berita-card">
                    <img src="assets/images/berita/<?= $berita['gambar'] ?? 'default.jpg' ?>" 
                         alt="<?= htmlspecialchars($berita['judul']) ?>"
                         onerror="this.src='https://via.placeholder.com/400x250'">
                    <div class="berita-content">
                        <span class="berita-date"><?= date('d F Y', strtotime($berita['tanggal'])) ?></span>
                        <h3><?= htmlspecialchars($berita['judul']) ?></h3>
                        <p><?= substr(strip_tags($berita['konten']), 0, 150) ?>...</p>
                        <a href="berita.php?id=<?= $berita['id'] ?>">Baca Selengkapnya →</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 30px;">
                <a href="berita.php" class="btn-primary">Lihat Semua Berita</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="assets/js/script.js"></script>
</body>
</html>