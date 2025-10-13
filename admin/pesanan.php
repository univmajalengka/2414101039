<?php
require_once 'session_check.php';
require_once '../includes/db_connect.php';

// Proses update status jika ada form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();
}

// PERUBAHAN 1: Ambil 'order_number' dari database
$sql = "SELECT o.id, o.order_number, u.name as customer_name, o.order_date, o.total_price, o.status 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.order_date DESC";
$orders = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="sidebar">
    <h3 class="mb-4">Panel Admin</h3>
    <p class="text-white-50 small ms-3">Login sebagai <?php echo htmlspecialchars($_SESSION['admin_name']); ?></p>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link active" href="pesanan.php">Pesanan</a></li>
        <li class="nav-item"><a class="nav-link" href="produk.php">Produk</a></li>
        <li class="nav-item mt-auto"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h2 class="mb-4">Manajemen Pesanan</h2>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($orders->num_rows > 0): ?>
                            <?php while($order = $orders->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($order['order_number']); ?></strong></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo date('d M Y, H:i', strtotime($order['order_date'])); ?></td>
                                <td>Rp <?php echo number_format($order['total_price']); ?></td>
                                <td>
                                    <?php
                                        $status_color = 'secondary';
                                        switch ($order['status']) {
                                            case 'PENDING': $status_color = 'warning'; break;
                                            case 'PAID': $status_color = 'info'; break;
                                            case 'SHIPPED': $status_color = 'primary'; break;
                                            case 'COMPLETED': $status_color = 'success'; break;
                                            case 'CANCELLED': $status_color = 'danger'; break;
                                        }
                                    ?>
                                    <span class="badge bg-<?php echo $status_color; ?>"><?php echo $order['status']; ?></span>
                                </td>
                                <td>
                                    <form method="POST" class="d-flex">
                                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                        <select name="status" class="form-select form-select-sm me-2">
                                            <option value="PENDING" <?php if($order['status'] == 'PENDING') echo 'selected'; ?>>Pending</option>
                                            <option value="PAID" <?php if($order['status'] == 'PAID') echo 'selected'; ?>>Paid</option>
                                            <option value="SHIPPED" <?php if($order['status'] == 'SHIPPED') echo 'selected'; ?>>Shipped</option>
                                            <option value="COMPLETED" <?php if($order['status'] == 'COMPLETED') echo 'selected'; ?>>Completed</option>
                                            <option value="CANCELLED" <?php if($order['status'] == 'CANCELLED') echo 'selected'; ?>>Cancelled</option>
                                        </select>
                                        <button type="submit" name="update_status" class="btn btn-sm btn-primary">Update</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Belum ada pesanan yang masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>