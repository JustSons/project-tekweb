<?php include "config/db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Guitar Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-gray-100">

<!-- HEADER -->
<div class="bg-black text-white p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">🎸 Guitar Shop</h1>
    <div class="flex items-center space-x-4">
        <?php if(isset($_SESSION['user'])) { ?>
            <span>Halo, <?= $_SESSION['user']['nama'] ?></span>
            <a href="auth/logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">Logout</a>
        <?php } else { ?>
            <a href="auth/login.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Login</a>
        <?php } ?>
    </div>
</div>

<!-- ITEMS -->
<div class="max-w-6xl mx-auto p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
<?php
$q = mysqli_query($conn, "SELECT * FROM items");
while ($item = mysqli_fetch_assoc($q)) {
?>
    <div class="bg-white rounded-lg shadow p-4">
        <img src="<?= $item['gambar'] ?>" class="w-full h-40 object-cover rounded">

        
        <h3 class="text-lg font-semibold mt-3">
            <?= $item['nama_item'] ?>
        </h3>
        
        <p class="text-green-600 font-bold">
            Rp <?= number_format($item['harga']) ?>
        </p>

        <?php if (!isset($_SESSION['user'])) { ?>
            <a href="auth/login.php"
               class="block text-center bg-black text-white py-2 mt-3 rounded hover:bg-gray-800">
               Buy
            </a>
        <?php } else { ?>
            <div class="flex items-center justify-between mt-3">
                <button class="minus bg-gray-300 px-3 rounded" data-id="<?= $item['id'] ?>">-</button>
                <span id="qty<?= $item['id'] ?>" class="font-bold">1</span>
                <button class="plus bg-gray-300 px-3 rounded" data-id="<?= $item['id'] ?>">+</button>
            </div>

            <button data-id="<?= $item['id'] ?>"
                class="buy w-full bg-green-600 text-white py-2 mt-3 rounded hover:bg-green-700">
                Buy
            </button>
        <?php } ?>
    </div>
<?php } ?>
</div>

<!-- CART FLOATING BUTTON -->
<a href="cart.php"
   class="fixed bottom-6 right-6 bg-green-600 text-white p-4 rounded-full shadow-lg text-xl hover:bg-green-700">
   🛒
</a>

<script>
$('.plus').click(function(){
    let id = $(this).data('id');
    let qty = parseInt($('#qty'+id).text());
    $('#qty'+id).text(qty+1);
});

$('.minus').click(function(){
    let id = $(this).data('id');
    let qty = parseInt($('#qty'+id).text());
    if(qty > 1) $('#qty'+id).text(qty-1);
});

$('.buy').click(function(){
    let id = $(this).data('id');
    let qty = $('#qty'+id).text();

    $.post("ajax/add_to_cart.php", {
        item_id: id,
        jumlah: qty
    }, function(){
        alert("Item masuk ke cart!");
    });
});
</script>

</body>
</html>
