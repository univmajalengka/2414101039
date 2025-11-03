<?php
session_start();
require_once 'includes/db_connect.php';

// Set header ke JSON
header('Content-Type: application/json');

// Inisialisasi respon default
$response = ['success' => false, 'message' => 'Aksi tidak valid.'];

// Fungsi helper untuk menghitung total item
function calculateTotalItems($cart) {
    return is_array($cart) ? array_sum($cart) : 0;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $action = $_POST['action'];
    $product_id = intval($_POST['product_id']);
    
    // Blok 'add'
    if ($action == 'add') {
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        
        // --- PERBAIKAN DI BLOK INI ---
        $stmt = $conn->prepare("SELECT name, stock FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        
        // Bind hasil query ke variabel
        $stmt->bind_result($product_name, $product_stock);
        
        // Fetch hasilnya (ambil datanya)
        // Kita gunakan if untuk mengecek apakah produknya ada
        if ($stmt->fetch()) {
            // Buat array asosiatif agar sisa kode tidak perlu diubah
            $product_result = ['name' => $product_name, 'stock' => $product_stock];
            $stmt->close(); // Tutup statement di sini setelah selesai

            $current_qty_in_cart = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;
            if (($current_qty_in_cart + $quantity) <= $product_result['stock']) {
                $_SESSION['cart'][$product_id] = $current_qty_in_cart + $quantity;
                $response = [
                    'success' => true, 
                    'message' => htmlspecialchars($product_result['name']) . ' berhasil ditambahkan!',
                    'totalItems' => calculateTotalItems($_SESSION['cart'])
                ];
            } else {
                $response = ['success' => false, 'message' => 'Gagal, stok tidak mencukupi!'];
            }
        } else {
            // Jika fetch() gagal, berarti produk tidak ditemukan
            $stmt->close(); // Tetap tutup statement
            $response = ['success' => false, 'message' => 'Produk tidak ditemukan.'];
        }
        // --- AKHIR PERBAIKAN ---
    } 
    // Blok 'remove'
    else if ($action == 'remove') {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $response = [
                'success' => true, 
                'message' => 'Produk berhasil dihapus dari keranjang.',
                'totalItems' => calculateTotalItems($_SESSION['cart'])
            ];
        } else {
            $response = ['success' => false, 'message' => 'Produk tidak ada di keranjang.'];
        }
    }
    // Blok 'update'
    else if ($action == 'update') {
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if (isset($_SESSION['cart'][$product_id]) && $quantity > 0) {
            
            // --- PERBAIKAN DI BLOK INI ---
            $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            
            // Bind hasil query ke variabel
            $stmt->bind_result($product_stock);
            
            // Fetch hasilnya
            $stmt->fetch();
            $stmt->close();
            // --- AKHIR PERBAIKAN ---

            if ($quantity <= $product_stock) {
                $_SESSION['cart'][$product_id] = $quantity;
                $response = [
                    'success' => true, 
                    'message' => 'Kuantitas diperbarui.',
                    'totalItems' => calculateTotalItems($_SESSION['cart'])
                ];
            } else {
                $_SESSION['cart'][$product_id] = $product_stock;
                $response = [
                    'success' => false, 
                    'message' => 'Stok tidak mencukupi! Kuantitas diatur ke ' . $product_stock,
                    'max_stock' => $product_stock,
                    'totalItems' => calculateTotalItems($_SESSION['cart'])
                ];
            }
        }
    }
}

// Keluarkan respon sebagai JSON dan hentikan script
echo json_encode($response);
exit();
?>