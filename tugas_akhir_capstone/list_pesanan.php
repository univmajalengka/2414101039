<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Pesanan - Jans Park</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        th, td { padding: 12px 15px; border-bottom: 1px solid #eee; text-align: left; font-size: 14px; }
        th { background-color: var(--secondary); color: white; white-space: nowrap; }
        tr:hover { background-color: #f1f1f1; }
        
        .btn-action { padding: 6px 10px; text-decoration: none; border-radius: 4px; font-size: 11px; color: white; font-weight: bold; margin-right: 2px; display: inline-block;}
        .btn-edit { background-color: #f39c12; }
        .btn-delete { background-color: #e74c3c; }

        .badge-yes { color: green; font-weight: bold; background: #e8f5e9; padding: 2px 6px; border-radius: 4px; }
        .badge-no { color: #777; }
        .badge-na { color: #ccc; }
    </style>
</head>
<body>
    <nav>
        <ul><li><a href="index.php">Ke Beranda</a></li></ul>
    </nav>
    
    <div class="container" style="max-width: 1300px;">
        <h2>Daftar Pesanan Masuk</h2>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Tiket</th>
                        <th>Tgl</th>
                        <th>Durasi</th>
                        <th style="text-align:center;">Travel</th>
                        <th style="text-align:center;">Makan</th>
                        <th style="text-align:center;">Inap</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // --- PERBAIKAN: Fungsi didefinisikan DI LUAR loop ---
                    function cekStatus($status, $is_bundling) {
                        if (!$is_bundling) {
                            return '<span class="badge-na">-</span>'; 
                        }
                        return ($status == 1) 
                            ? '<span class="badge-yes">Ya</span>' 
                            : '<span class="badge-no">Tidak</span>';
                    }
                    // ----------------------------------------------------

                    $query = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY id DESC");
                    
                    while($data = mysqli_fetch_array($query)){
                        // Cek apakah ini tiket bundling
                        $jenis = $data['jenis_tiket'];
                        $is_bundling = ($jenis == 'reguler' || $jenis == 'hemat');
                        
                        echo "<tr>";
                        echo "<td>".$data['id']."</td>";
                        echo "<td>".$data['nama_pemesan']."</td>";
                        echo "<td>".ucwords($jenis)."</td>";
                        echo "<td>".$data['tanggal_pesan']."</td>";
                        echo "<td>". ($is_bundling ? $data['durasi_wisata'] . " Hari" : "-") ."</td>";

                        // Panggil fungsi yang sudah aman
                        echo "<td style='text-align:center;'>" . cekStatus($data['layanan_travel'], $is_bundling) . "</td>";
                        echo "<td style='text-align:center;'>" . cekStatus($data['layanan_makan'], $is_bundling) . "</td>";
                        echo "<td style='text-align:center;'>" . cekStatus($data['layanan_penginapan'], $is_bundling) . "</td>";

                        echo "<td>Rp ".number_format($data['total_tagihan'])."</td>";
                        echo "<td>
                                <a href='form_edit.php?id=".$data['id']."' class='btn-action btn-edit'>Edit</a>
                                <a href='hapus_pesanan.php?id=".$data['id']."' class='btn-action btn-delete' onclick=\"return confirm('Yakin ingin menghapus data ini?');\">Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>