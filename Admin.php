<?php
require_once 'db.php';

// Proteksi Akses: Hanya Admin yang dapat membuka halaman ini
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: Login.php");
    exit;
}

$flash_success = '';
$flash_error   = '';

// =========================================================
// PROSES POST 1: EDIT TANGGAL & JAM RESERVASI (INDEPENDEN)
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_meeting') {
    $meeting_id   = (int) ($_POST['meeting_id'] ?? 0);
    $meeting_date = trim($_POST['meeting_date'] ?? '');
    $meeting_time = trim($_POST['meeting_time'] ?? '');
    $notes        = trim($_POST['notes'] ?? '');

    if ($meeting_id <= 0) {
        $flash_error = 'ID reservasi tidak valid.';
    } else {
        // Ambil data lama terlebih dahulu agar tanggal atau jam yang tidak diubah tetap bertahan
        $stmt_old = $conn->prepare("SELECT meeting_date, meeting_time, client_name FROM meetings WHERE meeting_id = ? LIMIT 1");
        $old_data = null;
        if ($stmt_old) {
            $stmt_old->bind_param("i", $meeting_id);
            $stmt_old->execute();
            $res_old  = $stmt_old->get_result();
            $old_data = $res_old->fetch_assoc();
            $stmt_old->close();
        }

        if (!$old_data) {
            $flash_error = 'Data reservasi tidak ditemukan di database.';
        } else {
            // Jika input tanggal atau jam kosong, gunakan nilai sebelumnya
            $final_date = !empty($meeting_date) ? $meeting_date : $old_data['meeting_date'];
            $final_time = !empty($meeting_time) ? $meeting_time : substr($old_data['meeting_time'], 0, 5);

            $target_hour    = (int) explode(':', $final_time)[0];
            $time_db_format = sprintf('%02d:00:00', $target_hour);

            $has_conflict  = false;
            $conflict_info = '';

            if (!in_array($target_hour, [9, 11, 13, 15, 17], true)) {
                $has_conflict  = true;
                $conflict_info = 'Jam meeting harus salah satu dari 09:00, 11:00, 13:00, 15:00, atau 17:00.';
            }

            // Cek bentrok & jeda 1 jam dengan reservasi lain di tanggal tujuan
            $stmt_conflict = $conn->prepare("SELECT meeting_time, client_name FROM meetings WHERE meeting_date = ? AND meeting_id != ?");

            if (!$has_conflict && $stmt_conflict) {
                $stmt_conflict->bind_param("si", $final_date, $meeting_id);
                $stmt_conflict->execute();
                $res_conflict = $stmt_conflict->get_result();

                while ($row_c = $res_conflict->fetch_assoc()) {
                    $booked_hour = (int) explode(':', $row_c['meeting_time'])[0];
                    if (abs($target_hour - $booked_hour) <= 1) {
                        $has_conflict  = true;
                        $conflict_info = 'Pukul ' . sprintf('%02d:00', $target_hour) . ' berjarak jeda 1 jam dari sesi pukul ' . sprintf('%02d:00', $booked_hour) . ' (' . $row_c['client_name'] . ').';
                        break;
                    }
                }
                $stmt_conflict->close();
            }

            if ($has_conflict) {
                $flash_error = 'Gagal memperbarui jadwal: ' . $conflict_info;
            } else {
                $stmt_upd = $conn->prepare("UPDATE meetings SET meeting_date = ?, meeting_time = ?, notes = ? WHERE meeting_id = ?");
                if ($stmt_upd) {
                    $stmt_upd->bind_param("sssi", $final_date, $time_db_format, $notes, $meeting_id);
                    if ($stmt_upd->execute()) {
                        $flash_success = 'Reservasi #' . $meeting_id . ' (' . htmlspecialchars($old_data['client_name']) . ') berhasil diperbarui ke tanggal ' . date('d/m/Y', strtotime($final_date)) . ' pukul ' . sprintf('%02d:00', $target_hour) . ' WIB.';
                    } else {
                        $flash_error = 'Gagal menyimpan perubahan jadwal ke database.';
                    }
                    $stmt_upd->close();
                }
            }
        }
    }
}

