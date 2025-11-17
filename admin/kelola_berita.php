<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

// Proses Tambah/Edit Berita
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = escape($_POST['judul']);
    $konten = escape($_POST['konten']);
    $tanggal = escape($_POST['tanggal']);
    $penulis = escape($_POST['penulis']);
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    // Handle upload gambar
    $gambar = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            // Buat folder jika belum ada
            $upload_dir = '../assets/images/berita/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $newname = time() . '_' . $filename;
            $upload_path = $upload_dir . $newname;
            
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                $gambar = $newname;
            } else {
                $error = 'Gagal upload gambar! Pastikan folder berita sudah dibuat.';
            }
        }
    }
    
    if ($id > 0) {
        // Update
        $sql = "UPDATE berita SET judul='$judul', konten='$konten', tanggal='$tanggal', penulis='$penulis'";
        if ($gambar) {
            $sql .= ", gambar='$gambar'";
        }
        $sql .= " WHERE id=$id";
        
        if (query($sql)) {
            $success = 'Berita berhasil diupdate!';
        } else {
            $error = 'Gagal update berita!';
        }
    } else {
        // Insert
        $sql = "INSERT INTO berita (judul, konten, tanggal, penulis, gambar) VALUES ('$judul', '$konten', '$tanggal', '$penulis', '$gambar')";
        if (query($sql)) {
            $success = 'Berita berhasil ditambahkan!';
        } else {
            $error = 'Gagal menambahkan berita!';
        }
    }
}

// Proses Hapus
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if (query("DELETE FROM berita WHERE id=$id")) {
        $success = 'Berita berhasil dihapus!';
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $edit_data = fetch(query("SELECT * FROM berita WHERE id=$id"));
}

// Ambil semua berita
$berita_list = fetchAll(query("SELECT * FROM berita ORDER BY created_at DESC"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita - Admin</title>
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
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; font-family: inherit; }
        .form-group textarea { min-height: 200px; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-primary { background: #2ecc71; color: white; }
        .btn-primary:hover { background: #27ae60; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-danger:hover { background: #c0392b; }
        .btn-warning { background: #f39c12; color: white; }
        .btn-warning:hover { background: #e67e22; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #f8f9fa; padding: 12px; text-align: left; font-weight: 600; color: #2c3e50; }
        table td { padding: 12px; border-bottom: 1px solid #ecf0f1; }
        .actions { display: flex; gap: 10px; }
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
                <li><a href="kelola_berita.php" class="active">📰 Kelola Berita</a></li>
                <li><a href="kelola_media.php">🖼️ Kelola Media</a></li>
                <li><a href="kelola_pesan.php">✉️ Pesan Masuk</a></li>
                <li><a href="../index.php" target="_blank">🌐 Lihat Website</a></li>
            </ul>
        </aside>

        <div class="main-content">
            <div class="topbar">
                <h1>Kelola Berita</h1>
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
                    <h2><?= $edit_data ? 'Edit Berita' : 'Tambah Berita Baru' ?></h2>
                    <form method="POST" enctype="multipart/form-data">
                        <?php if ($edit_data): ?>
                            <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label>Judul Berita *</label>
                            <input type="text" name="judul" required value="<?= $edit_data ? htmlspecialchars($edit_data['judul']) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label>Konten Berita *</label>
                            <textarea name="konten" required><?= $edit_data ? htmlspecialchars($edit_data['konten']) : '' ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Tanggal *</label>
                            <input type="date" name="tanggal" required value="<?= $edit_data ? $edit_data['tanggal'] : date('Y-m-d') ?>">
                        </div>

                        <div class="form-group">
                            <label>Penulis *</label>
                            <input type="text" name="penulis" required value="<?= $edit_data ? htmlspecialchars($edit_data['penulis']) : $_SESSION['admin_nama'] ?>">
                        </div>

                        <div class="form-group">
                            <label>Gambar <?= $edit_data ? '(Biarkan kosong jika tidak ingin mengubah)' : '' ?></label>
                            <input type="file" name="gambar" accept="image/*">
                            <?php if ($edit_data && $edit_data['gambar']): ?>
                                <p style="margin-top: 10px;">Gambar saat ini: <strong><?= $edit_data['gambar'] ?></strong></p>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <?= $edit_data ? '✏️ Update Berita' : '➕ Tambah Berita' ?>
                        </button>
                        
                        <?php if ($edit_data): ?>
                            <a href="kelola_berita.php" class="btn btn-warning">❌ Batal Edit</a>
                        <?php endif; ?>
                    </form>
                </div>

                <!-- Daftar Berita -->
                <div class="card">
                    <h2>Daftar Berita</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Penulis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($berita_list as $berita): ?>
                            <tr>
                                <td><?= $berita['id'] ?></td>
                                <td><?= htmlspecialchars(substr($berita['judul'], 0, 50)) ?><?= strlen($berita['judul']) > 50 ? '...' : '' ?></td>
                                <td><?= date('d/m/Y', strtotime($berita['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($berita['penulis']) ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="?edit=<?= $berita['id'] ?>" class="btn btn-warning">✏️ Edit</a>
                                        <a href="?delete=<?= $berita['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus berita ini?')">🗑️ Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>