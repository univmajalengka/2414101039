<?php 
$mode = isset($_GET['mode']) ? $_GET['mode'] : 'tiket_masuk';
$nama_item = isset($_GET['nama']) ? $_GET['nama'] : '';
$harga_item = isset($_GET['harga']) ? $_GET['harga'] : 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pemesanan - JANS PARK</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="logo">JANS PARK</div>
        <ul><li><a href="index.php">Kembali ke Beranda</a></li></ul>
    </nav>

    <div class="container">
        <h2>Formulir Pemesanan 
            <?php echo ($mode == 'wahana') ? 'Tiket Wahana' : 'Tiket Masuk'; ?> 
            <?php echo ($mode != 'wahana') ? '& Layanan' : ''; ?>
        </h2>
        
        <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
            
            <form id="bookingForm" action="proses_booking.php" method="POST" onsubmit="return validasiForm()" novalidate>
                
                <div class="form-group">
                    <label>Nama Pemesan <span style="color:red">*</span></label>
                    <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap">
                </div>
                <div class="form-group">
                    <label>Nomor HP/WhatsApp <span style="color:red">*</span></label>
                    <input type="number" name="no_hp" id="no_hp" placeholder="Contoh: 08123456789">
                </div>
                <div class="form-group">
                    <label>Tanggal Kunjungan <span style="color:red">*</span></label>
                    <input type="date" name="tanggal" id="tanggal">
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label>Lama Kunjungan (Hari)</label>
                        <input type="number" id="durasi" name="durasi" min="1" value="1">
                    </div>
                    <div class="form-group">
                        <label>Jumlah Peserta <span style="color:red">*</span></label>
                        <input type="number" id="peserta" name="peserta" min="1" value="1">
                    </div>
                </div>

                <div style="background: #f0f8ff; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #cce7ff;">
                    <h3 style="margin-top:0; color: #006994;">Pilihan Tiket</h3>

                    <?php if ($mode == 'wahana'): ?>
                        <div class="form-group">
                            <label>Item Wahana</label>
                            <input type="text" value="<?php echo $nama_item; ?>" readonly style="background:#eee; font-weight:bold;">
                            <input type="hidden" name="jenis_tiket" value="<?php echo $nama_item; ?>">
                            <input type="hidden" name="hari_kunjungan" value="-">
                            <input type="hidden" id="harga_fixed" value="<?php echo $harga_item; ?>">
                        </div>
                        <p style="font-size: 0.9rem; color: #666;"><i>*Tiket berlaku untuk 1x main.</i></p>

                    <?php else: ?>
                        <div class="form-group">
                            <label>Jenis Tiket</label>
                            <select id="jenis_tiket" name="jenis_tiket" style="width:100%; padding:10px;">
                                <option value="reguler">Tiket Reguler (Tiket Masuk Saja)</option>
                                <option value="hemat">Tiket Hemat (Masuk + Wahana)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Hari Kunjungan</label>
                            <select id="hari_kunjungan" name="hari_kunjungan" style="width:100%; padding:10px;">
                                <option value="weekday">Weekday (Senin - Jumat)</option>
                                <option value="weekend">Weekend (Sabtu - Minggu / Libur)</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($mode != 'wahana'): ?>
                <div style="background: #fff8e1; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffe0b2;">
                    <h3 style="margin-top:0; color: #e65100;">Layanan Tambahan (Opsional)</h3>
                    <div class="checkbox-group">
                        <div style="margin-bottom: 10px;">
                            <input type="checkbox" id="srv_travel" name="srv_travel" value="200000">
                            <label for="srv_travel">Transportasi Travel (Rp 200.000 /org/hari)</label>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <input type="checkbox" id="srv_makan" name="srv_makan" value="150000">
                            <label for="srv_makan">Paket Makan (Rp 150.000 /org/hari)</label>
                        </div>
                        <div style="margin-bottom: 10px;">
                            <input type="checkbox" id="srv_inap" name="srv_inap" value="250000">
                            <label for="srv_inap">Penginapan (Rp 250.000 /org/hari)</label>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="price-display">
                    <p>Harga Tiket Dasar (Per Orang): <strong id="txt_harga_paket">Rp 0</strong></p>
                    
                    <?php if ($mode != 'wahana'): ?>
                        <p>Biaya Layanan Tambahan: <strong id="txt_biaya_layanan">Rp 0</strong></p>
                        <hr>
                    <?php endif; ?>

                    <p style="font-size: 1.2rem; margin-top: 10px;">Total Tagihan: <strong id="txt_total_tagihan" style="color: var(--secondary); font-size: 1.8rem;">Rp 0</strong></p>
                    
                    <input type="hidden" name="harga_paket" id="input_harga_paket">
                    <input type="hidden" name="total_tagihan" id="input_total_tagihan">
                </div>

                <button type="submit" class="btn" style="width: 100%; margin-top: 25px;">Konfirmasi Pesanan</button>
            </form>
        </div>
    </div>

    <script src="script.js?v=7"></script>

    <script>
    function validasiForm() {
        // Ambil value dari form
        var nama = document.getElementById("nama").value.trim();
        var hp = document.getElementById("no_hp").value.trim();
        var tgl = document.getElementById("tanggal").value;
        var peserta = document.getElementById("peserta").value;

        var pesanError = "";

        // Logika Pengecekan
        if (nama === "") {
            pesanError += "- Nama Pemesan wajib diisi!\n";
        }
        if (hp === "") {
            pesanError += "- Nomor HP wajib diisi!\n";
        }
        if (tgl === "") {
            pesanError += "- Tanggal Kunjungan wajib dipilih!\n";
        }
        if (peserta === "" || peserta < 1) {
            pesanError += "- Jumlah peserta minimal 1 orang!\n";
        }

        // Jika ada error, tampilkan Alert dan Batalkan Submit
        if (pesanError !== "") {
            alert("⚠️ MOHON LENGKAPI DATA BERIKUT:\n\n" + pesanError);
            return false; // Stop form dari pengiriman
        }

        // Jika lolos, izinkan kirim
        return true; 
    }
    </script>
</body>
</html>