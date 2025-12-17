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

// Ambil data cart
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

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
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
                <p class="text-gray-500 mb-8">Sepertinya kamu belum memilih gitar impianmu.</p>
                <a href="index.php" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg">
                    Mulai Belanja
                </a>
            </div>
            <?php exit; ?>
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
                                foreach ($cart as $item) {
                                    $total = $item['harga'] * $item['qty'];
                                    $grandTotal += $total;
                                ?>
                                <tr>
                                    <td class="p-4">
                                        <div class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($item['nama']) ?></div>
                                        <div class="text-sm text-gray-500">@ Rp <?= number_format($item['harga'], 0, ',', '.') ?></div>
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="inline-block bg-gray-100 px-3 py-1 rounded font-mono font-bold text-gray-700">x<?= $item['qty'] ?></span>
                                    </td>
                                    <td class="p-4 text-right font-bold text-indigo-600">
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
                    
                    <form id="checkoutForm" method="post" action="make_order.php" class="space-y-4" onsubmit="confirmCheckout(event)">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" required rows="3"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition resize-none"
                                placeholder="Jalan, Nomor rumah, Kota..."></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp / Telepon</label>
                            <input type="number" name="telp" required
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                                placeholder="0812xxxx">
                        </div>

                        <div class="border-t border-dashed border-gray-200 pt-4 mt-4">
                            <div class="flex justify-between items-center mb-6 text-xl font-bold text-gray-900">
                                <span>Total Bayar</span>
                                <span class="text-indigo-600">Rp <?= number_format($grandTotal, 0, ',', '.') ?></span>
                            </div>

                            <button type="submit"
                                class="w-full bg-gray-900 text-white font-bold py-3 rounded-lg hover:bg-indigo-600 transition shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-bag-shopping"></i> Checkout Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmationModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-xl font-semibold leading-6 text-gray-900" id="modal-title">Konfirmasi Data</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Apakah alamat pengiriman dan nomor telepon yang Anda masukkan sudah benar? <br><br>
                                        <span class="font-bold text-gray-800">Pastikan data valid agar barang sampai tepat waktu.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="button" onclick="submitRealForm()" 
                            class="inline-flex w-full justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition">
                            Ya, Data Benar
                        </button>
                        <button type="button" onclick="closeModal()" 
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">
                            Cek Lagi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi ini dipanggil saat form disubmit (tombol Checkout diklik)
        function confirmCheckout(event) {
            // Mencegah form dikirim langsung
            event.preventDefault(); 
            
            // Tampilkan Modal (Hapus class hidden)
            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById('confirmationModal').classList.add('hidden');
        }

        // Fungsi jika user yakin (submit form secara manual)
        function submitRealForm() {
            document.getElementById('checkoutForm').submit();
        }
    </script>

</body>
</html>
