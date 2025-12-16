<?php
include "config/db.php";

$user = $_SESSION['user']['nama'];
$alamat = $_POST['alamat'];
$telp = $_POST['telp'];

foreach ($_SESSION['cart'] as $item) {
    $total = $item['harga'] * $item['qty'];

    mysqli_query($conn, "INSERT INTO buy
        (nama_user, nama_item, jumlah, total, alamat, telp_penerima, status)
        VALUES
        ('$user','{$item['nama']}',{$item['qty']},$total,'$alamat','$telp','PENDING')");
}

// Hapus cart setelah order
unset($_SESSION['cart']);

header("Location: index.php");
?>