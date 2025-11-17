<?php require_once 'config/database.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurusan - SMK Assyafiiyah</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="page-header">
        <div class="container">
            <h1>Program Keahlian</h1>
            <p>Pilih Jurusan Sesuai Minat dan Bakatmu</p>
        </div>
    </div>

    <section class="content-section">
        <div class="container">
            
            

            <div class="jurusan-detail" id="rpl">
                <div class="jurusan-detail-content reverse">
                    <div class="jurusan-detail-img">
                        <img src="assets/images/rpl.jpg" alt="RPL" onerror="this.src='https://via.placeholder.com/500x300'">
                    </div>
                    <div class="jurusan-detail-text">
                        <h2>📱 Rekayasa Perangkat Lunak (RPL)</h2>
                        <p>Program keahlian yang fokus pada pengembangan aplikasi dan sistem perangkat lunak berbasis desktop, web, dan mobile.</p>
                        
                        <h3>Kompetensi yang Dipelajari:</h3>
                        <ul>
                            <li>Pemrograman dasar dan lanjutan (PHP, Java, Python)</li>
                            <li>Pengembangan aplikasi web</li>
                            <li>Pengembangan aplikasi mobile (Android/iOS)</li>
                            <li>Database management (MySQL, PostgreSQL)</li>
                            <li>UI/UX Design</li>
                            <li>Version control (Git)</li>
                            <li>Web framework (Laravel, CodeIgniter)</li>
                        </ul>

                        <h3>Prospek Kerja:</h3>
                        <ul>
                            <li>Web Developer</li>
                            <li>Mobile App Developer</li>
                            <li>Software Engineer</li>
                            <li>Frontend/Backend Developer</li>
                            <li>UI/UX Designer</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="jurusan-detail" id="akl">
                <div class="jurusan-detail-content">
                    <div class="jurusan-detail-img">
                        <img src="assets/images/akl.jpg" alt="AKL" onerror="this.src='https://via.placeholder.com/500x300'">
                    </div>
                    <div class="jurusan-detail-text">
                        <h2>💼 Akuntansi dan Keuangan Lembaga (AKL)</h2>
                        <p>Program keahlian yang mempelajari pembukuan, perpajakan, dan manajemen keuangan perusahaan atau lembaga.</p>
                        
                        <h3>Kompetensi yang Dipelajari:</h3>
                        <ul>
                            <li>Dasar-dasar akuntansi</li>
                            <li>Akuntansi keuangan dan manajemen</li>
                            <li>Perpajakan</li>
                            <li>Komputer akuntansi (MYOB, Accurate)</li>
                            <li>Administrasi keuangan</li>
                            <li>Audit dan pemeriksaan keuangan</li>
                        </ul>

                        <h3>Prospek Kerja:</h3>
                        <ul>
                            <li>Staff Accounting</li>
                            <li>Kasir</li>
                            <li>Admin Keuangan</li>
                            <li>Staff Pajak</li>
                            <li>Bendahara</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="jurusan-detail" id="mp">
                <div class="jurusan-detail-content reverse">
                    <div class="jurusan-detail-img">
                        <img src="assets/images/mp.jpg" alt="MP" onerror="this.src='https://via.placeholder.com/500x300'">
                    </div>
                    <div class="jurusan-detail-text">
                        <h2>🏢 Manajemen Perkantoran (MP)</h2>
                        <p>Program keahlian yang menguasai administrasi perkantoran, manajemen bisnis, dan layanan pelanggan.</p>
                        
                        <h3>Kompetensi yang Dipelajari:</h3>
                        <ul>
                            <li>Administrasi perkantoran</li>
                            <li>Korespondensi bisnis</li>
                            <li>Kearsipan</li>
                            <li>Public speaking dan komunikasi bisnis</li>
                            <li>Aplikasi perkantoran (MS Office)</li>
                            <li>Event organizing</li>
                            <li>Customer service</li>
                        </ul>

                        <h3>Prospek Kerja:</h3>
                        <ul>
                            <li>Sekretaris</li>
                            <li>Admin Perkantoran</li>
                            <li>Receptionist</li>
                            <li>Customer Service</li>
                            <li>Staff HRD</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/script.js"></script>
</body>
</html>