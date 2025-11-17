<?php 
require_once 'config/database.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = escape($_POST['nama']);
    $email = escape($_POST['email']);
    $subjek = escape($_POST['subjek']);
    $pesan = escape($_POST['pesan']);
    
    if (empty($nama) || empty($email) || empty($subjek) || empty($pesan)) {
        $error = 'Semua field harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } else {
        $sql = "INSERT INTO pesan (nama, email, subjek, pesan) VALUES ('$nama', '$email', '$subjek', '$pesan')";
        if (query($sql)) {
            $success = 'Pesan Anda berhasil dikirim! Kami akan segera menghubungi Anda.';
        } else {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hubungi Kami - SMK Assyafiiyah</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="page-header">
        <div class="container">
            <h1>Hubungi Kami</h1>
            <p>Ada Pertanyaan? Kami Siap Membantu Anda</p>
        </div>
    </div>

    <section class="content-section">
        <div class="container">
            <div class="contact-wrapper">
                <!-- Informasi Kontak -->
                <div class="contact-info-box">
                    <h2>Informasi Kontak</h2>
                    
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div>
                            <h3>Alamat</h3>
                            <p>Jl. Raya Assyafiiyah No. 123<br>Jakarta Timur, DKI Jakarta 13750</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div>
                            <h3>Telepon</h3>
                            <p>(021) 1234-5678<br>(021) 8765-4321</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">📧</div>
                        <div>
                            <h3>Email</h3>
                            <p>info@smkassyafiiyah.sch.id<br>humas@smkassyafiiyah.sch.id</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">🕐</div>
                        <div>
                            <h3>Jam Operasional</h3>
                            <p>Senin - Jumat: 07.00 - 16.00 WIB<br>Sabtu: 07.00 - 12.00 WIB</p>
                        </div>
                    </div>

                    <div class="social-links">
                        <h3>Media Sosial</h3>
                        <div class="social-icons">
                            <a href="#" title="Facebook">📘 Facebook</a>
                            <a href="#" title="Instagram">📷 Instagram</a>
                            <a href="#" title="YouTube">📺 YouTube</a>
                            <a href="#" title="WhatsApp">💬 WhatsApp</a>
                        </div>
                    </div>
                </div>

                <!-- Form Kontak -->
                <div class="contact-form-box">
                    <h2>Kirim Pesan</h2>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST" class="contact-form">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap *</label>
                            <input type="text" id="nama" name="nama" required 
                                   value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required
                                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="subjek">Subjek *</label>
                            <input type="text" id="subjek" name="subjek" required
                                   value="<?= isset($_POST['subjek']) ? htmlspecialchars($_POST['subjek']) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="pesan">Pesan *</label>
                            <textarea id="pesan" name="pesan" rows="6" required><?= isset($_POST['pesan']) ? htmlspecialchars($_POST['pesan']) : '' ?></textarea>
                        </div>

                        <button type="submit" class="btn-primary">Kirim Pesan</button>
                    </form>
                </div>
            </div>

            <!-- Peta -->
            <div class="map-section">
                <h2>Lokasi Kami</h2>
                <div class="map-container-large">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666!2d106.845!3d-6.208!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMjguOCJTIDEwNsKwNTAnNDIuMCJF!5e0!3m2!1sen!2sid!4v1234567890" 
                            width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>