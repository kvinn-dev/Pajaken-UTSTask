<?php
// Nyalakan error reporting agar halaman tidak "putih doang" jika ada error di dalam kodenya
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Router sederhana untuk PHP Built-in Web Server

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// 1. Jika request adalah file statis (css, js, gambar), biarkan server bawaan PHP yang menangani
$ext = pathinfo($uri, PATHINFO_EXTENSION);
if ($ext && in_array(strtolower($ext), ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp', 'ico'])) {
    return false;
}

// 2. Jadikan root (/) mengarah ke dashboard
if ($uri === '/' || $uri === '/index.php') {
    $uri = '/homepage.php'; 
}

// Perbarui pembacaan ekstensi setelah rute $uri diubah
$ext = pathinfo($uri, PATHINFO_EXTENSION);

// 3. Tentukan nama file yang dicari (tambahkan .php jika tidak ada ekstensi)
$filename = ($ext === 'php') ? basename($uri) : basename($uri) . '.php';

$targetFile = false;
$viewsBaseDir = __DIR__ . '/resources/views';

// 4. Fitur Auto-Discovery: Cari file otomatis di folder dan subfolder resources/views/
if (file_exists($viewsBaseDir)) {
    if (file_exists($viewsBaseDir . '/' . $filename)) {
        $targetFile = $viewsBaseDir . '/' . $filename;
    } else {
        $dirs = array_filter(glob($viewsBaseDir . '/*'), 'is_dir');
        foreach ($dirs as $dir) {
            if (file_exists($dir . '/' . $filename)) {
                $targetFile = $dir . '/' . $filename;
                break;
            }
        }
    }
}

// 5. Fallback: Jika tidak ada di folder views, cek barangkali ada di root direktori
if (!$targetFile) {
    $directPath = __DIR__ . $uri;
    if (!empty($ext) && file_exists($directPath) && is_file($directPath)) {
        $targetFile = $directPath;
    } elseif (file_exists($directPath . '.php') && is_file($directPath . '.php')) {
        $targetFile = $directPath . '.php';
    }
}

// 6. Eksekusi file PHP jika berhasil ditemukan
if ($targetFile && file_exists($targetFile) && is_file($targetFile)) {
    chdir(__DIR__);
    require $targetFile;
} else {
    // Tampilkan 404 agar user tahu jika rute salah (bukan halaman putih)
    http_response_code(404);
    echo "<div style='text-align:center; margin-top: 50px; font-family: sans-serif;'>";
    echo "<h1>404 Not Found</h1>";
    echo "<p>Halaman atau rute <b>" . htmlspecialchars($uri) . "</b> tidak ditemukan.</p>";
    echo "<a href='/' style='color: #6200ff; text-decoration: none;'>&larr; Kembali ke Beranda</a>";
    echo "</div>";
}
