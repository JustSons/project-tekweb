<?php 
include "../config/db.php"; 

$error_message = ""; // Variabel untuk menampung pesan error

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    // Gunakan mysqli_real_escape_string untuk keamanan dasar dari SQL Injection
    $email = mysqli_real_escape_string($conn, $email);

    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $u = mysqli_fetch_assoc($q);

    if ($u) {
        if (password_verify($pass, $u['password'])) {
            $_SESSION['user'] = $u;
            if ($u['role'] == 'admin') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../index.php");
            }
            exit(); // Selalu gunakan exit setelah header redirect
        } else {
            $error_message = "Password salah!";
        }
    } else {
        $error_message = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">

<form method="post" class="bg-white p-6 rounded shadow w-80">
    <h2 class="text-xl font-bold mb-4 text-center">Login</h2>

    <?php if ($error_message): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-4 text-sm text-center">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <input name="email" type="email" placeholder="Email" required
        class="w-full border p-2 mb-3 rounded focus:outline-blue-500">

    <input type="password" name="password" placeholder="Password" required
        class="w-full border p-2 mb-3 rounded focus:outline-blue-500">

    <button name="login"
        class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">
        Login
    </button>

    <p class="text-center mt-3 text-sm">
        Belum punya akun? 
        <a href="register.php" class="text-blue-500 hover:underline">Register</a>
    </p>
</form>

</body>
</html>