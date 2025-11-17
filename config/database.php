<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smk_assyafiiyah');

// Koneksi Database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8");

// Fungsi untuk escape string
function escape($data) {
    global $conn;
    return mysqli_real_escape_string($conn, $data);
}

// Fungsi untuk query
function query($sql) {
    global $conn;
    return mysqli_query($conn, $sql);
}

// Fungsi untuk fetch data
function fetch($result) {
    return mysqli_fetch_assoc($result);
}

// Fungsi untuk fetch all data
function fetchAll($result) {
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}
?>