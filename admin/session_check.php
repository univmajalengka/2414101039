<?php
session_start();
// Jika tidak ada session admin atau levelnya bukan SUPERADMIN/EDITOR, tendang ke halaman login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php?error=Anda harus login terlebih dahulu.');
    exit();
}
?>