<?php
include "../config/db.php";
if ($_SESSION['user']['role'] != 'admin') die("Akses ditolak");

$message = "";
if(isset($_POST['save'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga = (int)$_POST['harga'];
    $desc = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    if (!empty($_FILES['gambar']['name'])) {
        $file = $_FILES['gambar'];
        $filename = time() . "-" . basename($file['name']);
        $filename = urlencode($filename); // Encode filename for URL

        // Upload ke Supabase
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Increase timeout
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch); // Get cURL error
        curl_close($ch);

        if ($http_code == 200 || $http_code == 201) {
            $img_url = "https://gbfusxshislkvgxuiwoh.supabase.co/storage/v1/object/public/guitars/" . $filename;

            $query = "INSERT INTO items (nama_item, harga, deskripsi, gambar) VALUES ('$nama', '$harga', '$desc', '$img_url')";
            if (mysqli_query($conn, $query)) {
                $message = "Item berhasil ditambahkan!";
                header("Location: index.php");
                exit();
            } else {
                $message = "Gagal menyimpan ke database: " . mysqli_error($conn);
            }
        } else {
            $message = "Gagal upload gambar ke Supabase. HTTP Code: $http_code. cURL Error: $curl_error. Response: " . substr($response, 0, 200);
        }
    } else {
        $message = "Harap pilih gambar.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Item</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h2 class="text-2xl font-bold mb-4">Add New Item</h2>

<?php if ($message): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?= $message ?>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="bg-white p-6 rounded shadow w-full max-w-md">
    <div class="mb-4">
        <label class="block text-gray-700">Nama Item</label>
        <input type="text" name="nama" required class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Harga</label>
        <input type="number" name="harga" required class="w-full border p-2 rounded">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" required class="w-full border p-2 rounded"></textarea>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Gambar</label>
        <input type="file" name="gambar" accept="image/*" required class="w-full border p-2 rounded">
    </div>
    <button name="save" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">Simpan</button>
</form>

<a href="index.php" class="text-blue-500 mt-4 inline-block">Kembali ke Admin Panel</a>

</body>
</html>
