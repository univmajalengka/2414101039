document.addEventListener('DOMContentLoaded', function() {
    // 1. Ambil Semua Elemen
    const durasiInput = document.getElementById('durasi');
    const pesertaInput = document.getElementById('peserta');
    
    // Checkbox Layanan
    const srvTravel = document.getElementById('srv_travel');
    const srvMakan = document.getElementById('srv_makan');
    const srvInap = document.getElementById('srv_inap');

    // Elemen Mode & Dropdown
    const hargaFixedInput = document.getElementById('harga_fixed');
    const jenisTiketInput = document.getElementById('jenis_tiket');
    const hariKunjunganInput = document.getElementById('hari_kunjungan');

    // Display
    const txtHargaPaket = document.getElementById('txt_harga_paket');
    const txtBiayaLayanan = document.getElementById('txt_biaya_layanan');
    const txtTotalTagihan = document.getElementById('txt_total_tagihan');
    
    const inputHargaPaket = document.getElementById('input_harga_paket');
    const inputTotalTagihan = document.getElementById('input_total_tagihan');

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
    }

    // --- FUNGSI HITUNG UTAMA ---
    function hitungTotal() {
        // Ambil Nilai Input (Default 0 atau 1)
        let peserta = parseInt(pesertaInput.value) || 0;
        let durasi = parseInt(durasiInput.value) || 0;
        
        // 1. HITUNG HARGA TIKET (BASE)
        let hargaTiketSatuan = 0;

        if (hargaFixedInput) {
            // Mode Wahana
            hargaTiketSatuan = parseInt(hargaFixedInput.value) || 0;
        } else if (jenisTiketInput && hariKunjunganInput) {
            // Mode Tiket Masuk (Reguler/Hemat)
            let jenis = jenisTiketInput.value;
            let hari = hariKunjunganInput.value;

            if (jenis === 'reguler') {
                hargaTiketSatuan = (hari === 'weekday') ? 30000 : 40000;
            } else if (jenis === 'hemat') {
                hargaTiketSatuan = (hari === 'weekday') ? 90000 : 100000;
            }
        }
        
        let totalBiayaTiket = hargaTiketSatuan * peserta;

        // 2. HITUNG HARGA LAYANAN TAMBAHAN (Travel + Makan + Inap)
        let hargaLayananPerHari = 0;
        if (srvTravel && srvTravel.checked) hargaLayananPerHari += parseInt(srvTravel.value);
        if (srvMakan && srvMakan.checked) hargaLayananPerHari += parseInt(srvMakan.value);
        if (srvInap && srvInap.checked) hargaLayananPerHari += parseInt(srvInap.value);

        // Rumus Layanan: (Harga x Hari x Orang)
        let totalBiayaLayanan = hargaLayananPerHari * durasi * peserta;

        // 3. GRAND TOTAL
        let grandTotal = totalBiayaTiket + totalBiayaLayanan;

        // --- UPDATE UI ---
        if (txtHargaPaket) txtHargaPaket.innerText = formatRupiah(hargaTiketSatuan);
        if (txtBiayaLayanan) txtBiayaLayanan.innerText = formatRupiah(totalBiayaLayanan);
        if (txtTotalTagihan) txtTotalTagihan.innerText = formatRupiah(grandTotal);

        // --- UPDATE HIDDEN INPUT ---
        if (inputHargaPaket) inputHargaPaket.value = hargaTiketSatuan;
        if (inputTotalTagihan) inputTotalTagihan.value = grandTotal;
    }

    // --- PASANG LISTENER ---
    if (pesertaInput) {
        // Input Angka
        pesertaInput.addEventListener('input', hitungTotal);
        if (durasiInput) durasiInput.addEventListener('input', hitungTotal);

        // Checkbox
        if (srvTravel) srvTravel.addEventListener('change', hitungTotal);
        if (srvMakan) srvMakan.addEventListener('change', hitungTotal);
        if (srvInap) srvInap.addEventListener('change', hitungTotal);

        // Dropdown (Jika ada)
        if (jenisTiketInput) jenisTiketInput.addEventListener('change', hitungTotal);
        if (hariKunjunganInput) hariKunjunganInput.addEventListener('change', hitungTotal);

        // Run First Time
        hitungTotal();
    }
});