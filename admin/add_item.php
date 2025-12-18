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

if(isset($_POST['save'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = (int)$_POST['harga'];
    $desc = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];
        $filename = time() . "-" . basename($file['name']);
        $filename = urlencode($filename); 

        // --- UPLOAD SUPABASE ---
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
            } else {
                $message = "Database Error: " . mysqli_error($conn);
                $status_type = "error";
            }
        } else {
            $message = "Upload Gagal (Supabase). Code: $http_code.";
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
                    
                    <label id="drop-zone" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-300 relative">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 pointer-events-none">
                            <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-400 mb-3 transition-colors" id="upload-icon"></i>
                            <p class="mb-1 text-sm text-gray-500"><span class="font-bold text-indigo-600">Klik untuk upload</span> atau drag and drop</p>
                            <p class="text-xs text-gray-500">PNG, JPG or JPEG (Max 5MB)</p>
                        </div>
                        <input id="file-input" type="file" name="gambar" accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewFile(this)">
                    </label>

                    <div id="preview-container" class="hidden relative w-full h-64 bg-gray-100 rounded-xl border border-gray-200 overflow-hidden flex items-center justify-center">
                        <img id="image-preview" src="" alt="Preview" class="h-full object-contain">
                        
                        <button type="button" onclick="removeImage()" 
                            class="absolute top-3 right-3 bg-white text-red-500 hover:text-red-700 rounded-full p-2 shadow-md hover:bg-red-50 transition transform hover:scale-110">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                </div>

                <button name="save" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-save"></i> Simpan Produk
                </button>

            </form>
        </div>
    </div>

    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-input');
        const uploadIcon = document.getElementById('upload-icon');
        
        
        const previewContainer = document.getElementById('preview-container');
        const imagePreview = document.getElementById('image-preview');

        // Fungsi saat file dipilih (Klik atau Drop)
        function previewFile(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();

                // Saat file selesai dibaca
                reader.onload = function(e) {
                    // Set sumber gambar
                    imagePreview.src = e.target.result;
                    
                    // Sembunyikan Box Upload -> Tampilkan Preview
                    dropZone.classList.add('hidden');
                    previewContainer.classList.remove('hidden');
                }

                reader.readAsDataURL(file); // Baca file sebagai URL data
            }
        }

        // Fungsi Tombol X (Hapus Gambar)
        function removeImage() {
            // Reset input file
            fileInput.value = '';
            imagePreview.src = '';

            // Tampilkan Box Upload -> Sembunyikan Preview
            dropZone.classList.remove('hidden');
            previewContainer.classList.add('hidden');
        }

        // --- DRAG AND DROP VISUAL EFFECTS ---

        // Saat file masuk area
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
            dropZone.classList.remove('border-gray-300', 'bg-gray-50');
            uploadIcon.classList.add('text-indigo-500');
        });

        // Saat file keluar area
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
            dropZone.classList.add('border-gray-300', 'bg-gray-50');
            uploadIcon.classList.remove('text-indigo-500');
        });

        // Saat file dilepas (Drop)
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            
            // Kembalikan style
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
            dropZone.classList.add('border-gray-300', 'bg-gray-50');
            uploadIcon.classList.remove('text-indigo-500');

            // Masukkan file ke input
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                previewFile(fileInput); // Panggil fungsi preview
            }
        });
    </script>

</body>
</html>
