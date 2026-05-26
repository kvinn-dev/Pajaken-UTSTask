<?php
// cekpajak.php
// File ini HANYA untuk meresponse fetch dari JavaScript
// Tidak boleh ada HTML apapun!

require_once 'config.php';

$plat = $_GET['plat'] ?? '';

if (empty($plat)) {
    echo "tidak_ada";
    exit;
}

// Cek di database
$conn = getDB();
$stmt = $conn->prepare("SELECT id, no_polisi, jenis, status FROM kendaraan WHERE no_polisi = ?");
$stmt->bind_param("s", $plat);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Plat ditemukan
    $kendaraan = $result->fetch_assoc();
    
    // Bisa juga mengembalikan JSON untuk data lebih lengkap
    // echo json_encode(['status' => 'ada', 'data' => $kendaraan]);
    
    echo "ada";
} else {
    echo "tidak_ada";
}

$stmt->close();
$conn->close();
?>