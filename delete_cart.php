<?php
session_start();

// Cek apakah ada data ID yang dikirim
if (isset($_GET['id']) && isset($_SESSION['cart'])) {
    $target = $_GET['id'];

    // Loop semua isi keranjang untuk mencari target
    foreach ($_SESSION['cart'] as $key => $item) {
        
        // Cek 1: Apakah target cocok dengan ID Database? (Misal: id barang 15)
        // Kita pakai isset() untuk mencegah error jika item tidak punya ID
        $cocok_id = (isset($item['id']) && $item['id'] == $target);

        // Cek 2: Apakah target cocok dengan Nomor Urut Array? (Misal: urutan ke-0)
        // Ini solusi untuk barang yang tadi error (image_9671e5.png)
        $cocok_key = ($key == $target);

        // Jika salah satu cocok, HAPUS!
        if ($cocok_id || $cocok_key) {
            unset($_SESSION['cart'][$key]);
            
            // PENTING: Susun ulang nomor urut array (Re-index)
            // Supaya kalau hapus item ke-1, item ke-2 geser jadi ke-1.
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            
            break; // Stop looping karena tugas sudah selesai
        }
    }
}

// Kembalikan ke halaman keranjang
header("Location: cart.php");
exit();
?>