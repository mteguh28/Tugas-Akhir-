<?php
ob_start();
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit;
}

$user_id = intval($_SESSION['user_id']);

// Ambil item dari keranjang
$query = mysqli_query($koneksi, "
    SELECT c.id AS cart_id, p.id AS product_id, p.nama, p.harga, p.gambar, c.jumlah
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = $user_id
");

if (mysqli_num_rows($query) === 0) {
    echo "<script>alert('Keranjang Anda kosong.'); window.location='keranjang.php';</script>";
    exit;
}

$items = [];
while ($row = mysqli_fetch_assoc($query)) {
    $items[] = $row;
}

// Proses checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = date('Y-m-d H:i:s');
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);

    if (empty($no_telp)) {
        echo "<p style='color:red; text-align:center;'>Nomor telepon tidak boleh kosong.</p>";
    } else {
        $insert_order = mysqli_query($koneksi, "
            INSERT INTO pesanan (user_id, tanggal_pesan, status_pengiriman, alamat_pengiriman, no_telp)
            VALUES ($user_id, '$tanggal', 'diproses', '$alamat', '$no_telp')
        ");

        if ($insert_order) {
            $order_id = mysqli_insert_id($koneksi);

            foreach ($items as $item) {
                $product_id = intval($item['product_id']);
                $jumlah     = intval($item['jumlah']);
                $harga      = intval($item['harga']);

                mysqli_query($koneksi, "
                    INSERT INTO order_items (pesanan_id, product_id, jumlah, harga)
                    VALUES ($order_id, $product_id, $jumlah, $harga)
                ");
            }

            mysqli_query($koneksi, "DELETE FROM cart WHERE user_id = $user_id");

            header("Location: sukses.php");
            exit;
        } else {
            echo "<p style='color:red; text-align:center;'>Gagal menyimpan pesanan.</p>";
        }
    }
}

include 'includes/header.php';
?>

<section class="main-home">
    <div class="main-text">
        <h1>Checkout</h1>
        <p>Periksa kembali pesananmu sebelum konfirmasi</p>
    </div>
</section>

<section class="cart-section">
    <div class="center-text">
        <h2>Ringkasan <span>Pesanan</span></h2>
    </div>

    <div class="cart-table">
        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $grand_total = 0;
                    foreach ($items as $item):
                        $total = $item['harga'] * $item['jumlah'];
                        $grand_total += $total;
                    ?>
                    <tr>
                        <td>
                            <img src="<?= !empty($item['gambar']) ? 'uploads/' . htmlspecialchars($item['gambar']) : 'assets/default.jpg' ?>" width="60" alt="<?= htmlspecialchars($item['nama']) ?>">
                            <?= htmlspecialchars($item['nama']) ?>
                        </td>
                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td><?= $item['jumlah'] ?></td>
                        <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Alamat Pengiriman -->
            <div class="form-container" style="margin-top: 30px;">
                <label for="alamat"><strong>Alamat Pengiriman:</strong></label><br>
                <textarea name="alamat" id="alamat" rows="3" required style="width: 100%; padding: 10px;" placeholder="Masukkan alamat lengkap Anda..."></textarea>
            </div>

            <!-- Nomor Telepon -->
            <div class="form-container" style="margin-top: 20px;">
                <label for="no_telp"><strong>Nomor Telepon:</strong></label><br>
                <input type="text" name="no_telp" id="no_telp" required style="width: 100%; padding: 10px;" placeholder="Contoh: 08123456789">
            </div>

            <!-- Total & Tombol -->
            <div class="cart-summary" style="margin-top: 20px;">
                <p>Total Pembayaran: <strong>Rp <?= number_format($grand_total, 0, ',', '.') ?></strong></p>
                <button type="submit" class="btn-checkout">Konfirmasi Pesanan</button>
            </div>
        </form>
    </div>
</section>

<?php
include 'includes/footer.php';
ob_end_flush();
?>
