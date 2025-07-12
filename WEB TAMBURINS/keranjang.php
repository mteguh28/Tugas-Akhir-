<?php
session_start();
include 'includes/header.php';
include 'config/db.php';
?>

<!-- Hero Section (Main Home) -->
<section class="main-home">
    <div class="main-text">
        <h1>Keranjang Belanja</h1>
        <p>Lihat dan kelola item belanjaanmu</p>
    </div>
</section>

<!-- Konten Keranjang -->
<section class="cart-section">
    <div class="center-text">
        <h2>Daftar <span>Produk</span> di Keranjang</h2>
    </div>

    <?php
    if (!isset($_SESSION['user_id'])) {
        echo '<p style="text-align:center; margin-top:20px;">Silakan <a href="login.php">login</a> untuk melihat keranjang Anda.</p>';
    } else {
        // Query keranjang dari database
        $user_id = $_SESSION['user_id'];
        $query = mysqli_query($koneksi, "
            SELECT c.id as cart_id, p.nama, p.harga, p.gambar, c.jumlah 
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = $user_id
        ");
        
        if (mysqli_num_rows($query) > 0): ?>
            <div class="cart-table">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $grand_total = 0;
                        while ($row = mysqli_fetch_assoc($query)):
                            $total = $row['harga'] * $row['jumlah'];
                            $grand_total += $total;
                            $gambar_path = !empty($row['gambar']) ? 'uploads/' . htmlspecialchars($row['gambar']) : 'assets/default.jpg';
                        ?>
                        <tr>
                            <td>
                                <img src="<?= $gambar_path ?>" alt="<?= htmlspecialchars($row['nama']) ?>" width="60">
                                <?= htmlspecialchars($row['nama']) ?>
                            </td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                            <td>
                                <a href="hapus_keranjang.php?id=<?= $row['cart_id'] ?>" onclick="return confirm('Hapus produk ini dari keranjang?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="cart-summary">
                    <p>Total Belanja: <strong>Rp <?= number_format($grand_total, 0, ',', '.') ?></strong></p>
                    <a href="checkout.php" class="btn-checkout">Lanjut ke Checkout</a>
                </div>
            </div>
        <?php else: ?>
            <p style="text-align:center; margin-top:20px;">Keranjang Anda kosong.</p>
        <?php endif;
    }
    ?>
</section>

<?php include 'includes/footer.php'; ?>
