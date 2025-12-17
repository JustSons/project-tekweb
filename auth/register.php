<?php
// Pastikan session dimulai
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
include "../config/db.php";

$error = "";

if (isset($_POST['register'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $telp   = mysqli_real_escape_string($conn, $_POST['telp']);
    $pass   = $_POST['password'];

    // Cek email sudah ada atau belum
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        $error = "Email sudah terdaftar! Silakan gunakan email lain.";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        mysqli_query($conn, "INSERT INTO users 
            (nama, email, password, no_telp, role)
            VALUES
            ('$nama','$email','$hash','$telp','user')");

        // Redirect ke login
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Gitar Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex justify-center items-center min-h-screen py-10">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Buat Akun Baru</h2>
            <p class="text-gray-500 mt-2 text-sm">Bergabunglah dan temukan gitar impianmu</p>
        </div>

        <?php if ($error) { ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r text-sm flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <?= $error ?>
            </div>
        <?php } ?>

        <form method="post" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Contoh: Budi Santoso" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-gray-50 focus:bg-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                <input type="email" name="email" placeholder="nama@email.com" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-gray-50 focus:bg-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                <input type="text" name="telp" placeholder="0812..." required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-gray-50 focus:bg-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" placeholder="••••••••" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-gray-50 focus:bg-white">
                <p class="text-xs text-gray-400 mt-1">Minimal 6 karakter agar aman.</p>
            </div>

            <button name="register"
                class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg hover:shadow-indigo-500/30 mt-6">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center mt-8 text-sm text-gray-600">
            Sudah punya akun?
            <a href="login.php" class="text-indigo-600 font-bold hover:underline">
                Login di sini
            </a>
        </p>
    </div>

</body>
</html>
