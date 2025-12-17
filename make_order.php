<?php
include "config/db.php";

$user_name = isset($_SESSION['user']['nama']) ? $_SESSION['user']['nama'] : '';
$user_id = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$telp = mysqli_real_escape_string($conn, $_POST['telp']);

// Cek apakah kolom user_id ada di tabel buy
$col = mysqli_query($conn, "SHOW COLUMNS FROM buy LIKE 'user_id'");
$has_user_id = ($col && mysqli_num_rows($col) > 0);

foreach ($_SESSION['cart'] as $item) {
    $item_name = mysqli_real_escape_string($conn, $item['nama']);
    $qty = (int)$item['qty'];
    $total = (int)$item['harga'] * $qty;

    if ($has_user_id && $user_id) {
        $sql = "INSERT INTO buy (user_id, nama_user, nama_item, jumlah, total, alamat, telp_penerima, status)
                VALUES ($user_id, '" . mysqli_real_escape_string($conn, $user_name) . "', '$item_name', $qty, $total, '$alamat', '$telp', 'PENDING')";
    } else {
        $sql = "INSERT INTO buy (nama_user, nama_item, jumlah, total, alamat, telp_penerima, status)
                VALUES ('" . mysqli_real_escape_string($conn, $user_name) . "', '$item_name', $qty, $total, '$alamat', '$telp', 'PENDING')";
    }

    mysqli_query($conn, $sql);
}

// Hapus cart setelah order
unset($_SESSION['cart']);

header("Location: index.php");
?>