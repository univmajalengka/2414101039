<?php
require_once 'session_check.php';
require_once '../includes/db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Sebaiknya tambahkan logika untuk menghapus file gambar juga
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: produk.php?status=dihapus");
    exit();
}
?>