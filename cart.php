<?php
// Pastikan session aktif
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
include "config/db.php";

// Cek Login
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

// Ambil data cart, default array kosong jika belum ada
$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Gitar Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-cart-shopping text-indigo-600"></i> Keranjang Belanja
            </h1>
            <a href="index.php" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition flex items-center gap-1">
                <i class="fa-solid fa-arrow-left"></i> Lanjut Belanja
            </a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">

        <?php if (empty($cart)) { ?>
            <div class="max-w-md mx-auto text-center py-16">
                <div class="bg-indigo-50 w-32 h-32 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-200">
                    <i class="fa-solid fa-cart-plus text-5xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Keranjangmu Kosong</h2>
                <p class="text-gray-500 mb-8">Sepertinya kamu belum memilih gitar impianmu. Yuk mulai belanja!</p>
                <a href="index.php" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-500/30">
                    Mulai Belanja
                </a>
            </div>
            <?php exit; // Hentikan script di sini agar form di bawah tidak muncul ?>
        <?php } ?>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="flex-1">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    Item yang dipilih <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full"><?= count($cart) ?></span>
                </h3>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-semibold">
                                <tr>
                                    <th class="p-4">Produk</th>
                                    <th class="p-4 text-center">Jumlah</th>
                                    <th class="p-4 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                $grandTotal = 0;
                                foreach ($cart as $id => $item) { // Mengambil key ID juga jika perlu
                                    $total = $item['harga'] * $item['qty'];
                                    $grandTotal += $total;
                                ?>
                                <tr>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($item['nama']) ?></div>
                                        <div class="text-sm text-gray-500">
                                            Harga Satuan: Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="inline-block bg-gray-100 px-3 py-1 rounded font-mono font-bold text-gray-700">
                                            x<?= $item['qty'] ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-right font-bold text-indigo-600 text-lg">
                                        Rp <?= number_format($total, 0, ',', '.') ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:w-96">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-24">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Rincian Pengiriman</h3>
                    
                    <form method="post" action="make_order.php" class="space-y-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" required rows="3"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                                placeholder="Jalan, Nomor rumah, Kota..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp / Telepon</label>
                            <input type="number" name="telp" required
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                placeholder="0812xxxx">
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-center mb-2 text-gray-600">
                                <span>Subtotal</span>
                                <span>Rp <?= number_format($grandTotal, 0, ',', '.') ?></span>
                            </div>
                            <div class="flex justify-between items-center mb-6 text-xl font-bold text-gray-900">
                                <span>Total Bayar</span>
                                <span class="text-indigo-600">Rp <?= number_format($grandTotal, 0, ',', '.') ?></span>
                            </div>

                            <button type="submit" onclick="return confirm('Apakah data alamat sudah benar?')"
                                class="w-full bg-gray-900 text-white font-bold py-3 rounded-lg hover:bg-indigo-600 transition shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-bag-shopping"></i> Checkout Sekarang
                            </button>
                            
                            <p class="text-xs text-center text-gray-400 mt-3">
                                <i class="fa-solid fa-lock"></i> Transaksi Aman & Terpercaya
                            </p>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

</body>
</html>
