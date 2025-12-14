<?php
include 'koneksi.php';
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id='$id'");
$data = mysqli_fetch_array($query);

// LOGIKA DETEKSI: Apakah ini Tiket Masuk (Reguler/Hemat) atau Wahana?
$tiket = $data['jenis_tiket'];
$is_tiket_masuk = ($tiket == 'reguler' || $tiket == 'hemat');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Pesanan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Modifikasi Pesanan (ID: <?php echo $data['id']; ?>)</h2>
        <div style="background: white; padding: 40px; border-radius: 10px;">
            
            <form action="update_booking.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                
                <div class="form-group">
                    <label>Nama Pemesan</label>
                    <input type="text" name="nama" value="<?php echo $data['nama_pemesan']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Nomor HP</label>
                    <input type="text" name="no_hp" value="<?php echo $data['nomor_hp']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Tanggal Kunjungan</label>
                    <input type="date" name="tanggal" value="<?php echo $data['tanggal_pesan']; ?>" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    
                    <?php if ($is_tiket_masuk): ?>
                    <div class="form-group">
                        <label>Lama Kunjungan (Hari)</label>
                        <input type="number" id="durasi" name="durasi" min="1" value="<?php echo $data['durasi_wisata']; ?>" required>
                    </div>
                    <?php else: ?>
                        <input type="hidden" id="durasi" name="durasi" value="1">
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Jumlah Peserta</label>
                        <input type="number" id="peserta" name="peserta" value="<?php echo $data['jumlah_peserta']; ?>" required>
                    </div>
                </div>

                <div style="background: #f0f8ff; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #cce7ff;">
                    <h3 style="margin-top:0; color: #006994;">Jenis Tiket</h3>
                    
                    <?php if ($is_tiket_masuk): ?>
                        <div class="form-group">
                            <label>Tipe Tiket</label>
                            <select id="jenis_tiket" name="jenis_tiket" style="width:100%; padding:10px;">
                                <option value="reguler" <?php echo ($tiket == 'reguler') ? 'selected' : ''; ?>>Tiket Reguler</option>
                                <option value="hemat" <?php echo ($tiket == 'hemat') ? 'selected' : ''; ?>>Tiket Hemat</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Hari</label>
                            <select id="hari_kunjungan" name="hari_kunjungan" style="width:100%; padding:10px;">
                                <option value="weekday" <?php echo ($data['hari_kunjungan'] == 'weekday') ? 'selected' : ''; ?>>Weekday</option>
                                <option value="weekend" <?php echo ($data['hari_kunjungan'] == 'weekend') ? 'selected' : ''; ?>>Weekend</option>
                            </select>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label>Wahana</label>
                            <input type="text" value="<?php echo $tiket; ?>" readonly style="background:#eee; font-weight:bold;">
                            <input type="hidden" id="harga_fixed" value="<?php echo $data['harga_paket']; ?>">
                            <input type="hidden" name="jenis_tiket" value="<?php echo $tiket; ?>">
                            <input type="hidden" name="hari_kunjungan" value="-">
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($is_tiket_masuk): ?>
                <div style="background: #fff8e1; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffe0b2;">
                    <h3 style="margin-top:0; color: #e65100;">Opsi Layanan Tambahan</h3>
                    <div class="checkbox-group">
                        <div style="margin-bottom: 10px;">
                            <input type="checkbox" id="srv_travel" name="srv_travel" value="200000" <?php echo ($data['layanan_travel']==1)?'checked':''; ?>>
                            <label>Transportasi Travel (Rp 200.000)</label>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <input type="checkbox" id="srv_makan" name="srv_makan" value="150000" <?php echo ($data['layanan_makan']==1)?'checked':''; ?>>
                            <label>Paket Makan (Rp 150.000)</label>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <input type="checkbox" id="srv_inap" name="srv_inap" value="250000" <?php echo ($data['layanan_penginapan']==1)?'checked':''; ?>>
                            <label>Penginapan (Rp 250.000)</label>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="price-display">
                    <p>Harga Tiket Dasar: <strong id="txt_harga_paket">Rp <?php echo number_format($data['harga_paket']); ?></strong></p>
                    <?php if ($is_tiket_masuk): ?>
                        <p>Biaya Layanan: <strong id="txt_biaya_layanan">Rp 0</strong></p>
                        <hr>
                    <?php endif; ?>
                    <p>Total Tagihan Baru: <strong id="txt_total_tagihan">Rp <?php echo number_format($data['total_tagihan']); ?></strong></p>
                    
                    <input type="hidden" name="total_tagihan" id="input_total_tagihan" value="<?php echo $data['total_tagihan']; ?>">
                    <input type="hidden" name="harga_paket" id="input_harga_paket" value="<?php echo $data['harga_paket']; ?>">
                </div>

                <button type="submit" class="btn">Simpan Perubahan</button>
                <a href="list_pesanan.php" class="btn" style="background:#888">Batal</a>
            </form>
        </div>
    </div>
    
    <script src="script.js?v=5"></script>
</body>
</html>