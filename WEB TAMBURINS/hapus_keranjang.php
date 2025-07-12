<?php
session_start();
include 'config/db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=keranjang.php");
    exit;
}

// Pastikan ada ID keranjang yang dikirim
if (isset($_GET['id'])) {
    $cart_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Verifikasi apakah item keranjang milik user tersebut
    $cek = mysqli_query($koneksi, "SELECT * FROM cart WHERE id = $cart_id AND user_id = $user_id");

    if (mysqli_num_rows($cek) > 0) {
        // Hapus item dari keranjang
        mysqli_query($koneksi, "DELETE FROM cart WHERE id = $cart_id AND user_id = $user_id");
    }
}

// Kembali ke halaman keranjang
header("Location: keranjang.php");
exit;
?>
