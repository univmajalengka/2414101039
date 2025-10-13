<?php 
include 'includes/header.php'; 
// Jika user sudah login, arahkan ke halaman profil
if(isset($_SESSION['user_id'])) {
    header("Location: profil.php");
    exit();
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="text-center fw-bold mb-4">Selamat Datang Kembali</h2>

                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                    <?php endif; ?>
                     <?php if(isset($_GET['success'])): ?>
                        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
                    <?php endif; ?>

                    <form action="auth_process.php" method="POST">
                        <input type="hidden" name="action" value="login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success btn-lg">Masuk</button>
                        </div>
                    </form>
                    <p class="text-center mt-3">
                        Belum punya akun? <a href="register.php">Daftar sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>