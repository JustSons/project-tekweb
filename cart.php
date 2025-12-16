<?php
include "config/db.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">🛒 Cart</h1>

<?php if (empty($cart)) { ?>
    <div class="bg-white p-6 rounded shadow text-center">
        Cart kosong
    </div>
<?php exit; } ?>

<form method="post" action="make_order.php">

<div class="bg-white p-4 rounded shadow mb-4">
<table class="w-full border">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">Item</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>

<?php
$grandTotal = 0;
foreach ($cart as $item) {
    $total = $item['harga'] * $item['qty'];
    $grandTotal += $total;
?>
<tr class="border-t text-center">
    <td class="p-2"><?= $item['nama'] ?></td>
    <td><?= $item['qty'] ?></td>
    <td>Rp <?= number_format($item['harga']) ?></td>
    <td>Rp <?= number_format($total) ?></td>
</tr>
<?php } ?>

    </tbody>
</table>

<div class="text-right font-bold mt-4">
    Grand Total: Rp <?= number_format($grandTotal) ?>
</div>
</div>

<!-- FORM ALAMAT -->
<div class="bg-white p-4 rounded shadow">
    <h2 class="font-bold mb-3">Alamat Penerima</h2>

    <textarea name="alamat" required
        class="w-full border p-2 rounded mb-3"
        placeholder="Masukkan alamat lengkap"></textarea>

    <input type="text" name="telp" required
        class="w-full border p-2 rounded mb-4"
        placeholder="No. Telepon Penerima">

    <button
        class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
        Make Order
    </button>
</div>

</form>

</body>
</html>
