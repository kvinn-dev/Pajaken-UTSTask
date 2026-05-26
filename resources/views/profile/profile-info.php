<?php
// profile-info.php
require_once 'config.php';
$pageTitle = 'Pengaturan Profil';
include './includes/header-plain.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $conn = getDB();

    if ($action === 'update_profile') {
        // Update profile data ke database
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ? WHERE id = ?");
        $userId = $_SESSION['user_data']['id'] ?? 1; // Sinkronisasi dengan session
        $stmt->bind_param(
            "ssssi",
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['phone'],
            $userId
        );

        try {
            if ($stmt->execute()) {
                // Update session
                $_SESSION['user_data']['first_name'] = $_POST['first_name'];
                $_SESSION['user_data']['last_name'] = $_POST['last_name'];
                $_SESSION['user_data']['email'] = $_POST['email'];
                $_SESSION['user_data']['phone'] = $_POST['phone'];

                $_SESSION['success'] = 'Profil berhasil diperbarui!';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui profil!';
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) { // Kode error MySQL untuk Duplicate Entry
                $_SESSION['error'] = 'Email tersebut sudah digunakan. Silakan gunakan email yang lain!';
            } else {
                $_SESSION['error'] = 'Terjadi kesalahan sistem saat memperbarui profil.';
            }
        }

        $stmt->close();
        header('Location: /profile-info');
        exit;
    }

    if ($action === 'update_address') {
        $userId = $_SESSION['user_data']['id'] ?? 1;
        // Cek apakah sudah ada alamat
        $check = $conn->query("SELECT id FROM alamat WHERE user_id = $userId");

        if ($check->num_rows > 0) {
            // Update alamat yang sudah ada
            $stmt = $conn->prepare("UPDATE alamat SET province = ?, city = ?, district = ?, village = ?, address_detail = ? WHERE user_id = ?");
            $stmt->bind_param(
                "sssssi",
                $_POST['province'],
                $_POST['city'],
                $_POST['district'],
                $_POST['village'],
                $_POST['address_detail'],
                $userId
            );
        } else {
            // Insert alamat baru
            $stmt = $conn->prepare("INSERT INTO alamat (user_id, province, city, district, village, address_detail) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "isssss",
                $userId,
                $_POST['province'],
                $_POST['city'],
                $_POST['district'],
                $_POST['village'],
                $_POST['address_detail']
            );
        }

        if ($stmt->execute()) {
            // Update session
            $_SESSION['user_data']['province'] = $_POST['province'];
            $_SESSION['user_data']['city'] = $_POST['city'];
            $_SESSION['user_data']['district'] = $_POST['district'];
            $_SESSION['user_data']['village'] = $_POST['village'];
            $_SESSION['user_data']['address_detail'] = $_POST['address_detail'];

            $_SESSION['success'] = 'Alamat berhasil diperbarui!';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui alamat!';
        }

        $stmt->close();
        header('Location: /profile-info');
        exit;
    }

    $conn->close();
}

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

