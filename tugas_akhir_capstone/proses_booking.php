<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil Data
    $nama = trim($_POST['nama']);
    $hp = trim($_POST['no_hp']);
    $tgl = $_POST['tanggal'];
    $durasi = $_POST['durasi'];
    $peserta = $_POST['peserta'];
    
    // 2. VALIDASI PHP (Double Check)
    if (empty($nama) || empty($hp) || empty($tgl) || empty($peserta)) {
        echo "<script>
                alert('GAGAL! Data tidak boleh kosong. Silakan ulangi.');
                window.history.back();
              </script>";
        exit; // Stop proses
    }

    // Lanjut ambil data lainnya
    $jenis_tiket = $_POST['jenis_tiket'];
    $hari_kunjungan = $_POST['hari_kunjungan'];
    
    $srv_travel = isset($_POST['srv_travel']) ? 1 : 0;
    $srv_makan = isset($_POST['srv_makan']) ? 1 : 0;
    $srv_inap = isset($_POST['srv_inap']) ? 1 : 0;
    
    $harga_paket = $_POST['harga_paket'];
    $total = $_POST['total_tagihan'];

    // 3. Simpan ke Database
    $query = "INSERT INTO pesanan (
                nama_pemesan, nomor_hp, tanggal_pesan, 
                durasi_wisata, jumlah_peserta, 
                jenis_tiket, hari_kunjungan, 
                layanan_travel, layanan_makan, layanan_penginapan,
                harga_paket, total_tagihan
              ) 
              VALUES (
                '$nama', '$hp', '$tgl', 
                '$durasi', '$peserta', 
                '$jenis_tiket', '$hari_kunjungan', 
                '$srv_travel', '$srv_makan', '$srv_inap',
                '$harga_paket', '$total'
              )";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Pesanan Berhasil Disimpan!'); window.location.href='list_pesanan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>