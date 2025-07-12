<?php
session_start();
include 'includes/header.php';
?>

<section class="main-home">
    <div class="main-text">
        <h1>Terima Kasih!</h1>
        <p>Pesanan Anda telah berhasil dibuat.</p>
    </div>
</section>

<section class="cart-section">
    <div class="center-text">
        <h2>Status: <span>Berhasil</span></h2>
        <p style="text-align:center;">Silakan cek menu <strong>Status Pengiriman</strong> untuk melihat perkembangan pesanan Anda.</p>
        
        <!-- Pemberitahuan WhatsApp -->
        <p style="text-align:center; color: green; margin-top: 20px;">
            📦 Tim kami akan segera menghubungi Anda melalui <strong>WhatsApp</strong> untuk pengaturan pengiriman.
        </p>
        
        <div style="text-align:center; margin-top: 30px;">
            <a href="dashboard.php" class="btn-checkout">Kembali ke Beranda</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
