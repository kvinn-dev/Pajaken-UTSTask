<?php
// homepage.php
require_once 'config.php';
$pageTitle = 'Beranda';
include 'includes/header.php';

// Ambil data dinamis sesuai user yang login
$currentUserId = $_SESSION['user_data']['id'] ?? 0;
$totalKendaraan = getTotalKendaraan($currentUserId);

$pajakJatuhTempo = 0;
$today = time();
foreach ($vehicles as $v) {
    $taxDate = strtotime($v['tanggal_pajak']);
    // Hitung sebagai jatuh tempo jika pajaknya sudah lewat atau H-30
    if ($taxDate - $today <= (30 * 86400)) {
        $pajakJatuhTempo++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pajaken - Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="title">
        <h1 class="homepage-title">Beranda</h1>
    </div>

    <div class="cards">


        <div class="card">
            <p>Total Kendaraan</p>
            <h1 class="blue"><?php echo $totalKendaraan; ?></h1>
        </div>

        <div class="card">
            <p>Pajak Jatuh Tempo</p>
            <h1 class="blue"><?php echo $pajakJatuhTempo; ?></h1>
            <span>Akan jatuh tempo</span>
        </div>
    </div>

    <div class="cards bottom">
        <a href="add-vehicle.php" class="card-link">
            <div class="card big">
                <div class="card-header-hp">
                    <img src="public/assets/plus.png" alt="Tambahkan">
                    <h2>Tambahkan</h2>
                </div>
                <p>
                    Tambahkan data kendaraan baru untuk diproses.<br>
                    Verifikasi semua pajak Anda di masa lalu.
                </p>
            </div>
        </a>

        <a href="vehicle-list.php" class="card-link">
            <div class="card big">
                <div class="card-header-hp">
                    <img src="public/assets/Kaca.png" alt="Cek Kendaraan">
                    <h2>Cek Kendaraan</h2>
                </div>
                <p>
                    Validasi dan simpan semua data kendaraan Anda.<br>
                    Pastikan data kendaraan Anda selalu terupdate.
                </p>
            </div>
        </a>
    </div>

</body>

</html>
<!-- FOOTER KONTAK -->
<footer class="tax-footer-hp">
    <div class="tax-footer-hp-content">
        <!-- Kontak -->
        <div class="tax-footer-hp-section">
            <h3>Kontak Kami</h3>
            <p><i class="fa-brands fa-whatsapp"></i> 0812-3456-7890</p>
            <p><i class="fa-solid fa-envelope"></i> pajaken@gmail.com</p>
        </div>

        <!-- Sosial Media -->
        <div class="tax-footer-hp-section">
            <h3>Sosial Media</h3>
            <p><i class="fa-brands fa-instagram"></i> @pajaken</p>
            <p><i class="fa-brands fa-twitter"></i> @pajaken_id</p>
            <p><i class="fa-brands fa-facebook"></i> Pajaken Official</p>
        </div>

        <!-- Info -->
        <div class="tax-footer-hp-section">
            <h3>Tentang</h3>
            <p>Aplikasi cek pajak kendaraan secara online dengan mudah dan cepat.</p>
        </div>
    </div>

    <p class="tax-footer-hp-bottom">
        © 2026 Pajaken. All rights reserved.
    </p>
</footer>