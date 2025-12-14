<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $hp = $_POST['no_hp'];
    $tgl = $_POST['tanggal'];
    
    $durasi = $_POST['durasi'];
    $peserta = $_POST['peserta'];
    
    $jenis_tiket = $_POST['jenis_tiket'];
    $hari_kunjungan = $_POST['hari_kunjungan'];
    
    // Checkbox (Pakai isset karena kalau tidak dicentang tidak terkirim)
    $srv_travel = isset($_POST['srv_travel']) ? 1 : 0;
    $srv_makan = isset($_POST['srv_makan']) ? 1 : 0;
    $srv_inap = isset($_POST['srv_inap']) ? 1 : 0;
    
    $harga_paket = $_POST['harga_paket'];
    $total = $_POST['total_tagihan'];

    $query = "UPDATE pesanan SET 
              nama_pemesan='$nama', 
              nomor_hp='$hp',
              tanggal_pesan='$tgl',
              durasi_wisata='$durasi', 
              jumlah_peserta='$peserta',
              jenis_tiket='$jenis_tiket',
              hari_kunjungan='$hari_kunjungan',
              layanan_travel='$srv_travel',
              layanan_makan='$srv_makan',
              layanan_penginapan='$srv_inap',
              harga_paket='$harga_paket',
              total_tagihan='$total'
              WHERE id='$id'";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data Berhasil Diubah!'); window.location.href='list_pesanan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>