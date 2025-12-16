<?php
include "../config/db.php";

$id = $_POST['item_id'];
$qty = (int)$_POST['jumlah'];

$item = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM items WHERE id=$id")
);

if (!isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id] = [
        'nama' => $item['nama_item'],
        'harga' => $item['harga'],
        'qty' => $qty
    ];
} else {
    $_SESSION['cart'][$id]['qty'] += $qty;
}
?>