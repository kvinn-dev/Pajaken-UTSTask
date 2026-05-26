<?php
// add-vehicle.php
require_once 'config.php';
$pageTitle = 'Tambah Kendaraan';
include 'includes/header.php';
?>

<style>
    .av-title {
        padding: 20px 30px;
    }

    .av-title h1 {
        font-size: 25px;
        font-weight: 600;
        color: #1f2937;
    }

    .av-form-wrapper {
        padding: 0 30px 50px;
    }

    .av-form-card {
        background: white;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .av-form-group {
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .av-form-group.full {
        width: 100%;
        margin-bottom: 20px;
    }

    .av-form-group label {
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 16px;
        color: #374151;
    }

    .av-form-group input,
    .av-form-group select {
        padding: 15px;
        border-radius: 14px;
        border: 1px solid #ddd;
        font-size: 14px;
        outline: none;
        transition: 0.2s;
        background: white;
    }

    .av-form-group input:focus,
    .av-form-group select:focus {
        border-color: #6200ff;
        box-shadow: 0 0 0 2px rgba(98, 0, 255, 0.1);
    }

    .av-form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .av-form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 30px;
    }

    .av-btn-cancel {
        padding: 15px 45px;
        border-radius: 14px;
        border: 1px solid #ccc;
        background: #eee;
        font-size: 15px;
        cursor: pointer;
        transition: 0.2s;
        color: #374151;
        font-weight: 500;
    }

    .av-btn-cancel:hover {
        background: #ddd;
    }

    .av-btn-save {
        padding: 15px 55px;
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, #7b2cff, #6200ff);
        color: white;
        font-weight: 500;
        font-size: 15px;
        cursor: pointer;
        transition: 0.2s;
    }

    .av-btn-save:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .av-alert {
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .av-alert-success {
        background: #f0fdf4;
        border: 1px solid #86efac;
        color: #166534;
    }

    .av-alert-error {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    @media (max-width: 768px) {
        .av-form-row {
            flex-direction: column;
            gap: 0;
        }

        .av-form-actions {
            flex-direction: column;
            gap: 10px;
        }

        .av-btn-cancel,
        .av-btn-save {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="pt-24"></div>

<div class="av-title">
    <h1>Tambahkan Kendaraan</h1>
</div>

<div class="av-form-wrapper">
    <div class="av-form-card">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="av-alert av-alert-success">
                <?php echo htmlspecialchars($_SESSION['success']);
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="av-alert av-alert-error">
                <?php echo htmlspecialchars($_SESSION['error']);
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form id="formKendaraan" action="/save-vehicles" method="POST">

            <!-- Plat Nomor -->
            <div class="av-form-group full">
                <label>Plat Nomor Kendaraan <span class="text-red-400">*</span></label>
                <input name="no_polisi" id="no_polisi" type="text" placeholder="Contoh: B 1234 XYZ" required>
            </div>

            <div class="av-form-row">
                <!-- Jenis Kendaraan -->
                <div class="av-form-group">
                    <label>Jenis Kendaraan <span class="text-red-400">*</span></label>
                    <select name="jenis" id="jenis" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="motor">Motor</option>
                        <option value="mobil">Mobil</option>
                    </select>
                </div>

                <!-- Merk -->
                <div class="av-form-group">
                    <label>Merk <span class="text-red-400">*</span></label>
                    <input name="merk" id="merk" type="text" placeholder="Contoh: Honda" required>
                </div>
            </div>

            <div class="av-form-row">
                <!-- Model/Tipe -->
                <div class="av-form-group">
                    <label>Model/Tipe</label>
                    <input name="model" id="model" type="text" placeholder="Contoh: Vario 150">
                </div>

                <!-- Tahun -->
                <div class="av-form-group">
                    <label>Tahun</label>
                    <input name="tahun" id="tahun" type="text" placeholder="Contoh: 2020" maxlength="4">
                </div>
            </div>

            <div class="av-form-row">
                <!-- Warna -->
                <div class="av-form-group">
                    <label>Warna</label>
                    <input name="warna" id="warna" type="text" placeholder="Contoh: Hitam">
                </div>

                <!-- Status -->
                <div class="av-form-group">
                    <label>Status <span class="text-red-400">*</span></label>
                    <select name="status" id="status" required>
                        <option value="aktif">Aktif</option>
                        <option value="akan_jatuh_tempo">Akan Jatuh Tempo</option>
                        <option value="jatuh_tempo">Jatuh Tempo</option>
                    </select>
                </div>
            </div>

            <div class="av-form-row">
                <!-- Pajak Jatuh Tempo -->
                <div class="av-form-group">
                    <label>Pajak Jatuh Tempo <span class="text-red-400">*</span></label>
                    <input name="tanggal_pajak" id="tanggal_pajak" type="date" required>
                </div>
            </div>

            <!-- Actions -->
            <div class="av-form-actions">
                <button type="button" class="av-btn-cancel" id="btnCancel">Batal</button>
                <button type="submit" class="av-btn-save">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const inputPlat = document.getElementById('no_polisi');
    const btnCancel = document.getElementById('btnCancel');
    const form = document.getElementById('formKendaraan');

    // Format plat huruf besar otomatis
    if (inputPlat) {
        inputPlat.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Tombol batal
    if (btnCancel) {
        btnCancel.addEventListener('click', function() {
            if (confirm("Yakin ingin batal?")) {
                window.location.href = "/";
            }
        });
    }

    // Validasi form
    if (form) {
        form.addEventListener('submit', function(event) {
            const plat = inputPlat.value.trim();
            const jenis = document.getElementById('jenis').value;
            const merk = document.getElementById('merk').value.trim();
            const tanggal = document.getElementById('tanggal_pajak').value;

            if (!plat || !jenis || !merk || !tanggal) {
                alert("Field bertanda * wajib diisi!");
                event.preventDefault();
                return;
            }

            // Validasi format plat
            const regexPlat = /^[A-Z]{1,2}\s\d{1,4}\s[A-Z]{1,3}$/;
            if (!regexPlat.test(plat)) {
                alert("Format plat salah! Contoh: B 1234 XYZ (Gunakan spasi)");
                event.preventDefault();
                return;
            }
        });
    }
</script>

<?php include 'includes/footer.php'; ?>