// Ambil data user dari database untuk ditampilkan
$conn = getDB();
$userId = $_SESSION['user_data']['id'] ?? 1;
$stmt = $conn->prepare("
    SELECT u.*, 
           a.province, 
           a.city, 
           a.district, 
           a.village, 
           a.address_detail
    FROM users u
    LEFT JOIN alamat a ON u.id = a.user_id
    WHERE u.id = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();

// Jika user tidak ditemukan, gunakan data default
if (!$userData) {
    $userData = [
        'first_name' => 'User',
        'last_name' => '',
        'email' => '',
        'phone' => '',
        'province' => '',
        'city' => '',
        'district' => '',
        'village' => '',
        'address_detail' => ''
    ];
}
?>
<!-- Profile Header -->
<div class="relative bg-gradient-to-r from-primary to-purple-700 h-[200px]">
    <div class="mx-auto px-6">
        <!-- Top Navigation -->
        <div class="flex justify-between items-start pt-8">
            <a href="#" onclick="handleBack(event)" class="flex items-center gap-2 text-white/90 hover:text-white transition-colors group">
                <img width="18" height="18" src="https://img.icons8.com/ios-filled/50/ffffff/back.png" alt="back" />
                <span class="text-md font-medium">Kembali</span>
            </a>

            <button class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all hover:scale-105" title="Edit Foto Profil">
                <svg width="16" height="16" fill="none" stroke="#6200ff" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Avatar -->
    <div class="absolute -bottom-20 left-10">
        <div class="avatar-wrapper" onclick="document.getElementById('avatarUpload').click()">
            <img src="public/assets/profile.svg" alt="profile" class="w-full h-full object-cover" />
            <div class="avatar-upload-overlay">
                <svg width="32" height="32" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"></path>
                    <circle cx="12" cy="13" r="4"></circle>
                </svg>
                <span class="avatar-upload-text">Ubah Foto</span>
            </div>
            <input type="file" id="avatarUpload" class="hidden" accept="image/*" />
        </div>
    </div>
</div>

<!-- Profile Content -->
<div class="mx-auto px-6">
    <!-- User Info -->
    <div class="mt-24 ml-10">
        <h1 class="text-3xl font-bold text-gray-800">
            <?php echo htmlspecialchars(($userData['first_name'] ?? '') . ' ' . ($userData['last_name'] ?? '')); ?>
        </h1>
        <div class="flex items-center gap-2 mt-2">
            <svg width="16" height="16" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
                <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <p class="text-gray-500">
            <?php if (!empty($userData['city']) || !empty($userData['province'])): ?>
                Kab. <?php echo htmlspecialchars($userData['city'] ?? ''); ?>, <?php echo htmlspecialchars($userData['province'] ?? ''); ?>
            <?php else: ?>
                Alamat belum ditambahkan
            <?php endif; ?>
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3 mt-6">
            <button id="editAllBtn" class="bg-primary text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-primary-dark transition-all active:scale-95 shadow-sm hover:shadow-md">
                <span class="flex items-center gap-2">
                    Edit Profil
                </span>
            </button>
            <a href="profile-setting.php" class="bg-white border-2 border-primary text-primary px-6 py-2.5 rounded-full text-sm font-medium hover:bg-primary-light transition-all active:scale-95">
                <span class="flex items-center gap-2">
                    Pengaturan
                </span>
            </a>
            <a href="/logout" class="bg-red-50 border-2 border-red-100 text-red-600 px-6 py-2.5 rounded-full text-sm font-medium hover:bg-red-100 transition-all active:scale-95">
                <span class="flex items-center gap-2">
                    Logout
                </span>
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    <?php if ($success): ?>
        <div class="mt-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 animate-fadeIn">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                <path d="M22 4L12 14.01l-3-3"></path>
            </svg>
            <span class="text-sm"><?php echo htmlspecialchars($success); ?></span>
        </div>
    <?php endif; ?>

    <!-- Form Sections -->
    <div class="mt-8 space-y-6 px-6 pb-12 mb-12">

        <!-- Profile Information Card -->
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Profil</h3>
                    <p class="text-xs text-gray-400 mt-1">Data pribadi Anda</p>
                </div>
                <button onclick="toggleSection('profileSection')" class="edit-toggle-btn" id="profileEditBtn">
                    <span class="flex items-center gap-1.5">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit
                    </span>
                </button>
            </div>

            <form id="profileSection" action="profile-info.php" method="POST">
                <input type="hidden" name="action" value="update_profile">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Nama Depan <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="text"
                            name="first_name"
                            value="<?php echo htmlspecialchars($userData['first_name'] ?? ''); ?>"
                            class="profile-input"
                            disabled
                            required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Nama Belakang</label>
                        <input
                            type="text"
                            name="last_name"
                            value="<?php echo htmlspecialchars($userData['last_name'] ?? ''); ?>"
                            class="profile-input"
                            disabled />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>"
                            class="profile-input"
                            disabled
                            required />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            No. Handphone <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="text"
                            name="phone"
                            value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>"
                            class="profile-input"
                            disabled
                            required
                            placeholder="+62" />
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 hidden" id="profileActions">
                    <button type="button" onclick="toggleSection('profileSection')" class="px-6 py-2.5 rounded-full text-sm font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-full text-sm font-medium bg-primary text-white hover:bg-primary-dark transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Address Card -->
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Alamat</h3>
                    <p class="text-xs text-gray-400 mt-1">Informasi tempat tinggal</p>
                </div>
                <button onclick="toggleSection('addressSection')" class="edit-toggle-btn" id="addressEditBtn">
                    <span class="flex items-center gap-1.5">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit
                    </span>
                </button>
            </div>

            <form id="addressSection" action="profile-info.php" method="POST">
                <input type="hidden" name="action" value="update_address">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Provinsi <span class="text-red-400">*</span>
                        </label>
                        <select name="province" class="profile-select" disabled required>
                            <option value="">Pilih Provinsi</option>
                            <?php if(!empty($userData['province'])): ?>
                            <option value="<?php echo htmlspecialchars($userData['province'] ?? ''); ?>" selected><?php echo htmlspecialchars($userData['province'] ?? ''); ?></option>
                            <?php endif; ?>
                            <option value="DKI Jakarta">DKI Jakarta</option>
                            <option value="Jawa Barat">Jawa Barat</option>
                            <option value="Banten">Banten</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Kab/Kota <span class="text-red-400">*</span>
                        </label>
                        <select name="city" class="profile-select" disabled required>
                            <option value="">Pilih Kab/Kota</option>
                            <?php if(!empty($userData['city'])): ?>
                            <option value="<?php echo htmlspecialchars($userData['city'] ?? ''); ?>" selected><?php echo htmlspecialchars($userData['city'] ?? ''); ?></option>
                            <?php endif; ?>
                            <option value="Jakarta Selatan">Jakarta Selatan</option>
                            <option value="Jakarta Pusat">Jakarta Pusat</option>
                            <option value="Bandung">Bandung</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Kecamatan <span class="text-red-400">*</span>
                        </label>
                        <select name="district" class="profile-select" disabled required>
                            <option value="">Pilih Kecamatan</option>
                            <?php if(!empty($userData['district'])): ?>
                            <option value="<?php echo htmlspecialchars($userData['district'] ?? ''); ?>" selected><?php echo htmlspecialchars($userData['district'] ?? ''); ?></option>
                            <?php endif; ?>
                            <option value="Kebayoran Baru">Kebayoran Baru</option>
                            <option value="Tebet">Tebet</option>
                            <option value="Coblong">Coblong</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Kelurahan <span class="text-red-400">*</span>
                        </label>
                        <select name="village" class="profile-select" disabled required>
                            <option value="">Pilih Kelurahan</option>
                            <?php if(!empty($userData['village'])): ?>
                            <option value="<?php echo htmlspecialchars($userData['village'] ?? ''); ?>" selected><?php echo htmlspecialchars($userData['village'] ?? ''); ?></option>
                            <?php endif; ?>
                            <option value="Senayan">Senayan</option>
                            <option value="Melawai">Melawai</option>
                            <option value="Dago">Dago</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Detail Alamat</label>
                        <textarea
                            name="address_detail"
                            rows="3"
                            placeholder="cth: Jl. Angsana No. 123, RT 01/RW 02"
                            class="profile-input resize-none"
                        disabled><?php echo htmlspecialchars($userData['address_detail'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 hidden" id="addressActions">
                    <button type="button" onclick="toggleSection('addressSection')" class="px-6 py-2.5 rounded-full text-sm font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-full text-sm font-medium bg-primary text-white hover:bg-primary-dark transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Handle back button
    function handleBack(e) {
        e.preventDefault();
        if (document.referrer && document.referrer.includes('/profile-info')) {
            window.location.href = '/';
        } else if (window.history.length > 1 && document.referrer) {
            window.history.back();
        } else {
            window.location.href = '/';
        }
    }

    // Simpan state editing per section
    const editingState = {
        profileSection: false,
        addressSection: false
    };

    function toggleSection(sectionId) {
        const section = document.getElementById(sectionId);
        const inputs = section.querySelectorAll('input, select, textarea');
        const actionsDiv = document.getElementById(sectionId.replace('Section', 'Actions'));
        const editBtn = document.getElementById(sectionId.replace('Section', 'EditBtn'));

        // Toggle state
        editingState[sectionId] = !editingState[sectionId];
        const isEditing = editingState[sectionId];

        // Update semua input
        inputs.forEach(input => {
            input.disabled = !isEditing;
            if (isEditing) {
                input.classList.add('editable');
            } else {
                input.classList.remove('editable');
            }
        });

        // Toggle action buttons
        if (actionsDiv) {
            if (isEditing) {
                actionsDiv.classList.remove('hidden');
            } else {
                actionsDiv.classList.add('hidden');
            }
        }

        // Update button text & style
        if (editBtn) {
            if (isEditing) {
                editBtn.classList.add('active');
                editBtn.innerHTML = `
                <span class="flex items-center gap-1.5">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 6L6 18M6 6l12 12"></path>
                    </svg>
                    Tutup
                </span>
            `;
            } else {
                editBtn.classList.remove('active');
                editBtn.innerHTML = `
                <span class="flex items-center gap-1.5">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Edit
                </span>
            `;
            }
        }

    }

    // Edit All button
    document.getElementById('editAllBtn').addEventListener('click', function() {
        const sections = ['profileSection', 'addressSection'];
        sections.forEach(sectionId => {
            const inputs = document.getElementById(sectionId).querySelectorAll('input, select, textarea');
            const isEditing = !inputs[0].disabled;

            // Only enable if currently disabled
            if (isEditing === false) {
                toggleSection(sectionId);
            }
        });
    });

    // Avatar upload
    document.getElementById('avatarUpload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validasi tipe file
            if (!file.type.startsWith('image/')) {
                alert('Mohon pilih file gambar (JPG, PNG, GIF)');
                return;
            }

            // Validasi ukuran (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file maksimal 5MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const avatarImg = document.querySelector('.avatar-wrapper img');
                avatarImg.src = e.target.result;

                // Animasi sukses
                const avatar = document.querySelector('.avatar-wrapper');
                avatar.style.transform = 'scale(1.1)';
                avatar.style.borderColor = '#10b981';

                setTimeout(() => {
                    avatar.style.transform = 'scale(1)';
                }, 300);

                setTimeout(() => {
                    avatar.style.borderColor = '#f9fafb';
                }, 2000);

                // Ubah overlay text jadi sukses
                const overlayText = document.querySelector('.avatar-upload-text');
                const originalText = overlayText.textContent;
                overlayText.textContent = 'Berhasil!';
                setTimeout(() => {
                    overlayText.textContent = originalText;
                }, 2000);
            };
            reader.readAsDataURL(file);
        }
    });

    // Reset input file agar bisa upload ulang file yang sama
    document.getElementById('avatarUpload').addEventListener('click', function(e) {
        this.value = null;
    });
</script>

<?php include 'includes/footer.php'; ?>