<?php 
// Pastikan session dimulai (jika di db.php belum ada session_start)
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
include "config/db.php"; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guitar Shop - Professional Instruments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        /* Animasi halus untuk tombol cart */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .float-anim { animation: float 3s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white/90 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 group">
                <div class="bg-indigo-600 text-white p-2 rounded-lg group-hover:bg-indigo-700 transition">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900">Guitar Shop</span>
            </a>
            
            <div class="flex items-center gap-4 md:gap-6">
                <?php if(isset($_SESSION['user'])) { ?>
                    <span class="hidden md:block text-gray-600 font-medium text-sm">
                        Halo, <span class="text-indigo-600 font-bold"><?= htmlspecialchars($_SESSION['user']['nama']) ?></span>
                    </span>
                    <a href="my_orders.php" class="text-gray-600 hover:text-indigo-600 font-medium text-sm transition">
                        Pesanan Saya
                    </a>
                    <a href="auth/logout.php" class="bg-red-50 text-red-600 px-4 py-2 rounded-full text-sm font-bold hover:bg-red-100 transition">
                        Logout
                    </a>
                <?php } else { ?>
                    <a href="auth/login.php" class="text-indigo-600 font-bold hover:underline text-sm">Masuk</a>
                    <a href="auth/register.php" class="bg-black text-white px-5 py-2 rounded-full text-sm font-bold hover:bg-gray-800 transition shadow-lg">Daftar</a>
                <?php } ?>
            </div>
        </div>
    </nav>

    <div class="relative bg-gray-900 text-white overflow-hidden mb-10">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1550985543-f442fa6e0230?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" alt="Background" class="w-full h-full object-cover opacity-30">
        </div>
        <div class="relative container mx-auto px-4 py-20 md:py-32 text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-6 drop-shadow-2xl">
                Mainkan Musik <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Impianmu</span>
            </h1>
            <p class="text-lg text-gray-300 max-w-2xl mx-auto mb-8">
                Temukan koleksi gitar terbaik dengan kualitas suara premium untuk setiap jenjang musisi.
            </p>
            <a href="#produk" class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-full transition transform hover:scale-105 shadow-xl">
                Belanja Sekarang
            </a>
        </div>
    </div>

    <div id="produk" class="container mx-auto px-4 pb-20">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Katalog Produk</h2>
            <div class="h-1 flex-1 bg-gray-200 ml-6 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $q = mysqli_query($conn, "SELECT * FROM items");
            while ($item = mysqli_fetch_assoc($q)) {
            ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition duration-300 flex flex-col group">
                    
                    <div class="relative h-64 overflow-hidden bg-gray-100">
                        <img src="<?= htmlspecialchars($item['gambar']) ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300"></div>
                    </div>

                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">
                            <?= htmlspecialchars($item['nama_item']) ?>
                        </h3>
                        
                        <div class="flex justify-between items-center mb-6">
                            <p class="text-2xl font-bold text-indigo-600">
                                Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                            </p>
                        </div>

                        <div class="mt-auto">
                            <?php if (!isset($_SESSION['user'])) { ?>
                                <a href="auth/login.php"
                                   class="block w-full text-center bg-gray-100 text-gray-800 font-bold py-3 rounded-xl hover:bg-gray-200 transition">
                                   Login untuk Membeli
                                </a>
                            <?php } else { ?>
                                <div class="flex items-center justify-between bg-gray-50 rounded-xl p-1 mb-3 border border-gray-200">
                                    <button class="minus w-10 h-10 flex items-center justify-center bg-white text-gray-600 rounded-lg shadow-sm hover:text-indigo-600 font-bold text-lg transition" 
                                            data-id="<?= $item['id'] ?>">−</button>
                                    
                                    <span id="qty<?= $item['id'] ?>" class="font-bold text-gray-800 text-lg w-12 text-center">1</span>
                                    
                                    <button class="plus w-10 h-10 flex items-center justify-center bg-white text-gray-600 rounded-lg shadow-sm hover:text-indigo-600 font-bold text-lg transition" 
                                            data-id="<?= $item['id'] ?>">+</button>
                                </div>

                                <button data-id="<?= $item['id'] ?>"
                                        class="buy w-full bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-indigo-600 transition shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Masukkan Keranjang
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <a href="cart.php"
       class="float-anim fixed bottom-8 right-8 bg-indigo-600 text-white w-16 h-16 rounded-full shadow-2xl flex items-center justify-center hover:bg-indigo-700 transition z-50 group border-4 border-white">
        <svg class="w-8 h-8 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        
        <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold w-6 h-6 flex items-center justify-center rounded-full border-2 border-white transform translate-x-1 -translate-y-1">
                <?= count($_SESSION['cart']) ?>
            </span>
        <?php endif; ?>
    </a>

    <script>
    $(document).ready(function() {
        // Logika Tombol Plus
        $('.plus').click(function(){
            let id = $(this).data('id');
            let qty = parseInt($('#qty'+id).text());
            $('#qty'+id).text(qty+1);
        });

        // Logika Tombol Minus
        $('.minus').click(function(){
            let id = $(this).data('id');
            let qty = parseInt($('#qty'+id).text());
            if(qty > 1) $('#qty'+id).text(qty-1);
        });

        // Logika Tombol Buy (AJAX)
        $('.buy').click(function(){
            let btn = $(this); // Simpan referensi tombol
            let originalText = btn.html(); // Simpan teks asli
            let id = btn.data('id');
            let qty = $('#qty'+id).text();

            // Ubah tombol jadi "Loading..."
            btn.prop('disabled', true).html('<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>');

            $.post("ajax/add_to_cart.php", {
                item_id: id,
                jumlah: qty
            }, function(){
                // Efek Sukses
                btn.removeClass('bg-gray-900').addClass('bg-green-600').html('Berhasil!');
                
                // Kembalikan ke semula setelah 1 detik
                setTimeout(function(){
                    btn.prop('disabled', false).removeClass('bg-green-600').addClass('bg-gray-900').html(originalText);
                    // Reload halaman opsional jika ingin update jumlah cart di ikon
                    location.reload(); 
                }, 800);
            });
        });
    });
    </script>

</body>
</html>
