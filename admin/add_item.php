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
$status_type = ""; // Untuk menentukan warna pesan (error/success)

if(isset($_POST['save'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = (int)$_POST['harga'];
    $desc = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];
        $filename = time() . "-" . basename($file['name']);
        $filename = urlencode($filename); // Encode filename for URL

        // --- LOGIKA UPLOAD SUPABASE (TIDAK DIUBAH) ---
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

            $query = "INSERT INTO items (nama_item, harga, deskripsi, gambar) VALUES ('$nama', '$harga', '$desc', '$img_url')";
            if (mysqli_query($conn, $query)) {
                $message = "Berhasil! Produk baru telah ditambahkan.";
                $status_type = "success";
                // Opsional: Redirect setelah sukses agar form bersih
                // header("Location: index.php"); 
            } else {
                $message = "Database Error: " . mysqli_error($conn);
                $status_type = "error";
            }
        } else {
            $message = "Upload Gagal (Supabase). Code: $http_code. Error: $curl_error.";
            $status_type = "error";
        }
    } else {
        $message = "Harap pilih gambar produk.";
        $status_type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin Panel</title>
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
            <h2 class="text-2xl font-bold text-gray-900">Tambah Produk Baru</h2>
        </div>

        <?php if ($message): ?>
            <div class="<?php echo ($status_type == 'success') ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200'; ?> border p-4 rounded-lg mb-6 flex items-center shadow-sm">
                <?php if($status_type == 'success'): ?>
                    <i class="fa-solid fa-circle-check mr-2 text-xl"></i>
                <?php else: ?>
                    <i class="fa-solid fa-circle-exclamation mr-2 text-xl"></i>
                <?php endif; ?>
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
            <form method="post" enctype="multipart/form-data" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
                    <input type="text" name="nama" required placeholder="Contoh: Fender Stratocaster"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-gray-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Jual</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-500 font-bold">Rp</span>
                        <input type="number" name="harga" required placeholder="0"
                            class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-gray-50 focus:bg-white">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Produk</label>
                    <textarea name="deskripsi" required rows="4" placeholder="Jelaskan spesifikasi gitar..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-gray-50 focus:bg-white resize-y"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Produk</label>
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                            <p class="mb-1 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG or JPEG</p>
                        </div>
                        <input type="file" name="gambar" accept="image/*" required class="hidden" onchange="previewFile(this)">
                    </label>
                    <p id="file-name" class="mt-2 text-sm text-indigo-600 font-medium text-center"></p>
                </div>

                <button name="save" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Produk
                </button>

            </form>
        </div>
    </div>

    <script>
        function previewFile(input) {
            const fileNameElement = document.getElementById('file-name');
            if (input.files && input.files[0]) {
                fileNameElement.textContent = "File terpilih: " + input.files[0].name;
                fileNameElement.classList.remove('hidden');
            } else {
                fileNameElement.textContent = "";
            }
        }
    </script>

</body>
</html>
