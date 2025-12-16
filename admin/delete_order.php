<?php
include "../config/db.php";
if ($_SESSION['user']['role'] != 'admin') die("Akses ditolak");

$id = (int)$_GET['id'];
if ($id > 0) {
    mysqli_query($conn, "DELETE FROM buy WHERE id=$id");
}
header("Location: index.php");
?>