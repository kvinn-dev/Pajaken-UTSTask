<?php
// save-vehicles.php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDB();
    
    $userId = 1; // Ganti dengan session user ID
    $no_polisi = strtoupper(trim($_POST['no_polisi'] ?? ''));
    $jenis = $_POST['jenis'] ?? '';
    $merk = trim($_POST['merk'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $tahun = trim($_POST['tahun'] ?? '');
    $warna = trim($_POST['warna'] ?? '');
    $status = $_POST['status'] ?? 'aktif';
    $tanggal_pajak = $_POST['tanggal_pajak'] ?? '';
    
    // Validasi server-side
    if (empty($no_polisi) || empty($jenis) || empty($merk) || empty($tanggal_pajak)) {
        $_SESSION['error'] = 'Field wajib harus diisi!';
        header('Location: add-vehicle.php');
        exit;
    }
    
    // Validasi format plat
    if (!preg_match('/^[A-Z]{1,2}\s\d{1,4}\s[A-Z]{1,3}$/', $no_polisi)) {
        $_SESSION['error'] = 'Format plat salah! Contoh: B 1234 XYZ';
        header('Location: add-vehicle.php');
        exit;
    }
    
    // Cek apakah plat sudah ada
    $check = $conn->prepare("SELECT id FROM kendaraan WHERE no_polisi = ? AND user_id = ?");
    $check->bind_param("si", $no_polisi, $userId);
    $check->execute();
    $check->store_result();
    
    if ($check->num_rows > 0) {
        $_SESSION['error'] = 'Plat nomor sudah terdaftar!';
        $check->close();
        $conn->close();
        header('Location: add-vehicle.php');
        exit;
    }
    $check->close();
    
    // Insert ke database (sesuai struktur tabel kendaraan)
    $stmt = $conn->prepare("
        INSERT INTO kendaraan (user_id, no_polisi, jenis, merk, model, tahun, warna, status, tanggal_pajak) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issssssss", 
        $userId, 
        $no_polisi, 
        $jenis, 
        $merk, 
        $model, 
        $tahun, 
        $warna, 
        $status, 
        $tanggal_pajak
    );
    
    if ($stmt->execute()) {
        $_SESSION['success'] = 'Kendaraan berhasil ditambahkan!';
    } else {
        $_SESSION['error'] = 'Gagal menambahkan kendaraan: ' . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    
    header('Location: add-vehicle.php');
    exit;
}

// Jika bukan POST, redirect
header('Location: add-vehicle.php');
exit;
?>