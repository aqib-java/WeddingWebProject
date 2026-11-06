<head>
    <link rel="stylesheet" href="css/History.css" type="text/css">

</head>
<?php
require_once 'db.php';


// Proteksi Akses:
// 1. Jika belum login, arahkan ke Login.php
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit;
}

// 2. Jika yang mengakses adalah Admin, arahkan langsung ke Admin.php
if (($_SESSION['role'] ?? '') === 'admin') {
    header("Location: Admin.php");
    exit;
}

$user_id    = (int) $_SESSION['user_id'];
$user_email = $_SESSION['email'] ?? '';
$history_list = [];

// Ambil seluruh riwayat reservasi dari tabel meetings berdasarkan user_id (atau email akunnya)
$stmt = $conn->prepare("
    SELECT meeting_id, client_name, meeting_date, meeting_time, notes, created_at 
    FROM meetings 
    WHERE user_id = ? OR email = ? 
    ORDER BY meeting_date DESC, meeting_time DESC
");

if ($stmt) {
    $stmt->bind_param("is", $user_id, $user_email);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $history_list[] = $row;
    }
    $stmt->close();
}

// Array nama bulan Indonesia agar tampilan tanggal lebih elegan
$bulan_indo = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

function formatTanggalIndo($date_str, $bulan_map) {
    $ts = strtotime($date_str);
    if (!$ts) return $date_str;
    $tgl = date('d', $ts);
    $bln = $bulan_map[(int) date('n', $ts)] ?? date('F', $ts);
    $thn = date('Y', $ts);
    return $tgl . ' ' . $bln . ' ' . $thn;
}

include 'Header.php';
?>

    <!-- CLIENT RESERVATION HISTORY PAGE (READ-ONLY) -->
    <main class="history-page">
        <section class="history-section">
            <div class="history-container">

                <!-- 1. HEADER RIWAYAT -->
                <div class="history-header">
                    <span class="history-subtitle">Portal Klien Ever After</span>
                    <h1 class="history-main-title">Riwayat Reservasi Pertemuan.</h1>
                    <p class="history-desc">
                        Halo, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>. Berikut adalah daftar jadwal sesi konsultasi pernikahan yang telah Anda reservasi bersama tim kami.
                    </p>
                </div>

                <!-- 2. RINGKASAN SINGKAT -->
                <div class="history-summary-bar">
                    <div class="summary-item">
                        <span class="summary-label">KLIEN TERDAFTAR</span>
                        <strong class="summary-value"><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-item">
                        <span class="summary-label">EMAIL AKUN</span>
                        <strong class="summary-value"><?php echo htmlspecialchars($_SESSION['email']); ?></strong>
                    </div>
                    <div class="summary-divider"></div>
                    <div class="summary-item">
                        <span class="summary-label">TOTAL RESERVASI</span>
                        <strong class="summary-value"><?php echo count($history_list); ?> Sesi Pertemuan</strong>
                    </div>
                </div>

                <!-- 3. TABEL RIWAYAT RESERVASI (READ-ONLY) -->
                <div class="history-table-wrap">
                    <?php if (empty($history_list)): ?>
                        <div class="history-empty-state">
                            <span class="empty-tag">BELUM ADA JADWAL</span>
                            <h3 class="empty-title">Anda belum memiliki riwayat reservasi pertemuan.</h3>
                            <p class="empty-desc">
                                Silakan kunjungi halaman reservasi kami jika Anda ingin menjadwalkan sesi konsultasi privat bersama spesialis pernikahan Ever After.
                            </p>
                        </div>
                    <?php else: ?>
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Reservasi</th>
                                    <th>Tanggal Pertemuan</th>
                                    <th>Jam Pertemuan</th>
                                    <th>Durasi Sesi</th>
                                    <th>Catatan Rencana Pernikahan</th>
                                    <th>Waktu Dipesan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                foreach ($history_list as $item): 
                                    $jam_int     = (int) explode(':', $item['meeting_time'])[0];
                                    $jam_mulai   = sprintf('%02d:00', $jam_int);
                                    $jam_selesai = sprintf('%02d:00', $jam_int + 1);
                                    $tgl_meeting = formatTanggalIndo($item['meeting_date'], $bulan_indo);
                                    $tgl_dibuat  = date('d/m/Y, H:i', strtotime($item['created_at']));
                                ?>
                                    <tr>
                                        <td class="mono-col"><?php echo str_pad($no++, 2, '0', STR_PAD_LEFT); ?></td>
                                        <td>
                                            <strong class="client-col"><?php echo htmlspecialchars($item['client_name']); ?></strong>
                                        </td>
                                        <td>
                                            <span class="date-col"><?php echo htmlspecialchars($tgl_meeting); ?></span>
                                        </td>
                                        <td>
                                            <span class="time-pill"><?php echo $jam_mulai; ?> - <?php echo $jam_selesai; ?> WIB</span>
                                        </td>
                                        <td class="mono-col">1 Jam Eksklusif</td>
                                        <td class="notes-col">
                                            <?php echo !empty($item['notes']) ? htmlspecialchars($item['notes']) : '<em>Tidak ada catatan tambahan</em>'; ?>
                                        </td>
                                        <td class="mono-col muted-col"><?php echo $tgl_dibuat; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

            </div>
        </section>
    </main>

<?php include 'Footer.php'; ?>