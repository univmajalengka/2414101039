<?php
include 'includes/header.php';

// Wajib login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$page = 'profil'; // Tandai halaman aktif

// --- PERBAIKAN DI BLOK INI ---
// Ambil data user
$stmt = $conn->prepare("SELECT name, email, phone, address, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

// Bind kolom hasil ke variabel
$stmt->bind_result($user_name, $user_email, $user_phone, $user_address, $user_profile_pic);

// Fetch datanya
$stmt->fetch();

// Buat array $user secara manual agar kompatibel dengan sisa kode
$user = [
    'name' => $user_name,
    'email' => $user_email,
    'phone' => $user_phone,
    'address' => $user_address,
    'profile_pic' => $user_profile_pic
];

$stmt->close();
// --- AKHIR PERBAIKAN ---
?>
<link rel="stylesheet" href="assets/css/dashboard.css">

<div class="container my-5">
    <div class="dashboard-layout">
        <div class="dashboard-sidebar">
            <div class="dashboard-nav">
                <div class="profile-info">
                    <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Picture">
                    <h5 class="text-truncate"><?php echo htmlspecialchars($user['name']); ?></h5>
                    <p class="text-muted small text-truncate"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link <?php echo ($page == 'profil') ? 'active' : ''; ?>" href="profil.php"><i class="fas fa-user-edit"></i> Profil Saya</a>
                    <a class="nav-link <?php echo ($page == 'pesanan') ? 'active' : ''; ?>" href="pesanan_saya.php"><i class="fas fa-receipt"></i> Riwayat Pesanan</a>
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </nav>
            </div>
        </div>

        <div class="dashboard-content">
            <div class="content-card">
                <div class="content-card-header">
                    <h4>Pengaturan Akun</h4>
                </div>
                <div class="content-card-body">
                    <?php if (isset($_GET['status'])): ?>
                        <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger'; ?>"><?php echo htmlspecialchars($_GET['message']); ?></div>
                    <?php endif; ?>

                    <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation"><button class="nav-link active" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile">Edit Profil</button></li>
                        <li class="nav-item" role="presentation"><button class="nav-link" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password">Ubah Password</button></li>
                    </ul>

                    <div class="tab-content" id="profileTabsContent">
                        <div class="tab-pane fade show active" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                            <form action="profile_process.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_profile">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label">Email (tidak bisa diubah)</label>
                                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Lengkap</label>
                                            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Nomor Telepon</label>
                                            <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Alamat</label>
                                            <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <img src="<?php echo htmlspecialchars($user['profile_pic']); ?>" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                                        <label for="profile_pic" class="form-label">Ubah Foto Profil</label>
                                        <input type="file" id="profile_pic" name="profile_pic" class="form-control">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                            <form action="profile_process.php" method="POST">
                                <input type="hidden" name="action" value="change_password">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Password Saat Ini</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Password Baru</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_new_password" class="form-label">Konfirmasi Password Baru</label>
                                    <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Ubah Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>