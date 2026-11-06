<?php
require_once 'db.php';

// Jika sudah login, arahkan sesuai role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: Admin.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$error_msg = '';
$saved_email = isset($_COOKIE['remember_email']) ? $_COOKIE['remember_email'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember_me']);

    if (empty($email) || empty($password)) {
        $error_msg = 'Mohon masukkan alamat email dan kata sandi Anda.';
    } else {
        $stmt = $conn->prepare("SELECT user_id, full_name, email, phone, password_hash, role FROM users WHERE email = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($user = $result->fetch_assoc()) {
                // Verifikasi hash password (atau fallback plain-text jika di-insert manual dari SQL)
                $is_valid = password_verify($password, $user['password_hash']) || ($password === $user['password_hash']);

                if ($is_valid) {
                    $_SESSION['user_id']   = $user['user_id'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['email']     = $user['email'];
                    $_SESSION['phone']     = $user['phone'];
                    $_SESSION['role']      = $user['role'];

                    // Pengaturan Cookies Remember Me (Berlaku 30 Hari)
                    if ($remember) {
                        $expire = time() + (86400 * 30);
                        $token  = hash('sha256', $user['email'] . $user['password_hash'] . 'EVER_AFTER_SALT');
                        setcookie('remember_email', $user['email'], $expire, "/");
                        setcookie('remember_user_id', (string) $user['user_id'], $expire, "/");
                        setcookie('remember_token', $token, $expire, "/");
                    } else {
                        // Hapus cookie jika tidak dicentang
                        setcookie('remember_email', '', time() - 3600, "/");
                        setcookie('remember_user_id', '', time() - 3600, "/");
                        setcookie('remember_token', '', time() - 3600, "/");
                    }

                    // Redirect berdasarkan role
                    if ($user['role'] === 'admin') {
                        header("Location: Admin.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit;
                } else {
                    $error_msg = 'Kata sandi yang Anda masukkan tidak sesuai.';
                }
            } else {
                $error_msg = 'Alamat email tidak ditemukan dalam sistem kami.';
            }
            $stmt->close();
        } else {
            $error_msg = 'Terjadi kesalahan pada server database.';
        }
    }
}

include 'header.php';
?>

    <!-- LOGIN PAGE SECTION -->
    <main class="auth-page">
        <section class="auth-section">
            <div class="auth-split-container">

                <!-- Kolom Kiri: Editorial Sambutan -->
                <div class="auth-left-col reveal-item">
                    <span class="auth-subtitle">Akses Akun</span>
                    <h1 class="auth-main-title">
                        Selamat datang kembali di cerita Anda.
                    </h1>
                    <p class="auth-desc">
                        Masuk ke akun Anda untuk melanjutkan perencanaan hari bahagia, melihat jadwal konsultasi privat, dan mengakses kurasi layanan eksklusif kami.
                    </p>

                    <div class="auth-quote-box">
                        <p class="auth-quote-text">
                            "Setiap detail perayaan Anda dirancang dengan ketenangan alam dan keanggunan yang abadi."
                        </p>
                        <span class="auth-quote-author">— EVER AFTER ATELIER</span>
                    </div>
                </div>

                <!-- Kolom Kanan: Formulir Login -->
                <div class="auth-right-col reveal-item">
                    <div class="auth-form-wrapper">
                        <span class="auth-form-tag">01 / SIGN IN</span>
                        <h2 class="auth-form-heading">Masuk ke Akun</h2>

                        <?php if (!empty($error_msg)): ?>
                            <div class="auth-alert error">
                                <?php echo htmlspecialchars($error_msg); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['registered']) && $_GET['registered'] === 'success'): ?>
                            <div class="auth-alert success">
                                Registrasi berhasil! Silakan masuk menggunakan email dan kata sandi Anda.
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_GET['auth']) && $_GET['auth'] === 'required'): ?>
                            <div class="auth-alert error">
                                 Silakan masuk ke akun Anda terlebih dahulu untuk menjadwalkan reservasi pertemuan.
                            </div>
                        <?php endif; ?>

                        <form class="auth-form" id="loginForm" action="Login.php" method="POST" novalidate>

                            <div class="auth-form-group">
                                <label for="loginEmail">Alamat Email *</label>
                                <input 
                                    type="email" 
                                    id="loginEmail" 
                                    name="email" 
                                    value="<?php echo htmlspecialchars($saved_email); ?>" 
                                    placeholder="nama@email.com" 
                                    required
                                >
                            </div>

                            <div class="auth-form-group">
                                <div class="auth-label-row">
                                    <label for="loginPassword">Kata Sandi *</label>
                                    <button type="button" class="auth-toggle-pass" id="toggleLoginPass">Tampilkan</button>
                                </div>
                                <input 
                                    type="password" 
                                    id="loginPassword" 
                                    name="password" 
                                    placeholder="Masukkan kata sandi Anda" 
                                    required
                                >
                            </div>

                            <!-- Remember Me Checkbox (Cookies) -->
                            <div class="auth-options-row">
                                <label class="auth-checkbox-label" for="rememberMe">
                                    <input 
                                        type="checkbox" 
                                        id="rememberMe" 
                                        name="remember_me" 
                                        <?php echo !empty($saved_email) ? 'checked' : ''; ?>
                                    >
                                    <span class="custom-check"></span>
                                    <span class="check-text">Ingat Saya (Simpan Sesi 30 Hari)</span>
                                </label>
                            </div>

                            <div class="auth-submit-row">
                                <button type="submit" class="btn-outline auth-submit-btn">Masuk Sekarang</button>
                            </div>

                            <div class="auth-switch-footer">
                                <span>Belum memiliki akun?</span>
                                <a href="Register.php" class="auth-switch-link">Daftar Sebagai Klien &rarr;</a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </section>
    </main>

<?php include 'footer.php'; ?>