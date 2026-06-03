<?php
require_once 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'] ?? '';
    $lastName = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($password !== $passwordConfirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } else {
        $conn = getDB();
        
        // Cek email ganda
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Email tersebut sudah terdaftar.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            // Menambahkan NIK secara default (kosong) di query agar tidak ditolak oleh MySQL
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, phone, password, nik) VALUES (?, ?, ?, ?, ?, '')");
            $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $hashedPassword);
            
            try {
                if ($stmt->execute()) {
                    header('Location: /login');
                    exit;
                } else {
                    $error = 'Terjadi kesalahan sistem saat mendaftar.';
                }
            } catch (mysqli_sql_exception $e) {
                // Tangkap pesan error dari MySQL untuk dimunculkan di layar web
                $error = 'Gagal mendaftar: ' . $e->getMessage();
            }
            $stmt->close();
        }
        $check->close();
        $conn->close();
    }
}

$pageTitle = 'Register';
include 'includes/header-plain.php';
?>
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <img class="mx-auto h-12 w-auto" src="/public/assets/logo-pajaken.svg" alt="Pajaken">
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100">
            <h2 class="mt-2 mb-6 text-center text-xl font-semibold text-gray-900">Buat Akun Baru</h2>
            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form class="space-y-4" action="/register" method="POST">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Depan</label>
                        <input name="first_name" type="text" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Belakang</label>
                        <input name="last_name" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input name="email" type="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor HP</label>
                    <input name="phone" type="text" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input name="password" type="password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ulangi Password</label>
                        <input name="password_confirm" type="password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark">Daftar Sekarang</button>
            </form>
            <p class="mt-6 text-center text-sm text-gray-600">Sudah punya akun? <a href="/login" class="font-medium text-primary hover:text-primary-dark">Masuk di sini</a></p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
