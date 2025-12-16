<?php
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
        $error = "Email sudah terdaftar!";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        mysqli_query($conn, "INSERT INTO users 
            (nama, email, password, no_telp, role)
            VALUES
            ('$nama','$email','$hash','$telp','user')");

        header("Location: login.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="bg-white p-6 rounded shadow w-96">
    <h2 class="text-2xl font-bold text-center mb-4">
        Register Akun
    </h2>

    <?php if ($error) { ?>
        <div class="bg-red-100 text-red-700 p-2 mb-3 rounded text-sm">
            <?= $error ?>
        </div>
    <?php } ?>

    <form method="post">
        <input type="text" name="nama" placeholder="Nama Lengkap" required
            class="w-full border p-2 mb-3 rounded">

        <input type="email" name="email" placeholder="Email" required
            class="w-full border p-2 mb-3 rounded">

        <input type="text" name="telp" placeholder="No. Telepon" required
            class="w-full border p-2 mb-3 rounded">

        <input type="password" name="password" placeholder="Password" required
            class="w-full border p-2 mb-4 rounded">

        <button name="register"
            class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">
            Register
        </button>
    </form>

    <p class="text-center mt-4 text-sm">
        Sudah punya akun?
        <a href="login.php" class="text-blue-500 hover:underline">
            Login
        </a>
    </p>
</div>

</body>
</html>
