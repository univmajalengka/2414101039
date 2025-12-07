<?php 
include 'includes/header.php'; 

if(!isset($_GET['id'])) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Produk tidak ditemukan.</div></div>";
    include 'includes/footer.php';
    exit();
}

$product_id = intval($_GET['id']);

$sql = "SELECT p.id, p.category_id, p.name, p.long_description, p.price, p.normal_price, p.stock, p.main_image, p.is_active, 
               c.name as category_name, c.slug as category_slug 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE p.id = ? AND p.is_active = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();

$stmt->bind_result(
    $p_id, $p_cat_id, $p_name, $p_desc, $p_price, $p_norm_price, $p_stock, $p_main_img, $p_is_active, 
    $c_name, $c_slug
);

$product = null;
if ($stmt->fetch()) {
    $product = [
        'id' => $p_id,
        'category_id' => $p_cat_id,
        'name' => $p_name,
        'long_description' => $p_desc,
        'price' => $p_price,
        'normal_price' => $p_norm_price,
        'stock' => $p_stock,
        'main_image' => $p_main_img,
        'is_active' => $p_is_active,
        'category_name' => $c_name,
        'category_slug' => $c_slug
    ];
}
$stmt->close();

if($product === null) {
    echo "<div class='container my-5'><div class='alert alert-danger'>Produk tidak ditemukan atau tidak aktif.</div></div>";
    include 'includes/footer.php';
    exit();
}
$stmt_images = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ? ORDER BY sort_order");
$stmt_images->bind_param("i", $product_id);
$stmt_images->execute();

$stmt_images->bind_result($image_path);

$product_images = [];
while($stmt_images->fetch()){
    $product_images[] = $image_path;
}
$stmt_images->close();
// --- AKHIR PERUBAHAN ---
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-6">
            <div id="productImageSlider" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?php echo htmlspecialchars($product['main_image']); ?>" class="d-block w-100 rounded" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <?php foreach($product_images as $image): ?>
                    <div class="carousel-item">
                        <img src="<?php echo htmlspecialchars($image); ?>" class="d-block w-100 rounded" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($product_images) > 0): ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#productImageSlider" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productImageSlider" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-6">
            <a href="kategori.php?slug=<?php echo $product['category_slug']; ?>" class="text-muted text-decoration-none"><?php echo htmlspecialchars($product['category_name']); ?></a>
            <h1 class="fw-bold mt-2"><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="price-section my-3">
                <span class="h2 fw-bold text-success">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                <?php if($product['normal_price'] > $product['price']): ?>
                <span class="ms-2 text-muted text-decoration-line-through">Rp <?php echo number_format($product['normal_price'], 0, ',', '.'); ?></span>
                <?php endif; ?>
            </div>
            
            <p class="text-muted">Stok: <span class="fw-semibold"><?php echo $product['stock']; ?></span></p>

            <form class="add-to-cart-form mt-4">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Jumlah:</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="<?php echo $product['stock']; ?>">
                    </div>
                    <div class="col-md-8 mt-3 mt-md-0 d-grid">
                        <button type="submit" class="btn btn-success btn-lg" <?php echo ($product['stock'] < 1) ? 'disabled' : ''; ?>>
                            <i class="fas fa-cart-plus me-2"></i> <?php echo ($product['stock'] < 1) ? 'Stok Habis' : 'Tambah ke Keranjang'; ?>
                        </button>
                    </div>
                </div>
            </form>
            
            <hr class="my-4">

            <h5 class="fw-semibold">Deskripsi Produk</h5>
            <div class="product-description">
                <?php echo nl2br(htmlspecialchars($product['long_description'])); ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>