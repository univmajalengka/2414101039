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

// --- PERBAIKAN 1 (User Info) ---
$stmt_user->store_result();
$stmt_user->bind_result($user_name, $user_email, $user_profile_pic);
$stmt_user->fetch();
$user_info = ['name' => $user_name, 'email' => $user_email, 'profile_pic' => $user_profile_pic];
// --- Akhir Perbaikan 1 ---

$stmt_user->close();

// Ambil semua data pesanan dengan order_number
$sql = "SELECT id, order_number, order_date, total_price, status FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// --- PERBAIKAN 2 (Daftar Pesanan) ---
$stmt->store_result();
$stmt->bind_result($order_id, $order_number, $order_date, $order_total_price, $order_status);
// $orders = $stmt->get_result(); // Dihapus
// $stmt->close(); // Dipindahkan ke setelah loop
// --- Akhir Perbaikan 2 ---
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
                    <a class="nav-link <?php echo ($page == 'profil') ? 'active' : ''; ?>" href="profile.php"><i class="fas fa-user-edit"></i> Profil Saya</a>
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
                    <?php if ($stmt->num_rows === 0): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Anda belum memiliki riwayat pesanan.</h4>
                            <a href="index.php" class="btn btn-success mt-3">Mulai Belanja Sekarang</a>
                        </div>
                    <?php else: ?>
                        <div class="accordion order-history" id="orderHistoryAccordion">
                            
                            <?php while ($stmt->fetch()) : ?>
                            <?php
                            // Buat array $order secara manual dari variabel yang di-bind
                            $order = [
                                'id' => $order_id, 
                                'order_number' => $order_number, 
                                'order_date' => $order_date, 
                                'total_price' => $order_total_price, 
                                'status' => $order_status
                            ];
                            ?>
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
                                        
                                        // --- PERBAIKAN 5 (Detail Item) ---
                                        $detail_stmt->store_result();
                                        $detail_stmt->bind_result($item_quantity, $item_unit_price, $item_name, $item_main_image);
                                        // $details = $detail_stmt->get_result(); // Dihapus
                                        // --- Akhir Perbaikan 5 ---
                                        ?>
                                        <ul class="list-group list-group-flush">
                                            
                                            <?php while ($detail_stmt->fetch()) : ?>
                                            <?php
                                            // Buat array $item secara manual
                                            $item = [
                                                'quantity' => $item_quantity, 
                                                'unit_price' => $item_unit_price, 
                                                'name' => $item_name, 
                                                'main_image' => $item_main_image
                                            ];
                                            ?>
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
                                            $detail_stmt->close(); // Ini sudah benar di sini
                                            ?>
                                        </ul>
                                        <hr>
                                        <div class="text-end">
                                            <h5 class="fw-bold">Total Pesanan: Rp <?php echo number_format($order['total_price']); ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; 
                            $stmt->close(); // <-- PERBAIKAN 7: $stmt ditutup di sini setelah loop selesai
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>