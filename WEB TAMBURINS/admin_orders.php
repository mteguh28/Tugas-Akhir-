<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Ambil semua pesanan
$query = "
SELECT 
    p.id AS pesanan_id, 
    u.username, 
    p.tanggal_pesan, 
    p.status_pengiriman, 
    p.alamat_pengiriman,
    p.no_telp,
    pr.nama AS nama_produk, 
    oi.jumlah
FROM pesanan p
JOIN users u ON p.user_id = u.id
JOIN order_items oi ON p.id = oi.pesanan_id
JOIN products pr ON oi.product_id = pr.id
ORDER BY p.tanggal_pesan DESC
";

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Pengguna</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-page">

<div class="admin-dashboard">
    <h1>Daftar Pesanan Pengguna</h1>

    <!-- Navigasi Admin -->
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="add_product.php">Tambah Produk</a></li>
        <li><a href="admin_orders.php">Lihat Pesanan</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

    <div class="orders-table">
        <table>
            <thead>
                <tr>
                    <th>ID Pesanan</th>
                    <th>Username</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $row['pesanan_id'] ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= $row['tanggal_pesan'] ?></td>
                            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td><?= htmlspecialchars($row['status_pengiriman']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['alamat_pengiriman'] ?? 'Belum diisi')) ?></td>
                            <td><?= htmlspecialchars($row['no_telp'] ?? '-') ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">Belum ada pesanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