// =========================================================
// PROSES POST 2: DELETE RESERVASI MEETING (HARD DELETE)
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_meeting') {
    $meeting_id = (int) ($_POST['meeting_id'] ?? 0);

    if ($meeting_id <= 0) {
        $flash_error = 'ID reservasi tidak valid.';
    } else {
        $stmt_del_m = $conn->prepare("DELETE FROM meetings WHERE meeting_id = ?");
        if ($stmt_del_m) {
            $stmt_del_m->bind_param("i", $meeting_id);
            if ($stmt_del_m->execute() && $stmt_del_m->affected_rows > 0) {
                $flash_success = 'Jadwal reservasi #' . $meeting_id . ' telah dihapus permanen dari database.';
            } else {
                $flash_error = 'Reservasi tidak ditemukan atau sudah dihapus.';
            }
            $stmt_del_m->close();
        }
    }
}

// =========================================================
// PROSES POST 3: HARD DELETE AKUN CLIENT DARI DATABASE
// =========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_client') {
    $client_user_id = (int) ($_POST['client_user_id'] ?? 0);

    if ($client_user_id <= 0) {
        $flash_error = 'ID akun klien tidak valid.';
    } else {
        // Hard delete langsung dari tabel users (baris benar-benar hilang permanen dari database)
        $stmt_del_u = $conn->prepare("DELETE FROM users WHERE user_id = ? AND role = 'client'");
        if ($stmt_del_u) {
            $stmt_del_u->bind_param("i", $client_user_id);
            if ($stmt_del_u->execute() && $stmt_del_u->affected_rows > 0) {
                $flash_success = 'Akun klien #' . $client_user_id . ' berhasil dihapus secara permanen dari database.';
            } else {
                $flash_error = 'Akun klien tidak ditemukan atau gagal dihapus.';
            }
            $stmt_del_u->close();
        }
    }
}

// =========================================================
// AMBIL DATA MEETINGS & DATA CLIENT TERBARU DARI DATABASE
// =========================================================
$meetings_list = [];
$booked_map    = [];

$res_meetings = $conn->query("SELECT meeting_id, client_name, email, phone, meeting_date, meeting_time, notes, created_at, user_id FROM meetings ORDER BY meeting_date ASC, meeting_time ASC");
if ($res_meetings) {
    while ($row = $res_meetings->fetch_assoc()) {
        $meetings_list[] = $row;
        $date_key = $row['meeting_date'];
        $hour_int = (int) explode(':', $row['meeting_time'])[0];
        if (!isset($booked_map[$date_key])) {
            $booked_map[$date_key] = [];
        }
        $booked_map[$date_key][] = [
            'meeting_id' => (int) $row['meeting_id'],
            'hour'       => $hour_int,
            'client'     => $row['client_name']
        ];
    }
}

$clients_list = [];
$res_clients = $conn->query("SELECT user_id, full_name, email, phone, created_at FROM users WHERE role = 'client' ORDER BY created_at DESC");
if ($res_clients) {
    while ($row = $res_clients->fetch_assoc()) {
        $clients_list[] = $row;
    }
}

