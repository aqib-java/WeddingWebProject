<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php?auth=required");
    exit;
}

$booking_success = '';
$booking_error = '';
date_default_timezone_set('Asia/Jakarta');

$default_date = $_POST['meetingDate'] ?? date('Y-m-d');

// Isi otomatis data diri jika user sedang login
$prefill_first = '';
$prefill_last = '';
$prefill_email = $_SESSION['email'] ?? '';
$prefill_phone = $_SESSION['phone'] ?? '';

if (!empty($_SESSION['full_name'])) {
    $parts = explode(' ', trim($_SESSION['full_name']), 2);
    $prefill_first = $parts[0] ?? '';
    $prefill_last = $parts[1] ?? '';
}

// PROSES SIMPAN RESERVASI KE TABEL MEETINGS (MYSQL)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_booking'])) {
    $first_name = trim($_POST['firstName'] ?? '');
    $last_name = trim($_POST['lastName'] ?? '');
    $client_name = trim($first_name . ' ' . $last_name);
    $email = trim($_POST['clientEmail'] ?? '');
    $phone = trim($_POST['clientPhone'] ?? '');
    $meeting_date = trim($_POST['meetingDate'] ?? '');
    $selected_hr = trim($_POST['selectedTime'] ?? ''); // Contoh: '13:00'
    $notes = trim($_POST['clientMessage'] ?? '');
    $user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;

    $target_hour = (int) explode(':', $selected_hr)[0];

    if (empty($first_name) || empty($email) || empty($phone) || empty($meeting_date) || empty($selected_hr)) {
        $booking_error = 'Mohon lengkapi seluruh kolom wajib dan klik salah satu slot jam yang tersedia.';
    } elseif (!in_array($target_hour, [9, 11, 13, 15, 17], true)) {
        $booking_error = 'Jam meeting harus salah satu dari 09:00, 11:00, 13:00, 15:00, atau 17:00.';
    } else {
        $time_db = sprintf('%02d:00:00', $target_hour);

        // Validasi Bentrok & Jeda 1 Jam langsung dari tabel meetings di MySQL

        // Validasi jadwal dan jeda 1 jam dari database
        $stmt_chk = $conn->prepare(
            "SELECT meeting_time, email, user_id
             FROM meetings
             WHERE meeting_date = ?"
        );

        $conflict = false;
        $already_booked_today = false;

        if (!$stmt_chk) {
            $booking_error = 'Gagal memeriksa jadwal: ' . $conn->error;
        } else {
            $stmt_chk->bind_param("s", $meeting_date);
            $stmt_chk->execute();
            $res_chk = $stmt_chk->get_result();

            while ($r = $res_chk->fetch_assoc()) {
                // Cek apakah klien yang sama sudah booking pada tanggal ini
                if ($user_id !== null) {
                    $same_client = (
                        $r['user_id'] !== null &&
                        (int) $r['user_id'] === $user_id
                    );
                } else {
                    $same_client = (
                        strtolower(trim($r['email'])) ===
                        strtolower(trim($email))
                    );
                }

                if ($same_client) {
                    $already_booked_today = true;
                }

                // Cek bentrok: jam yang sama, satu jam sebelum, atau sesudah
                $booked_hr = (int) explode(':', $r['meeting_time'])[0];

                if (abs($target_hour - $booked_hr) <= 1) {
                    $conflict = true;
                }
            }

            $stmt_chk->close();

            if ($already_booked_today) {
                $booking_error =
                    'Anda sudah memiliki jadwal meeting pada tanggal tersebut. Silakan pilih tanggal lain.';
            } elseif ($conflict) {
                $booking_error =
                    'Slot jam tersebut sudah terisi atau terkena jeda 1 jam. Silakan pilih jam lain.';
            } else {
                $stmt_ins = $conn->prepare("INSERT INTO meetings (client_name, email, phone, meeting_date, meeting_time, notes, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if ($stmt_ins) {
                    $stmt_ins->bind_param("ssssssi", $client_name, $email, $phone, $meeting_date, $time_db, $notes, $user_id);
                    if ($stmt_ins->execute()) {
                        $buffer_next = sprintf('%02d:00', $target_hour + 1);
                        $booking_success = 'Terima kasih, <strong>' . htmlspecialchars($client_name) . '</strong>! Reservasi meeting Anda pada <strong>' . date('d/m/Y', strtotime($meeting_date)) . ' pukul ' . htmlspecialchars($selected_hr) . ' WIB</strong> berhasil disimpan. Pukul <strong>' . $buffer_next . '</strong> otomatis dikunci sebagai waktu jeda 1 jam.';
                    } else {
                        $booking_error = 'Gagal menyimpan reservasi ke database. Silakan coba lagi.';
                    }
                    $stmt_ins->close();
                }
            }
        }
    }
}

// AMBIL SELURUH JADWAL DARI TABEL MEETINGS UNTUK STATUS JAM DI FRONTEND
$db_booked_slots = [];
$res_all = $conn->query("SELECT meeting_date, meeting_time FROM meetings");
if ($res_all) {
    while ($row = $res_all->fetch_assoc()) {
        $d = $row['meeting_date'];
        $h = sprintf('%02d:00', (int) explode(':', $row['meeting_time'])[0]);
        if (!isset($db_booked_slots[$d])) {
            $db_booked_slots[$d] = [];
        }
        if (!in_array($h, $db_booked_slots[$d], true)) {
            $db_booked_slots[$d][] = $h;
        }
    }
}

include 'Header.php';
?>

