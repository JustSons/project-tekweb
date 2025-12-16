<?php
include "../config/db.php";
if ($_SESSION['user']['role'] != 'admin') die("Akses ditolak");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    if ($id > 0 && in_array($status, ['PENDING', 'SENT'])) {
        mysqli_query($conn, "UPDATE buy SET status='$status' WHERE id=$id");
    }
}
header("Location: index.php");
?>