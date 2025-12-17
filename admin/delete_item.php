<?php
include "../config/db.php";
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    die("Akses ditolak");
}

$id = (int)$_GET['id'];
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM items WHERE id=$id");
}
header("Location: index.php");
?>