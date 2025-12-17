<?php
// Mulai session jika belum
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
include "../config/db.php";

// Cek akses admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    die("Akses ditolak: Anda bukan Admin.");
}

// --- LOGIKA TAMBAHAN UNTUK STATISTIK DASHBOARD ---
// 1. Hitung Total Produk
$q_items = mysqli_query($conn, "SELECT COUNT(*) as total FROM items");
$total_items = mysqli_fetch_assoc($q_items)['total'];

// 2. Hitung Total Order
$q_orders = mysqli_query($conn, "SELECT COUNT(*) as total FROM buy");
$total_orders = mysqli_fetch_assoc($q_orders)['total'];

// 3. Hitung Estimasi Pendapatan (Hanya yang status SENT)
$q_revenue = mysqli_query($conn, "SELECT SUM(total) as total FROM buy WHERE status='SENT'");
$row_revenue = mysqli_fetch_assoc($q_revenue);
$total_revenue = $row_revenue['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gitar Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="min-h-screen flex flex-col">
        
        <nav class="bg-gray-900 text-white shadow-lg sticky top-0 z-50">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-indigo-600 p-2 rounded-lg">
                        <i class="fa-solid fa-guitar text-xl"></i>
                    </div>
                    <span class="text-xl font-bold tracking-wide">Admin Panel</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-300 text-sm hidden md:inline">Halo, <?php echo htmlspecialchars($_SESSION['user']['nama']); ?></span>
                    <a href="../auth/logout.php" class="bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded-full transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <div class="container mx-auto px-6 py-8 flex-1">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-indigo-500 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Total Pendapatan (Sent)</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp <?= number_format($total_revenue, 0, ',', '.') ?></h3>
                    </div>
                    <div class="bg-indigo-50 text-indigo-600 w-12 h-12 rounded-full flex items-center justify-center text-xl">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-green-500 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Total Pesanan</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= $total_orders ?> Transaksi</h3>
                    </div>
                    <div class="bg-green-50 text-green-600 w-12 h-12 rounded-full flex items-center justify-center text-xl">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border-l-4 border-blue-500 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium uppercase">Total Produk</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= $total_items ?> Item</h3>
                    </div>
                    <div class="bg-blue-50 text-blue-600 w-12 h-12 rounded-full flex items-center justify-center text-xl">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Produk</h2>
                <a href="add_item.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg shadow-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Tambah Barang
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 mb-12">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold tracking-wider">
                            <tr>
                                <th class="p-4 border-b">Gambar</th>
                                <th class="p-4 border-b">Nama Produk</th>
                                <th class="p-4 border-b">Harga</th>
                                <th class="p-4 border-b">Deskripsi</th>
                                <th class="p-4 border-b text-center">ID</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php
                            $q = mysqli_query($conn, "SELECT * FROM items ORDER BY id DESC");
                            while ($item = mysqli_fetch_assoc($q)) {
                            ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                                        <img src="<?= htmlspecialchars($item['gambar']) ?>" class="w-full h-full object-cover" alt="Product">
                                    </div>
                                </td>
                                <td class="p-4 font-semibold text-gray-800"><?= htmlspecialchars($item['nama_item']) ?></td>
                                <td class="p-4 text-indigo-600 font-bold">Rp <?= number_format($item['harga']) ?></td>
                                <td class="p-4 text-gray-500 text-sm max-w-xs truncate"><?= htmlspecialchars($item['deskripsi']) ?></td>
                                <td class="p-4 text-center text-gray-400 text-xs">#<?= $item['id'] ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-6">Daftar Pesanan Masuk</h2>
            
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold tracking-wider">
                            <tr>
                                <th class="p-4 border-b">Order ID</th>
                                <th class="p-4 border-b">Pelanggan</th>
                                <th class="p-4 border-b">Item</th>
                                <th class="p-4 border-b">Total</th>
                                <th class="p-4 border-b">Status Saat Ini</th>
                                <th class="p-4 border-b">Update Status</th>
                                <th class="p-4 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php
                            $q = mysqli_query($conn, "SELECT * FROM buy ORDER BY id DESC");
                            while ($b = mysqli_fetch_assoc($q)) {
                            ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4 text-sm font-mono text-gray-500">#<?= $b['id'] ?></td>
                                <td class="p-4">
                                    <div class="font-bold text-gray-800"><?= htmlspecialchars($b['nama_user']) ?></div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fa-solid fa-location-dot mr-1"></i> <?= htmlspecialchars($b['alamat']) ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <i class="fa-solid fa-phone mr-1"></i> <?= htmlspecialchars($b['telp_penerima']) ?>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="block font-medium text-gray-800"><?= htmlspecialchars($b['nama_item']) ?></span>
                                    <span class="text-xs text-gray-500">Qty: <?= $b['jumlah'] ?></span>
                                </td>
                                <td class="p-4 font-bold text-indigo-600">Rp <?= number_format($b['total']) ?></td>
                                <td class="p-4">
                                    <?php if ($b['status'] == 'SENT'): ?>
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            SENT
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                            PENDING
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <form method="post" action="update_status.php" class="flex items-center gap-2">
                                        <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                        <select name="status" class="text-sm border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 border p-1.5 bg-gray-50">
                                            <option value="PENDING" <?= $b['status']=='PENDING' ? 'selected' : '' ?>>Pending</option>
                                            <option value="SENT" <?= $b['status']=='SENT' ? 'selected' : '' ?>>Sent</option>
                                        </select>
                                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white p-2 rounded transition" title="Simpan Perubahan">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="p-4 text-center">
                                    <a href="delete_order.php?id=<?= $b['id'] ?>" 
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition"
                                       onclick="return confirm('Yakin ingin menghapus order ini secara permanen?')" title="Hapus Order">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
