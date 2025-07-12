<?php
session_start();
include 'includes/header.php';
include 'config/db.php';
?>

<!-- Hero Section -->
<section class="main-home">
    <div class="main-text">
        <h5>Perfume Collection</h5>
        <h1>The New Collection 2025</h1>
        <p>There's Nothing like Trend</p>
    </div>
</section>

<!-- Produk Section -->
<section class="trending-products" id="produk">
    <div class="center-text">
        <h2>Semua <span>Produk</span></h2>
    </div>

    <?php
    $cari = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : '';
    $query_str = "SELECT * FROM products";
    if ($cari) {
        $query_str .= " WHERE nama LIKE '%$cari%' OR kategori LIKE '%$cari%'";
    }
    $query_str .= " ORDER BY id DESC";
    $query = mysqli_query($koneksi, $query_str);
    ?>

    <?php if ($cari): ?>
        <p style="text-align:center; margin-bottom: 20px;">
            Hasil pencarian untuk: <strong><?= htmlspecialchars($cari) ?></strong>
        </p>
    <?php endif; ?>

    <div class="Products">
        <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
                <?php
                $gambar = $row['gambar'] ? 'uploads/' . htmlspecialchars($row['gambar']) : 'assets/default.jpg';
                ?>
                <div class="row">
                    <img src="<?= $gambar ?>" alt="<?= htmlspecialchars($row['nama']) ?>">
                    <div class="product-text">
                        <h5><?= htmlspecialchars($row['kategori'] ?? 'Uncategorized') ?></h5>
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
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">Tidak ada produk ditemukan.</p>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
