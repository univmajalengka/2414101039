<?php
include 'includes/header.php';

// Wajib login untuk checkout
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Anda harus login untuk melanjutkan ke checkout.");
    exit();
}

// Keranjang tidak boleh kosong
// Ambil data user untuk diisi otomatis ke form
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name, email, phone, address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// --- PERBAIKAN ---
// 1. Bind hasil ke variabel PHP (sesuai urutan SELECT)
$stmt->bind_result($user_name, $user_email, $user_phone, $user_address);

// 2. Fetch data (karena hanya 1 user, fetch() sekali saja)
$stmt->fetch();

// 3. Buat array $user secara manual agar sisa kode tetap berfungsi
$user = [
    'name' => $user_name,
    'email' => $user_email,
    'phone' => $user_phone,
    'address' => $user_address
];
// --- AKHIR PERBAIKAN ---

$stmt->close();

// Hitung ulang total belanja dari database untuk keamanan
$cart_items = $_SESSION['cart'];
$total_price = 0;
if (!empty($cart_items)) {
    $product_ids = implode(',', array_keys($cart_items));
    $sql = "SELECT id, price FROM products WHERE id IN ($product_ids)";
    $result = $conn->query($sql);
    $products_data = [];
    while($row = $result->fetch_assoc()) {
        $products_data[$row['id']] = $row;
    }

    foreach($cart_items as $id => $qty) {
        $total_price += $products_data[$id]['price'] * $qty;
    }
}
?>

<div class="container my-5">
    <h1 class="fw-bold mb-4 text-center">Checkout Pesanan</h1>
    <form action="order_process.php" method="POST">
        <div class="row">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-semibold mb-3">Alamat Pengiriman</h4>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Penerima</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" id="address" name="address" rows="4" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>

                        <h4 class="fw-semibold mt-4 mb-3">Metode Pembayaran</h4>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                            <label class="form-check-label" for="cod">
                                Bayar di Tempat (COD)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="transfer" value="Bank Transfer">
                            <label class="form-check-label" for="transfer">
                                Transfer Bank
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="fw-semibold mb-3">Ringkasan Pesanan</h4>
                        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
                        <div class="d-flex justify-content-between">
                            <p>Total Harga (<?php echo count($cart_items); ?> item)</p>
                            <p class="fw-semibold">Rp <?php echo number_format($total_price); ?></p>
                        </div>
                         <div class="d-flex justify-content-between">
                            <p>Biaya Pengiriman</p>
                            <p class="fw-semibold">Gratis</p>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between h5 fw-bold">
                            <p>Total Tagihan</p>
                            <p>Rp <?php echo number_format($total_price); ?></p>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg">Buat Pesanan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>