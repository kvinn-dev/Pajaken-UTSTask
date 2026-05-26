<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Pajaken';
}

// Deteksi halaman aktif
$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
if ($currentPage === '' || $currentPage === 'index.php') {
    $currentPage = 'homepage.php';
} elseif (strpos($currentPage, '.php') === false) {
    $currentPage .= '.php';
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?> - Pajaken</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/resources/css/global.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6200ff',
                        'primary-light': '#f0ebff',
                        'primary-dark': '#4a00cc',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans antialiased">

    <!-- Top Navigation -->
    <header class="fixed top-0 left-0 right-0 z-40 backdrop-blur-sm">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="flex-shrink-0">
                    <img src="/public/assets/logo-pajaken.svg" alt="Logo Pajaken" class="h-10" />
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex bg-white rounded-full p-1.5 shadow-sm border border-gray-100">
                    <a href="/"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-200 <?php echo $currentPage == 'homepage.php' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:text-primary hover:bg-primary-light/30'; ?>">
                        Beranda
                    </a>
                    <a href="/tax-check"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-200 <?php echo $currentPage == 'tax-check.php' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:text-primary hover:bg-primary-light/30'; ?>">
                        Cek Pajak
                    </a>
                    <a href="/reminder"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-200 <?php echo $currentPage == 'reminder.php' ? 'bg-primary text-white shadow-md' : 'text-gray-600 hover:text-primary hover:bg-primary-light/30'; ?>">
                        Pengingat
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors" onclick="toggleMobileMenu()">
                    <svg id="menuIconOpen" width="24" height="24" fill="none" stroke="#6200ff" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg id="menuIconClose" width="24" height="24" fill="none" stroke="#6200ff" stroke-width="2" viewBox="0 0 24 24" class="hidden">
                        <path d="M6 6l12 12M18 6L6 18"></path>
                    </svg>
                </button>

                <!-- Right Icons (Desktop) -->
                <div class="hidden md:flex items-center gap-5">
                    <!-- Notification Bell dengan Dropdown -->
                    <div class="relative">
                        <div class="relative cursor-pointer" onclick="toggleNotificationDropdown()">
                            <svg width="24" height="24" fill="none" stroke="#6200ff" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9zm-6 13a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                            <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                                <div class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-red-500 rounded-full"></div>
                            <?php endif; ?>
                        </div>

                        <!-- Notification Dropdown -->
                        <div id="notificationDropdown" class="notification-dropdown hidden">
                            <!-- Header -->
                            <div class="dropdown-header">
                                <h3>Notifikasi</h3>
                                <button onclick="markAllAsRead(event)" class="dropdown-mark-read">Tandai semua telah dibaca</button>
                            </div>

                            <!-- Body -->
                            <div class="dropdown-body">
                                <?php
                                if (!empty($allNotifications)):
                                    foreach ($allNotifications as $notif):
                                        $iconColors = [
                                            'info' => '#3b82f6',
                                            'maintenance' => '#f59e0b',
                                            'event' => '#8b5cf6',
                                            'promo' => '#10b981',
                                            'jatuh_tempo' => '#f59e0b',
                                            'selesai' => '#10b981',
                                            'masalah' => '#ef4444'
                                        ];
                                        $iconBg = $iconColors[$notif['type']] ?? '#6b7280';
                                        $isRead = !empty($notif['read_at']);
                                ?>
                                        <div class="dropdown-notif-item <?php echo !$isRead ? 'unread' : ''; ?>">
                                            <div class="dropdown-notif-icon" style="background: <?php echo $iconBg; ?>;">
                                                <?php if ($notif['type'] === 'jatuh_tempo'): ?>
                                                    <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <path d="M12 6v6l4 2"></path>
                                                    </svg>
                                                <?php elseif ($notif['type'] === 'selesai'): ?>
                                                    <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path>
                                                        <path d="M22 4L12 14.01l-3-3"></path>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <path d="M12 16v-4M12 8h.01"></path>
                                                    </svg>
                                                <?php endif; ?>
                                            </div>
                                            <div class="dropdown-notif-content">
                                                <p class="dropdown-notif-title"><?php echo htmlspecialchars($notif['title']); ?></p>
                                                <p class="dropdown-notif-desc"><?php echo htmlspecialchars($notif['message']); ?></p>
                                                <span class="dropdown-notif-time"><?php echo diffForHumans($notif['created_at']); ?></span>
                                            </div>
                                            <?php if (!$isRead): ?>
                                                <div class="dropdown-notif-dot"></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php
                                    endforeach;
                                else:
                                    ?>
                                    <div class="dropdown-empty">
                                        <p class="dropdown-empty-text">Tidak ada notifikasi</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                        <a href="/notification" class="dropdown-view-all">Lihat Semua Notifikasi</a>
                        </div>
                    </div>

                    <!-- Avatar -->
                <a href="/profile-info">
                        <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-primary hover:border-primary-dark transition-colors">
                        <img src="/public/assets/profile.svg" alt="profile" class="w-full h-full object-cover" />
                        </div>
                    </a>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden pb-4 border-t border-gray-100 mt-3 pt-3">
                <nav class="flex flex-col gap-2">
                <a href="/"
                        class="px-4 py-3 rounded-lg text-sm font-medium transition-all <?php echo $currentPage == 'homepage.php' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-primary-light'; ?>">
                        Beranda
                    </a>
                <a href="/tax-check"
                        class="px-4 py-3 rounded-lg text-sm font-medium transition-all <?php echo $currentPage == 'tax-check.php' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-primary-light'; ?>">
                        Cek Pajak
                    </a>
                <a href="/reminder"
                        class="px-4 py-3 rounded-lg text-sm font-medium transition-all <?php echo $currentPage == 'reminder.php' ? 'bg-primary text-white' : 'text-gray-600 hover:bg-primary-light'; ?>">
                        Pengingat
                    </a>
                    <div class="border-t border-gray-100 pt-2 mt-2">
                    <a href="/notification" class="px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:bg-primary-light flex items-center gap-3">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9zm-6 13a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                            Notifikasi
                        </a>
                    <a href="/profile-info" class="px-4 py-3 rounded-lg text-sm font-medium text-gray-600 hover:bg-primary-light flex items-center gap-3">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Profil
                        </a>
                    </div>
                </nav>
            </div>
        </div>
        </div>
    </header>

    <script>
        // Toggle Mobile Menu
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const iconOpen = document.getElementById('menuIconOpen');
            const iconClose = document.getElementById('menuIconClose');

            menu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        }

        // Notification Dropdown (dari sebelumnya)
        function toggleNotificationDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const bell = event.target.closest('[onclick="toggleNotificationDropdown()"]');

            if (!bell && dropdown && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });

        function markAllAsRead(event) {
            event.stopPropagation();
            document.querySelectorAll('.dropdown-notif-item.unread').forEach(item => {
                item.classList.remove('unread');
                const dot = item.querySelector('.dropdown-notif-dot');
                if (dot) dot.remove();
            });
        }
    </script>