<?php 
include 'includes/header.php'; 

// Ambil kata kunci dari URL, jika tidak ada, kembali ke index.
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
if (empty($keyword)) {
    header("Location: index.php");
    exit();
}

// Persiapkan keyword untuk query LIKE agar lebih relevan
$search_term = "%" . $keyword . "%";

// --- PERBAIKAN DIMULAI DARI SINI ---

// Query untuk mencari produk berdasarkan nama atau deskripsi
// Ganti SELECT * dengan kolom spesifik untuk performa lebih baik dan kompatibilitas bind_result()
$sql = "SELECT id, main_image, name, price, stock FROM products WHERE name LIKE ? AND is_active = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $search_term);
$stmt->execute();

// Bind kolom hasil ke variabel
$stmt->bind_result($p_id, $p_main_image, $p_name, $p_price, $p_stock);

// Simpan semua hasil ke dalam sebuah array
$products_list = [];
while ($stmt->fetch()) {
    $products_list[] = [
        'id' => $p_id,
        'main_image' => $p_main_image,
        'name' => $p_name,
        'price' => $p_price,
        'stock' => $p_stock
    ];
}
// Hitung total produk yang ditemukan dari array
$total_found = count($products_list);
// --- AKHIR PERBAIKAN ---

?>

<div class="container my-5">

    <div class="mb-4">
        <h1 class="fw-bold">Hasil Pencarian: "<?php echo htmlspecialchars($keyword); ?>"</h1>
        <p class="text-muted">Ditemukan <?php echo $total_found; ?> produk yang relevan.</p>
    </div>

    <div class="row g-4">
        <?php if ($total_found > 0): ?>
            <?php foreach($products_list as $product): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card product-card h-100">
                        <a href="produk.php?id=<?php echo $product['id']; ?>">
                            <img src="<?php echo htmlspecialchars($product['main_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </a>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-semibold">
                               <a href="produk.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark"><?php echo htmlspecialchars($product['name']); ?></a>
                            </h5>
                            <div class="mt-auto">
                                <p class="price-text mb-2">
                                    <span class="fw-bold fs-5 text-success">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                                </p>
                                <form class="add-to-cart-form">
                                    <input type="hidden" name="action" value="add">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success fw-semibold" <?php echo ($product['stock'] < 1) ? 'disabled' : ''; ?>>
                                            <i class="fas fa-cart-plus me-2"></i>
                                            <?php echo ($product['stock'] < 1) ? 'Stok Habis' : 'Tambah Keranjang'; ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                <h4>Oops, produk tidak ditemukan.</h4>
                <p class="text-muted">Coba gunakan kata kunci lain yang lebih umum.</p>
                <a href="index.php" class="btn btn-success mt-2">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>
        <?php $stmt->close(); ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>