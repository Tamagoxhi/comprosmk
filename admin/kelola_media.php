<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

// Proses Tambah/Edit Media
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = escape($_POST['judul']);
    $deskripsi = escape($_POST['deskripsi']);
    $tipe = escape($_POST['tipe']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    // Handle upload file
    $file = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $allowed = $tipe == 'foto' ? ['jpg', 'jpeg', 'png', 'gif'] : ['mp4', 'avi', 'mov'];
        $filename = $_FILES['file']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            // Buat folder jika belum ada
            $upload_dir = '../assets/images/media/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $newname = time() . '_' . $filename;
            $upload_path = $upload_dir . $newname;
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)) {
                $file = $newname;
            } else {
                $error = 'Gagal upload file! Pastikan folder media sudah dibuat.';
            }
        }
    }
    
    if ($id > 0) {
        // Update
        $sql = "UPDATE media SET judul='$judul', deskripsi='$deskripsi', tipe='$tipe'";
        if ($file) {
            $sql .= ", file='$file'";
        }
        $sql .= " WHERE id=$id";
        
        if (query($sql)) {
            $success = 'Media berhasil diupdate!';
        } else {
            $error = 'Gagal update media!';
        }
    } else {
        // Insert
        if ($file) {
            $sql = "INSERT INTO media (judul, deskripsi, file, tipe) VALUES ('$judul', '$deskripsi', '$file', '$tipe')";
            if (query($sql)) {
                $success = 'Media berhasil ditambahkan!';
            } else {
                $error = 'Gagal menambahkan media!';
            }
        } else {
            $error = 'File harus diupload!';
        }
    }
}

// Proses Hapus
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if (query("DELETE FROM media WHERE id=$id")) {
        $success = 'Media berhasil dihapus!';
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = fetch(query("SELECT * FROM media WHERE id=$id"));
}

// Ambil semua media
$media_list = fetchAll(query("SELECT * FROM media ORDER BY created_at DESC"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Media - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #2c3e50; color: white; padding: 20px 0; }
        .sidebar-header { padding: 0 20px 20px; border-bottom: 1px solid #34495e; }
        .sidebar-header h3 { color: #2ecc71; margin-bottom: 5px; }
        .sidebar-menu { list-style: none; padding: 20px 0; }
        .sidebar-menu li { margin-bottom: 5px; }
        .sidebar-menu a { display: block; padding: 12px 20px; color: white; text-decoration: none; transition: all 0.3s; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: #34495e; border-left: 3px solid #2ecc71; }
        .main-content { flex: 1; background: #ecf0f1; }
        .topbar { background: white; padding: 15px 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
        .content-area { padding: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card h2 { color: #2c3e50; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #2ecc71; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #2c3e50; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: inherit; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-primary { background: #2ecc71; color: white; }
        .btn-primary:hover { background: #27ae60; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-danger:hover { background: #c0392b; }
        .btn-warning { background: #f39c12; color: white; }
        .btn-warning:hover { background: #e67e22; }
        .media-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        .media-item { background: #f8f9fa; padding: 15px; border-radius: 10px; text-align: center; }
        .media-item img, .media-item video { width: 100%; height: 150px; object-fit: cover; border-radius: 5px; margin-bottom: 10px; }
        .media-actions { display: flex; gap: 10px; justify-content: center; margin-top: 10px; }
        .logout-btn { background: #e74c3c; color: white; padding: 8px 20px; border-radius: 5px; text-decoration: none; }
        .logout-btn:hover { background: #c0392b; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
                <p>SMK Assyafiiyah</p>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php">📊 Dashboard</a></li>
                <li><a href="kelola_berita.php">📰 Kelola Berita</a></li>
                <li><a href="kelola_media.php" class="active">🖼️ Kelola Media</a></li>
                <li><a href="kelola_pesan.php">✉️ Pesan Masuk</a></li>
                <li><a href="../index.php" target="_blank">🌐 Lihat Website</a></li>
            </ul>
        </aside>

        <div class="main-content">
            <div class="topbar">
                <h1>Kelola Media</h1>
                <div>
                    <span>Selamat datang, <strong><?= $_SESSION['admin_nama'] ?></strong></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <div class="content-area">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?= $error ?></div>
                <?php endif; ?>

                <!-- Form Tambah/Edit -->
                <div class="card">
                    <h2><?= $edit_data ? 'Edit Media' : 'Tambah Media Baru' ?></h2>
                    <form method="POST" enctype="multipart/form-data">
                        <?php if ($edit_data): ?>
                            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label>Judul *</label>
                            <input type="text" name="judul" required value="<?= $edit_data ? htmlspecialchars($edit_data['judul']) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" rows="3"><?= $edit_data ? htmlspecialchars($edit_data['deskripsi']) : '' ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Tipe *</label>
                            <select name="tipe" required>
                                <option value="foto" <?= $edit_data && $edit_data['tipe'] == 'foto' ? 'selected' : '' ?>>📷 Foto</option>
                                <option value="video" <?= $edit_data && $edit_data['tipe'] == 'video' ? 'selected' : '' ?>>🎥 Video</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>File <?= $edit_data ? '(Biarkan kosong jika tidak ingin mengubah)' : '*' ?></label>
                            <input type="file" name="file" accept="image/*,video/*" <?= !$edit_data ? 'required' : '' ?>>
                            <?php if ($edit_data && $edit_data['file']): ?>
                                <p style="margin-top: 10px;">File saat ini: <strong><?= $edit_data['file'] ?></strong></p>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <?= $edit_data ? '✏️ Update Media' : '➕ Tambah Media' ?>
                        </button>
                        
                        <?php if ($edit_data): ?>
                            <a href="kelola_media.php" class="btn btn-warning">❌ Batal Edit</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Daftar Media -->
                <div class="card">
                    <h2>Galeri Media</h2>
                    <div class="media-grid">
                        <?php foreach ($media_list as $media): ?>
                        <div class="media-item">
                            <?php if ($media['tipe'] == 'foto'): ?>
                                <img src="../assets/images/media/<?= $media['file'] ?>" alt="<?= htmlspecialchars($media['judul']) ?>" onerror="this.src='https://via.placeholder.com/250x150'">
                            <?php else: ?>
                                <video width="100%" height="150">
                                    <source src="../assets/images/media/<?= $media['file'] ?>" type="video/mp4">
                                </video>
                                <p style="font-size: 2rem; margin: 20px 0;">▶️</p>
                            <?php endif; ?>
                            <h4><?= htmlspecialchars($media['judul']) ?></h4>
                            <p style="font-size: 0.9rem; color: #666;"><?= htmlspecialchars(substr($media['deskripsi'], 0, 50)) ?></p>
                            <span style="background: <?= $media['tipe'] == 'foto' ? '#3498db' : '#e74c3c' ?>; color: white; padding: 3px 10px; border-radius: 12px; font-size: 0.85rem;">
                                <?= $media['tipe'] == 'foto' ? '📷 Foto' : '🎥 Video' ?>
                            </span>
                            <div class="media-actions">
                                <a href="?edit=<?= $media['id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                <a href="?delete=<?= $media['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus media ini?')">🗑️ Hapus</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>