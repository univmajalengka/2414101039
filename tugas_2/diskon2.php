<?php

function hitungDiskon($totalBelanja) {
    if ($totalBelanja >= 100000) {
        $nominalDiskon = $totalBelanja * 0.10;
    } elseif ($totalBelanja >= 50000) {
        $nominalDiskon = $totalBelanja * 0.05;
    } else {
        $nominalDiskon = 0;
    }
    return $nominalDiskon;
}

echo "<h3>Hasil Pengujian Fungsi Diskon</h3>";

$totalBelanja = 120000;                 
$diskon = hitungDiskon($totalBelanja);  
$totalBayar = $totalBelanja - $diskon;  

echo "<strong>1. Kasus Belanja di atas Rp 100.000</strong><br>";
echo "Total Belanja : Rp " . number_format($totalBelanja, 0, ',', '.') . "<br>";
echo "Diskon (10%)  : Rp " . number_format($diskon, 0, ',', '.') . "<br>";
echo "Total Bayar   : Rp " . number_format($totalBayar, 0, ',', '.') . "<br>";
echo "<hr>";

$totalBelanja = 75000;                  
$diskon = hitungDiskon($totalBelanja);  
$totalBayar = $totalBelanja - $diskon;  

echo "<strong>2. Kasus Belanja antara Rp 50.000 - Rp 99.999</strong><br>";
echo "Total Belanja : Rp " . number_format($totalBelanja, 0, ',', '.') . "<br>";
echo "Diskon (5%)   : Rp " . number_format($diskon, 0, ',', '.') . "<br>";
echo "Total Bayar   : Rp " . number_format($totalBayar, 0, ',', '.') . "<br>";
echo "<hr>";

$totalBelanja = 45000;                  
$diskon = hitungDiskon($totalBelanja);  
$totalBayar = $totalBelanja - $diskon;  

echo "<strong>3. Kasus Belanja di bawah Rp 50.000</strong><br>";
echo "Total Belanja : Rp " . number_format($totalBelanja, 0, ',', '.') . "<br>";
echo "Diskon (0%)   : Rp " . number_format($diskon, 0, ',', '.') . "<br>";
echo "Total Bayar   : Rp " . number_format($totalBayar, 0, ',', '.') . "<br>";
echo "<hr>";

?>