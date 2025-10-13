<?php
// Pengaturan Database
$db_host = 'localhost';
$db_user = 'root'; // Default username untuk XAMPP
$db_pass = '';     // Default password untuk XAMPP
$db_name = 'toko_sembako';

// Membuat Koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek Koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Mengatur karakter set ke utf8mb4 untuk mendukung berbagai karakter
$conn->set_charset("utf8mb4");
?>