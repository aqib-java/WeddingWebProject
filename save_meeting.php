
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';

function respond($success, $message, $statusCode = 200)
{
    http_response_code($statusCode);
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Metode permintaan tidak valid.', 405);
}

$firstName  = trim($_POST['firstName'] ?? '');
$lastName   = trim($_POST['lastName'] ?? '');
$email      = trim($_POST['clientEmail'] ?? '');
$phone      = trim($_POST['clientPhone'] ?? '');
$date       = trim($_POST['meetingDate'] ?? '');
$time       = trim($_POST['selectedTime'] ?? '');
$notes      = trim($_POST['clientMessage'] ?? '');

$clientName = trim($firstName . ' ' . $lastName);

if (
    $firstName === '' ||
    $lastName === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    $phone === '' ||
    $date === '' ||
    $time === ''
) {
    respond(false, 'Mohon lengkapi semua data wajib dan pilih jam meeting.');
}

// Validasi format tanggal dan jam
$dateObject = DateTime::createFromFormat('!Y-m-d', $date);
$timeObject = DateTime::createFromFormat('!H:i', $time);

if (
    !$dateObject ||
    $dateObject->format('Y-m-d') !== $date ||
    !$timeObject ||
    $timeObject->format('H:i') !== $time
) {
    respond(false, 'Format tanggal atau jam tidak valid.');
}

if ($date < date('Y-m-d')) {
    respond(false, 'Tanggal meeting tidak boleh di masa lalu.');
}

if ((int)$timeObject->format('G') < 9 ||
    (int)$timeObject->format('G') > 17 ||
    $timeObject->format('i') !== '00') {
    respond(false, 'Silakan pilih jam meeting antara pukul 09:00–17:00.');
}

if (!in_array((int)$timeObject->format('G'), [9, 11, 13, 15, 17], true)) {
    respond(false, 'Jam meeting harus salah satu dari 09:00, 11:00, 13:00, 15:00, atau 17:00.');
}

// Periksa apakah jam tersebut sudah dipesan atau terkena jeda
$stmt = $conn->prepare(
    "SELECT meeting_id
     FROM meetings
     WHERE meeting_date = ?
       AND ABS(TIME_TO_SEC(TIMEDIFF(meeting_time, ?))) < 3600
     LIMIT 1"
);

if (!$stmt) {
    respond(false, 'Gagal memeriksa jadwal: ' . $conn->error, 500);
}

$stmt->bind_param('ss', $date, $time);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $stmt->close();
    respond(false, 'Jadwal tersebut sudah terisi atau terkena waktu jeda. Silakan pilih jam lain.');
}
$stmt->close();

// Hubungkan reservasi dengan akun yang sedang login jika tersedia
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

// Simpan reservasi ke database
$stmt = $conn->prepare(
    "INSERT INTO meetings
        (client_name, email, phone, meeting_date,
         meeting_time, notes, user_id)
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);

if (!$stmt) {
    respond(false, 'Gagal menyiapkan penyimpanan: ' . $conn->error, 500);
}

$stmt->bind_param(
    'ssssssi',
    $clientName,
    $email,
    $phone,
    $date,
    $time,
    $notes, 
    $userId
);

if ($stmt->execute()) {
    $stmt->close();
    respond(true, 'Reservasi berhasil disimpan! Tim Ever After akan menindaklanjuti permintaan Anda.');
}

$error = $stmt->error;
$stmt->close();

respond(false, 'Reservasi gagal disimpan: ' . $error, 500);
?>
