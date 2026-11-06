<?php
require_once 'db.php';

// Jika user sudah login, arahkan sesuai role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: Admin.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

// Deteksi role: Default 'client' (jika dari tombol Header).
// Berubah jadi 'admin' HANYA jika diakses via link khusus atau dari RegisterAdmin.php
$target_role = (defined('FORCE_ADMIN_REGISTER') || (isset($_GET['role']) && $_GET['role'] === 'admin')) ? 'admin' : 'client';

$error_msg   = '';
$full_name   = '';
$email       = '';
$phone       = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name        = trim($_POST['full_name'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $phone            = trim($_POST['phone'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $posted_role      = ($_POST['role_type'] ?? 'client') === 'admin' ? 'admin' : 'client';

    // Validasi Input
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_msg = 'Mohon lengkapi seluruh kolom wajib yang bertanda bintang (*).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Format alamat email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error_msg = 'Kata sandi harus terdiri dari minimal 6 karakter.';
    } elseif ($password !== $confirm_password) {
        $error_msg = 'Konfirmasi kata sandi tidak cocok.';
    } else {
        // Cek apakah email sudah terdaftar di tabel users
        $stmt_check = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
        if ($stmt_check) {
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $error_msg = 'Alamat email ini sudah terdaftar. Silakan gunakan email lain atau masuk ke akun Anda.';
            } else {
                // Hash password sesuai kolom password_hash di tabel users
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt_insert = $conn->prepare("INSERT INTO users (full_name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, ?)");
                if ($stmt_insert) {
                    $stmt_insert->bind_param("sssss", $full_name, $email, $phone, $password_hash, $posted_role);
                    if ($stmt_insert->execute()) {
                        $stmt_insert->close();
                        $stmt_check->close();
                        header("Location: Login.php?registered=success");
                        exit;
                    } else {
                        $error_msg = 'Gagal menyimpan data registrasi. Silakan coba lagi.';
                    }
                    $stmt_insert->close();
                } else {
                    $error_msg = 'Terjadi kesalahan pada query database.';
                }
            }
            $stmt_check->close();
        } else {
            $error_msg = 'Gagal memeriksa ketersediaan email di database.';
        }
    }
}

include 'header.php';
?>

    <!-- REGISTER PAGE SECTION -->
    <main class="auth-page <?php echo $target_role === 'admin' ? 'auth-admin-theme' : ''; ?>">
        <section class="auth-section">
            <div class="auth-split-container">

                <!-- Kolom Kiri: Copywriting Editorial -->
                <div class="auth-left-col reveal-item">
                    <?php if ($target_role === 'admin'): ?>
                        <span class="auth-subtitle">Akses Internal Pengelola</span>
                        <h1 class="auth-main-title">
                            Registrasi Kurator & Administrator.
                        </h1>
                        <p class="auth-desc">
                            Halaman khusus tim manajemen Ever After untuk mengelola jadwal konsultasi klien, ketersediaan tanggal, dan operasional perayaan.
                        </p>
                        <div class="auth-quote-box admin-box">
                            <p class="auth-quote-text">
                                "Ketelitian dalam setiap jadwal adalah kunci dari perayaan yang sempurna tanpa celah."
                            </p>
                            <span class="auth-quote-author">— INTERNAL MANAGEMENT PORTAL</span>
                        </div>
                    <?php else: ?>
                        <span class="auth-subtitle">Mulai Perjalanan Anda</span>
                        <h1 class="auth-main-title">
                            Rangkai babak baru kisah cinta Anda.
                        </h1>
                        <p class="auth-desc">
                            Buat akun pribadi Anda untuk memesan jadwal konsultasi bersama spesialis pernikahan kami, memilih paket venue, serta mengkurasi menu perjamuan.
                        </p>
                        <div class="auth-quote-box">
                            <p class="auth-quote-text">
                                "Di bawah pepohonan yang teduh, setiap janji suci menemukan rumahnya yang abadi."
                            </p>
                            <span class="auth-quote-author">— EVER AFTER ATELIER</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Kolom Kanan: Formulir Registrasi -->
                <div class="auth-right-col reveal-item">
                    <div class="auth-form-wrapper">
                        <div class="auth-role-header">
                            <span class="auth-form-tag">
                                <?php echo $target_role === 'admin' ? '02 / ADMIN REGISTRATION' : '02 / CLIENT REGISTRATION'; ?>
                            </span>
                            <span class="auth-role-pill <?php echo $target_role; ?>">
                                Role: <?php echo strtoupper($target_role); ?>
                            </span>
                        </div>

                        <h2 class="auth-form-heading">
                            <?php echo $target_role === 'admin' ? 'Buat Akun Admin' : 'Buat Akun Klien'; ?>
                        </h2>

                        <?php if (!empty($error_msg)): ?>
                            <div class="auth-alert error">
                                <?php echo htmlspecialchars($error_msg); ?>
                            </div>
                        <?php endif; ?>

                        <form class="auth-form" id="registerForm" action="<?php echo $target_role === 'admin' ? 'RegisterAdmin.php' : 'Register.php'; ?>" method="POST" novalidate>
                            
                            <!-- Hidden Input untuk Menyimpan Role -->
                            <input type="hidden" name="role_type" value="<?php echo htmlspecialchars($target_role); ?>">

                            <!-- 1. full_name -->
                            <div class="auth-form-group">
                                <label for="regFullName">Nama Lengkap *</label>
                                <input 
                                    type="text" 
                                    id="regFullName" 
                                    name="full_name" 
                                    value="<?php echo htmlspecialchars($full_name); ?>" 
                                    placeholder="<?php echo $target_role === 'admin' ? 'Cth: Aqib Java (Admin)' : 'Cth: Romeo & Juliet'; ?>" 
                                    maxlength="100"
                                    required
                                >
                            </div>

                            <!-- 2. email & 3. phone -->
                            <div class="auth-form-row-split">
                                <div class="auth-form-group">
                                    <label for="regEmail">Alamat Email *</label>
                                    <input 
                                        type="email" 
                                        id="regEmail" 
                                        name="email" 
                                        value="<?php echo htmlspecialchars($email); ?>" 
                                        placeholder="nama@email.com" 
                                        maxlength="150"
                                        required
                                    >
                                </div>

                                <div class="auth-form-group">
                                    <label for="regPhone">Nomor Telepon / WA</label>
                                    <input 
                                        type="tel" 
                                        id="regPhone" 
                                        name="phone" 
                                        value="<?php echo htmlspecialchars($phone); ?>" 
                                        placeholder="0812-xxxx-xxxx" 
                                        maxlength="20"
                                    >
                                </div>
                            </div>

                            <!-- 4. password_hash (Input Password & Konfirmasi) -->
                            <div class="auth-form-group">
                                <div class="auth-label-row">
                                    <label for="regPassword">Kata Sandi * <small>(Min. 6 Karakter)</small></label>
                                    <button type="button" class="auth-toggle-pass" id="toggleRegPass">Tampilkan</button>
                                </div>
                                <input 
                                    type="password" 
                                    id="regPassword" 
                                    name="password" 
                                    placeholder="Buat kata sandi yang aman" 
                                    required
                                >
                                <div class="pass-strength-bar" id="passStrengthBar">
                                    <span class="strength-fill" id="strengthFill"></span>
                                </div>
                            </div>

                            <div class="auth-form-group">
                                <label for="regConfirmPassword">Konfirmasi Kata Sandi *</label>
                                <input 
                                    type="password" 
                                    id="regConfirmPassword" 
                                    name="confirm_password" 
                                    placeholder="Ulangi kata sandi Anda" 
                                    required
                                >
                                <small class="auth-match-hint" id="passMatchHint"></small>
                            </div>

                            <div class="auth-submit-row">
                                <button type="submit" class="btn-outline auth-submit-btn">
                                    <?php echo $target_role === 'admin' ? 'Daftarkan Akun Admin' : 'Daftar Sekarang'; ?>
                                </button>
                            </div>

                            <div class="auth-switch-footer">
                                <span>Sudah memiliki akun?</span>
                                <a href="Login.php" class="auth-switch-link">Masuk di Sini &rarr;</a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </section>
    </main>

<?php include 'footer.php'; ?>