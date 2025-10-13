<?php
include 'includes/header.php';

// Wajib login untuk akses halaman ini
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$page = 'pesanan'; // Variabel untuk menandai halaman aktif di sidebar

// Ambil data user untuk sidebar
$stmt_user = $conn->prepare("SELECT name, email, profile_pic FROM users WHERE id = ?");
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_info = $stmt_user->get_result()->fetch_assoc();
$stmt_user->close();

// Ambil semua data pesanan dengan order_number
$sql = "SELECT id, order_number, order_date, total_price, status FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
$stmt->close();
?>
<link rel="stylesheet" href="assets/css/dashboard.css">

<div class="container my-5">
    <div class="dashboard-layout">
        <div class="dashboard-sidebar">
            <div class="dashboard-nav">
                <div class="profile-info">
                    <img src="<?php echo htmlspecialchars($user_info['profile_pic']); ?>" alt="Profile Picture">
                    <h5 class="text-truncate"><?php echo htmlspecialchars($user_info['name']); ?></h5>
                    <p class="text-muted small text-truncate"><?php echo htmlspecialchars($user_info['email']); ?></p>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link <?php echo ($page == 'profil') ? 'active' : ''; ?>" href="profil.php"><i class="fas fa-user-edit"></i> Profil Saya</a>
                    <a class="nav-link <?php echo ($page == 'pesanan') ? 'active' : ''; ?>" href="pesanan_saya.php"><i class="fas fa-receipt"></i> Riwayat Pesanan</a>
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>
        </div>

        <div class="dashboard-content">
            <div class="content-card">
                <div class="content-card-header">
                    <h4>Riwayat Pesanan Saya</h4>
                </div>
                <div class="content-card-body">
                    <?php if ($orders->num_rows === 0): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Anda belum memiliki riwayat pesanan.</h4>
                            <a href="index.php" class="btn btn-success mt-3">Mulai Belanja Sekarang</a>
                        </div>
                    <?php else: ?>
                        <div class="accordion order-history" id="orderHistoryAccordion">
                            <?php while ($order = $orders->fetch_assoc()) : ?>
                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="heading-<?php echo $order['id']; ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $order['id']; ?>">
                                        <div class="d-flex justify-content-between w-100 pe-3">
                                            <span>Pesanan <?php echo htmlspecialchars($order['order_number']); ?></span>
                                            <span class="text-muted d-none d-md-block"><?php echo date('d F Y', strtotime($order['order_date'])); ?></span>
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
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse-<?php echo $order['id']; ?>" class="accordion-collapse collapse" data-bs-parent="#orderHistoryAccordion">
                                    <div class="accordion-body">
                                        <h6 class="fw-semibold">Detail Item:</h6>
                                        <?php
                                        $detail_sql = "SELECT od.quantity, od.unit_price, p.name, p.main_image FROM order_details od JOIN products p ON od.product_id = p.id WHERE od.order_id = ?";
                                        $detail_stmt = $conn->prepare($detail_sql);
                                        $detail_stmt->bind_param("i", $order['id']);
                                        $detail_stmt->execute();
                                        $details = $detail_stmt->get_result();
                                        ?>
                                        <ul class="list-group list-group-flush">
                                            <?php while ($item = $details->fetch_assoc()) : ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?php echo htmlspecialchars($item['main_image']); ?>" width="50" class="rounded me-3">
                                                        <div>
                                                            <?php echo htmlspecialchars($item['name']); ?>
                                                            <small class="d-block text-muted"><?php echo $item['quantity']; ?> x Rp <?php echo number_format($item['unit_price']); ?></small>
                                                        </div>
                                                    </div>
                                                    <span class="fw-semibold">Rp <?php echo number_format($item['quantity'] * $item['unit_price']); ?></span>
                                                </li>
                                            <?php endwhile;
                                            $detail_stmt->close(); ?>
                                        </ul>
                                        <hr>
                                        <div class="text-end">
                                            <h5 class="fw-bold">Total Pesanan: Rp <?php echo number_format($order['total_price']); ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>



<?php include 'includes/footer.php'; ?>