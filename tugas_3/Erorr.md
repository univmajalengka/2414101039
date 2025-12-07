# Dokumentasi Deteksi dan Analisis Error
Dokumen ini berisi uraian lengkap mengenai error yang teridentifikasi pada kode program pendaftaran siswa, mulai dari kesalahan sintaks (*Syntax Error*), kesalahan logika (*Logic Error*), hingga celah keamanan (*Security Vulnerability*), beserta cara memperbaikinya.

---

## 1. Error Penulisan Variabel (Missing $)
* **Pesan Error Lengkap:**
  `Parse error: syntax error, unexpected '=' in ...\proses-pendaftaran-2.php on line 11`
* **Jenis Error:**
  Syntax Error (PHP)
* **Letak File dan Baris:**
  File: `proses-pendaftaran-2.php`
  Baris: 11 (pada kode asli)
* **Penyebab:**
  Variabel `sekolah` dideklarasikan tanpa menggunakan simbol dolar (`$`). Dalam PHP, semua variabel wajib diawali dengan simbol `$` agar dikenali oleh *interpreter*.
* **Cara Memperbaiki:**
  Menambahkan tanda `$` sebelum nama variabel.
  * *Salah:* `sekolah = $_POST['sekolah_asal'];`
  * *Benar:* `$sekolah = $_POST['sekolah_asal'];`

---

## 2. Error Sintaks SQL (Keyword VALUE)
* **Pesan Error Lengkap:**
  Potensi error SQL: *You have an error in your SQL syntax... near 'VALUE'* (Tergantung versi database, namun tidak sesuai standar ANSI SQL).
* **Jenis Error:**
  Syntax Error (SQL)
* **Letak File dan Baris:**
  File: `proses-pendaftaran-2.php`
  Baris: 14
* **Penyebab:**
  Query `INSERT` menggunakan keyword `VALUE`. Standar SQL yang benar dan didukung secara universal untuk menyisipkan data adalah `VALUES`.
* **Cara Memperbaiki:**
  Mengganti keyword `VALUE` menjadi `VALUES`.
  * *Salah:* `... sekolah_asal) VALUE ('$nama', ...`
  * *Benar:* `... sekolah_asal) VALUES ('$nama', ...`

---

## 3. Kesalahan Penulisan Redirection (Typo)
* **Pesan Error Lengkap:**
  `404 Not Found` (Jika kondisi `else` terpenuhi).
* **Jenis Error:**
  Logic Error / Typo
* **Letak File dan Baris:**
  File: `proses-pendaftaran-2.php`
  Baris: 22
* **Penyebab:**
  Terdapat kesalahan penulisan (*typo*) pada nama file tujuan pengalihan halaman. Tertulis `indek.ph` yang seharusnya `index.php`.
* **Cara Memperbaiki:**
  Memperbaiki nama file tujuan redirect.
  * *Salah:* `header('Location: indek.ph?status=gagal');`
  * *Benar:* `header('Location: index.php?status=gagal');`

---

## 4. Error Deklarasi HTML (Doctype)
* **Pesan Error Lengkap:**
  Browser Console Warning: *Quirks Mode triggered*.
* **Jenis Error:**
  Syntax Error (HTML)
* **Letak File dan Baris:**
  File: `form-daftar.php`
  Baris: 1
* **Penyebab:**
  Penulisan tag DOCTYPE tidak valid (`<DOCTYPE >`). Hal ini menyebabkan browser tidak merender halaman menggunakan standar HTML5 yang benar.
* **Cara Memperbaiki:**
  Mengubah deklarasi menjadi standar HTML5.
  * *Salah:* `<DOCTYPE >`
  * *Benar:* `<!DOCTYPE html>`

---

## 5. Celah Keamanan: SQL Injection (Best Practice)
* **Pesan Error Lengkap:**
  Tidak ada pesan error (*Silent Vulnerability*), namun kode tidak aman.
* **Jenis Error:**
  Security Vulnerability / Bad Practice
* **Letak File dan Baris:**
  File: `proses-pendaftaran-2.php`
  Baris: 14-15
* **Penyebab:**
  Kode menggunakan teknik penggabungan string (*concatenation*) secara langsung untuk memasukkan input user (`$_POST`) ke dalam query SQL. Hal ini membuat aplikasi rentan disusupi perintah SQL berbahaya (*SQL Injection*).
* **Cara Memperbaiki:**
  [cite_start]Mengganti metode *Direct Query* dengan **Prepared Statements** menggunakan fungsi `mysqli_stmt_prepare`, `mysqli_stmt_bind_param`, dan `mysqli_stmt_execute`[cite: 24].

  **Implementasi Kode Perbaikan:**
  ```php
  $sql = "INSERT INTO calon_siswa (nama, alamat, jenis_kelamin, agama, sekolah_asal) VALUES (?, ?, ?, ?, ?)";
  $stmt = mysqli_stmt_init($db);
  if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, "sssss", $nama, $alamat, $jk, $agama, $sekolah);
      $execute = mysqli_stmt_execute($stmt);
      // Lanjutkan ke logika redirect...
  }
