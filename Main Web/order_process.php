<?php
session_start();
require_once 'includes/db_connect.php';

// Pastikan user login dan ada request POST
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart_items = $_SESSION['cart'] ?? [];

if (empty($cart_items)) {
    header("Location: keranjang.php");
    exit();
}

// Ambil data dari form
$shipping_address = trim($_POST['address']);
$payment_method = $_POST['payment_method'];
$total_price = $_POST['total_price'];

// === FUNGSI UNTUK MEMBUAT NOMOR PESANAN ACAK & UNIK ===
function generateOrderNumber($conn) {
    do {
        $prefix = 'INV/' . date('Ymd') . '/';
        $random_part = strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
        $order_number = $prefix . $random_part;

        $stmt = $conn->prepare("SELECT id FROM orders WHERE order_number = ?");
        $stmt->bind_param("s", $order_number);
        $stmt->execute();
        $stmt->store_result();
        $is_unique = $stmt->num_rows == 0;
        $stmt->close();
    } while (!$is_unique);
    
    return $order_number;
}

$order_number = generateOrderNumber($conn);
// =======================================================

// Memulai Transaksi Database
$conn->begin_transaction();

try {
    // 1. Masukkan data ke tabel 'orders' dengan order_number baru
    $stmt_order = $conn->prepare("INSERT INTO orders (user_id, order_number, total_price, shipping_address, payment_method, status) VALUES (?, ?, ?, ?, ?, 'PENDING')");
    $stmt_order->bind_param("isdss", $user_id, $order_number, $total_price, $shipping_address, $payment_method);
    $stmt_order->execute();
    
    $order_id = $conn->insert_id;
    $stmt_order->close();

    // 2. Masukkan setiap item di keranjang ke tabel 'order_details' dan update stok
    $stmt_details = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
    $stmt_update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

    $product_ids = implode(',', array_map('intval', array_keys($cart_items)));
    $sql_prices = "SELECT id, price FROM products WHERE id IN ($product_ids)";
    $result_prices = $conn->query($sql_prices);
    $product_prices = [];
    while($row = $result_prices->fetch_assoc()) {
        $product_prices[$row['id']] = $row['price'];
    }

    foreach ($cart_items as $product_id => $quantity) {
        $unit_price = $product_prices[$product_id];
        
        $stmt_details->bind_param("iiid", $order_id, $product_id, $quantity, $unit_price);
        $stmt_details->execute();

        $stmt_update_stock->bind_param("ii", $quantity, $product_id);
        $stmt_update_stock->execute();
    }
    
    $stmt_details->close();
    $stmt_update_stock->close();

    $conn->commit();

    // 3. Hapus keranjang belanja dari session
    unset($_SESSION['cart']);

    // Arahkan ke halaman sukses dengan order_number
    header("Location: order_success.php?order_number=" . $order_number);
    exit();

} catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    header("Location: checkout.php?error=Gagal memproses pesanan. Silakan coba lagi.");
    exit();
}
?>