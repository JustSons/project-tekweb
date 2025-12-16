<?php
include "../config/db.php";
if ($_SESSION['user']['role'] != 'admin') die("Akses ditolak");
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h2 class="text-2xl font-bold mb-4">Admin Panel</h2>

<div class="mb-4 flex justify-between items-center">
    <a href="add_item.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Add Item</a>
    <a href="../auth/logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Logout</a>
</div>

<h3 class="text-xl font-semibold mb-2">Items</h3>
<div class="bg-white shadow rounded p-4 mb-6">
<table class="w-full border">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">ID</th>
            <th>Nama Item</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Gambar</th>
        </tr>
    </thead>
    <tbody>
<?php
$q = mysqli_query($conn, "SELECT * FROM items");
while ($item = mysqli_fetch_assoc($q)) {
?>
<tr class="border-t">
    <td class="p-2"><?= $item['id'] ?></td>
    <td><?= $item['nama_item'] ?></td>
    <td>Rp <?= number_format($item['harga']) ?></td>
    <td><?= $item['deskripsi'] ?></td>
    <td><img src="<?= $item['gambar'] ?>" class="w-16 h-16 object-cover"></td>
</tr>
<?php } ?>
    </tbody>
</table>
</div>

<h3 class="text-xl font-semibold mb-2">Orders</h3>
<div class="bg-white shadow rounded p-4">
<table class="w-full border">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2">ID</th>
            <th>Nama User</th>
            <th>Nama Item</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Status</th>
            <th>Alamat</th>
            <th>Telp Penerima</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
<?php
$q = mysqli_query($conn, "SELECT * FROM buy");
while ($b = mysqli_fetch_assoc($q)) {
?>
<tr class="border-t">
    <td class="p-2"><?= $b['id'] ?></td>
    <td><?= $b['nama_user'] ?></td>
    <td><?= $b['nama_item'] ?></td>
    <td><?= $b['jumlah'] ?></td>
    <td>Rp <?= number_format($b['total']) ?></td>
    <td>
        <span class="<?= $b['status']=='PENDING' ? 'text-yellow-500' : 'text-green-600' ?>">
            <?= $b['status'] ?>
        </span>
    </td>
    <td><?= $b['alamat'] ?></td>
    <td><?= $b['telp_penerima'] ?></td>
    <td>
        <form method="post" action="update_status.php" class="inline">
            <input type="hidden" name="id" value="<?= $b['id'] ?>">
            <select name="status" class="border p-1 rounded">
                <option value="PENDING" <?= $b['status']=='PENDING' ? 'selected' : '' ?>>PENDING</option>
                <option value="SENT" <?= $b['status']=='SENT' ? 'selected' : '' ?>>SENT</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded ml-1">Update</button>
        </form>
        <a href="delete_order.php?id=<?= $b['id'] ?>"
           class="text-red-500 hover:underline ml-2" onclick="return confirm('Hapus order ini?')">Delete</a>
    </td>
</tr>
<?php } ?>
    </tbody>
</table>
</div>

</body>
</html>
