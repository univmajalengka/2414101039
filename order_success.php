<?php 
include 'includes/header.php'; 

$order_number = isset($_GET['order_number']) ? htmlspecialchars($_GET['order_number']) : '';
?>

<div class="container my-5 text-center">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
            <h1 class="display-5 fw-bold">Pesanan Berhasil!</h1>
            <p class="lead">
                Terima kasih telah berbelanja di Sembako Maju Jaya. Pesanan Anda telah kami terima dan akan segera diproses.
            </p>
            <?php if ($order_number): ?>
            <p class="text-muted">Nomor Pesanan Anda: <strong><?php echo $order_number; ?></strong></p>
            <?php endif; ?>
            <hr class="my-4">
            <p>
                Anda akan menerima notifikasi lebih lanjut mengenai status pesanan Anda.
            </p>
            <a class="btn btn-success btn-lg mt-3" href="index.php" role="button">Kembali ke Beranda</a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>