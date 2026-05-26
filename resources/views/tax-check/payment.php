<?php
// tax-check.php
$pageTitle = 'Daftar Kendaraan';
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Pembayaran Pajak</title>
    <link rel="stylesheet" href="style3.css">
</head>

<body>

    <div class="spasi"></div>

    <!-- JUDUL -->
    <h2 class="payment-title">Pembayaran Pajak Kendaraan</h2>

    <!-- CARD UTAMA -->
    <div class="payment-card">

        <div class="payment-header">
            <img src="public/assets/motor.webp" alt="motor">
            <div>
                <b>F 5919 NH</b><br>
                Jatuh Tempo: 27 Mar 2024
            </div>
        </div>

        <div class="payment-detail">
            <div class="payment-row">
                <span>Jumlah Total Pajak</span>
                <span>Rp 120.000</span>
            </div>

            <div class="payment-row">
                <span>Biaya Administrasi</span>
                <span>Rp 25.000</span>
            </div>

            <div class="payment-row">
                <span>SWDKLLJ</span>
                <span>Rp 20.000</span>
            </div>

            <div class="payment-row">
                <span>Denda (Jika Ada)</span>
                <span>Rp 112.208</span>
            </div>

            <div class="payment-row payment-total">
                <span>Total Pembayaran</span>
                <span>Rp 277.208</span>
            </div>
        </div>

    </div>

    <!-- METODE PEMBAYARAN -->
    <div class="method">
        <h3 class="bank-title">Pilih Bank</h3>

        <button class="bank-btn" data-bank="bca">BCA</button>
        <button class="bank-btn" data-bank="bri">BRI</button>
        <button class="bank-btn" data-bank="mandiri">Mandiri</button>
        <button class="bank-btn" data-bank="bni">BNI</button>
    </div>

    <!-- DETAIL TRANSFER -->
    <div class="transfer-box">

        <div class="transfer-top">
            <div>
                Pilih Bank Tujuan: <b id="namaBank">-</b><br><br>
                No Rekening: <b id="noRek">-</b><br>
                PT. PAJAKIN
            </div>

            <button>Salin</button>
        </div>

        <p>
            Lakukan transfer sesuai total pembayaran ke nomor rekening di atas sebelum waktu yang ditentukan.
        </p>

        <p>
            Sisa Waktu Pembayaran: <b>2 Jam 45 Menit</b>
        </p>

    </div>

    <!-- ACTION BUTTON -->
    <div class="action">
        <button>Batalkan</button>
        <button>Cek Pembayaran</button>
    </div>

    <script>
        // Ambil plat dari localStorage
        const plat = localStorage.getItem("plat");

        if (plat) {
            document.querySelector(".payment-header b").innerText = plat;
        }

        // =======================
        // DATA BANK
        // =======================
        const dataBank = {
            bca: {
                nama: "BCA",
                norek: "1234 5678 9012"
            },
            bri: {
                nama: "BRI",
                norek: "9876 5432 1000"
            },
            mandiri: {
                nama: "Mandiri",
                norek: "1111 2222 3333"
            },
            bni: {
                nama: "BNI",
                norek: "4444 5555 6666"
            }
        };

        // =======================
        // EVENT KLIK BANK
        // =======================
        const buttons = document.querySelectorAll(".bank-btn");

        buttons.forEach(btn => {
            btn.addEventListener("click", function() {
                const kode = this.dataset.bank;
                const namaBank = document.getElementById("namaBank");
                const noRek = document.getElementById("noRek");

                if (dataBank[kode]) {
                    namaBank.innerText = dataBank[kode].nama;
                    noRek.innerText = dataBank[kode].norek;
                }
            });
        });

        // =======================
        // TOMBOL SALIN
        // =======================
        const btnSalin = document.querySelector(".transfer-top button");

        if (btnSalin) {
            btnSalin.addEventListener("click", function() {
                const noRek = document.getElementById("noRek").innerText;

                if (noRek === "-") {
                    alert("Pilih bank dulu!");
                    return;
                }

                navigator.clipboard.writeText(noRek).then(() => {
                    alert("Nomor rekening berhasil disalin!");
                });
            });
        }

        // =======================
        // TOMBOL BAYAR
        // =======================
        const btnBayar = document.querySelector(".action button:last-child");

        if (btnBayar) {
            btnBayar.addEventListener("click", function() {
                const namaBank = document.getElementById("namaBank").innerText;

                if (namaBank === "-") {
                    alert("Silakan pilih bank terlebih dahulu!");
                    return;
                }

                alert("Pembayaran berhasil!");
                localStorage.removeItem("plat");
                window.location.href = "tax-check.php";
            });
        }

        // =======================
        // TOMBOL BATAL
        // =======================
        const btnBatal = document.querySelector(".action button:first-child");

        if (btnBatal) {
            btnBatal.addEventListener("click", function() {
                const yakin = confirm("Yakin ingin membatalkan pembayaran?");
                if (yakin) {
                    window.location.href = "cekpajak2.php";
                }
            });
        }
    </script>

</body>

</html>