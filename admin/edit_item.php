<?php
// Pastikan session dimulai
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
include "../config/db.php";

// Cek akses admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    die("Akses ditolak: Anda bukan Admin.");
}

$message = "";
$status_type = "";

// Ambil data item berdasarkan ID
$id = (int)$_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM items WHERE id = $id");
$item = mysqli_fetch_assoc($query);

if (!$item) {
    die("Item tidak ditemukan.");
}

if(isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = (int)$_POST['harga'];
    $desc = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $gambar_lama = $item['gambar'];

    $img_url = $gambar_lama; // Default ke gambar lama

    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];
        $filename = time() . "-" . basename($file['name']);
        $filename = urlencode($filename); 

        // LOGIKA UPLOAD SUPABASE 
        $url = "https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/guitars/" . $filename;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImdiZnVzeHNoaXNsa3ZneHVpd29oIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2NTc0NjgzOSwiZXhwIjoyMDgxMzIyODM5fQ.l55WTenRerME3KtPhyEKl-WQaH3V_cnEVGM8VGhYEQY",
            "apikey: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImdiZnVzeHNoaXNsa3ZneHVpd29oIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2NTc0NjgzOSwiZXhwIjoyMDgxMzIyODM5fQ.l55WTenRerME3KtPhyEKl-WQaH3V_cnEVGM8VGhYEQY",
            "Content-Type: " . $file['type']
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($file['tmp_name']));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch); 
        curl_close($ch);

        if ($http_code == 200 || $http_code == 201) {
            $img_url = "https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/" . $filename;
        } else {
            $message = "Upload Gagal (Supabase). Code: $http_code. Menggunakan gambar lama.";
            $status_type = "warning";
        }
    }

    $query = "UPDATE items SET nama_item='$nama', harga='$harga', deskripsi='$desc', gambar='$img_url' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Berhasil! Produk telah diperbarui.";
        $_SESSION['message_type'] = "success";
        header("Location: index.php");
        exit();
    } else {
        $message = "Database Error: " . mysqli_error($conn);
        $status_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen py-10 px-4">

    <div class="max-w-2xl mx-auto">
        
        <div class="flex items-center justify-between mb-8">
            <a href="index.php" class="flex items-center text-gray-500 hover:text-indigo-600 transition font-medium">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
            <h2 class="text-2xl font-bold text-gray-900">Edit Produk</h2>
        </div>

        <?php if ($message): ?>
            <div class="<?php echo ($status_type == 'success') ? 'bg-green-50 text-green-700 border-green-200' : (($status_type == 'warning') ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-red-50 text-red-700 border-red-200'); ?> border p-4 rounded-lg mb-6 flex items-center shadow-sm">
                <?php if($status_type == 'success'): ?>
                    <i class="fa-solid fa-circle-check mr-2 text-xl"></i>
                <?php elseif($status_type == 'warning'): ?>
                    <i class="fa-solid fa-exclamation-triangle mr-2 text-xl"></i>
                <?php else: ?>
                    <i class="fa-solid fa-circle-exclamation mr-2 text-xl"></i>
                <?php endif; ?>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
            
            <div class="mb-6">
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
                <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($item['nama_item']) ?>" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
            </div>

            <div class="mb-6">
                <label for="harga" class="block text-sm font-semibold text-gray-700 mb-2">Harga (Rp)</label>
                <input type="number" id="harga" name="harga" value="<?= $item['harga'] ?>" required min="0"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
            </div>

            <div class="mb-6">
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Produk</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm resize-vertical"><?= htmlspecialchars($item['deskripsi']) ?></textarea>
            </div>

            <div class="mb-6">
                <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk (Opsional - Kosongkan jika tidak ingin mengubah)</label>
                <input type="file" id="gambar" name="gambar" accept="image/*"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <div class="mt-2">
                    <p class="text-sm text-gray-500">Gambar saat ini:</p>
                    <img src="<?= htmlspecialchars($item['gambar']) ?>" alt="Current Image" class="w-32 h-32 object-cover rounded-lg border border-gray-200 mt-1">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" name="update" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg transition shadow-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-save"></i> Perbarui Produk
                </button>
                <a href="index.php" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition shadow-lg flex items-center justify-center gap-2">
                    <i class="fa-solid fa-times"></i> Batal
                </a>
            </div>
        </form>

    </div>

</body>
</html>