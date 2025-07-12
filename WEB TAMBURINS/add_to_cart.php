<?php
session_start();
include 'config/db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=index.php"); // redirect setelah login
    exit;
}

// Cek apakah form dikirim dengan method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_POST['product_id']);

    // Cek apakah produk sudah ada di keranjang
    $check_query = mysqli_query($koneksi, "
        SELECT * FROM cart 
        WHERE user_id = $user_id AND product_id = $product_id
    ");

    if (mysqli_num_rows($check_query) > 0) {
        // Jika sudah ada, update jumlah
        mysqli_query($koneksi, "
            UPDATE cart 
            SET jumlah = jumlah + 1 
            WHERE user_id = $user_id AND product_id = $product_id
        ");
    } else {
        // Jika belum ada, tambahkan baru
        mysqli_query($koneksi, "
            INSERT INTO cart (user_id, product_id, jumlah) 
            VALUES ($user_id, $product_id, 1)
        ");
    }

    // Redirect ke halaman sebelumnya atau keranjang
    header("Location: keranjang.php");
    exit;
} else {
    // Akses langsung tanpa POST
    header("Location: index.php");
    exit;
}
?>
