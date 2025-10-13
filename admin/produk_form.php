<?php
require_once 'session_check.php';
require_once '../includes/db_connect.php';

$is_edit = false;
$page_title = 'Tambah Produk';
$product = [
    'id' => '', 'category_id' => '', 'name' => '', 'short_description' => '', 'long_description' => '',
    'price' => '', 'normal_price' => '', 'stock' => '', 'is_featured' => 0, 'main_image' => ''
];

// Jika ini adalah mode edit (ada ID di URL)
if (isset($_GET['id'])) {
    $is_edit = true;
    $page_title = 'Edit Produk';
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
    $stmt->close();
}

// Ambil semua kategori untuk dropdown
$categories = $conn->query("SELECT * FROM categories ORDER BY name");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <h3 class="mb-4">Panel Admin</h3>
    <p class="text-white-50 small ms-3">Login sebagai <?php echo htmlspecialchars($_SESSION['admin_name']); ?></p>
    <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="pesanan.php">Pesanan</a></li>
        <li class="nav-item"><a class="nav-link active" href="produk.php">Produk</a></li>
        <li class="nav-item mt-auto"><a class="nav-link" href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="main-content">
    <h2 class="mb-4"><?php echo $page_title; ?></h2>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Detail Produk</h5>
        </div>
        <div class="card-body">
            <form action="produk_save.php" method="POST" enctype="multipart/form-data"> 
                
                <?php if($is_edit): ?>
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php while($cat = $categories->fetch_assoc()): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php if($cat['id'] == $product['category_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" id="stock" name="stock" class="form-control" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                    </div>
                </div>

                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Harga Jual (Rp)</label>
                        <input type="number" id="price" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" placeholder="Contoh: 50000" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="normal_price" class="form-label">Harga Normal (Coret) (Rp)</label>
                        <input type="number" id="normal_price" name="normal_price" class="form-control" value="<?php echo htmlspecialchars($product['normal_price']); ?>" placeholder="Kosongkan jika tidak ada diskon">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="long_description" class="form-label">Deskripsi Panjang</label>
                    <textarea id="long_description" name="long_description" class="form-control" rows="5"><?php echo htmlspecialchars($product['long_description']); ?></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="main_image" class="form-label">Gambar Utama</label>
                    <input type="file" id="main_image" name="main_image" class="form-control">
                    <input type="hidden" name="existing_main_image" value="<?php echo htmlspecialchars($product['main_image']); ?>">
                    <?php if($is_edit && !empty($product['main_image'])): ?>
                        <div class="mt-2">
                            <small class="form-text text-muted">Gambar saat ini:</small>
                            <img src="../<?php echo htmlspecialchars($product['main_image']); ?>" height="80" class="ms-2 rounded" alt="Gambar Produk">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" <?php if($product['is_featured']) echo 'checked'; ?>>
                    <label class="form-check-label" for="is_featured">
                        Jadikan Produk Unggulan (Tampil di Halaman Depan)
                    </label>
                </div>
        </div>
        <div class="card-footer text-end">
                <a href="produk.php" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>