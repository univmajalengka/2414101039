<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Query Hapus
    $query = "DELETE FROM pesanan WHERE id = '$id'";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil dihapus!'); window.location.href='list_pesanan.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>