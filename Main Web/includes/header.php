<?php require_once 'db_connect.php';
session_start();

$total_items_in_cart = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    $total_items_in_cart = array_sum($_SESSION['cart']);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sembako Maju Jaya - Kebutuhan Pokok Terlengkap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="shortcut icon" href="../assets/images/favicon.ico"> 
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container py-2">
        <a class="navbar-brand fw-bold" href="index.php">Sembako Maju Jaya</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Beranda</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link position-relative" href="keranjang.php">
                        <i class="fas fa-shopping-cart"></i> Keranjang
                        
                        <span id="cart-count-badge" 
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?php if ($total_items_in_cart == 0) echo 'd-none'; ?>">
                            <?php echo $total_items_in_cart; ?>
                        </span>
                    </a>
                 </li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> Hai, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="profile.php">Profil Saya</a></li>
                            <li><a class="dropdown-item" href="pesanan_saya.php">Riwayat Pesanan</a></li> <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-success" href="login.php">Masuk</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-success" href="register.php">Daftar</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-success" href="admin/login.php">Login Admin</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>