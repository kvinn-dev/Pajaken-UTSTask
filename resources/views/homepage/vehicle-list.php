<?php
// daftar-kendaraan.php
require_once 'config.php';
$pageTitle = 'Daftar Kendaraan';
include 'includes/header.php';

// Ambil data pencarian
$cari = $_GET['cari'] ?? '';

// Query ke database
$conn = getDB();
$sql = "SELECT * FROM kendaraan WHERE user_id = 1"; // Ganti user_id sesuai session

if (!empty($cari)) {
    $sql .= " AND no_polisi LIKE '%" . $conn->real_escape_string($cari) . "%'";
}

$sql .= " ORDER BY no_polisi ASC";
$result = $conn->query($sql);
$kendaraanList = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $kendaraanList[] = $row;
    }
}
$conn->close();
?>

<style>
    .dk-title {
        padding: 20px 30px;
    }

    .dk-title h1 {
        font-size: 25px;
        font-weight: 600;
        color: #1f2937;
    }

    .dk-search-box {
        padding: 0 30px 20px;
    }

    .dk-search-box form {
        display: flex;
        width: 100%;
        gap: 0;
    }

    .dk-search-box input {
        flex: 1;
        padding: 15px 20px;
        border: 1px solid #ddd;
        border-right: none;
        border-radius: 14px 0 0 14px;
        font-size: 14px;
        outline: none;
        transition: 0.2s;
    }

    .dk-search-box input:focus {
        border-color: #6200ff;
        box-shadow: 0 0 0 2px rgba(98, 0, 255, 0.1);
    }

    .dk-search-btn {
        padding: 15px 20px;
        background: #6200ff;
        color: white;
        border: none;
        border-radius: 0 14px 14px 0;
        cursor: pointer;
        font-size: 16px;
        transition: 0.2s;
    }

    .dk-search-btn:hover {
        background: #4a00cc;
    }

    .dk-vehicle-list {
        padding: 0 30px 40px;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .dk-vehicle-item {
        display: flex;
        align-items: center;
        background: white;
        border-radius: 18px;
        padding: 20px;
        box-shadow: 0 5px 12px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }

    .dk-vehicle-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(98, 0, 255, 0.1);
    }

    .dk-vehicle-icon {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: linear-gradient(135deg, #7b2cff, #6200ff);
        margin-right: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .dk-vehicle-icon.motor {
        background: linear-gradient(135deg, #7b2cff, #6200ff);
    }

    .dk-vehicle-icon.mobil {
        background: linear-gradient(135deg, #f59e0b, #ef4444);
    }

    .dk-vehicle-icon svg {
        width: 24px;
        height: 24px;
        fill: white;
    }

    .dk-vehicle-info {
        flex: 1;
    }

    .dk-vehicle-info h2 {
        margin: 0;
        font-size: 16px;
        color: #333;
        font-weight: 600;
    }

    .dk-vehicle-info p {
        margin: 4px 0;
        font-size: 13px;
        color: #777;
    }

    .dk-vehicle-info .dk-detail {
        font-size: 11px;
        color: #999;
    }

    .dk-empty {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .dk-empty svg {
        width: 64px;
        height: 64px;
        margin: 0 auto 16px;
        display: block;
        color: #d1d5db;
    }

    .dk-empty p {
        font-size: 16px;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .dk-vehicle-item {
            flex-direction: column;
            text-align: center;
        }

        .dk-vehicle-icon {
            margin-right: 0;
            margin-bottom: 12px;
        }
    }
</style>

<div class="pt-24"></div>

<div class="dk-title">
    <h1>Daftar Kendaraan Terdaftar</h1>
</div>

<div class="dk-search-box">
    <form method="GET" action="daftar-kendaraan.php">
        <input type="text" name="cari" placeholder="Cari plat nomor..." value="<?php echo htmlspecialchars($cari); ?>">
        <button type="submit" class="dk-search-btn">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="M21 21l-4.35-4.35"></path>
            </svg>
        </button>
    </form>
</div>

<div class="dk-vehicle-list">
    <?php if (!empty($kendaraanList)): ?>
        <?php foreach ($kendaraanList as $k):
            $iconClass = ($k['jenis'] === 'motor') ? 'motor' : 'mobil';
        ?>
            <a href="detail-kendaraan.php?id=<?php echo $k['id']; ?>" class="dk-vehicle-item">
                <div class="dk-vehicle-icon <?php echo $iconClass; ?>">
                    <?php if ($k['jenis'] === 'motor'): ?>
                        <svg viewBox="0 0 24 24" fill="white">
                            <path d="M17.5 10.5c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm-11 0c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm7-2.5l-2-2h-4l-2 2h8zm-7 0c-1.7 0-3 1.3-3 3v4h16v-4c0-1.7-1.3-3-3-3h-10z" />
                        </svg>
                    <?php else: ?>
                        <svg viewBox="0 0 24 24" fill="white">
                            <path d="M5 11l1.5-4.5h11l1.5 4.5M5 11h14M5 11l-1 7h2l.5-2h11l.5 2h2l-1-7M8 13.5h.01M16 13.5h.01" />
                        </svg>
                    <?php endif; ?>
                </div>

                <div class="dk-vehicle-info">
                    <h2><?php echo ucfirst($k['jenis']); ?> - <?php echo htmlspecialchars($k['no_polisi']); ?></h2>
                    <p>Merk/Model: <?php echo htmlspecialchars($k['merk'] . ' ' . $k['model']); ?></p>
                    <span class="dk-detail">Tahun: <?php echo htmlspecialchars($k['tahun']); ?> | Warna: <?php echo htmlspecialchars($k['warna']); ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="dk-empty">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9zm-6 13a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
            </svg>
            <p>Belum ada data kendaraan</p>
            <?php if (!empty($cari)): ?>
                <p style="font-size: 14px; margin-top: 8px;">Tidak ditemukan hasil untuk "<?php echo htmlspecialchars($cari); ?>"</p>
                <a href="daftar-kendaraan.php" style="color: #6200ff; font-size: 14px;">Tampilkan semua</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>