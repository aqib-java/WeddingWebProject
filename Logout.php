<?php
require_once 'db.php';

$_SESSION = [];
session_unset();
session_destroy();

// Bersihkan cookie auto-login tapi biarkan remember_email jika ada
setcookie('remember_user_id', '', time() - 3600, "/");
setcookie('remember_token', '', time() - 3600, "/");

header("Location: Login.php");
exit;
?>