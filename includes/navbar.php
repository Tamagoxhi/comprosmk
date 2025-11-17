<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="container">
        <div class="nav-brand">
            <img src="assets/images/logo.png" alt="Logo SMK Assyafiiyah" onerror="this.style.display='none'">
            <span>SMK Assyafiiyah</span>
        </div>
        <button class="nav-toggle" id="navToggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <ul class="nav-menu" id="navMenu">
            <li><a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">Home</a></li>
            <li><a href="profil.php" class="<?= $current_page == 'profil.php' ? 'active' : '' ?>">Profil</a></li>
            <li><a href="jurusan.php" class="<?= $current_page == 'jurusan.php' ? 'active' : '' ?>">Jurusan</a></li>
            <li><a href="berita.php" class="<?= $current_page == 'berita.php' ? 'active' : '' ?>">Berita</a></li>
            <li><a href="media.php" class="<?= $current_page == 'media.php' ? 'active' : '' ?>">Media</a></li>
            <li><a href="hubungi.php" class="<?= $current_page == 'hubungi.php' ? 'active' : '' ?>">Hubungi</a></li>
        </ul>
    </div>
</nav>