include 'Header.php';
?>

    <!-- ADMIN DASHBOARD PAGE -->
    <main class="admin-page">
        <section class="admin-section">
            <div class="admin-container">

                <!-- 1. HEADER DASHBOARD -->
                <div class="admin-header">
                    <span class="admin-subtitle">Portal Manajemen Internal</span>
                    <h1 class="admin-main-title">Kurasi Jadwal & Klien Ever After.</h1>
                    <p class="admin-desc">
                        Selamat bertugas, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>. Kelola perubahan tanggal atau jam konsultasi, hapus reservasi, serta kelola akun klien di bawah ini.
                    </p>
                </div>

                <!-- 2. RINGKASAN STATISTIK -->
                <div class="admin-stats-grid">
                    <div class="admin-stat-card">
                        <span class="stat-label">01 / TOTAL RESERVASI MEETING</span>
                        <strong class="stat-number"><?php echo count($meetings_list); ?></strong>
                        <span class="stat-meta">Tersimpan di database MySQL</span>
                    </div>
                    <div class="admin-stat-card">
                        <span class="stat-label">02 / ATURAN JEDA SESI</span>
                        <strong class="stat-number">1 Jam</strong>
                        <span class="stat-meta">Otomatis mengunci slot berikutnya</span>
                    </div>
                    <div class="admin-stat-card">
                        <span class="stat-label">03 / TOTAL AKUN KLIEN</span>
                        <strong class="stat-number"><?php echo count($clients_list); ?></strong>
                        <span class="stat-meta">Terdaftar pada tabel users</span>
                    </div>
                </div>

                <!-- NOTIFIKASI FLASH -->
                <?php if (!empty($flash_success)): ?>
                    <div class="admin-alert success"><?php echo htmlspecialchars($flash_success); ?></div>
                <?php endif; ?>
                <?php if (!empty($flash_error)): ?>
                    <div class="admin-alert error"><?php echo htmlspecialchars($flash_error); ?></div>
                <?php endif; ?>

                <!-- 3. TOMBOL TAB PANEL ADMIN -->
                <div class="admin-tabs">
                    <button type="button" class="admin-tab-btn active" data-admin-tab="panel-reservations">
                        01 / Kelola Reservasi Meeting (<?php echo count($meetings_list); ?>)
                    </button>
                    <button type="button" class="admin-tab-btn" data-admin-tab="panel-clients">
                        02 / Kelola Akun Klien (<?php echo count($clients_list); ?>)
                    </button>
                </div>

                <!-- ========================================================= -->
                <!-- PANEL 1: MANAJEMEN RESERVASI (EDIT TANGGAL/JAM & DELETE)  -->
                <!-- ========================================================= -->
                <div class="admin-tab-panel active" id="panel-reservations">
                    <div class="admin-panel-top">
                        <div>
                            <h2 class="admin-panel-title">Daftar Reservasi Konsultasi</h2>
                            <p class="admin-panel-sub">Anda dapat mengubah jam saja tanpa mengubah tanggal, mengubah tanggal tanpa kehilangan jam pilihan, atau menghapus reservasi.</p>
                        </div>
                    </div>

                    <div class="admin-table-wrap">
                        <table class="admin-table" id="meetingsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Klien</th>
                                    <th>Kontak (Email & WA)</th>
                                    <th>Tanggal Meeting</th>
                                    <th>Jam Sesi (+Jeda)</th>
                                    <th>Catatan Klien</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($meetings_list)): ?>
                                    <tr>
                                        <td colspan="7" class="empty-row">Belum ada jadwal reservasi meeting di database.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($meetings_list as $m): 
                                        $hour_val   = (int) explode(':', $m['meeting_time'])[0];
                                        $time_str   = sprintf('%02d:00', $hour_val);
                                        $buffer_str = sprintf('%02d:00', $hour_val + 1);
                                        $date_indo  = date('d/m/Y', strtotime($m['meeting_date']));
                                    ?>
                                        <tr class="meeting-row">
                                            <td class="mono-cell">#<?php echo (int) $m['meeting_id']; ?></td>
                                            <td>
                                                <strong class="client-name-text"><?php echo htmlspecialchars($m['client_name']); ?></strong>
                                            </td>
                                            <td>
                                                <div class="contact-cell">
                                                    <span><?php echo htmlspecialchars($m['email']); ?></span>
                                                    <small><?php echo htmlspecialchars($m['phone']); ?></small>
                                                </div>
                                            </td>
                                            <td class="mono-cell"><?php echo $date_indo; ?></td>
                                            <td>
                                                <div class="time-cell">
                                                    <strong><?php echo $time_str; ?> WIB</strong>
                                                    <small>Jeda: <?php echo $buffer_str; ?></small>
                                                </div>
                                            </td>
                                            <td class="notes-cell">
                                                <?php echo !empty($m['notes']) ? htmlspecialchars($m['notes']) : '<em>Tidak ada catatan</em>'; ?>
                                            </td>
                                            <td>
                                                <div class="admin-action-group">
                                                    <button 
                                                        type="button" 
                                                        class="admin-action-btn edit-meeting-btn"
                                                        data-id="<?php echo (int) $m['meeting_id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($m['client_name'], ENT_QUOTES); ?>"
                                                        data-date="<?php echo htmlspecialchars($m['meeting_date']); ?>"
                                                        data-time="<?php echo $time_str; ?>"
                                                        data-notes="<?php echo htmlspecialchars($m['notes'] ?? '', ENT_QUOTES); ?>"
                                                    >
                                                        Edit Jadwal
                                                    </button>
                                                    <button 
                                                        type="button" 
                                                        class="admin-action-btn danger-btn delete-meeting-btn"
                                                        data-id="<?php echo (int) $m['meeting_id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($m['client_name'], ENT_QUOTES); ?>"
                                                        data-date="<?php echo $date_indo; ?>"
                                                        data-time="<?php echo $time_str; ?>"
                                                    >
                                                        Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- PANEL 2: MANAJEMEN AKUN KLIEN (HARD DELETE USER CLIENT)   -->
                <!-- ========================================================= -->
                <div class="admin-tab-panel" id="panel-clients">
                    <div class="admin-panel-top">
                        <div>
                            <h2 class="admin-panel-title">Daftar Akun Klien Terdaftar</h2>
                            <p class="admin-panel-sub">Menghapus akun di bawah ini akan menghapus data pengguna secara permanen dari tabel <code>users</code> di database.</p>
                        </div>
                    </div>

                    <div class="admin-table-wrap">
                        <table class="admin-table" id="clientsTable">
                            <thead>
                                <tr>
                                    <th>ID User</th>
                                    <th>Nama Lengkap</th>
                                    <th>Alamat Email</th>
                                    <th>Nomor Telepon</th>
                                    <th>Tanggal Registrasi</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($clients_list)): ?>
                                    <tr>
                                        <td colspan="6" class="empty-row">Belum ada akun klien yang terdaftar di database.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($clients_list as $c): ?>
                                        <tr class="client-row">
                                            <td class="mono-cell">#<?php echo (int) $c['user_id']; ?></td>
                                            <td><strong><?php echo htmlspecialchars($c['full_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($c['email']); ?></td>
                                            <td class="mono-cell"><?php echo !empty($c['phone']) ? htmlspecialchars($c['phone']) : '-'; ?></td>
                                            <td class="mono-cell"><?php echo date('d/m/Y H:i', strtotime($c['created_at'])); ?></td>
                                            <td>
                                                <button 
                                                    type="button" 
                                                    class="admin-action-btn danger-btn delete-client-btn"
                                                    data-userid="<?php echo (int) $c['user_id']; ?>"
                                                    data-fullname="<?php echo htmlspecialchars($c['full_name'], ENT_QUOTES); ?>"
                                                    data-email="<?php echo htmlspecialchars($c['email'], ENT_QUOTES); ?>"
                                                >
                                                    Hapus Akun Permanen
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </main>

    <!-- ========================================================= -->
    <!-- MODAL 1: EDIT TANGGAL & JAM RESERVASI (INDEPENDEN)        -->
    <!-- ========================================================= -->
    <div class="admin-modal-overlay" id="editMeetingModal">
        <div class="admin-modal-box">
            <button type="button" class="admin-modal-close" id="closeEditModal">&times;</button>
            <span class="admin-subtitle">Edit Data Reservasi</span>
            <h3 class="admin-modal-title" id="modalClientName">Ubah Jadwal Klien</h3>

            <form action="Admin.php" method="POST" id="rescheduleForm">
                <input type="hidden" name="action" value="update_meeting">
                <input type="hidden" name="meeting_id" id="editMeetingId">

                <div class="admin-form-group">
                    <label for="editMeetingDate">Tanggal Meeting * <small>(Dapat diubah tanpa harus mengganti jam)</small></label>
                    <input type="date" id="editMeetingDate" name="meeting_date" required>
                </div>

                <div class="admin-form-group">
                    <label>Jam Meeting * <small>(Dapat diubah tanpa harus mengganti tanggal)</small></label>
                    <div class="admin-time-grid" id="adminTimeGrid"></div>
                    <input type="hidden" name="meeting_time" id="editMeetingTime" required>
                    <small class="cp-slot-helper" id="adminSlotHelper"></small>
                </div>

                <div class="admin-form-group">
                    <label for="editMeetingNotes">Catatan Klien</label>
                    <textarea id="editMeetingNotes" name="notes" rows="2"></textarea>
                </div>

                <div class="admin-modal-actions">
                    <button type="button" class="admin-cancel-btn" id="cancelEditBtn">Batal</button>
                    <button type="submit" class="btn-outline admin-save-btn">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- MODAL 2: KONFIRMASI HAPUS RESERVASI MEETING               -->
    <!-- ========================================================= -->
    <div class="admin-modal-overlay" id="deleteMeetingModal">
        <div class="admin-modal-box small-box">
            <button type="button" class="admin-modal-close" id="closeDeleteMeetingModal">&times;</button>
            <span class="admin-subtitle">Konfirmasi Hapus Reservasi</span>
            <h3 class="admin-modal-title">Hapus Jadwal Ini?</h3>
            <p class="admin-modal-desc" id="deleteMeetingText">
                Apakah Anda yakin ingin menghapus jadwal reservasi ini dari database?
            </p>

            <form action="Admin.php" method="POST">
                <input type="hidden" name="action" value="delete_meeting">
                <input type="hidden" name="meeting_id" id="deleteMeetingIdInput">

                <div class="admin-modal-actions">
                    <button type="button" class="admin-cancel-btn" id="cancelDeleteMeetingBtn">Batal</button>
                    <button type="submit" class="admin-action-btn danger-btn">Ya, Hapus Reservasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- MODAL 3: KONFIRMASI HARD DELETE AKUN KLIEN                -->
    <!-- ========================================================= -->
    <div class="admin-modal-overlay" id="deleteClientModal">
        <div class="admin-modal-box small-box">
            <button type="button" class="admin-modal-close" id="closeDeleteModal">&times;</button>
            <span class="admin-subtitle">Konfirmasi Hard Delete</span>
            <h3 class="admin-modal-title">Hapus Akun Klien?</h3>
            <p class="admin-modal-desc" id="deleteClientText">
                Apakah Anda yakin ingin menghapus akun klien ini secara permanen dari database?
            </p>

            <form action="Admin.php" method="POST">
                <input type="hidden" name="action" value="delete_client">
                <input type="hidden" name="client_user_id" id="deleteClientIdInput">

                <div class="admin-modal-actions">
                    <button type="button" class="admin-cancel-btn" id="cancelDeleteBtn">Batal</button>
                    <button type="submit" class="admin-action-btn danger-btn">Ya, Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>

    <!-- DATA JSON JADWAL DARI DATABASE UNTUK MODAL ADMIN -->
    <script>
        window.ADMIN_BOOKED_MAP = <?php echo json_encode($booked_map); ?>;
    </script>

<?php include 'Footer.php'; ?>

<script src="Js/Admin.js"></script>
