<?php
// session_start();
include "config/db.php";

// 1. Cek Login
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}

$user_name = isset($_SESSION['user']['nama']) ? $_SESSION['user']['nama'] : '';
$user_id = isset($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : null;

// Cek apakah tabel buy punya kolom user_id dan item_id
$col_user = mysqli_query($conn, "SHOW COLUMNS FROM buy LIKE 'user_id'");
$has_user_id = ($col_user && mysqli_num_rows($col_user) > 0);
$col_item = mysqli_query($conn, "SHOW COLUMNS FROM buy LIKE 'item_id'");
$has_item_id = ($col_item && mysqli_num_rows($col_item) > 0);

// Tentukan kondisi JOIN dan WHERE sesuai kolom yang ada
$join_condition = $has_item_id ? 'ON buy.item_id = items.id' : 'ON buy.nama_item = items.nama_item';

if ($has_user_id && $user_id) {
    $query = "SELECT buy.*, items.gambar 
              FROM buy 
              LEFT JOIN items $join_condition 
              WHERE buy.user_id = $user_id 
              ORDER BY buy.id DESC";
} else {
    $safe_name = mysqli_real_escape_string($conn, $user_name);
    $query = "SELECT buy.*, items.gambar 
              FROM buy 
              LEFT JOIN items $join_condition 
              WHERE buy.nama_user = '$safe_name' 
              ORDER BY buy.id DESC";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Gitar Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 group">
                <div class="bg-indigo-600 text-white p-2 rounded-lg group-hover:bg-indigo-700 transition">
                    <i class="fa-solid fa-guitar text-xl"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-gray-900">Gitar Shop</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="index.php" class="text-gray-500 hover:text-indigo-600 transition font-medium">Home</a>
                <a href="auth/logout.php" class="text-sm bg-red-100 text-red-600 px-4 py-2 rounded-full hover:bg-red-200 transition font-semibold">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Riwayat Pesanan</h2>
            <span class="bg-indigo-100 text-indigo-800 text-sm font-semibold px-3 py-1 rounded-full">
                Halo, <?php echo htmlspecialchars($user_name); ?>!
            </span>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="grid gap-6">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition duration-300">
                        <div class="p-6 flex flex-col md:flex-row gap-6 items-center">
                            
                            <div class="w-full md:w-32 h-32 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden relative">
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="<?php echo htmlspecialchars($row['gambar']); ?>" alt="Gitar" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="flex items-center justify-center h-full text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex-1 text-center md:text-left">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">
                                    <?php echo htmlspecialchars($row['nama_item']); ?>
                                </h3>
                                <div class="text-sm text-gray-500 mb-4 space-y-1">
                                    <p>Jumlah: <span class="font-medium text-gray-800"><?php echo $row['jumlah']; ?> Unit</span></p>
                                    <p>Tujuan: <?php echo htmlspecialchars($row['alamat']); ?></p>
                                </div>
                                <div class="text-xl font-bold text-indigo-600">
                                    Rp <?php echo number_format($row['total'], 0, ',', '.'); ?>
                                </div>
                            </div>

                            <div class="flex flex-col items-center md:items-end justify-center min-w-[120px] gap-3 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 pl-0 md:pl-6 w-full md:w-auto">
                                <span class="text-xs text-gray-400 uppercase tracking-wider">Order #<?php echo $row['id']; ?></span>
                                
                                <?php if ($row['status'] == 'SENT'): ?>
                                    <span class="flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Dikirim
                                    </span>
                                <?php else: ?>
                                    <span class="flex items-center gap-2 bg-orange-100 text-orange-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm">
                                        <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Menunggu
                                    </span>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                    <?php endwhile; ?>
            </div>

        <?php else: ?>
            <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="bg-gray-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada pesanan</h3>
                <p class="text-gray-500 mb-6">Kamu belum pernah membeli gitar apapun.</p>
                <a href="index.php" class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Mulai Belanja
                </a>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
