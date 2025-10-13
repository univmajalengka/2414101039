<?php
session_start();
require_once 'includes/db_connect.php';

// Wajib login dan ada request POST
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$order_id = $_POST['order_id'];
$rating = $_POST['rating'];
$comment = trim($_POST['comment']);

// Validasi sederhana
if (empty($product_id) || empty($order_id) || empty($rating)) {
    header('Location: pesanan_saya.php?status=error&message=Data tidak lengkap.');
    exit();
}

// Cek apakah user sudah pernah mereview produk ini dari order yang sama
$stmt_check = $conn->prepare("SELECT id FROM reviews WHERE user_id = ? AND product_id = ? AND order_id = ?");
$stmt_check->bind_param("iii", $user_id, $product_id, $order_id);
$stmt_check->execute();
$stmt_check->store_result();

if($stmt_check->num_rows > 0) {
    header('Location: pesanan_saya.php?status=error&message=Anda sudah memberikan ulasan untuk produk ini.');
    exit();
}
$stmt_check->close();

// Masukkan review baru ke database
$stmt_insert = $conn->prepare("INSERT INTO reviews (product_id, user_id, order_id, rating, comment) VALUES (?, ?, ?, ?, ?)");
$stmt_insert->bind_param("iiiis", $product_id, $user_id, $order_id, $rating, $comment);

if($stmt_insert->execute()) {
    header('Location: pesanan_saya.php?status=success&message=Terima kasih atas ulasan Anda!');
} else {
    header('Location: pesanan_saya.php?status=error&message=Gagal menyimpan ulasan.');
}
$stmt_insert->close();
?>