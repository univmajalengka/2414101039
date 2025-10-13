<?php 
include 'includes/header.php'; 

// Cek slug
if(!isset($_GET['slug'])) {
    // Tampilkan pesan error jika tidak ada slug
    echo "<div class='container my-5 text-center'><h2>Kategori tidak ditemukan.</h2><a href='index.php' class='btn btn-success'>Kembali ke Beranda</a></div>";
    include 'includes/footer.php';
    exit();
}
$slug = $_GET['slug'];

// Ambil info kategori
$stmt_cat = $conn->prepare("SELECT id, name FROM categories WHERE slug = ?");
$stmt_cat->bind_param("s", $slug);
$stmt_cat->execute();
$result_cat = $stmt_cat->get_result();
if($result_cat->num_rows == 0) {
    // Tampilkan pesan error jika slug tidak valid
    echo "<div class='container my-5 text-center'><h2>Kategori tidak ditemukan.</h2><a href='index.php' class='btn btn-success'>Kembali ke Beranda</a></div>";
    include 'includes/footer.php';
    exit();
}
$category = $result_cat->fetch_assoc();
$category_id = $category['id'];
$category_name = $category['name'];
$stmt_cat->close();


// --- LOGIKA UNTUK SORTING DAN PAGINASI ---

// 1. Sorting
$sort_options = [
    'latest' => 'p.id DESC',
    'price_asc' => 'p.price ASC',
    'price_desc' => 'p.price DESC',
    'name_asc' => 'p.name ASC'
];
$sort_key = $_GET['sort'] ?? 'latest';
$order_by = $sort_options[$sort_key] ?? 'p.id DESC';

// 2. Paginasi
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$products_per_page = 8; // Tampilkan 8 produk per halaman
$offset = ($page - 1) * $products_per_page;

// Hitung total produk untuk paginasi
$stmt_count = $conn->prepare("SELECT COUNT(id) as total FROM products WHERE category_id = ? AND is_active = 1");
$stmt_count->bind_param("i", $category_id);
$stmt_count->execute();
$total_products = $stmt_count->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_products / $products_per_page);
$stmt_count->close();

// Query utama untuk mengambil produk dengan sorting dan limit
$sql_prod = "SELECT * FROM products p WHERE p.category_id = ? AND p.is_active = 1 ORDER BY $order_by LIMIT ? OFFSET ?";
$stmt_prod = $conn->prepare($sql_prod);
$stmt_prod->bind_param("iii", $category_id, $products_per_page, $offset);
$stmt_prod->execute();
$result_products = $stmt_prod->get_result();

?>

<div class="container my-5">

    <div class="category-banner text-center">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($category_name); ?></li>
            </ol>
        </nav>
        <h1 class="display-5"><?php echo htmlspecialchars($category_name); ?></h1>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <span class="text-muted">Menampilkan <?php echo $result_products->num_rows; ?> dari <?php echo $total_products; ?> produk</span>
        <form method="GET" class="d-flex align-items-center">
            <input type="hidden" name="slug" value="<?php echo $slug; ?>">
            <label for="sort" class="form-label me-2 mb-0">Urutkan:</label>
            <select name="sort" id="sort" class="form-select w-auto" onchange="this.form.submit()">
                <option value="latest" <?php if($sort_key == 'latest') echo 'selected'; ?>>Terbaru</option>
                <option value="price_asc" <?php if($sort_key == 'price_asc') echo 'selected'; ?>>Harga Terendah</option>
                <option value="price_desc" <?php if($sort_key == 'price_desc') echo 'selected'; ?>>Harga Tertinggi</option>
                <option value="name_asc" <?php if($sort_key == 'name_asc') echo 'selected'; ?>>Nama A-Z</option>
            </select>
        </form>
    </div>

    <div class="row g-4">
    <?php if ($result_products->num_rows > 0): ?>
        <?php while($product = $result_products->fetch_assoc()): ?>
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
                                <?php if(!empty($product['normal_price']) && $product['normal_price'] > $product['price']): ?>
                                <span class="text-muted text-decoration-line-through">Rp <?php echo number_format($product['normal_price'], 0, ',', '.'); ?></span>
                                <?php endif; ?>
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
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <h4>Oops, belum ada produk di kategori ini.</h4>
            <p class="text-muted">Coba lihat kategori lainnya!</p>
        </div>
    <?php endif; ?>
    <?php $stmt_prod->close(); ?>
</div>

    <?php if($total_pages > 1): ?>
    <nav aria-label="Page navigation" class="mt-5">
        <ul class="pagination justify-content-center">
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                <a class="page-link" href="?slug=<?php echo $slug; ?>&sort=<?php echo $sort_key; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
        </ul>
    </nav>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>