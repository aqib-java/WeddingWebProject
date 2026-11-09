<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'wedding_organizer';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// AUTO-LOGIN DARI COOKIE REMEMBER ME (Jika session belum aktif tapi cookie ada)
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user_id']) && isset($_COOKIE['remember_token'])) {
    $cookie_uid = (int) $_COOKIE['remember_user_id'];
    $cookie_token = $_COOKIE['remember_token'];

    $stmt_cookie = $conn->prepare("SELECT user_id, full_name, email, phone, password_hash, role FROM users WHERE user_id = ? LIMIT 1");
    if ($stmt_cookie) {
        $stmt_cookie->bind_param("i", $cookie_uid);
        $stmt_cookie->execute();
        $res_cookie = $stmt_cookie->get_result();

        if ($row_cookie = $res_cookie->fetch_assoc()) {
            $expected_token = hash('sha256', $row_cookie['email'] . $row_cookie['password_hash'] . 'EVER_AFTER_SALT');
            if (hash_equals($expected_token, $cookie_token)) {
                $_SESSION['user_id']   = $row_cookie['user_id'];
                $_SESSION['full_name'] = $row_cookie['full_name'];
                $_SESSION['email']     = $row_cookie['email'];
                $_SESSION['phone']     = $row_cookie['phone'];
                $_SESSION['role']      = $row_cookie['role'];
            }
        }
        $stmt_cookie->close();
    }
}
?>