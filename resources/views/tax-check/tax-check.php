<?php
// tax-check.php
$pageTitle = 'Cek Pajak';
include 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Cek Pajak</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <div class="tax-spacer"></div>

    <div class="tax-container">
        <center>
            <h1 class="tax-title">cek pajak</h1>

            <!-- Form -->
            <form class="tax-form" onsubmit="cekPajak(event)" novalidate>
                <label class="tax-label">Nomor Plat</label>
                <input
                    type="text"
                    id="plat"
                    class="tax-input"
                    placeholder="silakan masukan nomor polisi"><br>

                <button type="submit" class="tax-btn">cek pajak</button>
            </form>
        </center>

        <!-- Keterangan -->
        <p class="tax-info">
            silahkan masukan nomor polisi untuk memeriksa status pajak kendaraan anda
        </p>

        <p class="tax-example">
            contoh : B 2345 FYH
        </p>

        <hr class="tax-divider">
    </div>

    <!-- FOOTER KONTAK -->
    <footer class="tax-footer">
        <div class="tax-footer-content">
            <!-- Kontak -->
            <div class="tax-footer-section">
                <h3>Kontak Kami</h3>
                <p><i class="fa-brands fa-whatsapp"></i> 0812-3456-7890</p>
                <p><i class="fa-solid fa-envelope"></i> pajaken@gmail.com</p>
            </div>

            <!-- Sosial Media -->
            <div class="tax-footer-section">
                <h3>Sosial Media</h3>
                <p><i class="fa-brands fa-instagram"></i> @pajaken</p>
                <p><i class="fa-brands fa-twitter"></i> @pajaken_id</p>
                <p><i class="fa-brands fa-facebook"></i> Pajaken Official</p>
            </div>

            <!-- Info -->
            <div class="tax-footer-section">
                <h3>Tentang</h3>
                <p>Aplikasi cek pajak kendaraan secara online dengan mudah dan cepat.</p>
            </div>
        </div>

        <p class="tax-footer-bottom">
            © 2026 Pajaken. All rights reserved.
        </p>
    </footer>

    <script>
        function cekPajak(event) {
            event.preventDefault();

            let plat = document.getElementById("plat").value.trim();

            // kosong
            if (plat === "") {
                alert("Plat nomor belum diisi!");
                return;
            }

            // harus ada 2 spasi
            const jumlahSpasi = (plat.match(/ /g) || []).length;

            if (jumlahSpasi !== 2) {
                alert("Format harus pakai spasi! Contoh: B 1234 ABC");
                return;
            }

            // tidak boleh hanya angka
            if (/^[0-9 ]+$/.test(plat)) {
                alert("Plat nomor tidak boleh hanya angka!");
                return;
            }

            // format salah
            const regex = /^[A-Za-z]{1,2} [0-9]{1,4} [A-Za-z]{1,3}$/;

            if (!regex.test(plat)) {
                alert("Format salah! Contoh: B 1234 ABC");
                return;
            }

            // uppercase
            plat = plat.toUpperCase();

            // cek database
            fetch("./cekpajak.php?plat=" + encodeURIComponent(plat))
                .then(response => response.text())
                .then(data => {
                    console.log(data);

                    if (data.trim() === "ada") {
                        localStorage.setItem("plat", plat);
                        window.location.href = "vehicle-list-tax.php";
                    } else {
                        alert("Nomor plat tidak terdaftar");
                    }
                })
                .catch(error => {
                    console.log(error);
                    alert("Terjadi error");
                });
        }
    </script>
</body>

</html>