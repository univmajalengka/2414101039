<?php
session_start();
require_once 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    // Proses Registrasi (BAGIAN INI SUDAH BENAR, TIDAK DIUBAH)
    if ($action == 'register') {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            header("Location: register.php?error=Password tidak cocok!");
            exit();
        }

        // Cek apakah email sudah ada
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result(); // Metode ini kompatibel

        if ($stmt->num_rows > 0) {
            header("Location: register.php?error=Email sudah terdaftar!");
            $stmt->close();
            exit();
        }
        $stmt->close();

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Masukkan ke database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php?success=Registrasi berhasil! Silakan masuk.");
        } else {
            header("Location: register.php?error=Terjadi kesalahan. Coba lagi.");
        }
        $stmt->close();
    }

    // Proses Login
    if ($action == 'login') {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // --- PERBAIKAN DI BLOK INI ---
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        // Bind hasil query ke variabel
        $stmt->bind_result($user_id, $user_name, $user_hashed_password);

        // Coba fetch data. 
        // $stmt->fetch() akan bernilai true jika user ditemukan
        if ($stmt->fetch()) {
            // User ditemukan, sekarang verifikasi password
            if (password_verify($password, $user_hashed_password)) {
                // Login berhasil, set session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $user_name;
                $stmt->close(); // Tutup statement sebelum redirect
                header("Location: index.php"); // Arahkan ke homepage
                exit();
            }
        }
        // --- AKHIR PERBAIKAN ---
        
        // Jika user tidak ditemukan ATAU password salah
        header("Location: login.php?error=Email atau password salah.");
        $stmt->close();
        exit(); // Tambahkan exit di sini untuk keamanan
    }
}
$conn->close();
?>