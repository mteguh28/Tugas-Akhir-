<?php include 'includes/header.php'; ?>
<?php include 'config/db.php'; ?>

<section class="main-home">
    <div class="main-text">
        <h5>Perfume Collection</h5>
        <h1>The New Collection 2025</h1>
        <p>There's Nothing like Trend</p>
    </div>
</section>

<section class="trending-products" id="trending">
    <div class="center-text">
        <h2>Produk <span>Terpopuler</span></h2> 
    </div>

    <div class="Products">
        <?php
        // Ambil 3 produk terbaru atau terpopuler dari tabel 'products'
        $produk_query = mysqli_query($koneksi, "
            SELECT * FROM products 
            ORDER BY harga DESC 
            LIMIT 3
        ");

        while ($row = mysqli_fetch_assoc($produk_query)) {
            // Buat jalur gambar
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
