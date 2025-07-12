<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}
include 'includes/header.php';
include 'config/db.php';
?>

<!-- Hero Section (Main Home) -->
<section class="main-home">
    <div class="main-text">
        <h5>Selamat Datang,</h5>
        <h1><?= htmlspecialchars($_SESSION['username']) ?>!</h1>
        <p>Terima kasih telah bergabung di TAMBURINS</p>
    </div>
</section>

<!-- Produk Populer (sama seperti di index.php) -->
<section class="trending-products" id="trending">
    <div class="center-text">
        <h2>Produk <span>Terpopuler</span></h2>
    </div>

    <div class="Products">
        <?php
        $produk_query = mysqli_query($koneksi, "
            SELECT * FROM products 
            ORDER BY harga DESC 
            LIMIT 3
        ");

        while ($row = mysqli_fetch_assoc($produk_query)) {
            $gambar_path = !empty($row['gambar']) ? 'uploads/' . htmlspecialchars($row['gambar']) : 'assets/default.jpg';
        ?>
            <div class="row">
                <img src="<?= $gambar_path ?>" alt="<?= htmlspecialchars($row['nama']) ?>" style="width: 100%; max-width: 200px;">
                <div class="product-text">
                    <h5><?= htmlspecialchars($row['kategori']) ?></h5>
                </div>
                <div class="price">
                    <h4><?= htmlspecialchars($row['nama']) ?></h4>
                    <p>Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>
                    <form method="POST" action="add_to_cart.php">
                        <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="btn-add-to-cart">Tambah ke Keranjang</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
