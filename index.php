<?php include 'includes/header.php'; ?>

<header class="hero-section">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-3 text-white">Sembako Lengkap, Harga Hemat</h1>
                <p class="lead my-4 text-white-50">Temukan semua kebutuhan pokok Anda di sini. Kualitas terjamin, diantar sampai depan pintu rumah Anda.</p>
                <form action="search.php" method="GET" class="d-flex mt-5 search-form">
                    <input class="form-control form-control-lg me-2" type="search" name="keyword" placeholder="Cari minyak, beras, gula..." aria-label="Search" required>
                    <button class="btn btn-lg" type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
    </div>
</header>

<section class.="section-white">
    <div class="container">
        <div class="section-title">
            <h2>Jelajahi Kategori</h2>
        </div>
        <div class="category-grid">
            <?php
            // Ambil data kategori dari database
            $sql_categories = "SELECT * FROM categories ORDER BY name ASC";
            $result_categories = $conn->query($sql_categories);
            $icons = ['fa-seedling', 'fa-pepper-hot', 'fa-utensils', 'fa-tint', 'fa-egg'];
            $i = 0;

            if ($result_categories->num_rows > 0) {
                while($row = $result_categories->fetch_assoc()) {
                    echo '
                    <a href="kategori.php?slug='.$row['slug'].'" class="category-card">
                        <div class="icon-circle">
                            <i class="fas '.$icons[$i % count($icons)].'"></i>
                        </div>
                        <h5 class="fw-semibold">'.htmlspecialchars($row['name']).'</h5>
                    </a>';
                    $i++;
                }
            }
            ?>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Produk Unggulan</h2>
        
        <?php
        // PERBAIKAN: Hapus LIMIT 4 untuk mengambil semua produk unggulan
        $sql_products = "SELECT p.*, c.name as category_name FROM products p 
                         LEFT JOIN categories c ON p.category_id = c.id 
                         WHERE p.is_featured = 1 AND p.is_active = 1";
        $result_products = $conn->query($sql_products);

        if ($result_products->num_rows > 0) {
        ?>
            <div class="swiper product-slider">
                <div class="swiper-wrapper">
                    <?php while($product = $result_products->fetch_assoc()) { ?>
                        <div class="swiper-slide">
                            <div class="card product-card h-100">
                                <a href="produk.php?id=<?php echo $product['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($product['main_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </a>
                                <div class="card-body d-flex flex-column"> 
                                    <h5 class="card-title fw-semibold product-title"> 
                                    <a href="produk.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark"><?php echo htmlspecialchars($product['name']); ?></a>
                                    </h5>
                                    <p class="card-text text-muted small"><?php echo htmlspecialchars($product['category_name']); ?></p>

                                    <div class="mt-auto"> 
                                        <p class="price-text mb-2">
                                            <span class="fw-bold fs-5 text-success">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></span>
                                            <?php if(!empty($product['normal_price']) && $product['normal_price'] > $product['price']): ?>
                                            <span class="text-muted text-decoration-line-through">Rp <?php echo number_format($product['normal_price'], 0, ',', '.'); ?></span>
                                            <?php endif; ?>
                                        </p>
                                        <form class="add-to-cart-form">
                                            </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        <?php
            } else {
                echo "<p class='text-center'>Belum ada produk unggulan.</p>";
            }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>