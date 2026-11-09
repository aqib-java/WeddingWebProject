<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ever After - Pernikahan, Terinspirasi Alam</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    <!-- 1. CSS Global (Selalu dimuat di setiap halaman) -->
    <link rel="stylesheet" href="css/global.css?v=<?php echo time(); ?>">

    <!-- 2. CSS Spesifik per Halaman -->
    <?php
    $page = strtolower(basename($_SERVER['PHP_SELF']));

    if ($page === 'index.php') {
        echo '<link rel="stylesheet" href="css/home.css?v=' . time() . '">';
    } elseif ($page === 'about.php') {
        echo '<link rel="stylesheet" href="css/about.css?v=' . time() . '">';
    } elseif ($page === 'weddingoption.php') {
        echo '<link rel="stylesheet" href="css/weddingoption.css?v=' . time() . '">';
    } elseif ($page === 'suitesndress.php') {
        echo '<link rel="stylesheet" href="css/SuitsNDress.css?v=' . time() . '">';
    } elseif ($page === 'fnb.php') {
        echo '<link rel="stylesheet" href="css/fnb.css?v=' . time() . '">';
    } elseif ($page === 'contact.php') {
        echo '<link rel="stylesheet" href="css/contact.css?v=' . time() . '">';
    } elseif (in_array($page, ['login.php', 'register.php', 'registeradmin.php'])) {
        echo '<link rel="stylesheet" href="css/auth.css?v=' . time() . '">';
    } elseif ($page === 'admin.php') {
        echo '<link rel="stylesheet" href="css/contact.css?v=' . time() . '">';
        echo '<link rel="stylesheet" href="css/admin.css?v=' . time() . '">';
    }
    ?>
</head>
<body>

    <!-- HEADER / NAVBAR -->
    <header class="site-header" id="header">
        <div class="header-container">
            <!-- Hamburger Menu (Kiri) -->
            <button class="menu-toggle" aria-label="Buka Menu">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>

            <!-- Logo (Tengah) -->
            <div class="logo">
                <a href="index.php">
                    <span class="logo-text">Ever After</span>
                </a>
            </div>

            <!-- Navigasi Login & Registrasi (Kanan) -->
            <div class="header-auth">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="auth-greeting">
                        Halo, <?php echo htmlspecialchars(explode(' ', $_SESSION['full_name'])[0]); ?>
                    </span>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="Admin.php" class="auth-link auth-admin-badge">Panel Admin</a>
                    <?php endif; ?>
                    <span class="auth-divider">|</span>
                    <a href="Logout.php" class="auth-link">Keluar</a>
                <?php else: ?>
                    <a href="Login.php" class="auth-link">Masuk</a>
                    <span class="auth-divider">/</span>
                    <a href="Register.php" class="auth-btn-outline">Registrasi</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- OVERLAY & SIDEBAR MENU -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <nav class="sidebar-menu" id="sidebarMenu">
        <div class="sidebar-header">
            <span class="sidebar-eyebrow">Menu</span>
            <button class="close-btn" id="closeMenuBtn" aria-label="Tutup Menu">&times;</button>
        </div>

        <div class="sidebar-content">
            <h2 class="sidebar-logo">Ever After</h2>
            <p class="sidebar-tagline">Pernikahan, Terinspirasi Alam</p>

            <ul class="sidebar-nav">
                <li><a href="index.php" class="<?php echo $page === 'index.php' ? 'active' : ''; ?>">Beranda</a></li>
                <li><a href="WeddingOption.php" class="<?php echo $page === 'weddingoption.php' ? 'active' : ''; ?>">Pilihan Pernikahan</a></li>
                <li><a href="SuitesNDress.php" class="<?php echo $page === 'suitesndress.php' ? 'active' : ''; ?>">Koleksi Busana</a></li>
                <li><a href="FNB.php" class="<?php echo $page === 'fnb.php' ? 'active' : ''; ?>">Makanan &amp; Minuman</a></li>
                <li><a href="about.php" class="<?php echo $page === 'about.php' ? 'active' : ''; ?>">Tentang Kami</a></li>
                <li><a href="Contact.php" class="<?php echo $page === 'contact.php' ? 'active' : ''; ?>">Reservasi Meeting</a></li>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'client'): ?>
                    <li><a href="History.php" class="<?php echo $page === 'history.php' ? 'active' : ''; ?>">Riwayat Reservasi</a></li>
                <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="Admin.php" class="<?php echo $page === 'admin.php' ? 'active' : ''; ?>">Kelola Reservasi (Admin)</a></li>
                <?php endif; ?>
            </ul>

            <div class="sidebar-footer">
                <p class="sidebar-footer-title">Rencanakan Hari Istimewa Anda</p>
                <a href="Contact.php" class="sidebar-cta">Reservasi Sekarang</a>

                <div class="sidebar-social">
                    <a href="#">Instagram</a>
                    <a href="#">Facebook</a>
                    <a href="tel:0214567890">021-456-7890</a>
                </div>
            </div>
        </div>
    </nav>