<?php
// Di dunia nyata, Anda harus menambahkan sistem login admin yang aman di sini.
// Untuk sekarang, kita asumsikan admin sudah login.
require_once 'session_check.php';
require_once '../includes/db_connect.php';

// Query untuk menghitung pesanan yang masih 'PENDING'
$sql_pending_orders = "SELECT COUNT(id) as total FROM orders WHERE status = 'PENDING'";
$result_pending = $conn->query($sql_pending_orders);
$pending_orders_count = $result_pending->fetch_assoc()['total'];

// Query untuk menghitung total produk
$sql_total_products = "SELECT COUNT(id) as total FROM products WHERE is_active = 1";
$result_products = $conn->query($sql_total_products);
$total_products_count = $result_products->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; }
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0; width: 250px;
            padding: 20px; background-color: #2c3e50; color: white;
        }
        .sidebar h3 { color: #ecf0f1; }
        .sidebar .nav-link { color: #bdc3c7; padding: 10px 15px; }
        .sidebar .nav-link.active, .sidebar .nav-link:hover { color: white; background-color: #34495e; }
        .main-content { margin-left: 250px; padding: 30px; }
        .stat-card { padding: 25px; border-radius: 8px; color: white; }
        .stat-card h3 { font-size: 2.5rem; font-weight: bold; }
        .card-pending { border-left: 5px solid #e74c3c; background-color: white; color: #333; }
        .card-products { border-left: 5px solid #3498db; background-color: white; color: #333; }
        .card-header-custom { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>

<div class="sidebar">
    <h3 class="mb-4">Panel Admin</h3>
    <p class="text-white-50 small ms-3">Login sebagai <?php echo $_SESSION['admin_name']; ?></p>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="pesanan.php">Pesanan</a></li> <li class="nav-item"><a class="nav-link" href="produk.php">Produk</a></li> <li class="nav-item mt-auto"><a class="nav-link" href="logout.php">Logout</a></li> </ul>
</div>

<div class="main-content">
    <div class="card-header-custom mb-4">
        <h2>Selamat Datang, Super Admin Toko</h2>
        <a href="../index.php" target="_blank" class="btn btn-outline-secondary">Lihat Toko</a>
    </div>
    <p>Ini adalah dashboard pengelolaan toko sembako Anda. Fokus pada manajemen produk dan pesanan.</p>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card card-pending shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-danger">Pesanan Menunggu</h5>
                    <h1 class="display-4 fw-bold"><?php echo $pending_orders_count; ?></h1>
                    <p class="card-text">Butuh perhatian Anda segera!</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-products shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Produk</h5>
                    <h1 class="display-4 fw-bold"><?php echo $total_products_count; ?></h1>
                    <p class="card-text">Total item yang tersedia di toko.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>