<!-- CONTACT & APPOINTMENT RESERVATION PAGE -->
<main class="contact-page">
    <section class="cp-section">
        <div class="cp-split-container">

            <!-- KOLOM KIRI: INFORMASI VENUE & ATURAN JEDA 1 JAM -->
            <div class="cp-left-col">
                <span class="cp-subtitle">Hubungi Kami</span>

                <h1 class="cp-main-title">
                    Untuk pertanyaan atau memulai rencana perayaan Anda, silakan hubungi kami.
                </h1>

                <div class="cp-info-list">
                    <p>Jl. Kemang Raya No. 12</p>
                    <p>Jakarta Selatan, 12730</p>
                    <p><a href="mailto:halo@everafter.com">halo@everafter.com</a></p>
                    <p><a href="tel:0214567890">021-456-7890</a></p>
                </div>

                <div class="cp-policy-box">
                    <span class="cp-policy-title">KETENTUAN SESI KONSULTASI PRIVAT</span>
                    <p class="cp-policy-desc">
                        Setiap sesi pertemuan berlangsung secara eksklusif selama <strong>1 jam</strong>. Untuk menjaga
                        kenyamanan dan privasi antar pasangan, sistem menerapkan <strong>waktu jeda 1 jam</strong>
                        setelah sesi berakhir sebelum jadwal berikutnya dapat dipesan.
                    </p>
                </div>
            </div>

            <!-- KOLOM KANAN: FORMULIR RESERVASI DATABASE MYSQL -->
            <div class="cp-right-col">
                <form class="cp-form" id="appointmentForm" action="Contact.php" method="POST">
                    <input type="hidden" name="submit_booking" value="1">

                    <?php if (!empty($booking_success)): ?>
                        <div class="cp-feedback success show"><?php echo $booking_success; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($booking_error)): ?>
                        <div class="cp-feedback error show"><?php echo htmlspecialchars($booking_error); ?></div>
                    <?php endif; ?>

                    <!-- Baris 1: Nama Depan & Belakang -->
                    <div class="cp-form-row split">
                        <div class="cp-form-group">
                            <label for="firstName">Nama Depan *</label>
                            <input type="text" id="firstName" name="firstName"
                                value="<?php echo htmlspecialchars($prefill_first); ?>" required
                                placeholder="Cth: Romeo">
                        </div>
                        <div class="cp-form-group">
                            <label for="lastName">Nama Belakang *</label>
                            <input type="text" id="lastName" name="lastName"
                                value="<?php echo htmlspecialchars($prefill_last); ?>" required
                                placeholder="Cth: Montague">
                        </div>
                    </div>

                    <!-- Baris 2: Email & Telepon -->
                    <div class="cp-form-row split">
                        <div class="cp-form-group">
                            <label for="clientEmail">Email *</label>
                            <input type="email" id="clientEmail" name="clientEmail"
                                value="<?php echo htmlspecialchars($prefill_email); ?>" required
                                placeholder="nama@email.com">
                        </div>
                        <div class="cp-form-group">
                            <label for="clientPhone">Nomor WhatsApp *</label>
                            <input type="tel" id="clientPhone" name="clientPhone"
                                value="<?php echo htmlspecialchars($prefill_phone); ?>" required
                                placeholder="0812-xxxx-xxxx">
                        </div>
                    </div>

                    <!-- Baris 3: Pilih Tanggal Appointment -->
                    <div class="cp-form-row">
                        <div class="cp-form-group">
                            <label for="meetingDate">Tanggal Appointment Meeting *</label>
                            <input type="date" id="meetingDate" name="meetingDate"
                                value="<?php echo htmlspecialchars($default_date); ?>"
                                min="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <!-- Baris 4: Pilih Jam Meeting (Murni dari Database MySQL) -->
                    <div class="cp-form-row">
                        <div class="cp-form-group">
                            <div class="cp-slot-header">
                                <label>Pilih Jam Meeting * <span id="selectedDateLabel"></span></label>
                            </div>

                            <div class="cp-slot-legend">
                                <span><i class="dot available"></i> Tersedia</span>
                                <span><i class="dot booked"></i> Terisi</span>
                                <span><i class="dot buffer"></i> Jeda 1 Jam</span>
                            </div>

                            <div class="cp-time-grid" id="timeSlotGrid"></div>

                            <input type="hidden" id="selectedTime" name="selectedTime" required>
                            <small class="cp-slot-helper" id="slotHelperText">
                                Silakan klik salah satu jam yang tersedia di atas.
                            </small>
                        </div>
                    </div>

                    <!-- Baris 5: Pesan / Catatan -->
                    <div class="cp-form-row">
                        <div class="cp-form-group">
                            <label for="clientMessage">Tulis Pesan atau Rencana Pernikahan</label>
                            <textarea id="clientMessage" name="clientMessage" rows="3"
                                placeholder="Ceritakan konsep pernikahan atau perkiraan jumlah tamu Anda..."><?php echo isset($_GET['tasting']) ? 'Saya ingin menjadwalkan Private Food Tasting untuk menu: ' . htmlspecialchars($_GET['tasting']) : ''; ?></textarea>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="cp-submit-wrap">
                        <button type="submit" class="cp-submit-btn">Jadwalkan Pertemuan</button>
                    </div>

                </form>
            </div>

        </div>
    </section>
</main>

<!-- Data Jadwal dari Database MySQL -->
<script>
    window.DB_BOOKED_SLOTS = <?php echo json_encode($db_booked_slots); ?>;
</script>

<?php include 'Footer.php'; ?>

<script src="Js/contact.js"></script>