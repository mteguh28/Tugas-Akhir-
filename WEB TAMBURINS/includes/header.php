<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>TAMBURINS</title>
    <link rel="stylesheet" href="style.css">
    <!-- Boxicons untuk icon login & keranjang -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>

<header class="main-header">
    <a href="index.php" class="logo-text"><h1>TAMBURINS</h1></a>

    <ul class="navmenu">
        <li><a href="index.php">Home</a></li>
        <li><a href="keranjang.php">Shop</a></li>
        <li><a href="produk.php">Product</a></li>
        <li><a href="kontak.php">Kontak</a></li>
    </ul>

    <div class="nav-icon">
        <?php if (isset($_SESSION['user_id'])): ?>
            <span title="Akun Kamu" style="margin-left: 10px;">
                <i class='bx bx-user'></i> <?= htmlspecialchars($_SESSION['username']) ?>
            </span>
            <a href="keranjang.php" title="Keranjang"><i class='bx bx-cart'></i></a>
            <a href="logout.php" title="Keluar"><i class='bx bx-log-out'></i></a>
        <?php else: ?>
            <a href="login.php" title="Login"><i class='bx bx-user'></i></a>
        <?php endif; ?>
    </div>
</header>
