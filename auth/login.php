<?php 
// Pastikan session dimulai
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
include "../config/db.php"; 

$error_message = ""; 

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $email = mysqli_real_escape_string($conn, $email);

    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $u = mysqli_fetch_assoc($q);

    if ($u) {
        if (password_verify($pass, $u['password'])) {
            $_SESSION['user'] = $u;
            
            // Redirect sesuai Role
            if ($u['role'] == 'admin') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../index.php");
            }
            exit(); 
        } else {
            $error_message = "Password yang Anda masukkan salah!";
        }
    } else {
        $error_message = "Email tidak ditemukan di sistem kami.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gitar Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Poppins', sans-serif; } </style>
</head>
<body class="bg-gray-50 flex justify-center items-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-100">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 mb-4">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Selamat Datang</h2>
            <p class="text-gray-500 text-sm">Masuk untuk melanjutkan belanja</p>
        </div>

        <?php if ($error_message): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r text-sm flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input name="email" type="email" placeholder="contoh@email.com" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-gray-50 focus:bg-white">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                </div>
                <input type="password" name="password" placeholder="••••••••" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-gray-50 focus:bg-white">
            </div>

            <button name="login"
                class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition duration-300 shadow-lg hover:shadow-indigo-500/30">
                Masuk Sekarang
            </button>
        </form>

        <p class="text-center mt-8 text-sm text-gray-600">
            Belum punya akun? 
            <a href="register.php" class="text-indigo-600 font-bold hover:underline">Daftar sekarang</a>
        </p>
    </div>

</body>
</html>
