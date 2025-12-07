<?php
require_once 'session_check.php';
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil semua data dari form
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $normal_price = $_POST['normal_price'] ?: null;
    $stock = $_POST['stock'];
    $long_description = $_POST['long_description'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Logika Upload Gambar
    $main_image_path = $_POST['existing_main_image'] ?? null;
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $target_dir = "../assets/images/product_images/";
        $filename = uniqid('prod_') . basename($_FILES["main_image"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["main_image"]["tmp_name"], $target_file)) {
            $main_image_path = "assets/images/product_images/" . $filename;
        }
    }

    if ($id) { // UPDATE
        $stmt = $conn->prepare("UPDATE products SET category_id=?, name=?, long_description=?, price=?, normal_price=?, stock=?, main_image=?, is_featured=? WHERE id=?");
        $stmt->bind_param("issddisii", $category_id, $name, $long_description, $price, $normal_price, $stock, $main_image_path, $is_featured, $id);
        $stmt->execute();
        $status = "diperbarui";
    } else { // INSERT
        $stmt = $conn->prepare("INSERT INTO products (category_id, name, long_description, price, normal_price, stock, main_image, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issddisi", $category_id, $name, $long_description, $price, $normal_price, $stock, $main_image_path, $is_featured);
        $stmt->execute();
        $status = "ditambahkan";
    }
    
    $stmt->close();
    header("Location: produk.php?status=" . $status);
    exit();
}
?>