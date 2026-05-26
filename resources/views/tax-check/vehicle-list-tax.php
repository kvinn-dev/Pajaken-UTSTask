<?php
// tax-check.php
$pageTitle = 'Daftar Kendaraan';
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <title>Daftar Kendaraan</title>
</head>
<body>

  <div class="spasi"></div>

  <!-- Judul -->
  <h2 class="list-title">Daftar Kendaraan</h2>

  <!-- Menu -->
  <p>
    <button>+ Daftar Kendaraan</button>
    <button>Verifikasi Data</button>
    <button>Sinkronisasi</button>
    <button>Jatuh Tempo Dekat</button>
  </p>

  <!-- Card kendaraan -->
  <div class="card">
    <div class="card-header">
      <img src="public/assets/motor.webp">
      <div>
        <b>F 5919 NH</b><br>
        Jatuh Tempo: 27 Mar 2024
      </div>
      <div class="status">Akan Jatuh Tempo</div>
    </div>
  
    <div class="card-detail">
      <div>
        Jumlah Total Pajak<br><b>Rp 120.000</b>
      </div>
      <div>
        Biaya Admin<br><b>Rp 25.000</b>
      </div>
      <div>
        SWDKLLJ<br><b>Rp 20.000</b>
      </div>
      <div>
        Denda<br><b>Rp 112.208</b>
      </div>
    </div>
  
    <div class="card-footer">
      <span>Pembayaran pajak tahunan kendaraan Anda</span>
      <button>Bayar Sekarang</button>
    </div>
  </div>

  <!-- Footer -->
    <tr>
      <td colspan="2">
        Pembayaran pajak tahunan kendaraan Anda.
      </td>

      <td align="right">
        <button>Bayar Pajak Sekarang</button>
      </td>
    </tr>

  <br><br>

  <!-- Link bawah -->
  <p>
    <a href="#" class="a-list">Cara Cek Pajak Kendaraan Online ></a>
  </p>

  <script>
    // ambil data dari halaman 1
    const plat = localStorage.getItem("plat");
  
    if (plat) {
      document.querySelector(".card-header b").innerText = plat;
    }
  
    // tombol bayar
    const btnBayar = document.querySelector(".card-footer button");
    btnBayar.addEventListener("click", function () {
      window.location.href = "/payment";
    });
  </script>
</body>
</html>