<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = "";

// Hapus produk
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    mysqli_query($koneksi, "DELETE FROM products WHERE id = $delete_id");
    header("Location: add_product.php");
    exit;
}

// Ambil data produk untuk diedit
$edit_mode = false;
$edit_data = null;
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = intval($_GET['edit']);
    $edit_query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = $edit_id");
    $edit_data = mysqli_fetch_assoc($edit_query);
}

// Tambah produk baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['update_id'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $harga = intval($_POST['harga']);
    $kategori = htmlspecialchars($_POST['kategori']);
    
    $gambar = null;
    if ($_FILES['gambar']['name']) {
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $gambar_name = uniqid() . '_' . basename($_FILES['gambar']['name']);
        $target_file = $upload_dir . $gambar_name;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar = $gambar_name;
        } else {
            $message = "Upload gambar gagal.";
        }
    }

    $query = "INSERT INTO products (nama, harga, gambar, kategori) 
              VALUES ('$nama', $harga, " . ($gambar ? "'$gambar'" : "NULL") . ", " . ($kategori ? "'$kategori'" : "NULL") . ")";
    if (mysqli_query($koneksi, $query)) {
        $message = "Produk berhasil ditambahkan!";
    } else {
        $message = "Gagal menambahkan produk.";
    }
}

// Update produk
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
    $id = intval($_POST['update_id']);
    $nama = htmlspecialchars($_POST['nama']);
    $harga = intval($_POST['harga']);
    $kategori = htmlspecialchars($_POST['kategori']);

    $gambar_update = "";
    if ($_FILES['gambar']['name']) {
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $gambar_name = uniqid() . '_' . basename($_FILES['gambar']['name']);
        $target_file = $upload_dir . $gambar_name;

        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar_update = ", gambar='$gambar_name'";
        }
    }

    $update = "UPDATE products SET nama='$nama', harga=$harga, kategori='$kategori' $gambar_update WHERE id=$id";
    if (mysqli_query($koneksi, $update)) {
        $message = "Produk berhasil diperbarui!";
    } else {
        $message = "Gagal memperbarui produk.";
    }

    header("Location: add_product.php");
    exit;
}

// Ambil semua produk
$produk_result = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="admin-page">

<div class="admin-dashboard">
    <h1>Tambah / Kelola Produk</h1>

    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="add_product.php">Tambah Produk</a></li>
        <li><a href="admin_orders.php">Lihat Pesanan</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>

    <?php if ($message): ?>
        <p class="success" style="text-align:center;"><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Form Tambah/Edit Produk -->
    <?php if ($edit_mode && $edit_data): ?>
        <h2 style="text-align:center;">Edit Produk</h2>
        <form method="POST" class="form-container" enctype="multipart/form-data">
            <input type="hidden" name="update_id" value="<?php echo $edit_data['id']; ?>">
            <input type="text" name="nama" placeholder="Nama Produk" required value="<?php echo htmlspecialchars($edit_data['nama']); ?>">
            <input type="number" name="harga" placeholder="Harga Produk" required value="<?php echo $edit_data['harga']; ?>">
            <input type="text" name="kategori" placeholder="Kategori" value="<?php echo htmlspecialchars($edit_data['kategori']); ?>">
            <input type="file" name="gambar" accept="image/*">
            <button type="submit">Update Produk</button>
            <a href="add_product.php" style="margin-left: 10px;">Batal</a>
        </form>
    <?php else: ?>
        <h2 style="text-align:center;">Tambah Produk Baru</h2>
        <form method="POST" class="form-container" enctype="multipart/form-data">
            <input type="text" name="nama" placeholder="Nama Produk" required>
            <input type="number" name="harga" placeholder="Harga Produk" required>
            <input type="text" name="kategori" placeholder="Kategori (opsional)">
            <input type="file" name="gambar" accept="image/*">
            <button type="submit">Simpan Produk</button>
        </form>
    <?php endif; ?>

    <hr style="margin: 40px 0;">

    <h2 style="text-align:center;">Daftar Produk</h2>

    <div class="product-list-admin">
        <?php if (mysqli_num_rows($produk_result) > 0): ?>
            <table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #e91e63; color:white;">
                        <th style="padding:10px;">ID</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($p = mysqli_fetch_assoc($produk_result)): ?>
                    <tr style="text-align:center; background-color:#f9f9f9;">
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo htmlspecialchars($p['nama']); ?></td>
                        <td>Rp<?php echo number_format($p['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($p['kategori']); ?></td>
                        <td>
                            <?php if ($p['gambar']): ?>
                                <img src="uploads/<?php echo $p['gambar']; ?>" width="60">
                            <?php else: ?>
                                Tidak ada
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="add_product.php?edit=<?php echo $p['id']; ?>">Edit</a> |
                            <a href="add_product.php?delete=<?php echo $p['id']; ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align:center;">Belum ada produk ditambahkan.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
