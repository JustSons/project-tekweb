<?php
session_start();

// Cek apakah ada ID dan ACTION (plus/minus) yang dikirim
if (isset($_GET['id']) && isset($_GET['action']) && isset($_SESSION['cart'])) {
    
    $target = $_GET['id'];
    $action = $_GET['action'];

    foreach ($_SESSION['cart'] as $key => $item) {
        // Logika pencocokan "Mata Dewa" (ID atau Key)
        $cocok_id = (isset($item['id']) && $item['id'] == $target);
        $cocok_key = ($key == $target);

        if ($cocok_id || $cocok_key) {
            
            // JIKA TOMBOL TAMBAH DITEKAN
            if ($action == 'plus') {
                $_SESSION['cart'][$key]['qty']++;
            } 
            // JIKA TOMBOL KURANG DITEKAN
            elseif ($action == 'minus') {
                // Cek dulu, kalau jumlahnya lebih dari 1, kurangi
                if ($_SESSION['cart'][$key]['qty'] > 1) {
                    $_SESSION['cart'][$key]['qty']--;
                } 
                // Kalau jumlahnya 1 dan dikurang, berarti dihapus
                else {
                    unset($_SESSION['cart'][$key]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']); // Rapikan index
                }
            }
            
            break; // Stop loop setelah ketemu
        }
    }
}

// Kembali ke keranjang
header("Location: cart.php");
exit();
?>