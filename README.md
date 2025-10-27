# 🛒 Sembako Maju Jaya

> Sembako Lengkap, Harga Hemat.

**Sembako Maju Jaya** adalah proyek website E-Commerce yang dirancang untuk menyediakan kebutuhan pokok berkualitas dengan harga terbaik. Temukan semua kebutuhan Anda di sini, mulai dari beras, minyak, mie instan, hingga bumbu dapur. Kualitas terjamin, diantar sampai depan pintu rumah Anda.

## 👨‍💻 Data Diri Pembuat

Proyek ini disusun untuk memenuhi tugas mata kuliah Pengembangan Aplikasi Berbasis Web.

| Keterangan | Data |
| :--- | :--- |
| **Nama** | Muhamad Anugrah Aidil Akbar |
| **NIM / NPM** | 2424101039 |
| **Semester** | 3A |
| **Program Studi** | Informatika |
| **Fakultas** | Teknik |
| **Universitas** | Universitas Majalengka |

## 📸 Galeri / Tampilan

Berikut adalah beberapa tampilan dari website Sembako Maju Jaya:

| Tampilan Utama (Hero) | Kategori & Produk Unggulan | Daftar Produk & Footer |
| :---: | :---: | :---: |
| <img src="Screenshot 2025-10-27 144718.png" alt="Homepage Sembako Maju Jaya" width="300"> | <img src="Screenshot 2025-10-27 144728.png" alt="Kategori dan Produk Unggulan" width="300"> | <img src="Screenshot 2025-10-27 144738.png" alt="Daftar Produk dan Footer" width="300"> |

*(Catatan: Ganti nama file di atas jika berbeda, atau unggah gambar ke GitHub agar bisa tampil)*

## ✨ Fitur Utama

Website ini memiliki beberapa fitur inti yang mendukung fungsionalitas e-commerce:

**Bagi Pengguna (User):**
* **Pencarian Produk:** Pengguna dapat dengan mudah mencari produk (seperti minyak, beras, gula).
* **Registrasi & Login:** Sistem autentikasi untuk pengguna baru dan pengguna terdaftar.
* **Keranjang Belanja:** Pengguna dapat menambahkan produk ke keranjang sebelum melakukan checkout.
* **Jelajah Kategori:** Menampilkan produk berdasarkan kategori (Beras, Bumbu Dapur, Mie Instan, dll.).
* **Daftar Produk Unggulan:** Menampilkan produk-produk yang sedang promo atau paling laku.

**Bagi Admin:**
* **Login Admin:** Halaman login terpisah untuk administrator.
* **(Asumsi) Manajemen Produk:** Admin dapat menambah, mengedit, dan menghapus produk.
* **(Asumsi) Manajemen Kategori:** Admin dapat mengelola kategori produk.
* **(Asumsi) Manajemen Pesanan:** Admin dapat melihat dan mengelola pesanan yang masuk dari pelanggan.

## 💻 Teknologi yang Digunakan

Proyek ini kemungkinan besar dibangun menggunakan tumpukan teknologi berikut:

* **Backend:** PHP (Native)
* **Database:** MySQL
* **Frontend:** HTML, CSS, JavaScript

## 🚀 Cara Menjalankan Proyek (Instalasi Lokal)

Jika Anda ingin menjalankan proyek ini di komputer lokal Anda, ikuti langkah-langkah berikut:

1.  **Prasyarat:**
    * Pastikan Anda memiliki server lokal seperti **XAMPP** atau **WAMP** yang sudah terinstal (yang mencakup Apache, PHP, dan MySQL).

2.  **Clone Repositori:**
    ```bash
    git clone [https://github.com/username-anda/nama-repositori-anda.git](https://github.com/username-anda/nama-repositori-anda.git)
    ```
    Atau unduh file ZIP dan ekstrak.

3.  **Pindahkan Folder Proyek:**
    * Pindahkan folder proyek yang telah Anda clone/ekstrak ke dalam direktori `htdocs` (jika menggunakan XAMPP) atau `www` (jika menggunakan WAMP).

4.  **Database Setup:**
    * Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
    * Buat database baru (misalnya: `db_sembako`).
    * Impor file `.sql` yang ada di repositori ini (misalnya `database.sql`) ke dalam database `db_sembako` yang baru saja Anda buat.

5.  **Konfigurasi Koneksi:**
    * Cari file konfigurasi database di dalam folder proyek (misalnya `config.php` atau `koneksi.php`).
    * Sesuaikan pengaturan koneksi database (nama host, username, password, dan nama database) dengan pengaturan lokal Anda.

    ```php
    <?php
    $host = 'localhost';
    $user = 'root'; // User default XAMPP
    $pass = '';     // Password default XAMPP
    $db   = 'db_sembako'; // Nama database yang Anda buat

    $koneksi = mysqli_connect($host, $user, $pass, $db);
    // ...
    ?>
    ```

6.  **Jalankan Proyek:**
    * Nyalakan modul **Apache** dan **MySQL** dari control panel XAMPP Anda.
    * Buka browser Anda dan akses proyek melalui URL:
        `http://localhost/nama-folder-proyek-anda`

---
*Terima kasih telah mengunjungi repositori ini!*
