<?php
session_start();
require_once 'includes/db_connect.php';

// Wajib login dan ada request POST
if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

// === AKSI UPDATE PROFIL ===
if ($action === 'update_profile') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Logika Upload Foto Profil
    $profile_pic_path = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $target_dir = "assets/images/profiles/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $filename = "user_" . $user_id . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $filename;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $profile_pic_path = $target_file;
        }
    }

    if ($profile_pic_path) {
        $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, address=?, profile_pic=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $phone, $address, $profile_pic_path, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $phone, $address, $user_id);
    }

    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name; // Update nama di session juga
        header('Location: profile.php?status=success&message=Profil berhasil diperbarui!');
    } else {
        header('Location: profile.php?status=error&message=Gagal memperbarui profil.');
    }
    $stmt->close();
}

// === AKSI UBAH PASSWORD ===
elseif ($action === 'change_password') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if ($new_password !== $confirm_new_password) {
        header('Location: profile.php?status=error&message=Password baru tidak cocok.');
        exit();
    }

    // Ambil hash password saat ini dari DB
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($result && password_verify($current_password, $result['password'])) {
        // Jika password saat ini benar, hash password baru dan update
        $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt_update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt_update->bind_param("si", $new_hashed_password, $user_id);
        if ($stmt_update->execute()) {
            header('Location: profile.php?status=success&message=Password berhasil diubah!');
        } else {
            header('Location: profile.php?status=error&message=Gagal mengubah password.');
        }
        $stmt_update->close();
    } else {
        header('Location: profile.php?status=error&message=Password saat ini salah.');
    }
} else {
    header('Location: profile.php');
}

exit();
?>