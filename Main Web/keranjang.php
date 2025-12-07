<?php 
include 'includes/header.php';

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$products_in_cart = [];
$total_price = 0;

if (!empty($cart_items)) {
    $product_ids_string = implode(',', array_map('intval', array_keys($cart_items)));
    if(!empty($product_ids_string)){
        $sql = "SELECT id, name, price, main_image, stock FROM products WHERE id IN ($product_ids_string)";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $products_in_cart[$row['id']] = $row;
        }
    }
}
?>
<div class="container my-5">
    <h1 class="fw-bold mb-4">Keranjang Belanja Anda</h1>
    
    <?php if (empty($cart_items)): ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Keranjang Anda masih kosong.</h4>
            <a href="index.php" class="btn btn-success mt-3">Mulai Belanja</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            
            <div class="col-lg-8">
                <?php foreach($cart_items as $product_id => $quantity): 
                    if(isset($products_in_cart[$product_id])):
                        $product = $products_in_cart[$product_id];
                        $subtotal = $product['price'] * $quantity;
                        $total_price += $subtotal;
                ?>
                <div class="card mb-3 shadow-sm product-item-row" data-id="<?php echo $product_id; ?>">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo htmlspecialchars($product['main_image']); ?>" width="100" class="rounded me-3">
                            <div class="flex-grow-1">
                                <h5 class="mb-1"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="mb-1 text-muted">Rp <?php echo number_format($product['price']); ?></p>
                                <input type="number" 
                                       class="form-control quantity-input" 
                                       value="<?php echo $quantity; ?>" 
                                       style="width: 80px;"
                                       min="1"
                                       max="<?php echo $product['stock']; ?>"
                                       data-id="<?php echo $product_id; ?>"
                                       data-price="<?php echo $product['price']; ?>">
                            </div>
                            <div class="text-end">
                                <h6 class="fw-semibold item-subtotal">Rp <?php echo number_format($subtotal); ?></h6>
                                <form class="remove-from-cart-form mt-2"> 
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; endforeach; ?>
            </div>
            
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-body">
                        <h4 class="card-title fw-semibold">Ringkasan Pesanan</h4>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <p>Total Harga</p>
                            <p id="grand-total" class="fw-semibold">Rp <?php echo number_format($total_price); ?></p>
                        </div>
                        <div class="d-grid mt-3">
                            <a href="checkout.php" class="btn btn-success btn-lg">Lanjut ke Checkout</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>