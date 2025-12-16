<?php
$conn = mysqli_connect("localhost", "root", "", "gitar_shop");
if (!$conn) {
    die("Koneksi gagal");
}
session_start();
?>
