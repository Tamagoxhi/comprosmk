<?php
/**
 * File untuk generate password hash
 * Jalankan file ini sekali untuk mendapatkan password hash yang benar
 * Akses: http://localhost/smk-assyafiiyah/generate_password.php
 */

// Password yang ingin di-hash
$password = "kasir123";

// Generate hash
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h2>Password Hash Generator</h2>";
echo "<p><strong>Password:</strong> " . $password . "</p>";
echo "<p><strong>Hash:</strong> " . $hash . "</p>";
echo "<hr>";
echo "<h3>Cara Menggunakan:</h3>";
echo "<ol>";
echo "<li>Copy hash di atas</li>";
echo "<li>Buka phpMyAdmin</li>";
echo "<li>Pilih database 'smk_assyafiiyah'</li>";
echo "<li>Klik tabel 'admin'</li>";
echo "<li>Edit data admin</li>";
echo "<li>Paste hash ke kolom 'password'</li>";
echo "<li>Atau jalankan SQL berikut:</li>";
echo "</ol>";

echo "<textarea style='width:100%; height:100px; padding:10px; font-family:monospace;'>";
echo "UPDATE admin SET password = '$hash' WHERE username = 'admin';";
echo "</textarea>";

echo "<hr>";
echo "<h3>Atau Hapus dan Buat Ulang:</h3>";
echo "<textarea style='width:100%; height:120px; padding:10px; font-family:monospace;'>";
echo "DELETE FROM admin WHERE username = 'admin';\n";
echo "INSERT INTO admin (username, password, nama) VALUES ('admin', '$hash', 'Administrator');";
echo "</textarea>";

echo "<hr>";
echo "<p><strong>PENTING:</strong> Hapus file ini setelah selesai untuk keamanan!</p>";
?>