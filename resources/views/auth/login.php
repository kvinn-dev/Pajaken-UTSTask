<?php
require_once 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $conn = getDB();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($user = $result->fetch_assoc()) {
        // Verifikasi password (dukungan untuk plain text user lama & password_hash user baru)
        if (password_verify($password, $user['password']) || $password === $user['password'] || empty($user['password'])) {
            $_SESSION['user_data'] = $user;
            header('Location: /');
            exit;
        } else {
            $error = 'Password yang Anda masukkan salah.';
        }
    } else {
        $error = 'Email tidak ditemukan. Silakan daftar terlebih dahulu.';
    }
    $stmt->close();
    $conn->close();
}

$pageTitle = 'Login';
include 'includes/header-plain.php';
?>
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <img class="mx-auto h-12 w-auto" src="/public/assets/logo-pajaken.svg" alt="Pajaken">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Masuk ke Akun Anda</h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100">
            <?php if ($error): ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form class="space-y-6" action="/login" method="POST">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input name="email" type="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input name="password" type="password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                </div>
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark">Masuk</button>
            </form>
            <p class="mt-6 text-center text-sm text-gray-600">Belum punya akun? <a href="/register" class="font-medium text-primary hover:text-primary-dark">Daftar sekarang</a></p>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
