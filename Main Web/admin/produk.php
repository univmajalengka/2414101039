<?php
require_once 'session_check.php';
require_once '../includes/db_connect.php';

// Ambil semua produk
$products = $conn->query("SELECT p.id, p.name, c.name as category_name, p.price, p.stock 
                          FROM products p 
                          LEFT JOIN categories c ON p.category_id = c.id 
                          ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="sidebar">
    <h3 class="mb-4">Panel Admin</h3>
    <p class="text-white-50 small ms-3">Login sebagai <?php echo $_SESSION['admin_name']; ?></p>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="pesanan.php">Pesanan</a></li>
        <li class="nav-item"><a class="nav-link active" href="produk.php">Produk</a></li>
        <li class="nav-item mt-auto"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Produk</h2>
        <a href="produk_form.php" class="btn btn-success">Tambah Produk Baru</a>
    </div>
    
    <?php if(isset($_GET['status'])): ?>
        <div class="alert alert-success">Produk berhasil <?php echo htmlspecialchars($_GET['status']); ?>!</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($product = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                        <td>Rp <?php echo number_format($product['price']); ?></td>
                        <td><?php echo $product['stock']; ?></td>
                        <td>
                            <a href="produk_form.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="produk_delete.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin menghapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>