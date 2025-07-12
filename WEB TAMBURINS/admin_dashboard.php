<?php
session_start();

// Cek apakah user sudah login dan merupakan admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-page">

    <div class="admin-dashboard">
        <h1>Selamat datang, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="add_product.php">Tambah Produk</a></li>
            <li><a href="admin_orders.php">Lihat Pesanan</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>

        <div style="margin-top: 40px; text-align: center;">
            <p>Silakan pilih menu admin di atas untuk mengelola toko online Anda.</p>
        </div>
    </div>

</body>
</html>
