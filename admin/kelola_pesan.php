<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

// Proses Hapus
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if (query("DELETE FROM pesan WHERE id=$id")) {
        $success = 'Pesan berhasil dihapus!';
    }
}

// Proses Tandai Dibaca
if (isset($_GET['read'])) {
    $id = (int)$_GET['read'];
    if (query("UPDATE pesan SET status='dibaca' WHERE id=$id")) {
        $success = 'Pesan ditandai sudah dibaca!';
    }
}

// Proses Detail Pesan
$detail_pesan = null;
if (isset($_GET['detail'])) {
    $id = (int)$_GET['detail'];
    $detail_pesan = fetch(query("SELECT * FROM pesan WHERE id=$id"));
    // Auto tandai dibaca
    query("UPDATE pesan SET status='dibaca' WHERE id=$id");
}

// Ambil semua pesan
$pesan_list = fetchAll(query("SELECT * FROM pesan ORDER BY created_at DESC"));

// Statistik
$total_pesan = count($pesan_list);
$pesan_baru = count(array_filter($pesan_list, function($p) { return $p['status'] == 'baru'; }));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesan - Admin</title>
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
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); text-align: center; }
        .stat-card h3 { color: #7f8c8d; font-size: 0.9rem; margin-bottom: 10px; }
        .stat-card .stat-number { font-size: 2.5rem; font-weight: bold; color: #2ecc71; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #f8f9fa; padding: 12px; text-align: left; font-weight: 600; color: #2c3e50; }
        table td { padding: 12px; border-bottom: 1px solid #ecf0f1; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; }
        .badge-new { background: #e74c3c; color: white; }
        .badge-read { background: #95a5a6; color: white; }
        .btn { padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; font-size: 0.9rem; }
        .btn-primary { background: #3498db; color: white; }
        .btn-primary:hover { background: #2980b9; }
        .btn-success { background: #2ecc71; color: white; }
        .btn-success:hover { background: #27ae60; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn-danger:hover { background: #c0392b; }
        .actions { display: flex; gap: 10px; }
        .logout-btn { background: #e74c3c; color: white; padding: 8px 20px; border-radius: 5px; text-decoration: none; }
        .logout-btn:hover { background: #c0392b; }
        .detail-box { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-top: 20px; }
        .detail-box h3 { color: #2c3e50; margin-bottom: 15px; }
        .detail-row { margin-bottom: 15px; }
        .detail-row strong { display: inline-block; width: 120px; color: #7f8c8d; }
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
                <li><a href="kelola_media.php">🖼️ Kelola Media</a></li>
                <li><a href="kelola_pesan.php" class="active">✉️ Pesan Masuk</a></li>
                <li><a href="../index.php" target="_blank">🌐 Lihat Website</a></li>
            </ul>
        </aside>

        <div class="main-content">
            <div class="topbar">
                <h1>Pesan Masuk</h1>
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

                <!-- Statistik Pesan -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Pesan</h3>
                        <div class="stat-number"><?= $total_pesan ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Pesan Baru</h3>
                        <div class="stat-number" style="color: #e74c3c;"><?= $pesan_baru ?></div>
                    </div>
                </div>

                <!-- Detail Pesan (jika ada) -->
                <?php if ($detail_pesan): ?>
                <div class="card">
                    <h2>Detail Pesan</h2>
                    <div class="detail-box">
                        <div class="detail-row">
                            <strong>Dari:</strong>
                            <?= htmlspecialchars($detail_pesan['nama']) ?>
                        </div>
                        <div class="detail-row">
                            <strong>Email:</strong>
                            <a href="mailto:<?= htmlspecialchars($detail_pesan['email']) ?>"><?= htmlspecialchars($detail_pesan['email']) ?></a>
                        </div>
                        <div class="detail-row">
                            <strong>Subjek:</strong>
                            <?= htmlspecialchars($detail_pesan['subjek']) ?>
                        </div>
                        <div class="detail-row">
                            <strong>Tanggal:</strong>
                            <?= date('d F Y, H:i', strtotime($detail_pesan['created_at'])) ?> WIB
                        </div>
                        <div class="detail-row">
                            <strong>Status:</strong>
                            <span class="badge <?= $detail_pesan['status'] == 'baru' ? 'badge-new' : 'badge-read' ?>">
                                <?= ucfirst($detail_pesan['status']) ?>
                            </span>
                        </div>
                        <hr style="margin: 20px 0;">
                        <div class="detail-row">
                            <strong>Pesan:</strong><br>
                            <div style="margin-top: 10px; padding: 15px; background: white; border-radius: 5px; border: 1px solid #ddd;">
                                <?= nl2br(htmlspecialchars($detail_pesan['pesan'])) ?>
                            </div>
                        </div>
                        <div style="margin-top: 20px;">
                            <a href="kelola_pesan.php" class="btn btn-primary">← Kembali ke Daftar</a>
                            <a href="?delete=<?= $detail_pesan['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus pesan ini?')">🗑️ Hapus Pesan</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Daftar Pesan -->
                <div class="card">
                    <h2>Daftar Semua Pesan</h2>
                    <?php if (empty($pesan_list)): ?>
                        <p style="text-align: center; color: #7f8c8d; padding: 20px;">Belum ada pesan masuk.</p>
                    <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pesan_list as $pesan): ?>
                            <tr style="<?= $pesan['status'] == 'baru' ? 'background: #fff3cd;' : '' ?>">
                                <td><?= date('d/m/Y H:i', strtotime($pesan['created_at'])) ?></td>
                                <td><?= htmlspecialchars($pesan['nama']) ?></td>
                                <td><?= htmlspecialchars($pesan['email']) ?></td>
                                <td><?= htmlspecialchars(substr($pesan['subjek'], 0, 40)) ?><?= strlen($pesan['subjek']) > 40 ? '...' : '' ?></td>
                                <td>
                                    <span class="badge <?= $pesan['status'] == 'baru' ? 'badge-new' : 'badge-read' ?>">
                                        <?= ucfirst($pesan['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="?detail=<?= $pesan['id'] ?>" class="btn btn-primary" title="Lihat Detail">👁️ Lihat</a>
                                        <?php if ($pesan['status'] == 'baru'): ?>
                                            <a href="?read=<?= $pesan['id'] ?>" class="btn btn-success" title="Tandai Dibaca">✓</a>
                                        <?php endif; ?>
                                        <a href="?delete=<?= $pesan['id'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus pesan ini?')" title="Hapus">🗑️</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>