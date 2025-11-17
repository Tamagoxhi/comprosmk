<?php
session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Statistik
$total_berita = fetch(query("SELECT COUNT(*) as total FROM berita"))['total'];
$total_media = fetch(query("SELECT COUNT(*) as total FROM media"))['total'];
$total_pesan = fetch(query("SELECT COUNT(*) as total FROM pesan"))['total'];
$pesan_baru = fetch(query("SELECT COUNT(*) as total FROM pesan WHERE status = 'baru'"))['total'];

// Berita terbaru
$berita_terbaru = fetchAll(query("SELECT * FROM berita ORDER BY created_at DESC LIMIT 5"));

// Pesan terbaru
$pesan_terbaru = fetchAll(query("SELECT * FROM pesan ORDER BY created_at DESC LIMIT 5"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMK Assyafiiyah</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px 0;
        }
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #34495e;
        }
        .sidebar-header h3 {
            color: #2ecc71;
            margin-bottom: 5px;
        }
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #34495e;
            border-left: 3px solid #2ecc71;
        }
        .main-content {
            flex: 1;
            background: #ecf0f1;
        }
        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .content-area {
            padding: 30px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .stat-card .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2ecc71;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2ecc71;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #2c3e50;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-new {
            background: #e74c3c;
            color: white;
        }
        .badge-read {
            background: #95a5a6;
            color: white;
        }
        .logout-btn {
            background: #e74c3c;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
                <p>SMK Assyafiiyah</p>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active">📊 Dashboard</a></li>
                <li><a href="kelola_berita.php">📰 Kelola Berita</a></li>
                <li><a href="kelola_media.php">🖼️ Kelola Media</a></li>
                <li><a href="kelola_pesan.php">✉️ Pesan Masuk</a></li>
                <li><a href="../index.php" target="_blank">🌐 Lihat Website</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Topbar -->
            <div class="topbar">
                <h1>Dashboard</h1>
                <div>
                    <span>Selamat datang, <strong><?= $_SESSION['admin_nama'] ?></strong></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Berita</h3>
                        <div class="stat-number"><?= $total_berita ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Media</h3>
                        <div class="stat-number"><?= $total_media ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Total Pesan</h3>
                        <div class="stat-number"><?= $total_pesan ?></div>
                    </div>
                    <div class="stat-card">
                        <h3>Pesan Baru</h3>
                        <div class="stat-number" style="color: #e74c3c;"><?= $pesan_baru ?></div>
                    </div>
                </div>

                <!-- Berita Terbaru -->
                <div class="card">
                    <h2>Berita Terbaru</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Tanggal</th>
                                <th>Penulis</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($berita_terbaru as $berita): ?>
                            <tr>
                                <td><?= htmlspecialchars(substr($berita['judul'], 0, 50)) ?>...</td>
                                <td><?= date('d/m/Y', strtotime($berita['tanggal'])) ?></td>
                                <td><?= htmlspecialchars($berita['penulis']) ?></td>
                                <td>
                                    <a href="kelola_berita.php?edit=<?= $berita['id'] ?>" style="color: #2ecc71;">Edit</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pesan Terbaru -->
                <div class="card">
                    <h2>Pesan Masuk Terbaru</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pesan_terbaru as $pesan): ?>
                            <tr>
                                <td><?= htmlspecialchars($pesan['nama']) ?></td>
                                <td><?= htmlspecialchars($pesan['email']) ?></td>
                                <td><?= htmlspecialchars(substr($pesan['subjek'], 0, 30)) ?>...</td>
                                <td>
                                    <span class="badge <?= $pesan['status'] == 'baru' ? 'badge-new' : 'badge-read' ?>">
                                        <?= ucfirst($pesan['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y H:i', strtotime($pesan['created_at'])) ?></td>
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