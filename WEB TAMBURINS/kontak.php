<?php
include 'includes/header.php';
include 'config/db.php';

$pesan_sukses = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $pesan = mysqli_real_escape_string($koneksi, $_POST['pesan']);

    $query = "INSERT INTO kontak (nama, email, pesan) VALUES ('$nama', '$email', '$pesan')";
    if (mysqli_query($koneksi, $query)) {
        $pesan_sukses = "Pesan berhasil dikirim!";
    } else {
        $pesan_sukses = "Gagal mengirim pesan: " . mysqli_error($koneksi);
    }
}
?>

<!-- Background judul sama seperti index -->
<section class="main-home" style="height: 300px;">
    <div class="main-text">
        <h1>Hubungi Kami</h1>
        <p>Need help? We’re here for you!</p>
    </div>
</section>

<!-- Konten Form -->
<section class="contact" id="contact">
    <div class="contact-form">
        <?php if ($pesan_sukses != "") : ?>
            <p style="color: green; font-weight: bold; text-align:center;"><?= $pesan_sukses ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="nama" placeholder="Nama Anda" required />
            <input type="email" name="email" placeholder="Email Anda" required />
            <textarea name="pesan" rows="5" placeholder="Pesan Anda" required></textarea>
            <button type="submit" class="btn login-btn">Kirim Pesan</button>
        </form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
